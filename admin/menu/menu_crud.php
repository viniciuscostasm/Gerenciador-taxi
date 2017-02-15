<?php

require_once("../../_inc/global.php");
GF::importClass(array("menu"));

$menuFather = new Menu();
$menuFather->setMen_int_id($_POST["men_int_idfather"]);

$menu = new Menu();
$menu->setMen_int_id($_POST["men_int_id"]);
$menu->setMen_var_name($_POST["men_var_name"]);
$menu->setMen_cha_status($_POST["men_cha_status"]);
$menu->setMen_var_url($_POST["men_var_url"]);
$menu->setMen_var_icon($_POST["men_var_icon"]);
$menu->setMen_var_class($_POST["men_var_class"]);
$menu->setMenuFather($menuFather);
$menu->setMen_cha_type($_POST["men_cha_type"]);
$menu->setMen_int_order($_POST["men_int_order"]);

$menuDao = new MenuDao();

switch ($_POST["acao"]) {
    case "ins":
        echo json_encode($menuDao->insert($menu));
        break;
    case "upd":
        echo json_encode($menuDao->update($menu));
        break;
    case "del":
        echo json_encode($menuDao->delete($menu));
        break;
    case "sel":
        echo json_encode($menuDao->selectByIdForm($menu));
        break;
    case "combo":
        $where = "";
        try {
            $mysql = new GDbMysql();
            $men_int_id = $_POST["men_int_id"];

            if (!empty($men_int_id)) {
                $query = "SELECT men_var_key 
                            FROM vw_adm_menu 
                           WHERE men_int_id = ?";
                $param = array('i', $men_int_id);
                $mysql->execute($query, $param);

                if ($mysql->fetch()) {
                    $men_var_key = $mysql->res['men_var_key'];
                    if (!empty($men_var_key)) {
                        $where = " WHERE men_var_key NOT LIKE '" . $men_var_key . "%' ";
                    }
                }
            }

            echo getComboMenu('men_int_idfather', 'Father menu ', array('class' => 'm-wrap span6'), true, $where);
        } catch (GDbException $exc) {
            echo $exc->getError();
        }

        break;
    default:
        echo json_encode(array("status" => false, "msg" => "Invalid action"));
        break;
}
?>
