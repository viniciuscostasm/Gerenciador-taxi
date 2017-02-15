<?php

/**
 * Arquivo com as funções que serão utilizadas em todo o sistema.
 * Este arquivo já está inserido automaticamente em global.php
 */

/**
 * Retorna o html com os botões de filtro padrão
 *
 * @param bool $expandir default=false
 * @return string
 */
function getBotoesFiltro($expandir = false) {
    $form = new GForm();
    $retorno = '';
    $retorno .= $form->addInput('hidden', 'p__selecionado', false, false, false, false, false);

    $retorno .= $form->addButton('p__btn_filtrar', '<i class="fa fa-search"></i>', array('class' => 'btn  pull-left grey-cascade', 'data-title' => 'Search'), 'submit');
    $retorno .= $form->addButton('p__btn_limpar', '<i class="fa fa-reply"></i>', array('class' => 'btn pull-left sepV_a hidden-phone', 'data-title' => 'Clear filter'));
    if ($expandir) {
        $retorno .= $form->addButton('p__btn_expandir', '<i class="fa fa-filter"></i>', array('class' => 'btn pull-left sepV_a hidden-phone', 'data-title' => 'Advanced filter'));
        $retorno .= "<script>";
        $retorno .= "$('#p__btn_expandir').click(function() {";
        $retorno .= "var display = $('#divFiltrosAvancados').css('display');";
        $retorno .= "if (display === 'none') {";
        $retorno .= "$('#divFiltrosAvancados').show();";
        $retorno .= "} else {";
        $retorno .= "$('#divFiltrosAvancados').hide();";
        $retorno .= "}";
        $retorno .= "});";
        $retorno .= '$("#p__btn_expandir").tooltip({placement:"top", container:"body"}); ';
        $retorno .= '</script>';
    }
    $retorno .= '<script> $("#p__btn_limpar, #p__btn_filtrar").tooltip({placement:"top", container:"body"}); </script>';


    return $retorno;
}

function getBotaoExportar($excel = TRUE, $pdf = TRUE, $arrayOpcoesPdf = NULL) {
    $form = new GForm();
    $retorno .= '<div class="btn-group hidden-phone pull-left sepV_a">';
    $retorno .= $form->addButton('p__btn_relatorio', '<i class="fa fa-print" style="opacity: 0.5;"></i>', array('class' => 'btn', 'title' => 'Relatórios', 'data-toggle' => 'dropdown'));
    $retorno .= '<ul class="dropdown-menu">';
    if ($pdf) {
        if (empty($arrayOpcoesPdf)) {
            $retorno .= '<li><a class="p__btn_exportar" rel="pdf">Export to PDF</a></li>';
        } else {
            foreach ($arrayOpcoesPdf AS $key => $title) {
                $retorno .= '<li><a class="p__btn_exportar" rel="' . $key . '">' . $title . '</a></li>';
            }
        }
    }
    if ($excel) {
        $retorno .= '<li><a class="p__btn_exportar" rel="xls">Export to Excel</a></li>';
    }
    $retorno .= '</ul>';
    $retorno .= '</div>';
    $retorno .= $form->addInput('hidden', 'p__tipo_exportar', false, false, false, false, false);

    $retorno .= "<script>";
    $retorno .= "$(document).on('click', '.p__btn_exportar', function() { ";
    $retorno .= "$('#p__tipo_exportar').val($(this).attr('rel')); ";
    $retorno .= "$('#filter').attr('target', '_blank').attr('action', pagReport).submit().attr('target', '_self').attr('action', ''); ";
    $retorno .= "$('#p__btn_relatorio').click(); ";
    $retorno .= "return false; ";
    $retorno .= "});";
    $retorno .= '$("#p__btn_relatorio").tooltip({placement:"top", container:"body"});';
    $retorno .= "</script>";


    return $retorno;
}

/**
 *
 * @param bool $excluir defaul=false
 * @return type
 */
function getBotoesAcao($excluir = false, $cancelar = true) {
    $form = new GForm();
    $retorno = '';

    $retorno .= $form->addButton('f__btn_salvar', '<i class="fa fa-check"></i> Salvar', array('class' => 'btn blue pull-left sepV_b'), 'submit');
    if ($cancelar) {
        $retorno .= $form->addButton('f__btn_cancelar', '<i class="fa fa-ban"></i> Cancelar', array('class' => 'btn pull-left'));
        $retorno .= "<script> $('#f__btn_cancelar').hover(function() { $(this).addClass('yellow'); }, function() { $(this).removeClass('yellow'); }); </script>";
    }

    if ($excluir) {
        $retorno .= $form->addButton('f__btn_excluir', '<i class="fa fa-trash"></i> Remover', array('class' => 'btn pull-right'));
        $retorno .= "<script> $('#f__btn_excluir').hover(function() { $(this).addClass('red'); }, function() { $(this).removeClass('red'); }); </script>";
    }

    return $retorno;
}

