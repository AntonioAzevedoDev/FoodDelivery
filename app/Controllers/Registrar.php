<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\Usuario;


class Registrar extends BaseController {

    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new \App\Models\UsuarioModel();
    }

    public function novo() {
        $data = [
            'titulo' => 'Criar nova conta'
        ];

        return view('Registrar/novo', $data);
    }

    public function criar() {

        if ($this->request->getMethod() === 'post') {
            $usuario = new Usuario($this->request->getPost(['nome', 'email', 'cpf', 'password', 'password_confirmation']));

            $this->usuarioModel->desabilitaValidacaoTelefone();

            $usuario->iniciaAtivacao();

            if ($this->usuarioModel->insert($usuario)) {

                $this->enviaEmailParaAtivarConta($usuario);

                return redirect()->to(site_url("registrar/ativacaoenviado"));
            } else {
                return redirect()->back()
                                ->with('errors_model', $this->usuarioModel->errors())
                                ->with('atencao', 'Por favor verifique os erros abaixo')
                                ->withInput();
            }
        } else {
            return redirect()->back();
        }
    }

    public function ativar(string $token = null) {

        if ($token == null) {
            return redirect()->to(site_url('login'));
        }

        $this->usuarioModel->ativarContaPeloToken($token);

        return redirect()->to(site_url('login'))->with('sucesso', 'Conta ativada com sucesso!');
    }

    public function ativacaoEnviado() {
        $data = [
            'titulo' => 'E-mail de ativação da conta enviado para a sua caixa de entrada'
        ];

        return view('Registrar/ativacao_enviado', $data);
    }

//     private function enviaEmailParaAtivarConta(object $usuario) {
//
//        $email = service('email');
//
//        $email->setFrom('no-reply@deliciasdaauzidelivery.com', 'Delicias da Auzi Delivery');
//        $email->setTo($usuario->email);
//
//        $email->setSubject('Ativação de conta');
//
//        $mensagem = view('Registrar/ativacao_email', ['usuario' => $usuario]);
//
//        $email->setMessage($mensagem);
//
//        $email->send();
//    }
    private function enviaEmailParaAtivarConta(object $usuario) {


        $mensagem = view('Registrar/ativacao_email', ['usuario' => $usuario]);

        $email = new \SendGrid\Mail\Mail();
        $email->setFrom("deliciasdaauzidelivery@outlook.com", "Delicias da Auzi Delivery");
        $email->setSubject("Ativação de conta");
        $email->addTo($usuario->email);
        $email->addContent("text/plain", "Bem vindo ao Delicias da Auzi Delivery");
        $email->addContent(
                "text/html", "$mensagem"
        );
        $sendgrid = new \SendGrid('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');
        try {
            $response = $sendgrid->send($email);
            print $response->statusCode() . "\n";
            print_r($response->headers());
            print $response->body() . "\n";
            dd($response->body());
        } catch (Exception $e) {
            echo 'Caught exception: ' . $e->getMessage() . "\n";
        }
    }

}
