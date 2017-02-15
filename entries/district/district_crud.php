<?php
require_once("../../_inc/global.php");
GF::importClass(array("taxDistrict","taxZone","taxCity"));

$taxZone = new TaxZone();
$taxZone->setZon_int_id($_POST["zon_int_id"]);
$taxCity = new TaxCity();
$taxCity->setCit_int_id($_POST["cit_int_id"]);

$taxDistrict = new TaxDistrict();
$taxDistrict->setDis_int_id($_POST["dis_int_id"]);
$taxDistrict->setTaxZone($taxZone);
$taxDistrict->setTaxCity($taxCity);
$taxDistrict->setDis_var_name($_POST["dis_var_name"]);

$taxDistrictDao = new TaxDistrictDao();

switch ($_POST["acao"]) {
    case "ins":
        echo json_encode($taxDistrictDao->insert($taxDistrict));
        break;
    case "upd":
        echo json_encode($taxDistrictDao->update($taxDistrict));
        break;
    case "del":
        echo json_encode($taxDistrictDao->delete($taxDistrict));
        break;
    case "sel":
        echo json_encode($taxDistrictDao->selectByIdForm($taxDistrict));
        break;
    default:
        echo json_encode(array("status" => false, "msg" => "Invalid action"));
        break;
}