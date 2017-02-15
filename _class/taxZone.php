<?php
class TaxZone{
	private $zon_int_id;
	private $zon_var_name;


	public function getZon_int_id() {
		return $this->zon_int_id;
	}

	public function setZon_int_id($zon_int_id) {
		$this->zon_int_id = $zon_int_id;
	}

	public function getZon_int_idassociated() {
		return $this->zon_int_idassociated;
	}

	public function setZon_int_idassociated($zon_int_idassociated) {
		$this->zon_int_idassociated = $zon_int_idassociated;
	}

	public function getZon_var_name() {
		return $this->zon_var_name;
	}

	public function setZon_var_name($zon_var_name) {
		$this->zon_var_name = $zon_var_name;
	}

}