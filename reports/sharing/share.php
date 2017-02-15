<?php
require_once '../../_inc/global.php';

$form = new GForm();
$mysql = new GDbMysql();

$header = new GHeader('Rateio entre Centro de Custos');
$header->addLib(array('daterangepicker', 'chartjs'));
$header->show(false, 'reports/share.php');

try {
	$usr_int_id = GSec::getUserSession()->getUsr_int_id();

    $filterCombo = new GFilter();
    $filterCombo->setOrder(array('coc_var_name' => 'ASC'));

    $query = "SELECT coc_int_id, coc_var_name
                FROM vw_tax_costcenter " . $filterCombo->getWhere();
    $param = $filterCombo->getParam();
    $opt_coc_var_name = $mysql->executeCombo($query, $param);

    $filterCombo = new GFilter();
    $filterCombo->setOrder(array('mot_var_name' => 'ASC'));

    $query = "SELECT mot_int_id, mot_var_name
                FROM vw_tax_motive " . $filterCombo->getWhere();
    $param = $filterCombo->getParam();
    $opt_mot_var_name = $mysql->executeCombo($query, $param);

} catch (Exception $e) {

}

$html .= '<div id="divTable" class="row">';
$html .= getWidgetHeader();
//<editor-fold desc="Formulário de Filtro">
$html .= $form->open('filter', 'form-inline filterForm', 'POST', '_blank', 'share_print.php');
$html .= $form->addInput('text', 'p__rid_int_id', false, array('placeholder' => 'Corrida', 'class' => 'sepV_b m-wrap small', 'style' => 'width: 100px;'), false, false, false);
$html .= $form->addInput('hidden', 'p__req_dat_date', false, array('value' => ''), false, false, false);
$html .= '<div id="p__req_dat_date_text" class="btn sepV_b" style=""><i class="icon-calendar"></i>&nbsp;<span>Período...</span>&nbsp;&nbsp;<b class="caret"></b></div>';
$html .= $form->addSelect('p__coc_int_id', $opt_coc_var_name, '', false, array('class' => 'sepV_b hidden-phone', 'style' => 'width:210px;'), false, false, true, '', 'Todos os centros de custo', false);
$html .= $form->addSelect('p__mot_int_id', $opt_mot_var_name, '', false, array('class' => 'sepV_b hidden-phone', 'style' => 'width:160px;'), false, false, true, '', 'Todos os motivos', false);
$html .= $form->addInput('text', 'p__req_var_passenger', false, array('placeholder' => 'Passageiro', 'class' => 'sepV_b m-wrap small'), false, false, false);
$html .= $form->addInput('hidden', 'graphImg', false, array('value' => ''), false, false, false);

$html .= getBotoesFiltro();
$html .= $form->addButton('p__btn_imprimir', '<i class="fa fa-print"></i>', array('class' => 'btn pull-left sepV_a hidden-phone', 'data-title' => 'Print'), "submit");
$html .= $form->close();

$html .= getWidgetFooter();


$html .= '<div class="col-md-12">';
$html .= '	<div class="panel panel-default">';
$html .= '		<div class="panel-heading">';
$html .= '			<h3 class="panel-title">Centros de Custo</h3>';
$html .= '		</div>';
$html .= '		<div class="panel-body">';
$html .= '			<div><canvas class="chart"></div>';
$html .= '			<div class="report-data">';
$html .= 			'</div>';
$html .= '		</div>';
$html .= '	</div>';
$html .= '</div>';


$html .= '</div>'; //divTable


echo $html;


