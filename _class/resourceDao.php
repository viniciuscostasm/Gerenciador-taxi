<?php

require_once(ROOT_SYS_CLASS . "resource.php");

GF::importClass(array("resource", "menu"));

class ResourceDao {

    /** @param Resource $resource */
    public function selectByIdForm($resource) {
        $ret = array();
        try {
            $mysql = new GDbMysql();
            $mysql->execute("SELECT res_int_id,men_int_id,res_var_key,res_var_name,res_cha_type,res_var_path,res_txt_parameters FROM vw_adm_resource WHERE res_int_id = ? ", array("i", $resource->getRes_int_id()));
            if ($mysql->fetch()) {
                $ret = $mysql->res;
            }
            $mysql->close();
        } catch (GDbException $e) {
            echo $e->getError();
        }
        return $ret;
    }

    /** @param Resource $resource */
    public function insert($resource) {

        $userSession = GSec::getUserSession();
        $return = array();
        $param = array("iisssss", $userSession->getUsr_int_id(), $resource->getMenu()->getMen_int_id(), $resource->getRes_var_key(), $resource->getRes_var_name(), $resource->getRes_cha_type(), $resource->getRes_var_path(), $resource->getRes_txt_parameters());
        try {
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_adm_resource_ins(?,?,?,?,?,?,?);", $param);
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

    /** @param Resource $resource */
    public function update($resource) {

        $userSession = GSec::getUserSession();
        $return = array();
        $param = array("iiisssss", $userSession->getUsr_int_id(), $resource->getRes_int_id(), $resource->getMenu()->getMen_int_id(), $resource->getRes_var_key(), $resource->getRes_var_name(), $resource->getRes_cha_type(), $resource->getRes_var_path(), $resource->getRes_txt_parameters());
        try {
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_adm_resource_upd(?,?,?,?,?,?,?,?);", $param);
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

    /** @param Resource $resource */
    public function delete($resource) {

        $userSession = GSec::getUserSession();
        $return = array();
        $param = array("ii", $userSession->getUsr_int_id(), $resource->getRes_int_id());
        try {
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_adm_resource_del(?,?);", $param);
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