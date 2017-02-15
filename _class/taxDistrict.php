<?php
class TaxDistrict{
	private $dis_int_id;
	/* @var $taxZone TaxZone */
	private $taxZone;
	/* @var $taxCity TaxCity */
	private $taxCity;
	private $dis_var_name;


	public function getDis_int_id() {
		return $this->dis_int_id;
	}

	public function setDis_int_id($dis_int_id) {
		$this->dis_int_id = $dis_int_id;
	}

	/** @return TaxZone */
	public function getTaxZone() {
		return $this->taxZone;
	}

	/** @param TaxZone $taxZone */
	public function setTaxZone($taxZone) {
		$this->taxZone = $taxZone;
	}

	/** @return TaxCity */
	public function getTaxCity() {
		return $this->taxCity;
	}

	/** @param TaxCity $taxCity */
	public function setTaxCity($taxCity) {
		$this->taxCity = $taxCity;
	}

	public function getDis_var_name() {
		return $this->dis_var_name;
	}

	public function setDis_var_name($dis_var_name) {
		$this->dis_var_name = $dis_var_name;
	}

}