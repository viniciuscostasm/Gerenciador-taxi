<?php

	$html .= $form->addInput("text", "coc_var_key", "Key", array("class" => "input", "size" => "10", "maxlength" => "10","validate" => "required"));
	$html .= $form->addInput("text", "coc_var_name", "Name", array("class" => "input", "size" => "60", "maxlength" => "50","validate" => "required"));

?>