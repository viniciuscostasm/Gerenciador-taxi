<?php
require_once '../_inc/global.php';


$header = new GHeader('Página não encontrada');
$header->addCSS(URL_SYS_THEME . 'css/pages/error.css');
$header->show(false, 'dashboard');
// ---------------------------------- Header ---------------------------------//
?>
<div class="row-fluid page-404">
    <div class="span5 number">
        404
    </div>
    <div class="span7 details">
        <h3>Ops,</h3>
        <p>
            Esta página que você está procurando, infelizmente não existe, :(
        </p>
        <ul class="error-suggestion">
            <li> Verifique o endereço digitado.</li>
            <li> Tente acessar a página pelo menu.</li>
        </ul>
    </div>
</div>
<?php
// ---------------------------------- Footer ---------------------------------//
$footer = new GFooter();
$footer->show();
?>