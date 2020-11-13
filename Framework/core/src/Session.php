<?php

namespace Framework\core;

use Exception;

class Session {

    private static $pessoa,
            $permissoes;

    /*
     * Método para inicializar uma sessão de forma segura
     * @return void
     */

    public static function init() {
        //Nome personalizado da sessão
        $session_name = 'cenajur';
        $secure = SECURE;
        // Isso impede que o JavaScript possa acessar a identificação da sessão.
        $httponly = true;
        // Força a sessão a usar apenas cookies. 
        if (ini_set('session.use_only_cookies', 1) === FALSE) {
            throw new Exception("Location: ../error/Could-not-nitiate-a-safe-session)");
        }
        // Obtém params de cookies atualizados.
        $cookieParams = session_get_cookie_params();
        session_set_cookie_params($cookieParams["lifetime"],
                $cookieParams["path"],
                $cookieParams["domain"],
                $secure,
                $httponly);
        // Estabelece o nome fornecido acima como o nome da sessão.
        session_name($session_name);
        session_start();            // Inicia a sessão PHP 
        session_regenerate_id();    // Recupera a sessão e deleta a anterior.
        //echo "<pre>";print_r($_SESSION);echo"</pre>";
    }

    /*
     * Método para destruir a sessão
     * @return void
     */

    public static function destroy($key = false) {
        if ($key) {
            if (is_array($key)) {
                for ($i = 0; $i < count($key); $i++) {
                    if (isset($_SESSION[$key[$i]])) {
                        unset($_SESSION[$key[$i]]);
                    }
                }
            } else {
                if (isset($_SESSION[$key])) {
                    unset($_SESSION[$key]);
                }
            }
        } else {
            session_destroy();
        }
    }

    /*
     * Método para adicionar uma nova chave na sessão
     * @return void
     */

    public static function set($key, $val) {
        if (!empty($key)) {
            $_SESSION[$key] = $val;
        }
    }

    /*
     * Método para adicionar uma nova chave na sessão
     * @return void
     */

    public static function setUser($key, $val) {
        if (is_array(self::$pessoa)) {
            self::$pessoa[$key] = $val;
        } else if (isset($_SESSION['login_auth_user'])) {
            self::$pessoa = unserialize($_SESSION['login_auth_user']);
            self::$pessoa[$key] = $val;
        }
        self::set('login_auth_user', serialize(self::$pessoa));
    }

    /*
     * Retorna a informação da sessão conforme a chave solicitada
     * @return String
     */

    public static function get($key) {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
    }

    /*
     * Método para retornar a informação da pessoa que está logada, se o usuário
     * já estiver carregado no atributo da classe retorna a chave solicitada, caso
     * contrário desserealiza da sessão e retorna a chave solicitada
     * @return String, Int, -> depende da chave solicitada
     */

    public static function getUser($key) {
        if (is_array(self::$pessoa)) {
            return self::$pessoa[$key];
        } else if (isset($_SESSION['login_auth_user'])) {
            self::$pessoa = unserialize($_SESSION['login_auth_user']);
            return isset(self::$pessoa[$key]) ? self::$pessoa[$key] : 'Atributo inválido';
        } else {
            return false;
        }
    }

    /*
     * Permite somente usuários logados acessar a página,
     * também compara se o ip e o navegador são o mesmos
     * no momento do login e no momento de acessar a página
     * dificultando o roubo de sessão
     * @return void
     */

    public static function restricted() {
        if (isset($_SESSION['login_auth_user']) && isset($_SESSION['login_hash_str'])) {
            $hash = hash('sha512',
                    self::getUser('ID') .
                    $_SERVER['HTTP_USER_AGENT'] .
                    $_SERVER['REMOTE_ADDR']);
            if ($hash !== self::get('login_hash_str')) {
                self::logout();
                header("Location:" . BASE_URL . 'error/acessonegado');
            }
        } else {
            header("Location: " . BASE_URL . 'login');
        }
    }

    /*
     * Faz logout e apaga todos os dados da sessão
     * @return void
     */

    public static function logout() {
//        echo "<pre>"; print_r($_SESSION); exit;
        $cliente = self::get('login_client_user');
        self::$pessoa = array();
        // Desfaz todos os valores da sessão  
        self::destroy(array('login_hash_str', 'login_auth_user', 'login_perm_user',
            'login_client_user'));
        //self::destroy('login_auth_user');
        // obtém os parâmetros da sessão 
        $params = session_get_cookie_params();
        // Deleta o cookie em uso. 
        setcookie(session_name(), '', time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]);
        self::destroy();
        unset($_SESSION);
        session_destroy();
        $location = BASE_URL . 'login';
        if ($cliente) {
            $location .= "/cliente/$cliente";
        }
        header("Location: $location");
        exit;
    }

    /*
     * Método que valida se o usuário tem permissão para executar determinadas
     * tarefas, se passado redirect = true, faz logout caso o usuário não possua 
     * a permissão
     * @return boolean
     */

    public static function validaPermissaoUsuario($id_entidade, $id_permissao, $redirect = false) {
        if (is_array(self::$permissoes)) {
            $permissoes = self::$permissoes;
        } else if (isset($_SESSION['login_perm_user'])) {
            $permissoes = unserialize($_SESSION['login_perm_user']);
            self::$permissoes = $permissoes;
        } else {
            return false;
        }
        //echo "<pre>";print_r($permissoes);exit;
        $valido = true;
        if (!in_array(array($id_entidade, $id_permissao), $permissoes)) {
            $valido = false;
            if ($redirect) {
                self::logout();
            }
        }
        return $valido;
    }

}
