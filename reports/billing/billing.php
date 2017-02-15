<?php
require_once '../../_inc/global.php';

$form = new GForm();
$mysql = new GDbMysql();

$header = new GHeader('Faturamento de Empresas de Táxi');
$header->addLib(array('daterangepicker', 'chartjs'));
$header->show(false, 'reports/share.php');

$txc_int_id = NULL;

try {
	$usr_int_id = GSec::getUserSession()->getUsr_int_id();

	$pro_cha_typecurrent = GSec::getUserSession()->getProfile()->getPro_cha_type();

	if ($pro_cha_typecurrent != 'ADM') {
		$mysql->execute("SELECT txc_int_id FROM vw_tax_taxicompany_user WHERE usr_int_id = ?", array("i", $usr_int_id));
		if ($mysql->fetch()) {
			$txc_int_id = $mysql->res['txc_int_id'];
		}
		$mysql->freeResult();
	}

    $filterCombo = new GFilter();
    $filterCombo->setOrder(array('txc_var_name' => 'ASC'));

    $query = "SELECT txc_int_id, txc_var_name
                FROM vw_tax_taxicompany " . $filterCombo->getWhere();
    $param = $filterCombo->getParam();
    $opt_txc_var_name = $mysql->executeCombo($query, $param);

} catch (Exception $e) {

}

$html .= '<div id="divTable" class="row">';
$html .= getWidgetHeader();
//<editor-fold desc="Formulário de Filtro">
$html .= $form->open('filter', 'form-inline filterForm', 'POST', '_blank', 'billing_print.php');
$html .= $form->addInput('text', 'p__rid_int_id', false, array('placeholder' => 'Corrida', 'class' => 'sepV_b m-wrap small', 'style' => 'width: 100px;'), false, false, false);
$html .= $form->addInput('hidden', 'p__rid_dat_date', false, array('value' => ''), false, false, false);
$html .= '<div id="p__rid_dat_date_text" class="btn sepV_b" style=""><i class="icon-calendar"></i>&nbsp;<span>Período...</span>&nbsp;&nbsp;<b class="caret"></b></div>';
if ($pro_cha_typecurrent == 'ADM') {
	$html .= $form->addSelect('p__txc_int_id', $opt_txc_var_name, $txc_int_id, false, array('class' => 'sepV_b hidden-phone', 'style' => 'width:250px;'), false, false, true, '', 'Todas as companhias de táxi', false);
} else {
	$html .= $form->addInput('hidden', 'p__txc_int_id', false, array('value' => $txc_int_id), false, false, false);
}
$html .= $form->addInput('hidden', 'graphImg', false, array('value' => ''), false, false, false);

$html .= getBotoesFiltro();
$html .= $form->addButton('p__btn_imprimir', '<i class="fa fa-print"></i>', array('class' => 'btn pull-left sepV_a hidden-phone', 'data-title' => 'Print'), "submit");
$html .= $form->close();

$html .= getWidgetFooter();


$html .= '<div class="col-md-12">';
$html .= '	<div class="panel panel-default">';
$html .= '		<div class="panel-heading">';
$html .= '			<h3 class="panel-title">Faturamento de Empresas de Táxi</h3>';
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
	var pagLoad = 'billing_load.php';

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
        $('#p__rid_dat_date_text span').html((moment().subtract(89, 'days')).format('DD/MM/YYYY') + ' - ' + (moment()).format('DD/MM/YYYY'));
        $('#p__rid_dat_date').val((moment().subtract(89, 'days')).format('DD/MM/YYYY') + '-' + (moment()).format('DD/MM/YYYY'));
        $('#p__rid_int_id, #p__txc_int_id').val('');
        $('#p__btn_filtrar').click();
    });


    $(function () {
    	chartToImg('.chart');
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

	$('#p__rid_dat_date_text span').html((moment().subtract(89, 'days')).format('DD/MM/YYYY') + ' - ' + (moment()).format('DD/MM/YYYY'));
	$('#p__rid_dat_date').val((moment().subtract(89, 'days')).format('DD/MM/YYYY') + '-' + (moment()).format('DD/MM/YYYY'));

	$('#p__rid_dat_date_text').daterangepicker({
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
	        $('#filter #p__rid_dat_date_text span').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
	        $('#filter #p__rid_dat_date').val(start.format('DD/MM/YYYY') + '-' + end.format('DD/MM/YYYY'));
	    } else {
	        $('#filter #p__rid_dat_date_text span').html(start.format('DD/MM/YYYY'));
	        $('#filter #p__rid_dat_date').val(start.format('DD/MM/YYYY'));
	    }
	    $('#p__btn_filtrar').click();
	});
</script>