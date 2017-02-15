<?php

require_once("../../_inc/global.php");

$html = '';
$mysql = new GDbMysql();
//-------------------------------- Filtros -----------------------------------//
$filter = new GFilter();

$rid_int_id = $_POST['rid_int_id'];

//-------------------------------- Filtros -----------------------------------//

try {
    if(!empty($rid_int_id)){
        $filter->setOrder(array('log_dat_date' => 'DESC', 'log_hou_hour' => 'DESC'));
        $filter->addFilter('AND', 'rid_int_id', '=', 'i', $rid_int_id);

        $query = "SELECT log_int_id, log_dat_date, log_daf_date, log_hou_hour, log_var_type, log_txt_status, rid_int_id, usr_var_name FROM vw_tax_log " . $filter->getWhere();
        $param = $filter->getParam();
        $mysql->execute($query, $param);

        $html .= '<div class="row-fluid">';
        $html .= '<div class="col-md-12">';
        if ($mysql->numRows() > 0) {
            while ($mysql->fetch()) {
                $html .= '<div class="logLista sepH_c">';
                $html .= '<div class="row-fluid logListaTopo">';
                $html .= '<div class="col-md-4">' . $mysql->res['log_daf_date'] . ' - ' . $mysql->res['log_hou_hour'] . '</div>';
                $html .= '<div class="col-md-4 text-center">' . $mysql->res['usr_var_name'] . '</div>';
                $html .= '<div class="col-md-4 text-right">' . $mysql->res['log_var_type'] . '</div>';
                $html .= '</div>';
                $html .= '<div id="conteudo_' . $mysql->res['log_int_id'] . '" class="logListaConteudo">' . trim($mysql->res['log_txt_status']) . '</div>';
                $html .= '</div>';
            }
        } else {
            $html .= '<div class="nenhumResultado">Nenhum resultado foi encontrado.</div>';
        }
        $html .= '</div>'; //col-md-12
        $html .= '</div>'; //row-fluid
    } else {
        $html .= '<div class="nenhumResultado">Favor informar algum agendamento.</div>';
    }

    echo $html;
} catch (GDbException $exc) {
    echo $exc->getError();
}
?>