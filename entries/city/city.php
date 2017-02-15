<?php
require_once '../../_inc/global.php';

$form = new GForm();
$mysql = new GDbMysql();

$header = new GHeader('Cidades');
$header->addLib(array('paginate'));
$header->show(false, 'entries/city/city.php');
// ---------------------------------- Header ---------------------------------//

$html .= '<div id="divTable" class="row">';
$html .= getWidgetHeader();
//<editor-fold desc="Formulário de Filtro">
$html .= $form->open('filter', 'form-inline filterForm');
$html .= $form->addInput('text', 'p__cit_var_name', false, array('placeholder' => 'Name', 'class' => 'sepV_b m-wrap small'), false, false, false);

$html .= getBotoesFiltro();
$html .= getBotaoAdicionar();
$html .= $form->close();
//</editor-fold>

$paginate = new GPaginate('city', 'city_load.php', SYS_PAGINACAO);
$html .= $paginate->get();
$html .= '</div>'; //divTable
$html .= getWidgetFooter();
echo $html;

echo '<div id="divForm" class="row divForm">';
include 'city_form.php';
echo '</div>';

// ---------------------------------- Footer ---------------------------------//
$footer = new GFooter();
$footer->show();
?>
<script>
    var pagCrud = 'city_crud.php';
    var pagView = 'city_view.php';
    var pagLoad = 'city_load.php';
    var pagReport = 'city_relatorio.php';

    function filtrar(page) {
        cityLoad('', '', '', $('#filter').serializeObject(), page);
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
            var param = {acao: 'sel', 'cit_int_id': codigo};

            scrollTop();
            selectLine(codigo);

            loadForm(pagCrud, param, function(json) {
                if (json.status === undefined)
                    showForm('divForm', 'upd', 'Edit');
            });
        });

        $(document).on('click', '.l__btn_excluir', function() {
            var codigo = $(this).parents('tr.linhaRegistro').attr('id');
            var param = {acao: 'del', cit_int_id: codigo};

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