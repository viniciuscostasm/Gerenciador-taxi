<?php
require_once("../../_inc/global.php");
GF::importClass(array("taxEmployee","taxCostcenter","taxCity","taxDistrict"));

$taxCostcenter = new TaxCostcenter();
$taxCostcenter->setCoc_int_id($_POST["coc_int_id"]);
$taxCity = new TaxCity();
$taxCity->setCit_int_id($_POST["cit_int_id"]);
$taxDistrict = new TaxDistrict();
$taxDistrict->setDis_int_id($_POST["dis_int_id"]);

$taxEmployee = new TaxEmployee();
$taxEmployee->setEmp_int_id($_POST["emp_int_id"]);
$taxEmployee->setTaxCostcenter($taxCostcenter);
$taxEmployee->setEmp_var_key($_POST["emp_var_key"]);
$taxEmployee->setEmp_var_name($_POST["emp_var_name"]);
$taxEmployee->setEmp_var_address($_POST["emp_var_address"]);
$taxEmployee->setEmp_var_cep($_POST["emp_var_cep"]);
$taxEmployee->setTaxCity($taxCity);
$taxEmployee->setTaxDistrict($taxDistrict);

$taxEmployeeDao = new TaxEmployeeDao();

switch ($_POST["acao"]) {
    case "ins":
        echo json_encode($taxEmployeeDao->insert($taxEmployee));
        break;
    case "upd":
        echo json_encode($taxEmployeeDao->update($taxEmployee));
        break;
    case "del":
        echo json_encode($taxEmployeeDao->delete($taxEmployee));
        break;
    case "sel":
        echo json_encode($taxEmployeeDao->selectByIdForm($taxEmployee));
        break;
    default:
        echo json_encode(array("status" => false, "msg" => "Invalid action"));
        break;
}