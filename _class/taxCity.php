<?php
class TaxCity{
	private $cit_int_id;
	private $cit_var_name;
	private $cit_cha_uf;


	public function getCit_int_id() {
		return $this->cit_int_id;
	}

	public function setCit_int_id($cit_int_id) {
		$this->cit_int_id = $cit_int_id;
	}

	public function getCit_var_name() {
		return $this->cit_var_name;
	}

	public function setCit_var_name($cit_var_name) {
		$this->cit_var_name = $cit_var_name;
	}

	public function getCit_cha_uf() {
		return $this->cit_cha_uf;
	}

	public function setCit_cha_uf($cit_cha_uf) {
		$this->cit_cha_uf = $cit_cha_uf;
	}

}