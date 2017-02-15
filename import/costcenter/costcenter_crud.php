<?php
require_once("../../_inc/global.php");
require_once ROOT_SYS_THEME . 'plugins/jquery-file-upload/server/php/CustomUploadHandler.php';

switch ($_POST["acao"]) {
    case "insArq":
        $options = array(
            'upload_dir' => ROOT_UPLOAD . 'costcenter/',
            'upload_url' => URL_UPLOAD . 'costcenter/',
            'accept_file_types' => '/.+$/i',
            'max_file_size' => null,
            'min_file_size' => 1
        );



        $uploadHandler = new CustomUploadHandler($options);
        $content = $uploadHandler->getContent();
        $content['files'][0]->new_name = $uploadHandler->getNew_name();
        $content['path'][0] = ROOT_UPLOAD . 'costcenter/' . $uploadHandler->getNew_name();

        $centers = array_map('str_getcsv', file(ROOT_UPLOAD . 'costcenter/' . $uploadHandler->getNew_name()));
        array_shift($centers);

        unlink(ROOT_UPLOAD . 'costcenter/' . $uploadHandler->getNew_name());
        $taxCostCenters = array();

        foreach ($centers as $center) {
            $taxCostCenters[] = implode("||", $center);
        }

        $taxCostCenters = utf8_encode(implode("|@|", $taxCostCenters));

        $return = array();
        try{
            $userSession = GSec::getUserSession();
            $mysql = new GDbMysql();
            $param = array("sis",$content['files'][0]->name, $userSession->getUsr_int_id(), $taxCostCenters);
            $mysql->execute("CALL sp_tax_csv(?,?,?, @p_status, @p_msg);", $param, false);
            $mysql->execute("SELECT @p_status, @p_msg");
            $mysql->fetch();
            $return["status"] = ($mysql->res[0]) ? true : false;
            $return["msg"] = $mysql->res[1];
            $mysql->close();
        } catch (GDbException $e) {
            $return["status"] = false;
            $return["msg"] = $e->getError();
        }
        echo json_encode($return);
    break;
}