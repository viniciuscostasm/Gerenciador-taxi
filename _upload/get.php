<?php

require_once '../_inc/global.php';

try {
	$arq_var_key = $_GET['p'];
	list($env_int_id, $arq_var_key_parcial) = explode('-', $arq_var_key);

	$mysql = new GDbMysql();

	if(!empty($arq_var_key) && file_exists($env_int_id . '/' . $arq_var_key)){
		$local = $_GET['l'];
		if($local == 'cotacao'){
			$query = "SELECT arc_var_arquivo, arc_var_extensao, arc_int_bytes 
						FROM vw_com_arquivocotacao 
					   WHERE arc_var_key = ?
					     AND env_int_id = ?";
			$param = array('si', $arq_var_key, $env_int_id);

		} else {
			$query = "SELECT arq_var_arquivo, arq_var_extensao, arq_int_bytes 
						FROM vw_com_arquivo 
					   WHERE arq_var_key = ?
					     AND env_int_id = ?";
			$param = array('si', $arq_var_key, $env_int_id);
		}
			
		$mysql->execute($query, $param);
		if($mysql->fetch()){
			$arq_var_arquivo = $mysql->res[0];
			$arq_var_extensao = $mysql->res[1];
			$arq_int_bytes = $mysql->res[2];

			$finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
			$contentType = finfo_file($finfo, $env_int_id . '/' . $arq_var_key);

			header('Content-Type: ' . $contentType);
			header('Content-Length: ' . $arq_int_bytes);
			header('Content-Disposition: filename=' . urlencode($arq_var_arquivo));

			// Caso queira forçar o donwload basta descomentar
			// header('Content-Disposition: attachment; filename=' . urlencode($arq_var_arquivo));

			echo readfile($env_int_id . '/' . $arq_var_key);
		} else {
			echo utf8_decode('Arquivo ainda está em temporário ou não existe.');
		}
	} else {
		echo 'Arquivo inexistente';
	}
} catch (Exception $e) {
	
}

