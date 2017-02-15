<?php
require_once '../_inc/global.php';

$html = '';
$form = new GForm();

$userSession = GSec::getUserSession();

$header = new GHeader('Update profile');
$header->addLib(array('mask'));
$header->show(false);
// ---------------------------------- Header ---------------------------------//
//<editor-fold desc="Alterar senha">
$html .= $form->open('formUpdateProfile', 'form-vertical');
$html .= $form->addInput('hidden', 'acao', false, array('value' => 'updateProfile'), false, false, false);

$html .= '<div class="row">';
$html .= '<div class="col-md-5">' . $form->addInput('text', 'usr_var_name', 'Name*', array('validate' => 'required', 'class' => '', 'value' => $userSession->getUsr_var_name())) . '</div>';
$html .= '<div class="col-md-5">' . $form->addInput('text', 'usr_var_email', 'Email*', array('validate' => 'required', 'class' => '', 'value' => $userSession->getUsr_var_email())) . '</div>';
$html .= '</div>';

$html .= '<div class="row">';
$html .= '<div class="col-md-5">' . $form->addInput('text', 'usr_var_phone', 'Phone', array('class' => '', 'value' => $userSession->getUsr_var_phone())) . '</div>';
$html .= '<div class="col-md-5">' . $form->addInput('text', 'usr_var_function', 'Function', array('class' => '', 'value' => $userSession->getUsr_var_function())) . '</div>';
$html .= '</div>';

$html .= '<div class="form-actions">';
$html .= $form->addButton('btn_salvar', '<i class="fa fa-check"></i> Save', array('class' => 'btn blue'), 'submit');
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
        $("#formUpdateProfile").submit(function() {
            if ($("#formUpdateProfile").gValidate()) {
                $.gAjax.execCallback("profile_crud.php", $("#formUpdateProfile").serializeArray(), true, function(json) {
                    if (json.status) {

                    }
                });
            }
            return false;
        });
        $('#usr_var_phone').mask('9?9999999999999999');
    });
</script>
