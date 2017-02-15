<?php

function verificaTipoData($tipo) {
    $tipo = strtoupper($tipo);
    $arrDat = array('DATE', 'DATETIME', 'TIMESTAMP', 'YEAR');

    return strpos_arr($tipo, $arrDat);
}

function verificaTipoInteger($tipo) {
    $tipo = strtoupper($tipo);
    $arrInt = array('INT', 'INTEGER', 'TINYINT', 'MEDIUMINT', 'BIGINT');

    return strpos_arr($tipo, $arrInt);
}

function verificaTipoFloat($tipo) {
    $tipo = strtoupper($tipo);
    $arrInt = array('FLOAT', 'DOUBLE', 'DECIMAL');

    return strpos_arr($tipo, $arrInt);
}

function verificaTipoFormChar($tipo) {
    $tipo = strtoupper($tipo);
    $arrInt = array('CHAR', 'VARCHAR');

    return strpos_arr($tipo, $arrInt);
}

function verificaTipoFormText($tipo) {
    $tipo = strtoupper($tipo);
    $arrTex = array('BLOB', 'TINYBLOB', 'MEDIUMBLOB', 'LONGBLOB', 'TEXT', 'TINYTEXT', 'MEDIUMTEXT', 'LONGTEXT');

    return strpos_arr($tipo, $arrTex);
}

function verificaFormCombo($campo) {
    if ($campo['values'] != '')
        return true;
    return false;
}

function strpos_arr($haystack, $needle) {
    if (!is_array($needle))
        $needle = array($needle);
    foreach ($needle as $what) {
        if (($pos = strpos($haystack, $what)) !== false)
            return true;
    }
    return false;
}

function maxlength($tipo) {
    $tamanho = explode("(", $tipo);
    $tamanho = substr($tamanho[1], 0, -1);
    $tamanho = explode(",", $tamanho);
    return $tamanho[0];
}

function size($tipo) {
    $tamanho = explode("(", $tipo);
    $tamanho = substr($tamanho[1], 0, -1);
    $tamanho = explode(",", $tamanho);
    if ($tamanho[0] > 0 && $tamanho[0] < 6)
        $tamanho = 5;
    else if ($tamanho[0] > 5 && $tamanho[0] < 11)
        $tamanho = 10;
    else if ($tamanho[0] > 10 && $tamanho[0] < 21)
        $tamanho = 20;
    else if ($tamanho[0] > 20 && $tamanho[0] < 41)
        $tamanho = 40;
    else if ($tamanho[0] > 40 && $tamanho[0] < 61)
        $tamanho = 60;
    else if ($tamanho[0] > 60)
        $tamanho = 80;
    return $tamanho;
}

if (false === function_exists('lcfirst')) {

    /**
     * Torna o primeiro caractere de uma string minúsculo
     *
     * @param string $str
     * @return string resultado a string.
     */
    function lcfirst($str) {
        $str[0] = strtolower($str[0]);
        return (string) $str;
    }

}
?>