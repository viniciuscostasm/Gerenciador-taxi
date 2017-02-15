<?php
require_once '../../_inc/global.php';

$form = new GForm();
$mysql = new GDbMysql();

$header = new GHeader('Encerramento de Corridas');
$header->addLib(array('paginate', 'daterangepicker', 'datepicker', 'typeahead', 'maskMoney', 'mask'));
$header->show(false, 'main/closing/closing.php');
// ---------------------------------- Header ---------------------------------//

try {
    $query = "SELECT txc_int_id, txc_var_name FROM vw_tax_taxicompany ORDER BY txc_var_name ASC";
    $opt_txc_var_name = $mysql->executeCombo($query);

} catch (Exception $e) {

}

$html .= '<div id="divTable" >';
$html .= getWidgetHeader();

$html .= '<div class="row">';

$html .= '<div class="col-md-12">';
//<editor-fold desc="Formulário de Filtro">
$html .= $form->open('filter', 'form-inline filterForm');
$html .= $form->addInput('text', 'p__rid_int_id', false, array('placeholder' => 'Corrida', 'class' => 'sepV_b m-wrap small', 'style' => 'width: 75px'), false, false, false);
$html .= $form->addInput('hidden', 'p__rid_dat_date', false, array('value' => ''), false, false, false);
$html .= '<div id="p__rid_dat_date_text" class="btn sepV_b"><i class="icon-calendar"></i>&nbsp;<span>' . $rid_dat_dateText . '</span>&nbsp;&nbsp;<b class="caret"></b></div>';
$html .= $form->addSelect('p__rid_hou_hour', $__arrayHoras , '', '', array('validate' => 'required', 'class' => 'sepV_b m-wrap'), false, false, true, '', 'Hora', false);
$html .= $form->addInput('text', 'p__rid_txt_passengerlist', false, array('placeholder' => 'Passageiro', 'class' => 'sepV_b m-wrap', 'style' => 'width: 100px'), false, false, false);
$html .= $form->addSelect('p__txc_int_id', $opt_txc_var_name, '', false, array('class' => 'sepV_b hidden-phone', 'style' => 'width:auto;'), false, false, true, '', 'Todas as empresas', false);
$html .= $form->addSelect('p__rid_cha_status', array("APR" => "Aprovadas", "CLO" => "Fechadas"), '', false, array('class' => 'sepV_b hidden-phone', 'style' => 'width:auto;'), false, false, true, '', 'Aprovadas e fechadas', false);

$html .= getBotoesFiltro();
$html .= $form->close();
//</editor-fold>

$paginate = new GPaginate('closing', 'closing_load.php', SYS_PAGINACAO);
$html .= $paginate->get();
$html .= '</div>'; //divTable

$html .= '</div>'; //md-122
$html .= '</div>'; //row

$html .= getWidgetFooter();

$html .= '<div id="loadClosing"></div>';
echo $html;

