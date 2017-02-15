<?php

require_once(ROOT_SYS_CLASS . "resourceProfile.php");

GF::importClass(array("resourceProfile", "resource", "profile"));

class ResourceProfileDao {

    /** @param ResourceProfile $resourceProfile */
    public function selectByIdForm($resourceProfile) {
        $ret = array();
        try {
            $mysql = new GDbMysql();
            $mysql->execute("SELECT rpr_int_id,res_int_id,pro_int_id FROM vw_adm_resource_profile WHERE rpr_int_id = ? ", array("i", $resourceProfile->getRpr_int_id()));
            if ($mysql->fetch()) {
                $ret = $mysql->res;
            }
            $mysql->close();
        } catch (GDbException $e) {
            echo $e->getError();
        }
        return $ret;
    }

    /** @param ResourceProfile $resourceProfile */
    public function insert($resourceProfile) {

        $userSession = GSec::getUserSession();
        $return = array();
        $param = array("iii", $userSession->getUsr_int_id(), $resourceProfile->getResource()->getRes_int_id(), $resourceProfile->getProfile()->getPro_int_id());
        try {
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_adm_resource_profile_ins(?,?,?);", $param);
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

    /** @param ResourceProfile $resourceProfile */
    public function update($resourceProfile) {

        $userSession = GSec::getUserSession();
        $return = array();
        $param = array("iiii", $userSession->getUsr_int_id(), $resourceProfile->getRpr_int_id(), $resourceProfile->getResource()->getRes_int_id(), $resourceProfile->getProfile()->getPro_int_id());
        try {
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_adm_resource_profile_upd(?,?,?,?);", $param);
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

    /** @param ResourceProfile $resourceProfile */
    public function delete($resourceProfile) {

        $userSession = GSec::getUserSession();
        $return = array();
        $param = array("ii", $userSession->getUsr_int_id(), $resourceProfile->getRpr_int_id());
        try {
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_adm_resource_profile_del(?,?);", $param);
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

}