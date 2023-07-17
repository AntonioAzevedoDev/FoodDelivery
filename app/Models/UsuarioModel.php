<?php

namespace App\Models;

use CodeIgniter\Model;

use App\Libraries\Token;

class UsuarioModel extends Model
{
    protected $DBGroup = 'default';
    protected $table            = 'usuarios';
    protected $returnType       = 'App\Entities\Usuario';
    protected $allowedFields    = ['nome','email','cpf','telefone','password','reset_hash','reset_expira_em'];
    
    
    protected $useSoftDeletes   = true;
    protected $dateFormat = 'datetime';
    protected $useTimestamps        = true;
    protected $createdField         = 'criado_em';
    protected $updatedField         = 'atualizado_em';
    protected $deletedField         = 'deletado_em'; 
    protected $validationRules = [
        'nome'     => 'required|min_length[4]|max_length[120]',
        'email'        => 'required|valid_email|is_unique[usuarios.email]',
        'cpf'        => 'required|exact_length[14]|is_unique[usuarios.cpf]|validaCpf',
        'password'     => 'required|min_length[6]',
        'password_confirmation' => 'required_with[password]|matches[password]',
    ];
    protected $validationMessages = [
        'nome' => [
            'required' => 'O campo Nome é obrigatório',
        ],
        'email' => [
            'required' => 'O campo Email é obrigatório',
            'is_unique' => 'Desculpe. Esse e-mail já existe.',
        ],
        'cpf' => [
            'required' => 'O campo CPF é obrigatório',
            'is_unique' => 'Desculpe. Esse CPF já existe.',
        ],
    ];
    //Eventos callback
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];
    
    protected function hashPassword(array $data) {
        
        if(isset($data['data']['password'])){
            
            $data['data']['password_hash'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
            
            unset($data['data']['password']);
            unset($data['data']['password_confirmation']);
        }
        return $data;
        
    }
    
    
    /**
     * 
     * @param string $term
     * @return type
     */
    public function procurar($term) {
        if($term == null){
            return [];
        }
        
        return $this->select('id,nome')
                    ->like('nome', $term)
                    ->withDeleted(true)
                    ->get()
                    ->getResult();
    }
    
    
    public function desabilitaValidacaoSenha() {
        unset($this->validationRules['password']);
        unset($this->validationRules['password_confirmation']);
    }

    public function buscaUsuarioPorEmail(string $email) {
        
        return $this->where('email', $email)->first();
        
    }
    
    public function buscaUsuarioParaResetarSenha(string $token) {
        
        $token = new Token($token);
        
        $tokenHash = $token->getHash();
        
        $usuario = $this->where('reset_hash', $tokenHash)->first();
        
        if($usuario){
            
            
            if($usuario->reset_expira_em < date('Y-m-d H:i:s')){
                
                $usuario = null;
                
            }
            
            return $usuario;
            
        }
    }
    
    public function desfazerExclusao(int $id) {
        return $this->protect(false)
                ->where('id',$id)
                ->set('deletado_em', null)
                ->update();
    }
}
