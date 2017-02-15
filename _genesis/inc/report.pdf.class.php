<?php

class ReportPdf {

    public $header;
    public $footer;
    public $html;
    public $heightHeader = 50;
    public $css;
    public $title;
    public $reportTitle;

    /**
     * Classe para a geração de relatórios utilizando o mPDF
     *
     * @param string $title titulo do relatório e do arquivo
     * @param string $html código html com o conteudo que será exbido
     * @param array $filter array com os filtros
     */
    function __construct($title, $html, $filter = array(), $reportTitle = '') {
        require_once(ROOT_GENESIS . "inc/mpdf/mpdf.php");

        $this->html = $html;
        $this->title = $title;
        $this->reportTitle = empty($reportTitle) ? $this->title : $reportTitle;

//        $this->header .= '<div id="header">';
//        $this->header .= '<img src="' . URL_UPLOAD . 'environment/' . GSec::getEnvironmentSession()->getEnv_int_id() . '-logo.png" style="max-height: 40px;" />';
//        $this->header .= '<h2 class="tituloRelatorio">' . $this->title . '</h2>';
//        $this->header .= '<div id="environment">' . GSec::getEnvironmentSession()->getEnv_var_name() . '</div>';

        $this->header .= '<div id="header">';
        $this->header .= '<div id="topo" class="sepH_a">';

        $this->header .= '<table width="100%"><tr>';
        //$this->header .= '<td width="30%"><img src="' . URL_UPLOAD . 'environment/' . GSec::getEnvironmentSession()->getEnv_int_id() . '-logo.png" style="max-height: 75px;max-width: 250px;" /></td>';
        $env_var_logomarca = GSec::getEnvironmentSession()->getEnv_var_logomarca();
        $img = (!empty($env_var_logomarca)) ? '<img src="' . getUrlLogomarca($env_var_logomarca) . '" style="max-height: 75px;max-width: 250px;" />' : '';

        $env_var_telefone2 = GSec::getEnvironmentSession()->getEnv_var_telefone2();
        $env_var_telefone3 = GSec::getEnvironmentSession()->getEnv_var_telefone3();

        $telefones = GSec::getEnvironmentSession()->getEnv_var_telefone();
        $telefones .= (!empty($env_var_telefone2)) ? ' - ' . $env_var_telefone2 : '';
        $telefones .= (!empty($env_var_telefone3)) ? ' - ' . $env_var_telefone3 : '';

        $this->header .= '<td width="30%">' . $img . '</td>';
        $this->header .= '<td width="70%" align="right">';
        $this->header .= '<div id="environment">' . GSec::getEnvironmentSession()->getEnv_var_name() . '</div>';
        $this->header .= '<div id="environmentEndereco">' . GSec::getEnvironmentSession()->getEndereco()->toString() . '</div>';
        $this->header .= '<div id="environmentTelefone">' . $telefones . '</div>';
        $this->header .= '</td>';
        $this->header .= '</tr></table>';

        $this->header .= '</div>'; //#topo
        $this->header .= '<div id="nomeRelatorio">' . $this->reportTitle . '</div>';
        $this->header .= '</div>';


        if (count($filter) > 0) {
            $this->header .= '<table class="table" style="border-top:1px solid #DDDDDD;">';
            $i = 0;
            foreach ($filter as $desc => $value) {
                if (($i % 3) == 0) {
                    $this->header.= '<tr>';
                    $this->heightHeader += 10;
                }
                $this->header .= '<td width="33.3%" style="border:none">' . $desc . ': ' . $value . '</td>';
                if (($i % 3) == 3) {
                    $this->header .= '</tr>';
                }
                $i++;
            }
            $this->header .='</table>';
        }
//        $this->header .= '</div>';

        $this->footer .= '<table id="footer" class="table">';
        $this->footer .= '<tr>';
        $this->footer .= '<td>Emitido em: ' . date('d/m/Y H:i') . '</td>';
        $this->footer .= '<td>Por ' . GSec::getUserSession()->getUsr_var_name() . '</td>';
        $this->footer .= '<td align="right">Pág. {PAGENO} / {nb}</td>';
        $this->footer .= '</tr>';
        $this->footer .= '</table>';
    }

    /**
    * @param string $dest default: 'I'
    * I: send the file inline to the browser. The plug-in is used if available. The name given by filename is used when one selects the "Save as" option on the link generating the PDF.
    * D: send to the browser and force a file download with the name given by filename.
    * F: save to a local file with the name given by filename (may include a path).
    * S: return the document as a string. filename is ignored.
    */
    function export($dest = 'I') {
        error_reporting(~E_ALL);

        $mpdf = new mPDF('c', 'A4', '', '', 10, 10, $this->heightHeader, 20, 10, 10);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->list_indent_first_level = 0;
        $mpdf->charset_in = 'UTF-8';

        $mpdf->SetTitle($this->title);

        $mpdf->SetHTMLHeader($this->header);
        $mpdf->SetHTMLFooter($this->footer);

//        $mpdf->showImageErrors = true;

        $stylesheet = file_get_contents(ROOT_SYS_THEME . '_css/relatorio.css');
        if (!empty($this->css)) {
            $stylesheet .= file_get_contents($this->css);
        }
        $mpdf->WriteHTML($stylesheet, 1);

        $mpdf->WriteHTML($this->html, 2);
//        $mpdf->Output();
        $mpdf->Output($this->title . '.pdf', $dest);
    }

    public function setCss($css) {
        $this->css = $css;
    }

}