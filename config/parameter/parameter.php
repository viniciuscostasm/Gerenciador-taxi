<?php
require_once '../../_inc/global.php';

$form = new GForm();
$mysql = new GDbMysql();


$header = new GHeader('Parameters');
$header->addLib(array('paginate'));
$header->show(false, 'config/parameter/parameter.php');
// ---------------------------------- Header ---------------------------------//
try {
    $query = "SELECT par_int_limitaccept, par_int_limitanswer, par_int_limitapprove, par_int_limittotal, 
                     par_cha_hideproblemdescription, par_cha_hideimmediatemeasure,  par_cha_hidediscussion 
                FROM vw_rep_parameter ";
    $mysql->execute($query);
    $mysql->fetch();
} catch (GDbException $exc) {
    echo $exc->getError();
}

$html .= '<div class="row">';
$html .= getWidgetHeader('', '');
//</editor-fold>
//<editor-fold desc="Formulário">
$html .= $form->open('form', 'form-vertical form');
$html .= $form->addInput('hidden', 'acao', false, array('value' => 'upd', 'class' => 'acao'), false, false, false);

$html .= '<h3>Time limits (in days)</h3>';
$html .= '<div class="row sepH_b">';
$html .= '<div class="col-md-6">' . $form->addLabel('par_int_limitaccept', 'Time limit to accept 8D report - Engineering Tires') . '</div>';
$html .= '<div class="col-md-1">' . $form->addInput('text', 'par_int_limitaccept', 'Time limit to accept 8D report - Engineering Tires', array('value' => $mysql->res['par_int_limitaccept'], 'maxlength' => '11','validate' => 'required'), array('style' => 'display:none'), false, false) . '</div>';
$html .= '</div>';
$html .= '<div class="row sepH_b">';
$html .= '<div class="col-md-6">' . $form->addLabel('par_int_limitanswer', 'Time limit to answer 8D report - Machine Supplier') . '</div>';
$html .= '<div class="col-md-1">' . $form->addInput('text', 'par_int_limitanswer', 'Time limit to answer 8D report - Machine Supplier', array('value' => $mysql->res['par_int_limitanswer'], 'maxlength' => '11','validate' => 'required'), array('style' => 'display:none'), false, false) . '</div>';
$html .= '</div>';
$html .= '<div class="row sepH_b">';
$html .= '<div class="col-md-6">' . $form->addLabel('par_int_limitapprove', 'Time limit to approve 8D report - Engineering Tires and Plant Engineering') . '</div>';
$html .= '<div class="col-md-1">' . $form->addInput('text', 'par_int_limitapprove', 'Time limit to approve 8D report - Engineering Tires and Plant Engineering', array('value' => $mysql->res['par_int_limitapprove'], 'maxlength' => '11','validate' => 'required'), array('style' => 'display:none'), false, false) . '</div>';
$html .= '</div>';
$html .= '<div class="row sepH_b">';
$html .= '<div class="col-md-6">' . $form->addLabel('par_int_limittotal', '8D report total time') . '</div>';
$html .= '<div class="col-md-1">' . $form->addInput('text', 'par_int_limittotal', '8D report total time', array('value' => $mysql->res['par_int_limittotal'], 'maxlength' => '11','validate' => 'required'), array('style' => 'display:none'), false, false) . '</div>';
$html .= '</div>';


$html .= '<h3>Chapters to hide for suppliers</h3>';

$ckd_par_cha_hideproblemdescription = ($mysql->res['par_cha_hideproblemdescription'] == 'Y') ? array('checked' => 'checked') : array();
$ckd_par_cha_hideimmediatemeasure = ($mysql->res['par_cha_hideimmediatemeasure'] == 'Y') ? array('checked' => 'checked') : array();
$ckd_par_cha_hidediscussion = ($mysql->res['par_cha_hidediscussion'] == 'Y') ? array('checked' => 'checked') : array();

$html .= $form->addCheckbox('par_cha_hideproblemdescription', 'Hide problem description for suppliers', array('value' => 'Y', 'class' => 'icheck') + $ckd_par_cha_hideproblemdescription);
$html .= $form->addCheckbox('par_cha_hideimmediatemeasure',   'Hide immediate measure for suppliers', array('value' => 'Y', 'class' => 'icheck') + $ckd_par_cha_hideimmediatemeasure);
$html .= $form->addCheckbox('par_cha_hidediscussion', 'Hide discussion of final report and evaluation', array('value' => 'Y', 'class' => 'icheck') + $ckd_par_cha_hidediscussion);

$html .= '<div class="form-actions">';
$html .= getBotoesAcao(false, false);
$html .= '</div>';
$html .= $form->close();
//</editor-fold>
$html .= getWidgetFooter();
$html .= '</div>';

echo $html;

// ---------------------------------- Footer ---------------------------------//
$footer = new GFooter();
$footer->show();
?>
<script>
    var pagCrud = 'parameter_crud.php';

    $(function() {
        $('#form').submit(function() {
            if ($('#form').gValidate()) {
                $('#p__selecionado').val($('#usr_int_id').val());
                $.gAjax.execCallback(pagCrud, $('#form').serializeArray(), false, function(json) {
                    if (json.status) {
                        $.gDisplay.showSuccess('Successfully updated parameters', function(){
                            location.reload();
                        });
                    }
                });
            }
            return false;
        });

        $('.icheck').iCheck({
            checkboxClass: "icheckbox_minimal"
        });
    });

</script>