<?php
require_once("../../_inc/global.php");
GF::importClass(array("taxRequest","user","taxEmployee","taxCostcenter","taxMotive","taxCity","taxDistrict","taxZone","taxCity","taxDistrict","taxZone","taxRide"));

$user = GSec::getUserSession();

$taxEmployee = new TaxEmployee();
$taxEmployee->setEmp_int_id($_POST["emp_int_id"]);
$taxCostCenter = new TaxCostCenter();
$taxCostCenter->setCoc_int_id($_POST["coc_int_id"]);
$taxMotive = new TaxMotive();
$taxMotive->setMot_int_id($_POST["mot_int_id"]);

$taxCitySource = new TaxCity();
$taxCitySource->setCit_int_id($_POST["cit_int_idsource"]);
$taxDistrictSource = new TaxDistrict();
$taxDistrictSource->setDis_int_id($_POST["dis_int_idsource"]);
$taxZoneSource = new TaxZone();
$taxZoneSource->setZon_int_id($_POST["zon_int_idsource"]);

$taxCityDestination = new TaxCity();
$taxCityDestination->setCit_int_id($_POST["cit_int_iddestination"]);
$taxDistrictDestination = new TaxDistrict();
$taxDistrictDestination->setDis_int_id($_POST["dis_int_iddestination"]);
$taxZoneDestination = new TaxZone();
$taxZoneDestination->setZon_int_id($_POST["zon_int_iddestination"]);

$taxRide = new TaxRide();
$taxRide->setRid_int_id($_POST["rid_int_id"]);

$taxRequest = new TaxRequest();
$taxRequest->setReq_int_id($_POST["req_int_id"]);
$taxRequest->setUser($user);
$taxRequest->setTaxEmployee($taxEmployee);
$taxRequest->setReq_var_passenger($_POST["req_var_passenger"]);
$taxRequest->setReq_cha_type($_POST["req_cha_type"]);
$taxRequest->setTaxCostCenter($taxCostCenter);
$taxRequest->setReq_dat_date(GF::convertDate($_POST["req_dat_date"]));
$taxRequest->setReq_var_hour($_POST["req_var_hour"]);
$taxRequest->setTaxMotive($taxMotive);

$taxRequest->setReq_var_addresssource($_POST["req_var_addresssource"]);
$taxRequest->setTaxCitySource($taxCitySource);
$taxRequest->setTaxDistrictSource($taxDistrictSource);
$taxRequest->setTaxZoneSource($taxZoneSource);

$taxRequest->setReq_var_addressdestination($_POST["req_var_addressdestination"]);
$taxRequest->setTaxCityDestination($taxCityDestination);
$taxRequest->setTaxDistrictDestination($taxDistrictDestination);
$taxRequest->setTaxZoneDestination($taxZoneDestination);

$taxRequest->setReq_txt_comment($_POST["req_txt_comment"]);
$taxRequest->setTaxRide($taxRide);
$taxRequest->setReq_dec_value($_POST["req_dec_value"]);

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
    case "comboDistrictSource":
        $html = '';
        try {
            $form = new GForm();
            $mysql = new GDbMysql();

            $cit_int_idsource = $_POST['cit_int_idsource'];
            $dis_int_idsource = $_POST['dis_int_idsource'];
            $edit = $_POST['edit'];

            $opt_dis_int_id = $mysql->executeCombo("SELECT dis_int_id, dis_var_name FROM vw_tax_district WHERE cit_int_id = ? ORDER BY dis_var_name;", array('i', $cit_int_idsource));

            $selecione = (empty($cit_int_idsource)) ? 'Selecione a cidade...' : 'Selecione...';
            $validate = (empty($cit_int_idsource)) ? array() : array('validate' => 'required');
            $disabled = ($edit) ? array('disabled' => 'disabled'): array();
            $html .= $form->addSelect('dis_int_idsource', $opt_dis_int_id , $dis_int_idsource, 'Bairro*', array('class' => 'combobox') + $validate + $disabled, false, false, true, '', $selecione, true, false);
        } catch (GDbException $e) {
            $html .= $e->getError();
        }
        echo $html;
        break;
    case "comboCoc":
        $html = '';
        try {
            $form = new GForm();
            $mysql = new GDbMysql();
            $emp_int_id = $_POST['emp_int_id'];
            
            $opt_coc_int_id = $mysql->executeCombo("SELECT coc_int_id, coc_var_name FROM vw_tax_employee WHERE emp_int_id = ?" , array('i', $emp_int_id));


            $html .= $form->addSelect('coc_int_id', $opt_coc_int_id , $opt_coc_int_id['coc_int_id'], 'Centro de Custo*', array('class' => 'combobox', 'validate' => 'required'), false, false, false, false, false, true, false);
        } catch (GDbException $e) {
            $html .= $e->getError();
        }
        echo $html;
        break;
    case "comboDistrictDestination":
        $html = '';
        try {
            $form = new GForm();
            $mysql = new GDbMysql();

            $cit_int_iddestination = $_POST['cit_int_iddestination'];
            $dis_int_iddestination = $_POST['dis_int_iddestination'];

            $edit = $_POST['edit'];

            $opt_dis_int_id = $mysql->executeCombo("SELECT dis_int_id, dis_var_name FROM vw_tax_district WHERE cit_int_id = ? ORDER BY dis_var_name;", array('i', $cit_int_iddestination));
            
            $selecione = (empty($cit_int_iddestination)) ? 'Selecione a cidade...' : 'Selecione...';
            $validate = (empty($cit_int_iddestination)) ? array() : array('validate' => 'required');
            $disabled = ($edit) ? array('disabled' => 'disabled'): array();
            $html .= $form->addSelect('dis_int_iddestination', $opt_dis_int_id , $dis_int_iddestination, 'Bairro*', array('class' => 'combobox') + $validate + $disabled, false, false, true, '', $selecione, true, false);
        } catch (GDbException $e) {
            $html .= $e->getError();
        }
        echo $html;
        break;
    default:
        echo json_encode(array("status" => false, "msg" => "Invalid action"));
        break;
}
