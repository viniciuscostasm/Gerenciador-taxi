<?php
$__externo = true;

require_once("../_inc/global.php");


$url = isset($_GET['url']) ? $_GET['url'] : URL_SYS;
if (GSec::validarLogin()){
    echo "<script>self.location = '" . $url . "';</script>";
    exit();
}

$form = new GForm();
$html = '';

$header = new GHeader('Login');
$header->addBodyClass('page-md login');
$header->addCSS(URL_SYS_THEME . 'pages/css/login.css');
$header->show();
// ---------------------------------- Header ---------------------------------//

$html .= '<div class="logo">';
$html .= '<img alt="' . SYS_TITLE . '" src="' . URL_SYS_THEME . 'img/logo-continental.png">';
$html .= '</div>';


if ($_GET["type"] == 'forgot') {
    $token = $_GET["token"];
    $html .= '<div class="content">';
    //<editor-fold desc="Form de criar nova password">
    $html .= $form->open('forgot_form', 'login-form');
    $html .= '<h3 class="form-title">Change password</h3>';
    $html .= $form->addInput('hidden', 'acao', false, array('value' => 'forgot'), false, false, false);
    $html .= $form->addInput('hidden', 'token', false, array('value' => $token), false, false, false);

    $html .= '<div class="form-group">';
    $html .= '<label class="control-label visible-ie8 visible-ie9" for="r_usr_var_password">Password</label>';
    $html .= $form->addInput('password', 'r_usr_var_password', false, array('placeholder' => 'Type the new password', 'class' => 'form-control placeholder-no-fix', 'validate' => 'required;conferencia;senha'), false, false, false);
    $html .= '</div>';

    $html .= '<div class="form-group">';
    $html .= '<label class="control-label visible-ie8 visible-ie9" for="r_usr_var_password_conf">Re-type the new password</label>';
    $html .= $form->addInput('password', 'r_usr_var_password_conf', false, array('placeholder' => 'Confirm the new password', 'class' => 'form-control placeholder-no-fix', 'validate' => 'required;senha'), false, false, false);
    $html .= '</div>';

    $html .= '<div class="form-actions">';
    $html .= $form->addButton('btn_forgot', 'Change <i class="icon-chevron-right icon-white"></i>', array('class' => 'btn green pull-right'), 'submit');
    $html .= '</div>';
    $html .= $form->close();
    //</editor-fold>

    $html .= '</div>';
} else {
    $html .= '<div class="content">';
    //<editor-fold desc="Form de login">
    $html .= $form->open('login_form', 'login-form');
    $html .= '<h3 class="form-title">Login</h3>';
    $html .= $form->addInput('hidden', 'acao', false, array('value' => 'login'), false, false);

    $html .= '<div class="form-group">';
    $html .= '<label class="control-label visible-ie8 visible-ie9" for="l_usr_var_email">Email</label>';
    $html .= $form->addInput('email', 'l_usr_var_email', false, array('placeholder' => 'Login', 'class' => 'form-control placeholder-no-fix', 'validate' => 'required'), false, false, false);
    $html .= '</div>';

    $html .= '<div class="form-group">';
    $html .= '<label class="control-label visible-ie8 visible-ie9" for="l_usr_var_password">Password</label>';
    $html .= $form->addInput('password', 'l_usr_var_password', false, array('placeholder' => 'Password', 'class' => 'form-control placeholder-no-fix', 'validate' => 'required'), false, false, false);
    $html .= '</div>';

    $html .= '<div class="form-actions">';
    $html .= $form->addButton('btn_login', 'Submit', array('class' => 'btn btn-success uppercase'), 'submit');
    $html .= '</div>';

    $html .= '<div class="create-account">
                <p><a href="#" id="recuperar" class="uppercase">Forgot password</a></p>';
    $html .= '</div>';

    $html .= $form->close();
    //</editor-fold>

    //<editor-fold desc="Form recuperar">
    $html .= $form->open('recuperar_form', 'login-form');
    $html .= '<h3 class="form-title">Reset your password</h3>';
    $html .= '<p>Submit your email address and we’ll send you a link to reset your password.</p>';
    $html .= $form->addInput('hidden', 'acao', false, array('value' => 'esqueci'), false, false);
    $html .= '<div class="form-group">';
    $html .= '<label class="control-label visible-ie8 visible-ie9">Email</label>';
    $html .= $form->addInput('text', 'r_usr_var_email', false, array('placeholder' => 'Email', 'class' => 'form-control placeholder-no-fix', 'validate' => 'required'));
    $html .= '</div>';

    $html .= '<div class="form-actions">';
    $html .= $form->addButton('voltar', 'Back', array('class' => 'btn btn-default uppercase pull-left'));
    $html .= $form->addButton('btn_recuperar', 'Submit', array('class' => 'btn btn-success uppercase pull-right'), 'submit');
    $html .= '</div>';

    $html .= $form->close();
    //</editor-fold>

    $html .= '</div>';
}
echo $html;
// ---------------------------------- Footer ---------------------------------//
$footer = new GFooter();
$footer->show();
?>
<style>
    .form-actions {
        padding: 0 30px 25px !important;
    }
</style>
<script>
    $(function() {
        $('#recuperar_form').hide();
        $('#l_usr_var_login').focus();
        $("#login_form").submit(function() {
            if ($("#login_form").gValidate()) {
                $.gAjax.execCallback("crud.php", $("#login_form").serializeArray(), false, function(json) {
                    if (json.status) {
                        $.gDisplay.loadStart('html');
                        location.reload();
                    }
                });
            }
            return false;
        });

        $("#recuperar_form").submit(function() {
            if ($("#recuperar_form").gValidate()) {
                $.gAjax.execCallback("crud.php", $("#recuperar_form").serializeArray(), true, function(json) {
                    if (json.status) {
                        $('#voltar').click();
                    }
                });
            }
            return false;
        });

        $("#forgot_form").submit(function() {
            if ($("#forgot_form").gValidate()) {
                $.gAjax.execCallback("crud.php", $("#forgot_form").serializeArray(), false, function(json) {
                    if (json.status) {
                        $.gDisplay.loadStart('html');
                        location.reload();
                    }
                });
            }
            return false;
        });

        $('#recuperar').click(function() {
            $('#login_form').hide();
            $('#recuperar_form').show();
        });

        $('#voltar').click(function() {
            $('#login_form').show();
            $('#recuperar_form').hide();
        });

        $('#l_usr_var_email').focus();
    });
</script>