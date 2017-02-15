<?php

require_once(ROOT_SYS_CLASS . "user.php");

GF::importClass(array("user","resource", "resourceProfile"));

class UserDao {

    /** @param User $user */
    public function selectByIdForm($user) {
        $ret = array();
        try {
            $mysql = new GDbMysql();
            $mysql->execute("SELECT usr_int_id,usr_var_name,usr_var_email,usr_cha_status,
                                    usr_var_token,usr_dti_lastaccess,usr_dti_add,CONCAT(pro_int_id,'-',pro_cha_type) AS pro_int_id
                               FROM vw_adm_user WHERE usr_int_id = ? ", array("i", $user->getUsr_int_id()));
            if ($mysql->fetch()) {
                $ret = $mysql->res;
            }
            $mysql->freeResult();
            if ($ret['pro_int_id'] == '4-SOL') {
                $res = array();
                $mysql->execute("SELECT coc_int_id FROM vw_tax_costcenter_user WHERE usr_int_id = ? ", array("i", $user->getUsr_int_id()));
                while ($mysql->fetch()) {
                    $res[] = $mysql->res['coc_int_id'];
                }
                if (count($res)) $ret['coc_int_id'] = implode('||', $res);
            } else if ($ret['pro_int_id'] == '5-EMP') {
                $res = array();
                $mysql->execute("SELECT txc_int_id FROM vw_tax_taxicompany_user WHERE usr_int_id = ? ", array("i", $user->getUsr_int_id()));
                if ($mysql->fetch()) {
                    $ret['txc_int_id'] = $mysql->res['txc_int_id'];
                }
            }
            $mysql->close();
        } catch (GDbException $e) {
            echo $e->getError();
        }
        return $ret;
    }

    /** @param User $user */
    public function insert($user, $coc_int_idlist, $txc_int_idlist) {
        $return = array();
        $param = array("ssissss",
            $user->getUsr_var_name(),
            $user->getUsr_var_email(),
            $user->getProfile()->getPro_int_id(),
            // $user->getUsr_var_function(),
            // $user->getUsr_var_phone(),
            $user->getUsr_var_password(),
            $user->getUsr_cha_status(),
            $txc_int_idlist,
            $coc_int_idlist
        );
        try {
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_adm_user_ins(?,?,?,?,?,?,?,@p_status,@p_msg,@p_insert_id);", $param, false);
            $mysql->execute('SELECT @p_status,@p_msg,@p_insert_id');
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

    /** @param User $user */
    public function update($user, $coc_int_idlist, $txc_int_idlist) {
        $return = array();
        $param = array("ississs",
            $user->getUsr_int_id(),
            $user->getUsr_var_name(),
            $user->getUsr_var_email(),
            $user->getProfile()->getPro_int_id(),
            // $user->getUsr_var_function(),
            // $user->getUsr_var_phone(),
            $user->getUsr_cha_status(),
            $txc_int_idlist,
            $coc_int_idlist
        );

        try {
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_adm_user_upd(?,?,?,?,?,?,?,@p_status,@p_msg);", $param, false);
            $mysql->execute('SELECT @p_status,@p_msg');
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

    /** @param User $user */
    public function profile($user) {
        $return = array();
        $param = array("issss",
            $user->getUsr_int_id(),
            $user->getUsr_var_name(),
            $user->getUsr_var_email(),
            $user->getUsr_var_function(),
            $user->getUsr_var_phone()
        );

        try {
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_adm_user_profile(?,?,?,?,?,@p_status,@p_msg);", $param, false);
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

    /** @param User $user */
    public function delete($user) {

        $return = array();
        $param = array("i", $user->getUsr_int_id());
        try {
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_adm_user_del(?,@p_status,@p_msg);", $param, false);
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

    /** @param User $user */
    public function signin($user, $env_int_id) {
        $return = array();
        $param = array("ssi", $user->getUsr_var_email(), $user->getUsr_var_password(), $env_int_id);

        try {
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_signin(?,?,?,@p_status,@p_msg);", $param, false);
            $mysql->execute('SELECT @p_status,@p_msg');
            $mysql->fetch();
            $return["status"] = ($mysql->res[0]) ? true : false;
            $return["msg"] = $mysql->res[1];
            if ($return["status"]) {
                $retLogin = $this->login($user);
                $return["msg"] .= '<br>' . $retLogin['msg'];
            }
            $mysql->close();
        } catch (GDbException $e) {
            $return["status"] = false;
            $return["msg"] = $e->getError();
        }
        return $return;
    }

    /**
     * @param User $user
     */
    public function login($user) {
        try {
            $mysql = new GDbMysql();
            $mysql2 = new GDbMysql();

            $param = array("ss", $user->getUsr_var_email(), $user->getUsr_var_password());
            $mysql->execute("CALL sp_adm_login(?,?, @p_status, @p_msg, @p_usr_int_id);", $param, false);
            $mysql->execute("SELECT @p_status, @p_msg, @p_usr_int_id");
            $mysql->fetch();

            if ($mysql->res[0]) {
                $usr_int_id = $mysql->res[2];

                $userSes = new User();
                $userSes->setUsr_int_id($usr_int_id);

                $query = "SELECT usr_var_name, usr_var_email, usr_cha_status,
                                 usr_dti_lastaccess, pro_int_id, pro_var_name, pro_cha_type
                            FROM vw_adm_user
                           WHERE usr_int_id = ?";
                $param = array('i', $usr_int_id);
                $mysql2->execute($query, $param);
                if ($mysql2->fetch()) {
                    $profile = new Profile();
                    $profile->setPro_int_id($mysql2->res['pro_int_id']);
                    $profile->setPro_var_name($mysql2->res['pro_var_name']);
                    $profile->setPro_cha_type($mysql2->res['pro_cha_type']);

                    $userSes->setUsr_var_name($mysql2->res['usr_var_name']);
                    $userSes->setUsr_var_email($mysql2->res['usr_var_email']);
                    $userSes->setUsr_cha_status($mysql2->res['usr_cha_status']);
                    $userSes->setUsr_dti_lastaccess($mysql2->res['usr_dti_lastaccess']);
                    $userSes->setProfile($profile);

                    $mysql3 = new GDbMysql();

                    $query = "SELECT res_var_key, res_var_name, res_cha_type, res_txt_parameters, res_var_path, rpr_int_id
                                FROM vw_adm_resource_profile
                               WHERE pro_int_id = ?";
                    $param = array('i', $profile->getPro_int_id());
                    $mysql3->execute($query, $param);
                    $arrayResourceProfileSes = array();

                    while ($mysql3->fetch()) {
                        $resource = new Resource();
                        $resource->setRes_var_key($mysql3->res['res_var_key']);
                        $resource->setRes_var_name($mysql3->res['res_var_name']);
                        $resource->setRes_cha_type($mysql3->res['res_cha_type']);
                        $resource->setRes_txt_parameters($mysql3->res['res_txt_parameters']);
                        $resource->setRes_var_path($mysql3->res['res_var_path']);

                        $resourceProfile = new ResourceProfile();
                        $resourceProfile->setRpr_int_id($mysql3->res['rpr_int_id']);
                        $resourceProfile->setProfile($profile);
                        $resourceProfile->setResource($resource);

                        $arrayResourceProfileSes[] = $resourceProfile;
                    }

                    unset($_SESSION["s_user"]);
                    $_SESSION["s_user"] = serialize($userSes);

                    unset($_SESSION["s_resourceProfile"]);
                    $_SESSION["s_resourceProfile"] = serialize($arrayResourceProfileSes);

                    $this->menuProfileSession($profile);

                    $ret['status'] = true;
                    $ret['msg'] = $mysql->res[1];

                }
            } else {
                unset($_SESSION["s_user"]);
                $ret['status'] = false;
                $ret['msg'] = $mysql->res[1];
            }
        } catch (GDbException $e) {
            $ret['status'] = false;
            $ret['msg'] = $e->getError();
        }
        $mysql->close();
        $mysql2->close();
        return $ret;
    }

    /**
     * Coloca na sessão o array com as permissões para o profile
     *
     * @param Profile $profile
     */
    public function menuProfileSession($profile) {
        $mysql = new GDbMysql();
        try {
            $query = "SELECT men_var_url
                        FROM vw_adm_menu_profile
                       WHERE pro_int_id = ?";
            $param = array('i', $profile->getPro_int_id());
            $mysql->execute($query, $param);
            $arrayMenuProfile = array();
            while ($mysql->fetch()) {
                $arrayMenuProfile[] = $mysql->res['men_var_url'];
            }
            unset($_SESSION["s_menuProfile"]);
            $_SESSION["s_menuProfile"] = $arrayMenuProfile;
        } catch (GDbException $e) {
            echo $e->getError();
        }
        $mysql->close();
    }

    /** @param User $user */
    public function changePassword($usr_var_currentpassword, $usr_var_newpassword) {

        $userSession = GSec::getUserSession();
        $return = array();
        $param = array("iss", $userSession->getUsr_int_id(), $usr_var_currentpassword, $usr_var_newpassword);
        try {
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_adm_userpassword_upd(?,?,?);", $param);
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

}
