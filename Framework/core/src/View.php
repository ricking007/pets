<?php

namespace Framework\core;

use Framework\autoLoader\AutoLoader;
use Framework\core\Factory;
use Framework\core\Session;
use Exception;

class View {

    private $controller;
    private $js;
    private $otherjs;
    private $css;
    private $othercss;
    private $breadcrumb;
    private $msg;
    private $active;
    private $title;
    private $menu;
    private $tema = DEFAULT_TEMA,
            $PageTitle;

    public function __construct(Request $request) {
        $this->controller = strtolower($request->getController());
        $this->js = array();
        if (!Session::get('language')) {
            Session::set('language', 'ptBR');
        }
        $autoLoader = AutoLoader::getAutoLoader();
        $autoLoader->_language = Factory::language(Session::get('language'));
    }

    public function render($view) {
        $autoLoader = AutoLoader::getAutoLoader();
        $TEXT = $autoLoader->_language;
        $js = array();
        if (count($this->js)) {
            $js = $this->js;
        }
        $rotaView = APP_PATH . 'views' . DS . $this->controller . DS . $view . '.php';
        if (is_readable($rotaView)) {
            include_once APP_PATH . 'views' . DS . 'themes' . DS . $this->tema . DS . 'header.php';
            include_once $rotaView;
            include_once APP_PATH . 'views' . DS . 'themes' . DS . $this->tema . DS . 'footer.php';
        } else {
            throw new Exception('Error ao carregar view: '.$view);
        }
    }

    public function renderAjax($view) {
        $autoLoader = AutoLoader::getAutoLoader();
        $TEXT = $autoLoader->_language;
        $js = array();
        if (count($this->js)) {
            $js = $this->js;
        }
        if (count($this->otherjs)) {
            array_push($js, $this->otherjs);
        }
        $rotaView = APP_PATH . 'views' . DS . $this->controller . DS . $view . '.php';
        if (is_readable($rotaView)) {
            include_once $rotaView;
        } else {
            throw new Exception('Error ao carregar página');
        }
    }

    /*
     * Recebe um array com todos os arquivos JS
     * que devem ser incluídos na View, para indicar a pasta do js
     * basta colocar pasta/nome_do_js
     */

    public function setJS(array $js) {
        if (is_array($js) && count($js)) {
            for ($i = 0; $i < count($js); $i++) {
                if (strpos($js[$i], '/') === false) {
                    $this->js[] = 'js/' . $this->controller . '/' . $js[$i] . '.js';
                } else {
                    $this->js[] = 'js/' . $js[$i] . '.js';
                }
            }
            $this->js = array_unique($this->js);
        } else {
            throw new Exception('Error ao carregar js');
        }
    }

    public function setOtherjs(array $js) {
        if (is_array($js) && count($js)) {
            for ($i = 0; $i < count($js); $i++) {
                $this->otherjs[] = $js[$i] . '.js';
            }
            $this->otherjs = array_unique($this->otherjs);
        } else {
            throw new Exception('Error ao carregar outros arquivos js');
        }
    }

    public function setTheme($tema) {
        $this->tema = $tema;
    }

    /*
     * Recebe um array com todos os arquivos CSS
     * que devem ser incluídos na View, para indicar a pasta
     * basta passar como parametro nome_da_pasta/nome_do_css
     */

    public function setCSS(array $css) {
        if (is_array($css) && count($css)) {
            for ($i = 0; $i < count($css); $i++) {
                if (strpos($css[$i], '/') === false) {
                    $this->css[] = 'css/' . $this->controller . '/' . $css[$i] . '.css';
                } else {
                    $this->css[] = 'css/' . $css[$i] . '.css';
                }
            }
            $this->css = array_unique($this->css);
        } else {
            throw new Exception('Error ao carregar css');
        }
    }

    public function setOthercss(array $css) {
        if (is_array($css) && count($css)) {
            for ($i = 0; $i < count($css); $i++) {
                $this->othercss[] = $css[$i] . '.css';
            }
            $this->othercss = array_unique($this->othercss);
        } else {
            throw new Exception('Error ao carregar outros css');
        }
    }

    /*
     * Recebe um array no formato (nome => link) e cria o
     * breadcrumb da View. Para adicionar um breadcrumb sem link
     * passe a posição do array no formato (nome => '')
     */

    public function setBreadcrumb(array $breadcrumb) {
        if (is_array($breadcrumb) && count($breadcrumb)) {
            $this->breadcrumb = array_unique($breadcrumb);
        } else {
            throw new Exception('Error criar breadcrumb');
        }
    }

    /*
     * Recebe as mensagens que devem ser apresentadas na view
     * para o usuário, o formato é ex: setMsg('mensagem','danger');
     * As classes possíveis são, success, danger, info e warning
     */

    public function setMsg($msg, $class = 'danger') {
        if (!empty($msg)) {
            $newMsg = array('msg' => $msg, 'class' => $class);
            if (!empty($_SESSION['mensagem'])) {
                unset($_SESSION['mensagem']);
            }
            $_SESSION['mensagem'] = serialize($newMsg);
        }
    }

    /*
     * Exibe a mensagem na view se houver alguma disponível
     * logo após exibir, elimina a mesma.
     */

    public function getMsg() {
        if (!empty($_SESSION['mensagem'])) {
            $this->msg = unserialize($_SESSION['mensagem']);
            $rotaView = APP_PATH . 'views' . DS . 'mensagem' . DS . 'index.php';
            if (is_readable($rotaView)) {
                include $rotaView;
            }
            unset($_SESSION['mensagem']);
        }
    }

    /*
     * Método para retornar dados do usuário logado
     * @return, depende do atributo solicitado
     */

    public function getUser($key) {
        return Session::getUser($key);
    }

    /*
     * Método para validar permissã de acesso
     * @return boolean
     */

    public function validaPermissaoUsuario($id_entidade, $id_permissao, $redirect = false) {
        return Session::validaPermissaoUsuario($id_entidade, $id_permissao, $redirect);
    }

    /*
     * Recebe um array de registros e cria uma variavel para a View
     */

    public function setRegistros($registros, $var = 'registros') {
        if (is_array($registros) && count($registros)) {
            $this->$var = $registros;
        } elseif (!empty($registros) && !empty($var)) {
            $this->$var = $registros;
        }
    }

    protected function getActive($item) {
        return $item == $this->active ? 'active' : '';
    }

    public function setActive($item) {
        $this->active = $item;
    }

    protected function getMenu() {
        return $this->menu;
    }

    public function setMenu($menu) {
        $this->menu = $menu;
    }

    public function setTitle($titulo) {
        $this->title = $titulo;
    }

    public function getTitle() {
        return $this->title;
    }

    protected function urlEncode($str) {
        // Assume $str esteja em UTF-8
        $find = "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ";
        $replace = "aaaaeeiooouucAAAAEEIOOOUUC";
        $keys = array();
        $values = array();
        preg_match_all('/./u', $find, $keys);
        preg_match_all('/./u', $replace, $values);
        $mapping = array_combine($keys[0], $values[0]);
        $str = strtr($str, $mapping);
        $str = preg_replace("/[^A-Za-z0-9]/", '-', $str);
        return strtolower($str);
    }

}
