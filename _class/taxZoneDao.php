<?php
require_once(ROOT_SYS_CLASS . "taxZone.php");

GF::importClass(array("taxZone"));

class TaxZoneDao {
    /** @param TaxZone $taxZone */
    public function selectByIdForm($taxZone) {
        $ret = array();
        try {
            $mysql = new GDbMysql();
            $mysql->execute("SELECT zon_int_id,zon_var_name FROM vw_tax_zone WHERE zon_int_id = ? ", array("i", $taxZone->getZon_int_id()));
            if ($mysql->fetch()) {
                $ret = $mysql->res;
            }
            $mysql->freeResult();
            $mysql->execute("SELECT zon_int_iddestination,zon_var_namedestination FROM vw_tax_zoneassociated WHERE zon_int_idsource = ? ", array("i", $taxZone->getZon_int_id()));
            while ($mysql->fetch()) {
                $ret['zones_associated'][] = array('zon_int_id' => $mysql->res['zon_int_iddestination'], 'zon_var_name' => $mysql->res['zon_var_namedestination']);
            }
            $mysql->close();
        } catch (GDbException $e) {
            echo $e->getError();
        }
        return $ret;
    }

    /** @param TaxZone $taxZone */
    public function insert($taxZone) {

        $return = array();
        $param = array("ss",$taxZone->getZon_var_name(), $taxZone->getZon_int_idassociated());
        try{
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_tax_zone_ins(?,?, @p_status, @p_msg, @p_insert_id);", $param, false);
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

    /** @param TaxZone $taxZone */
    public function update($taxZone) {

        $return = array();
        $param = array("iss",$taxZone->getZon_int_id(),$taxZone->getZon_var_name(), $taxZone->getZon_int_idassociated());
        try{
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_tax_zone_upd(?,?,?, @p_status, @p_msg);", $param, false);
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

    /** @param TaxZone $taxZone */
    public function delete($taxZone) {

        $return = array();
        $param = array("i",$taxZone->getZon_int_id());
        try {
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_tax_zone_del(?, @p_status, @p_msg);", $param, false);
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