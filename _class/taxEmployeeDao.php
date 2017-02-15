<?php
require_once(ROOT_SYS_CLASS . "taxEmployee.php");

GF::importClass(array("taxEmployee","taxCostcenter","taxCity","taxDistrict"));

class TaxEmployeeDao {
    /** @param TaxEmployee $taxEmployee */
    public function selectByIdForm($taxEmployee) {
        $ret = array();
        try {
            $mysql = new GDbMysql();
            $mysql->execute("SELECT emp_int_id,coc_int_id,emp_var_key,emp_var_name,emp_var_address,emp_var_cep,cit_int_id,dis_int_id FROM vw_tax_employee WHERE emp_int_id = ? ", array("i", $taxEmployee->getEmp_int_id()));
            if ($mysql->fetch()) {
                $ret = $mysql->res;
            }
            $mysql->close();
        } catch (GDbException $e) {
            echo $e->getError();
        }
        return $ret;
    }

    /** @param TaxEmployee $taxEmployee */
    public function insert($taxEmployee) {

        $return = array();
        $param = array("issssii",$taxEmployee->getTaxCostcenter()->getCoc_int_id(),$taxEmployee->getEmp_var_key(),$taxEmployee->getEmp_var_name(),$taxEmployee->getEmp_var_address(),$taxEmployee->getEmp_var_cep(),$taxEmployee->getTaxCity()->getCit_int_id(),$taxEmployee->getTaxDistrict()->getDis_int_id());
        try{
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_tax_employee_ins(?,?,?,?,?,?,?, @p_status, @p_msg, @p_insert_id);", $param, false);
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

    /** @param TaxEmployee $taxEmployee */
    public function update($taxEmployee) {

        $return = array();
        $param = array("iissssii",$taxEmployee->getEmp_int_id(),$taxEmployee->getTaxCostcenter()->getCoc_int_id(),$taxEmployee->getEmp_var_key(),$taxEmployee->getEmp_var_name(),$taxEmployee->getEmp_var_address(),$taxEmployee->getEmp_var_cep(),$taxEmployee->getTaxCity()->getCit_int_id(),$taxEmployee->getTaxDistrict()->getDis_int_id());
        try{
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_tax_employee_upd(?,?,?,?,?,?,?,?, @p_status, @p_msg);", $param, false);
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

    /** @param TaxEmployee $taxEmployee */
    public function delete($taxEmployee) {

        $return = array();
        $param = array("i",$taxEmployee->getEmp_int_id());
        try {
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_tax_employee_del(?, @p_status, @p_msg);", $param, false);
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