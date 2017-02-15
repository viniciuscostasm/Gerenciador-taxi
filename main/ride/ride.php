<?php
require_once '../../_inc/global.php';

$form = new GForm();
$mysql = new GDbMysql();

$header = new GHeader('Corridas');
$header->addLib(array('paginate', 'datepicker', 'daterangepicker', 'typeahead'));
$header->show(false, 'main/ride/ride.php');
// ---------------------------------- Header ---------------------------------//

try {

    $query = "SELECT txc_int_id, txc_var_name FROM vw_tax_taxicompany ORDER BY txc_var_name ASC";
    $opt_txc_var_name = $mysql->executeCombo($query); 

} catch (Exception $e) {
    
}

$html .= getWidgetHeader();

$html .= '<div class="row">';
$html .= '<div class="col-md-12">';
//TODO: FILTRO da DATA
$html .= '</div>'; //md-3
$html .= '</div>'; //row

$html .= '<div class="row">';
$html .= '<div class="col-md-8">';

$html .= '<div id="divTable" >';
//<editor-fold desc="Formulário de Filtro">
$html .= $form->open('filter', 'form-inline filterForm');
$html .= $form->addInput('text', 'p__rid_txt_passengerlist', false, array('placeholder' => 'Nome', 'class' => 'sepV_b m-wrap small'), false, false, false);
$html .= $form->addSelect('p__txc_int_id', $opt_txc_var_name, '', false, array('class' => 'sepV_b hidden-phone', 'style' => 'width:auto;'), false, false, true, '', 'Todas as empresas', false);
$html .= $form->addSelect('p__rid_cha_status', array("PEN" => "Pendente", "APP" => "Aprovada", "CLO" => "Fechada"), '', false, array('class' => 'sepV_b hidden-phone', 'style' => 'width:175px;'), false, false, true, '', 'Todos os status', false);
$html .= $form->addInput('hidden', 'p__rid_dat_date', false, array('value' => ''), false, false, false);

$html .= getBotoesFiltro();
$html .= $form->close();

$html .= '<div id="navegacaoDia" class="__acenter" style="margin-bottom:15px; margin-top: 10px;">';
$html .= $form->addButton('anterior', '<i class="fa fa-chevron-left"></i>', array('class' => 'btn black pull-left'));
$html .= '<div id="p__rid_dat_date_text" class="btn"><i class="icon-calendar"></i>&nbsp;<span>' . $rid_dat_dateText . '</span>&nbsp;&nbsp;<b class="caret"></b></div>';
$html .= $form->addButton('proximo', '<i class="fa fa-chevron-right"></i>', array('class' => 'btn black pull-right'));
$html .= '</div>';

//</editor-fold>

$paginate = new GPaginate('ride', 'ride_load.php', SYS_PAGINACAO);
$html .= $paginate->get();
$html .= '</div>'; //divTable

$html .= '</div>'; //md-9


$html .= '<div class="col-md-4" id="loadRide">';

$html .= '</div>'; //md-3
$html .= '</div>'; //row

$html .= getWidgetFooter();
echo $html;

// ---------------------------------- Footer ---------------------------------//
$footer = new GFooter();
$footer->show();
?>
<script>
    var pagCrud = 'ride_crud.php';
    var pagView = 'ride_view.php';
    var pagLoad = 'ride_load.php';
    var pagForm = 'ride_form.php';
    var pagReport = 'ride_relatorio.php';

    function filtrar(page) {
        rideLoad('', '', '', $('#filter').serializeObject(), page);
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

            loadRide(rid_int_id)
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
            filtrar(1);
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
            filtrar(1);
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
            filtrar(1);
            return false;
        });

    function loadRide(rid_int_id) {
        $.gAjax.load(pagForm, {rid_int_id:rid_int_id}, '#loadRide');
    }

</script>