<?php
require_once '../../_inc/global.php';

$form = new GForm();
$mysql = new GDbMysql();

$header = new GHeader('Solicitação');
$header->addLib(array('paginate', 'daterangepicker', 'datepicker', 'typeahead'));
$header->show(false, 'main/request/request.php');
// ---------------------------------- Header ---------------------------------//

try {
    $user = GSec::getUserSession();
    $usr_int_idcurrent = $user->getUsr_int_id();

    $pro_cha_typecurrent = $user->getProfile()->getPro_cha_type();

    $filterCombo = new GFilter();
    $filterCombo->setOrder(array('zon_var_name' => 'ASC'));

    $query = "SELECT zon_int_id, zon_var_name
                FROM vw_tax_zone " . $filterCombo->getWhere();
    $param = $filterCombo->getParam();
    $opt_zon_var_name = $mysql->executeCombo($query, $param);

    $filterCombo = new GFilter();
    $filterCombo->setOrder(array('cit_var_name' => 'ASC'));

    $query = "SELECT cit_int_id, cit_var_name
                FROM vw_tax_city " . $filterCombo->getWhere();
    $param = $filterCombo->getParam();
    $opt_cit_var_name = $mysql->executeCombo($query, $param);

    $filterCombo = new GFilter();
    $filterCombo->setOrder(array('coc_var_name' => 'ASC'));

    $query = "SELECT coc_int_id, coc_var_name
                FROM vw_tax_costcenter " . $filterCombo->getWhere();
    $param = $filterCombo->getParam();
    $opt_coc_var_nameall = $mysql->executeCombo($query, $param);

    $filterCombo->addFilter('AND', 'usr_int_id', '=', 'i', $usr_int_idcurrent);

    $query = "SELECT coc_int_id, coc_var_name
                FROM vw_tax_costcenter_user " . $filterCombo->getWhere();
    $param = $filterCombo->getParam();
    $opt_coc_var_name = $mysql->executeCombo($query, $param);

    $filterCombo = new GFilter();
    $filterCombo->setOrder(array('usr_var_name' => 'ASC'));
    $filterCombo->addFilter('AND', 'pro_cha_type', '=', 's', 'SOL');

    $query = "SELECT usr_int_id, usr_var_name
                FROM vw_adm_user " . $filterCombo->getWhere();
    $param = $filterCombo->getParam();
    $opt_usr_var_name = $mysql->executeCombo($query, $param);
} catch (Exception $e) {
    
}

$html .= '<div id="divTable" class="row">';
$html .= getWidgetHeader();
//<editor-fold desc="Formulário de Filtro">
$html .= $form->open('filter', 'form-inline filterForm');
$html .= $form->addInput('text', 'p__req_var_passenger', false, array('placeholder' => 'Passageiro', 'class' => 'sepV_b sepH_c m-wrap small'), false, false, false);
$html .= $form->addInput('hidden', 'p__req_dat_date', false, array('value' => ''), false, false, false);
$html .= '<div id="p__req_dat_date_text" class="btn sepV_b" style=""><i class="icon-calendar"></i>&nbsp;<span>Período...</span>&nbsp;&nbsp;<b class="caret"></b></div>';
$html .= $form->addSelect('p__zon_int_id', $opt_zon_var_name, '', false, array('class' => 'sepV_b hidden-phone', 'style' => 'width:150px;'), false, false, true, '', 'Todas as zonas', false);
if ($pro_cha_typecurrent == "ADM") {
    $html .= $form->addSelect('p__usr_int_id', $opt_usr_var_name, '', false, array('class' => 'sepV_b hidden-phone', 'style' => 'width:225px;'), false, false, true, '', 'Todos os solicitantes', false);
} else {
    $html .= $form->addInput('hidden', 'p__usr_int_id', false, array('value' => $usr_int_idcurrent), false, false, false);
}

