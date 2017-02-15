<?php
class TaxMotive{
	private $mot_int_id;
	private $mot_var_name;


	public function getMot_int_id() {
		return $this->mot_int_id;
	}

	public function setMot_int_id($mot_int_id) {
		$this->mot_int_id = $mot_int_id;
	}

	public function getMot_var_name() {
		return $this->mot_var_name;
	}

	public function setMot_var_name($mot_var_name) {
		$this->mot_var_name = $mot_var_name;
	}

}