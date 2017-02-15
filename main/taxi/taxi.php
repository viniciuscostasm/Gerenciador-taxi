<?php
require_once '../../_inc/global.php';

$form = new GForm();

$header = new GHeader('Corridas de Táxi');
$header->addLib(array('datepicker', 'daterangepicker', 'chartjs'));
$header->show(false, 'reports/share.php');

$html .= '<div id="divTable" class="row">';
$html .= getWidgetHeader();
//<editor-fold desc="Formulário de Filtro">
$html .= $form->open('filter', 'form-inline filterForm', 'POST', '_blank', 'billing_print.php');
$html .= $form->addSelect('p__rid_hou_hour', $__arrayHoras , '', false, array('class' => 'sepV_a hidden-phone', 'validate' => 'required'), false, false, true, '', 'Todos os horários', false);

$html .= $form->addInput('hidden', 'p__txc_int_id', false, array('value' => ''), false, false, false);
$html .= $form->addInput('hidden', 'p__rid_dat_date', false, array('value' => ''), false, false, false);
$html .= $form->addInput('text', 'p__rid_txt_passengerlist', false, array('placeholder' => 'Passageiro', 'class' => 'sepV_a m-wrap small'), false, false, false);

$html .= getBotoesFiltro();
$html .= $form->close();

$html .= '<div id="navegacaoDia" class="__acenter" style="margin-bottom:15px; margin-top: 10px;">';
$html .= $form->addButton('anterior', '<i class="fa fa-chevron-left"></i>', array('class' => 'btn black pull-left'));
$html .= '<div id="p__rid_dat_date_text" class="btn"><i class="icon-calendar"></i>&nbsp;<span>' . $rid_dat_dateText . '</span>&nbsp;&nbsp;<b class="caret"></b></div>';
$html .= $form->addButton('proximo', '<i class="fa fa-chevron-right"></i>', array('class' => 'btn black pull-right'));
$html .= '</div>';

$html .= getWidgetFooter();


$html .= '<div class="col-md-12">';
$html .= '	<div class="portlet light">';
$html .= '		<div class="taxi-rides"></div>';
$html .= '		<div class="form-actions clearfix">';
$html .= 		$form->addButton('f__btn_faturamento', '<i class="fa fa-print"></i> Faturamento do Período', array('class' => 'btn blue pull-right sepV_b'), 'submit');
$html .= '		</div>';
$html .= '	</div>';
$html .= '</div>'; //divTable

echo $html;


