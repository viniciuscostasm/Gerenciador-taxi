<?php

/**
 * Carregar um array com as bibliotecas do tema
 *
 * @return array bibliotecas
 */
function getLibsTheme() {
    return array(
        'jquery' => array(
            URL_SYS_THEME . 'plugins/jquery.min.js',
            URL_SYS_THEME . 'plugins/jquery-migrate.min.js',
        ),
        'jqueryui' => array(
            URL_SYS_THEME . 'plugins/jquery-ui/jquery-ui-1.10.1.custom.min.js'
        ),
        'cookie' => array(
            URL_SYS_THEME . 'plugins/jquery.cookie.min.js'
        ),
        'breakpoints' => array(
            URL_SYS_THEME . 'plugins/breakpoints/breakpoints.js'
        ),
        'tagsinput' => array(
            URL_SYS_THEME . 'plugins/jquery-tags-input/jquery.tagsinput.min.js',
            URL_SYS_THEME . 'plugins/jquery-tags-input/jquery.tagsinput.css'
        ),
        'tinymce' => array(
            URL_SYS_THEME . '_js/tiny_mce/js/tinymce/tinymce.min.js',
        ),
        'datepicker' => array(
            URL_SYS_THEME . 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
            URL_SYS_THEME . 'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.pt-BR.min.js',
            // URL_SYS_THEME . 'plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css',
            URL_SYS_THEME . 'plugins/bootstrap-datepicker/css/bootstrap-datepicker3.standalone.css'
        ),
        'datetimepicker' => array(
            URL_SYS_THEME . 'plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js',
            URL_SYS_THEME . 'plugins/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.pt-BR.js',
            URL_SYS_THEME . 'plugins/bootstrap-datetimepicker/css/datetimepicker.css'
        ),
        'daterangepicker' => array(
            URL_SYS_THEME . 'plugins/bootstrap-daterangepicker/date.js',
            URL_SYS_THEME . 'plugins/bootstrap-daterangepicker/time.js',
            URL_SYS_THEME . 'plugins/bootstrap-daterangepicker/moment.min.js',
            URL_SYS_THEME . 'plugins/bootstrap-daterangepicker/daterangepicker.js',
            URL_SYS_THEME . 'plugins/bootstrap-daterangepicker/daterangepicker-bs3.css'
        ),
        'select2' => array(
            URL_SYS_THEME . 'plugins/select2/select2.js',
            URL_SYS_THEME . 'plugins/select2/select2_locale_pt-BR.js',
            URL_SYS_THEME . 'plugins/select2/select2.css'
        ),
        'flot' => array(
            URL_SYS_THEME . 'plugins/flot/jquery.flot.js',
            URL_SYS_THEME . 'plugins/flot/jquery.flot.pie.js',
            URL_SYS_THEME . 'plugins/flot/jquery.flot.resize.js',
            URL_SYS_THEME . 'plugins/flot/jquery.flot.selection.js',
            URL_SYS_THEME . 'plugins/flot/jquery.flot.stack.js',
            URL_SYS_THEME . 'plugins/flot/jquery.flot.time.js',
            URL_SYS_THEME . 'plugins/flot/jquery.flot.tooltip.js'
        ),
        'fileupload' => array(
            URL_SYS_THEME . 'plugins/jquery-file-upload/css/jquery.fileupload-ui.css',
            URL_SYS_THEME . 'plugins/jquery-file-upload/js/vendor/jquery.ui.widget.js',
            URL_SYS_THEME . 'plugins/jquery-file-upload/js/vendor/load-image.min.js',
            URL_SYS_THEME . 'plugins/jquery-file-upload/js/vendor/canvas-to-blob.min.js',
            URL_SYS_THEME . 'plugins/jquery-file-upload/js/jquery.iframe-transport.js',
            URL_SYS_THEME . 'plugins/jquery-file-upload/js/jquery.fileupload.js',
            URL_SYS_THEME . 'plugins/jquery-file-upload/js/jquery.fileupload-process.js',
            URL_SYS_THEME . 'plugins/jquery-file-upload/js/jquery.fileupload-image.js',
            URL_SYS_THEME . 'plugins/jquery-file-upload/js/cors/jquery.xdr-transport.js',
            URL_SYS_THEME . 'plugins/jquery-file-upload/js/cors/jquery.xdr-transport.js'
        ),
        'fancybox' => array(
            URL_SYS_THEME . 'plugins/fancybox/source/jquery.fancybox.css',
            URL_SYS_THEME . 'plugins/fancybox/source/jquery.fancybox.js',
            URL_SYS_THEME . 'plugins/fancybox/source/helpers/jquery.fancybox-buttons.css',
            URL_SYS_THEME . 'plugins/fancybox/source/helpers/jquery.fancybox-buttons.js',
            URL_SYS_THEME . 'plugins/fancybox/source/helpers/jquery.fancybox-thumbs.css',
            URL_SYS_THEME . 'plugins/fancybox/source/helpers/jquery.fancybox-thumbs.js',
            URL_SYS_THEME . 'plugins/fancybox/source/helpers/jquery.fancybox-media.js'
        ),
        'numberMask' => array(
            URL_SYS_THEME . '_js/jquery.numberMask.js'
        ),
        'chosen' => array(
            URL_SYS_THEME . 'plugins/chosen-bootstrap/chosen/chosen.css',
            URL_SYS_THEME . 'plugins/chosen-bootstrap/chosen/chosen.jquery.min.js'
        ),
        'backstretch' => array(
            URL_SYS_THEME . 'plugins/backstretch/jquery.backstretch.min.js'
        ),
        'sparkline' => array(
            URL_SYS_THEME . 'plugins/jquery.sparkline.min.js'
        ),
        'wizard' => array(
            URL_SYS_THEME . 'plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js'
        ),
        'multiselect' => array(
            URL_SYS_THEME . 'plugins/jquery-multi-select/js/jquery.multi-select.js',
            URL_SYS_THEME . 'plugins/jquery-multi-select/css/multi-select-metro.css'
        ),
        'typeahead' => array(
            URL_SYS_THEME . '_js/typeahead/js/bootstrap-typeahead.js',
        ),
        'chartjs' => array(
            URL_SYS_THEME . '_js/chartjs/Chart.min.js',
            URL_SYS_THEME . '_js/chartjs/Chart.StackedBar.js',
            URL_SYS_THEME . '_js/chartjs/html2canvas.min.js'
        ),
        'ckeditor' => array(
            URL_SYS_THEME . '_js/ckeditor/ckeditor.js',
            URL_SYS_THEME . '_js/ckeditor/adapters/jquery.js',
//            URL_SYS_THEME . '_js/ckeditor/config.js',
//            URL_SYS_THEME . '_js/ckeditor/contents.css',
//            URL_SYS_THEME . '_js/ckeditor/plugins/templates/templates/default.js',
//            URL_SYS_THEME . '_js/ckeditor/plugins/pastefromword/filter/default.js',
//            URL_SYS_THEME . '_js/ckeditor/build-config.js',
//            URL_SYS_THEME . '_js/ckeditor/styles.js',
//            URL_SYS_THEME . '_js/ckeditor/plugins/styles/styles/default.js',
        )
    );
}