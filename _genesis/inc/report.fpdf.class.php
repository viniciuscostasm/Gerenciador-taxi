<?php

require_once(ROOT_GENESIS . "inc/fpdf/fpdf.php");

class ReportFPDF extends FPDF {
	public $titleReport;
	public $env_int_id;

	function Header() {
		$environmentSession = GSec::getEnvironmentSession();
		if(empty($this->env_int_id)){
			$this->env_int_id = $environmentSession->getEnv_int_id();
		}
		$this->Image(URL_SYS_THEME . '_img/logo-interna.png', 10, 11, null, 7);

    	//Arial bold 15
		$this->SetFont('Arial','B',15);
    	// Title
		$this->Cell(190, 10, utf8_decode($this->titleReport), 0, 0, 'C');
    	// Line break
		$this->Ln(15);
	}

	// Page footer
	function Footer() {
		$this->SetY(-15);
		$this->SetFont('Arial','I', 8);
		// $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo(), 0, 0, 'C');
	}

}