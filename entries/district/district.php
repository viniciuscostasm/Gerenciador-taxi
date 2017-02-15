<?php
require_once '../../_inc/global.php';

$form = new GForm();
$mysql = new GDbMysql();

$header = new GHeader('Bairro');
$header->addLib(array('paginate'));
$header->show(false, 'entries/district/district.php');
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
$html .= $form->addInput('text', 'p__dis_var_name', false, array('placeholder' => 'Name', 'class' => 'sepV_b m-wrap small'), false, false, false);
$html .= $form->addSelect('p__zon_int_id', $opt_zon_var_name, '', false, array('class' => 'sepV_b hidden-phone', 'style' => 'width:150px;'), false, false, true, '', 'Todas as zonas', false);
$html .= $form->addSelect('p__cit_int_id', $opt_cit_var_name, '', false, array('class' => 'sepV_b hidden-phone', 'style' => 'width:175px;'), false, false, true, '', 'Todas as cidades', false);

$html .= getBotoesFiltro();
$html .= getBotaoAdicionar();
$html .= $form->close();
//</editor-fold>

$paginate = new GPaginate('district', 'district_load.php', SYS_PAGINACAO);
$html .= $paginate->get();
$html .= '</div>'; //divTable
$html .= getWidgetFooter();
echo $html;

echo '<div id="divForm" class="row divForm">';
include 'district_form.php';
echo '</div>';

// ---------------------------------- Footer ---------------------------------//
$footer = new GFooter();
$footer->show();
?>
<script>
    var pagCrud = 'district_crud.php';
    var pagView = 'district_view.php';
    var pagLoad = 'district_load.php';
    var pagReport = 'district_relatorio.php';

    function filtrar(page) {
        districtLoad('', '', '', $('#filter').serializeObject(), page);
        return false;
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

            showForm('divForm', 'ins', 'Add');
        });
        $(document).on('click', '.l__btn_editar, tr.linhaRegistro td:not([class~="acoes"])', function() {
            var codigo = $(this).parents('tr.linhaRegistro').attr('id');
            var param = {acao: 'sel', 'dis_int_id': codigo};

            scrollTop();
            selectLine(codigo);

            loadForm(pagCrud, param, function(json) {
                if (json.status === undefined)
                    showForm('divForm', 'upd', 'Edit');
            });
        });

        $(document).on('click', '.l__btn_excluir', function() {
            var codigo = $(this).parents('tr.linhaRegistro').attr('id');
            var param = {acao: 'del', dis_int_id: codigo};

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