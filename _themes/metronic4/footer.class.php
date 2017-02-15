<?php

class GFooter extends GFooterParent {

    function show($isIframe = false) {
        $html = '';
        if (!$isIframe) {
            $html .= '';

            if (GSec::validarLogin()) {
                $html .= '</div>'; // .container
                $html .= '</div>'; // .page-container
                $html .= '</div>'; // .page-container

                $html .= '<div class="page-prefooter">';
                $html .= '</div>';

                $html .= '<div class="page-footer">';
                $html .= '<div class="container">';
                $html .= SYS_COPYRIGHT;                
                $html .= '</div>'; //.container
                $html .= '</div>'; //.page-footer
            } else {
                $html .= '<div class="copyright">';
                if (strlen(SYS_COPYRIGHT_URL) == 0) {
                    $html .= SYS_COPYRIGHT;
                } else {
                    $html .= '' . SYS_COPYRIGHT . '';
                }

                $html .= '</div>';
            }
            $html .= '</body>';
        } else {
            $html .= '</body>'; //body
        }

        echo $html;
        parent::show($isIframe);
    }

}

?>