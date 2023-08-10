<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use ApiGratis\ApiBrasil;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class Checkout extends BaseController {

    private $usuario;
    private $formaPagamentoModel;
    private $bairroModel;
    private $pedidoModel;

    public function __construct() {
        $this->usuario = service('autenticacao')->pegaUsuarioLogado();
        $this->formaPagamentoModel = new \App\Models\FormaPagamentoModel();
        $this->bairroModel = new \App\Models\BairroModel();
        $this->pedidoModel = new \App\Models\PedidoModel();
    }

    public function index() {

        if (!session()->has('carrinho') || count(session()->get('carrinho')) < 1) {

            return redirect()->to(site_url('carrinho'));
        }

        $data = [
            'titulo' => 'Finalizar pedido',
            'carrinho' => session()->get('carrinho'),
            'formas' => $this->formaPagamentoModel->where('ativo', true)->findAll(),
            'bairros' => $this->bairroModel->where('ativo', true)->findAll()
        ];

        return view('Checkout/index', $data);
    }

    public function consultaCep() {

        if (!$this->request->isAJAX()) {
            return redirect()->to(site_url('/'));
        }

        $validacao = service('validation');

        $validacao->setRule('cep', 'CEP', 'required|exact_length[9]');

        if (!$validacao->withRequest($this->request)->run()) {

            $retorno['erro'] = '<span class "text-danger small">' . $validacao->getError() . '</span>';

            return $this->response->setJSON($retorno);
        }

        $cep = str_replace("-", "", $this->request->getGet('cep'));

        helper('consulta_cep');

        $consulta = consultaCep($cep);

        if (isset($consulta->erro) && !isset($consulta->cep)) {
            $retorno['erro'] = '<span class="text-danger small">Informe um CEP válido</span>';

            return $this->response->setJSON($retorno);
        }

        if ($consulta->bairro == null) {
            $consulta->bairro = $this->bairroModel->select('bairros.slug, bairros.nome')->where('id', $this->request->getGet('bairro'))->where('ativo', true)->first()->toArray();
        }

        $bairro = $this->bairroModel->select('nome,valor_entrega, slug')->where('slug', $consulta->bairro['slug'])->where('ativo', true)->first();

        if ($cep != '62850000') {
            if ($consulta->bairro == null || $bairro == null) {

                $retorno['erro'] = '<span class="text-danger small">Não atendemos o Bairro: '
                        . esc($consulta->bairro)
                        . ' - ' . esc($consulta->localidade)
                        . ' - CEP ' . esc($consulta->cep)
                        . ' - ' . esc($consulta->uf)
                        . '</span>';

                return $this->response->setJSON($retorno);
            }
        }

        $retorno['valor_entrega'] = 'R$ ' . esc(number_format($bairro->valor_entrega, 2));

        $retorno['bairro'] = '<span class="text">Valor de entrega para o Bairro: '
                . esc($consulta->bairro['nome'])
                . ' - ' . esc($consulta->localidade)
                . ' - CEP ' . esc($consulta->cep)
                . ' - ' . esc($consulta->uf)
                . ' - R$ ' . esc(number_format($bairro->valor_entrega, 2))
                . '</span>';

        if ($consulta->logradouro != "") {
            $retorno['endereco'] = esc($consulta->bairro['nome'])
                    . ' - ' . esc($consulta->localidade)
                    . ' - ' . esc($consulta->logradouro)
                    . ' - CEP ' . esc($consulta->cep)
                    . ' - ' . esc($consulta->uf);
        } else {
            $retorno['endereco'] = esc($consulta->bairro['nome'])
                    . ' - ' . esc($consulta->localidade)
                    . ' - CEP ' . esc($consulta->cep)
                    . ' - ' . esc($consulta->uf);
        }
        $retorno['logradouro'] = $consulta->logradouro;

        $retorno['bairro_slug'] = $bairro->slug;

        $retorno['total'] = number_format($this->somaValorProdutosCarrinho() + $bairro->valor_entrega, 2);

        session()->set('endereco_entrega', $retorno['endereco']);

        return $this->response->setJSON($retorno);
    }

    public function processar() {

        if ($this->request->getMethod() === 'post') {
            $checkoutPost = $this->request->getPost('checkout');
            $validacao = service('validation');


            $validacao->setRules([
                'checkout.rua' => ['label' => 'Endereço', 'rules' => 'required|string|max_length[50]'],
                'checkout.numero' => ['label' => 'Numero', 'rules' => 'required|max_length[30]'],
                'checkout.referencia' => ['label' => 'Ponto de Referência', 'rules' => 'required|string'],
                'checkout.forma_id' => ['label' => 'Forma de pagamento na entrega', 'rules' => 'required|integer'],
                'checkout.bairro_slug' => ['label' => 'Endereço de entrega', 'rules' => 'required|string|max_length[30]'],
            ]);

            if (!$validacao->withRequest($this->request)->run()) {

                session()->remove('endereco_entrega');
                return redirect()->back()
                                ->with('errors_model', $validacao->getErrors())
                                ->with('atencao', 'Por favor verifique os erros abaixo e tente novamente')
                                ->withInput();
            }

            $forma = $this->formaPagamentoModel->where('id', $checkoutPost['forma_id'])->where('ativo', true)->first();

            if ($forma == null) {
                session()->remove('endereco_entrega');
                return redirect()->back()
                                ->with('atencao', "Por favor escolha a <strong>Forma de pagamento na Entrega</strong> e tente novamente");
            }

            $bairro = $this->bairroModel->where('slug', $checkoutPost['bairro_slug'])->where('ativo', true)->first();

            if ($bairro == null) {
                session()->remove('endereco_entrega');
                return redirect()->back()
                                ->with('atencao', "Por favor escolha o seu <strong>Bairro</strong> ao calcular a taxa de entrega");
            }

            if (!session()->get('endereco_entrega')) {
                return redirect()->back()
                                ->with('atencao', "Por favor informe o seu <strong>CEP</strong> e calcule a taxa de entrega novamente");
            }

            $pedido = new \App\Entities\Pedido();

            $pedido->usuario_id = $this->usuario->id;
            $pedido->codigo = $this->pedidoModel->geraCodigoPedido();
            $pedido->forma_pagamento = $forma->nome;
            $pedido->produtos = serialize(session()->get('carrinho'));
            $pedido->valor_produtos = number_format($this->somaValorProdutosCarrinho(), 2);
            $pedido->valor_entrega = number_format($bairro->valor_entrega, 2);
            $pedido->valor_pedido = number_format($pedido->valor_produtos + $pedido->valor_entrega, 2);
            $pedido->endereco_entrega = session()->get('endereco_entrega') . ' - ' . $checkoutPost['rua'] . ' - Número ' . $checkoutPost['numero'];

            if ($forma->id == 1) {

                if (isset($checkoutPost['sem_troco'])) {

                    $pedido->observacoes = 'Ponto de referência: ' . $checkoutPost['referencia'] . ' - Número: ' . $checkoutPost['numero'] . '. - Você informou que não precisa de troco';
                }

                if (isset($checkoutPost['troco_para'])) {


                    $trocoPara = str_replace(',', '', $checkoutPost['troco_para']);

                    if ($trocoPara < 1) {

                        return redirect()->back()->with('atencao', 'Ao escolher que <strong>Precisa de troco</strong>, por favor informe um valor maior que 0');
                    }
                    $pedido->observacoes = 'Ponto de referência: ' . $checkoutPost['referencia'] . ' - Número: ' . $checkoutPost['numero'] . '. - Você informou que precisa de troco para: R$ ' . number_format($trocoPara, 2);
                }
            } else {

                $pedido->observacoes = 'Ponto de referência: ' . $checkoutPost['referencia'] . ' - Número: ' . $checkoutPost['numero'];
            }

            $this->pedidoModel->save($pedido);

            $pedido->usuario = $this->usuario;

            $this->enviaMensagemPedidoRealizadoWhats($pedido, false);

            session()->remove('carrinho');
            session()->remove('endereco_entrega');

            return redirect()->to(site_url("checkout/sucesso/$pedido->codigo"));
        } else {
            return redirect()->back();
        }
    }
    
    public function processar_retirada() {

        if ($this->request->getMethod() === 'post') {
            
            $checkoutPost = $this->request->getPost('checkout');
            
            $forma = $this->formaPagamentoModel->where('id', $checkoutPost['forma_id_retirada'])->where('ativo', true)->first();

            if ($forma == null) {
                return redirect()->back()
                                ->with('atencao', "Por favor escolha a <strong>Forma de pagamento na Entrega</strong> e tente novamente");
            }

            $bairro = 'jardim-primavera';

            $pedido = new \App\Entities\Pedido();

            $pedido->usuario_id = $this->usuario->id;
            $pedido->codigo = $this->pedidoModel->geraCodigoPedido();
            $pedido->forma_pagamento = $forma->nome;
            $pedido->produtos = serialize(session()->get('carrinho'));
            $pedido->valor_produtos = number_format($this->somaValorProdutosCarrinho(), 2);
            $pedido->valor_entrega = 0;
            $pedido->valor_pedido = number_format($pedido->valor_produtos + $pedido->valor_entrega, 2);
            $pedido->endereco_entrega = 'Rua Joana Darc - 321 - Jardim Primavera';
            
            if ($forma->id == 1) {
                $sem_troco = $this->request->getPost('sem_troco_retirada');
                if ($sem_troco != null) {

                    $pedido->observacoes = 'Ponto de referência para retirada: Delicias da Auzi - Número: 321 - Você informou que não precisa de troco';
                }

                if (isset($checkoutPost['troco_para_retirada'])) {


                    $trocoPara = str_replace(',', '', $checkoutPost['troco_para_retirada']);

                    if ($trocoPara < 1) {

                        return redirect()->back()->with('atencao', 'Ao escolher que <strong>Precisa de troco</strong>, por favor informe um valor maior que 0');
                    }
                    $pedido->observacoes = 'Ponto de referência para retirada: Delicias da Auzi - Você informou que precisa de troco para: R$ ' . number_format($trocoPara, 2);
                }
            } else {

                $pedido->observacoes = 'Ponto de referência para retirada: Delicias da Auzi - número: 321';
            }

            $this->pedidoModel->save($pedido);

            $pedido->usuario = $this->usuario;
            
            $this->enviaMensagemPedidoRealizadoWhats($pedido , true);

            session()->remove('carrinho');
            
            return redirect()->to(site_url("checkout/sucesso_retirada/$pedido->codigo"));
        } else {
            return redirect()->back();
        }
    }

    public function sucesso($codigoPedido = null) {

        $pedido = $this->buscaPedidoOu404($codigoPedido);

        $data = [
            'titulo' => "Pedido $codigoPedido realizado com sucesso",
            'pedido' => $pedido,
            'produtos' => unserialize($pedido->produtos),
        ];
        
        return view('Checkout/sucesso', $data);
    }
    public function sucesso_retirada($codigoPedido = null) {

        $pedido = $this->buscaPedidoOu404($codigoPedido);

        $data = [
            'titulo' => "Pedido $codigoPedido realizado com sucesso",
            'pedido' => $pedido,
            'produtos' => unserialize($pedido->produtos),
        ];
        
        return view('Checkout/sucesso_retirada', $data);
    }

    private function somaValorProdutosCarrinho() {

        $produtosCarrinho = array_map(function($linha) {

            return $linha['quantidade'] * $linha['preco'];
        }, session()->get('carrinho'));


        return array_sum($produtosCarrinho);
    }

    private function enviaMensagemPedidoRealizadoWhats(object $pedido, $retirada = false) {

        $produtos_pedido = session()->get('carrinho');
        $produtos_mensagem = "*Descrição do pedido:* \n";

        foreach ($produtos_pedido as $produto) {
            $produtos_mensagem = $produtos_mensagem . '-----------------------------------\n ';
            $produtos_mensagem = $produtos_mensagem . '*Nome do produto:* ' . $produto['nome'] . ', \n ';
            $produtos_mensagem = $produtos_mensagem . '*Preço:* R$ ' . $produto['preco'] . ', \n ';
            $produtos_mensagem = $produtos_mensagem . '*Quantidade:* ' . $produto['quantidade'] . ', \n ';
            $produtos_mensagem = $produtos_mensagem . '*Tamanho:* ' . $produto['tamanho'] . ' \n ';
        }

        $client = new Client(['verify' => false]);
        $telefone_usuario = str_replace('(', '', $pedido->usuario->telefone);
        $telefone_usuario = str_replace(')', '', $telefone_usuario);
        $telefone_usuario = str_replace(' ', '', $telefone_usuario);
        $telefone_usuario = str_replace('-', '', $telefone_usuario);
        $codigo_pedido = esc($pedido->codigo);
        $nome_cliente = esc($pedido->usuario->nome);
        if($retirada == false){
        $mensagem = "Pedido *$codigo_pedido* realizado com sucesso!\n" .
                "Olá *$nome_cliente*, recebemos o seu pedido *$codigo_pedido* \n" .
                "Estamos acelerando do lado de cá para que o seu pedido fique pronto rapidinho. Logo logo ele sairá para entrega.\n" .
                "Não se preocupe, quando isso acontecer, avisaremos você por mensagem, beleza ?\n " .
                "\n \n" .
                "$produtos_mensagem" .
                "-----------------------------------\n " .
                "*Valor do pedido: R$ $pedido->valor_pedido* \n" .
                "-----------------------------------\n " .
                "\n \n" .
                "*Endereço de entrega:* \n " .
                "$pedido->endereco_entrega" .
                "\n \n" .
                "*Observações do pedido:* \n" .
                "$pedido->observacoes";

        }
        else{
            $mensagem = "Pedido *$codigo_pedido* realizado com sucesso!\n" .
                "Olá *$nome_cliente*, recebemos o seu pedido *$codigo_pedido* \n" .
                "Estamos acelerando do lado de cá para que o seu pedido fique pronto rapidinho. Logo logo ele sairá para entrega.\n" .
                "Não se preocupe, quando isso acontecer, avisaremos você por mensagem, beleza ?\n " .
                "\n \n" .
                "$produtos_mensagem" .
                "-----------------------------------\n " .
                "*Valor do pedido: R$ $pedido->valor_pedido* \n" .
                "-----------------------------------\n " .
                "\n \n" .
                "*Endereço de retirada:* \n " .
                "$pedido->endereco_entrega" .
                "\n \n" .
                "*Observações do pedido:* \n" .
                "$pedido->observacoes";
        }
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $body = "{
            'apikey': 'a581c6d8-bd39-4c8f-92fa-db258986c4a5',
            'phone_number': '5585991674535',
            'contact_phone_number': '$telefone_usuario',
            'message_custom_id': 'DeliciasDaAuziDelivery',
            'message_type': 'text',
            'message_body': '$mensagem',
            'check_status': '1'
          }";
        $request = new \GuzzleHttp\Psr7\Request('POST', 'https://app.whatsgw.com.br/api/WhatsGw/Send', $headers, $body);
        $res = $client->sendAsync($request)->wait();
    }

    private function enviaMensagemPedidoRealizado(object $pedido) {

        $email = service('email');

        $email->setFrom('no-reply@deliciasdaauzidelivery.com', 'Delicias da Auzi Delivery');
        $email->setTo($this->usuario->email);

        $email->setSubject("Pedido $pedido->codigo realizado com sucesso");

        $mensagem = view('Checkout/pedido_mensagem', ['pedido' => $pedido]);

        $email->setMessage($mensagem);

        $email->send();
    }

    private function buscaPedidoOu404(string $codigoPedido = null) {
        if (!$codigoPedido || !$pedido = $this->pedidoModel
                ->where('codigo', $codigoPedido)
                ->where('usuario_id', $this->usuario->id)
                ->first()) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Não encontramos o pedido $codigoPedido");
        }
        return $pedido;
    }

}
