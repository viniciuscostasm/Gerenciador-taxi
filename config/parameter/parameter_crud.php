<?php
require_once("../../_inc/global.php");
GF::importClass(array("repParameter"));


$repParameter = new RepParameter();
$repParameter->setPar_int_limitaccept($_POST["par_int_limitaccept"]);
$repParameter->setPar_int_limitanswer($_POST["par_int_limitanswer"]);
$repParameter->setPar_int_limitapprove($_POST["par_int_limitapprove"]);
$repParameter->setPar_int_limittotal($_POST["par_int_limittotal"]);

$par_cha_hideproblemdescription = ($_POST["par_cha_hideproblemdescription"] == 'Y') ? 'Y' : 'N';
$par_cha_hideimmediatemeasure = ($_POST["par_cha_hideimmediatemeasure"] == 'Y') ? 'Y' : 'N';
$par_cha_hidediscussion = ($_POST["par_cha_hidediscussion"] == 'Y') ? 'Y' : 'N';

$repParameter->setPar_cha_hideproblemdescription($par_cha_hideproblemdescription);
$repParameter->setPar_cha_hideimmediatemeasure($par_cha_hideimmediatemeasure);
$repParameter->setPar_cha_hidediscussion($par_cha_hidediscussion);

$repParameterDao = new RepParameterDao();

switch ($_POST["acao"]) {
    case "upd":
        echo json_encode($repParameterDao->update($repParameter));
        break;
    default:
        echo json_encode(array("status" => false, "msg" => "Invalid action"));
        break;
}