<?php

require_once(ROOT_SYS_CLASS . "menu.php");

GF::importClass(array("menu"));

class MenuDao {

    /** @param Menu $menu */
    public function selectByIdForm($menu) {
        $ret = array();
        try {
            $mysql = new GDbMysql();
            $mysql->execute("SELECT men_int_id,men_var_name,men_cha_status,
                                    men_var_url,men_var_icon,men_var_class,
                                    men_int_idfather,men_cha_type,men_int_order
                               FROM vw_adm_menu
                              WHERE men_int_id = ? ", array("i", $menu->getMen_int_id()));
            if ($mysql->fetch()) {
                $ret = $mysql->res;
            }
            $mysql->close();
        } catch (GDbException $e) {
            echo $e->getError();
        }
        return $ret;
    }

    /** @param Menu $menu */
    public function insert($menu) {

        $userSession = GSec::getUserSession();
        $return = array();
        $param = array("isssssisi",
            $userSession->getUsr_int_id(),
            $menu->getMen_var_name(),
            $menu->getMen_cha_status(),
            $menu->getMen_var_url(),
            $menu->getMen_var_icon(),
            $menu->getMen_var_class(),
            $menu->getMenuFather()->getMen_int_id(),
            $menu->getMen_cha_type(),
            $menu->getMen_int_order()
        );
//        var_dump($param);
        try {
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_adm_menu_ins(?,?,?,?,?,?,?,?,?);", $param);
            $mysql->fetch();
            $return["status"] = ($mysql->res[0]) ? true : false;
            $return["msg"] = $mysql->res[1];
            $return["insertId"] = $mysql->res[2];
            $mysql->close();
        } catch (GDbException $e) {
            $return["status"] = false;
            $return["msg"] = $e->getError();
        }
        return $return;
    }

    /** @param Menu $menu */
    public function update($menu) {

        $userSession = GSec::getUserSession();
        $return = array();

        $param = array("iisssssisi",
            $userSession->getUsr_int_id(),
            $menu->getMen_int_id(),
            $menu->getMen_var_name(),
            $menu->getMen_cha_status(),
            $menu->getMen_var_url(),
            $menu->getMen_var_icon(),
            $menu->getMen_var_class(),
            $menu->getMenuFather()->getMen_int_id(),
            $menu->getMen_cha_type(),
            $menu->getMen_int_order()
        );

        try {
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_adm_menu_upd(?,?,?,?,?,?,?,?,?,?);", $param);
            $mysql->fetch();
            $return["status"] = ($mysql->res[0]) ? true : false;
            $return["msg"] = $mysql->res[1];
            $return["affectedRows"] = $mysql->res[2];
            $mysql->close();
        } catch (GDbException $e) {
            $return["status"] = false;
            $return["msg"] = $e->getError();
        }
        return $return;
    }

    /** @param Menu $menu */
    public function delete($menu) {

        $userSession = GSec::getUserSession();
        $return = array();
        $param = array("ii", $userSession->getUsr_int_id(), $menu->getMen_int_id());
        try {
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_adm_menu_del(?,?,@p_status,@p_msg);", $param, false);
            $mysql->execute('SELECT @p_status,@p_msg');
            $mysql->fetch();
            $return["status"] = ($mysql->res[0]) ? true : false;
            $return["msg"] = $mysql->res[1];
            $mysql->close();
        } catch (GDbException $e) {
            $return["status"] = false;
            $return["msg"] = $e->getError();
        }
        return $return;
    }

}
