<?php

session_start();

// this should be at the top
if (!defined('__DIR__')) {
   define('__DIR__', dirname(__FILE__));
}

//<editor-fold desc="Definição das constantes de URL e ROOT">
$scriptRoot = explode('/', str_replace('_inc', '', __DIR__));
$scriptName = explode('/', $_SERVER['SCRIPT_NAME']);
$arrayCaminho = array_intersect($scriptRoot, $scriptName);

$complementoPasta = '';
if (count($arrayCaminho) > 0) {
    foreach ($arrayCaminho as $pasta) {
        if (!empty($pasta)) {
            $complementoPasta .= $pasta . '/';
        }
    }
}
define('URL_SYS', 'http://' . $_SERVER['SERVER_NAME'] . '/' . $complementoPasta);
define('ROOT_SYS', str_replace('_inc', '', dirname(__FILE__)));
//</editor-fold>

define('URL_STATIC', URL_SYS);

//Constantes do Sistema
define('SYS_TITLE', "Gestão Taxi");
define('SYS_SUBTITLE', '');
define('SYS_VERSION', '1.0');
define('SYS_THEME', 'metronic4');
define('SYS_LIB_DEFAULT', 'jquery,genesis,php.js');
define('SYS_CHARSET', 'utf-8');
define('SYS_COPYRIGHT', "2015 &copy; Continental");
define('SYS_COPYRIGHT_URL', 'http://continental.com.br');

//Constantes de Contatos
define('SYS_EMAIL_CONTATO', "application.service@conti.com.br");
define('SYS_EMAIL_NOREPLY', "application.service@conti.com.br");
define('SYS_EMAIL_SUPORT', "application.service@conti.com.br");
define('SYS_EMAIL_VIA', 'SMTP'); //AWS ou PHP
define('SYS_EMAIL_SMTP','10.204.32.24');
define('SYS_EMAIL_SMTP_USUARIO','gestao.mbr@conti.com.br');
define('SYS_EMAIL_SMTP_SENHA','Start123!');

//Constantes URL
define('URL_GENESIS', URL_SYS . '_genesis/');
define('URL_SYS_THEME', URL_SYS . '_themes/' . SYS_THEME . '/');
define('URL_SYS_LOGO', URL_SYS_THEME . '/_img/logo-interna.png');
define('URL_STATIC_GN', URL_STATIC . '_genesis/');

define('URL_ERROR', URL_SYS . 'error/');
define('URL_SIGNIN', URL_SYS . 'login/login.php');
define('URL_SIGNUP', URL_SYS . 'login/signup.php');
define('URL_SIGNOUT', URL_SYS . 'login/logout.php');

define('URL_UPLOAD', URL_SYS . '_upload/');

//Constantes caminho absoluto
define('ROOT_SYS_INC', ROOT_SYS . '_inc/');
define('ROOT_SYS_CLASS', ROOT_SYS . '_class/');
define('ROOT_GENESIS', ROOT_SYS . '_genesis/');
define('ROOT_SYS_THEME', ROOT_SYS . '_themes/' . SYS_THEME . '/');

define('ROOT_UPLOAD', ROOT_SYS . '_upload/');

define('SYS_PAGINACAO', 20);

define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');

define("SYS_DEFAULT_TIMEZONE", "America/Sao_Paulo");

// Constantes Google Analytics
define('ANALYTICS_ACCOUNT', '');
define('ANALYTICS_DOMAIN', '');

define('SYS_PREFIX', 'con');

require_once(ROOT_SYS_INC . 'security.conf.php');
require_once(ROOT_SYS_INC . 'security.class.php');
require_once(ROOT_SYS_INC . 'functions.php');
require_once(ROOT_GENESIS . 'genesis.php');

$genesis = new Genesis();

$php_timezone = PHP_TIMEZONE;
// if(GSec::validarLogin()){
//     $php_timezone = GSec::getUserSession()->getZone()->getZon_var_name();
// }
date_default_timezone_set($php_timezone);

require_once(ROOT_SYS_THEME . 'theme.lib.php');
require_once(ROOT_SYS_THEME . 'header.class.php');
require_once(ROOT_SYS_THEME . 'footer.class.php');
require_once(ROOT_SYS_THEME . 'form.class.php');


