<?php
require_once '../../_inc/global.php';
require_once(ROOT_GENESIS . "inc/mpdf/mpdf.php");

$form = new GForm();
$mysql = new GDbMysql();
$filter = new GFilter();

$rid_int_id = $_GET['id'];

if (!empty($rid_int_id)) {
    $filter->addFilter('AND', 'rid_int_id', '=', 'i', $rid_int_id);

    $filter->addClause("AND rid_cha_status IN ('CLO', 'APR')");

	try {
	    $query = "SELECT rid_int_id, rid_cha_type, rid_daf_date, rid_hou_hour, txc_var_name,
	    			IFNULL(fn_tax_sourcedestination(rid_int_id, 'S'), 'Continental') AS source,
	 				IFNULL(fn_tax_sourcedestination(rid_int_id, 'D'), 'Continental') AS destination,
	    			rid_dec_value, rid_dec_extreme, rid_hor_stopped, rid_dec_stopped,
	    			rid_dec_parking, rid_cha_transfer, rid_dec_transfer, rid_dec_total
	    			FROM vw_tax_ride " . $filter->getWhere() . " LIMIT 1";
	    $param = $filter->getParam();
	    $mysql->execute($query, $param);

		$tax_ride = array();
		while ($mysql->fetch()) {
			$tax_ride = array(
				'rid_int_id' => $mysql->res['rid_int_id'],
				'rid_cha_type' => $mysql->res['rid_cha_type'],
				'rid_daf_date' => $mysql->res['rid_daf_date'],
				'rid_hou_hour' => $mysql->res['rid_hou_hour'],
				'txc_var_name' => $mysql->res['txc_var_name'],
				'rid_dec_value' => $mysql->res['rid_dec_value'],
				'rid_dec_extreme' => $mysql->res['rid_dec_extreme'],
				'rid_hor_stopped' => $mysql->res['rid_hor_stopped'],
				'rid_dec_stopped' => $mysql->res['rid_dec_stopped'],
				'rid_dec_parking' => $mysql->res['rid_dec_parking'],
				'rid_cha_transfer' => $mysql->res['rid_cha_transfer'],
				'rid_dec_transfer' => $mysql->res['rid_dec_transfer'],
				'rid_dec_total' => $mysql->res['rid_dec_total'],
				'source' => $mysql->res['source'],
				'destination' => $mysql->res['destination']
			);
		}

		$query = "SELECT req_var_passenger, req_var_addresssource, dis_var_namesource, cit_var_namesource,
					cit_cha_ufsource, zon_var_namesource, req_var_addressdestination, dis_var_namedestination,
					cit_var_namedestination, cit_cha_ufdestination, zon_var_namedestination, req_dec_value, rid_int_order
					FROM vw_tax_request " . $filter->getWhere() . " ORDER BY rid_int_order ASC";
	    $param = $filter->getParam();
	    $mysql->execute($query, $param);

		$tax_requests = array();
		while ($mysql->fetch()) {
			$tax_requests[] = array(
				'req_var_passenger' => $mysql->res['req_var_passenger'],
				'req_var_address' => (empty($mysql->res['req_var_addresssource'])) ? $mysql->res['req_var_addressdestination'] : $mysql->res['req_var_addresssource'],
				'dis_var_name' => (empty($mysql->res['dis_var_namesource'])) ? $mysql->res['dis_var_namedestination'] : $mysql->res['dis_var_namesource'],
				'cit_var_name' => (empty($mysql->res['cit_var_namesource'])) ? $mysql->res['cit_var_namedestination'] : $mysql->res['cit_var_namesource'],
				'cit_cha_uf' => (empty($mysql->res['cit_cha_ufsource'])) ? $mysql->res['cit_cha_ufdestination'] : $mysql->res['cit_cha_ufsource'],
				'zon_var_name' => (empty($mysql->res['zon_var_namesource'])) ? $mysql->res['zon_var_namedestination'] : $mysql->res['zon_var_namesource'],
				'req_dec_value' => $mysql->res['req_dec_value']
			);
		}

		if (!empty($tax_ride) && !empty($rid_int_id)) {
			$html .= '<br style="clear: both">'; 
			$html .= '<div style="width: 25%; float: left">Data: ' . $tax_ride['rid_daf_date'] . '</div>';
			$html .= '<div style="width: 25%; float: left">Hora de partida: ' . $tax_ride['rid_hou_hour'] . '</div>';
			$html .= '<div style="width: 25%; float: left">Empresa: ' . $tax_ride['txc_var_name'] . '</div>';
			$html .= '<br style="clear: both">';
			$html .= '<b>Origem: </b>' . $tax_ride['source'];
			$html .= '<br style="clear: both">';
			$html .= '<b>Destino: </b>' . $tax_ride['destination'];
			$html .= '<br style="clear: both">'; 
			$html .= '<br style="clear: both">'; 
			$html .= '<div><h3>Roteiro:</h3></div>';
			$html .= '<table border="1" style="border-collapse: collapse">';
			$html .= '<tr>';
			$html .= '<th><center>Passageiros</center></th>';
			$html .= '<th><center>Assinatura</center></th>';
			$html .= '</tr>';
			foreach ($tax_requests as $request) {
				$html .= '<tr>';
				$html .= '	<td width="450px">';
				$html .= '		<div style="font-size: 14px">';
				$html .= '			<div>Passageiro: ' . $request['req_var_passenger'] . '</div>';
				$html .= '			<div>Endereço: ' . $request['req_var_address'] . ', ' . $request['dis_var_name'] . ', ' . $request['cit_var_name'] .' - ' . $request['cit_cha_uf'] . '</div>';
				$html .= '			<div><div style="float: left">Zona: ' . $request['zon_var_name'] . '</div>  <div style="float: right; width: 200px; text-align: right">Valor: ' . GF::numberFormat($request['req_dec_value']) . '</div></div>';
				$html .= '		</div>';
				$html .= '	</td>';
				$html .= '	<td>';
				$html .= '		<div>';
				$html .= '			<br style="clear: both">';
				$html .= '			<div style="width: 200px:">_______________________________________________</div>';
				$html .= '			<center>' . $request['req_var_passenger'] . '</center>';
				$html .= '		</div>';
				$html .= '	</td>';
				$html .= '</tr>';
			}
			$html .= '</table>';
			$html .= '<br style="clear: both">';
			$html .= '<br style="clear: both">';
			$html .= '<div style="font-size: 12px">';
			$html .= '<div><b>Valor da corrida: ' . GF::numberFormat($tax_ride['rid_dec_value']) . '</b></div>';
			$html .= '<div><b>Adicionais:</b>';
			$html .= '<div>Estacionamento: ' . GF::numberFormat($tax_ride['rid_dec_parking']) . '</div>';
			$html .= '<div>Horas paradas: ' . GF::numberFormat($tax_ride['rid_dec_stopped']);
			(empty($tax_ride['rid_hor_stopped'])) ? $html .= ' (0)</div>' : $html .= ' (' . $tax_ride['rid_hor_stopped'] . ')</div>';
			$html .= '<div>Extremo: ' . GF::numberFormat($tax_ride['rid_dec_extreme']);
			$html .= '<div>Deslocamento: ' . GF::numberFormat($tax_ride['rid_dec_transfer']) . '</div>';
			$html .= '</div>';
			$html .= '<h3>Valor total: ' . GF::numberFormat($tax_ride['rid_dec_total']) . '</h3>';
			$html .= '</div>';
			$mpdf = new mPDF('c', 'A4', '', '', 10, 10, 32, 20, 10, 10);
			$mpdf->SetDisplayMode('fullpage');
			$mpdf->list_indent_first_level = 0;
			// $mpdf->charset_in = 'UTF-8';

			$mpdf->SetTitle("Imprimir Voucher - Corrida " . $rid_int_id);

			$header = '<img src="' . URL_SYS_THEME . 'img/logo-continental.png" style="float: left; margin-top: 15px">';
			$header .= '<div style="float: right; font-size: 24px; font-weight: bold; width: 350px; margin-right: 200px; text-align: center; line-height: 1.25;"> Voucher <br>Corrida ' . $rid_int_id . '</div>';

			$mpdf->SetHTMLHeader($header);
			$mpdf->SetHTMLFooter('<div style="text-align: center; border-top: 1px solid #000000;">Emitido em '. date("d/m/Y H:i:s") . '</div>');

			$stylesheet = file_get_contents(ROOT_SYS_THEME . '_css/relatorio.css');

			$mpdf->WriteHTML($stylesheet, 1);
			$mpdf->WriteHTML($html, 2);
			$mpdf->Output('Voucher - Corrida ' . $rid_int_id . '.pdf', 'I');
		} else {
			$html = '<i class="nenhumResultado">Não há corridas que correspondam a esse código</i>';
			echo $html;
		}
	} catch (Exception $e) {

	}
} else {
	$html = '<html><head><meta charset="utf-8"></head><body><i class="nenhumResultado">Não há corridas que correspondam a esse código</i></body>';
	echo $html;
}
