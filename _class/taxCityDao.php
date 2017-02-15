<?php
require_once(ROOT_SYS_CLASS . "taxCity.php");

GF::importClass(array("taxCity"));

class TaxCityDao {
    /** @param TaxCity $taxCity */
    public function selectByIdForm($taxCity) {
        $ret = array();
        try {
            $mysql = new GDbMysql();
            $mysql->execute("SELECT cit_int_id,cit_var_name,cit_cha_uf FROM vw_tax_city WHERE cit_int_id = ? ", array("i", $taxCity->getCit_int_id()));
            if ($mysql->fetch()) {
                $ret = $mysql->res;
            }
            $mysql->close();
        } catch (GDbException $e) {
            echo $e->getError();
        }
        return $ret;
    }

    /** @param TaxCity $taxCity */
    public function insert($taxCity) {

        $return = array();
        $param = array("ss",$taxCity->getCit_var_name(),$taxCity->getCit_cha_uf());
        try{
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_tax_city_ins(?,?, @p_status, @p_msg, @p_insert_id);", $param, false);
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

    /** @param TaxCity $taxCity */
    public function update($taxCity) {

        $return = array();
        $param = array("iss",$taxCity->getCit_int_id(),$taxCity->getCit_var_name(),$taxCity->getCit_cha_uf());
        try{
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_tax_city_upd(?,?,?, @p_status, @p_msg);", $param, false);
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

    /** @param TaxCity $taxCity */
    public function delete($taxCity) {

        $return = array();
        $param = array("i",$taxCity->getCit_int_id());
        try {
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_tax_city_del(?, @p_status, @p_msg);", $param, false);
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