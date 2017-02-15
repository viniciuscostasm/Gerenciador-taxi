<?php
require_once(ROOT_SYS_CLASS . "taxCostcenter.php");

GF::importClass(array("taxCostcenter"));

class TaxCostcenterDao {
    /** @param TaxCostcenter $taxCostcenter */
    public function selectByIdForm($taxCostcenter) {
        $ret = array();
        try {
            $mysql = new GDbMysql();
            $mysql->execute("SELECT coc_int_id,coc_var_key,coc_var_name FROM vw_tax_costcenter WHERE coc_int_id = ? ", array("i", $taxCostcenter->getCoc_int_id()));
            if ($mysql->fetch()) {
                $ret = $mysql->res;
            }
            $mysql->close();
        } catch (GDbException $e) {
            echo $e->getError();
        }
        return $ret;
    }

    /** @param TaxCostcenter $taxCostcenter */
    public function insert($taxCostcenter) {

        $return = array();
        $param = array("ss",$taxCostcenter->getCoc_var_key(),$taxCostcenter->getCoc_var_name());
        try{
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_tax_costcenter_ins(?,?, @p_status, @p_msg, @p_insert_id);", $param, false);
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

    /** @param TaxCostcenter $taxCostcenter */
    public function update($taxCostcenter) {

        $return = array();
        $param = array("iss",$taxCostcenter->getCoc_int_id(),$taxCostcenter->getCoc_var_key(),$taxCostcenter->getCoc_var_name());
        try{
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_tax_costcenter_upd(?,?,?, @p_status, @p_msg);", $param, false);
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

    /** @param TaxCostcenter $taxCostcenter */
    public function delete($taxCostcenter) {

        $return = array();
        $param = array("i",$taxCostcenter->getCoc_int_id());
        try {
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_tax_costcenter_del(?, @p_status, @p_msg);", $param, false);
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