<?php
require_once(ROOT_SYS_CLASS . "taxTaxcompanyUser.php");

GF::importClass(array("taxTaxcompanyUser","taxTaxcompany","user"));

class TaxTaxcompanyUserDao {
    /** @param TaxTaxcompanyUser $taxTaxcompanyUser */
    public function selectByIdForm($taxTaxcompanyUser) {
        $ret = array();
        try {
            $mysql = new GDbMysql();
            $mysql->execute("SELECT tus_int_id,txc_int_id,usr_int_id FROM vw_tax_taxicompany_user WHERE tus_int_id = ? ", array("i", $taxTaxcompanyUser->getTus_int_id()));
            if ($mysql->fetch()) {
                $ret = $mysql->res;
            }
            $mysql->close();
        } catch (GDbException $e) {
            echo $e->getError();
        }
        return $ret;
    }

    /** @param TaxTaxcompanyUser $taxTaxcompanyUser */
    public function insert($taxTaxcompanyUser) {

        $return = array();
        $param = array("ii",$taxTaxcompanyUser->getTaxTaxcompany()->getTxc_int_id(),$taxTaxcompanyUser->getUser()->getUsr_int_id());
        try{
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_tax_taxicompany_user_ins(?,?, @p_status, @p_msg, @p_insert_id);", $param, false);
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

    /** @param TaxTaxcompanyUser $taxTaxcompanyUser */
    public function update($taxTaxcompanyUser) {

        $return = array();
        $param = array("iii",$taxTaxcompanyUser->getTus_int_id(),$taxTaxcompanyUser->getTaxTaxcompany()->getTxc_int_id(),$taxTaxcompanyUser->getUser()->getUsr_int_id());
        try{
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_tax_taxicompany_user_upd(?,?,?, @p_status, @p_msg);", $param, false);
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

    /** @param TaxTaxcompanyUser $taxTaxcompanyUser */
    public function delete($taxTaxcompanyUser) {

        $return = array();
        $param = array("i",$taxTaxcompanyUser->getTus_int_id());
        try {
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_tax_taxicompany_user_del(?, @p_status, @p_msg);", $param, false);
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