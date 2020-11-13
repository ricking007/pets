<?php

namespace App\controllers;

use App\models\CorModel;
use Framework\core\Controller;

class CorController extends Controller
{

    private $model;

    function __construct()
    {
        parent::__construct();
        $this->view->setActive('cor');
        $this->view->setTheme('default');
        //$this->view->setTitle('Minha primeira página');
        $this->model = new CorModel;
    }

    function index()
    {
        $this->view->setTitle("Minhas Cores");
        $cores = $this->model->getCors();
        $this->view->setRegistros($cores, "cores");
        $this->view->render('index');
    }

    function form($id = null)
    {
        //cadastrar ou editar uma cor
        if ($id) {
            $this->model->setId_cor($id);
            $cor = $this->model->getCor();
            $this->view->setTitle("Editar a cor ".$cor['no_cor']);
            $this->view->setRegistros($cor, "cor");
        }
        $this->view->render('form');
    }

    function set()
    {
        $this->model->setId_cor($this->getPostInt('id_cor'));
        $this->model->setNo_cor($this->getPostDefaultParam('no_cor'));

        if (!$this->getPostInt('id_cor')) {
            if ($this->model->getCorByName()) {
                echo json_encode(array("success" => false, "message" => "Essa cor já existe!"));
                exit;
            }
        }

        $id = $this->model->set();
        if ($id) {
            echo json_encode(array("success" => true, "message" => "Cadastro efetuado com sucesso!"));
            exit;
        } else {
            echo json_encode(array("success" => false, "message" => "Ocorreu um erro!"));
            exit;
        }
    }

    function del($id)
    {
        $id = $this->filterInt($id);
        $this->model->setId_cor($id);
        $this->model->delete();
        echo json_encode(array("success" => true, "message" => "Cadastro excluido com sucesso!"));
        exit;
    }
}