function getBotaoAdicionar($id = 'p__btn_adicionar') {
    $form = new GForm();
    return $form->addButton($id, '<i class="fa fa-plus"></i> <span class="hidden-phone">Adicionar</span>', array('class' => 'btn sepH_a sepV_a blue-steel pull-left'));
}

function getImagemStatus($status, $statusNome) {
    if ($status == 'A') {
        $img = 'verde_24.png';
    } else if ($status == 'I') {
        $img = 'vermelho_24.png';
    } else if ($status == 'B') {
        $img = 'amarelo_24.png';
    }

    return '<img src="' . URL_SYS_THEME . 'img/icones/' . $img . '" title="' . $statusNome . '" />';
}

function validaCPF($cpf) {

    // Verifiva se o número digitado contém todos os digitos
    $cpf = str_pad(preg_replace('/[^0-9]/i', '', $cpf), 11, '0', STR_PAD_LEFT);

    // Verifica se nenhuma das sequências abaixo foi digitada, caso seja, retorna falso
    if (strlen($cpf) != 11 || $cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' || $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999') {
        return false;
    } else {   // Calcula os números para verificar se o CPF é verdadeiro
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf{$c} * (($t + 1) - $c);
            }

            $d = ((10 * $d) % 11) % 10;

            if ($cpf{$c} != $d) {
                return false;
            }
        }

        return true;
    }
}

function validaDominio($dominio) {
    if (!empty($dominio)) {
        if (strstr($dominio, '@'))
            list ($user, $dominio) = explode('@', $dominio);

        if (checkdnsrr($dominio, 'MX')) {
            return true;
        } else {
            return false;
        }
    } else {
        return true;
    }
}

/**
 *
 * @param string $title
 * @param string $tools ''
 * @param string $cor 'blue'
 * @param string $span 'span12'
 * @return string
 */
function getWidgetHeader($title = '', $tools = '', $cor = 'blue-steel', $span = 'col-md-12') {
    $html = '';
    $html .= '<div class="' . $span . '">';
    $html .= '<div class="portlet light">';

    //<editor-fold desc="portlet-title">
    if(!empty($title)){
        $html .= '<div class="portlet-title">';
        $html .= '<div class="caption">';
        $html .= '<span class="caption-subject bold uppercase font-' . $cor . '">' . $title . '</span>';
        $html .= '</div>';

        if (!empty($tools)) {
            $html .= '<div class="tools ' . $cor . '">';
            $html .= $tools;
            $html .= '</div>';
        }
        $html .= '</div>';
    }
    //</editor-fold>

    $html .= '<div class="portlet-body form">';

    return $html;
}

function getWidgetFooter() {
    $html = '';
    $html .= '</div>'; //portlet-body
    $html .= '</div>'; //portlet
    $html .= '</div>'; //span6

    return $html;
}

/**
 *
 * @param string $span 'span12'
 * @param string $cor 'bondi-blue'
 * @param string $numero ''
 * @param string $icone ''
 * @param string $titulo ''
 */
function getIndicadorIcone($span = 'span12', $cor = 'bondi-blue', $numero = '', $icone = '', $titulo = '') {
    $html = '';
    $html .= '<div class="row-fluid">';
    $html .= '<div class="' . $span . '">';
    $html .= '<div class="board-widgets small-widget ' . $cor . '">';
    $html .= '<a>';
    $html .= '<span class="widget-stat">' . $numero . '</span>';
    $html .= '<span class="widget-icon ' . $icone . '"></span>';
    $html .= '<span class="widget-label">' . $titulo . '</span>';
    $html .= '</a>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';

    return $html;
}

/**
 *
 * @param string $span 'span12'
 * @param string $cor 'bondi-blue'
 * @param string $numero ''
 * @param string $icone ''
 * @param string $titulo ''
 */
