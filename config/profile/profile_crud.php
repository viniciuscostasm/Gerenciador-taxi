<?php

require_once("../../_inc/global.php");
GF::importClass(array("profile"));

$profile = new Profile();
$profile->setPro_int_id($_POST["pro_int_id"]);
$profile->setPro_var_name($_POST["pro_var_name"]);
$profile->setPro_dti_add($_POST["pro_dti_add"]);

$men_int_idlist = $_POST['men_int_idlist'];
$rec_int_idlist = $_POST['rec_int_idlist'];

$profileDao = new ProfileDao();

switch ($_POST["acao"]) {
    case "ins":
        echo json_encode($profileDao->insert($profile, $men_int_idlist, $rec_int_idlist));
        break;
    case "upd":
        echo json_encode($profileDao->update($profile, $men_int_idlist, $rec_int_idlist));
        break;
    case "del":
        echo json_encode($profileDao->delete($profile));
        break;
    case "sel":
        echo json_encode($profileDao->selectByIdForm($profile));
        break;
    default:
        echo json_encode(array("status" => false, "msg" => "Invalid action"));
        break;
}
?>
