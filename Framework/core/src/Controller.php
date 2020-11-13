<?php

namespace Framework\core;

use Framework\autoLoader\AutoLoader;
use Framework\core\View;
use Framework\core\Session;
use App\libs\Util;
use App\libs\Headers;
use Exception;
use App\libs\Email;
use App\libs\TemplateEmail;

abstract class Controller
{

    private $autoLoader;
    protected $view;

    public function __construct()
    {
        $this->autoLoader = AutoLoader::getAutoLoader();
        $this->view = new View($this->autoLoader->request);
        $this->cors();//comente esta linha caso não seja necessário o uso de cors
    }

    abstract public function index();

    /*
     * Carrega o modelo solicitado
     * @return Object
     */

    protected function loadModel($model)
    {
        $model = ucfirst($model) . 'Model';
        $rotaModelo = APP_PATH . 'models' . DS . 'src' . DS . $model . '.php';
        if (is_readable($rotaModelo)) {
            $nspace = APP_NSPACE . "\\models\\{$model}";
            $modelo = new $nspace();
            return $modelo;
        } else {
            throw new Exception($model . ' Model não encontrada');
        }
    }

    /*
     * Carrega o modelo solicitado com os atributos vindos do formulário
     * @return Object
     */

    protected function loadObject($model, $post)
    {
        $model = ucfirst($model) . 'Model';
        $rotaModelo = APP_PATH . 'models' . DS . 'src' . DS . $model . '.php';
        if (is_readable($rotaModelo)) {
            $nspace = APP_NSPACE . "\\models\\{$model}";
            $modelo = new $nspace();
            $post = (object) $post;
            //$modelo->set($post);
            foreach ($post as $key => $value) {
                if (!empty($value)) {
                    $modelo->setObject($key, $value);
                }
            }
            return $modelo;
        } else {
            throw new Exception($model . ' Model não encontrada');
        }
    }
    /*
     * Retorna o post do tipo int sem tags e caracteres indesejados
     * @return Int
     */

    protected function getPostInt($name)
    {
        return filter_input(INPUT_POST, $name, FILTER_SANITIZE_NUMBER_INT);
    }

    /*
     * Retorna o post do tipo flot sem tags e caracteres indesejados
     * @return Float
     */

