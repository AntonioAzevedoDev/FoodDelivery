<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsuarioSeeder extends Seeder
{
    public function run()
    {
        $usuarioModel = new \App\Models\UsuarioModel;
        
        $usuario = [
            'nome' => 'Lucas Silva',
            'email' => 'lucassilva.eq@gmail.com',
            'telefone' => '85 - 99167-4535',
            'cpf' => '045.385.210-60',
            'password' => '123456',
            'password_confirmation'=>'123456'
        ];
        
        $usuarioModel -> protect(false) -> insert($usuario);
        
       
        
        $usuarioModel -> protect(false) -> insert($usuario);
        
        dd($usuarioModel -> errors());
    }
}
