<?php
require_once("../../_inc/global.php");
GF::importClass(array("taxTaxicompany"));
$mysql = new GDbMysql();


$taxTaxicompany = new TaxTaxicompany();
$taxTaxicompany->setTxc_int_id($_POST["txc_int_id"]);
$taxTaxicompany->setTxc_var_name($_POST["txc_var_name"]);
$taxTaxicompany->setTxc_dec_valuestopped(GF::numberUnformat($_POST["txc_dec_valuestopped"]));
$taxTaxicompany->setTxc_dec_valueextreme(GF::numberUnformat($_POST["txc_dec_valueextreme"]));
$taxTaxicompany->setTxc_dec_valuetransfer(GF::numberUnformat($_POST["txc_dec_valuetransfer"]));

$taxTaxicompanyDao = new TaxTaxicompanyDao();
$zon_int_idlist = $_POST['zon_int_idlist'];
$tzo_dec_valuelist = $_POST['tzo_dec_valuelist'];
$cit_int_idsourcelist = $_POST['cit_int_idsourcelist'];
$cit_int_iddestinationlist = $_POST['cit_int_iddestinationlist'];
$txi_dec_valuelist = $_POST['txi_dec_valuelist'];

switch ($_POST["acao"]) {
    case "ins":
        echo json_encode($taxTaxicompanyDao->insert($taxTaxicompany, $zon_int_idlist, $tzo_dec_valuelist, $cit_int_idsourcelist , $cit_int_iddestinationlist , $txi_dec_valuelist));
        break;
    case "upd":
        echo json_encode($taxTaxicompanyDao->update($taxTaxicompany, $zon_int_idlist, $tzo_dec_valuelist, $cit_int_idsourcelist , $cit_int_iddestinationlist , $txi_dec_valuelist));
        break;
    case "del":
        echo json_encode($taxTaxicompanyDao->delete($taxTaxicompany));
        break;
    case "sel":
        $ret = $taxTaxicompanyDao->selectByIdForm($taxTaxicompany);
        echo json_encode($ret);
        break;
    default:
        echo json_encode(array("status" => false, "msg" => "Invalid action"));
        break;
}