<?php
require_once(ROOT_SYS_CLASS . "taxMotive.php");

GF::importClass(array("taxMotive"));

class TaxMotiveDao {
    /** @param TaxMotive $taxMotive */
    public function selectByIdForm($taxMotive) {
        $ret = array();
        try {
            $mysql = new GDbMysql();
            $mysql->execute("SELECT mot_int_id,mot_var_name FROM vw_tax_motive WHERE mot_int_id = ? ", array("i", $taxMotive->getMot_int_id()));
            if ($mysql->fetch()) {
                $ret = $mysql->res;
            }
            $mysql->close();
        } catch (GDbException $e) {
            echo $e->getError();
        }
        return $ret;
    }

    /** @param TaxMotive $taxMotive */
    public function insert($taxMotive) {

        $return = array();
        $param = array("s",$taxMotive->getMot_var_name());
        try{
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_tax_motive_ins(?, @p_status, @p_msg, @p_insert_id);", $param, false);
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

    /** @param TaxMotive $taxMotive */
    public function update($taxMotive) {

        $return = array();
        $param = array("is",$taxMotive->getMot_int_id(),$taxMotive->getMot_var_name());
        try{
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_tax_motive_upd(?,?, @p_status, @p_msg);", $param, false);
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

    /** @param TaxMotive $taxMotive */
    public function delete($taxMotive) {

        $return = array();
        $param = array("i",$taxMotive->getMot_int_id());
        try {
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_tax_motive_del(?, @p_status, @p_msg);", $param, false);
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