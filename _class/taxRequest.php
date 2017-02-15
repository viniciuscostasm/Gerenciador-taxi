<?php
class TaxRequest{
	private $req_int_id;
	/* @var $user User */
	private $user;
	/* @var $taxEmployee TaxEmployee */
	private $taxEmployee;
	private $req_var_passenger;
	private $req_cha_type;
	/* @var $taxCostCenter TaxCostCenter */
	private $taxCostCenter;
	private $req_dat_date;
	private $req_var_hour;
	/* @var $taxMotive TaxMotive */
	private $taxMotive;

	private $req_var_addresssource;
	/* @var $taxCitySource TaxCity */
	private $taxCitySource;
	/* @var $taxDistrictSource TaxDistrict */
	private $taxDistrictSource;
	/* @var $taxZoneSource TaxZone */
	private $taxZoneSource;

	private $req_var_addressdestination;
	/* @var $taxCityDestination TaxCity */
	private $taxCityDestination;
	/* @var $taxDistrictDestination TaxDistrict */
	private $taxDistrictDestination;
	/* @var $taxZoneDestination TaxZone */
	private $taxZoneDestination;

	private $req_txt_comment;
	/* @var $taxRide TaxRide */
	private $taxRide;
	private $req_dec_value;


	public function getReq_int_id() {
		return $this->req_int_id;
	}

	public function setReq_int_id($req_int_id) {
		$this->req_int_id = $req_int_id;
	}

	/** @return User */
	public function getUser() {
		return $this->user;
	}

	/** @param User $user */
	public function setUser($user) {
		$this->user = $user;
	}

	/** @return TaxEmployee */
	public function getTaxEmployee() {
		return $this->taxEmployee;
	}

	/** @param TaxEmployee $taxEmployee */
	public function setTaxEmployee($taxEmployee) {
		$this->taxEmployee = $taxEmployee;
	}

	public function getReq_var_passenger() {
		return $this->req_var_passenger;
	}

	public function setReq_var_passenger($req_var_passenger) {
		$this->req_var_passenger = $req_var_passenger;
	}

	public function getReq_cha_type() {
		return $this->req_cha_type;
	}

	public function setReq_cha_type($req_cha_type) {
		$this->req_cha_type = $req_cha_type;
	}

	/** @return TaxCostCenter */
	public function getTaxCostCenter() {
		return $this->taxCostCenter;
	}

	/** @param TaxCostCenter $taxCostCenter */
	public function setTaxCostCenter($taxCostCenter) {
		$this->taxCostCenter = $taxCostCenter;
	}

	public function getReq_dat_date() {
		return $this->req_dat_date;
	}

	public function setReq_dat_date($req_dat_date) {
		$this->req_dat_date = $req_dat_date;
	}

	public function getReq_var_hour() {
		return $this->req_var_hour;
	}

	public function setReq_var_hour($req_var_hour) {
		$this->req_var_hour = $req_var_hour;
	}

	/** @return TaxMotive */
	public function getTaxMotive() {
		return $this->taxMotive;
	}

	/** @param TaxMotive $taxMotive */
	public function setTaxMotive($taxMotive) {
		$this->taxMotive = $taxMotive;
	}

	public function getReq_var_addresssource() {
		return $this->req_var_addresssource;
	}

	public function setReq_var_addresssource($req_var_addresssource) {
		$this->req_var_addresssource = $req_var_addresssource;
	}

	/** @return TaxCity */
	public function getTaxCitySource() {
		return $this->taxCitySource;
	}

	/** @param TaxCity $taxCity */
	public function setTaxCitySource($taxCitySource) {
		$this->taxCitySource = $taxCitySource;
	}

	/** @return TaxDistrict */
	public function getTaxDistrictSource() {
		return $this->taxDistrictSource;
	}

	/** @param TaxDistrict $taxDistrictSource */
	public function setTaxDistrictSource($taxDistrictSource) {
		$this->taxDistrictSource = $taxDistrictSource;
	}

	/** @return TaxZone */
	public function getTaxZoneSource() {
		return $this->taxZoneSource;
	}

	/** @param TaxZone $taxZoneSource */
	public function setTaxZoneSource($taxZoneSource) {
		$this->taxZoneSource = $taxZoneSource;
	}

	public function getReq_var_addressdestination() {
		return $this->req_var_addressdestination;
	}

	public function setReq_var_addressdestination($req_var_addressdestination) {
		$this->req_var_addressdestination = $req_var_addressdestination;
	}

	/** @return TaxCity */
	public function getTaxCityDestination() {
		return $this->taxCityDestination;
	}

	/** @param TaxCity $taxCityDestination */
	public function setTaxCityDestination($taxCityDestination) {
		$this->taxCityDestination = $taxCityDestination;
	}

	/** @return TaxDistrict */
	public function getTaxDistrictDestination() {
		return $this->taxDistrictDestination;
	}

	/** @param TaxDistrict $taxDistrictDestination */
	public function setTaxDistrictDestination($taxDistrictDestination) {
		$this->taxDistrictDestination = $taxDistrictDestination;
	}

	/** @return TaxZone */
	public function getTaxZoneDestination() {
		return $this->taxZoneDestination;
	}

	/** @param TaxZone $taxZoneDestination */
	public function setTaxZoneDestination($taxZoneDestination) {
		$this->taxZoneDestination = $taxZoneDestination;
	}

	public function getReq_txt_comment() {
		return $this->req_txt_comment;
	}

	public function setReq_txt_comment($req_txt_comment) {
		$this->req_txt_comment = $req_txt_comment;
	}

	/** @return TaxRide */
	public function getTaxRide() {
		return $this->taxRide;
	}

	/** @param TaxRide $taxRide */
	public function setTaxRide($taxRide) {
		$this->taxRide = $taxRide;
	}

	public function getReq_dec_value() {
		return $this->req_dec_value;
	}

	public function setReq_dec_value($req_dec_value) {
		$this->req_dec_value = $req_dec_value;
	}

}
