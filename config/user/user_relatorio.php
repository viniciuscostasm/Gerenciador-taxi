<?php

require_once("../../_inc/global.php");

$html = '';
$mysql = new GDbMysql();
//-------------------------------- Filtros -----------------------------------//
$filter = new GFilter();
$arrFiltro = array();

$usr_var_name = $_POST['p__usr_var_name'];
$pro_int_id = $_POST['p__pro_int_id'];
$pro_int_id_text = $_POST['p__pro_int_id_text'];
$usr_cha_status = $_POST['p__usr_cha_status'];
$usr_cha_status_text = $_POST['p__usr_cha_status_text'];

if (!empty($usr_var_name)) {
    $filter->addFilter('AND', 'usr_var_name', 'LIKE', 's', '%' . str_replace(' ', '%', $usr_var_name) . '%');
    $arrFiltro["Name"] = $usr_var_name;
}
if (!empty($pro_int_id)) {
    $filter->addFilter('AND', 'pro_int_id', '=', 'i', $pro_int_id);
    $arrFiltro["Profile"] = $pro_int_id_text;
}
if (!empty($usr_cha_status)) {
    $filter->addFilter('AND', 'usr_cha_status', '=', 's', $usr_cha_status);
    $arrFiltro["Status"] = $usr_cha_status_text;
}
$filter->addClause("AND pro_cha_type NOT IN ('G')");
//-------------------------------- Filtros -----------------------------------//

try {

    $filter->setOrder(array('usr_var_name' => 'ASC'));
    $query = "SELECT usr_var_name, usr_var_email, pro_var_name, usr_var_status
                FROM vw_adm_user" . $filter->getWhere();
    $param = $filter->getParam();
    $mysql->execute($query, $param);
    if ($mysql->numRows() > 0) {
        $arrayDados = array();
        while ($mysql->fetch()) {
            $arrayDados[] = array(
                'nome' => $mysql->res['usr_var_name'],
                'email' => $mysql->res['usr_var_email'],
                'profile' => $mysql->res['pro_var_name'],
                'status' => $mysql->res['usr_var_status']
            );
        }
    }

    $title = 'Usuários';

    if ($_POST['p__tipo_exportar'] == 'pdf') {

        $cabecalho = array();
        array_push($cabecalho, array('text' => 'Name', 'width' => '55', 'align' => 'L'));
        array_push($cabecalho, array('text' => 'Email', 'width' => '55', 'align' => 'L'));
        array_push($cabecalho, array('text' => 'Profile', 'width' => '55', 'align' => 'L'));
        array_push($cabecalho, array('text' => 'Status', 'width' => '25', 'align' => 'L'));

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
                $pdf->Cell($cabecalho[1]['width'], 6, utf8_decode($arrayRegistro['email']), 'LRBT', 0, $cabecalho[1]['align'], $fill);
                $pdf->Cell($cabecalho[2]['width'], 6, utf8_decode($arrayRegistro['profile']), 'LRBT', 0, $cabecalho[2]['align'], $fill);
                $pdf->Cell($cabecalho[3]['width'], 6, utf8_decode($arrayRegistro['status']), 'LRBT', 0, $cabecalho[3]['align'], $fill);
                $pdf->Ln();
                $fill = !$fill;
            }

            $pdf->Output();
        }

    } else if ($_POST['p__tipo_exportar'] == 'xls') {

        $reportExcel = new ReportExcel($title);
        if (count($arrayDados) > 0) {
            $arrayCols = array(
                'A' => array('title' => 'Name', 'width' => '50'),
                'B' => array('title' => 'Email', 'width' => '50'),
                'C' => array('title' => 'Profile', 'width' => '50'),
                'D' => array('title' => 'Status', 'width' => '20')
            );
            $reportExcel->setHeader($arrayCols);
            $i = 5;
            foreach ($arrayDados as $arrayRegistro) {
                $reportExcel->objPHPExcel->getActiveSheet()
                        ->setCellValue('A' . $i, $arrayRegistro['nome'])
                        ->setCellValue('B' . $i, $arrayRegistro['email'])
                        ->setCellValue('C' . $i, $arrayRegistro['profile'])
                        ->setCellValue('D' . $i, $arrayRegistro['status']);
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