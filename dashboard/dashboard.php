<?php
require_once '../_inc/global.php';

$form = new GForm();
$mysql = new GDbMysql();

$header = new GHeader('Dashboard');
$header->addLib(array('daterangepicker', 'chartjs'));
$header->show(false, 'dashboard/dashboard.php');

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

$html .= getWidgetHeader();
//<editor-fold desc="Formulário de Filtro">
$html .= $form->open('filter', 'form-inline filterForm', 'POST', '_blank', 'share_print.php');
$html .= $form->addInput('hidden', 'p__dat_date', false, array('value' => ''), false, false, false);
$html .= '<div id="p__dat_date_text" class="btn sepV_b" style=""><i class="icon-calendar"></i>&nbsp;<span>Período...</span>&nbsp;&nbsp;<b class="caret"></b></div>';
$html .= $form->addSelect('p__coc_int_id', $opt_coc_var_name, '', false, array('class' => 'sepV_b hidden-phone', 'style' => 'width:210px;'), false, false, true, '', 'Todos os centros de custo', false);

$html .= getBotoesFiltro();
$html .= $form->close();

$html .= getWidgetFooter();

$html .= '<div class="col-md-6">';
$html .= '	<div class="panel panel-default">';
$html .= '		<div class="panel-heading">';
$html .= '			<h3 class="panel-title">Centros de Custo que mais consomem</h3>';
$html .= '		</div>';
$html .= '		<div class="panel-body">';
$html .= '			<div><canvas class="chart coc"></div>';
$html .= '			<div class="report-data">';
$html .= 			'</div>';
$html .= '		</div>';
$html .= '	</div>';
$html .= '</div>';

$html .= '<div class="col-md-6">';
$html .= '	<div class="panel panel-default">';
$html .= '		<div class="panel-heading">';
$html .= '			<h3 class="panel-title">Ranking de Passageiros</h3>';
$html .= '		</div>';
$html .= '		<div class="panel-body" style="overflow: auto; height: 335px;">';
$html .= '			<div class="ranking-table"></div>';
$html .= '			<div class="report-data">';
$html .= 			'</div>';
$html .= '		</div>';
$html .= '	</div>';
$html .= '</div>';
$html .= '<div class="clearfix"></div>';

$html .= '<div class="col-md-6">';
$html .= '	<div class="panel panel-default">';
$html .= '		<div class="panel-heading">';
$html .= '			<h3 class="panel-title">Maiores Motivos de Solicitação</h3>';
$html .= '		</div>';
$html .= '		<div class="panel-body">';
$html .= '			<div><canvas class="chart motives"></div>';
$html .= '			<div class="report-data">';
$html .= 			'</div>';
$html .= '		</div>';
$html .= '	</div>';
$html .= '</div>';

$html .= '<div class="col-md-6">';
$html .= '	<div class="panel panel-default">';
$html .= '		<div class="panel-heading">';
$html .= '			<h3 class="panel-title">Horários com maior número de corridas</h3>';
$html .= '		</div>';
$html .= '		<div class="panel-body">';
$html .= '			<div><canvas class="chart hour"></div>';
$html .= '			<div class="report-data">';
$html .= 			'</div>';
$html .= '		</div>';
$html .= '	</div>';
$html .= '</div>';

echo $html;

// ---------------------------------- Footer ---------------------------------//
$footer = new GFooter();
$footer->show();
?>

