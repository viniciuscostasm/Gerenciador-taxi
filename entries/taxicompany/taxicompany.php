<?php
require_once '../../_inc/global.php';

$form = new GForm();
$mysql = new GDbMysql();

$header = new GHeader('Companhia de Táxi');
$header->addLib(array('paginate', 'maskMoney'));
$header->show(false, 'entries/taxicompany/taxicompany.php');
// ---------------------------------- Header ---------------------------------//

try {
    $filterCombo = new GFilter();
    $filterCombo->setOrder(array('zon_var_name' => 'ASC'));

    $query = "SELECT zon_int_id, zon_var_name
                FROM vw_tax_zone " . $filterCombo->getWhere();
    $param = $filterCombo->getParam();
    $opt_zon_var_name = $mysql->executeCombo($query, $param);

    $filterCombo = new GFilter();
    $filterCombo->setOrder(array('cit_var_name' => 'ASC'));

    $query = "SELECT cit_int_id, cit_var_name
                FROM vw_tax_city " . $filterCombo->getWhere();
    $param = $filterCombo->getParam();
    $opt_cit_var_name = $mysql->executeCombo($query, $param);
} catch (Exception $e) {

}

$html .= '<div id="divTable" class="row">';
$html .= getWidgetHeader();
//<editor-fold desc="Formulário de Filtro">
$html .= $form->open('filter', 'form-inline filterForm');
$html .= $form->addInput('text', 'p__txc_var_name', false, array('placeholder' => 'Name', 'class' => 'sepV_b m-wrap small'), false, false, false);
// $html .= $form->addSelect('p__zon_int_id', $opt_zon_var_name, '', false, array('class' => 'sepV_b hidden-phone', 'style' => 'width:150px;'), false, false, true, '', 'Todas as zonas', false);
// $html .= $form->addSelect('p__cit_int_id', $opt_cit_var_name, '', false, array('class' => 'sepV_b hidden-phone', 'style' => 'width:175px;'), false, false, true, '', 'Todas as cidades', false);

$html .= getBotoesFiltro();
$html .= getBotaoAdicionar();
$html .= $form->close();
//</editor-fold>

$paginate = new GPaginate('taxiCompany', 'taxicompany_load.php', SYS_PAGINACAO);
$html .= $paginate->get();
$html .= '</div>'; //divTable
$html .= getWidgetFooter();
echo $html;

echo '<div id="divForm" class="row divForm">';
include 'taxicompany_form.php';
echo '</div>';

// ---------------------------------- Footer ---------------------------------//
$footer = new GFooter();
$footer->show();
?>
<script>
    var pagCrud = 'taxicompany_crud.php';
    var pagView = 'taxicompany_view.php';
    var pagLoad = 'taxicompany_load.php';
    var pagReport = 'taxicompany_relatorio.php';

    function filtrar(page) {

        taxiCompanyLoad('', '', '', $('#filter').serializeObject(), page);
        return false;
    }

    function addZoneToTable(zoneTitle, zoneID, zoneValue) {
        var node = "<tr data-zone-id='"+zoneID+"' data-zone-value='"+zoneValue+"'><td>"+zoneTitle+"</td><td>R$ "+zoneValue+"</td><td><i class='fa fa-trash f__remove_node'></td></tr>"
        $('#zones-table tbody').append(node);
    }

    function addCitiesToTable(cit_int_idsource, cit_var_namesource, cit_int_iddestination, cit_var_namedestination, txi_dec_value) {
        var line = '<tr class="cities-'+cit_int_idsource+'-'+cit_int_iddestination+'">';
            line += '<td class="city-source" data-id="' + cit_int_idsource +'">'+ cit_var_namesource +'</td>';
            line += '<td class="city-destination" data-id="' + cit_int_iddestination +'">'+ cit_var_namedestination +'</td>';
            line += '<td class="cities-value" data-value="'+ numberUnformat(txi_dec_value) + '"> R$ '+ txi_dec_value +'</td>';
            line += '<td><button class="t__btn_rmassociated btn" type="button" title="Remover"><i class="fa fa-trash"></i></button></td>';
            line += "</tr>";
            $('#cities-table tbody').append(line);
    }

    function zoneTableIds() {
        var rows = $('#zones-table tbody tr');
        var ids = [];
        rows.each(function() {
            ids.push($(this).data('zone-id'));
        })
        return ids;
    }

    function clearTableZones() {
        $('#zones-table tbody tr, #cities-table tr').remove();
    }

    $(function() {
        filtrar(1);
        $('#filter select').change(function() {
            filtrar(1);
            return false;
        });
        $('#filter').submit(function() {
            if ($('#filter').attr('action').length === 0) {
                filtrar(1);
                return false;
            }
        });
        $('#p__btn_limpar').click(function() {
            clearForm('#filter');
            filtrar(1);
        });
        $(document).on('click', '#p__btn_adicionar', function() {
            scrollTop();
            unselectLines();
            clearTableZones();
            showForm('divForm', 'ins', 'Add');
            updateInputs();
        });
        $(document).on('click', '.l__btn_editar, tr.linhaRegistro td:not([class~="acoes"])', function() {
            var codigo = $(this).parents('tr.linhaRegistro').attr('id');
            var param = {acao: 'sel', 'txc_int_id': codigo};

            scrollTop();
            selectLine(codigo);
            clearTableZones();
            loadForm(pagCrud, param, function(json) {
                if (json.status === undefined) {
                    showForm('divForm', 'upd', 'Edit');
                    $('#txc_dec_valueextreme').val(numberFormat(json.txc_dec_valueextreme));
                    $('#txc_dec_valuetransfer').val(numberFormat(json.txc_dec_valuetransfer));
                    $('#txc_dec_valuestopped').val(numberFormat(json.txc_dec_valuestopped));
                    if (json.zones.length > 0) {
                        $.each(json.zones, function (i,zone) {
                            addZoneToTable(zone.zon_var_name, zone.zon_int_id, numberFormat(zone.tzo_dec_value));
                        });
                    };
                    console.log(json.cities);
                    if (json.cities.length > 0) {
                        $.each(json.cities, function (i,city) {
                            addCitiesToTable(city.cit_int_idsource, city.cit_var_namesource, city.cit_int_iddestination, city.cit_var_namedestination, numberFormat(city.txi_dec_value));
                        });
                    };
                }
            });
        });

        $(document).on('click', '.l__btn_excluir', function() {
            var codigo = $(this).parents('tr.linhaRegistro').attr('id');
            var param = {acao: 'del', txc_int_id: codigo};

            $.gDisplay.showYN("Do you really want to delete the selected item?", function() {
                $.gAjax.execCallback(pagCrud, param, false, function(json) {
                    if (json.status) {
                        filtrar();
                    }
                });
            });
        });


    });
</script>