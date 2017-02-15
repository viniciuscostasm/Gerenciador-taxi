<?php
require_once '../../_inc/global.php';

$form = new GForm();
$mysql = new GDbMysql();
$filter = new GFilter();

$rid_int_id = $_POST['p__rid_int_id'];

$usr_int_id = GSec::getUserSession()->getUsr_int_id();

$txc_int_id = $_POST['p__txc_int_id'];
$rid_dat_date = $_POST['p__rid_dat_date'];
$txc_var_name = $_POST['p__txc_var_name'];
$acao = $_POST['acao'];

if (!empty($txc_var_name)) {
    $filter->addFilter('AND', 'txc_var_name', 'LIKE', 's', '%' . str_replace(' ', '%', $txc_var_name) . '%');
}

if (!empty($rid_int_id)) {
    $filter->addFilter('AND', 'rid_int_id', '=', 'i', $rid_int_id);
}

if (!empty($txc_int_id)) {
    $filter->addFilter('AND', 'txc_int_id', '=', 'i', $txc_int_id);
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

$filter->addFilter('AND', 'rid_cha_status', '=', 's', 'CLO');

try {
	$graph = array();
 	$query = "SELECT txc_var_name, TRUNCATE (sum(rid_dec_total), 2) as rid_dec_totalsum FROM vw_tax_ride ". $filter->getWhere() . " GROUP BY txc_var_name";
 	$param = $filter->getParam();
    $mysql->execute($query, $param, true, MYSQL_ASSOC);
    $graph['data'] = array();
    while ($mysql->fetch()) {
    	$graph['labels'][] =  $mysql->res['txc_var_name'];
    	$graph['data'][] =  $mysql->res['rid_dec_totalsum'];
    }
    $mysql->freeResult();

    $query = "SELECT rid_int_id, rid_daf_date, rid_hou_hour, txc_var_name,
    			rid_dec_value, rid_dec_extreme, rid_dec_parking,
    			rid_dec_stopped, IFNULL(rid_hor_stopped, 0) AS rid_hor_stopped, rid_dec_transfer, rid_dec_total
    		  	FROM vw_tax_ride " . $filter->getWhere() . " ORDER BY rid_int_id";

    $param = $filter->getParam();
    $mysql->execute($query, $param);

	$tax_rides = array();
	while ($mysql->fetch()) {
		if (!isset($tax_rides[$mysql->res['txc_var_name']])) {
			$tax_rides[$mysql->res['txc_var_name']] = array();
		}

		$tax_rides[$mysql->res['txc_var_name']][] = array(
			'txc_var_name' => $mysql->res['txc_var_name'],
			'rid_daf_date' => $mysql->res['rid_daf_date'],
			'rid_hou_hour' => $mysql->res['rid_hou_hour'],
			'rid_int_id' => $mysql->res['rid_int_id'],
			'rid_dec_value' => $mysql->res['rid_dec_value'],
			'rid_dec_extreme' => $mysql->res['rid_dec_extreme'],
			'rid_dec_stopped' => $mysql->res['rid_dec_stopped'],
			'rid_hor_stopped' => $mysql->res['rid_hor_stopped'],
			'rid_dec_parking' => $mysql->res['rid_dec_parking'],
			'rid_dec_transfer' => $mysql->res['rid_dec_transfer'],
			'rid_dec_total' => $mysql->res['rid_dec_total']
		);
	}

	if ($acao != 'graph') {
		if (!empty($tax_rides)) {
			$total = 0;
			$html = '<div class="no-more-tables">';
			foreach ($tax_rides as $txc_group) {
				$totalGrupo = 0;
				$html .= '<div class="row-fluid">';
				$html .= '	<h3>Empresa: ' . $txc_group[0]['txc_var_name'] . '</h3>';
				$html .= '	<table class="table table-striped">';
				$html .= '		<thead>';
				$html .= '			<th>Data</th>';
				$html .= '			<th>Hora</th>';
				$html .= '			<th>Corrida</th>';
				$html .= '			<th>Valor</th>';
				$html .= '			<th>Extremo</th>';
				$html .= '			<th>Hora Parada</th>';
				$html .= '			<th>Estacionamento</th>';
				$html .= '			<th>Deslocamento</th>';
				$html .= '			<th>Total</th>';
				$html .= '		</thead>';
				$html .= '		<tbody>';
				foreach ($txc_group as $ride) {
					$html .=		'<tr>';
					$html .=		'	<td>' . $ride['rid_daf_date'] . '</td>';
					$html .=		'	<td>' . $ride['rid_hou_hour'] . '</td>';
					$html .=		'	<td>' . $ride['rid_int_id'] . '</td>';
					$html .=		'	<td>' . GF::numberFormat($ride['rid_dec_value']) . '</td>';
					$html .=		'	<td>' . GF::numberFormat($ride['rid_dec_extreme']) . '</td>';
					$html .=		'	<td>' . GF::numberFormat($ride['rid_dec_stopped']) . ' (' . $ride['rid_hor_stopped'] . ')</td>';
					$html .=		'	<td>' . GF::numberFormat($ride['rid_dec_parking']) . '</td>';
					$html .=		'	<td>' . GF::numberFormat($ride['rid_dec_transfer']) . '</td>';
					$html .=		'	<td>' . GF::numberFormat($ride['rid_dec_total']) . '</td>';
					$html .=		'</tr>';
					$totalGrupo += $ride['rid_dec_total'];
				}
				$html .= '		</tbody>';
				$html .= '	</table>';
				$html .= '<h4 class="text-right sepH_C">Total: ' . GF::numberFormat($totalGrupo);
				$html .= '</div>';
				$total += $totalGrupo;
			}
			$html .= '<div class="row-fluid">';
			$html .= '<h3 class="text-right">Total Geral: ' . GF::numberFormat($total);
			$html .= '</div>';
			$html .= '</div>';
		} else {
			$html .= '<div class="nenhumResultado">Não há corridas que correspondam a essa pesquisa</div>';
		}
		echo $html;
	} else {
		echo json_encode($graph + array("status"=>"true"));
	}
} catch (Exception $e) {

}