<?php
class TaxTaxcompanyUser{
	private $tus_int_id;
	/* @var $taxTaxcompany TaxTaxcompany */
	private $taxTaxcompany;
	/* @var $user User */
	private $user;


	public function getTus_int_id() {
		return $this->tus_int_id;
	}

	public function setTus_int_id($tus_int_id) {
		$this->tus_int_id = $tus_int_id;
	}

	/** @return TaxTaxcompany */
	public function getTaxTaxcompany() {
		return $this->taxTaxcompany;
	}

	/** @param TaxTaxcompany $taxTaxcompany */
	public function setTaxTaxcompany($taxTaxcompany) {
		$this->taxTaxcompany = $taxTaxcompany;
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