// ---------------------------------- Footer ---------------------------------//
$footer = new GFooter();
$footer->show();
?>
<script>
	var pagLoad = 'share_load.php';

	function filtrar () {
		$.gAjax.load(pagLoad, $('#filter').serializeObject(), '.report-data');
	}

	$('#p__btn_filtrar').on('click', function(event) {
        event.preventDefault();
        data = $('#filter').serializeObject();
        data['acao'] = 'graph';
        loadChart(data);
        filtrar();
    });

    $('#p__btn_limpar').click(function () {
        $('#p__req_dat_date_text span').html((moment().subtract(89, 'days')).format('DD/MM/YYYY') + ' - ' + (moment()).format('DD/MM/YYYY'));
        $('#p__req_dat_date').val((moment().subtract(89, 'days')).format('DD/MM/YYYY') + '-' + (moment()).format('DD/MM/YYYY'));
        $('#p__rid_int_id, #p__coc_int_id, #p__mot_int_id, #p__req_var_passenger').val('');
        $('#p__btn_filtrar').click();
    });

	function loadChart (data) {
		$.gAjax.execCallback(pagLoad, data, false, function(json) {
            if (json !== null) {
                if (json.data.length > 0) {
                	$(".chart").show();
                	if (typeof myBarChart != 'undefined'){
                        updateChart(json); 
                	} else {
                		getChart(json);
                	}
                } else {
                	myBarChart.destroy();
                	$('#graphImg').val('');
                	$(".chart").hide();
                }
            };
        }, true, false);
	};

	function getChart (graphData) {
		var ctx = $(".chart")[0].getContext("2d");
		var data = {};
		data.labels = graphData.labels;
		data.datasets = [{
            fillColor: "rgba(220,220,220,0.5)",
            strokeColor: "rgba(220,220,220,0.8)",
            highlightFill: "rgba(220,220,220,0.75)",
            highlightStroke: "rgba(220,220,220,1)",
            data: graphData.data
        }];
		myBarChart = new Chart(ctx).StackedBar(data, {
			animation: false,
			responsive: true,
			scaleLabel: "<%='R$ ' + Number(value).toFixed(2).replace('.', ',')%>",
			tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%='R$ ' + Number(value).toFixed(2).replace('.', ',')%>"
		});
	}

	function updateChart(json) {
        myBarChart.destroy();
        var data = {
            labels: json.labels,
            datasets: [{
            	label: '123 som',
                fillColor: "rgba(220,220,220,0.5)",
                strokeColor: "rgba(220,220,220,0.8)",
                highlightFill: "rgba(220,220,220,0.75)",
                highlightStroke: "rgba(220,220,220,1)",
                data: json.data
            }]
        };

        var ctx = $(".chart")[0].getContext("2d");
        myBarChart = new Chart(ctx).StackedBar(data, {
			animation: false,
			responsive: true,
			scaleLabel: "<%='R$ ' + Number(value).toFixed(2).replace('.', ',')%>",
			tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%='R$ ' + Number(value).toFixed(2).replace('.', ',')%>"
		});

        chartToImg('.chart');
	}

	function chartToImg(element) {
		html2canvas($(element), {
	        onrendered: function(canvas) {  
	            var imgData = canvas.toDataURL('image/png');   
	            $('#graphImg').val(imgData);
	        }
		});
	}

	$('#filter select').change(function() {
        data = $('#filter').serializeObject();
        data['acao'] = 'graph';
        loadChart(data);
        filtrar();
	});

	$('#p__req_dat_date_text span').html((moment().subtract(89, 'days')).format('DD/MM/YYYY') + ' - ' + (moment()).format('DD/MM/YYYY'));
	$('#p__req_dat_date').val((moment().subtract(89, 'days')).format('DD/MM/YYYY') + '-' + (moment()).format('DD/MM/YYYY'));

	$('#p__req_dat_date_text').daterangepicker({
	    ranges: {
	        'Today': [moment(), moment()],
	        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
	        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
	        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
	        'Last 60 Days': [moment().subtract(59, 'days'), moment()],
	        'Last 90 Days': [moment().subtract(89, 'days'), moment()],
	        'This Month': [moment().startOf('month'), moment().endOf('month')],
	        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
	    },
	    autoApply: true,
	    opens: 'right',
	    format: 'DD/MM/YYYY',
	    separator: ' - ',
	    startDate: Date.today().add({
	        days: -29
	    }),
	    endDate: Date.today(),
	    maxDate: Date.today(),
	    showWeekNumbers: false,
	    buttonClasses: ['btn']
	},
	function(start, end) {
	    if (start.format('DD/MM/YYYY') !== end.format('DD/MM/YYYY')) {
	        $('#filter #p__req_dat_date_text span').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
	        $('#filter #p__req_dat_date').val(start.format('DD/MM/YYYY') + '-' + end.format('DD/MM/YYYY'));
	    } else {
	        $('#filter #p__req_dat_date_text span').html(start.format('DD/MM/YYYY'));
	        $('#filter #p__req_dat_date').val(start.format('DD/MM/YYYY'));
	    }
	    $('#p__btn_filtrar').click();
	});
</script>