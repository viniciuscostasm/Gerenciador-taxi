<?php
require_once(ROOT_SYS_CLASS . "country.php");

GF::importClass(array("country"));

class CountryDao {
    /** @param Country $country */
    public function selectByIdForm($country) {
        $ret = array();
        try {
            $mysql = new GDbMysql();
            $mysql->execute("SELECT cou_cha_country,cou_var_name FROM vw_adm_country WHERE cou_cha_country = ? ", array("s", $country->getCou_cha_country()));
            if ($mysql->fetch()) {
                $ret = $mysql->res;
            }
            $mysql->close();
        } catch (GDbException $e) {
            echo $e->getError();
        }
        return $ret;
    }

    /** @param Country $country */
    public function insert($country) {

        $return = array();
        $param = array("s",$country->getCou_var_name());
        try{
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_adm_country_ins(?, @p_status, @p_msg, @p_insert_id);", $param, false);
            $mysql->execute("SELECT @p_status, @p_msg, @p_insert_id");
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

    /** @param Country $country */
    public function update($country) {

        $return = array();
        $param = array("ss",$country->getCou_cha_country(),$country->getCou_var_name());
        try{
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_adm_country_upd(?,?, @p_status, @p_msg);", $param, false);
            $mysql->execute("SELECT @p_status, @p_msg");
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

    /** @param Country $country */
    public function delete($country) {

        $return = array();
        $param = array("s",$country->getCou_cha_country());
        try {
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_adm_country_del(?, @p_status, @p_msg);", $param, false);
            $mysql->execute("SELECT @p_status, @p_msg");
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