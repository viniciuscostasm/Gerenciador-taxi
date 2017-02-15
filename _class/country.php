<?php
class Country{
	private $cou_cha_country;
	private $cou_var_name;


	public function getCou_cha_country() {
		return $this->cou_cha_country;
	}

	public function setCou_cha_country($cou_cha_country) {
		$this->cou_cha_country = $cou_cha_country;
	}

	public function getCou_var_name() {
		return $this->cou_var_name;
	}

	public function setCou_var_name($cou_var_name) {
		$this->cou_var_name = $cou_var_name;
	}

}