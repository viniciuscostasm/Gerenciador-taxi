<?php

/**
 * Classe que implementa as validações de segurança da aplicação
 */
Class GSec {

    /**
     * Verifica se usuário logado, se não estiver, redireciona para a página de Login(URL_SIGNIN - informada em global.php)
     */
    public static function verificarLogin() {
        if (!GSec::getUserSession()) {
            $url = (strlen($_SERVER["REQUEST_URI"]) > 1 && !IS_AJAX) ? '&url=' . $_SERVER["REQUEST_URI"] : '';
            exit('<script>self.location = "' . URL_SIGNIN . '?' . $url . '";</script>');
        }
    }

    /**
     * Verifica se usuário logado, se não estiver, retorna false, se tiver logado retorna true
     */
    public static function validarLogin() {
        return (!GSec::getUserSession()) ? false : true;
    }

    /**
     * Verifica se o usuário logar tem permissão na url passado, se não estiver, redireciona para a página de ERRO(URL_EROR - informada em global.php)
     */
    public static function verificarMenuProfile($url) {
        if (!GSec::validarMenuProfile($url)) {
            echo '<script>self.location = "' . URL_SYS . 'errors/perm.php";</script>';
        }
    }

    /**
     * Verifica se o usuário logar tem permissão na url passado, retorna false, se tiver logado retorna true
     */
    public static function validarMenuProfile($url) {
        return (array_search($url, $_SESSION['s_menuProfile']) !== false) ? true : false;
    }

    /**
     * Retorna o User que está logado
     *
     * @return User
     */
    public static function getUserSession() {
        GF::importClass(array('user'));

        return isset($_SESSION['s_user']) && !is_array($_SESSION['s_user']) ? unserialize($_SESSION['s_user']) : false;
    }

    /**
     * Retorna a instituição selecionada
     *
     * @return ResourceProfile
     */
    public static function getResourceProfileSession() {
        GF::importClass(array('resourceProfile', 'profile'));

        return isset($_SESSION['s_resourceProfile']) && !is_array($_SESSION['s_resourceProfile']) ? unserialize($_SESSION['s_resourceProfile']) : false;
    }

    /**
     * Valida se o usuário logado tem permissão ao recurso passado por parâmetro
     * 
     * @param string $rec_var_key
     */
    public static function validarPermissao($rec_var_key) {
        $arrayResourceProfile = GSec::getResourceProfileSession();
        foreach ($arrayResourceProfile as $resourceProfile) {
            if ($resourceProfile->getResource()->getRec_var_key() == $rec_var_key) {
                return true;
            }
        }
        return false;
    }

    /**
     * 
     * @param User $userNovoSes
     */
    public static function updateUserSession($userNovoSes) {
        unset($_SESSION["s_user"]);
        $_SESSION["s_user"] = serialize($userNovoSes);
    }

    public static function pingUserSession() {
        // try {
        //     $mysql = new GDbMysql();
        //     $mysql->execute('CALL sp_adm_ping(?)', array('i', GSec::getUserSession()->getUsr_int_id()), false);
        // } catch (GDbException $exc) {
        //     echo $exc->getError();
        // }
    }

}

?>
