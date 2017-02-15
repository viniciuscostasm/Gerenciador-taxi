<?php
require_once("../_inc/global.php");
GF::importClass(array("taxCostcenter"));

$centers = array_map('str_getcsv', file('centrocusto.csv'));
array_shift($centers);

$taxCostCenters = array();

foreach ($centers as $center) {
	$taxCenter = new TaxCostcenter();
	$taxCenter->setCoc_var_key($center[0]);
	$taxCenter->setCoc_var_name($center[1]);

	$taxCostCenters[] = $taxCenter;
}

$taxCostcenterDao = new TaxCostcenterDao();

foreach ($taxCostCenters as $taxCenter) {
	echo json_encode($taxCostcenterDao->insert($taxCenter));
}
