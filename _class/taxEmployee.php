<?php
class TaxEmployee{
	private $emp_int_id;
	/* @var $taxCostcenter TaxCostcenter */
	private $taxCostcenter;
	private $emp_var_key;
	private $emp_var_name;
	private $emp_var_address;
	private $emp_var_cep;
	/* @var $taxCity TaxCity */
	private $taxCity;
	/* @var $taxDistrict TaxDistrict */
	private $taxDistrict;


	public function getEmp_int_id() {
		return $this->emp_int_id;
	}

	public function setEmp_int_id($emp_int_id) {
		$this->emp_int_id = $emp_int_id;
	}

	/** @return TaxCostcenter */
	public function getTaxCostcenter() {
		return $this->taxCostcenter;
	}

	/** @param TaxCostcenter $taxCostcenter */
	public function setTaxCostcenter($taxCostcenter) {
		$this->taxCostcenter = $taxCostcenter;
	}

	public function getEmp_var_key() {
		return $this->emp_var_key;
	}

	public function setEmp_var_key($emp_var_key) {
		$this->emp_var_key = $emp_var_key;
	}

	public function getEmp_var_name() {
		return $this->emp_var_name;
	}

	public function setEmp_var_name($emp_var_name) {
		$this->emp_var_name = $emp_var_name;
	}

	public function getEmp_var_address() {
		return $this->emp_var_address;
	}

	public function setEmp_var_address($emp_var_address) {
		$this->emp_var_address = $emp_var_address;
	}

	public function getEmp_var_cep() {
		return $this->emp_var_cep;
	}

	public function setEmp_var_cep($emp_var_cep) {
		$this->emp_var_cep = $emp_var_cep;
	}

	/** @return TaxCity */
	public function getTaxCity() {
		return $this->taxCity;
	}

	/** @param TaxCity $taxCity */
	public function setTaxCity($taxCity) {
		$this->taxCity = $taxCity;
	}

	/** @return TaxDistrict */
	public function getTaxDistrict() {
		return $this->taxDistrict;
	}

	/** @param TaxDistrict $taxDistrict */
	public function setTaxDistrict($taxDistrict) {
		$this->taxDistrict = $taxDistrict;
	}

}