// ---------------------------------- Footer ---------------------------------//
$footer = new GFooter();
$footer->show();
?>
<script>
	var pagLoad = 'taxi_load.php';
	var pagPrint = '../voucher/voucher_print.php';

	function filtrar () {
		$.gAjax.load(pagLoad, $('#filter').serializeObject(), '.taxi-rides');
	}

	$('#p__btn_filtrar').on('click', function(event) {
        event.preventDefault();
        filtrar();
    });

	$('body').on('click', '#f__btn_faturamento', function(event) {
        event.preventDefault();
        window.location = '../../reports/billing/billing.php';
    });

	$('#filter select').change(function() {
        filtrar();
	});

	$('#p__btn_limpar').click(function() {
        clearForm('#filter');
        $('#p__rid_dat_date_text span').html((moment().subtract(89, 'days')).format('DD/MM/YYYY') + ' até ' + (moment()).format('DD/MM/YYYY'));
    	$('#p__rid_dat_date').val((moment().subtract(89, 'days')).format('DD/MM/YYYY') + '-' + (moment()).format('DD/MM/YYYY'));
        filtrar(1);
    });

    $('#p__rid_dat_date_text span').html((moment().subtract(89, 'days')).format('DD/MM/YYYY') + ' até ' + (moment()).format('DD/MM/YYYY'));
    $('#p__rid_dat_date').val((moment().subtract(89, 'days')).format('DD/MM/YYYY') + '-' + (moment()).format('DD/MM/YYYY'));

	$('#p__rid_dat_date_text').daterangepicker({
            ranges: {
                'Hoje': [moment(), moment()],
                // 'Ontem': ['yesterday', 'yesterday'],
                'Últimos 7 dias': [moment().subtract(6, 'days'), moment()],
                'Este mês': [moment().startOf('month'), moment().endOf('month')],
                'Próximo mês': [moment().add(1, 'month').startOf('month'), moment().add(1, 'month').endOf('month')],
	            'Último mês': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'Mês anterior': [moment().subtract(29, 'days'), moment()],
	            'Últimos 60 dias': [moment().subtract(59, 'days'), moment()],
	            'Últimos 90 dias': [moment().subtract(89, 'days'), moment()],
            },
            opens: 'left',
            format: 'dd/MM/yyyy',
            separator: ' até ',
            startDate: moment().add({
                days: -29
            }),
            locale: {
                applyLabel: 'Filtrar',
                fromLabel: 'De',
                toLabel: 'Até',
                customRangeLabel: 'Selecionar período',
                daysOfWeek: ['Do', 'Se', 'Te', 'Qu', 'Qu', 'Se', 'Sa'],
                monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                firstDay: 1
            },
            showWeekNumbers: false,
            buttonClasses: ['black'],
            applyClass: 'blue'
        },
        function(start, end) {
            if (start.toString('dd/MM/yyyy') !== end.toString('dd/MM/yyyy')) {
                $('#p__rid_dat_date_text span').html(start.format('DD/MM/YYYY') + ' até ' + end.format('DD/MM/YYYY'));
                $('#p__rid_dat_date').val(start.format('DD/MM/YYYY') + '-' + end.format('DD/MM/YYYY'));
            } else {
                $('#p__rid_dat_date_text span').html(start.format('DD/MM/YYYY'));
                $('#p__rid_dat_date').val(start.format('DD/MM/YYYY'));
            }
            filtrar();
        });

        $('#navegacaoDia #anterior').click(function() {
            var data = $('#p__rid_dat_date').val();
            var novaData = novaDataHidden = '';
            var arrayData = data.split('-');
            if(arrayData[1] !== undefined){
                var data0 = Date.parse(arrayData[0].substring(3, 5) + '/' + arrayData[0].substring(0, 2) + '/' + arrayData[0].substring(6, 10), 'MM/dd/yyyy');
                var data1 = Date.parse(arrayData[1].substring(3, 5) + '/' + arrayData[1].substring(0, 2) + '/' + arrayData[1].substring(6, 10), 'MM/dd/yyyy');

                var dataInicio = Date.parse(arrayData[0].substring(3, 5) + '/' + arrayData[0].substring(0, 2) + '/' + arrayData[0].substring(6, 10), 'MM/dd/yyyy').moveToFirstDayOfMonth();
                var dataTermino = Date.parse(arrayData[1].substring(3, 5) + '/' + arrayData[1].substring(0, 2) + '/' + arrayData[1].substring(6, 10), 'MM/dd/yyyy').moveToLastDayOfMonth();

                if(data0.equals(dataInicio) && data1.equals(dataTermino)){
                    var novoInicio = data0.add({months: -1}).toString('dd/MM/yyyy');
                    var novoTermino = data0.moveToLastDayOfMonth().toString('dd/MM/yyyy');
                } else {
                    var diff = (new TimeSpan(data1 - data0).days) + 1;

                    var novoInicio = data0.add({ days: -diff }).toString('dd/MM/yyyy');
                    var novoTermino = data1.add({ days: -diff }).toString('dd/MM/yyyy');
                }

                novaData = novoInicio + ' até ' + novoTermino;
                novaDataHidden = novoInicio + '-' + novoTermino;
            } else {
                var dataM = arrayData[0].substring(3, 5) + '/' + arrayData[0].substring(0, 2) + '/' + arrayData[0].substring(6, 10);
                novaData = Date.parse(dataM, 'MM/dd/yyyy').add({days: -1}).toString('dd/MM/yyyy');
                novaDataHidden = novaData;
            }

            $('#p__rid_dat_date').val(novaDataHidden);
            $('#p__rid_dat_date_text span').html(novaData);
            $('#p__btn_filtrar').click();
            return false;
        });

        $('#navegacaoDia #proximo').click(function() {
            var data = $('#p__rid_dat_date').val();
            var novaData = novaDataHidden = '';
            var arrayData = data.split('-');
            if(arrayData[1] !== undefined){
                var data0 = Date.parse(arrayData[0].substring(3, 5) + '/' + arrayData[0].substring(0, 2) + '/' + arrayData[0].substring(6, 10), 'MM/dd/yyyy');
                var data1 = Date.parse(arrayData[1].substring(3, 5) + '/' + arrayData[1].substring(0, 2) + '/' + arrayData[1].substring(6, 10), 'MM/dd/yyyy');

                var dataInicio = Date.parse(arrayData[0].substring(3, 5) + '/' + arrayData[0].substring(0, 2) + '/' + arrayData[0].substring(6, 10), 'MM/dd/yyyy').moveToFirstDayOfMonth();
                var dataTermino = Date.parse(arrayData[1].substring(3, 5) + '/' + arrayData[1].substring(0, 2) + '/' + arrayData[1].substring(6, 10), 'MM/dd/yyyy').moveToLastDayOfMonth();

                if(data0.equals(dataInicio) && data1.equals(dataTermino)){
                    var novoInicio = data0.add({months: 1}).toString('dd/MM/yyyy');
                    var novoTermino = data0.moveToLastDayOfMonth().toString('dd/MM/yyyy');
                } else {
                    var diff = (new TimeSpan(data1 - data0).days) + 1;

                    var novoInicio = data0.add({ days: diff }).toString('dd/MM/yyyy');
                    var novoTermino = data1.add({ days: diff }).toString('dd/MM/yyyy');
                }
                novaData = novoInicio + ' até ' + novoTermino;
                novaDataHidden = novoInicio + '-' + novoTermino;
            } else {
                var dataM = arrayData[0].substring(3, 5) + '/' + arrayData[0].substring(0, 2) + '/' + arrayData[0].substring(6, 10);
                novaData = Date.parse(dataM, 'MM/dd/yyyy').add({days: 1}).toString('dd/MM/yyyy');
                novaDataHidden = novaData;
            }

            $('#p__rid_dat_date').val(novaDataHidden);
            $('#p__rid_dat_date_text span').html(novaData);
            $('#p__btn_filtrar').click();
            return false;
        });

</script>