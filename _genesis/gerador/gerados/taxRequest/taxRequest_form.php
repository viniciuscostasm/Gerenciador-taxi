<?php
$opt_usr_int_id = $mysql->executeCombo("SELECT usr_int_id, usr_int_id FROM adm_user ORDER BY usr_int_id;");
$opt_emp_int_id = $mysql->executeCombo("SELECT emp_int_id, emp_int_id FROM tax_employee ORDER BY emp_int_id;");
$opt_cit_int_id = $mysql->executeCombo("SELECT cit_int_id, cit_int_id FROM tax_city ORDER BY cit_int_id;");
$opt_dis_int_id = $mysql->executeCombo("SELECT dis_int_id, dis_int_id FROM tax_district ORDER BY dis_int_id;");
$opt_zon_int_id = $mysql->executeCombo("SELECT zon_int_id, zon_int_id FROM tax_zone ORDER BY zon_int_id;");
$opt_mot_int_id = $mysql->executeCombo("SELECT mot_int_id, mot_int_id FROM tax_motive ORDER BY mot_int_id;");

	$html .= $form->addSelect("usr_int_id", $opt_usr_int_id , "-1", "User", array("class" => "combobox","validate" => "([~] != -1)|Obrigatório"));
	$html .= $form->addSelect("req_cha_origin", array("C" => "Continental", "O" => "Other"), "-1", "Origin", array("class" => "combobox","validate" => "([~] != -1)|Obrigatório"));
	$html .= $form->addDateField("req_dat_date", "Date", false, array("size" => "10"));
	$html .= $form->addInput("text", "req_var_hour", "Hour", array("class" => "input", "size" => "5", "maxlength" => "5","validate" => "required"));
	$html .= $form->addSelect("emp_int_id", $opt_emp_int_id , "-1", "TaxEmployee", array("class" => "combobox"));
	$html .= $form->addInput("text", "req_var_passenger", "Passenger", array("class" => "input", "size" => "80", "maxlength" => "100","validate" => "required"));
	$html .= $form->addInput("text", "req_var_address", "Address", array("class" => "input", "size" => "80", "maxlength" => "255","validate" => "required"));
	$html .= $form->addSelect("cit_int_id", $opt_cit_int_id , "-1", "TaxCity", array("class" => "combobox","validate" => "([~] != -1)|Obrigatório"));
	$html .= $form->addSelect("dis_int_id", $opt_dis_int_id , "-1", "TaxDistrict", array("class" => "combobox","validate" => "([~] != -1)|Obrigatório"));
	$html .= $form->addSelect("zon_int_id", $opt_zon_int_id , "-1", "TaxZone", array("class" => "combobox","validate" => "([~] != -1)|Obrigatório"));
	$html .= $form->addSelect("mot_int_id", $opt_mot_int_id , "-1", "TaxMotive", array("class" => "combobox","validate" => "([~] != -1)|Obrigatório"));
	$html .= $form->addTextarea("req_txt_comment", "", "Obs", array("class" => "textarea", "cols" => "10", "rows" => "3"));

?>