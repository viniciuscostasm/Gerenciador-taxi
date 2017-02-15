<?php
require_once("../../_inc/global.php");
GF::importClass(array("taxCostcenter"));


$taxCostcenter = new TaxCostcenter();
$taxCostcenter->setCoc_int_id($_POST["coc_int_id"]);
$taxCostcenter->setCoc_var_key($_POST["coc_var_key"]);
$taxCostcenter->setCoc_var_name($_POST["coc_var_name"]);

$taxCostcenterDao = new TaxCostcenterDao();

switch ($_POST["acao"]) {
    case "ins":
        echo json_encode($taxCostcenterDao->insert($taxCostcenter));
        break;
    case "upd":
        echo json_encode($taxCostcenterDao->update($taxCostcenter));
        break;
    case "del":
        echo json_encode($taxCostcenterDao->delete($taxCostcenter));
        break;
    case "sel":
        echo json_encode($taxCostcenterDao->selectByIdForm($taxCostcenter));
        break;
    default:
        echo json_encode(array("status" => false, "msg" => "Invalid action"));
        break;
}