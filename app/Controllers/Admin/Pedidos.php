<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use ApiGratis\ApiBrasil;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class Pedidos extends BaseController {

    private $pedidoModel;
    private $entregadorModel;

    public function __construct() {
        $this->pedidoModel = new \App\Models\PedidoModel();
        $this->entregadorModel = new \App\Models\EntregadorModel();
    }

    public function index() {
        $data = [
            'titulo' => 'Pedidos realizados',
            'pedidos' => $this->pedidoModel->listaTodosOsPedidos(),
            'pager' => $this->pedidoModel->pager
        ];
        return view('Admin/Pedidos/index', $data);
    }

    public function procurar() {

        if (!$this->request->isAJAX()) {
            exit('Página não encontrada');
        }

        $pedidos = $this->pedidoModel->procurar($this->request->getGet('term'));

        $retorno = [];

        foreach ($pedidos as $pedido) {
            $data['value'] = $pedido->codigo;

            $retorno[] = $data;
        }

        return $this->response->setJSON($retorno);
    }

    public function show($codigoPedido = null) {

        $pedido = $this->pedidoModel->buscaPedidoOu404($codigoPedido);
        $data = [
            'titulo' => "Detalhando o pedido $pedido->codigo",
            'pedido' => $pedido,
        ];
        return view('Admin/Pedidos/show', $data);
    }

    public function editar($codigoPedido = null) {

        $pedido = $this->pedidoModel->buscaPedidoOu404($codigoPedido);

        if ($pedido->situacao == 2) {
            return redirect()->back()->with('info', 'Esse pedido já foi entregue, e portanto não é possível editá-lo');
        }
        if ($pedido->situacao == 3) {
            return redirect()->back()->with('info', 'Esse pedido já foi cancelado, e portanto não é possível editá-lo');
        }

        $data = [
            'titulo' => "Editando o pedido $pedido->codigo",
            'pedido' => $pedido,
            'entregadores' => $this->entregadorModel->select('id, nome')->where('ativo', true)->findAll(),
        ];
        return view('Admin/Pedidos/editar', $data);
    }

    public function atualizar($codigoPedido = null) {

        if ($this->request->getMethod() === 'post') {

            $pedido = $this->pedidoModel->buscaPedidoOu404($codigoPedido);

            if ($pedido->situacao == 2) {
                return redirect()->back()->with('info', 'Esse pedido já foi entregue, e portanto não é possível editá-lo');
            }
            if ($pedido->situacao == 3) {
                return redirect()->back()->with('info', 'Esse pedido já foi cancelado, e portanto não é possível editá-lo');
            }

            $pedidoPost = $this->request->getPost();

            if (!isset($pedidoPost['situacao'])) {

                return redirect()->back()->with('atencao', 'Escolha a situação do pedido');
            }
            if ($pedidoPost['situacao'] == 1) {

                if (strlen($pedidoPost['entregador_id']) < 1) {
                    return redirect()->back()->with('atencao', 'Se o pedido está saindo para entrega, por favor escolha o entregador');
                }
            }

            if ($pedido->situacao == 0) {

                if ($pedidoPost['situacao'] == 2) {
                    return redirect()->back()->with('atencao', 'O pedido não pode ter sido entregue, pois ainda não <strong>Saiu para entrega</strong>');
                }
            }

            if ($pedidoPost['situacao'] != 1) {
                unset($pedidoPost['entregador_id']);
            }
            if ($pedidoPost['situacao'] == 3) {
                $pedidoPost['entregador_id'] = null;
            }

            $situacaoAnteriorPedido = $pedido->situacao;

            $pedido->fill($pedidoPost);

            if (!$pedido->hasChanged()) {
                return redirect()->back()->with('info', 'Não há novas informações para atualizar');
            }



            if ($this->pedidoModel->save($pedido)) {

                if ($pedido->situacao == 1) {

                    $entregador = $this->entregadorModel->find($pedido->entregador_id);

                    $pedido->entregador = $entregador;

                    $this->enviaMensagemPedidoSaiuEntrega($pedido);
                }
                if ($pedido->situacao == 2) {


                    $this->enviaMensagemPedidoFoiEntregueWhats($pedido);

                    $this->insereProdutosDoPedido($pedido);
                }

                if ($pedido->situacao == 3) {


                    $this->enviaMensagemPedidoFoiCanceladoWhats($pedido);

                    if ($situacaoAnteriorPedido == 1) {

                        session()->setFlashdata('atencao', 'Administrador, esse pedido está em rota de entrega. Por favor entre em contato com o entregador para que ele retorne para a loja');
                    }
                }

                return redirect()->to(site_url("admin/pedidos/show/$codigoPedido"))->with('sucesso', 'Pedido atualizado com sucesso!');
            } else {
                return redirect()->back()
                                ->with('errors_model', $this->pedidoModel->errors())
                                ->with('atencao', 'Por favor verifique os erros abaixo');
            }
        } else {
            return redirect()->back();
        }
    }
    
    public function excluir($codigoPedido = null) {

        $pedido = $this->pedidoModel->buscaPedidoOu404($codigoPedido);
        
        if($pedido->situacao < 2){
            
            return redirect()->back()->with('info', 'Apenas pedidos <strong>Entregues ou Cancelados</strong> podem ser excluídos!');
            
        }
        
        if($this->request->getMethod() === 'post'){
            
            $this->pedidoModel->delete($pedido->id);
            
            return redirect()->to(site_url('admin/pedidos'))->with('sucesso', 'O pedido foi excluído com sucesso');
        }
        
        $data = [
            'titulo' => "Excluindo o pedido $pedido->codigo",
            'pedido' => $pedido,
        ];
        return view('Admin/Pedidos/excluir', $data);
    }

    public function desfazerExclusao($codigoPedido = null) {

        $pedido = $this->pedidoModel->buscaPedidoOu404($codigoPedido);

        if ($pedido->deletado_em == null) {
            return redirect()->back()->with('info', "Apenas pedidos excluídos podem ser recuperados");
        }

        if ($this->pedidoModel->desfazerExclusao($pedido->id)) {
            return redirect()->back()->with('sucesso', "Exclusão desfeita com sucesso!");
        } else {
            return redirect()->back()
                            ->with('errors_model', $this->pedidoModel->errors())
                            ->with('atencao', 'Por favor verifique os erros abaixo')
                            ->withInput();
        }
    }
    
    private function enviaMensagemPedidoSaiuEntregaWhats(object $pedido) {

        $entregador = $pedido->entregador;
        $client = new Client(['verify' => false]);
        $telefone_usuario = str_replace('(', '', $pedido->telefone);
        $telefone_usuario = str_replace(')', '', $telefone_usuario);
        $telefone_usuario = str_replace(' ', '', $telefone_usuario);
        $telefone_usuario = str_replace('-', '', $telefone_usuario);
        $codigo_pedido = esc($pedido->codigo);
        $nome_cliente = esc($pedido->nome);
        $mensagem = "Olá *$nome_cliente*, o seu pedido *$codigo_pedido* saiu para entrega! \n" .
                "A forma de pagamento na entrega é: *$pedido->forma_pagamento* \n" .
                "Verificamos aqui que o endereço de entrega é: *$pedido->endereco_entrega* \n " .
                "Observações do pedido: *$pedido->observacoes* \n " .
                "\n \n" .
                "-----------------------------------\n " .
                "O *$entregador->nome* chegará pilotando *$entregador->veiculo* \n "
                . "que tem a placa *$entregador->placa* \n" .
                "-----------------------------------\n " .
                "Bom apetite! ";


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

    private function enviaMensagemPedidoSaiuEntrega(object $pedido) {

        $email = service('email');

        $email->setFrom('no-reply@deliciasdaauzidelivery.com', 'Delicias da Auzi Delivery');
        $email->setTo($pedido->email);

        $email->setSubject("Pedido $pedido->codigo saiu para entrega");

        $mensagem = view('Admin/Pedidos/pedido_saiu_entrega_email', ['pedido' => $pedido]);

        $email->setMessage($mensagem);

        $email->send();
    }

    private function enviaMensagemPedidoFoiEntregue(object $pedido) {

        $email = service('email');

        $email->setFrom('no-reply@deliciasdaauzidelivery.com', 'Delicias da Auzi Delivery');
        $email->setTo($pedido->email);

        $email->setSubject("Pedido $pedido->codigo foi entregue");

        $mensagem = view('Admin/Pedidos/pedido_foi_entregue_email', ['pedido' => $pedido]);

        $email->setMessage($mensagem);

        $email->send();
    }
    private function enviaMensagemPedidoFoiEntregueWhats(object $pedido) {

        $entregador = $pedido->entregador;
        $client = new Client(['verify' => false]);
        $telefone_usuario = str_replace('(', '', $pedido->telefone);
        $telefone_usuario = str_replace(')', '', $telefone_usuario);
        $telefone_usuario = str_replace(' ', '', $telefone_usuario);
        $telefone_usuario = str_replace('-', '', $telefone_usuario);
        $codigo_pedido = esc($pedido->codigo);
        $nome_cliente = esc($pedido->nome);
        $mensagem = "Obaa, o seu  pedido *$codigo_pedido* foi entregue! \n" .
                "Esperamos que você aproveite ao máximo a experiência e sabor que só a *Delicias da Auzi Delivery* pode oferecer!\n" .
                "Se possível faz um stories e marca a gente no Insta, ajuda bastante :D \n " .
                "@delicias_daauzi\n " .
                "Bom apetite! ";


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
    
    private function enviaMensagemPedidoFoiCancelado(object $pedido) {

        $email = service('email');

        $email->setFrom('no-reply@deliciasdaauzidelivery.com', 'Delicias da Auzi Delivery');
        $email->setTo($pedido->email);

        $email->setSubject("Pedido $pedido->codigo foi cancelado");

        $mensagem = view('Admin/Pedidos/pedido_foi_cancelado_email', ['pedido' => $pedido]);

        $email->setMessage($mensagem);

        $email->send();
    }
    
    private function enviaMensagemPedidoFoiCanceladoWhats(object $pedido) {

        $entregador = $pedido->entregador;
        $client = new Client(['verify' => false]);
        $telefone_usuario = str_replace('(', '', $pedido->telefone);
        $telefone_usuario = str_replace(')', '', $telefone_usuario);
        $telefone_usuario = str_replace(' ', '', $telefone_usuario);
        $telefone_usuario = str_replace('-', '', $telefone_usuario);
        $codigo_pedido = esc($pedido->codigo);
        $nome_cliente = esc($pedido->nome);
        $mensagem = "Que pena, o seu  pedido *$pedido->codigo* foi cancelado! \n" .
                "Olá *$pedido->nome*, o seu pedido *$pedido->codigo* foi cancelado \n" .
                "Verificamos aqui que o endereço de entrega é: *$pedido->endereco_entrega* \n " .
                "Lamentamos que isso tenha ocorrido." .
                "\n \n" .
                "Se a *Delicias da Auzi Delivery* puder fazer alguma coisa para melhorar a sua experiência conosco, não exite em nos contactar!";


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
    
    private function insereProdutosDoPedido(object $pedido) {

        $pedidoProdutoModel = new \App\Models\PedidoProdutoModel();

        $produtos = unserialize($pedido->produtos);

        $produtosDoPedido = [];

        foreach ($produtos as $produto) {
            array_push($produtosDoPedido, [
                'pedido_id' => $pedido->id,
                'produto' => $produto['nome'],
                'quantidade' => $produto['quantidade']
            ]);
        }

        $pedidoProdutoModel->insertBatch($produtosDoPedido);
    }

}
