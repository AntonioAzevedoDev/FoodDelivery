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
            'is_admin' => true,
            'cpf' => '036.952.503-56',
            'ativo' => true,
            'password' => '123456',
        ];
        
        $usuarioModel->skipValidation(true) -> protect(false) -> insert($usuario);
    }
}
