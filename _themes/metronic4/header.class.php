<?php

class GHeader extends GHeaderParent {

    private $titulo;
    private $subtitulo;

    function __construct($tilulo, $subtitulo = '') {
        $this->titulo = $tilulo;
        $this->subtitulo = $subtitulo;

        parent::__construct($tilulo);
    }

    /**
     * Exibir o cabeçalho da página completo
     *
     * @param boolean $isIframe default: false
     * @param string $currentMenu default: '' caminho completo para a página
     * @param string $breadcrumb default: ''
     */
    function show($isIframe = false, $currentMenu = '', $breadcrumb = '') {
        if (GSec::validarLogin() && !empty($currentMenu)) {
            // GSec::verificarMenuProfile($currentMenu);
        }
        parent::show();
        $userSession = GSec::getUserSession();

        $html = '';
        $html .= '<meta name="description" content="">';
        $html .= '<meta name="author" content="">';

        $html .= '<!--[if lt IE 9]> ';
        $html .= '<script src="' . URL_SYS_THEME . 'plugins/respond.min.js"></script> ';
        $html .= '<script src="' . URL_SYS_THEME . 'plugins/excanvas.min.js"></script> ';
        $html .= '<![endif]-->';

        // fechar head
        $html .= '</head>';

        if (!$isIframe) {
            if ($this->_bodyClass != "")
                $html .= '<body class="' . $this->_bodyClass . '">';
            else
                $html .= '<body class="page-md">';

            if (GSec::validarLogin()) {
                $html .= '<div class="page-header">'; 
                $html .= '<div class="page-header-top">';
                $html .= '<div class="container">';

                $html .= '<div class="page-logo">';
                $html .= '<a href="' . URL_SYS . 'dashboard/dashboard.php"><img src="' . URL_SYS_THEME . 'img/logo-continental.png" alt="logo" class="logo-default" /></a>';
                $html .= '</div>';

                $html .= '<a href="javascript:;" class="menu-toggler"></a>';

                //<editor-fold desc="Informações do usuário à direita">
                $arrUser = explode(' ', $userSession->getUsr_var_name());
                $usr_var_name = $arrUser[0];
                $usr_int_id =  $userSession->getUsr_int_id();
                $arrayUserInfo = getUserInfo($usr_int_id);
                $pro_var_name = $arrayUserInfo['pro_var_name'];
                $info = serialize($arrayUserInfo);

                $html .= '<div class="top-menu">';
                $html .= '<ul class="nav navbar-nav pull-right">
                            <li class="dropdown dropdown-user dropdown-dark">
                                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                    <span class="username username-hide-mobile">' . $usr_var_name . ' | ' . $pro_var_name . '</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-default">
                                    <li><a href="' . URL_SYS . 'profile/profile.php"><i class="fa fa-user"></i> Update profile</a></li>
                                    <li><a href="' . URL_SYS . 'profile/password.php"><i class="fa fa-key"></i> Update password</a></li>
                                    <li class="divider"></li>
                                    <li><a href="' . URL_SIGNOUT . '"><i class="fa fa-power-off"></i> Logout</a></li>
                                </ul>
                            </li>
                        </ul>';

                $html .= '</div>'; //.top-menu
                $html .= '</div>'; //.container
                $html .= '</div>'; //.page-header-top

                $html .= '<div class="page-header-menu">';
                $html .= '<div class="container">';

                $html .= '<div class="hor-menu">';
                $html .= $this->getMenu($currentMenu);
                $html .= '</div>';

                $html .= '</div>'; //.container
                $html .= '</div>'; //.page-header-menu

                $html .= '<div class="page-container">';

                $html .= '<div class="page-head">';
                $html .= '<div class="container">';

                if (!empty($this->titulo)) {
                    $html .= '<div class="page-title">';
                    $html .= '<h1>' . $this->titulo . ' ';
                    if (!empty($this->subtitulo)) {
                        $html .= '<small>' . $this->subtitulo . '</small>';
                    }
                    $html .= '</h1>';
                    $html .= '</div>';
                }

                $html .= '</div>'; //.container
                $html .= '</div>'; //.page-head

                $html .= '<div class="page-content">';
                $html .= '<div class="container">';
            }
        } else {
            if ($this->_bodyClass != "")
                $html .= '<body class="' . $this->_bodyClass . '">';
            else
                $html .= '<body>';
        }



        echo $html;
    }

