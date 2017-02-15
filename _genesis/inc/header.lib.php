<?php

/**
 * Carregar um array com as bibliotecas já instaladas no Genesis
 *
 * @return array bibliotecas
 */
function getLibsDefault() {
    return array(
        'genesis' => array(
            URL_STATIC_GN . 'js/gFunctions.js',
            URL_STATIC_GN . 'js/gDisplay.js',
            URL_STATIC_GN . 'js/gAjax.js',
            URL_STATIC_GN . 'js/gValidate.js'
        ),
        'paginate' => array(
            ROOT_GENESIS . 'inc/paginate.class.php',
            URL_STATIC_GN . 'js/jquery.jqpagination.js'
        ),
        'php.js' => array(
            URL_STATIC_GN . 'js/php.js'
        ),
        'bookmarks' => array(
            URL_STATIC_GN . 'js/bookmarks.min.js'
        ),
        'mask' => array(
            URL_STATIC_GN . 'js/jquery.maskedinput.js'
        ),
        'maskMoney' => array(
            URL_STATIC_GN . 'js/jquery.maskMoney.js'
        ),
        'alphanumeric' => array(
            URL_STATIC_GN . 'js/jquery.alphanumeric.pack.js'
        ),
        'cookie' => array(
            URL_STATIC_GN . 'js/jquery.cookie.js'
        ),
        'counter' => array(
            URL_STATIC_GN . 'js/jquery.counter.min.js'
        ),
        'spinner' => array(
            URL_STATIC_GN . 'js/jquery.spinners.min.js'
        ),
        'mpdf' => array(
            ROOT_GENESIS . 'inc/mpdf/mpdf.php'
        )
    );
}