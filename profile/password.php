<?php
require_once '../_inc/global.php';

$html = '';
$form = new GForm();

$userSession = GSec::getUserSession();

$header = new GHeader('Change password');
$header->show(false);
// ---------------------------------- Header ---------------------------------//
//<editor-fold desc="Alterar senha">
$html .= $form->open('formUpdatePassword', 'form-vertical');
$html .= $form->addInput('hidden', 'acao', false, array('value' => 'changePassword'), false, false, false);
$html .= $form->addInput('password', 'usr_var_currentpassword', 'Current password*', array('validate' => 'required', 'class' => 'm-wrap'));
$html .= $form->addInput('password', 'usr_var_password', 'New password*', array('validate' => 'required', 'class' => 'm-wrap'));
$html .= $form->addInput('password', 'usr_var_newpassword', 'Confirm new password*', array('validate' => 'required', 'class' => 'm-wrap'));
$html .= '<div class="form-actions">';
$html .= $form->addButton('a__btn_salvar', '<i class="fa fa-check"></i> Change', array('class' => 'btn blue'), 'submit');
$html .= '</div>';
$html .= $form->close();
//</editor-fold>


$html .= '';
echo $html;
// ---------------------------------- Footer ---------------------------------//
$footer = new GFooter();
$footer->show();
?>
<script>
    $(function() {
        $("#formUpdatePassword").submit(function() {
            if ($("#formUpdatePassword").gValidate()) {
                if ($("#usr_var_newpassword").val() === $("#usr_var_password").val() && $("#usr_var_newpassword").val().length > 0) {
                    $.gAjax.execCallback("profile_crud.php", $("#formUpdatePassword").serializeArray(), true, function(json) {
                        if (json.status) {
                            clearForm('#formUpdatePassword');
                        }
                    });
                } else {
                    $.gDisplay.showError("As senhas não conferem");
                }
            }
            return false;
        });
    });
</script>
