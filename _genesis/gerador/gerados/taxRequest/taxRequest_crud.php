<?php
require_once("../../_inc/global.php");
GF::importClass(array("taxRequest","user","taxEmployee","taxCity","taxDistrict","taxZone","taxMotive"));

$user = new User();
$user->setUsr_int_id($_POST["usr_int_id"]);
$taxEmployee = new TaxEmployee();
$taxEmployee->setEmp_int_id($_POST["emp_int_id"]);
$taxCity = new TaxCity();
$taxCity->setCit_int_id($_POST["cit_int_id"]);
$taxDistrict = new TaxDistrict();
$taxDistrict->setDis_int_id($_POST["dis_int_id"]);
$taxZone = new TaxZone();
$taxZone->setZon_int_id($_POST["zon_int_id"]);
$taxMotive = new TaxMotive();
$taxMotive->setMot_int_id($_POST["mot_int_id"]);

$taxRequest = new TaxRequest();
$taxRequest->setReq_int_id($_POST["req_int_id"]);
$taxRequest->setUser($user);
$taxRequest->setReq_cha_origin($_POST["req_cha_origin"]);
$taxRequest->setReq_dat_date($_POST["req_dat_date"]);
$taxRequest->setReq_var_hour($_POST["req_var_hour"]);
$taxRequest->setTaxEmployee($taxEmployee);
$taxRequest->setReq_var_passenger($_POST["req_var_passenger"]);
$taxRequest->setReq_var_address($_POST["req_var_address"]);
$taxRequest->setTaxCity($taxCity);
$taxRequest->setTaxDistrict($taxDistrict);
$taxRequest->setTaxZone($taxZone);
$taxRequest->setTaxMotive($taxMotive);
$taxRequest->setReq_txt_comment($_POST["req_txt_comment"]);

$taxRequestDao = new TaxRequestDao();

switch ($_POST["acao"]) {
    case "ins":
        echo json_encode($taxRequestDao->insert($taxRequest));
        break;
    case "upd":
        echo json_encode($taxRequestDao->update($taxRequest));
        break;
    case "del":
        echo json_encode($taxRequestDao->delete($taxRequest));
        break;
    case "sel":
        echo json_encode($taxRequestDao->selectByIdForm($taxRequest));
        break;
    default:
        echo json_encode(array("status" => false, "msg" => "Invalid action"));
        break;
}