    function getMenu($current) {
        $mysql = new GDbMysql();

        $html = '';
        $query = "SELECT men_int_id, men_var_name, men_var_icon,
                         men_var_class, men_var_url, men_cha_type
                    FROM vw_adm_menu
                   WHERE men_int_level = 1
                     AND men_cha_status = 'A'
                     AND EXISTS (SELECT 1
                                   FROM vw_adm_menu_profile
                                  WHERE vw_adm_menu.men_int_id IN (vw_adm_menu_profile.men_int_idfather, vw_adm_menu_profile.men_int_id)
                                    AND vw_adm_menu_profile.pro_int_id = ?)
                ORDER BY men_var_key";
        $param = array('i', GSec::getUserSession()->getProfile()->getPro_int_id());
        $mysql->execute($query, $param);

        $html .= '<ul class="nav navbar-nav">';
        while ($mysql->fetch()) {
            $mysql2 = new GDbMysql();
            $query2 = "SELECT men_int_id, men_var_name, men_var_icon,
                              men_var_url,men_var_class, men_cha_type
                         FROM vw_adm_menu
                        WHERE men_int_idfather = ?
                          AND men_cha_status = 'A'
                          AND EXISTS (SELECT 1
                                        FROM vw_adm_menu_profile
                                       WHERE vw_adm_menu.men_int_id = vw_adm_menu_profile.men_int_id
                                         AND vw_adm_menu_profile.pro_int_id = ?)
                        ORDER BY men_var_key ";
            $param2 = array('ii', $mysql->res['men_int_id'], GSec::getUserSession()->getProfile()->getPro_int_id());
            $mysql2->execute($query2, $param2);

            if ($mysql2->numRows() > 0) {
                $html .= '<li class="menu-dropdown classic-menu-dropdown ">';

                $html .= '<a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown" href="javascript:;">';
                if(!empty($mysql->res['men_var_icon'])){
                    $html .= '<i class="' . $mysql->res['men_var_icon'] . '"></i> ';
                }
                $html .= $mysql->res['men_var_name'];
                $html .= ' <i class="fa fa-angle-down"></i>';
                $html .= '</a>';

                $html .= '<ul class="dropdown-menu pull-left">';

                while ($mysql2->fetch()) {
                    // Verifica se a url existe
                    $men_var_url = $mysql2->res['men_var_url'];
                    $style = 'color: #c7c7c7 !important;';
                    $url = '#';
                    if (file_exists(ROOT_SYS . $men_var_url)) {
                        $url = URL_SYS . $men_var_url;
                        $style = ($mysql2->res['men_cha_type'] == 'P') ? 'color: #3b7bea !important;' : '';
                    }
                    $active = (stripos($mysql2->res['men_var_url'], $current) !== false) ? 'active' : '';

                    $html .= '<li class="' . $active . '">';
                    $html .= '<a href="' . $url . '" style="' . $style . '">';

                    if(!empty($mysql2->res['men_var_icon'])){
                        $html .= '<i class="' . $mysql2->res['men_var_icon'] . '"></i> ';
                    }
                    
                    $html .= $mysql2->res['men_var_name'];
                    $html .= '</a>';
                    $html .= '</li>';
                }

                $html .= '</ul>';
                $html .= '</li>';
            } else {
                // Verifica se a url existe
                $men_var_url = $mysql->res['men_var_url'];
                $style = 'color: #c7c7c7 !important;';
                $url = '#';
                if (!empty($mysql->res['men_var_url'])) {
                    if (file_exists(ROOT_SYS . $men_var_url)) {
                        $url = URL_SYS . $men_var_url;
                        $style = ($mysql->res['men_cha_type'] == 'P') ? 'color: #3b7bea !important;' : '';
                    }
                }
                $active = (stripos($mysql->res['men_var_url'], $current) !== false) ? 'active' : '';

                $html .= '<li class="' . $active . '">';
                $html .= '<a href="' . $url . '" style="' . $style . '">';
                if(!empty($mysql->res['men_var_icon'])){
                    $html .= '<i class="' . $mysql->res['men_var_icon'] . '"></i> ';
                }
                $html .= $mysql->res['men_var_name'];
                $html .= '</a>';
                $html .= '</li>';
            }

            $mysql2->close();
        }
        $html .= '</ul>';

        $html .= '
            <script>
                // $(function(){
                //     $(".sub-menu").find(".active").parents(".nivel1").addClass("active");
                //     // var id = $(".left-secondary-nav").find(".active").parents(".tab-pane").attr("id");
                //     // $(".left-primary-nav").find(\'a[href="#\'+id+\'"]\').parent().addClass("active");
                // });
            </script>';
        return $html;
    }

    function getBreacrumb($breadcrumb) {
        $html = '';

        $html .= '';
        $html .= '';
        //TODO: Implementar os breadcrumbs de acordo com o sistema
        //return $html;
    }

}

?>