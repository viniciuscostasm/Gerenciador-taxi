<?php
require_once(ROOT_SYS_CLASS . "taxCostcenterUser.php");

GF::importClass(array("taxCostcenterUser","taxCostcenter","user"));

class TaxCostcenterUserDao {
    /** @param TaxCostcenterUser $taxCostcenterUser */
    public function selectByIdForm($taxCostcenterUser) {
        $ret = array();
        try {
            $mysql = new GDbMysql();
            $mysql->execute("SELECT cus_int_id,coc_int_id,usr_int_id FROM vw_tax_costcenter_user WHERE cus_int_id = ? ", array("i", $taxCostcenterUser->getCus_int_id()));
            if ($mysql->fetch()) {
                $ret = $mysql->res;
            }
            $mysql->close();
        } catch (GDbException $e) {
            echo $e->getError();
        }
        return $ret;
    }

    /** @param TaxCostcenterUser $taxCostcenterUser */
    public function insert($taxCostcenterUser) {

        $return = array();
        $param = array("ii",$taxCostcenterUser->getTaxCostcenter()->getCoc_int_id(),$taxCostcenterUser->getUser()->getUsr_int_id());
        try{
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_tax_costcenter_user_ins(?,?, @p_status, @p_msg, @p_insert_id);", $param, false);
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

    /** @param TaxCostcenterUser $taxCostcenterUser */
    public function update($taxCostcenterUser) {

        $return = array();
        $param = array("iii",$taxCostcenterUser->getCus_int_id(),$taxCostcenterUser->getTaxCostcenter()->getCoc_int_id(),$taxCostcenterUser->getUser()->getUsr_int_id());
        try{
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_tax_costcenter_user_upd(?,?,?, @p_status, @p_msg);", $param, false);
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

    /** @param TaxCostcenterUser $taxCostcenterUser */
    public function delete($taxCostcenterUser) {

        $return = array();
        $param = array("i",$taxCostcenterUser->getCus_int_id());
        try {
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_tax_costcenter_user_del(?, @p_status, @p_msg);", $param, false);
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