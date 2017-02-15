<?php
require_once '../../_inc/global.php';

$form = new GForm();
$mysql = new GDbMysql();

$header = new GHeader('Profiles');
$header->addLib(array('paginate'));
$header->show(false, 'config/profile/profile.php');
// ---------------------------------- Header ---------------------------------//

$html .= '<div id="divTable" class="row">';
$html .= getWidgetHeader();
//<editor-fold desc="Formulário de Filtro">
$html .= $form->open('filter', 'form-inline filterForm');
$html .= $form->addInput('text', 'p__pro_var_name', false, array('placeholder' => 'Name', 'class' => 'sepV_b m-wrap small'), false, false, false);

$html .= getBotoesFiltro();
$html .= getBotaoAdicionar();
$html .= $form->close();
//</editor-fold>

$paginate = new GPaginate('profile', 'profile_load.php', SYS_PAGINACAO);
$html .= $paginate->get();
$html .= '</div>'; //divTable
$html .= getWidgetFooter();
echo $html;

echo '<div id="divForm" class="row divForm">';
include 'profile_form.php';
echo '</div>';

// ---------------------------------- Footer ---------------------------------//
$footer = new GFooter();
$footer->show();
?>
<script>
    var pagCrud = 'profile_crud.php';
    var pagView = 'profile_view.php';
    var pagLoad = 'profile_load.php';
    var pagReport = 'profile_relatorio.php';

    function filtrar(page) {
        profileLoad('', '', '', $('#filter').serializeObject(), page);
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

            $('input[tipo="O"]').prop('checked', true);
            $.uniform.update('input[tipo="O"]');
            $('.form-actions').show();

            showForm('divForm', 'ins', 'Add');
        });
        $(document).on('click', '.l__btn_editar, tr.linhaRegistro td:not([class~="acoes"])', function() {
            var codigo = $(this).parents('tr.linhaRegistro').attr('id');
            var param = {acao: 'sel', 'pro_int_id': codigo};

            scrollTop();
            selectLine(codigo);

            loadForm(pagCrud, param, function(json) {
                $('input[tipo="O"]').prop('checked', true);
                $.uniform.update('input[tipo="O"]');
                if (json.men_int_idlist !== undefined) {
                    var arrListaMenu = json.men_int_idlist.split(',');
                    $.each(arrListaMenu, function(i, menu) {
                        $('#menuLista input[rel="' + menu + '"]').prop("checked", true);
                        $.uniform.update('#menuLista input[rel="' + menu + '"]');
                    });
                    arrListaMenu = '';
                }
                if (json.rec_int_idlist !== undefined) {
                    var arrListaResource = json.rec_int_idlist.split(',');
                    $.each(arrListaResource, function(i, resource) {
                        $('#widgetLista input[rel="' + resource + '"]').prop("checked", true);
                        $.uniform.update('#widgetLista input[rel="' + resource + '"]');
                    });
                    arrListaResource = '';
                }
                if (json.env_int_id === null) {
                    $('.form-actions').hide();
                    $('.menuListaItem').prop('disabled', true);
                    $('.widgetListaItem').prop('disabled', true);
                    $.uniform.update('.menuListaItem');
                    $.uniform.update('.widgetListaItem');
                } else {
                    $('.form-actions').show();
                    $('.menuListaItem[tipo!="O"]').prop('disabled', false);
                    $('.widgetListaItem').prop('disabled', false);
                    $.uniform.update('.menuListaItem[tipo!="O"]');
                    $.uniform.update('.widgetListaItem');
                }

                if (json.status === undefined)
                    showForm('divForm', 'upd', 'Edit');
            });
        });

        $(document).on('click', '.l__btn_excluir', function() {
            var codigo = $(this).parents('tr.linhaRegistro').attr('id');
            var param = {acao: 'del', pro_int_id: codigo};

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