<script type="text/javascript">
var pagLoad = 'dashboard_load.php';
var charts = [];
	function filtrar () {
		$.gAjax.execCallback(pagLoad, $('#filter').serializeObject(), false, function(json) {
			$.each(json.graphs, function(index, data) {
				loadChart(data, data.name);
			});
			if (json.ranking) {
				$('.ranking-table').html(json.ranking)
			}
		});
	}

	$('#p__btn_filtrar').on('click', function(event) {
        event.preventDefault();
        filtrar();
    });

    $('#p__btn_limpar').click(function () {
        $('#p__req_dat_date_text span').html((moment().subtract(89, 'days')).format('DD/MM/YYYY') + ' - ' + (moment()).format('DD/MM/YYYY'));
        $('#p__req_dat_date').val((moment().subtract(89, 'days')).format('DD/MM/YYYY') + '-' + (moment()).format('DD/MM/YYYY'));
        $('#p__rid_int_id, #p__coc_int_id, #p__mot_int_id, #p__req_var_passenger').val('');
        $('#p__btn_filtrar').click();
    });

	function loadChart (data, name) {
        if (typeof data == "object") {
        	$(".chart").show();
        	if (typeof charts[2] != 'undefined'){
                updateChart(data, name); 
        	} else {
        		getChart(data, name);
        	}
        } else {
        	$(charts).each(function(index, el) {
        		el.destroy();
        	});
        	$(".chart").hide();
        }
    };

	function getChart (graphData, el) {
		var ctx = $('.chart.'+el)[0].getContext("2d");
		var data = {};
		var money = (el == "coc") ? "R$ " : "";
		data.labels = graphData.labels;
		data.datasets = [{
            fillColor: "rgba(220,220,220,0.5)",
            strokeColor: "rgba(220,220,220,0.8)",
            highlightFill: "rgba(220,220,220,0.75)",
            highlightStroke: "rgba(220,220,220,1)",
            data: graphData.data
        }];

        charts.push(new Chart(ctx).StackedBar(data, {
			animation: true,
			responsive: true,
			scaleLabel: (money) ? "<%='"+ money +"' + Number(value).toFixed(2).replace('.', ',')%>" : "<%=parseInt(value)%>",
			tooltipTemplate: (money) ? "<%if (label){%><%=label%>: <%}%><%='R$ ' + Number(value).toFixed(2).replace('.', ',')%>" : "<%if (label){%><%=label%>: <%}%><%=parseInt(value)%>"
		}));
	}

	function updateChart(json, el) {
        $(charts).each(function(index, el) {
        	el.destroy();
        });
        charts = [];
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

		var ctx = $('.chart.'+el)[0].getContext("2d");
        charts.push(new Chart(ctx).StackedBar(data, {
			animation: true,
			responsive: true,
			scaleLabel: "<%='R$ ' + Number(value).toFixed(2).replace('.', ',')%>",
			tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%='R$ ' + Number(value).toFixed(2).replace('.', ',')%>"
		}));
	}

	$('#filter select').change(function() {
        filtrar();
	});

	$('#p__dat_date_text span').html((moment().subtract(89, 'days')).format('DD/MM/YYYY') + ' - ' + (moment()).format('DD/MM/YYYY'));
	$('#p__dat_date').val((moment().subtract(89, 'days')).format('DD/MM/YYYY') + '-' + (moment()).format('DD/MM/YYYY'));

	$('#p__dat_date_text').daterangepicker({
	    ranges: {
	        'Hoje': [moment(), moment()],
            'Ontem': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Últimos 7 Dias': [moment().subtract(6, 'days'), moment()],
            'Últimos 30 Dias': [moment().subtract(29, 'days'), moment()],
            'Este Mês': [moment().startOf('month'), moment().endOf('month')],
            'Este Ano': [moment().startOf('year'), moment().endOf('year')]
	    },
	    autoApply: true,
	    opens: 'right',
	    format: 'DD/MM/YYYY',
	    separator: ' - ',
	    startDate: Date.today().add({
	        days: -29
	    }),
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
	    endDate: Date.today(),
	    maxDate: Date.today(),
	    showWeekNumbers: false,
	    buttonClasses: ['btn']
	},
	function(start, end) {
	    if (start.format('DD/MM/YYYY') !== end.format('DD/MM/YYYY')) {
	        $('#filter #p__dat_date_text span').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
	        $('#filter #p__dat_date').val(start.format('DD/MM/YYYY') + '-' + end.format('DD/MM/YYYY'));
	    } else {
	        $('#filter #p__dat_date_text span').html(start.format('DD/MM/YYYY'));
	        $('#filter #p__dat_date').val(start.format('DD/MM/YYYY'));
	    }
	    $('#p__btn_filtrar').click();
	});
</script>