<?php
require_once(ROOT_SYS_CLASS . "taxDistrict.php");

GF::importClass(array("taxDistrict","taxZone","taxCity"));

class TaxDistrictDao {
    /** @param TaxDistrict $taxDistrict */
    public function selectByIdForm($taxDistrict) {
        $ret = array();
        try {
            $mysql = new GDbMysql();
            $mysql->execute("SELECT dis_int_id,zon_int_id,cit_int_id,dis_var_name FROM vw_tax_district WHERE dis_int_id = ? ", array("i", $taxDistrict->getDis_int_id()));
            if ($mysql->fetch()) {
                $ret = $mysql->res;
            }
            $mysql->close();
        } catch (GDbException $e) {
            echo $e->getError();
        }
        return $ret;
    }

    /** @param TaxDistrict $taxDistrict */
    public function insert($taxDistrict) {

        $return = array();
        $param = array("iis",$taxDistrict->getTaxZone()->getZon_int_id(),$taxDistrict->getTaxCity()->getCit_int_id(),$taxDistrict->getDis_var_name());
        try{
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_tax_district_ins(?,?,?, @p_status, @p_msg, @p_insert_id);", $param, false);
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

    /** @param TaxDistrict $taxDistrict */
    public function update($taxDistrict) {

        $return = array();
        $param = array("iiis",$taxDistrict->getDis_int_id(),$taxDistrict->getTaxZone()->getZon_int_id(),$taxDistrict->getTaxCity()->getCit_int_id(),$taxDistrict->getDis_var_name());
        try{
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_tax_district_upd(?,?,?,?, @p_status, @p_msg);", $param, false);
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

    /** @param TaxDistrict $taxDistrict */
    public function delete($taxDistrict) {

        $return = array();
        $param = array("i",$taxDistrict->getDis_int_id());
        try {
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_tax_district_del(?, @p_status, @p_msg);", $param, false);
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