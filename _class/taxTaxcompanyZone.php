<?php
class TaxTaxcompanyZone{
	private $tzo_int_id;
	/* @var $taxTaxcompany TaxTaxcompany */
	private $taxTaxcompany;
	/* @var $taxZone TaxZone */
	private $taxZone;
	private $tzo_dec_value;


	public function getTzo_int_id() {
		return $this->tzo_int_id;
	}

	public function setTzo_int_id($tzo_int_id) {
		$this->tzo_int_id = $tzo_int_id;
	}

	/** @return TaxTaxcompany */
	public function getTaxTaxcompany() {
		return $this->taxTaxcompany;
	}

	/** @param TaxTaxcompany $taxTaxcompany */
	public function setTaxTaxcompany($taxTaxcompany) {
		$this->taxTaxcompany = $taxTaxcompany;
	}

	/** @return TaxZone */
	public function getTaxZone() {
		return $this->taxZone;
	}

	/** @param TaxZone $taxZone */
	public function setTaxZone($taxZone) {
		$this->taxZone = $taxZone;
	}

	public function getTzo_dec_value() {
		return $this->tzo_dec_value;
	}

	public function setTzo_dec_value($tzo_dec_value) {
		$this->tzo_dec_value = $tzo_dec_value;
	}

}