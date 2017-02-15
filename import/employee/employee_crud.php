<?php
require_once("../../_inc/global.php");
require_once ROOT_SYS_THEME . 'plugins/jquery-file-upload/server/php/CustomUploadHandler.php';

switch ($_POST["acao"]) {
    case "insArq":
        $options = array(
            'upload_dir' => ROOT_UPLOAD . 'employee/',
            'upload_url' => URL_UPLOAD . 'employee/',
            'accept_file_types' => '/.+$/i',
            'max_file_size' => null,
            'min_file_size' => 1
        );



        $uploadHandler = new CustomUploadHandler($options);
        $content = $uploadHandler->getContent();
        $content['files'][0]->new_name = $uploadHandler->getNew_name();
        $content['path'][0] = ROOT_UPLOAD . 'employee/' . $uploadHandler->getNew_name();

        $employees = array_map('str_getcsv', file(ROOT_UPLOAD . 'employee/' . $uploadHandler->getNew_name()));
        array_shift($employees);

        unlink(ROOT_UPLOAD . 'employee/' . $uploadHandler->getNew_name());
        $taxEmployees = array();

        try{
            $limite = 200;
            $i = 0;
            $t = 0;
            $p = 1;
            $total = count($employees);
            foreach ($employees as $employee) {
                $i++;
                $t++;
                $taxEmployees[] = implode("||", $employee);
                if($i == $limite || $t == $total){
                    $taxEmployees = implode("|@|", $taxEmployees);

                    $return = array();

                    $userSession = GSec::getUserSession();
                    $mysql = new GDbMysql();
                    $param = array("sis",$content['files'][0]->name . ' - p'. $p, $userSession->getUsr_int_id(), $taxEmployees);

                    $mysql->execute("CALL sp_tax_employee_import(?,?,?, @p_status, @p_msg);", $param, false);
                    $mysql->execute("SELECT @p_status, @p_msg");
                    $mysql->fetch();
                    $return["status"] = ($mysql->res[0]) ? true : false;
                    $return["msg"] = $mysql->res[1];
                    if(!$return["status"]){
                        break;
                    }

                    $taxEmployees = array();
                    $i = 0;
                    $p++;
                }
            }
            $mysql->close();
        } catch (GDbException $e) {
            $return["status"] = false;
            $return["msg"] = $e->getError();
        }
        echo json_encode($return);
    break;
}