//<editor-fold desc = "Tratamento do $_POST">
if (!empty($_POST)) {
    foreach ($_POST as $key => $value) {
        $value = is_string($value) ? trim($value) : $value;
        if (empty($value)) {
            $_POST[$key] = null;
        } else {
            $_POST[$key] = is_string($value) ? stripslashes($value) : $value;
        }
    }
}
//</editor-fold>
//
if (!$__externo) {
    GSec::verificarLogin();
}
$__arrayMeses = array('01' => 'Janeiro', '02' => 'Fevereiro', '03' => 'Março', '04' => 'Abril', '05' => 'Maio', '06' => 'Junho', '07' => 'Julho', '08' => 'Agosto', '09' => 'Setembro', '10' => 'Outubro', '11' => 'Novembro', '12' => 'Dezembro');
$__arrayMesesReduzido = array('01' => 'Jan', '02' => 'Fev', '03' => 'Mar', '04' => 'Abr', '05' => 'Mai', '06' => 'Jun', '07' => 'Jul', '08' => 'Ago', '09' => 'Set', '10' => 'Out', '11' => 'Nov', '12' => 'Dez');
$__arrayMesesNumero = array('01' => '01', '02' => '02', '03' => '03', '04' => '04', '05' => '05', '06' => '06', '07' => '07', '08' => '08', '09' => '09', '10' => '10', '11' => '11', '12' => '12');
$__arrayDias = array('01' => '01', '02' => '02', '03' => '03', '04' => '04', '05' => '05', '06' => '06', '07' => '07', '08' => '08', '09' => '09', '10' => '10', '11' => '11', '12' => '12', '13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17', '18' => '18', '19' => '19', '20' => '20', '21' => '21', '22' => '22', '23' => '23', '24' => '24', '25' => '25', '26' => '26', '27' => '27', '28' => '28', '29' => '29', '30' => '30', '31' => '31');
$__comboUf = array('AC' => 'Acre', 'AL' => 'Alagoas', 'AM' => 'Amazonas', 'AP' => 'Amapá', 'BA' => 'Bahia', 'CE' => 'Ceará', 'DF' => 'Distrito Federal', 'ES' => 'Espírito Santo', 'GO' => 'Goias', 'MA' => 'Maranhão', 'MG' => 'Minas Gerais', 'MS' => 'Mato Grosso do Sul', 'MT' => 'Mato Grosso', 'PA' => 'Pará', 'PB' => 'Paraíba', 'PE' => 'Pernambuco', 'PI' => 'Piauí', 'PR' => 'Paraná', 'RJ' => 'Rio de Janeiro', 'RN' => 'Rio Grande do Norte', 'RO' => 'Rondônia', 'RR' => 'Roraima', 'RS' => 'Rio Grande do Sul', 'SC' => 'Santa Catarina', 'SE' => 'Sergipe', 'SP' => 'São Paulo', 'TO' => 'Tocantins');
$__arrayExtensoes['gif'] = 'Pictures.png';
$__arrayExtensoes['jpg'] = 'Pictures.png';
$__arrayExtensoes['jpeg'] = 'Pictures.png';
$__arrayExtensoes['tiff'] = 'Pictures.png';
$__arrayExtensoes['png'] = 'Pictures.png';
$__arrayExtensoes['pdf'] = 'Reader.png';
$__arrayExtensoes['xps'] = 'Reader.png';
$__arrayExtensoes['xls'] = 'Excel.png';
$__arrayExtensoes['xlsx'] = 'Excel.png';
$__arrayExtensoes['ppt'] = 'PowerPoint.png';
$__arrayExtensoes['pptx'] = 'PowerPoint.png';
$__arrayExtensoes['doc'] = 'Word.png';
$__arrayExtensoes['docx'] = 'Word.png';
$__arrayExtensoes['mp3'] = 'Music.png';

$__arrayReportStatus = array(
    "DRA" => "Draft",
    "OTI" => "On time",
    "TOL" => "Tolerance",
    "DEL" => "Delayed"
);

$__arrayReportStage = array(
    "DRA" => "Draft",
    "ETA" => "Engineering tires analysis",
    "ETR" => "Engineering tires release",
    "SUP" => "Machine Supplier Answer",
    "APP" => "Approval",
    "FIN" => "Finished"
);

$__arrayHoras = array(
    '00:15' => '00:15',
    '01:15' => '01:15',
    '02:15' => '02:15',
    '03:15' => '03:15',
    '04:15' => '04:15',
    '05:15' => '05:15',
    '06:15' => '06:15',
    '07:15' => '07:15',
    '08:15' => '08:15',
    '09:15' => '09:15',
    '10:15' => '10:15',
    '11:15' => '11:15',
    '12:15' => '12:15',
    '13:15' => '13:15',
    '14:15' => '14:15',
    '15:15' => '15:15',
    '16:15' => '16:15',
    '17:15' => '17:15',
    '18:15' => '18:15',
    '19:15' => '19:15',
    '20:15' => '20:15',
    '21:15' => '21:15',
    '22:15' => '22:15',
    '23:15' => '23:15'
);
