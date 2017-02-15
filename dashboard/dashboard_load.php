<?php

require_once("../_inc/global.php");

$html = '';
$mysql = new GDbMysql();
$form = new GForm();
$filterRid = new GFilter();
$filterReq = new GFilter();

$coc_int_id = $_POST['p__coc_int_id'];
$dat_date = $_POST['p__dat_date'];

if (!empty($coc_int_id)) {
    $filterRid->addFilter('AND', 'coc_int_id', '=', 'i', $coc_int_id);
    $filterReq->addFilter('AND', 'coc_int_id', '=', 'i', $coc_int_id);
}

if (!empty($dat_date)) {
    $arrData = explode('-', $dat_date);
    if (empty($arrData[1])) {
        $filterRid->addFilter('AND', 'rid_dat_date', '=', 's', GF::convertDate($arrData[0]));
        $filterReq->addFilter('AND', 'req_dat_date', '=', 's', GF::convertDate($arrData[0]));
    } else {
        $inicio = GF::convertDate($arrData[0]);
        $fim = GF::convertDate($arrData[1]);
        $filterRid->addFilter('AND', 'rid_dat_date', 'BETWEEN', 'ss', array($inicio, $fim), 'F');
        $filterReq->addFilter('AND', 'req_dat_date', 'BETWEEN', 'ss', array($inicio, $fim), 'F');
    }
}

$filterRid->addFilter('AND', 'rid_cha_status', '=', 's', 'CLO');
$filterReq->addFilter('AND', 'rid_cha_status', '=', 's', 'CLO');

//-------------------------------- Filtros -----------------------------------//

try {

    $query = "SELECT coc_int_id, coc_var_name, SUM(req_dec_value) as consumo FROM vw_tax_request " . $filterReq->getWhere() . " GROUP BY coc_int_id ORDER BY coc_var_name LIMIT 10" ;
    $param = $filterReq->getParam();
    $mysql->execute($query, $param);
    $graph1 = array();
    while ($mysql->fetch()) {
        $graph1['labels'][] = $mysql->res['coc_var_name'];
        $graph1['data'][] = $mysql->res['consumo'];
    }
    $graph1['name'] = 'coc';

    $mysql->freeResult();

    $query = "SELECT IF(emp_int_id IS NULL, req_var_passenger, CONCAT(req_var_passenger, ' (', emp_int_id, ')')) as req_var_passenger, coc_var_name, sum(req_dec_value) as req_dec_total FROM vw_tax_request " . $filterReq->getWhere() . " GROUP BY req_var_passenger, coc_var_name ORDER BY req_dec_total DESC, req_var_passenger ASC";
    $param = $filterReq->getParam();
    $mysql->execute($query, $param);
    $table = "";
    $table .= "<table class='table table-striped'>";
    $table .= "<thead>";
    $table .= "<th>Nome</th>";
    $table .= "<th>Centro de Custo</th>";
    $table .= "<th>Valor</th>";
    $table .= "</thead>";
    $table .= "<tbody>";

    while ($mysql->fetch()) {
        $table .= "<tr>";
        $table .= "<td>" . $mysql->res['req_var_passenger'] . "</td>";
        $table .= "<td>" . $mysql->res['coc_var_name'] . "</td>";
        $table .= "<td>" . GF::numberFormat($mysql->res['req_dec_total']) . "</td>";
        $table .= "</tr>";
    }
    $table .= "</tbody>";
    $table .= "</table>";

    $mysql->freeResult();

    $query = "SELECT mot_int_id, mot_var_name, count(1) as qtd FROM vw_tax_request " . $filterReq->getWhere() . " GROUP BY mot_int_id ORDER BY mot_var_name LIMIT 10";
    $param = $filterReq->getParam();
    $mysql->execute($query, $param);
    $graph2 = array();
    while ($mysql->fetch()) {
        $graph2['labels'][] = $mysql->res['mot_var_name'];
        $graph2['data'][] = $mysql->res['qtd'];
    }
    $graph2['name'] = 'motives';

    $mysql->freeResult();

    $query = "SELECT rid_hou_hour, count(1) as qtd FROM vw_tax_request " . $filterReq->getWhere() . " GROUP BY rid_hou_hour ORDER BY rid_hou_hour";
    $param = $filterReq->getParam();
    $mysql->execute($query, $param);
    $graph3 = array();
    while ($mysql->fetch()) {
        $graph3['labels'][] = $mysql->res['rid_hou_hour'];
        $graph3['data'][] = $mysql->res['qtd'];
    }
    $graph3['name'] = 'hour';

    $mysql->freeResult();

    $result = array(
        "graphs" => array($graph1, $graph2, $graph3),
        "ranking" => $table,
        "status"=> true
    );

    echo json_encode($result);
} catch (GDbException $exc) {
    echo $exc->getError();
}
?>