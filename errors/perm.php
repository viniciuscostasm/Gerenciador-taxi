<?php
require_once '../_inc/global.php';


$header = new GHeader('Permissão negada');
$header->addCSS(URL_SYS_THEME . 'css/pages/error.css');
$header->show(false, '');
// ---------------------------------- Header ---------------------------------//
?>
<div class="row-fluid page-404">
    <div class="span7 details">
        <h3>Ops,</h3>
        <p>
            Infelizmente, você não tem permissão para acessar esta página. :(
        </p>
        <ul class="error-suggestion">
            <li> Verifique o endereço digitado.</li>
            <li> Tente acessar a página pelo menu.</li>
            <li> Entre em contato com o administrador..</li>
        </ul>
    </div>
</div>


<?php
// ---------------------------------- Footer ---------------------------------//
$footer = new GFooter();
$footer->show();
?>