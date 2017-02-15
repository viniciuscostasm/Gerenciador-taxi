<?php
$form = new GForm();
$mysql = new GDbMysql();
$filter = new GFilter();

$query = "SELECT cit_int_id, cit_var_name
            FROM vw_tax_city " . $filterCombo->getWhere();
$param = $filterCombo->getParam();
$opt_cit_var_name = $mysql->executeCombo($query, $param);

//<editor-fold desc="Header">
$title = '<span class="acaoTitulo"></span>';
$tools = '<a id="f__btn_voltar"><i class="fa fa-arrow-left font-blue-steel"></i> <span class="hidden-phone font-blue-steel bold uppercase">Back</span></a>';
$htmlForm .= getWidgetHeader($title, $tools);
//</editor-fold>
//<editor-fold desc="Formulário">
$htmlForm .= $form->open('form', 'form-vertical form');
$htmlForm .= $form->addInput('hidden', 'acao', false, array('value' => 'ins', 'class' => 'acao'), false, false, false);
$htmlForm .= $form->addInput('hidden', 'txc_int_id', false, array('value' => ''), false, false, false);
$htmlForm .= $form->addInput('text', 'txc_var_name', 'Nome*', array('maxlength' => '100', 'validate' => 'required', 'class' => 'm-wrap span8'));
$htmlForm .= $form->addInput('text', 'txc_dec_valueextreme', 'Percentual por extremo*', array('maxlength' => '100', 'validate' => 'required', 'class' => 'm-wrap span8'), false, array("B" => "%"));
$htmlForm .= $form->addInput('text', 'txc_dec_valuestopped', 'Valor por hora parada*', array('maxlength' => '100', 'validate' => 'required', 'class' => 'm-wrap span8'), false, array("B" => "R$"));
$htmlForm .= $form->addInput('text', 'txc_dec_valuetransfer', 'Valor de deslocamento*', array('maxlength' => '100', 'validate' => 'required', 'class' => 'm-wrap span8'), false, array("B" => "R$"));

$htmlForm .= '<h3>Corridas onde a Continental é origem ou destino</h3>';
$htmlForm .= '<div class="row">';
$htmlForm .= '<div class="col-md-2">';
$htmlForm .= $form->addSelect('f__zon_int_id', $opt_zon_var_name, '', false, array('class' => 'sepV_b hidden-phone', 'style' => 'width:175px;'), false, false, true, '', 'Selecione uma zona', false);
$htmlForm .= '</div>';
$htmlForm .= '<div class="col-md-2">';
$htmlForm .= $form->addInput('text', 'f__tzo_dec_value', false, array('maxlength' => '100', 'placeholder' => 'Valor', 'class' => 'm-wrap span8'), false, false, false);
$htmlForm .= $form->addInput('hidden', 'zon_int_idlist', false, false, false, false, false);
$htmlForm .= $form->addInput('hidden', 'tzo_dec_valuelist', false, false, false, false, false);
$htmlForm .= '</div>';
$htmlForm .= '<div class="col-md-2">';
$htmlForm .= getBotaoAdicionar('f__add_zone_value');
$htmlForm .= '</div>';
$htmlForm .= '</div>';
$htmlForm .= '<table class="table" id="zones-table">';
$htmlForm .= '<thead>';
$htmlForm .= '<th>Zona</th>';
$htmlForm .= '<th>Valor</th>';
$htmlForm .= '<th></th>';
$htmlForm .= '</thead>';
$htmlForm .= '<tbody>';
$htmlForm .= '</tbody>';
$htmlForm .= '</table>';

