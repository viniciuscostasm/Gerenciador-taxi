<?php
$form = new GForm();

try {
    $opt_cou_var_name = $mysql->executeCombo('SELECT cou_cha_country, cou_var_name FROM adm_country ORDER BY cou_var_name;');
} catch (Exception $e) {
    
}

//<editor-fold desc="Header">
$title = '<span class="acaoTitulo"></span>';
$tools = '<a id="f__btn_voltar" class="acaoWidget"><i class="icon-arrow-left"></i> <span class="hidden-phone">Voltar</span></a>';
$htmlForm .= getWidgetHeader($title, $tools);
//</editor-fold>
//<editor-fold desc="Formulário">
$htmlForm .= $form->open('form', 'form-vertical form');
$htmlForm .= $form->addInput('hidden', 'acao', false, array('value' => 'ins', 'class' => 'acao'), false, false, false);
$htmlForm .= $form->addInput('hidden', 'usr_int_id', false, array('value' => ''), false, false, false);
$htmlForm .= $form->addInput('hidden', 'coc_int_id', false, array('value' => ''), false, false, false);
$htmlForm .= $form->addInput('hidden', 'txc_int_id', false, array('value' => ''), false, false, false);

$htmlForm .= '<div class="row">';
$htmlForm .= '<div class="col-md-5">' . $form->addInput('text', 'usr_var_name', 'Nome*', array('maxlength' => '100', 'validate' => 'required')) . '</div>';
// $htmlForm .= '<div class="col-md-4">' . $form->addInput('text', 'usr_var_function', 'Function*', array('maxlength' => '50', 'validate' => 'required')) . '</div>';
$htmlForm .= '<div class="col-md-3">' . $form->addStatus('usr_cha_status', 'Status', 'A') . '</div>';
$htmlForm .= '</div>';

$htmlForm .= '<div class="row">';
$htmlForm .= '<div class="col-md-4">' . $form->addInput('text', 'usr_var_email', 'Email*', array('maxlength' => '100', 'validate' => 'required;email')) . '</div>';
// $htmlForm .= '<div class="col-md-2">' . $form->addInput('text', 'usr_var_phone', 'Phone*', array('maxlength' => '20', 'validate' => 'required')) . '</div>';
// $htmlForm .= '<div class="col-md-2">' . $form->addSelect('cou_cha_country', $opt_cou_var_name , '', 'Country*', array('validate' => 'required'), false, false, true, '', 'Select...') . '</div>';
// $htmlForm .= '<div class="col-md-3"><div id="loadZone"></div></div>';
// $htmlForm .= '<div class="col-md-2">' . $html .= $form->addSelect('zon_int_id', $opt_cou_var_name , '', 'Country', array('validate' => 'required'), false, false, true, '', 'Select...') . '</div>';
$htmlForm .= '</div>';

$htmlForm .= '<div class="row">';
$htmlForm .= '<div class="col-md-6">' . $form->addSelect('pro_int_id', $opt_pro_var_name, '', 'Profile*', array('validate' => 'required'), false, false, true, '', 'Select...') . '</div>';
$htmlForm .= '<div class="col-md-6" id="loadProfileChange"></div>';
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
    var pagCrud = 'profile_crud.php';

    $(function() {
        $('#form').submit(function() {
            var coc_int_idlist = $('.coc_int_idlist');

            if ($('#form').gValidate()) {
                $('#p__selecionado').val($('#usr_int_id').val());
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
            var codigo = $('#usr_int_id').val();
            var param = {acao: 'del', usr_int_id: codigo};

            $.gDisplay.showYN("Do you really want to delete the selected item?", function() {
                $.gAjax.execCallback(pagCrud, param, false, function(json) {
                    if (json.status) {
                        showList(true);
                    }
                });
            });
        });

        $('#pro_int_id').change(function() {
            changeProfile($(this).val(),$('#coc_int_id').val(),$('#txc_int_idlist').val(),'');
        });

        // $('#cou_cha_country').change(function(){
        //     var cou_cha_country = $(this).val();
        //     loadZone(cou_cha_country, '');
        // });
    });

    function changeProfile(pro_int_id, coc_int_idlist, txc_int_idlist) {
        $.gAjax.load(pagCrud, {acao: 'changeProfile', pro_int_id:pro_int_id, coc_int_idlist:coc_int_idlist, txc_int_idlist:txc_int_idlist}, '#loadProfileChange');
    }
    // function loadZone(cou_cha_country, zon_int_id) {
    //     $.gAjax.load(pagCrud, {acao: 'loadZone', cou_cha_country:cou_cha_country, zon_int_id:zon_int_id}, '#loadZone');
    // }
</script>