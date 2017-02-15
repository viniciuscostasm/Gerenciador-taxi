<?php
require('../fpdf.php');

$data = array();
for ($i=0; $i < 100; $i++) { 
	array_push($data, array('Pais ' . $i, 'Capital ' . $i, rand(), rand()));
}

$pdf = new FPDF();
$pdf->SetFont('Arial','',10);

// Cabeçalho
$cabecalho = array();
array_push($cabecalho, array('text' => 'País', 'width' => '50', 'align' => 'L'));
array_push($cabecalho, array('text' => 'Capital', 'width' => '50', 'align' => 'L'));
array_push($cabecalho, array('text' => 'Área', 'width' => '45', 'align' => 'R'));
array_push($cabecalho, array('text' => 'População', 'width' => '45', 'align' => 'R'));

$pdf->AddPage();
cabecalho ($pdf, $cabecalho);

// Dados
$fill = false;
foreach($data as $row) {
	// Cell (largura, altura, texto, borda[0-1][L-T-R-B], posicao apos[0-1-2], alinhamento[L-C-R], fill, link)
	$i = 0;
	foreach ($cabecalho as $coluna) {
		$pdf->Cell($coluna['width'], 6, $row[$i++], 'LR', 0, $coluna['align'], $fill);
	}
	$pdf->Ln();
	$fill = !$fill;
}
// Closing line
$pdf->Cell(190, 0, '', 'T');
$pdf->Ln();


$pdf->Output();




function cabecalho($pdf, $conteudo) {

	$pdf->SetFillColor(39,169,227);
	$pdf->SetTextColor(255);
	$pdf->SetDrawColor(39,169,227);
	$pdf->SetFont('','B');

	foreach ($conteudo as $coluna) {
		$width = empty($coluna['width']) ? 0 : $coluna['width'];
		$align = empty($coluna['align']) ? 'C' : $coluna['align'];
		$pdf->Cell($width, 7, utf8_decode($coluna['text']), 1, 0, $align, true);	
	}
	$pdf->Ln();

	$pdf->SetFillColor(224,235,255);
	$pdf->SetTextColor(0);
	$pdf->SetFont('');

}


?>
