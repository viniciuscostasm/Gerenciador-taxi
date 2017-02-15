<?php
require_once(ROOT_SYS_CLASS . "taxTaxcompanyZone.php");

GF::importClass(array("taxTaxcompanyZone","taxTaxcompany","taxZone"));

class TaxTaxcompanyZoneDao {
    /** @param TaxTaxcompanyZone $taxTaxcompanyZone */
    public function selectByIdForm($taxTaxcompanyZone) {
        $ret = array();
        try {
            $mysql = new GDbMysql();
            $mysql->execute("SELECT tzo_int_id,txc_int_id,zon_int_id,tzo_dec_value FROM vw_tax_taxicompany_zone WHERE tzo_int_id = ? ", array("i", $taxTaxcompanyZone->getTzo_int_id()));
            if ($mysql->fetch()) {
                $ret = $mysql->res;
            }
            $mysql->close();
        } catch (GDbException $e) {
            echo $e->getError();
        }
        return $ret;
    }

    /** @param TaxTaxcompanyZone $taxTaxcompanyZone */
    public function insert($taxTaxcompanyZone) {

        $return = array();
        $param = array("issssss",
            $taxEmployee->getTaxCostcenter()->getCoc_var_key(),
            $taxEmployee->getEmp_var_key(),$taxEmployee->getEmp_var_name(),$taxEmployee->getEmp_var_address(),$taxEmployee->getEmp_var_city(),$taxEmployee->getEmp_var_district(),$taxEmployee->getEmp_var_cep());

        try{
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_tax_taxicompany_zone_ins(?,?,?, @p_status, @p_msg, @p_insert_id);", $param, false);
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

    /** @param TaxTaxcompanyZone $taxTaxcompanyZone */
    public function update($taxTaxcompanyZone) {

        $return = array();
        $param = array("iiid",$taxTaxcompanyZone->getTzo_int_id(),$taxTaxcompanyZone->getTaxTaxcompany()->getTxc_int_id(),$taxTaxcompanyZone->getTaxZone()->getZon_int_id(),$taxTaxcompanyZone->getTzo_dec_value());
        try{
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_tax_taxicompany_zone_upd(?,?,?,?, @p_status, @p_msg);", $param, false);
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

    /** @param TaxTaxcompanyZone $taxTaxcompanyZone */
    public function delete($taxTaxcompanyZone) {

        $return = array();
        $param = array("i",$taxTaxcompanyZone->getTzo_int_id());
        try {
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_tax_taxicompany_zone_del(?, @p_status, @p_msg);", $param, false);
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