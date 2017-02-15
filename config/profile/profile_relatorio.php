<?php

require_once("../../_inc/global.php");

$html = '';
$mysql = new GDbMysql();
//-------------------------------- Filtros -----------------------------------//
$filter = new GFilter();
$arrFiltro = array();

$pro_var_sigla = $_POST['p__pro_var_sigla'];
$pro_var_name = $_POST['p__pro_var_name'];

if (!empty($pro_var_name)) {
    $filter->addFilter('AND', 'pro_var_name', 'LIKE', 's', '%' . str_replace(' ', '%', $pro_var_name) . '%');
    $arrFiltro['Name'] = $pro_var_name;
}
$filter->setOrder(array($sortname => $sortorder));
//-------------------------------- Filtros -----------------------------------//

try {

    $filter->setOrder(array('pro_var_name' => 'ASC'));
    $query = "SELECT pro_int_id, pro_var_name
                FROM vw_adm_profile" . $filter->getWhere();
    $param = $filter->getParam();
    $mysql->execute($query, $param);
    if ($mysql->numRows() > 0) {
        $arrayDados = array();
        while ($mysql->fetch()) {
            $arrayDados[] = array(
                'nome' => $mysql->res['pro_var_name']
            );
        }
    }

    $title = 'Perfis de acesso';

    if ($_POST['p__tipo_exportar'] == 'pdf') {

        $cabecalho = array();
        array_push($cabecalho, array('text' => 'Name', 'width' => '190', 'align' => 'L'));

        if (count($arrayDados) > 0) {

            $pdf = new ReportFPDF();
            $pdf->titleReport = $title;
            $fontSize = 8;
            $pdf->SetFont('Arial','',$fontSize);
            $pdf->AddPage();

            // Exibição dos filtros
            if ($arrFiltro) {
                foreach ($arrFiltro as $campo => $conteudo) {
                    $pdf->Cell(22, 5, utf8_decode($campo . ':'));
                    $pdf->Cell(160, 5, utf8_decode($conteudo));
                    $pdf->Ln();
                }
                $pdf->Ln();
            }

            cabecalhoFPDF($pdf, $cabecalho);

            $fill = 0;
            foreach ($arrayDados as $arrayRegistro) {
                $pdf->Cell($cabecalho[0]['width'], 6, utf8_decode($arrayRegistro['nome']), 'LRBT', 0, $cabecalho[0]['align'], $fill);
                $pdf->Ln();
                $fill = !$fill;
            }

            $pdf->Output();
        }

    } else if ($_POST['p__tipo_exportar'] == 'xls') {

        $reportExcel = new ReportExcel($title);
        if (count($arrayDados) > 0) {
            $arrayCols = array(
                'A' => array('title' => 'Name', 'width' => '50')
            );
            $reportExcel->setHeader($arrayCols);
            $i = 5;
            foreach ($arrayDados as $arrayRegistro) {
                $reportExcel->objPHPExcel->getActiveSheet()
                        ->setCellValue('A' . $i, $arrayRegistro['nome']);
                $i++;
            }
        } else {
            $reportExcel->objPHPExcel->getActiveSheet()
                    ->setCellValue('A4', 'No results found.');
        }
        $reportExcel->export();

    }
} catch (GDbException $exc) {
    echo $exc->getError();
}
exit();