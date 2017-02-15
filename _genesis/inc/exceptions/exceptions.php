<?php

require_once (ROOT_GENESIS . 'inc/exceptions/dbException.class.php');
require_once (ROOT_GENESIS . 'inc/exceptions/appException.class.php');

set_error_handler('myErrorHandler');

if (SERVER == 'D') {
    ini_set('display_errors', 'On');
}

function myErrorHandler($errno, $errstr, $errfile, $errline) {
    switch ($errno) {
        case E_WARNING:
        case E_USER_WARNING:
        case E_ERROR:
        case E_USER_ERROR:
            if (SERVER == 'D')
                echo 'Mensagem: <b>', $errstr, '</b><br/>Arquivo: <b>', $errfile, '</b><br />Linha: <b>', $errline, '</b><br/><br/>';
            else
                echo 'Ops! An unexpected error, sorry for the inconvenience.';
            break;
        default:
            break;
    }
    return true;
}

?>
