<?php
$form = new GForm();
$mysql = new GDbMysql();
$filterCombo = new GFilter();

$query = "SELECT zon_int_id, zon_var_name
            FROM vw_tax_zone " . $filterCombo->getWhere();
$param = $filterCombo->getParam();
$opt_zon_var_name = $mysql->executeCombo($query, $param);

//<editor-fold desc="Header">
$title = '<span class="acaoTitulo"></span>';
$tools = '<a id="f__btn_voltar"><i class="fa fa-arrow-left font-blue-steel"></i> <span class="hidden-phone font-blue-steel bold uppercase">Back</span></a>';
$htmlForm .= getWidgetHeader($title, $tools);
//</editor-fold>
//<editor-fold desc="Formulário">
$htmlForm .= $form->open('form', 'form-vertical form');
$htmlForm .= $form->addInput('hidden', 'acao', false, array('value' => 'ins', 'class' => 'acao'), false, false, false);
$htmlForm .= $form->addInput('hidden', 'zon_int_id', false, array('value' => ''), false, false, false);
$htmlForm .= $form->addInput('text', 'zon_var_name', 'Nome*', array('maxlength' => '100', 'validate' => 'required', 'class' => 'm-wrap span8'));
$htmlForm .= $form->addInput('hidden', 'zon_int_idassociated', false, array('value' => ''), false, false, false);

$htmlForm .= '<h3>Zonas associadas</h3>';
$htmlForm .= '<div class="row">';
$htmlForm .= '<div class="col-md-6">';
$htmlForm .= $form->addSelect('zones', $opt_zon_var_name, '', false, false, false, false, true, '', 'Selecione uma zona', false);
$htmlForm .= '</div>';
$htmlForm .= '<div class="col-md-2">';
$htmlForm .= $form->addButton('btn_addassociated', 'Adicionar', array('class' => 'btn blue-steel', 'style' => 'float: left'));
$htmlForm .= '</div>';
$htmlForm .= '</div>';
$htmlForm .= '<div class="row">';
$htmlForm .= '<div class="col-md-8">';
$htmlForm .= '<table class="table table-striped zones-table">';
$htmlForm .= '<thead>';
$htmlForm .= '<th>Zona</th>';
$htmlForm .= '<th style="width: 15%;">Ações</th>';
$htmlForm .= '</thead>';
$htmlForm .= '<tbody>';
$htmlForm .= '</tbody>';
$htmlForm .= '</table>';
$htmlForm .= '</div>';
$htmlForm .= '</div>';
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
        var pagCrud = 'zone_crud.php';

        $('#form').submit(function() {
            updateZoneAssociated();
            $('#p__selecionado').val($('#zon_int_id').val());
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
            var codigo = $('#zon_int_id').val();
            var param = {acao: 'del', zon_int_id: codigo};

            $.gDisplay.showYN("Do you really want to delete the selected item?", function() {
                $.gAjax.execCallback(pagCrud, param, false, function(json) {
                    if (json.status) {
                        showList(true);
                    }
                });
            });
        });

        $('#btn_addassociated').click(function(event) {
            var op = $('#zones option:selected');
            addZoneAssociated(op.val(), op.text());
        });

        $('body').on('click', '.t__btn_rmassociated', function() {
            $(this).parents('tr').remove();
        });
    });
    function addZoneAssociated (zon_int_id, zon_var_name) {
        if (zon_int_id == '') {
            $.gDisplay.showError('Escolha uma zona para associar.')
            return false;
        }

        if (zon_int_id == $('#zon_int_id').val()) {
            $.gDisplay.showError('Você não pode escolher a própria zona como zona associada.');
            return false;
        }

        var found = $('.zones-table tr.zone-'+zon_int_id+'');
        if (found.length == 0) {
            var line = '<tr id='+ zon_int_id +'>';
            line += '<td>'+ zon_var_name +'</td>';
            line += '<td><button class="t__btn_rmassociated btn" type="button" title="Remover"><i class="fa fa-trash"></i></button></td>';
            line += "</tr>";
            $('.zones-table tbody').append(line);
        } else {
            $.gDisplay.showError('A zona especificada já está na lista de zonas associadas.')
        }
    }

    function updateZoneAssociated() {
        $('#zon_int_idassociated').val('');
        var len = $('.zones-table tbody tr').length;
        $('.zones-table tbody tr').each(function(index, el) {
            var zon_int_id = $(el).attr('id');
            $('#zon_int_idassociated').val($('#zon_int_idassociated').val() + zon_int_id);
            if (len > 1 && index != len-1) {
                $('#zon_int_idassociated').val($('#zon_int_idassociated').val() + '||');
            }
        });
    }
</script>