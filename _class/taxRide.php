<?php
class TaxRide{
	private $rid_int_id;
	private $rid_cha_type;
	private $rid_dat_date;
	private $rid_hou_hour;
	private $rid_cha_status;
	private $rid_int_passengers;
	/* @var $zone reference Zone reference */
	private $zone;
	private $zon_int_idlist;
	private $zon_var_namelist;
	private $rid_txt_passengerlist;
	/* @var $taxTaxicompany TaxTaxicompany */
	private $taxTaxicompany;
	private $rid_dec_value;
	private $rid_dec_extreme;
	private $rid_hor_stopped;
	private $rid_dec_stopped;
	private $rid_dec_parking;
	private $rid_cha_transfer;
	private $rid_dec_transfer;
	private $rid_dec_total;
	/* @var $user User */
	private $user;
	private $rid_var_plate;
	private $rid_txt_comment;
	private $rid_var_driver;
	private $rid_dat_arrival;
	private $rid_hou_arrival;


	public function getRid_int_id() {
		return $this->rid_int_id;
	}

	public function setRid_int_id($rid_int_id) {
		$this->rid_int_id = $rid_int_id;
	}

	public function getRid_cha_type() {
		return $this->rid_cha_type;
	}

	public function setRid_cha_type($rid_cha_type) {
		$this->rid_cha_type = $rid_cha_type;
	}

	public function getRid_dat_date() {
		return $this->rid_dat_date;
	}

	public function setRid_dat_date($rid_dat_date) {
		$this->rid_dat_date = $rid_dat_date;
	}

	public function getRid_hou_hour() {
		return $this->rid_hou_hour;
	}

	public function setRid_hou_hour($rid_hou_hour) {
		$this->rid_hou_hour = $rid_hou_hour;
	}

	public function getRid_cha_status() {
		return $this->rid_cha_status;
	}

	public function setRid_cha_status($rid_cha_status) {
		$this->rid_cha_status = $rid_cha_status;
	}

	public function getRid_int_passengers() {
		return $this->rid_int_passengers;
	}

	public function setRid_int_passengers($rid_int_passengers) {
		$this->rid_int_passengers = $rid_int_passengers;
	}

	/** @return Zone reference */
	public function getZone() {
		return $this->zone;
	}

	/** @param Zone reference $zone reference */
	public function setZone($zone) {
		$this->zone = $zone;
	}

	public function getZon_int_idlist() {
		return $this->zon_int_idlist;
	}

	public function setZon_int_idlist($zon_int_idlist) {
		$this->zon_int_idlist = $zon_int_idlist;
	}

	public function getZon_var_namelist() {
		return $this->zon_var_namelist;
	}

	public function setZon_var_namelist($zon_var_namelist) {
		$this->zon_var_namelist = $zon_var_namelist;
	}

	public function getRid_txt_passengerlist() {
		return $this->rid_txt_passengerlist;
	}

	public function setRid_txt_passengerlist($rid_txt_passengerlist) {
		$this->rid_txt_passengerlist = $rid_txt_passengerlist;
	}

	/** @return TaxTaxicompany */
	public function getTaxTaxicompany() {
		return $this->taxTaxicompany;
	}

	/** @param TaxTaxicompany $taxTaxicompany */
	public function setTaxTaxicompany($taxTaxicompany) {
		$this->taxTaxicompany = $taxTaxicompany;
	}

	public function getRid_dec_value() {
		return $this->rid_dec_value;
	}

	public function setRid_dec_value($rid_dec_value) {
		$this->rid_dec_value = $rid_dec_value;
	}

	public function getRid_dec_extreme() {
		return $this->rid_dec_extreme;
	}

	public function setRid_dec_extreme($rid_dec_extreme) {
		$this->rid_dec_extreme = $rid_dec_extreme;
	}

	public function getRid_hor_stopped() {
		return $this->rid_hor_stopped;
	}

	public function setRid_hor_stopped($rid_hor_stopped) {
		$this->rid_hor_stopped = $rid_hor_stopped;
	}

	public function getRid_dec_stopped() {
		return $this->rid_dec_stopped;
	}

	public function setRid_dec_stopped($rid_dec_stopped) {
		$this->rid_dec_stopped = $rid_dec_stopped;
	}

	public function getRid_dec_parking() {
		return $this->rid_dec_parking;
	}

	public function setRid_dec_parking($rid_dec_parking) {
		$this->rid_dec_parking = $rid_dec_parking;
	}
	
	public function getRid_dec_stoppedhour() {
		return $this->rid_dec_stoppedhour;
	}

	public function setRid_dec_stoppedhour($rid_dec_stoppedhour) {
		$this->rid_dec_stoppedhour = $rid_dec_stoppedhour;
	}

	public function getRid_cha_transfer() {
		return $this->rid_cha_transfer;
	}

	public function setRid_cha_transfer($rid_cha_transfer) {
		$this->rid_cha_transfer = $rid_cha_transfer;
	}

	public function getRid_dec_transfer() {
		return $this->rid_dec_transfer;
	}

	public function setRid_dec_transfer($rid_dec_transfer) {
		$this->rid_dec_transfer = $rid_dec_transfer;
	}

	public function getRid_dec_total() {
		return $this->rid_dec_total;
	}

	public function setRid_dec_total($rid_dec_total) {
		$this->rid_dec_total = $rid_dec_total;
	}

	/** @return User */
	public function getUser() {
		return $this->user;
	}

	/** @param User $user */
	public function setUser($user) {
		$this->user = $user;
	}

	public function getRid_var_plate() {
		return $this->rid_var_plate;
	}

	public function setRid_var_plate($rid_var_plate) {
		$this->rid_var_plate = $rid_var_plate;
	}

	public function getRid_txt_comment() {
		return $this->rid_txt_comment;
	}

	public function setRid_txt_comment($rid_txt_comment) {
		$this->rid_txt_comment = $rid_txt_comment;
	}

	public function getRid_var_driver() {
		return $this->rid_var_driver;
	}

	public function setRid_var_driver($rid_var_driver) {
		$this->rid_var_driver = $rid_var_driver;
	}

	public function getRid_dat_arrival() {
		return $this->rid_dat_arrival;
	}

	public function setRid_dat_arrival($rid_dat_arrival) {
		$this->rid_dat_arrival = $rid_dat_arrival;
	}

	public function getRid_hou_arrival() {
		return $this->rid_hou_arrival;
	}

	public function setRid_hou_arrival($rid_hou_arrival) {
		$this->rid_hou_arrival = $rid_hou_arrival;
	}

}