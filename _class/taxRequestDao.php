<?php
require_once(ROOT_SYS_CLASS . "taxRequest.php");

GF::importClass(array("taxRequest","user","taxEmployee","taxCostcenter","taxMotive","taxCity","taxDistrict","taxZone","taxCity","taxDistrict","taxZone","taxRide"));

class TaxRequestDao {
    /** @param TaxRequest $taxRequest */
    public function selectByIdForm($taxRequest) {
        $ret = array();
        try {
            $mysql = new GDbMysql();
            $mysql->execute("SELECT req_int_id,usr_int_id,emp_int_id,req_var_passenger,req_cha_type,coc_int_id,req_daf_date as req_dat_date,req_var_hour,mot_int_id,req_var_addresssource,cit_int_idsource,dis_int_idsource,zon_int_idsource,req_var_addressdestination,cit_int_iddestination,dis_int_iddestination,zon_int_iddestination,req_txt_comment,rid_int_id,req_dec_value FROM vw_tax_request WHERE req_int_id = ? ", array("i", $taxRequest->getReq_int_id()));
            if ($mysql->fetch()) {
                $ret = $mysql->res;
            }
            $mysql->close();
        } catch (GDbException $e) {
            echo $e->getError();
        }
        return $ret;
    }

    /** @param TaxRequest $taxRequest */
    public function insert($taxRequest) {

        $return = array();
        $param = array("iississisiisiis",
            $taxRequest->getUser()->getUsr_int_id(),
            $taxRequest->getTaxEmployee()->getEmp_int_id(),
            $taxRequest->getReq_var_passenger(),
            $taxRequest->getReq_cha_type(),
            $taxRequest->getTaxCostCenter()->getCoc_int_id(),
            $taxRequest->getReq_dat_date(),
            $taxRequest->getReq_var_hour(),
            $taxRequest->getTaxMotive()->getMot_int_id(),
            $taxRequest->getReq_var_addresssource(),
            $taxRequest->getTaxCitySource()->getCit_int_id(),
            $taxRequest->getTaxDistrictSource()->getDis_int_id(),
            $taxRequest->getReq_var_addressdestination(),
            $taxRequest->getTaxCityDestination()->getCit_int_id(),
            $taxRequest->getTaxDistrictDestination()->getDis_int_id(),
            $taxRequest->getReq_txt_comment()
        );
        try{
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_tax_request_ins(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?, @p_status, @p_msg, @p_insert_id);", $param, false);
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

    /** @param TaxRequest $taxRequest */
    public function update($taxRequest) {

        $return = array();
        $param = array("iiississisiisiis",
            $taxRequest->getReq_int_id(),
            $taxRequest->getUser()->getUsr_int_id(),
            $taxRequest->getTaxEmployee()->getEmp_int_id(),
            $taxRequest->getReq_var_passenger(),
            $taxRequest->getReq_cha_type(),
            $taxRequest->getTaxCostCenter()->getCoc_int_id(),
            $taxRequest->getReq_dat_date(),
            $taxRequest->getReq_var_hour(),
            $taxRequest->getTaxMotive()->getMot_int_id(),

            $taxRequest->getReq_var_addresssource(),
            $taxRequest->getTaxCitySource()->getCit_int_id(),
            $taxRequest->getTaxDistrictSource()->getDis_int_id(),

            $taxRequest->getReq_var_addressdestination(),
            $taxRequest->getTaxCityDestination()->getCit_int_id(),
            $taxRequest->getTaxDistrictDestination()->getDis_int_id(),

            $taxRequest->getReq_txt_comment()
        );
        try{
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_tax_request_upd(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?, @p_status, @p_msg);", $param, false);
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

    /** @param TaxRequest $taxRequest */
    public function delete($taxRequest) {

        $return = array();
        $param = array("i",$taxRequest->getReq_int_id());
        try {
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_tax_request_del(?, @p_status, @p_msg);", $param, false);
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