<?php
$form = new GForm();
$mysql = new GDbMysql();

//<editor-fold desc="Header">
$title = '<span class="acaoTitulo"></span>';
$tools = '<a id="f__btn_voltar"><i class="fa fa-arrow-left"></i> <span class="hidden-phone">Voltar</span></a>';
$htmlForm .= getWidgetHeader($title, $tools);
//</editor-fold>
//<editor-fold desc="Formulário">
$htmlForm .= $form->open('form', 'form-vertical form');
$htmlForm .= $form->addInput('hidden', 'acao', false, array('value' => 'ins', 'class' => 'acao'), false, false, false);
$htmlForm .= $form->addInput('hidden', 'pro_int_id', false, array('value' => ''), false, false, false);
$htmlForm .= $form->addInput('text', 'pro_var_name', 'Name*', array('maxlength' => '100', 'validate' => 'required', 'class' => 'm-wrap span8'));

$htmlForm .= '<div class="row-fluid" style="margin-bottom:10px">';
try {
    //<editor-fold desc="Lista dos Menus">
    $htmlForm .= '<div id="menuLista" class="span6">';
    $htmlForm .= '<h4 class="widget-header">Menu itens</h4>';
    $htmlForm .= $form->addInput('hidden', 'men_int_idlist', false, array('value' => ''), false, false, false);
    $query = "SELECT men_int_id, men_cha_consolidator, men_var_name, men_cha_type, men_int_level
                FROM vw_adm_menu
               WHERE men_cha_type <> 'P'
            ORDER BY men_var_key";
    $mysql->execute($query);
    $i = 0;

    while ($mysql->fetch()) {
        $margin = ($mysql->res['men_int_level'] - 1) * 15;
        $htmlForm .= '<div style="margin-left: ' . $margin . 'px">';
        if ($mysql->res['men_cha_consolidator'] == 'S') {
            $htmlForm .= '<label><b>' . $mysql->res['men_var_name'] . '</b></label>';
        } else {
            $cheDis = ($mysql->res['men_cha_type'] == 'O') ? array('checked' => 'checked', 'disabled' => 'disabled') : array();
            $htmlForm .= $form->addCheckbox('men_int_id_' . $i, $mysql->res['men_var_name'], array('rel' => $mysql->res['men_int_id'], 'class' => 'menuListaItem', 'tipo' => $mysql->res['men_cha_type']) + $cheDis);
            $i++;
        }
        $htmlForm .= '</div>';
    }
    $htmlForm .= '</div>'; //span6
    //</editor-fold>
    //<editor-fold desc="Lista dos Widgets">
    $htmlForm .= '<div id="widgetLista" class="span6">';
    $htmlForm .= '<h4 class="widget-header">Wigets e Indicadores</h4>';
    $htmlForm .= $form->addInput('hidden', 'res_int_idlist', false, array('value' => ''), false, false, false);
    $htmlForm .= '<ul class="unstyled">';

    $query2 = "SELECT res_int_id,res_var_name,res_cha_type
                 FROM vw_adm_resource
                WHERE res_cha_type IN ('I','W','C')
             ORDER BY res_cha_type,res_var_name";
    $mysql->execute($query2);

    $arrayWidget = $arrayIndicadores = $arrayComponentes = array();
    while ($mysql->fetch()) {
        if ($mysql->res['res_cha_type'] == 'W') {
            $arrayWidget[] = array($mysql->res['res_int_id'], $mysql->res['res_var_name']);
        } else if ($mysql->res['res_cha_type'] == 'I') {
            $arrayIndicadores[] = array($mysql->res['res_int_id'], $mysql->res['res_var_name']);
        } else {
            $arrayComponentes[] = array($mysql->res['res_int_id'], $mysql->res['res_var_name']);
        }
    }

    $i = 0;
    if (count($arrayWidget) > 0) {
        $htmlForm .= '<li><label><b>Widgets</b></label></li>';
        $htmlForm .= '<ul class="unstyled">';
        foreach ($arrayWidget as $widget) {
            $htmlForm .= '<li>';
            $htmlForm .= $form->addCheckbox('res_int_id_' . $i, $widget[1], array('rel' => $widget[0], 'class' => 'widgetListaItem'));
            $htmlForm .= '</li>';
            $i++;
        }
        $htmlForm .= '</ul>';
    }
    if (count($arrayIndicadores) > 0) {
        $htmlForm .= '<li><label><b>Stats</b></label></li>';
        $htmlForm .= '<ul class="unstyled">';
        foreach ($arrayIndicadores as $indicador) {
            $htmlForm .= '<li>';
            $htmlForm .= $form->addCheckbox('res_int_id_' . $i, $indicador[1], array('rel' => $indicador[0], 'class' => 'widgetListaItem'));
            $htmlForm .= '</li>';
            $i++;
        }
        $htmlForm .= '</ul>';
    }
    if (count($arrayComponentes) > 0) {
        $htmlForm .= '<li><label><b>Components</b></label></li>';
        $htmlForm .= '<ul class="unstyled">';
        foreach ($arrayComponentes as $componente) {
            $htmlForm .= '<li>';
            $htmlForm .= $form->addCheckbox('res_int_id_' . $i, $componente[1], array('rel' => $componente[0], 'class' => 'widgetListaItem'));
            $htmlForm .= '</li>';
            $i++;
        }
        $htmlForm .= '</ul>';
    }

    $htmlForm .= '</ul>';
    $htmlForm .= '</div>'; //span6
    //</editor-fold>
} catch (GDbException $exc) {
    echo $exc->getError();
}
$mysql->close();
$htmlForm .= '</div>'; //row-fluid

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
        var pagCrud = 'profile_crud.php';

        $('#form').submit(function() {
            $('#p__selecionado').val($('#pro_int_id').val());
            if ($('#form').gValidate()) {
                var men_int_idlist = '';
                $('.menuListaItem').each(function(i, elem) {
                    if ($(elem).is(':checked')) {
                        men_int_idlist += $(elem).attr('rel') + ',';
                    }
                });
                men_int_idlist = men_int_idlist.substring(0, men_int_idlist.length - 1);
                $('#men_int_idlist').val(men_int_idlist);

                var res_int_idlist = '';
                $('.widgetListaItem').each(function(i, elem) {
                    if ($(elem).is(':checked')) {
                        res_int_idlist += $(elem).attr('rel') + ',';
                    }
                });
                res_int_idlist = res_int_idlist.substring(0, res_int_idlist.length - 1);
                $('#res_int_idlist').val(res_int_idlist);

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
            var codigo = $('#pro_int_id').val();
            var param = {acao: 'del', pro_int_id: codigo};

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