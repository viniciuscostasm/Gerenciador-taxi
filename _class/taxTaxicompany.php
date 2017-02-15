<?php
class TaxTaxicompany{
	private $txc_int_id;
	private $txc_var_name;
	private $txc_dec_valuestopped;
	private $txc_dec_valueextreme;
	private $txc_dec_valuetransfer;


	public function getTxc_int_id() {
		return $this->txc_int_id;
	}

	public function setTxc_int_id($txc_int_id) {
		$this->txc_int_id = $txc_int_id;
	}

	public function getTxc_var_name() {
		return $this->txc_var_name;
	}

	public function setTxc_var_name($txc_var_name) {
		$this->txc_var_name = $txc_var_name;
	}

	public function getTxc_dec_valuestopped() {
		return $this->txc_dec_valuestopped;
	}

	public function setTxc_dec_valuestopped($txc_dec_valuestopped) {
		$this->txc_dec_valuestopped = $txc_dec_valuestopped;
	}

	public function getTxc_dec_valueextreme() {
		return $this->txc_dec_valueextreme;
	}

	public function setTxc_dec_valueextreme($txc_dec_valueextreme) {
		$this->txc_dec_valueextreme = $txc_dec_valueextreme;
	}

	public function getTxc_dec_valuetransfer() {
		return $this->txc_dec_valuetransfer;
	}

	public function setTxc_dec_valuetransfer($txc_dec_valuetransfer) {
		$this->txc_dec_valuetransfer = $txc_dec_valuetransfer;
	}
}