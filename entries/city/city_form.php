<?php
$form = new GForm();
$mysql = new GDbMysql();

//<editor-fold desc="Header">
$title = '<span class="acaoTitulo"></span>';
$tools = '<a id="f__btn_voltar"><i class="fa fa-arrow-left font-blue-steel"></i> <span class="hidden-phone font-blue-steel bold uppercase">Back</span></a>';
$htmlForm .= getWidgetHeader($title, $tools);
//</editor-fold>
//<editor-fold desc="Formulário">
$htmlForm .= $form->open('form', 'form-vertical form');
$htmlForm .= $form->addInput('hidden', 'acao', false, array('value' => 'ins', 'class' => 'acao'), false, false, false);
$htmlForm .= $form->addInput('hidden', 'cit_int_id', false, array('value' => ''), false, false, false);
$htmlForm .= $form->addInput('text', 'cit_var_name', 'Nome*', array('maxlength' => '100', 'validate' => 'required', 'class' => 'm-wrap span8'));
$htmlForm .= $form->addSelect('cit_cha_uf', $__comboUf, '', 'UF*' , array('validate' => 'required'), false, false, true, '', 'Selecione um estado...');

$htmlForm .= '<div class="form-actions">';
$htmlForm .= getBotoesAcao(true);
$htmlForm .= '</div>';
$htmlForm .= $form->close();
//</editor-fold>
$htmlForm .= getWidgetFooter();

echo $htmlForm;
?>
<script>
    $(function() {
        var pagCrud = 'city_crud.php';

        $('#form').submit(function() {
            $('#p__selecionado').val($('#cit_int_id').val());
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
            var codigo = $('#cit_int_id').val();
            var param = {acao: 'del', cit_int_id: codigo};

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