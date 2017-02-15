<?php

require_once(ROOT_SYS_CLASS . "profile.php");

GF::importClass(array("profile"));

class ProfileDao {

    /** @param Profile $profile */
    public function selectById($profile) {
        try {
            $mysql = new GDbMysql();
            $mysql->execute("SELECT pro_int_id,pro_cha_type,pro_var_name FROM vw_adm_profile WHERE pro_int_id = ? ", array("i", $profile->getPro_int_id()));
            if ($mysql->fetch()) {
                $profile = new Profile();
                $profile->setPro_int_id($mysql->res["pro_int_id"]);
                $profile->setPro_var_name($mysql->res["pro_var_name"]);
            }
            $mysql->close();
        } catch (GDbException $e) {
            echo $e->getError();
        }
        return $profile;
    }

    /** @param Profile $profile */
    public function selectByIdForm($profile) {
        $ret = array();
        try {
            $mysql = new GDbMysql();
            $mysql->execute("SELECT pro_int_id,pro_cha_type,pro_var_name,men_int_idlist,res_int_idlist FROM vw_adm_profile WHERE pro_int_id = ? ", array("i", $profile->getPro_int_id()));
            if ($mysql->fetch()) {
                $ret = $mysql->res;
            }
            $mysql->close();
        } catch (GDbException $e) {
            echo $e->getError();
        }
        return $ret;
    }

    /**
     * @param Profile $profile
     * @param string $men_int_idlist
     */
    public function insert($profile, $men_int_idlist, $res_int_idlist) {

        $userSession = GSec::getUserSession();
        $return = array();
        $param = array("isss", $userSession->getUsr_int_id(), $profile->getPro_var_name(), $men_int_idlist, $res_int_idlist);
        try {
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_adm_profile_ins(?,?,?,?);", $param);
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

    /**
     * @param Profile $profile
     * @param string $men_int_idlist
     */
    public function update($profile, $men_int_idlist, $res_int_idlist) {

        $userSession = GSec::getUserSession();
        $return = array();
        $param = array("iisss", 
            $userSession->getUsr_int_id(), 
            $profile->getPro_int_id(), 
            $profile->getPro_var_name(), 
            $men_int_idlist, 
            $res_int_idlist);
        try {
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_adm_profile_upd(?,?,?,?,?);", $param);
            $mysql->fetch();
            $return["status"] = ($mysql->res[0]) ? true : false;
            $return["msg"] = $mysql->res[1];
            $return["affectedRows"] = $mysql->res[2];
            $mysql->close();
        } catch (GDbException $e) {
            $return["status"] = false;
            $return["msg"] = $e->getError();
        }
        return $return;
    }

    /** @param Profile $profile */
    public function delete($profile) {

        $userSession = GSec::getUserSession();
        $return = array();
        $param = array("ii", $userSession->getUsr_int_id(), $profile->getPro_int_id());
        try {
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_adm_profile_del(?,?,@p_status,@p_msg);", $param, false);
            $mysql->execute('SELECT @p_status,@p_msg');
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
