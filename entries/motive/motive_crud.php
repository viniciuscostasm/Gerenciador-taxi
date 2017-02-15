<?php
require_once("../../_inc/global.php");
GF::importClass(array("taxMotive"));


$taxMotive = new TaxMotive();
$taxMotive->setMot_int_id($_POST["mot_int_id"]);
$taxMotive->setMot_var_name($_POST["mot_var_name"]);

$taxMotiveDao = new TaxMotiveDao();

switch ($_POST["acao"]) {
    case "ins":
        echo json_encode($taxMotiveDao->insert($taxMotive));
        break;
    case "upd":
        echo json_encode($taxMotiveDao->update($taxMotive));
        break;
    case "del":
        echo json_encode($taxMotiveDao->delete($taxMotive));
        break;
    case "sel":
        echo json_encode($taxMotiveDao->selectByIdForm($taxMotive));
        break;
    default:
        echo json_encode(array("status" => false, "msg" => "Invalid action"));
        break;
}