    protected function getPostFloat($name)
    {
        return filter_input(INPUT_POST, $name, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }

    /*
     * Retorna o POST da string sem tags html indesejadas
     * @return String
     */

    protected function getPostString($name)
    {
        $texto = filter_input(INPUT_POST, $name, FILTER_SANITIZE_STRING);
        $encoding = 'UTF-8';
        $caracteres = preg_replace(
            array(
                "/(á|à|ã|â|ä)/",
                "/(Á|À|Ã|Â|Ä)/",
                "/(é|è|ê|ë)/",
                "/(É|È|Ê|Ë)/",
                "/(í|ì|î|ï)/",
                "/(Í|Ì|Î|Ï)/",
                "/(ó|ò|õ|ô|ö)/",
                "/(Ó|Ò|Õ|Ô|Ö)/",
                "/(ú|ù|û|ü)/",
                "/(Ú|Ù|Û|Ü)/",
                "/(ñ)/",
                "/(Ñ)/",
                "/(ç)/",
                "/(Ç)/"
            ),
            explode(" ", "a A e E i I o O u U n N c C"),
            $texto
        );
        return mb_convert_case($caracteres, MB_CASE_UPPER, $encoding);
    }

    /*
     * Retorna o POST conforme nome passado por parametro
     * @return String, Int -> depende do Post
     */

    protected function getPostParam($name)
    {
        $texto = filter_input(INPUT_POST, $name);
        $encoding = 'UTF-8';
        $caracteres = preg_replace(
            array(
                "/(á|à|ã|â|ä)/",
                "/(Á|À|Ã|Â|Ä)/",
                "/(é|è|ê|ë)/",
                "/(É|È|Ê|Ë)/",
                "/(í|ì|î|ï)/",
                "/(Í|Ì|Î|Ï)/",
                "/(ó|ò|õ|ô|ö)/",
                "/(Ó|Ò|Õ|Ô|Ö)/",
                "/(ú|ù|û|ü)/",
                "/(Ú|Ù|Û|Ü)/",
                "/(ñ)/",
                "/(Ñ)/",
                "/(ç)/",
                "/(Ç)/"
            ),
            explode(" ", "a A e E i I o O u U n N c C"),
            $texto
        );
        return mb_convert_case($caracteres, MB_CASE_UPPER, $encoding);
    }

    protected function getPostDefaultParam($name)
    {
        return filter_input(INPUT_POST, $name);
    }

    protected function getGetDefaultParam($name)
    {
        $filter = filter_input(INPUT_GET, $name);
        return trim($filter);
    }

    protected function getGetParam($name)
    {
        $filter = filter_input(INPUT_GET, $name);
        return trim($filter);
    }

    /*
     * Retorna o POST conforme nome passado por parametro
     * @return String, Int -> depende do Post
     */

    protected function getPostMultiParam($name)
    {
        return filter_input(INPUT_POST, $name, FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    }

    /*
     * Retorna o GET conforme nome passado por parametro
     * @return String, Int -> depende do GET
     */

    protected function getGetMultiParam($name)
    {
        return filter_input(INPUT_GET, $name, FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    }

    /*
     * Retorna o GET da string sem tags html indesejadas
     * @return String
     */

    protected function getGetString($name)
    {
        $filter = filter_input(INPUT_GET, $name, FILTER_SANITIZE_STRING);
        return trim($filter);
    }

    /*
     * Retorna o GET com o num inteiro
     * @return int
     */

    protected function getGetInt($name)
    {
        return filter_input(INPUT_GET, $name, FILTER_SANITIZE_NUMBER_INT);
    }

    /*
     * Retorna o GET com o num inteiro
     * @return int
     */

    protected function filterInt($num)
    {
        $num = filter_var($num, FILTER_SANITIZE_NUMBER_INT);
        settype($num, "integer");
        return $num;
    }

    /*
     * Verifica se uma string está no formato de hash MD5
     * se não estiver transforma-a
     * @return MD5
     */

    protected function isMD5($hash)
    {
        return preg_match('/^[a-f0-9]{32}$/', $hash) ? $hash : md5($hash);
    }

    protected function printR($array)
    {
        echo '<pre>';
        print_r($array);
        exit;
    }

    /*
     * Verifica se o email é válido e o retorna, caso contrário retorna false
     * @return String Email
     */

    protected function getPostEmail($email)
    {
        $email = filter_input(INPUT_POST, $email, FILTER_SANITIZE_EMAIL);
        return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : FALSE;
    }

    protected function isAjax()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    /*
     * Redireciona para o caminho desejado
     * Recebe no seguinte formato: controle/método/parametro
     * @return void
     */

    protected function Redirect($location = NULL)
    {
        if ($location) {
            header('Location:' . BASE_URL . $location);
            exit;
        } else {
            header('Location:' . BASE_URL);
            exit;
        }
    }

    protected function getIp()
    {
        return filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP);
    }

    /*
     * Retorna o texto traduzido conforme linguagem instanciada
     * @return String
     */

    protected function Text()
    {
        $autoLoader = AutoLoader::getAutoLoader();
        return $TEXT = $autoLoader->_language;
    }

    /*
     * Seta a linguagem escolhida pelo usuário
     * @return void
     */

    public function setLanguage($lang)
    {
        Session::set('language', $lang);
        $location = $_SERVER['HTTP_REFERER'];
        header("Location: $location");
    }

    /*
     * Retorna o atributo do usuário conforme solicitado
     * @return String, Int -> depende do atributo solicitado
     */

    public function getUser($key)
    {
        return Session::getUser($key);
    }

    public function setUser($key, $val)
    {
        return Session::setUser($key, $val);
    }
    /*
     * Método para validar permissão de acesso
     * @return boolean
     */

    public function validaPermissaoUsuario($id_entidade, $id_permissao, $redirect = false)
    {
        return Session::validaPermissaoUsuario($id_entidade, $id_permissao, $redirect);
    }

    /*
     * Permite somente usuários autenticados
     */

    public function restricted()
    {
        $url = $_SERVER["REQUEST_URI"];
        if (BASE_URL == "http://localhost/cenajur/public/") {
            $url = str_replace("/cenajur/public/", "", $url);
        }
        Session::set("redirectUri", $url);
        return Session::restricted();
    }

    /*
     * Permite somente usuários retritos especificamente
     */

    public function restrictedColaborador()
    {
        if ($this->getUser('usuario_tipo') != 'colaborador') {
            $this->Redirect('login');
        }
    }

    public function restrictedAssociado()
    {
        if ($this->getUser('usuario_tipo') != 'associado') {
            $this->Redirect('login');
        }
    }

    /*
     * Verificar nivel de permissão do grupo
     * para acessar uma determinada área
     */

    protected function permissao($menu)
    {
        $grupo = $this->getUser('IDGrupo');
        $perm = $this->loadModel('grupodeacesso');
        $perm->SetID($grupo);
        $perm->SetIDMenu($menu);
        $permissao = $perm->getGrupoMenuAcesso();
        $this->view->setRegistros($permissao, 'permissoes');
    }

    protected function validatePost($fields)
    {
        $result = array();
        $i = 0;
        $fields = json_decode($fields, true);
        if (!empty($fields['required'])) {
            foreach ($fields['required'] as $r) {
                $var = $this->getPostParam($r);
                if (empty($var)) {
                    $result['errors'][$i]['field'] = $r;
                    $result['errors'][$i]['message'] = $this->Text()->TxCampoObrigatorio();
                    $i++;
                }
            }
        }
        if (!empty($fields['multiRequired'])) {
            foreach ($fields['multiRequired'] as $r) {
                $var = $this->getPostMultiParam($r);
                $e = array();
                for ($j = 0; $j < sizeof($var); $j++) {
                    if (empty($var[$j])) {
                        $e[$j] = $this->Text()->TxCampoObrigatorio();
                    }
                }
                if (!empty($e)) {
                    $result['errors'][$i]['field'] = $r;
                    $result['errors'][$i]['message'] = $e;
                    $i++;
                }
            }
        }
        if (!empty($fields['cpf'])) {
            foreach ($fields['cpf'] as $r) {
                $var = $this->getPostParam($r);
                if (!empty($var) && !Util::validaCpf($var)) {
                    $result['errors'][$i]['field'] = $r;
                    $result['errors'][$i]['message'] = $this->Text()->TxCPFInvalido();
                    $i++;
                }
            }
        }
        if (!empty($fields['email'])) {
            foreach ($fields['email'] as $r) {
                $var = $this->getPostParam($r);
                if (!empty($var) && !Util::validaEmail($var)) {
                    $result['errors'][$i]['field'] = $r;
                    $result['errors'][$i]['message'] = $this->Text()->TxEmailInvalido();
                    $i++;
                }
            }
        }
        if (!empty($fields['data'])) {
            foreach ($fields['data'] as $r) {
                $var = $this->getPostParam($r);
                if (!empty($var) && !Util::validaData($var)) {
                    $result['errors'][$i]['field'] = $r;
                    $result['errors'][$i]['message'] = $this->Text()->TxDataInvalida();
                    $i++;
                }
            }
        }
        if (!empty($fields['file'])) {
            foreach ($fields['file'] as $r) {
                if (!empty($_FILES[$r['name']])) {
                    $var = $_FILES[$r['name']];
                    if ($var['size'] > $r['size']) {
                        $result['errors'][$i]['field'] = $r['name'];
                        $result['errors'][$i]['message'] = $this->Text()->TxTamanhoMaximo() . ': ' . $r['size'] / 1024 / 1024 . 'MB';
                        $i++;
                        //} else if($var['type'] != $r['type']){
                    } else if (!in_array($var['type'], $r['type'])) {
                        $result['errors'][$i]['field'] = $r['name'];
                        $result['errors'][$i]['message'] = $this->Text()->TxTipoDeArquivoInvalido();
                        $i++;
                    }
                }
            }
        }
        if (!empty($fields['captcha'])) {
            if (current($fields['captcha']) == 'recaptcha') {
                $captcha = $this->getPostParam('g-recaptcha-response');
                $ip = $this->getIp();
                $json = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . SECRET_KEY_RECAPTCHA . "&response=" . $captcha . "&remoteip=" . $ip);
                $response = json_decode($json);
                if ($response->success) {
                    $result['captcha']['success'] = true;
                } else {
                    $result['captcha']['success'] = false;
                    $result['captcha']['message'] = $this->Text()->TxConfirmeQueVoceNaoEUmRobo();
                }
            }
        } else {
            $result['captcha']['success'] = true;
        }
        if (empty($result['errors']) && $result['captcha']['success']) {
            $result['success'] = true;
        } else {
            $result['success'] = false;
            $result['message'] = $this->Text()->TxOcorremErrosDeValidacao();
        }
        return $result;
    }

    /*
     * Autenticação e controle de acesso da API
     */

    function httpResponseCode($code = NULL)
    {

        if ($code !== NULL) {

            switch ($code) {
                case 100:
                    $text = 'Continue';
                    break;
                case 101:
                    $text = 'Switching Protocols';
                    break;
                case 200:
                    $text = 'OK';
                    break;
                case 201:
                    $text = 'Created';
                    break;
                case 202:
                    $text = 'Accepted';
                    break;
                case 203:
                    $text = 'Non-Authoritative Information';
                    break;
                case 204:
                    $text = 'No Content';
                    break;
                case 205:
                    $text = 'Reset Content';
                    break;
                case 206:
                    $text = 'Partial Content';
                    break;
                case 300:
                    $text = 'Multiple Choices';
                    break;
                case 301:
                    $text = 'Moved Permanently';
                    break;
                case 302:
                    $text = 'Moved Temporarily';
                    break;
                case 303:
                    $text = 'See Other';
                    break;
                case 304:
                    $text = 'Not Modified';
                    break;
                case 305:
                    $text = 'Use Proxy';
                    break;
                case 400:
                    $text = 'Bad Request';
                    break;
                case 401:
                    $text = 'Usuário e senha incorretos';
                    break;
                case 402:
                    $text = 'Payment Required';
                    break;
                case 403:
                    $text = 'Usuário logado em outro aparelho';
                    break;
                case 404:
                    $text = 'Usuário ou senha não encontrado';
                    break;
                case 405:
                    $text = 'Method Not Allowed';
                    break;
                case 406:
                    $text = 'Not Acceptable';
                    break;
                case 407:
                    $text = 'Proxy Authentication Required';
                    break;
                case 408:
                    $text = 'Request Time-out';
                    break;
                case 409:
                    $text = 'Conflict';
                    break;
                case 410:
                    $text = 'Gone';
                    break;
                case 411:
                    $text = 'Length Required';
                    break;
                case 412:
                    $text = 'Precondition Failed';
                    break;
                case 413:
                    $text = 'Request Entity Too Large';
                    break;
                case 414:
                    $text = 'Request-URI Too Large';
                    break;
                case 415:
                    $text = 'Unsupported Media Type';
                    break;
                case 422:
                    $text = 'Conta inativada, por favor contate o suporte';
                    break;
                case 423:
                    $text = 'Período de teste expirado, Favor entrar em contato com o suporte';
                    break;
                case 424:
                    $text = 'Token não identificado';
                    break;
                case 425:
                    $text = 'Token expirado';
                    break;
                case 426:
                    $text = 'O Token informado não foi reconhecido como um token válido';
                    break;
                case 427:
                    $text = 'Este usuário encontra-se bloqueado';
                    break;
                case 500:
                    $text = 'Problema interno, por favor contate o suporte';
                    break;
                case 501:
                    $text = 'Not Implemented';
                    break;
                case 502:
                    $text = 'Bad Gateway';
                    break;
                case 503:
                    $text = 'Service Unavailable';
                    break;
                case 504:
                    $text = 'Gateway Time-out';
                    break;
                case 505:
                    $text = 'HTTP Version not supported';
                    break;
                default:
                    exit('Código não identificado "' . htmlentities($code) . '"');
                    break;
            }

            $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');

            header($protocol . ' ' . $code . ' ' . $text);

            $GLOBALS['http_response_code'] = $code;
        } else {
            $code = (isset($GLOBALS['http_response_code']) ? $GLOBALS['http_response_code'] : 200);
        }
        return $code;
    }

    function cors()
    {
        // Allow from any origin
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }
        // Access-Control headers are received during OPTIONS requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            exit(0);
        }
        return true;
    }
}
