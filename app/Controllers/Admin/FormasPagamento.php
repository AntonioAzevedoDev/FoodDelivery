<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

use App\Entities\FormaPagamento;

class FormasPagamento extends BaseController
{
    
    private $formaPagamentoModel;
    
    public function __construct() {
        $this->formaPagamentoModel = new \App\Models\FormaPagamentoModel();
    }
    public function index()
    {
        $data = [
            'titulo' => 'Listando as formas de pagamento',
            'formas' => $this->formaPagamentoModel->withDeleted(true)->paginate(10),
            'pager' => $this->formaPagamentoModel->pager
        ];
        
        return view('Admin/FormasPagamento/index', $data);
    }
    
    public function procurar() {

        if (!$this->request->isAJAX()) {
            exit('Página não encontrada');
        }

        $formas = $this->formaPagamentoModel->procurar($this->request->getGet('term'));

        $retorno = [];

        foreach ($formas as $forma) {
            $data['id'] = $forma->id;
            $data['value'] = $forma->nome;

            $retorno[] = $data;
        }

        return $this->response->setJSON($retorno);
    }
}
