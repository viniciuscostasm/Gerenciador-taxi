<?php
require_once '../_inc/global.php';


$header = new GHeader('Erro interno');
$header->show(false, 'dashboard');
// ---------------------------------- Header ---------------------------------//
?>
<div class="container error-wrapper">
    <div class="row">
        <div class="span4">
            <div class="error-code">
                500
                <div>
                </div>
            </div>
        </div>
        <div class="span4">
            <div class="error-message">
                <h4>Oops! Erro interno </h4>
                <p>
                    Esta página que você está acessando, está com algum erro.
                </p>
                <ul class="error-suggestion">
                    <li> Tente novamente mais tarde.</li>
                    <li> Entre com contato com o suporte.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php
// ---------------------------------- Footer ---------------------------------//
$footer = new GFooter();
$footer->show();
?>