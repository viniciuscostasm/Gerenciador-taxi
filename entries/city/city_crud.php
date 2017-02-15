<?php

require_once("../../_inc/global.php");
GF::importClass(array("taxCity"));

$taxCity = new TaxCity();
$taxCity->setCit_int_id($_POST["cit_int_id"]);
$taxCity->setCit_var_name($_POST["cit_var_name"]);
$taxCity->setCit_cha_uf($_POST["cit_cha_uf"]);
$taxCityDao = new TaxCityDao();

switch ($_POST["acao"]) {
    case "ins":
        echo json_encode($taxCityDao->insert($taxCity));
        break;
    case "upd":
        echo json_encode($taxCityDao->update($taxCity));
        break;
    case "del":
        echo json_encode($taxCityDao->delete($taxCity));
        break;
    case "sel":
        echo json_encode($taxCityDao->selectByIdForm($taxCity));
        break;
    default:
        echo json_encode(array("status" => false, "msg" => "Invalid action"));
        break;
}
?>
