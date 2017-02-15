<?php
$form = new GForm();
$mysql = new GDbMysql();

$opt_zon_var_name = $mysql->executeCombo("SELECT zon_int_id, zon_var_name FROM tax_zone ORDER BY zon_var_name;");
$opt_cit_var_name = $mysql->executeCombo("SELECT cit_int_id, cit_var_name FROM tax_city ORDER BY cit_var_name;");

//<editor-fold desc="Header">
$title = '<span class="acaoTitulo"></span>';
$tools = '<a id="f__btn_voltar"><i class="fa fa-arrow-left font-blue-steel"></i> <span class="hidden-phone font-blue-steel bold uppercase">Back</span></a>';
$htmlForm .= getWidgetHeader($title, $tools);
//</editor-fold>
//<editor-fold desc="Formulário">
$htmlForm .= $form->open('form', 'form-vertical form');
$htmlForm .= $form->addInput('hidden', 'acao', false, array('value' => 'ins', 'class' => 'acao'), false, false, false);
$htmlForm .= $form->addInput('hidden', 'dis_int_id', false, array('value' => ''), false, false, false);
$htmlForm .= $form->addInput('text', 'dis_var_name', 'Nome*', array('maxlength' => '100', 'validate' => 'required', 'class' => 'm-wrap span8'));
$htmlForm .= $form->addSelect("zon_int_id", $opt_zon_var_name , "", "Zona*", array("class" => "combobox","validate" => "required"));
$htmlForm .= $form->addSelect("cit_int_id", $opt_cit_var_name , "", "Cidade*", array("class" => "combobox","validate" => "required"));


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
        var pagCrud = 'district_crud.php';

        $('#form').submit(function() {
            $('#p__selecionado').val($('#dis_int_id').val());
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
            var codigo = $('#dis_int_id').val();
            var param = {acao: 'del', dis_int_id: codigo};

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