$html .= "<br><br style='margin-bottom: 5px'>";
$html .= $form->addSelect('p__cit_int_id', $opt_cit_var_name, '', false, array('class' => 'sepV_b hidden-phone', 'style' => 'width:175px;'), false, false, true, '', 'Todas as cidades', false);
if ($pro_cha_typecurrent == "ADM") {
    $html .= $form->addSelect('p__coc_int_id', $opt_coc_var_nameall, '', false, array('class' => 'sepV_b hidden-phone', 'style' => 'width:210px;'), false, false, true, '', 'Todos os centros de custo', false);
} else {
    $html .= $form->addSelect('p__coc_int_id', $opt_coc_var_name, '', false, array('class' => 'sepV_b hidden-phone', 'style' => 'width:210px;'), false, false, true, '', 'Meus centros de custo', false);
}
$html .= $form->addSelect('p__req_cha_status', array("PEN" => "Pendente", "REJ" => "Rejeitada", "APP" => "Aprovada", "CLO" => "Fechada"), 'PEN', false, array('class' => 'sepV_b hidden-phone', 'style' => 'width:175px;'), false, false, true, '', 'Todos os status', false);

$html .= getBotoesFiltro();
$html .= getBotaoAdicionar();
$html .= $form->close();
//</editor-fold>

$paginate = new GPaginate('request', 'request_load.php', SYS_PAGINACAO);
$html .= $paginate->get();
$html .= '</div>'; //divTable
$html .= getWidgetFooter();
echo $html;

echo '<div id="divForm" class="row divForm">';
include 'request_form.php';
echo '</div>';

