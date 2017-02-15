<?php
$opt_coc_var_name = $mysql->executeCombo("SELECT coc_int_id, coc_var_name FROM tax_costcenter ORDER BY coc_var_name;");
$opt_cit_var_name = $mysql->executeCombo("SELECT cit_int_id, cit_var_name FROM tax_city ORDER BY cit_var_name;");
$opt_dis_var_name = $mysql->executeCombo("SELECT dis_int_id, dis_var_name FROM tax_district ORDER BY dis_var_name;");

	$html .= $form->addSelect("coc_int_id", $opt_coc_var_name , "-1", "TaxCostcenter", array("class" => "combobox"));
	$html .= $form->addInput("text", "emp_var_key", "Key", array("class" => "input", "size" => "20", "maxlength" => "20","validate" => "required"));
	$html .= $form->addInput("text", "emp_var_name", "Name", array("class" => "input", "size" => "80", "maxlength" => "100","validate" => "required"));
	$html .= $form->addInput("text", "emp_var_address", "Address", array("class" => "input", "size" => "80", "maxlength" => "255","validate" => "required"));
	$html .= $form->addInput("text", "emp_var_cep", "CEP", array("class" => "input", "size" => "10", "maxlength" => "10","validate" => "required"));
	$html .= $form->addSelect("cit_int_id", $opt_cit_var_name , "-1", "TaxCity", array("class" => "combobox"));
	$html .= $form->addSelect("dis_int_id", $opt_dis_var_name , "-1", "TaxDistrict", array("class" => "combobox"));

?>