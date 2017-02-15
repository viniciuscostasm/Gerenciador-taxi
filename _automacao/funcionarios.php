<?php 

require_once("../_inc/global.php");
GF::importClass(array("taxEmployee"));

$funcionarios = array_map('str_getcsv', file('funcionario.csv'));
array_shift($funcionarios);

$taxEmployees = array();

foreach ($funcionarios as $funcionario) {
	$taxCostcenter = new TaxCostcenter();
	$taxCostcenter->setCoc_var_key($funcionario[6]);

	$taxEmployee = new TaxEmployee();
	$taxEmployee->setEmp_var_key($funcionario[0]);
	$taxEmployee->setEmp_var_name($funcionario[1]);
	$taxEmployee->setEmp_var_address($funcionario[2]);
	$taxEmployee->setEmp_var_district($funcionario[3]);
	$taxEmployee->setEmp_var_city($funcionario[4]);
	$taxEmployee->setEmp_var_cep($funcionario[5]);
	$taxEmployee->setTaxCostcenter($taxCostcenter);

	$taxEmployees[] = $taxEmployee;
}

$taxEmployeeDao = new TaxEmployeeDao();

foreach ($taxEmployees as $taxEmployee) {
	echo json_encode($taxEmployeeDao->insert($taxEmployee));
}

?>