<?php
require_once '../../_inc/global.php';

$form = new GForm();
$mysql = new GDbMysql();
$filter = new GFilter();

$rid_int_id = $_POST['p__rid_int_id'];
$req_dat_date = $_POST['p__req_dat_date'];
$coc_int_id = $_POST['p__coc_int_id'];
$mot_int_id = $_POST['p__mot_int_id'];
$req_var_passenger = $_POST['p__req_var_passenger'];
$acao = $_POST['acao'];

if (!empty($req_var_passenger)) {
    $filter->addFilter('AND', 'req_var_passenger', 'LIKE', 's', '%' . str_replace(' ', '%', $req_var_passenger) . '%');
}

if (!empty($rid_int_id)) {
    $filter->addFilter('AND', 'rid_int_id', '=', 'i', $rid_int_id);
}

if (!empty($coc_int_id)) {
    $filter->addFilter('AND', 'coc_int_id', '=', 'i', $coc_int_id);
}

if (!empty($mot_int_id)) {
    $filter->addFilter('AND', 'mot_int_id', '=', 'i', $mot_int_id);
}

if (!empty($req_dat_date)) {
    $arrData = explode('-', $req_dat_date);
    if (empty($arrData[1])) {
        $filter->addFilter('AND', 'req_dat_date', '=', 's', GF::convertDate($arrData[0]));
    } else {
        $inicio = GF::convertDate($arrData[0]);
        $fim = GF::convertDate($arrData[1]);
        $filter->addFilter('AND', 'req_dat_date', 'BETWEEN', 'ss', array($inicio, $fim), 'F');
    }
}

// $filter->addFilter('AND', 'usr_int_id', '=', 'i', $usr_int_id);
$filter->addFilter('AND', 'req_cha_status', '=', 's', 'CLO');

try {
	$graph = array();
 	$query = "SELECT coc_var_name, TRUNCATE (sum(req_dec_value), 2) as req_dec_valuesum FROM vw_tax_request ". $filter->getWhere() . " GROUP BY coc_var_name";
 	$param = $filter->getParam();
    $mysql->execute($query, $param, true, MYSQL_ASSOC);
    $graph['data'] = array();
    while ($mysql->fetch()) {
    	$graph['labels'][] =  $mysql->res['coc_var_name'];
    	$graph['data'][] =  $mysql->res['req_dec_valuesum'];
    }
    $mysql->freeResult();

    $query = "SELECT coc_int_id, coc_var_name, req_daf_date, req_var_hour, req_cha_status,
    				 req_var_passenger, dis_var_namesource, cit_var_namesource, dis_var_namedestination, cit_var_namedestination, 
    				 rid_int_id, req_dec_value, req_cha_type
    			FROM vw_tax_request" . $filter->getWhere() . " ORDER BY coc_int_id";

    $param = $filter->getParam();
    $mysql->execute($query, $param);

	$tax_requests = array();
	while ($mysql->fetch()) {
		if (!isset($tax_requests[$mysql->res['coc_int_id']])) {
			$tax_requests[$mysql->res['coc_int_id']] = array();
		}

		switch ($mysql->res['req_cha_type']) {
			case 'SCO':
				$source = 'Continental';
				$destination = $mysql->res['dis_var_namedestination'] . ' - ' . $mysql->res['cit_var_namedestination'];
				break;

			case 'DCO':
				$destination = 'Continental';
				$source = $mysql->res['dis_var_namesource'] . ' - ' . $mysql->res['cit_var_namesource'];
				break;

			default:
				$source = $mysql->res['dis_var_namesource'] . ' - ' . $mysql->res['cit_var_namesource'];
				$destination = $mysql->res['dis_var_namedestination'] . ' - ' . $mysql->res['cit_var_namedestination'];
				break;
		}

		$tax_requests[$mysql->res['coc_int_id']][] = array(
			'coc_var_name' => $mysql->res['coc_var_name'],
			'req_cha_type' => $mysql->res['req_cha_type'],
			'req_daf_date' => $mysql->res['req_daf_date'],
			'req_var_hour' => $mysql->res['req_var_hour'],
			'req_var_passenger' => $mysql->res['req_var_passenger'],
			'source' => $source,
			'destination' => $destination,
			'rid_int_id' => $mysql->res['rid_int_id'],
			'req_dec_value' => $mysql->res['req_dec_value']
		);
	}

	if ($acao != 'graph') {
		if (!empty($tax_requests)) {
			$total = 0;
			$html = '<div class="no-more-tables">';
			foreach ($tax_requests as $coc_group) {
				$totalGrupo = 0;
				$html .= '<div class="row-fluid">';
				$html .= '	<h3>Centro de custo: ' . $coc_group[0]['coc_var_name'] . '</h3>';
				$html .= '	<table class="table table-striped">';
				$html .= '		<thead>';
				$html .= '			<th width="85px">Data</th>';
				$html .= '			<th width="30px">Hora</th>';
				$html .= '			<th width="250px">Passageiro</th>';
				$html .= '			<th width="200px">Origem</th>';
				$html .= '			<th width="200px">Destino</th>';
				$html .= '			<th width="60px">Corrida</th>';
				$html .= '			<th width="60px">Valor</th>';
				$html .= '		</thead>';
				$html .= '		<tbody>';
				foreach ($coc_group as $request) {
					$html .=		'<tr>';
					$html .=		'	<td>' . $request['req_daf_date'] . '</td>';
					$html .=		'	<td>' . $request['req_var_hour'] . '</td>';
					$html .=		'	<td>' . $request['req_var_passenger'] . '</td>';
					$html .=		'	<td>' . $request['source'] . '</td>';
					$html .=		'	<td>' . $request['destination'] . '</td>';
					$html .=		'	<td>' . $request['rid_int_id'] . '</td>';
					$html .=		'	<td>' . GF::numberFormat($request['req_dec_value']) . '</td>';
					$html .=		'</tr>';
					$totalGrupo += $request['req_dec_value'];
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