function getIndicador($span = 'span12', $cor = 'blue', $numero = '', $icone = '', $titulo = '', $url = '') {
    $html = '';
    $html .= '<div class="row-fluid">';
    $html .= '<div class="' . $span . ' responsive" data-desktop="' . $span . '" data-tablet="' . $span . '">
                <div class="dashboard-stat ' . $cor . '">';
    if (!empty($icone)) {
        $html .= '      <div class="visual">
                            <i class="' . $icone . '"></i>
                        </div>';
    }
    $html .= '      <div class="details">
                        <div class="number"> ' . $numero . ' </div>
                        <div class="desc"> ' . $titulo . ' </div>
                    </div>';
    if (!empty($url)) {
        $html .= '      <a class="more" href="' . URL_SYS . $url . '">
                            Ver mais
                            <i class="m-fa fa-swapright m-fa fa-white"></i>
                        </a>';
    }
    $html .= '  </div>
              </div>
              </div>';

    return $html;
}

function validaIp($ip) {
    return preg_match("/^(([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]).){3}([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/", $ip);
}

/**
 *
 * @param string $titulo
 * @param string $span 'span12'
 * @return string
 */
function getWidgetViewHeader($titulo, $span = 'span12') {
    $html = '<div class="row-fluid">';
    $html .= '<div class="' . $span . '">';
    $html .= '<div class="widget-view">';
    $html .= '<div class="widget-view-header clearfix">';
    $html .= '<h5>' . $titulo . '</h5>';
    $html .= '</div>';
    $html .= '<div class="widget-view-container">';

    return $html;
}

function getWidgetViewFooter() {
    $html = '';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';

    return $html;
}

function getHeaderPastas($pes_var_name, $ani_var_name = '') {
    $html = '';

    $html .= '<div class="widget-head blue clearfix">';
    $html .= '<h3 class="acaoTitulo pull-left">' . $pes_var_name . '</h3>';
    $html .= '<a class="widget-button pull-right v__btn_fechar_pro"><i class="fa fa-remove"></i></a>';
    $html .= '</div>';

    if (!empty($ani_var_name)) {
        $html .= '<div class="widget-head green clearfix">';
        $html .= '<h3 class="acaoTituloAni pull-left">' . $ani_var_name . '</h3>';
        $html .= '<a class="widget-button pull-right v__btn_fechar_ani"><i class="fa fa-remove"></i></a>';
        $html .= '</div>';
    }
    return $html;
}

/**
 * Formatar número para quantidade de estoque, com casa decimal apenas se houver e hifen se vazio
 *
 * @param dec $numero
 * @return type
 */
function numberStock($number) {
    $number = (($number != 0) ? str_replace('.00', '', $number) : '-');
    return $number;
}

function getComboMenu($id, $title = false, $param = false, $control = true, $where = '') {
    $mysqlCombo = new GDbMysql();
    $form = new GForm();

    $queryCombo = "SELECT men_int_id, men_var_name,
                          men_int_level, men_cha_consolidator
                     FROM vw_adm_menu
                          " . $where . "
                 ORDER BY men_var_key";
    $mysqlCombo->execute($queryCombo);

    $combo = ($control) ? '<div class="form-group">' : '';
    $combo .= ($title) ? $form->addLabel($id, $title, array('class' => 'control-label')) : '';
    $combo .= ($control) ? '<div class="controls">' : '';
    $combo .= '<select id="' . $id . '" name="' . $id . '" ';
    if ($param) {
        foreach ($param as $key => $value) {
            $combo .= $key . '="' . $value . '"';
        }
    }
    $combo .= '>';
    $combo .= '<option value="">Select...</option>';
    while ($mysqlCombo->fetch()) {
        $qtd = (($mysqlCombo->res['men_int_level'] - 1) * 5);
        $men_var_name = $mysqlCombo->res['men_var_name'];
        $count = strlen($men_var_name);

        $combo .= '<option value="' . $mysqlCombo->res['men_int_id'] . '">';
        $combo .= str_replace('_', '&nbsp;', str_pad($men_var_name, $qtd + $count, "_", STR_PAD_LEFT));
        $combo .= '</option>';
    }
    $combo .= '</select>';
    $combo .= ($control) ? '</div></div>' : '';
    return $combo;
}

