<?php
require_once '../../_inc/global.php';

$form = new GForm();
$mysql = new GDbMysql();

$header = new GHeader('Zonas');
$header->addLib(array('paginate'));
$header->show(false, 'entries/zone/zone.php');
// ---------------------------------- Header ---------------------------------//

$html .= '<div id="divTable" class="row">';
$html .= getWidgetHeader();
//<editor-fold desc="Formulário de Filtro">
$html .= $form->open('filter', 'form-inline filterForm');
$html .= $form->addInput('text', 'p__zon_var_name', false, array('placeholder' => 'Nome', 'class' => 'sepV_b m-wrap small'), false, false, false);

$html .= getBotoesFiltro();
$html .= getBotaoAdicionar();
$html .= $form->close();
//</editor-fold>

$paginate = new GPaginate('zone', 'zone_load.php', SYS_PAGINACAO);
$html .= $paginate->get();
$html .= '</div>'; //divTable
$html .= getWidgetFooter();
echo $html;

echo '<div id="divForm" class="row divForm">';
include 'zone_form.php';
echo '</div>';

// ---------------------------------- Footer ---------------------------------//
$footer = new GFooter();
$footer->show();
?>
<script>
    var pagCrud = 'zone_crud.php';
    var pagView = 'zone_view.php';
    var pagLoad = 'zone_load.php';
    var pagReport = 'zone_relatorio.php';

    function filtrar(page) {
        zoneLoad('', '', '', $('#filter').serializeObject(), page);
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
            $('.zones-table tbody tr').remove();
            showForm('divForm', 'ins', 'Add');
        });
        $(document).on('click', '.l__btn_editar, tr.linhaRegistro td:not([class~="acoes"])', function() {
            var codigo = $(this).parents('tr.linhaRegistro').attr('id');
            var param = {acao: 'sel', 'zon_int_id': codigo};

            scrollTop();
            selectLine(codigo);

            loadForm(pagCrud, param, function(json) {
                if (json.status === undefined) {
                    $('.zones-table tbody tr').remove();
                    showForm('divForm', 'upd', 'Edit');
                    $.each(json.zones_associated, function(index, el) {
                        var line = '<tr id='+ el.zon_int_id +' class="zone-'+ el.zon_int_id +'">';
                        line += '<td>'+ el.zon_var_name +'</td>';
                        line += '<td><button class="t__btn_rmassociated btn" type="button" title="Remover"><i class="fa fa-trash"></i></button></td>';
                        line += "</tr>";
                        $('.zones-table tbody').append(line);
                    });
                }
            });
        });

        $(document).on('click', '.l__btn_excluir', function() {
            var codigo = $(this).parents('tr.linhaRegistro').attr('id');
            var param = {acao: 'del', zon_int_id: codigo};

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