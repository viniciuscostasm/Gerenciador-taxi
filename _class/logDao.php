<?php
require_once(ROOT_SYS_CLASS . "log.php");

GF::importClass(array("log"));

class LogDao {
    /** @param Log $log */
    public function selectByIdForm($log) {
        $ret = array();
        try {
            $mysql = new GDbMysql();
            $mysql->execute("SELECT env_int_id,log_int_id,log_var_key,log_var_evento,log_txt_conteudo,log_cha_tecnico FROM vw_adm_log WHERE log_int_id = ? ", array("i", $log->getLog_int_id()));
            if ($mysql->fetch()) {
                $ret = $mysql->res;
            }
            $mysql->close();
        } catch (GDbException $e) {
            echo $e->getError();
        }
        return $ret;
    }

    /** @param Log $log */
    public function insert($log) {

        $return = array();
        $param = array("issss",$log->getEnv_int_id(),$log->getLog_var_key(),$log->getLog_var_evento(),$log->getLog_txt_conteudo(),$log->getLog_cha_tecnico());
        try{
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_adm_log_ins(?,?,?,?,?);", $param, false);
            $mysql->fetch();
            $return["status"] = true;
            $return["msg"] = $mysql->res[1];
            $mysql->close();
        } catch (GDbException $e) {
            $return["status"] = false;
            $return["msg"] = $e->getError();
        }
        return $return;
    }
}