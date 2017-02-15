<?php
require_once(ROOT_SYS_CLASS . "taxRide.php");

GF::importClass(array("taxRide","taxTaxicompany","taxZone","user"));

class TaxRideDao {
    /** @param TaxRide $taxRide */
    public function selectByIdForm($taxRide) {
        $ret = array();
        try {
            $mysql = new GDbMysql();
            $mysql->execute("SELECT rid_int_id, rid_dat_date, rid_daf_arrival, rid_hou_hour,txc_int_id,rid_cha_status,rid_int_passengers,zon_int_id,zon_int_idlist,zon_var_namelist,rid_txt_passengerlist,rid_hor_stopped,rid_dec_stopped,rid_dec_parking,rid_dec_transfer,rid_dec_value,usr_int_idadd,usr_int_idarrival,rid_var_plate,rid_txt_comment,rid_var_driver,rid_hou_arrival FROM vw_tax_ride WHERE rid_int_id = ? ", array("i", $taxRide->getRid_int_id()));
            if ($mysql->fetch()) {
                $ret = $mysql->res;
            }
            $mysql->close();
        } catch (GDbException $e) {
            echo $e->getError();
        }
        return $ret;
    }

    /** @param TaxRide $taxRide */
    public function insert($taxRide) {

        $return = array();
        $param = array("sssisiissssddddiissss",$taxRide->getTco_cha_type(),$taxRide->getRid_dat_date(),$taxRide->getRid_hou_hour(),$taxRide->getTaxTaxicompany()->getTxc_int_id(),$taxRide->getRid_cha_status(),$taxRide->getRid_int_passengers(),$taxRide->getTaxZone()->getZon_int_id(),$taxRide->getZon_int_idlist(),$taxRide->getZon_var_namelist(),$taxRide->getRid_txt_passengerlist(),$taxRide->getRid_hor_stopped(),$taxRide->getRid_dec_stoppedhour(),$taxRide->getRid_dec_parking(),$taxRide->getRid_dec_transfer(),$taxRide->getRid_dec_value(),$taxRide->getUser()->getUsr_int_idadd(),$taxRide->getUser()->getUsr_int_idarrival(),$taxRide->getRid_var_plate(),$taxRide->getRid_txt_comment(),$taxRide->getRid_var_driver(),$taxRide->getRid_hou_arrival());
        try{
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_tax_ride_ins(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?, @p_status, @p_msg, @p_insert_id);", $param, false);
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

    /** @param TaxRide $taxRide */
    public function update($taxRide) {

        $return = array();
        $param = array("isssisiissssddddiissss",$taxRide->getRid_int_id(),$taxRide->getTco_cha_type(),$taxRide->getRid_dat_date(),$taxRide->getRid_hou_hour(),$taxRide->getTaxTaxicompany()->getTxc_int_id(),$taxRide->getRid_cha_status(),$taxRide->getRid_int_passengers(),$taxRide->getTaxZone()->getZon_int_id(),$taxRide->getZon_int_idlist(),$taxRide->getZon_var_namelist(),$taxRide->getRid_txt_passengerlist(),$taxRide->getRid_hor_stopped(),$taxRide->getRid_dec_stoppedhour(),$taxRide->getRid_dec_parking(),$taxRide->getRid_dec_transfer(),$taxRide->getRid_dec_value(),$taxRide->getUser()->getUsr_int_idadd(),$taxRide->getUser()->getUsr_int_idarrival(),$taxRide->getRid_var_plate(),$taxRide->getRid_txt_comment(),$taxRide->getRid_var_driver(),$taxRide->getRid_hou_arrival());
        try{
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_tax_ride_upd(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?, @p_status, @p_msg);", $param, false);
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

    public function order($taxRide, $action, $req_int_id)
    {
        $userSession = GSec::getUserSession();
        $return = array();
        $param = array("iis",$userSession->getUsr_int_id(), $req_int_id, $action);
        try{
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_tax_requestride_order(?,?,?, @p_status, @p_msg);", $param, false);
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

    public function newRide($taxRide, $req_int_id)
    {
        $userSession = GSec::getUserSession();
        $return = array();
        $param = array("ii",$userSession->getUsr_int_id(), $req_int_id);
        try{
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_tax_requestride_new(?,?, @p_status, @p_msg);", $param, false);
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

    public function reject($taxRide, $comment, $req_int_id)
    {
        $userSession = GSec::getUserSession();
        $return = array();
        $param = array("iis",$userSession->getUsr_int_id(), $req_int_id, $comment);
        try{
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_tax_requestride_reject(?,?,?, @p_status, @p_msg);", $param, false);
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

    public function move($taxRide, $rid_int_id, $req_int_id)
    {
        $userSession = GSec::getUserSession();
        $return = array();
        $param = array("iii",$userSession->getUsr_int_id(), $req_int_id, $rid_int_id);
        try{
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_tax_requestride_move(?,?,?, @p_status, @p_msg);", $param, false);
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

    public function taxiCompany($taxRide, $txc_int_id)
    {
        $userSession = GSec::getUserSession();
        $return = array();
        $param = array("iii",$userSession->getUsr_int_id(), $taxRide->getRid_int_id(), $txc_int_id);
        try{
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_tax_requestride_taxcompany(?,?,?, @p_status, @p_msg, @p_rid_dec_total);", $param, false);
            $mysql->execute("SELECT @p_status, @p_msg, @p_rid_dec_total");
            $mysql->fetch();
            $return["status"] = ($mysql->res[0]) ? true : false;
            $return["msg"] = $mysql->res[1];
            $return["valor"] = $mysql->res[2];
            $mysql->close();
        } catch (GDbException $e) {
            $return["status"] = false;
            $return["msg"] = $e->getError();
        }
        return $return;
    }

    public function changeHour($taxRide, $rid_hou_hour)
    {
        $userSession = GSec::getUserSession();
        $return = array();
        $param = array("iis",$userSession->getUsr_int_id(), $taxRide->getRid_int_id(), $rid_hou_hour);
        try{
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_tax_requestride_hour(?,?,?, @p_status, @p_msg);", $param, false);
            $mysql->execute("SELECT @p_status, @p_msg");
            $mysql->fetch();
            $return["status"] = ($mysql->res[0]) ? true : false;
            $return["msg"] = $mysql->res[1];
            $return["valor"] = $mysql->res[2];
            $mysql->close();
        } catch (GDbException $e) {
            $return["status"] = false;
            $return["msg"] = $e->getError();
        }
        return $return;
    }

    public function approve($taxRide)
    {
        $userSession = GSec::getUserSession();
        $return = array();
        $param = array("ii",$userSession->getUsr_int_id(), $taxRide->getRid_int_id());
        try{
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_tax_ride_approve(?,?, @p_status, @p_msg);", $param, false);
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

    public function cancel($taxRide)
    {
        $userSession = GSec::getUserSession();
        $return = array();
        $param = array("ii",$userSession->getUsr_int_id(), $taxRide->getRid_int_id());
        try{
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_tax_ride_cancel(?,?, @p_status, @p_msg);", $param, false);
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
    public function close($taxRide)
    {
        $userSession = GSec::getUserSession();
        $return = array();
        $param = array("iisssdssss",$userSession->getUsr_int_id(), $taxRide->getRid_int_id(), $taxRide->getRid_dat_arrival(), $taxRide->getRid_hou_arrival(), $taxRide->getRid_hor_stopped(), $taxRide->getRid_dec_parking(), $taxRide->getRid_cha_transfer(), $taxRide->getRid_var_driver(), $taxRide->getRid_var_plate(), $taxRide->getRid_txt_comment());
        try{
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_tax_ride_close(?,?,?,?,?,?,?,?,?,?, @p_status, @p_msg);", $param, false);
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

    public function commentRequest($req_int_id, $req_txt_comment, $req_cha_absent)
    {
        $userSession = GSec::getUserSession();
        $return = array();
        $param = array("iiss",$userSession->getUsr_int_id(), $req_int_id, $req_txt_comment, $req_cha_absent);
        try{
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_tax_requestride_upd(?,?,?,?, @p_status, @p_msg);", $param, false);
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

    /** @param TaxRide $taxRide */
    public function delete($taxRide) {

        $return = array();
        $param = array("i",$taxRide->getRid_int_id());
        try {
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_tax_ride_del(?, @p_status, @p_msg);", $param, false);
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