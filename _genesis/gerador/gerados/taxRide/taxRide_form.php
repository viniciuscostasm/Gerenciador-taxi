<?php
$opt_txc_var_name = $mysql->executeCombo("SELECT txc_int_id, txc_var_name FROM tax_taxicompany ORDER BY txc_var_name;");
$opt_zon_var_name = $mysql->executeCombo("SELECT zon_int_id, zon_var_name FROM adm_zone ORDER BY zon_var_name;");
$opt_usr_var_name = $mysql->executeCombo("SELECT usr_int_idadd, usr_var_name FROM adm_user ORDER BY usr_var_name;");
$opt_usr_var_name = $mysql->executeCombo("SELECT usr_int_idarrival, usr_var_name FROM adm_user ORDER BY usr_var_name;");

	$html .= $form->addSelect("tco_cha_type", array("SCO" => "Source Continetal", "DCO" => "Destination Continental", "EXT" => "External"), "-1", "Type", array("class" => "combobox","validate" => "([~] != -1)|Obrigatório"));
	$html .= $form->addDateField("rid_dat_date", "Date", false, array("size" => "10"));
	$html .= $form->addInput("text", "rid_hou_hour", "Hour", array("class" => "input", "size" => "5", "maxlength" => "5"));
	$html .= $form->addSelect("txc_int_id", $opt_txc_var_name , "-1", "TaxTaxcompany", array("class" => "combobox"));
	$html .= $form->addSelect("rid_cha_status", array("PEN" => "Pending", "APR" => "Approved", "CLO" => "Closed"), "-1", "Status", array("class" => "combobox"));
	$html .= $form->addInput("text", "rid_int_passengers", "Passagers", array("class" => "input", "size" => "5", "maxlength" => "2","validate" => "required"));
	$html .= $form->addSelect("zon_int_id", $opt_zon_var_name , "-1", "Zone reference", array("class" => "combobox"));
	$html .= $form->addTextarea("zon_int_idlist", "", "List of zones idss", array("class" => "textarea", "cols" => "10", "rows" => "3"));
	$html .= $form->addTextarea("zon_var_namelist", "", "List of zones name", array("class" => "textarea", "cols" => "10", "rows" => "3"));
	$html .= $form->addTextarea("rid_txt_passengerlist", "", "Passenger list", array("class" => "textarea", "cols" => "10", "rows" => "3"));
	$html .= $form->addInput("text", "rid_hor_stopped", "Stopped Hours", array("class" => "input", "size" => "5", "maxlength" => "5"));
	$html .= $form->addSelect("usr_int_idadd", $opt_usr_var_name , "-1", "User", array("class" => "combobox"));
	$html .= $form->addSelect("usr_int_idarrival", $opt_usr_var_name , "-1", "User", array("class" => "combobox"));
	$html .= $form->addInput("text", "rid_var_plate", "License Plate", array("class" => "input", "size" => "10", "maxlength" => "8"));
	$html .= $form->addTextarea("rid_txt_comment", "", "Comments", array("class" => "textarea", "cols" => "10", "rows" => "3"));
	$html .= $form->addInput("text", "rid_var_driver", "Driver", array("class" => "input", "size" => "80", "maxlength" => "100"));
	$html .= $form->addInput("text", "rid_hou_arrival", "Arrival", array("class" => "input", "size" => "5", "maxlength" => "4"));

?>