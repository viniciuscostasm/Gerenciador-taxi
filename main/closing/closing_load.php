<?php

require_once("../../_inc/global.php");

$html = '';
$mysql = new GDbMysql();
$form = new GForm();
//------------------------------ Parâmetros ----------------------------------//
$type = $_POST['type'];
$page = $_POST['page'];
$count = $_POST['count'];
$rp = (int) $_POST['rp'];
$start = (($page - 1) * $rp);
//------------------------------ Parâmetros ----------------------------------//
//-------------------------------- Filtros -----------------------------------//
$filter = new GFilter();


$rid_int_id = $_POST['p__rid_int_id'];
$rid_dat_date = $_POST['p__rid_dat_date'];
$rid_hou_hour = $_POST['p__rid_hou_hour'];
$rid_txt_passengerlist = $_POST['p__rid_txt_passengerlist'];
$txc_int_id = $_POST['p__txc_int_id'];
$rid_cha_status = $_POST['p__rid_cha_status'];


if (!empty($rid_int_id)) {
    $filter->addFilter('AND', 'rid_int_id', '=', 'i', $rid_int_id);
}

if (!empty($rid_dat_date)) {
    $arrData = explode('-', $rid_dat_date);
    if (empty($arrData[1])) {
        $filter->addFilter('AND', 'rid_dat_date', '=', 's', GF::convertDate($arrData[0]));
    } else {
        $inicio = GF::convertDate($arrData[0]);
        $fim = GF::convertDate($arrData[1]);
        $filter->addFilter('AND', 'rid_dat_date', 'BETWEEN', 'ss', array($inicio, $fim), 'F');
    }
}

if (!empty($rid_hou_hour)) {
    $filter->addFilter('AND', 'rid_hou_hour', 'LIKE', 's', '%' . str_replace(' ', '%', $rid_hou_hour) . '%');
}

if (!empty($rid_txt_passengerlist)) {
    $filter->addFilter('AND', 'rid_txt_passengerlist', 'LIKE', 's', '%' . str_replace(' ', '%', $rid_txt_passengerlist) . '%');
}

if (!empty($txc_int_id)) {
    $filter->addFilter('AND', 'txc_int_id', '=', 'i', $txc_int_id);
}

if (!empty($rid_cha_status)) {
    $filter->addFilter('AND', 'rid_cha_status', 'LIKE', 's', '%' . str_replace(' ', '%', $rid_cha_status) . '%');
}

$filter->addClause('AND rid_cha_status IN ("APR", "CLO")');


//-------------------------------- Filtros -----------------------------------//

try {
    if ($type == 'C') {
        $query = "SELECT count(1) FROM vw_tax_ride " . $filter->getWhere();
        $param = $filter->getParam();
        $mysql->execute($query, $param);
        if ($mysql->fetch()) {
            $count = ceil($mysql->res[0] / $rp);
        }
        $count = $count == 0 ? 1 : $count;
        echo json_encode(array('count' => $count));
    } else if ($type == 'R') {
        $filter->setOrder(array('rid_hou_hour' => 'ASC'));
        $filter->setLimit($start, $rp);

        $query = "SELECT rid_int_id, rid_daf_date, rid_hou_hour, rid_var_status, 
                         zon_int_id, rid_cha_type, zon_var_namelist, 
                         rid_txt_passengerlist, rid_dec_total, txc_var_name,
                         IFNULL(fn_tax_sourcedestination(rid_int_id, 'S'), 'Continental') AS source,
                         IFNULL(fn_tax_sourcedestination(rid_int_id, 'D'), 'Continental') AS destination
                    FROM vw_tax_ride " . $filter->getWhere();
        $param = $filter->getParam();
        $mysql->execute($query, $param);

        if ($mysql->numRows() > 0) {
            $html .= '<table class="table table-striped table-hover">';
            $html .= '<thead>';
            $html .= '<tr>';
            $html .= '<th>Cód.</th>';
            $html .= '<th>Data</th>';
            $html .= '<th>Hora</th>';
            $html .= '<th>Passageiros</th>';
            $html .= '<th>Origem</th>';
            $html .= '<th>Destino</th>';
            $html .= '<th>Empresa</th>';
            $html .= '<th>Status</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';
            while ($mysql->fetch()) {
                $zon_var_namelist = explode(',',$mysql->res['zon_var_namelist']);
                $rid_txt_passengerlist = explode(',', $mysql->res['rid_txt_passengerlist']);

                $arrayPassengers = array();
                foreach ($rid_txt_passengerlist as $key => $passenger) {
                    $arrayPassengers[] = $passenger . ' (' . $zon_var_namelist[$key] . ')';
                }

                $class = ($_POST['p__selecionado'] == $mysql->res['rid_int_id']) ? 'success' : '';
                $html .= '<tr id="' . $mysql->res['rid_int_id'] . '" class="linhaRegistro ' . $class . '">';
                $html .= '<td>' . $mysql->res['rid_int_id'] . '</td>';
                $html .= '<td>' . $mysql->res['rid_daf_date'] . '</td>';
                $html .= '<td>' . $mysql->res['rid_hou_hour'] . '</td>';
                $html .= '<td>' . implode('<br>', $arrayPassengers) . '</td>';

                $html .= '<td>' . $mysql->res['source'] . '</td>';
                $html .= '<td>' . $mysql->res['destination'] . '</td>';
                $html .= '<td>' . $mysql->res['txc_var_name'] . '</td>';
                $html .= '<td>' . $mysql->res['rid_var_status'] . '</td>';

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