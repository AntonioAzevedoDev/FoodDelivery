<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Entities\Extra;

class Extras extends BaseController {

    private $extraModel;

    public function __construct() {
        $this->extraModel = new \App\Models\ExtraModel();
    }

    public function index() {
        $data = [
            'titulo' => 'Listando os extras de produtos',
            'extras' => $this->extraModel->withDeleted(true)->paginate(10),
            'pager' => $this->extraModel->pager
        ];

        return view('Admin/Extras/index', $data);
    }

    public function criar() {

        $extra = new Extra();

        $data = [
            'titulo' => "Criando um novo extra",
            'extra' => $extra,
        ];

        return view('Admin/Extras/criar', $data);
    }

    public function cadastrar() {

        if ($this->request->getMethod() === 'post') {
            $extra = new Extra($this->request->getPost());


            if ($this->extraModel->save($extra)) {
                return redirect()->to(site_url("admin/extras/show/" . $this->extraModel->getInsertID()))
                                ->with('sucesso', "Extra $extra->nome cadastrado com sucesso");
            } else {
                return redirect()->back()
                                ->with('errors_model', $this->extraModel->errors())
                                ->with('atencao', 'Por favor verifique os erros abaixo')
                                ->withInput();
            }
        } else {
            //não é POST
            return redirect()->back();
        }
    }

    public function show($id = null) {

        $extra = $this->buscarExtraOu404($id);

        $data = [
            'titulo' => "Detalhando o extra $extra->nome",
            'extra' => $extra,
        ];

        return view('Admin/Extras/show', $data);
    }

    public function editar($id = null) {

        $extra = $this->buscarExtraOu404($id);

        if ($extra->deletado_em != null) {
            return redirect()->back()->with('info', "O extra $extra->nome encontra-se excluido. Portanto, não é possível editá-lo.");
        }


        $data = [
            'titulo' => "Editando o extra $extra->nome",
            'extra' => $extra,
        ];

        return view('Admin/Extras/editar', $data);
    }

    public function atualizar($id = null) {

        if ($this->request->getMethod() === 'post') {
            $extra = $this->buscarExtraOu404($id);

            if ($extra->deletado_em != null) {
                return redirect()->back()->with('info', "O extra $extra->nome encontra-se excluido. Portanto, não é possível atualizá-lo.");
            }



            $extra->fill($this->request->getPost());

            if (!$extra->hasChanged()) {
                return redirect()->back()->with('info', 'Não há dados para atualizar');
            }

            if ($this->extraModel->save($extra)) {
                return redirect()->to(site_url("admin/extras/show/$extra->id"))
                                ->with('sucesso', "Extra $extra->nome atualizado com sucesso");
            } else {
                return redirect()->back()
                                ->with('errors_model', $this->extraModel->errors())
                                ->with('atencao', 'Por favor verifique os erros abaixo')
                                ->withInput();
            }
        } else {
            //não é POST
            return redirect()->back();
        }
    }

    public function excluir($id = null) {

        $extra = $this->buscarExtraOu404($id);

        if ($extra->deletado_em != null) {
            return redirect()->back()->with('info', "O extra $extra->nome encontra-se excluido.");
        }

        if ($this->request->getMethod() === 'post') {
            $this->extraModel->delete($id);
            return redirect()->to(site_url('admin/extras'))->with('sucesso', "Extra $extra->nome excluidp com sucesso!");
        }

        $data = [
            'titulo' => "Excluindo o extra $extra->nome",
            'extra' => $extra,
        ];

        return view('Admin/Extras/excluir', $data);
    }

    public function desfazerExclusao($id = null) {

        $extra = $this->buscarExtraOu404($id);

        if ($extra->deletado_em == null) {
            return redirect()->back()->with('info', "Apenas extras excluídas podem ser recuperadas");
        }

        if ($this->extraModel->desfazerExclusao($id)) {
            return redirect()->back()->with('sucesso', "Exclusão desfeita com sucesso!");
        } else {
            return redirect()->back()
                            ->with('errors_model', $this->extraModel->errors())
                            ->with('atencao', 'Por favor verifique os erros abaixo')
                            ->withInput();
        }
    }

    public function procurar() {

        if (!$this->request->isAJAX()) {
            exit('Página não encontrada');
        }

        $extras = $this->extraModel->procurar($this->request->getGet('term'));

        $retorno = [];

        foreach ($extras as $extra) {
            $data['id'] = $extra->id;
            $data['value'] = $extra->nome;

            $retorno[] = $data;
        }

        return $this->response->setJSON($retorno);
    }

    private function buscarExtraOu404(int $id = null) {
        if (!$id || !$extra = $this->extraModel->withDeleted(true)->where('id', $id)->first()) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Não encontramos o extra $id");
        }
        return $extra;
    }

}
