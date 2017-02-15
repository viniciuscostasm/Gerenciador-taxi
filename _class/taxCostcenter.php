<?php
class TaxCostcenter{
	private $coc_int_id;
	private $coc_var_key;
	private $coc_var_name;


	public function getCoc_int_id() {
		return $this->coc_int_id;
	}

	public function setCoc_int_id($coc_int_id) {
		$this->coc_int_id = $coc_int_id;
	}

	public function getCoc_var_key() {
		return $this->coc_var_key;
	}

	public function setCoc_var_key($coc_var_key) {
		$this->coc_var_key = $coc_var_key;
	}

	public function getCoc_var_name() {
		return $this->coc_var_name;
	}

	public function setCoc_var_name($coc_var_name) {
		$this->coc_var_name = $coc_var_name;
	}

}