<?php
require_once("../../_inc/global.php");
GF::importClass(array("taxRide","taxZone","taxTaxicompany","user","user"));

$taxTaxicompany = new TaxTaxicompany();
$taxTaxicompany->setTxc_int_id($_POST["txc_int_id"]);

$taxZone = new TaxZone();
$taxZone->setZon_int_id($_POST["zon_int_id"]);

$userAdd = GSec::getUserSession();

$userArrival = new User();
$userArrival->setUsr_int_id($_POST["usr_int_idarrival"]);

$taxRide = new TaxRide();
$taxRide->setRid_int_id($_POST["rid_int_id"]);
// $taxRide->setTco_cha_type($_POST["tco_cha_type"]);
$taxRide->setRid_dat_date($_POST["rid_dat_date"]);
$taxRide->setRid_hou_hour($_POST["rid_hou_hour"]);
$taxRide->setTaxTaxicompany($taxTaxicompany);
$taxRide->setRid_cha_status($_POST["rid_cha_status"]);
$taxRide->setRid_int_passengers($_POST["rid_int_passengers"]);
// $taxRide->setTaxZone($taxZone);
$taxRide->setZon_int_idlist($_POST["zon_int_idlist"]);
$taxRide->setZon_var_namelist($_POST["zon_var_namelist"]);
$taxRide->setRid_txt_passengerlist($_POST["rid_txt_passengerlist"]);
$taxRide->setRid_hor_stopped($_POST["rid_hor_stopped"]);
$taxRide->setRid_dec_stoppedhour($_POST["rid_dec_stoppedhour"]);
$taxRide->setRid_dec_parking($_POST["rid_dec_parking"]);
$taxRide->setRid_dec_transfer($_POST["rid_dec_transfer"]);
$taxRide->setRid_dec_value($_POST["rid_dec_value"]);
// $taxRide->setUserAdd($userAdd);
// $taxRide->setUserArrival($userArrival);
$taxRide->setRid_var_plate($_POST["rid_var_plate"]);
$taxRide->setRid_txt_comment($_POST["rid_txt_comment"]);
$taxRide->setRid_var_driver($_POST["rid_var_driver"]);
$taxRide->setRid_hou_arrival($_POST["rid_hou_arrival"]);
$req_int_id = $_POST['req_int_id'];

$taxRideDao = new TaxRideDao();

switch ($_POST["acao"]) {
    case "ins":
        echo json_encode($taxRideDao->insert($taxRide));
        break;
    case "upd":
        echo json_encode($taxRideDao->update($taxRide));
        break;
    case "del":
        echo json_encode($taxRideDao->delete($taxRide));
        break;
    case "sel":
        echo json_encode($taxRideDao->selectByIdForm($taxRide));
        break;
    case "order":
        echo json_encode($taxRideDao->order($taxRide, $_POST['action'], $req_int_id));
        break;
    case "new":
        echo json_encode($taxRideDao->newRide($taxRide, $req_int_id));
        break;
    case "reject":
        echo json_encode($taxRideDao->reject($taxRide, $_POST['req_txt_comment'], $req_int_id));
        break;
    case "move":
        echo json_encode($taxRideDao->move($taxRide, $_POST['rid_int_idmove'], $req_int_id));
        break;
    case "approve":
        echo json_encode($taxRideDao->approve($taxRide));
        break;
    case "cancel":
        echo json_encode($taxRideDao->cancel($taxRide));
        break;
    case "taxiCompany":
        echo json_encode($taxRideDao->taxiCompany($taxRide, $_POST['txc_int_id']));
        break;
    case "changeHour":
        echo json_encode($taxRideDao->changeHour($taxRide, $_POST['rid_hou_hour']));
        break;
    default:
        echo json_encode(array("status" => false, "msg" => "Invalid action"));
        break;
}