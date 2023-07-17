<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
{
    
    public function before(RequestInterface $request, $arguments = null)
    {
        $usuario = service('autenticacao')->pegaUsuarioLogado();
        if(!$usuario ->is_admin){
            
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("$usuario->nome, não encontramos a página que você está procurando :(");
            
        }
    }

    
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
