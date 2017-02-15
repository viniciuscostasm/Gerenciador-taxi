<?php

class ReportExcel {

    public $objPHPExcel;
    private $arrayAlignCols = array();

    /**
     * Classe para a geração de relatórios utilizando o PHPExcel
     * 
     * @param string $title titulo do relatório e do arquivo
     */
    function __construct($title) {
        require_once(ROOT_GENESIS . "inc/PHPExcel/PHPExcel.php");

        $this->title = $title;

        $this->objPHPExcel = new PHPExcel();
        $this->objPHPExcel->setActiveSheetIndex(0);

        $this->objPHPExcel->getProperties()->setCreator(SYS_TITLE)
                ->setLastModifiedBy(SYS_TITLE)
                ->setTitle($this->title);

        $this->objPHPExcel->getActiveSheet()
                ->setCellValue('A1', $this->title)
                ->setCellValue('A2', GSec::getEnvironmentSession()->getEnv_var_name());

        //<editor-fold desc="Formatação das colunas do topo">
        $styleArray = array(
            'font' => array(
                'bold' => true,
            )
        );
        $this->objPHPExcel->getActiveSheet()->getStyle('A1:Z4')->applyFromArray($styleArray);

        $this->objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setSize(16);
        $this->objPHPExcel->getActiveSheet()->getStyle("A2")->getFont()->setSize(11);

        $this->objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(17);
        $this->objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(25);
        $this->objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);
        //</editor-fold>
    }

    function setHeader($arrayCols) {
        foreach ($arrayCols as $col => $arrayLine) {
            $this->objPHPExcel->getActiveSheet()
                    ->setCellValue($col . '4', $arrayLine['title']);

            //<editor-fold desc="Formatação das colunas do título">
            $this->objPHPExcel->getActiveSheet()
                    ->getColumnDimension($col)
                    ->setWidth($arrayLine['width']);

            $this->objPHPExcel->getActiveSheet()->getStyle($col . '4')->getFill()
                    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFE1E1E1');
            //</editor-fold>
            //Preenchimento do array de alinhamento
            if (!empty($arrayLine['align'])) {
                $this->arrayAlignCols[$col] = $arrayLine['align'];
            }
        }

        //Merge das colunas de título
        $this->objPHPExcel->getActiveSheet()->mergeCells('A1:' . $col . '1');
        $this->objPHPExcel->getActiveSheet()->mergeCells('A2:' . $col . '2');
    }

    function export() {
        //<editor-fold desc="Alinhamento de todas as linhas das colunas marcadas para alinhas">
        $total = $this->objPHPExcel->getActiveSheet()->getHighestRow();
        if (count($this->arrayAlignCols) > 0) {
            foreach ($this->arrayAlignCols as $col => $align) {
                $this->objPHPExcel->getActiveSheet()->getStyle($col . '4:' . $col . $total)->getAlignment()->setHorizontal($align);
            }
        }
        //</editor-fold>

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $this->title . '.xls"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

}