function formataTipoDado($datatype, $tamanho) {
    switch ($datatype) {
        case 'VARCHAR':
            $datatypeDefinitivo = $datatype . '(' . $tamanho . ')';
            break;
        case 'TEXT':
            $datatypeDefinitivo = $datatype;
            break;
        case 'INT':
            $datatypeDefinitivo = $datatype . '(' . $tamanho . ')';
            break;
        case 'DATE':
            $datatypeDefinitivo = $datatype;
            break;
        case 'DECIMAL':
            $datatypeDefinitivo = $datatype . '(' . $tamanho . ')';
            break;
    }
    return $datatypeDefinitivo;
}
/**
 * Função para retornar um id aleatório
 *
 * @return string
 */

function geraPalavra($ini, $fim) {
    $CaracteresAceitos = '0123456789abcdefghijklmnopqrstuvxywz';
    $CaracteresAceitos = str_shuffle($CaracteresAceitos);
    $max = strlen($CaracteresAceitos) - 1;
    $palavra = NULL;
    for ($i = 0; $i < 4; $i++) {
        $palavra .= $CaracteresAceitos{mt_rand(0, $max)};
    }
    return str_shuffle(substr(uniqid($palavra, true), $ini, $fim));
}

function primeiroUltimoNome ($texto) {
    $arrTexto = explode(' ',$texto);
    $primeiro = $arrTexto[0];
    $ultimo = (count($arrTexto) > 1) ? $arrTexto[count($arrTexto)-1] : '';
    $retorno = trim($primeiro . ' ' . $ultimo);
    return $retorno;
}

//funcao para submeter um POST
function do_post_request($url, $data, $optional_headers = null) {
    // $params = array('http' => array(
    //                 'method' => 'POST',
    //                 'content' => $data
    //         ));
    // if ($optional_headers !== null) {
    //     $params['http']['header'] = $optional_headers;
    // }
    // $ctx = stream_context_create($params);
    // $fp = @fopen($url, 'rb', false, $ctx);
    // if (!$fp) {
    //     throw new Exception("Problem with $url, $php_errormsg");
    // }
    // $response = @stream_get_contents($fp);
    // if ($response === false) {
    //     throw new Exception("Problem reading data from $url, $php_errormsg");
    // }


    $cURL = curl_init($url);
    @curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
    @curl_setopt($cURL, CURLOPT_POST, true);
    @curl_setopt($cURL, CURLOPT_POSTFIELDS, $data);
    $vResultado = @curl_exec($cURL);
    if ($vResultado) {
        // echo($vResultado);
        curl_close($cURL);
        return json_decode($vResultado);
    } else {
        throw new Exception("Erro no processamento da URL: " . $url . ".", 1);
    }
}

function cabecalhoFPDF($pdf, $conteudo) {
    $pdf->SetTextColor(0);
    $pdf->SetFillColor(220,220,220);
    $pdf->SetDrawColor(70,70,70);
    $pdf->SetFont('Arial','B',$fontSize);
    foreach ($conteudo as $coluna) {
        $width = empty($coluna['width']) ? 0 : $coluna['width'];
        $align = empty($coluna['align']) ? 'C' : $coluna['align'];
        $pdf->Cell($width, 6, utf8_decode($coluna['text']), 1, 0, $align, true);
    }
    $pdf->SetFont('Arial','',$fontSize);
    $pdf->Ln();
}

function tituloGrupoFPDF($pdf, $conteudo, $fontSize = 8, $width = 190) {
    $pdf->SetDrawColor(39,169,227);
    $pdf->SetFillColor(39,169,227);
    $pdf->SetTextColor(255);
    $pdf->SetFont('','',$fontSize+3);
    $pdf->Cell($width, 7, utf8_decode($conteudo), 1, 0, 'L', true);
    $pdf->SetFont('','',$fontSize);
    $pdf->Ln();
}


