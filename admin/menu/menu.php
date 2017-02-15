<?php
require_once '../../_inc/global.php';

$form = new GForm();

$header = new GHeader('Menu');
$header->addLib(array('paginate'));
$header->show(false, 'admin/menu/menu.php');
// ---------------------------------- Header ---------------------------------//

$html .= '<div id="divTable" class="row">';
$html .= getWidgetHeader();
//<editor-fold desc="Formulário de Filtro">
$html .= $form->open('filter', 'form-inline filterForm');
$html .= $form->addInput('text', 'p__men_var_name', false, array('placeholder' => 'Name', 'class' => 'sepV_b'), false, false, false);

$html .= getBotoesFiltro();
$html .= getBotaoAdicionar();
$html .= $form->close();
//</editor-fold>

$paginate = new GPaginate('menu', 'menu_load.php', 1000);
$html .= $paginate->get();
$html .= '</div>'; //divTable
$html .= getWidgetFooter();
echo $html;

echo '<div id="divForm" class="row divForm">';
include 'menu_form.php';
echo '</div>';

// ---------------------------------- Footer ---------------------------------//
$footer = new GFooter();
$footer->show();
?>
<script>
    var pagCrud = 'menu_crud.php';
    var pagView = 'menu_view.php';
    var pagLoad = 'menu_load.php';

    function filtrar(page) {
        menuLoad('', '', '', $('#filter').serializeObject(), page);
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

            $.gAjax.load(pagCrud, {acao: 'combo'}, '#divComboMenu');

            showForm('divForm', 'ins', 'Add');
        });
        $(document).on('click', '.l__btn_editar, tr.linhaRegistro td:not([class~="acoes"])', function() {
            var codigo = $(this).parents('tr.linhaRegistro').attr('id');
            var param = {acao: 'sel', men_int_id: codigo};

            scrollTop();
            selectLine(codigo);

            $.gAjax.load(pagCrud, {acao: 'combo', men_int_id: codigo}, '#divComboMenu', function() {
                loadForm(pagCrud, param, function(json) {
                    if (json.status === undefined)
                        showForm('divForm', 'upd', 'Editar', 'tabGeral');
                });
            });
        });

        $(document).on('click', '.l__btn_excluir', function() {
            var codigo = $(this).parents('tr.linhaRegistro').attr('id');
            var param = {acao: 'del', men_int_id: codigo};

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