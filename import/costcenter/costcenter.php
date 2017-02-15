<?php
require_once '../../_inc/global.php';

$form = new GForm();
$mysql = new GDbMysql();

$header = new GHeader('Importação de Centros de Custo');
$header->addLib(array('paginate', 'daterangepicker', 'datepicker', 'typeahead', 'maskMoney', 'mask', 'fileupload'));
$header->show(false, 'main/closing/closing.php');
// ---------------------------------- Header ---------------------------------//

try {
    $query = "SELECT usr_int_id, usr_var_name FROM vw_adm_user";
    $opt_usr_var_name = $mysql->executeCombo($query);

} catch (Exception $e) {

}

$html .= '<div id="divTable" >';
$html .= getWidgetHeader();

$html .= '<div class="row">';

$html .= '<div class="col-md-12">';
//<editor-fold desc="Formulário de Filtro">
$html .= $form->open('filter', 'form-inline filterForm');
$html .= $form->addInput('hidden', 'p__cci_dat_date', false, array('value' => ''), false, false, false);
$html .= '<div id="p__cci_dat_date_text" class="btn sepV_b"><i class="icon-calendar"></i>&nbsp;<span>' . $cci_dat_dateText . '</span>&nbsp;&nbsp;<b class="caret"></b></div>';
$html .= $form->addSelect('p__usr_int_id', $opt_usr_var_name, '', false, array('class' => 'sepV_b hidden-phone', 'style' => 'width:150px;'), false, false, true, '', 'Todos os usuários', false);
$html .= getBotoesFiltro();
$html .= $form->addButton('p__btn_adicionar', '<i class="fa fa-plus"></i> <span class="hidden-phone">Importar</span>', array('class' => 'btn sepH_a sepV_a blue-steel pull-left'));
$html .= $form->close();
//</editor-fold>

$paginate = new GPaginate('costcenter', 'costcenter_load.php', SYS_PAGINACAO);
$html .= $paginate->get();
$html .= '</div>'; //divTable

$html .= '</div>'; //md-122
$html .= '</div>'; //row

$html .= getWidgetFooter();

$html .= '<div id="loadCostcenter"></div>';
echo $html;

echo '<div id="divForm" class="row divForm">';
include 'costcenter_form.php';
echo '</div>';
// ---------------------------------- Footer ---------------------------------//
$footer = new GFooter();
$footer->show();
?>
<script>
    var pagCrud = 'costcenter_crud.php';
    var pagView = 'costcenter_view.php';
    var pagLoad = 'costcenter_load.php';
    var pagForm = 'costcenter_form.php';

    function filtrar(page) {
        costcenterLoad('', '', '', $('#filter').serializeObject(), page);
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
            $('#p__cci_dat_date_text span').html((moment().subtract(89, 'days')).format('DD/MM/YYYY') + ' até ' + (moment()).format('DD/MM/YYYY'));
            $('#p__cci_dat_date').val((moment().subtract(89, 'days')).format('DD/MM/YYYY') + '-' + (moment()).format('DD/MM/YYYY'));
            filtrar(1);
        });
        $(document).on('click', 'tr.linhaRegistro td:not([class~="acoes"])', function() {
            var rid_int_id = $(this).parents('tr.linhaRegistro').attr('id');
            var param = {acao: 'sel', 'rid_int_id': rid_int_id};

            scrollTop();
            selectLine(rid_int_id);
            loadForm(pagCrud, param, function(json) {
                if (json.status === undefined){
                   /* $('#rid_dec_parking').val(numberFormat(json.rid_dec_parking));
                    showForm('divForm', 'upd', 'Corrida #'+rid_int_id);
                    if (json.rid_cha_status == "CLO") {
                        $('#divForm input, #divForm textarea, #passageiroForm input, #passageiroForm textarea').attr("disabled", "disabled");
                        $('#f__btn_salvar, #f__btn_cancelar').hide();
                    } else {
                        $('#divForm input, #divForm textarea, #passageiroForm input, #passageiroForm textarea').removeAttr("disabled");
                        $('#f__btn_salvar, #f__btn_cancelar').show();
                    }
                    $.gAjax.load(pagCrud, {acao: 'rideRequests', rid_int_id:rid_int_id}, '#requests-table');*/
                }
            });
        });

        $(document).on('click', '#p__btn_adicionar', function() {
            scrollTop();
            unselectLines();

            $('#form .btn-group button, #form select, #form input, #form textarea').attr('disabled', false);

            showForm('divForm', 'ins', 'Add');
        });

        $('#p__cci_dat_date_text span').html((moment().subtract(89, 'days')).format('DD/MM/YYYY') + ' até ' + (moment()).format('DD/MM/YYYY'));
        $('#p__cci_dat_date').val((moment().subtract(89, 'days')).format('DD/MM/YYYY') + '-' + (moment()).format('DD/MM/YYYY'));

        $('#p__cci_dat_date_text').daterangepicker({
            ranges: {
                'Hoje': [moment(), moment()],
                // 'Ontem': ['yesterday', 'yesterday'],
                'Últimos 7 dias': [moment().subtract(6, 'days'), moment()],
                'Este mês': [moment().startOf('month'), moment().endOf('month')],
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
        startDate: Date.today(),
        endDate: Date.today().add({days: 6}),
        showWeekNumbers: false,
        buttonClasses: ['btn'],
        startDate: moment().add({
            days: -29
        })
        },
        function(start, end) {
            if (start.toString('dd/MM/yyyy') !== end.toString('dd/MM/yyyy')) {
                $('#p__cci_dat_date_text span').html(start.format('DD/MM/YYYY') + ' até ' + end.format('DD/MM/YYYY'));
                $('#p__cci_dat_date').val(start.format('DD/MM/YYYY') + '-' + end.format('DD/MM/YYYY'));
            } else {
                $('#p__cci_dat_date_text span').html(start.format('DD/MM/YYYY'));
                $('#p__cci_dat_date').val(start.format('DD/MM/YYYY'));
            }
            filtrar(1);
        });
    });

    function loadCostCenter(cci_int_id) {
        $.gAjax.load(pagForm, {cci_int_id:cci_int_id}, '#loadCostCenter', function (response) {
            $('.default-content').hide();
            $('#loadClosing').show();
        });
    }

</script>