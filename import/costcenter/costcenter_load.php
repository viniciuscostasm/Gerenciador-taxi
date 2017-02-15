<?php

require_once("../../_inc/global.php");

$html = '';
$mysql = new GDbMysql();
$form = new GForm();

$type = $_POST['type'];
$page = $_POST['page'];
$count = $_POST['count'];
$rp = (int) $_POST['rp'];
$start = (($page - 1) * $rp);

//-------------------------------- Filtros -----------------------------------//
$filter = new GFilter();

$usr_int_id = $_POST['p__usr_int_id'];
$cci_dat_date = $_POST['p__cci_dat_date'];

if (!empty($usr_int_id)) {
    $filter->addFilter('AND', 'usr_int_id', '=', 'i', $rid_int_id);
}

if (!empty($cci_dat_date)) {
    $arrData = explode('-', $cci_dat_date);
    if (empty($arrData[1])) {
        $filter->addFilter('AND', 'cci_dat_date', '=', 's', GF::convertDate($arrData[0]));
    } else {
        $inicio = GF::convertDate($arrData[0]);
        $fim = GF::convertDate($arrData[1]);
        $filter->addFilter('AND', 'cci_dat_date', 'BETWEEN', 'ss', array($inicio, $fim), 'F');
    }
}

//-------------------------------- Filtros -----------------------------------//

try {
    if ($type == 'C') {
        $query = "SELECT count(1) FROM vw_tax_costcenterimport " . $filter->getWhere();
        $param = $filter->getParam();
        $mysql->execute($query, $param);
        if ($mysql->fetch()) {
            $count = ceil($mysql->res[0] / $rp);
        }
        $count = $count == 0 ? 1 : $count;
        echo json_encode(array('count' => $count));
    } else if ($type == 'R') {
        $filter->setOrder(array('cci_daf_date' => 'DESC', 'cci_hou_hour' => 'DESC'));
        $filter->setLimit($start, $rp);

        $query = "SELECT cci_int_id, cci_daf_date, cci_hou_hour, cci_var_filename, cci_var_result, usr_var_name
                    FROM vw_tax_costcenterimport " . $filter->getWhere();
        $param = $filter->getParam();
        $mysql->execute($query, $param);
        if ($mysql->numRows() > 0) {
            $html .= '<table class="table table-striped table-hover">';
            $html .= '<thead>';
            $html .= '<tr>';
            $html .= '<th>Data</th>';
            $html .= '<th>Hora</th>';
            $html .= '<th>Arquivo</th>';
            $html .= '<th>Usuário</th>';
            $html .= '<th>Resultado</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';
            while ($mysql->fetch()) {
                $html .= '<tr>';
                $html .= '<td>' . $mysql->res['cci_daf_date'] . '</td>';
                $html .= '<td>' . $mysql->res['cci_hou_hour'] . '</td>';
                $html .= '<td>' . $mysql->res['cci_var_filename'] . '</td>';
                $html .= '<td>' . $mysql->res['usr_var_name'] . '</td>';
                $html .= '<td>' . $mysql->res['cci_var_result'] . '</td>';
                $html .= '</tr>';
            }
            $html .= '</tbody>';
            $html .= '</table>';
        } else {
            $html .= '<div class="nenhumResultado">Nenhum resultado encontrado.</div>';
        }

        $html .= $form->close();

        echo $html;
    }
} catch (GDbException $exc) {
    echo $exc->getError();
}
?>