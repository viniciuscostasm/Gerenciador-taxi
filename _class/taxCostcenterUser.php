<?php
class TaxCostcenterUser{
	private $cus_int_id;
	/* @var $taxCostcenter TaxCostcenter */
	private $taxCostcenter;
	/* @var $user User */
	private $user;


	public function getCus_int_id() {
		return $this->cus_int_id;
	}

	public function setCus_int_id($cus_int_id) {
		$this->cus_int_id = $cus_int_id;
	}

	/** @return TaxCostcenter */
	public function getTaxCostcenter() {
		return $this->taxCostcenter;
	}

	/** @param TaxCostcenter $taxCostcenter */
	public function setTaxCostcenter($taxCostcenter) {
		$this->taxCostcenter = $taxCostcenter;
	}

	/** @return User */
	public function getUser() {
		return $this->user;
	}

	/** @param User $user */
	public function setUser($user) {
		$this->user = $user;
	}

}