$htmlForm .= '<h3>Corrida entre Cidades</h3>';
$htmlForm .= '<div class="row">';
$htmlForm .= '<div class="col-md-2">';
$htmlForm .= $form->addSelect('cit_int_idsource', $opt_cit_var_name, '', false, array('class' => 'sepV_b hidden-phone', 'style' => 'width:180px;'), false, false, true, '', 'Selecione uma cidade', false);
$htmlForm .= '</div>';
$htmlForm .= '<div class="col-md-2">';
$htmlForm .= $form->addSelect('cit_int_iddestination', $opt_cit_var_name, '', false, array('class' => 'sepV_b hidden-phone', 'style' => 'width:180px;'), false, false, true, '', 'Selecione uma cidade', false);
$htmlForm .= '</div>';
$htmlForm .= '<div class="col-md-2">';
$htmlForm .= $form->addInput('text', 'f__txi_dec_value', false, array('maxlength' => '100', 'placeholder' => 'Valor', 'class' => 'm-wrap span8'), false, array("B" => "R$"));
$htmlForm .= $form->addInput('hidden', 'cit_int_idsourcelist', false, false, false, false, false);
$htmlForm .= $form->addInput('hidden', 'cit_int_iddestinationlist', false, false, false, false, false);
$htmlForm .= $form->addInput('hidden', 'txi_dec_valuelist', false, false, false, false, false);
$htmlForm .= '</div>';
$htmlForm .= '<div class="col-md-2">';
$htmlForm .= getBotaoAdicionar('btn_addcities');
$htmlForm .= '</div>';
$htmlForm .= '</div>';
$htmlForm .= '<table class="table table-striped" id="cities-table">';
$htmlForm .= '<thead>';
$htmlForm .= '<th>Cidade 1</th>';
$htmlForm .= '<th>Cidade 2</th>';
$htmlForm .= '<th>Valor</th>';
$htmlForm .= '<th></th>';
$htmlForm .= '</thead>';
$htmlForm .= '<tbody>';
$htmlForm .= '</tbody>';
$htmlForm .= '</table>';
$htmlForm .= '<div class="form-actions">';

$htmlForm .= getBotoesAcao(true);
$htmlForm .= '</div>';
$htmlForm .= $form->close();
//</editor-fold>
$htmlForm .= getWidgetFooter();