// ---------------------------------- Footer ---------------------------------//
$footer = new GFooter();
$footer->show();
?>
<script>
    var pagCrud = 'request_crud.php';
    var pagView = 'request_view.php';
    var pagLoad = 'request_load.php';
    var pagReport = 'request_relatorio.php';

    function filtrar(page) {
        requestLoad('', '', '', $('#filter').serializeObject(), page);
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
            filtrar(1);
        });
        $(document).on('click', '#p__btn_adicionar', function() {
            scrollTop();
            unselectLines();

            loadDistricSource('', '', false);
            loadDistricDestination('', '', false);
            changeType('');
            $('#req_cha_type_group button').removeClass('blue');

            changePassengerType('EMP', 'ins');

            $('#form .btn-group button, #form select, #form input, #form textarea').attr('disabled', false);
            $('#f__btn_salvar').show();

            showForm('divForm', 'ins', 'Add');
            $('#req_dat_date').val('');
        });
        $(document).on('click', '.l__btn_editar, tr.linhaRegistro td:not([class~="acoes"])', function() {
            var codigo = $(this).parents('tr.linhaRegistro').attr('id');
            var param = {acao: 'sel', 'req_int_id': codigo};

            scrollTop();
            selectLine(codigo);

            loadForm(pagCrud, param, function(json) {
                if (json.status === undefined){
                    showForm('divForm', 'upd', 'Edit');

                    loadDistricSource(json.cit_int_idsource, json.dis_int_idsource, true);
                    loadDistricDestination(json.cit_int_iddestination, json.dis_int_iddestination, true);
                    changeType(json.req_cha_type);
                    $('#req_cha_type_group button[attr="' + json.req_cha_type + '"]').click();

                    var type = (json.emp_int_id == null) ? 'EXT' : 'EMP';
                    changePassengerType(type, 'upd');
                    $('#btn_passengertype_group button[attr="' + type + '"]').click();

                    $('#form .btn-group button, #form select, #form input, #form textarea').attr('disabled', true);
                    $('#f__btn_salvar').hide();
                }
            });
        });

        $(document).on('click', '.l__btn_excluir', function() {
            var codigo = $(this).parents('tr.linhaRegistro').attr('id');
            var param = {acao: 'del', req_int_id: codigo};

            $.gDisplay.showYN("Do you really want to delete the selected item?", function() {
                $.gAjax.execCallback(pagCrud, param, false, function(json) {
                    if (json.status) {
                        filtrar();
                    }
                });
            });
        });
    });

    $('#p__req_dat_date_text span').html((moment()).format('DD/MM/YYYY') + ' - ' + (moment().add(6, 'days')).format('DD/MM/YYYY'));
    $('#p__req_dat_date').val((moment()).format('DD/MM/YYYY') + '-' + (moment().add(6, 'days')).format('DD/MM/YYYY'));

    $('#p__req_dat_date_text').daterangepicker({
        ranges: {
            'Hoje': [moment(), moment()],
            'Ontem': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Últimos 7 Dias': [moment().subtract(6, 'days'), moment()],
            'Próximos 7 Dias': [moment(), moment().add(6, 'days')],
            'Últimos 30 Dias': [moment().subtract(29, 'days'), moment()],
            'Últimos 60 Dias': [moment().subtract(59, 'days'), moment()],
            'Últimos 90 Dias': [moment().subtract(89, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Último Mês': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
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

    function loadDistricSource(cit_int_idsource, dis_int_idsource, edit) {
        $.gAjax.load(pagCrud, {acao: 'comboDistrictSource', cit_int_idsource:cit_int_idsource, dis_int_idsource:dis_int_idsource, edit: edit}, '#divComboDistrictSource');
    }

    function loadDistricDestination(cit_int_iddestination, dis_int_iddestination, edit) {
        $.gAjax.load(pagCrud, {acao: 'comboDistrictDestination', cit_int_iddestination:cit_int_iddestination, dis_int_iddestination:dis_int_iddestination, edit: edit}, '#divComboDistrictDestination');
    }

    function changeType(req_cha_type) {
        switch (req_cha_type) {
            case 'SCO': 
                $('#divSource').hide();
                $('#divDestination').show();
                $('#divSource input, #divSource select').removeAttr('validate').val('');
                $('#divDestination input, #divDestination select').attr('validate', 'required');
                break;
            case 'DCO': 
                $('#divSource').show();
                $('#divDestination').hide();

                $('#divSource input, #divSource select').attr('validate', 'required');
                $('#divDestination input, #divDestination select').removeAttr('validate').val('');
                break;
            case 'EXT': 
                $('#divSource, #divDestination').show();
                $('#divDestination input, #divDestination select, #divSource input, #divSource select').attr('validate', 'required');
                break;
            default: 
                $('#divSource, #divDestination').hide();
                $('#divDestination input, #divDestination select, #divSource input, #divSource select').removeAttr('validate').val('');
                break;
        }
        fillEmployeeData();
    }

    function changePassengerType(type, action){
        $('#btn_passengertype_group button').removeClass('active blue');
        $('#btn_passengertype_group button[rel="'+type+'"]').addClass('active blue');
        if(type == 'EMP'){
            $('#divEmployee').show();
            $('#divPassenger').hide();
            $('#emp_int_id_typeahead').attr('validate', 'required');
            $('#req_var_passenger').val('').removeAttr('validate');
        } else {
            $('#divEmployee').hide();
            $('#divPassenger').show();
            $('#req_var_passenger').attr('validate', 'required');
            $('#emp_int_id_typeahead').val('').removeAttr('validate');
        }
        if (action == "ins") {
            $('#req_var_addresssource, #req_var_addressdestination, #cit_int_iddestination, #dis_int_iddestination, #cit_int_idsource, #dis_int_idsource').val('');
        }
    }

    function changeEmployee(emp_int_id){
        if(emp_int_id != ''){
            var req_var_passenger = $('#emp_int_id option[value=' + emp_int_id + ']').text();
            $('#req_var_passenger').val(req_var_passenger);
        }
    }

    function fillEmployeeData(){
        switch ($('#req_cha_type').val()) {
            case 'SCO':
                if($('#acao').val() !== 'upd'){
                    $('#req_var_addressdestination').val($('#emp_var_address').val());
                    $('#cit_int_iddestination').val($('#cit_int_id').val());
                    loadDistricDestination($('#cit_int_id').val(), $('#dis_int_id').val());
                }
                break;
            case 'DCO': 
            case 'EXT': 
                if($('#acao').val() !== 'upd'){
                    $('#req_var_addresssource').val($('#emp_var_address').val());
                    $('#cit_int_idsource').val($('#cit_int_id').val());
                    loadDistricSource($('#cit_int_id').val(), $('#dis_int_id').val());
                }
                break;
            default: 
                break;
        }
    }
</script>