function formatarEmail($arrayConteudo, $environment = NULL) {
    global $__arraySocial;

    $html = '';
    //<editor-fold desc="Cabeçalho">
    $html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>' . SYS_TITLE . '</title>
        <style type="text/css">
            #outlook a{padding:0;} /* Force Outlook to provide a "view in browser" button. */
            body{width:100% !important; margin:0;}
            body{-webkit-text-size-adjust:none;} /* Prevent Webkit platforms from changing default text sizes. */

            body{margin:0; padding:0;}
            img{border:0; height:auto; line-height:100%; outline:none; text-decoration:none;}
            table td{border-collapse:collapse;}
            #backgroundTable{height:100% !important; margin:0; padding:0; width:100% !important;}

            body, #backgroundTable{ background-color:#FFF; }

            .TopbarLogo{
                padding:10px;
                text-align:left;
                vertical-align:middle;
            }

            h1, .h1{
                color:#444444;
                display:block;
                font-family: Arial, Helvetica, sans-serif;
                font-size:35px;
                font-weight: 400;
                line-height:100%;
                margin-top:2%;
                margin-right:0;
                margin-bottom:1%;
                margin-left:0;
                text-align:left;
            }

            h2, .h2{
                color:#444444;
                display:block;
                font-family: Arial, Helvetica, sans-serif;
                font-size:30px;
                font-weight: 400;
                line-height:100%;
                margin-top:2%;
                margin-right:0;
                margin-bottom:1%;
                margin-left:0;
                text-align:left;
            }

            h3, .h3{
                color:#444444;
                display:block;
                font-family: Arial, Helvetica, sans-serif;
                font-size:24px;
                font-weight:400;
                margin-top:2%;
                margin-right:0;
                margin-bottom:1%;
                margin-left:0;
                text-align:left;
            }

            h4, .h4{
                color:#444444;
                display:block;
                font-family: Arial, Helvetica, sans-serif;
                font-size:18px;
                font-weight:400;
                line-height:100%;
                margin-top:2%;
                margin-right:0;
                margin-bottom:1%;
                margin-left:0;
                text-align:left;
            }

            h5, .h5{
                color:#444444;
                display:block;
                font-family: Arial, Helvetica, sans-serif;
                font-size:14px;
                font-weight:400;
                line-height:100%;
                margin-top:2%;
                margin-right:0;
                margin-bottom:1%;
                margin-left:0;
                text-align:left;
            }

            .textdark {
                color: #444444;
                font-family: Arial, Helvetica, sans-serif;
                font-size: 13px;
                line-height: 150%;
                text-align: left;
            }

            .textwhite {
                color: #fff;
                font-family: Arial, Helvetica, sans-serif;
                font-size: 13px;
                line-height: 150%;
                text-align: left;
            }

            .fontwhite { color:#fff; }

            .btn {
                background-color: #e5e5e5;
                background-image: none;
                filter: none;
                border: 0;
                box-shadow: none;
                padding: 7px 14px;
                text-shadow: none;
                font-family: Arial, Helvetica, sans-serif;
                font-size: 14px;
                color: #333333;
                cursor: pointer;
                outline: none;
                -webkit-border-radius: 0 !important;
                    -moz-border-radius: 0 !important;
                        border-radius: 0 !important;
            }

            .btn:hover,
            .btn:focus,
            .btn:active,
            .btn.active,
            .btn[disabled],
            .btn.disabled {
              font-family: Arial, Helvetica, sans-serif;
              color: #333333;
              box-shadow: none;
              background-color: #d8d8d8;
            }

            .btn.red {
                color: white;
                text-shadow: none;
                background-color: #d84a38;
            }

            .btn.red:hover,
            .btn.red:focus,
            .btn.red:active,
            .btn.red.active,
            .btn.red[disabled],
            .btn.red.disabled {
                background-color: #bb2413 !important;
                color: #fff !important;
            }

            .btn.green {
                color: white;
                text-shadow: none;
                background-color: #35aa47;
            }

            .btn.green:hover,
            .btn.green:focus,
            .btn.green:active,
            .btn.green.active,
            .btn.green.disabled,
            .btn.green[disabled]{
                background-color: #1d943b !important;
                color: #fff !important;
            }
        </style>
    </head>
    <body>
        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color:#ffffff; height:52px;">
            <tr>
                <td align="center">
                    <center>
                        <table border="0" cellpadding="0" cellspacing="0" width="600px" style="height:100%;">
                            <tr>
                                <td align="left" valign="middle" style="padding-left:20px;">
                                    <a href="' . URL_SYS . '">
                                        <img src="' . URL_SYS_THEME . 'img/logo-continental.png' . '" alt="' . SYS_TITLE . '"/>
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </center>
                </td>
            </tr>
        </table>';
    //</editor-fold>
    $stripped = FALSE;
    foreach ($arrayConteudo AS $titulo => $conteudo) {
        if (!$stripped) {
            //<editor-fold desc="Seção branca">
            $html .= '<table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td style="padding-bottom:20px;">
                                <center>
                                    <table border="0" cellpadding="0" cellspacing="0" width="600px" style="height:100%;">
                                        <tr>
                                            <td valign="top" class="bodyContent">
                                                <table border="0" cellpadding="20" cellspacing="0" width="100%">
                                                    <tr>
                                                        <td valign="top">
                                                            <h2 class="h2">' . $titulo . '</h2>
                                                            <br />
                                                            <div class="textdark">' . $conteudo . '</div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </center>
                            </td>
                        </tr>
                    </table>';
            //</editor-fold>
        } else {
            //<editor-fold desc="Seção cinza">
            $html .= '<table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color:#f8f8f8;border-top:1px solid #e7e7e7;border-bottom:1px solid #e7e7e7;">
                        <tr>
                            <td>
                                <center>
                                    <table border="0" cellpadding="0" cellspacing="0" width="600px" style="height:100%;">
                                        <tr>
                                            <td valign="top" style="padding:20px;">
                                                <h2>' . $titulo . '</h2>
                                                <br />
                                                <div class="textdark">' . $conteudo . '</div>
                                                <br />
                                            </td>
                                        </tr>
                                    </table>
                                </center>
                            </td>
                        </tr>
                    </table>';
            //</editor-fold>
        }
        $stripped = ($stripped) ? FALSE : TRUE;
    }
    //<editor-fold desc="Redapé">
    $html .= '<table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color:#ffffff;">
            <tr>
                <td align="center">
                    <center>
                        <table border="0" cellpadding="0" cellspacing="0" width="600px" style="height:100%;">
                            <tr>
                                <td align="right" valign="middle" style="font-size:12px; padding:20px; color: #000000;">
                                    <hr>
                                    Automated message sent by the system, please do not respond.
                                    <br />
                                    &copy; 2015 ' . SYS_TITLE . '.
                                </td>
                            </tr>
                        </table>
                    </center>
                </td>
            </tr>
        </table>
        </body>
              </html>';
    //</editor-fold>
    return $html;
}

function getUserInfo($usr_int_id) {
    try {
        $ret = array();

        $mysql = new GDbMysql();

        $query = "SELECT pro_cha_type, pro_var_name FROM vw_adm_user WHERE usr_int_id = ?";
        $param = array('i', $usr_int_id);
        $mysql->execute($query, $param);
        if ($mysql->fetch()) {
            $pro_cha_type = $mysql->res['pro_cha_type'];
            $pro_var_name = $mysql->res['pro_var_name'];
            $mysql->freeResult();

            $ret['pro_cha_type'] = $pro_cha_type;
            $ret['pro_var_name'] = $pro_var_name;

            if($pro_cha_type == 'PLA'){
                $query = "SELECT pla_int_id, pla_var_name FROM vw_adm_user_plant WHERE usr_int_id = ?";
                $param = array('i', $usr_int_id);
                $mysql->execute($query, $param);
                while ($mysql->fetch()) {
                    $arrayPlants[$mysql->res['pla_int_id']] = $mysql->res['pla_var_name'];
                    $arrayPlantsIds[] = $mysql->res['pla_int_id'];
                }
                $ret['plants'] = $arrayPlants;
                $ret['plants_ids'] = implode(',', $arrayPlantsIds);

                $mysql->freeResult();
            } else if($pro_cha_type == 'ARE'){
                $query = "SELECT are_int_id, are_var_name FROM vw_adm_user_area WHERE usr_int_id = ?";
                $param = array('i', $usr_int_id);
                $mysql->execute($query, $param);
                while ($mysql->fetch()) {
                    $arrayAreas[$mysql->res['are_int_id']] = $mysql->res['are_var_name'];
                    $arrayAreasIds[] = $mysql->res['are_int_id'];
                }
                $ret['areas'] = $arrayAreas;
                $ret['areas_ids'] = implode(',', $arrayAreasIds);

                $mysql->freeResult();
            } else if($pro_cha_type == 'SUP'){
                $query = "SELECT sup_int_id, sup_var_name FROM vw_adm_user_supplier WHERE usr_int_id = ?";
                $param = array('i', $usr_int_id);
                $mysql->execute($query, $param);
                while ($mysql->fetch()) {
                    $arraySupplier[$mysql->res['sup_int_id']] = $mysql->res['sup_var_name'];
                    $arraySupplierIds[] = $mysql->res['sup_int_id'];
                }
                $ret['suppliers'] = $arraySupplier;
                $ret['suppliers_ids'] = implode(',', $arraySupplierIds);

                $mysql->freeResult();
            }
        }
    } catch (GDbException $e) {
        $ret['status'] = false;
        $ret['msg'] = $e->getError();
    }

    return $ret;
}