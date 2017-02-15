<?php
$form = new GForm();

//<editor-fold desc="Header">
$title = '<span class="acaoTitulo"></span>';
$tools = '<a id="f__btn_voltar"><i class="fa fa-arrow-left"></i> <span class="hidden-phone">Voltar</span></a>';
$htmlForm .= getWidgetHeader($title, $tools);
//</editor-fold>
//<editor-fold desc="Formulário">
$htmlForm .= $form->open('form', 'form-vertical form');
$htmlForm .= $form->addInput('hidden', 'acao', false, array('value' => 'ins', 'class' => 'acao'), false, false, false);
$htmlForm .= $form->addInput('hidden', 'men_int_id', false, array('value' => ''), false, false, false);

$htmlForm .= '<div class="row">';
$htmlForm .= '<div class="col-md-6">' . $form->addInput('text', 'men_var_name', 'Name*', array('maxlength' => '100', 'validate' => 'required', 'class' => 'm-wrap span12')) . '</div>';
$htmlForm .= '<div class="col-md-3">' . $form->addSelect('men_cha_type', array('P' => 'Plataform', 'O' => 'Obrigatory', 'N' => 'Normal'), '', 'Type*', array('class' => 'm-wrap span12', 'validate' => 'required'), false, false, true, '', 'Select...') . '</div>';
$htmlForm .= '</div>';

$htmlForm .= $form->addInput('text', 'men_var_url', 'Url', array('maxlength' => '255', 'class' => 'm-wrap span8'));

$htmlForm .= '<div id="divComboMenu"></div>';

$htmlForm .= '<div class="row">';
$htmlForm .= '<div class="col-md-3">' . $form->addInput('text', 'men_var_icon', 'Icon', array('maxlength' => '50', 'class' => 'm-wrap span12')) . '</div>';
$htmlForm .= '<div class="col-md-3">' . $form->addInput('text', 'men_var_class', 'Class', array('maxlength' => '50', 'class' => 'm-wrap span12')) . '</div>';
$htmlForm .= '<div class="col-md-3">' . $form->addInput('text', 'men_int_order', 'Order', array('maxlength' => '3', 'class' => 'm-wrap span12')) . '</div>';
$htmlForm .= '</div>';

$htmlForm .= $form->addStatus('men_cha_status', 'Status', 'A');

$htmlForm .= '<div class="form-actions fluid">';
$htmlForm .= getBotoesAcao(true);
$htmlForm .= '</div>';
$htmlForm .= $form->close();
//</editor-fold>
$htmlForm .= getWidgetFooter();

echo $htmlForm;
?>
<script>
    $(function() {
        var pagCrud = 'menu_crud.php';

        $('#form').submit(function() {
            $('#p__selecionado').val($('#men_int_id').val());
            if ($('#form').gValidate()) {
                $.gAjax.execCallback(pagCrud, $('#form').serializeArray(), false, function(json) {
                    if (json.status) {
                        showList(true);
                    }
                });
            }
            return false;
        });

        $('#f__btn_cancelar, #f__btn_voltar').click(function() {
            showList();
            return false;
        });

        $('#f__btn_excluir').click(function() {
            var codigo = $('#men_int_id').val();
            var param = {acao: 'del', men_int_id: codigo};

            $.gDisplay.showYN("Do you really want to delete the selected item?", function() {
                $.gAjax.execCallback(pagCrud, param, false, function(json) {
                    if (json.status) {
                        showList(true);
                    }
                });
            });
        });
    });
</script>