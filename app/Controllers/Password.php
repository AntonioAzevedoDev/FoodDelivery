<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Password extends BaseController {

    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new \App\Models\UsuarioModel();
    }

    public function esqueci() {
        $data = [
            'titulo' => 'Esqueci a minha senha',
        ];
        return view('Password/esqueci', $data);
    }

    public function processaEsqueci() {

        if ($this->request->getMethod() === 'post') {

            $usuario = $this->usuarioModel->buscaUsuarioPorEmail($this->request->getPost('email'));

            if ($usuario === null || !$usuario->ativo) {

                return redirect()->to(site_url('password/esqueci'))
                                ->with('atencao', 'Não encontramos uma conta válida com esse email')
                                ->withInput();
            }

            $usuario->iniciaPasswordReset();

            $this->usuarioModel->save($usuario);
            
            

            $this->enviaEmailRedefinicaoSenha($usuario);
            //dd($usuario);
            return redirect()->to(site_url('login'))->with('sucesso', 'E-mail de redefinição de senha enviado para sua caixa de entrada');
        } else {
            return redirect()->back();
        }
    }

    public function reset($token = null) {

        if ($token === null) {
            return redirect()->to(site_url('password/esqueci'))->with('atencao', 'Link inválido ou expirado');
        }

        $usuario = $this->usuarioModel->buscaUsuarioParaResetarSenha($token);

        if ($usuario != null) {

            $data = [
                'titulo' => 'Redefina sua senha',
                'token' => $token,
            ];

            return view('Password/reset', $data);
        } else {
            return redirect()->to(site_url('password/esqueci'))->with('atencao', 'Link inválido ou expirado');
            ;
        }
    }

    public function processareset($token = null) {

        if ($token === null) {
            return redirect()->to(site_url('password/esqueci'))->with('atencao', 'Link inválido ou expirado');
        }

        $usuario = $this->usuarioModel->buscaUsuarioParaResetarSenha($token);

        if ($usuario != null) {

            $usuario->fill($this->request->getPost());

            if ($this->usuarioModel->save($usuario)) {
                
                
                $usuario->completaPasswordReset();
                
                $this->usuarioModel->save($usuario);

                return redirect()->to(site_url('login'))->with('sucesso', 'Nova senha cadastrada com sucesso!');
            } else {

                return redirect()->to(site_url("password/reset/$token"))
                                ->with('errors_model', $this->usuarioModel->errors())
                                ->with('atencao', 'Por favor verifique os erros abaixo')
                                ->withInput();
            }
        } else {
            return redirect()->to(site_url('password/esqueci'))->with('atencao', 'Link inválido ou expirado');
            
        }
    }

    private function enviaEmailRedefinicaoSenha(object $usuario) {

        $email = service('email');

        $email->setFrom('no-reply@deliciasdaauzidelivery.com', 'Delicias da Auzi Delivery');
        $email->setTo($usuario->email);

        $email->setSubject('Redefinição de senha');

        $mensagem = view('Password/reset_email', ['token' => $usuario->reset_token]);

        $email->setMessage($mensagem);

        $email->send();
    }

}
