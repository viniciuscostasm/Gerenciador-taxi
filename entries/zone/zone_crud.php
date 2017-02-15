<?php

require_once("../../_inc/global.php");
GF::importClass(array("taxZone"));

$taxZone = new TaxZone();
$taxZone->setZon_int_id($_POST["zon_int_id"]);
$taxZone->setZon_int_idassociated($_POST["zon_int_idassociated"]);
$taxZone->setZon_var_name($_POST["zon_var_name"]);
$taxZoneDao = new TaxZoneDao();

switch ($_POST["acao"]) {
    case "ins":
        echo json_encode($taxZoneDao->insert($taxZone));
        break;
    case "upd":
        echo json_encode($taxZoneDao->update($taxZone));
        break;
    case "del":
        echo json_encode($taxZoneDao->delete($taxZone));
        break;
    case "sel":
        echo json_encode($taxZoneDao->selectByIdForm($taxZone));
        break;
    default:
        echo json_encode(array("status" => false, "msg" => "Invalid action"));
        break;
}
?>
