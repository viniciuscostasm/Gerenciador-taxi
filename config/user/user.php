<?php
require_once '../../_inc/global.php';

$form = new GForm();
$mysql = new GDbMysql();

$header = new GHeader('Users');
$header->addLib(array('paginate'));
$header->show(false, 'config/user/user.php');
// ---------------------------------- Header ---------------------------------//
try {
    $query = "SELECT CONCAT(pro_int_id,'-',pro_cha_type), pro_var_name
                FROM vw_adm_profile
            ORDER BY pro_var_name";
    $opt_pro_var_name = $mysql->executeCombo($query);
} catch (GDbException $exc) {
    echo $exc->getError();
}

$html .= '<div id="divTable" class="row">';
$html .= getWidgetHeader();
$html .= $form->open('filter', 'form-inline filterForm');
$html .= $form->addInput('text', 'p__usr_var_name', false, array('placeholder' => 'Name', 'class' => 'sepV_b sepH_b input-small'), false, false, false);
$html .= $form->addSelect('p__pro_int_id', $opt_pro_var_name, '', false, array('class' => 'sepV_b hidden-phone', 'style' => 'width:auto'), false, false, true, '', 'All profiles', false);
$html .= $form->addSelect('p__usr_cha_status', array('A' => 'Active', 'I' => 'Inactive'), '', false, array('class' => 'sepV_b hidden-phone', 'style' => 'width:auto'), false, false, true, '', 'All status', false);

$html .= getBotoesFiltro();
$html .= getBotaoAdicionar();
$html .= $form->close();

$paginate = new GPaginate('user', 'user_load.php', SYS_PAGINACAO);
$html .= $paginate->get();
$html .= '</div>';
$html .= getWidgetFooter();
echo $html;

echo '<div id="divForm" class="row divForm">';
include 'user_form.php';
echo '</div>';


$bootboxTrack = '<div id="track-box" class="modal ajuda fade hide" data-backdrop="true">';
$bootboxTrack .= '<div class="modal-header"><button class="close" type="button" data-dismiss="modal">x</button><i class="icon-sitemap"></i> Track <b><span id="labelTrack"></span></b> - <b><span id="labelUser"></span></b></div>';
$bootboxTrack .= '<div class="modal-body">';
$bootboxTrack .= '<div id="loadTrack"></div>';
$bootboxTrack .= '</div>';
$bootboxTrack .= '<div class="modal-footer">';
$bootboxTrack .= '<button id="b__btn_fechar" class="btn pull-right" data-dismiss="modal"><i class="icon-ban-circle"></i> Fechar</button>';
$bootboxTrack .= '</div>';
$bootboxTrack .= '</div>';

echo $bootboxTrack;

// ---------------------------------- Footer ---------------------------------//
$footer = new GFooter();
$footer->show();
?>
<script>
    var pagCrud = 'user_crud.php';
    var pagView = 'user_view.php';
    var pagLoad = 'user_load.php';
    var pagReport = 'user_relatorio.php';
    var key = 'usr_int_id';

    function filtrar(page) {
        userLoad('', '', '', $('#filter').serializeObject(), page);
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

            changeProfile('','','','');
            $('#usr_cha_status_group button[rel="A"]').click();

            showForm('divForm', 'ins', 'Add');
        });
        $(document).on('click', '.l__btn_editar, tr.linhaRegistro td:not([class~="acoes"])', function() {
            var codigo = $(this).parents('tr.linhaRegistro').attr('id');
            var param = {acao: 'sel', usr_int_id: codigo};

            scrollTop();
            selectLine(codigo);

            loadForm(pagCrud, param, function(json) {
                if (json.status === undefined){
                    console.log(json.pro_int_id);
                    $('#pro_int_id').val(json.pro_int_id);
                    if (json.pro_int_id == '4-SOL'){
                        changeProfile(json.pro_int_id, json.coc_int_id, '');
                    } else if (json.pro_int_id == '5-EMP'){
                        changeProfile(json.pro_int_id, '', json.txc_int_id);
                    }
                    // loadZone(json.cou_cha_country, json.zon_int_id);

                    $('#usr_cha_status_group button[rel="' + json.usr_cha_status + '"]').click();

                    showForm('divForm', 'upd', 'Edit');
                }
            });
        });
        $(document).on('click', '.l__btn_excluir', function() {
            var codigo = $(this).parents('tr.linhaRegistro').attr('id');
            var param = {acao: 'del', usr_int_id: codigo};

            $.gDisplay.showYN("Do you really want to delete the selected item?", function() {
                $.gAjax.execCallback(pagCrud, param, false, function(json) {
                    if (json.status) {
                        filtrar();
                    }
                });
            });
        });

        $(document).on('click', '.l__btn_track', function() {
            var codigo = $(this).parents('tr').attr('rel');
            var param = {acao: 'track', usr_int_id: codigo};

            $.gAjax.execCallback(pagCrud, param, false, function(json) {
                if (json.status) {
                    $('#track-box').modal('show');
                    $('#loadTrack').html(json.conteudo);
                    $('#labelTrack').html(json.environment);
                    $('#labelUser').html(json.user);
                }
            });
        });
    });

</script>