echo '<div id="divForm" class="row divForm">';
include 'closing_form.php';
echo '</div>';
// ---------------------------------- Footer ---------------------------------//
$footer = new GFooter();
$footer->show();
?>
<script>
    var pagCrud = 'closing_crud.php';
    var pagView = 'closing_view.php';
    var pagLoad = 'closing_load.php';
    var pagForm = 'closing_form.php';

    function filtrar(page) {
        closingLoad('', '', '', $('#filter').serializeObject(), page);
        return false;
    }

    $(function() {
        filtrar(1);
        $('#filter select').change(function() {
            filtrar(1);
            return false;
        });
        $('#filter').submit(function() {
            if ($('#filter').attr('action').length === 0) {
                filtrar(1);
                return false;
            }
        });
        $('#p__btn_limpar').click(function() {
            clearForm('#filter');
            $('#p__rid_dat_date_text span').html((moment().subtract(89, 'days')).format('DD/MM/YYYY') + ' até ' + (moment()).format('DD/MM/YYYY'));
            $('#p__rid_dat_date').val((moment().subtract(89, 'days')).format('DD/MM/YYYY') + '-' + (moment()).format('DD/MM/YYYY'));
            filtrar(1);
        });
        $(document).on('click', 'tr.linhaRegistro td:not([class~="acoes"])', function() {
            var rid_int_id = $(this).parents('tr.linhaRegistro').attr('id');
            var param = {acao: 'sel', 'rid_int_id': rid_int_id};

            scrollTop();
            selectLine(rid_int_id);
            loadForm(pagCrud, param, function(json) {
                if (json.status === undefined){
                    $('#rid_dec_parking').val(numberFormat(json.rid_dec_parking));
                    showForm('divForm', 'upd', 'Corrida #'+rid_int_id);
                    if (json.rid_cha_status == "CLO") {
                        $('#divForm input, #divForm textarea, #passageiroForm input, #passageiroForm textarea').attr("disabled", "disabled");
                        $('#f__btn_salvar, #f__btn_cancelar').hide();
                    } else {
                        $('#divForm input, #divForm textarea, #passageiroForm input, #passageiroForm textarea').removeAttr("disabled");
                        $('#f__btn_salvar, #f__btn_cancelar').show();
                    }
                    $.gAjax.load(pagCrud, {acao: 'rideRequests', rid_int_id:rid_int_id}, '#requests-table');
                }
            });
        });

        $(document).on('click', '.l__btn_excluir', function() {
            var rid_int_id = $(this).parents('tr.linhaRegistro').attr('id');
            var param = {acao: 'del', rid_int_id: rid_int_id};


            $.gDisplay.showYN("Do you really want to delete the selected item?", function() {
                $.gAjax.execCallback(pagCrud, param, false, function(json) {
                    if (json.status) {
                        filtrar();
                    }
                });
            });
        });

        $('#p__rid_dat_date_text span').html((moment().subtract(89, 'days')).format('DD/MM/YYYY') + ' até ' + (moment()).format('DD/MM/YYYY'));
        $('#p__rid_dat_date').val((moment().subtract(89, 'days')).format('DD/MM/YYYY') + '-' + (moment()).format('DD/MM/YYYY'));

        $('#p__rid_dat_date_text').daterangepicker({
            ranges: {
                'Hoje': [moment(), moment()],
                // 'Ontem': ['yesterday', 'yesterday'],
                'Próximos 7 dias': [moment(), moment().add(6, 'days')],
                'Últimos 7 dias': [moment().subtract(6, 'days'), moment()],
                'Este mês': [moment().startOf('month'), moment().endOf('month')],
                'Próximo mês': [moment().add(1, 'month').startOf('month'), moment().add(1, 'month').endOf('month')],
                'Último mês': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'Mês anterior': [moment().subtract(29, 'days'), moment()],
                'Últimos 60 dias': [moment().subtract(59, 'days'), moment()],
                'Últimos 90 dias': [moment().subtract(89, 'days'), moment()],
            },
            autoApply: true,
            opens: 'right',
            format: 'DD/MM/YYYY',
            separator: ' - ',
            locale: {
                applyLabel: 'Ok',
                fromLabel: 'De',
                toLabel: 'Até',
                cancelLabel: 'Cancelar',
                customRangeLabel: 'Selecionar período',
                daysOfWeek: [
                    "D",
                    "S",
                    "T",
                    "Q",
                    "Q",
                    "S",
                    "S"
                ],
                monthNames: [
                    "Janeiro",
                    "Fevereiro",
                    "Março",
                    "Abril",
                    "Maio",
                    "Junho",
                    "Julho",
                    "Agosto",
                    "Setembro",
                    "Outubro",
                    "Novembro",
                    "Dezembro"
                ]
            },
            showWeekNumbers: false,
            buttonClasses: ['btn'],
            startDate: moment().add({
                days: -29
            })
        },
        function(start, end) {
            if (start.toString('dd/MM/yyyy') !== end.toString('dd/MM/yyyy')) {
                $('#p__rid_dat_date_text span').html(start.format('DD/MM/YYYY') + ' até ' + end.format('DD/MM/YYYY'));
                $('#p__rid_dat_date').val(start.format('DD/MM/YYYY') + '-' + end.format('DD/MM/YYYY'));
            } else {
                $('#p__rid_dat_date_text span').html(start.format('DD/MM/YYYY'));
                $('#p__rid_dat_date').val(start.format('DD/MM/YYYY'));
            }
            filtrar(1);
        });
    });

    function loadClosing(rid_int_id) {
        $.gAjax.load(pagForm, {rid_int_id:rid_int_id}, '#loadClosing', function (response) {
            $('.default-content').hide();
            $('#loadClosing').show();
        });
    }

</script>