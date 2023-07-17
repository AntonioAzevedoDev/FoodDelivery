<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class VisitanteFilter implements FilterInterface
{
    
    public function before(RequestInterface $request, $arguments = null)
    {
        
        $autenticacao = service('autenticacao');
        
        if($autenticacao->estaLogado()){
            
            $usuario = $autenticacao->pegaUsuarioLogado();
            
            if($usuario->is_admin){
                //dd($usuario);
                return redirect()->to(site_url('admin/home'));
                
            }
            
            return redirect()->to(site_url('/'));
            
        }
        
    }

    
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
