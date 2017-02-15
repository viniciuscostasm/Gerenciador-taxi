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

$rid_dat_arrival = GF::convertDate($_POST['rid_daf_arrival']);


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
$taxRide->setRid_dec_value($_POST["rid_dec_value"]);
// $taxRide->setUserAdd($userAdd);
// $taxRide->setUserArrival($userArrival);
$taxRide->setRid_var_plate($_POST["rid_var_plate"]);
$taxRide->setRid_txt_comment($_POST["rid_txt_comment"]);
$taxRide->setRid_var_driver($_POST["rid_var_driver"]);
$taxRide->setRid_hou_arrival($_POST["rid_hou_arrival"]);
// $taxRide->setReq_int_id($_POST['req_int_id']);
$taxRide->setRid_dat_arrival($rid_dat_arrival);
$taxRide->setRid_hou_arrival($_POST['rid_hou_arrival']);
$taxRide->setRid_hor_stopped($_POST['rid_hor_stopped']);
$taxRide->setRid_dec_parking(GF::numberUnformat($_POST['rid_dec_parking']));
$taxRide->setRid_cha_transfer(($_POST['rid_cha_transfer'] == 'Y') ? 'Y' : 'N');
$taxRide->setRid_var_driver($_POST['rid_var_driver']);
$taxRide->setRid_var_plate($_POST['rid_var_plate']);
$taxRide->setRid_txt_comment($_POST['rid_txt_comment']);

$req_int_id = $_POST['req_int_id'];
$req_txt_comment = $_POST['req_txt_comment'];
$req_cha_absent = ($_POST['req_cha_absent'] == 'Y') ? 'Y' : 'N';

$taxRideDao = new TaxRideDao();

switch ($_POST["acao"]) {
    case "sel":
        echo json_encode($taxRideDao->selectByIdForm($taxRide));
        break;
    case "close":
        echo json_encode($taxRideDao->close($taxRide));
        break;
    case "rideRequests":
        try {
            $mysql = new GDbMysql();
            $form = new GForm();
            $rid_int_id = $_POST['rid_int_id'];
            $query = "SELECT req_int_id, req_var_passenger,
                             IFNULL(fn_tax_requestsourcedestination(req_int_id, 'S'), 'Continental') AS source,
                             IFNULL(fn_tax_requestsourcedestination(req_int_id, 'D'), 'Continental') AS destination,
                             coc_var_name,req_txt_comment
                        FROM vw_tax_request
                       WHERE rid_int_id = ? ORDER BY rid_int_order ASC";
            $param = array('i', $rid_int_id);
            $mysql->execute($query, $param);

            if ($mysql->numRows() > 0) {
                $html .= '<table class="table table-striped table-hover requests-table">';
                $html .= '<thead>';
                $html .= '<tr>';
                $html .= '<th>Nome</th>';
                $html .= '<th>Centro de Custo</th>';
                $html .= '<th>Origem</th>';
                $html .= '<th>Destino</th>';
                $html .= '<th>Observações</th>';
                $html .= '<th>Ação</th>';
                $html .= '</tr>';
                $html .= '</thead>';
                $html .= '<tbody>';
                while ($mysql->fetch()) {
                    $html .= '<tr id="' . $mysql->res['req_int_id'] . '">';
                    $html .= '<td class="name">' . $mysql->res['req_var_passenger'] . '</td>';
                    $html .= '<td class="coc">' . $mysql->res['coc_var_name'] . '</td>';
                    $html .= '<td>' . $mysql->res['source'] . '</td>';
                    $html .= '<td>' . $mysql->res['destination'] . '</td>';
                    $html .= '<td class="comment">' . $mysql->res['req_txt_comment'] . '</td>';
                    $html .= '<td>' . $form->addButton('btn_commentrequest', '<i class="fa fa-pencil"></i>', array('class' => 'btn hidden-phone btn_commentrequest')) . '</td>';
                    $html .= '</tr>';
                }
                $html .= '</tbody>';
                $html .= '</table>';
            } else {
                $html .= '<div class="nenhumResultado">Nenhum resultado encontrado.</div>';
            }

            echo $html;
        } catch (Exception $e) {
            
        }
        break;
    case "commentRequest":
        echo json_encode($taxRideDao->commentRequest($req_int_id, $req_txt_comment, $req_cha_absent));
        break;
    default:
        echo json_encode(array("status" => false, "msg" => "Invalid action"));
        break;
}