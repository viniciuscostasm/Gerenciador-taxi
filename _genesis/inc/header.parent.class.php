<?php

/**
 * Classe mãe para exibição do cabeçalho da página
 *
 */
class GHeaderParent {

    var $_titulo = "";
    var $_metas = "";
    var $_css = "";
    var $_scripts = "";
    var $_bodyClass = "";
    var $_min = "min";
    var $_libs = array();

    /**
     * Inciar a classe carregando aos arquivos do tema e definindo um título
     *
     * @param String $title
     */
    function __construct($title) {
        $this->_titulo = strip_tags($title) . ' | ' . SYS_TITLE;

        //$this->_libs = getLibsDefault();
        $this->_libs = array_merge(getLibsDefault(), getLibsTheme());

        $libDefault = explode(',', SYS_LIB_DEFAULT);
        $this->addLib($libDefault);

        require_once (ROOT_SYS_THEME . 'theme.php');
        $theme = new Theme();
        $this->addTheme($theme->getFiles());
    }

    /**
     * Adicionar uma String com metas extras
     *
     * @param String $metas
     */
    function addMetas($metas) {
        $this->_metas = $metas;
    }

    /**
     * Adicionar um css no array de css
     *
     * @param String $scripts
     */
    function addCSS($css) {
        $this->_css[] = $css;
    }

    /**
     * Adicionar um script no array de scripts js
     *
     * @param String $script
     */
    function addScript($script) {
        $this->_scripts[] = $script;
    }

    /**
     * Adicionar uma classe ao body
     *
     * @param String $bodyClass
     */
    function addBodyClass($bodyClass) {
        $this->_bodyClass = $bodyClass;
    }

    /**
     * Adicionar um array de bibliotecas
     *
     * @param array $bibliotecas ex: array('flexigrid','datepicker','ckeditor')
     *
     */
    function addLib($bibliotecas) {
        foreach ($bibliotecas as $bib) {
            if ($bib != '') {
                $arquivos = $this->_libs[$bib];
                foreach ($arquivos as $arq) {
                    $tipo = explode(".", $arq);
                    if ($tipo[count($tipo) - 1] == 'css') {
                        $this->addCSS($arq);
                    } else if ($tipo[count($tipo) - 1] == 'js') {
                        $this->addScript($arq);
                    } else if ($tipo[count($tipo) - 1] == 'php') {
                        require_once $arq;
                    }
                }
            }
        }
    }

    /**
     * Remover uma biblioteca específica
     *
     * @param string $biblioteca
     */
    function removeLib($biblioteca) {
        $arquivos = $this->_libs[$biblioteca];
        foreach ($arquivos as $arq) {
            $tipo = explode(".", $arq);
            if ($tipo[count($tipo) - 1] == 'css') {
                $key = array_search($arq, $this->_css);
                unset($this->_css[$key]);
            } else if ($tipo[count($tipo) - 1] == 'js') {
                $key = array_search($arq, $this->_scripts);
                unset($this->_scripts[$key]);
            }
        }
    }

    /**
     * Adicionar os arquivos do tema
     *
     * @param array $arquivos
     */
    function addTheme($arquivos) {
        foreach ($arquivos as $arq) {
            $tipo = explode(".", $arq);
            if ($tipo[count($tipo) - 1] == 'css') {
                $this->addCSS(URL_SYS_THEME . $arq);
            } else if ($tipo[count($tipo) - 1] == 'js') {
                $this->addScript(URL_SYS_THEME . $arq);
            }
        }
    }

    /**
     * Renderizar todo o Cabeçalho da página com todos os parametros.
     *
     * @param bool $isIframe
     * @return $html
     */
    function get($isIframe) {
        $html = '';
        $bg = ($isIframe) ? 'style="background:#FFF;min-width: 10px;"' : '';
        $html .= '<!DOCTYPE html>';
        $html .= '<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->';
        $html .= '<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->';
        $html .= '<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->';
        $html .= '<head>';
        $html .= '<meta charset="utf-8">';
        $html .= '<title>' . $this->_titulo . '</title>';
        $html .= '<script> var URL_SYS = "' . URL_SYS . '"; var URL_SYS_THEME = "' . URL_SYS_THEME . '"; </script>';

        $html .= '<meta content="width=device-width, initial-scale=1.0" name="viewport" />';

        // gerar metas
        if ($this->_metas != "") {
            $html .= $this->_metas;
        }

        // gerar css montado
        if ($this->_css != "") {
            foreach ($this->_css as $style) {
                $html .= '<link href="' . $style . '" rel="stylesheet" type="text/css" />';
            }
        }

        // gerar scripts montados
        if ($this->_scripts != "") {
            foreach ($this->_scripts as $js) {
                $html .= '<script src="' . $js . '" type="text/javascript" charset="utf-8"></script>';
            }
        }

        return $html;
    }

    /**
     * Imprimir todo o Cabeçalho da página com todos os parametros.
     *
     * @param bool $isIframe
     * @return $html
     */
    function show($isIframe = false) {
        echo $this->get($isIframe);
    }

}

?>