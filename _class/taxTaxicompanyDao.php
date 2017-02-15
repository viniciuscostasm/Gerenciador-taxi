<?php
require_once(ROOT_SYS_CLASS . "taxTaxicompany.php");

GF::importClass(array("taxTaxicompany"));

class TaxTaxicompanyDao {
    /** @param TaxTaxicompany $taxTaxicompany */
    public function selectByIdForm($taxTaxicompany) {
        $ret = array();
        try {
            $mysql = new GDbMysql();
            $mysql->execute("SELECT txc_int_id,txc_var_name,txc_dec_valuestopped,txc_dec_valueextreme, txc_dec_valuetransfer FROM vw_tax_taxicompany WHERE txc_int_id = ? ;", array("i", $taxTaxicompany->getTxc_int_id()));
            if ($mysql->fetch()) {
                $ret = $mysql->res;
            }
            $mysql->freeResult();
            $ret['zones'] = array();
            $ret['cities'] = array();
            $mysql->execute("SELECT zon_int_id,zon_var_name,tzo_dec_value FROM vw_tax_taxicompany_zone WHERE txc_int_id = ? ", array("i", $taxTaxicompany->getTxc_int_id()));
            while ($mysql->fetch()) {
                $ret['zones'][] = array('zon_int_id' => $mysql->res['zon_int_id'], 'zon_var_name' => $mysql->res['zon_var_name'], 'tzo_dec_value' => $mysql->res['tzo_dec_value']);
            }
            $mysql->freeResult();
            $mysql->execute("SELECT cit_int_idsource,cit_var_namesource,cit_int_iddestination,cit_var_namedestination,txi_dec_value FROM vw_tax_taxcompanyitinerary WHERE txc_int_id = ? ", array("i", $taxTaxicompany->getTxc_int_id()));
            while ($mysql->fetch()) {
                $ret['cities'][] = array('cit_int_idsource' => $mysql->res['cit_int_idsource'], 'cit_var_namesource' => $mysql->res['cit_var_namesource'], 'cit_int_iddestination' => $mysql->res['cit_int_iddestination'], 'cit_var_namedestination' => $mysql->res['cit_var_namedestination'], 'txi_dec_value' => $mysql->res['txi_dec_value']);
            }
            $mysql->close();
        } catch (GDbException $e) {
            echo $e->getError();
        }
        return $ret;
    }

    /** @param TaxTaxicompany $taxTaxicompany */
    public function insert($taxTaxicompany, $zon_int_idlist, $tzo_dec_valuelist, $cit_int_idsourcelist , $cit_int_iddestinationlist, $txi_dec_valuelist) {

        $return = array();
        $param = array("sdddsssss",$taxTaxicompany->getTxc_var_name(), $taxTaxicompany->getTxc_dec_valuestopped() ,$taxTaxicompany->getTxc_dec_valueextreme(), $taxTaxicompany->getTxc_dec_valuetransfer(), $zon_int_idlist, $tzo_dec_valuelist, $cit_int_idsourcelist, $cit_int_iddestinationlist , $txi_dec_valuelist);
        try{
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_tax_taxicompany_ins(?,?,?,?,?,?,?,?,?, @p_status, @p_msg, @p_insert_id);", $param, false);
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

    /** @param TaxTaxicompany $taxTaxicompany */
    public function update($taxTaxicompany, $zon_int_idlist, $tzo_dec_valuelist, $cit_int_idsourcelist, $cit_int_iddestinationlist, $txi_dec_valuelist) {

        $return = array();
        $param = array("isdddsssss",$taxTaxicompany->getTxc_int_id(), $taxTaxicompany->getTxc_var_name(), $taxTaxicompany->getTxc_dec_valuestopped(), $taxTaxicompany->getTxc_dec_valueextreme(), $taxTaxicompany->getTxc_dec_valuetransfer(), $zon_int_idlist, $tzo_dec_valuelist, $cit_int_idsourcelist, $cit_int_iddestinationlist, $txi_dec_valuelist);
        try{
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_tax_taxicompany_upd(?,?,?,?,?,?,?,?,?,?, @p_status, @p_msg);", $param, false);
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

    /** @param TaxTaxicompany $taxTaxicompany */
    public function delete($taxTaxicompany) {

        $return = array();
        $param = array("i",$taxTaxicompany->getTxc_int_id());
        try {
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_tax_taxicompany_del(?, @p_status, @p_msg);", $param, false);
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