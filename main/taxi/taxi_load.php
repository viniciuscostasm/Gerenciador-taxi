<?php
require_once '../../_inc/global.php';

$form = new GForm();
$mysql = new GDbMysql();
$filter = new GFilter();

$rid_hou_hour = $_POST['p__rid_hou_hour'];
$usr_int_id = GSec::getUserSession()->getUsr_int_id();
$txc_int_id = NULL;

$mysql->execute("SELECT txc_int_id FROM vw_tax_taxicompany_user WHERE usr_int_id = ?", array("i", $usr_int_id));
if ($mysql->fetch()) {
	$txc_int_id = $mysql->res['txc_int_id'];
}
$mysql->freeResult();

$rid_dat_date = $_POST['p__rid_dat_date'];
$rid_dat_date_text = $_POST['p__rid_dat_date_text'];
$rid_txt_passengerlist = $_POST['p__rid_txt_passengerlist'];

if (!empty($rid_txt_passengerlist)) {
    $filter->addFilter('AND', 'rid_txt_passengerlist', 'LIKE', 's', '%' . str_replace(' ', '%', $rid_txt_passengerlist) . '%');
}

if (!empty($rid_hou_hour)) {
    $filter->addFilter('AND', 'rid_hou_hour', '=', 's', $rid_hou_hour);
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

// $filter->addFilter('AND', 'usr_int_id', '=', 'i', $usr_int_id);
$filter->addClause("AND rid_cha_status IN ('CLO', 'APR')");

try {
    $query = "SELECT rid_int_id, rid_daf_date, rid_hou_hour, rid_txt_passengerlist, rid_var_status,
    			IFNULL(fn_tax_sourcedestination(rid_int_id, 'S'), 'Continental') AS source,
 				IFNULL(fn_tax_sourcedestination(rid_int_id, 'D'), 'Continental') AS destination,
    			rid_dec_value, zon_var_namelist, rid_dec_total
    		  	FROM vw_tax_ride " . $filter->getWhere() . " ORDER BY rid_hou_hour ASC";

    $param = $filter->getParam();
    $mysql->execute($query, $param);
	$tax_rides = array();
	while ($mysql->fetch()) {
		$zon_var_namelist = explode(',',$mysql->res['zon_var_namelist']);
        $rid_txt_passengerlist = explode(',', $mysql->res['rid_txt_passengerlist']);

        $arrayPassengers = array();
        foreach ($rid_txt_passengerlist as $key => $passenger) {
            $arrayPassengers[] = $passenger . ' (' . $zon_var_namelist[$key] . ')';
        }

		$tax_rides[] = array(
			'rid_int_id' => $mysql->res['rid_int_id'],
			'rid_daf_date' => $mysql->res['rid_daf_date'],
			'rid_hou_hour' => $mysql->res['rid_hou_hour'],
			'rid_dec_total' => $mysql->res['rid_dec_total'],
			'rid_var_status' => $mysql->res['rid_var_status'],
			'rid_txt_passengerlist' => $arrayPassengers,
			'source' => $mysql->res['source'],
			'destination' => $mysql->res['destination']
		);
	}

	if (!empty($tax_rides)) {
		$total = 0;
		$html = '<div class="no-more-tables">';
		$html .= '<div class="row-fluid">';
		$html .= '	<table class="table table-striped">';
		$html .= '		<thead>';
		$html .= '			<th width="120px">Hora</th>';
		$html .= '			<th>Passageiros</th>';
	 $html .= '			<th>Origem</th>';
	 $html .= '			<th>Destino</th>';
		$html .= '			<th width="180px">Valor total</th>';
		$html .= '			<th>Status</th>';
		$html .= '			<th width="30px">Ação</th>';
		$html .= '		</thead>';
		$html .= '		<tbody>';
		foreach ($tax_rides as $ride) {
			$html .=		'<tr>';
			$html .=		'	<td>' . $ride['rid_daf_date'] . ' ' . $ride['rid_hou_hour'] . '</td>';
			$html .=		'	<td>' . implode('<br>', $ride['rid_txt_passengerlist']) . '</td>';
			$html .=		'	<td>' . $ride['source'] . '</td>';
			$html .=		'	<td>' . $ride['destination'] . '</td>';
			$html .=		'	<td>' . GF::numberFormat($ride['rid_dec_total']) . '</td>';
			$html .=		'	<td>' . $ride['rid_var_status'] . '</td>';
			$html .=		'	<td>';
            $html .= 			'<a class="btn btn-small btn-icon-only l__btn_excluir" title ="Imprimir Voucher" href="../voucher/voucher_print.php?id=' . $ride['rid_int_id'] . '" target="_blank"><i class="fa fa-print"></i></a>';
			$html .=		'	</td>';
			$html .=		'</tr>';
		}
		$html .= '		</tbody>';
		$html .= '	</table>';
		$html .= '</div>';
		$html .= '</div>';
	} else {
		$html .= '<div class="nenhumResultado">Não há corridas que correspondam a essa pesquisa</div>';
	}
	echo $html;

} catch (Exception $e) {

}