echo $htmlForm;
?>
<script>
    function updateInputs() {
        $('#zon_int_idlist').val('');
        $('#tzo_dec_valuelist').val('');
        var len = $('#zones-table tbody tr').length;
        $('#zones-table tbody tr').each(function(index, el) {
            var zon_int_id = $(this).data('zone-id');
            var tzo_dec_value = numberUnformat($(this).data('zone-value'));
            $('#zon_int_idlist').val($('#zon_int_idlist').val() + zon_int_id);
            if (len > 1 && index != len-1) {
                $('#zon_int_idlist').val($('#zon_int_idlist').val() + '||');
            }
            $('#tzo_dec_valuelist').val($('#tzo_dec_valuelist').val() + tzo_dec_value);
            if (len > 1 && index != len-1) {
                $('#tzo_dec_valuelist').val($('#tzo_dec_valuelist').val() + '||');
            }
        });
        console.log($('#zon_int_idlist').val());
        console.log($('#tzo_dec_valuelist').val());
    }

    function addCitiesValue (cit_int_idsource, cit_var_namesource, cit_int_iddestination, cit_var_namedestination, txi_dec_value) {
        if (cit_int_idsource == '' || cit_int_iddestination == '' || txi_dec_value == '') {
            $.gDisplay.showError('Escolha as duas cidades e adicione um valor antes de prosseguir.')
            return false;
        }

        var odd = $('#cities-table tr.cities-'+cit_int_idsource+'-'+cit_int_iddestination);
        var even = $('#cities-table tr.cities-'+cit_int_iddestination+'-'+cit_int_idsource);
        if (odd.length == 0 && even.length == 0) {
            var line = '<tr class="cities-'+cit_int_idsource+'-'+cit_int_iddestination+'">';
            line += '<td class="city-source" data-id="' + cit_int_idsource +'">'+ cit_var_namesource +'</td>';
            line += '<td class="city-destination" data-id="' + cit_int_iddestination +'">'+ cit_var_namedestination +'</td>';
            line += '<td class="cities-value" data-value="'+ numberUnformat(txi_dec_value) + '"> R$ '+ txi_dec_value +'</td>';
            line += '<td><button class="t__btn_rmassociated btn" type="button" title="Remover"><i class="fa fa-trash"></i></button></td>';
            line += "</tr>";
            $('#cities-table tbody').append(line);
        } else {
            $.gDisplay.showError('A combinação entre as cidades especificadas já tem um valor definido.');
        }
    }

    function updateCitiesValue() {
        $('#cit_int_idsourcelist').val('');
        $('#cit_int_iddestinationlist').val('');
        $('#txi_dec_valuelist').val('');
        var len = $('#cities-table tbody tr').length;

        $('#cities-table tbody td.city-source').each(function(index, el) {
            var cit_int_idsource = $(el).data('id');
            $('#cit_int_idsourcelist').val($('#cit_int_idsourcelist').val() + cit_int_idsource);
            if (len > 1 && index != len-1) {
                $('#cit_int_idsourcelist').val($('#cit_int_idsourcelist').val() + '||');
            }
        });

        $('#cities-table tbody td.city-destination').each(function(index, el) {
            var cit_int_iddestination = $(el).data('id');
            $('#cit_int_iddestinationlist').val($('#cit_int_iddestinationlist').val() + cit_int_iddestination);
            if (len > 1 && index != len-1) {
                $('#cit_int_iddestinationlist').val($('#cit_int_iddestinationlist').val() + '||');
            }
        });

        $('#cities-table tbody td.cities-value').each(function(index, el) {
            var txi_dec_value = $(el).data('value');
            $('#txi_dec_valuelist').val($('#txi_dec_valuelist').val() + txi_dec_value);
            if (len > 1 && index != len-1) {
                $('#txi_dec_valuelist').val($('#txi_dec_valuelist').val() + '||');
            }
        });
    }

    $(function() {
        var pagCrud = 'taxicompany_crud.php';

        $('#form').submit(function() {
            updateInputs();
            updateCitiesValue();
            $('#p__selecionado').val($('#dis_int_id').val());
            if ($('#form').gValidate()) {
                $.gAjax.execCallback(pagCrud, $('#form').serializeArray(), false, function(json) {
                    if (json.status) {
                        showList(true);
                    }
                });
            }
            return false;
            clearTableZones();
            updateInputs();
        });

        $('#f__btn_cancelar, #f__btn_voltar').click(function() {
            showList();
            return false;
        });

        $('#f__btn_excluir').click(function() {
            var codigo = $('#txc_int_id').val();
            var param = {acao: 'del', dis_int_id: codigo};

            $.gDisplay.showYN("Do you really want to delete the selected item?", function() {
                $.gAjax.execCallback(pagCrud, param, false, function(json) {
                    if (json.status) {
                        showList(true);
                    }
                });
            });
        });

        $('#txc_dec_valueextreme, #txc_dec_valuestopped, #txc_dec_valuetransfer, #f__tzo_dec_value, #f__txi_dec_value').maskMoney({decimal: ",", thousands: "."});

        $('#f__add_zone_value').on('click', function () {
            var zone = $("#f__zon_int_id option:selected").text();
            var zoneID = $("#f__zon_int_id option:selected").val()*1;
            var value = $("#f__tzo_dec_value").val();
            // console.log(zoneTableIds()[0], zoneID, $.inArray(zoneID, zoneTableIds()) )
            if (zoneID == '' || value == '') {
                $.gDisplay.showError('Preencha os campos de zona e valor antes de continuar.');
                return false;
            }
            if ($.inArray(zoneID, zoneTableIds()) == -1) {
                addZoneToTable(zone, zoneID, value);
                $("#f__zon_int_id, #f__tzo_dec_value").val('');
            } else {
                $.gDisplay.showError('Você não pode atribuir dois valores para a mesma zona.');
            }
            updateInputs();
        });

        $('#zones-table').on('click', '.f__remove_node', function () {
            $(this).parents('tr')[0].remove();
            updateInputs();
        });

        $('#btn_addcities').click(function(event) {
            var cit_int_idsource = $('#cit_int_idsource option:selected');
            var cit_int_iddestination = $('#cit_int_iddestination option:selected');
            var txi_dec_value = $('#f__txi_dec_value').val();
            addCitiesValue(cit_int_idsource.val(), cit_int_idsource.text(), cit_int_iddestination.val(), cit_int_iddestination.text(), txi_dec_value);
            $("#cit_int_idsource, #cit_int_iddestination, #f__txi_dec_value").val('');
        });

        $('#cities-table').on('click', '.t__btn_rmassociated', function () {
            $(this).parents('tr')[0].remove();
        });
    });


</script>