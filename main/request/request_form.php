<?php
$form = new GForm();
$mysql = new GDbMysql();

$opt_zon_var_name = $mysql->executeCombo("SELECT zon_int_id, zon_var_name FROM tax_zone ORDER BY zon_var_name;");
$opt_cit_var_name = $mysql->executeCombo("SELECT cit_int_id, cit_var_name FROM tax_city ORDER BY cit_var_name;");

//<editor-fold desc="Header">
$title = '<span class="acaoTitulo"></span>';
$tools = '<a id="f__btn_voltar"><i class="fa fa-arrow-left font-blue-steel"></i> <span class="hidden-phone font-blue-steel bold uppercase">Voltar</span></a>';
$htmlForm .= getWidgetHeader($title, $tools);
//</editor-fold>
//<editor-fold desc="Formulário">
$htmlForm .= $form->open('form', 'form-vertical form');
$htmlForm .= $form->addInput('hidden', 'acao', false, array('value' => 'ins', 'class' => 'acao'), false, false, false);
$htmlForm .= $form->addInput('hidden', 'req_int_id', false, array('value' => ''), false, false, false);

$opt_emp_int_id = $mysql->executeCombo("SELECT emp_int_id, emp_var_name FROM vw_tax_employee ORDER BY emp_var_name;");

$filterCombo = new GFilter();
$filterCombo->setOrder(array('coc_var_name' => 'ASC'));

$query = "SELECT coc_int_id, coc_var_name
            FROM vw_tax_costcenter " . $filterCombo->getWhere();
$param = $filterCombo->getParam();
$opt_coc_int_idall = $mysql->executeCombo($query, $param);

$filterCombo->addFilter('AND', 'usr_int_id', '=', 'i', $usr_int_idcurrent);

$query = "SELECT coc_int_id, coc_var_name
            FROM vw_tax_costcenter_user " . $filterCombo->getWhere();
$param = $filterCombo->getParam();
$opt_coc_int_id = $mysql->executeCombo($query, $param);

$coc_list = array();

foreach ($opt_coc_int_id as $key => $value) {
    $coc_list[] = $value[0];
}

$coc_list = implode(',', $coc_list);

$opt_mot_int_id = $mysql->executeCombo("SELECT mot_int_id, mot_var_name FROM vw_tax_motive ORDER BY mot_var_name;");
$opt_cit_int_id = $mysql->executeCombo("SELECT cit_int_id, cit_var_name FROM vw_tax_city ORDER BY cit_var_name;");
$opt_zon_int_id = $mysql->executeCombo("SELECT zon_int_id, zon_var_name FROM vw_tax_zone ORDER BY zon_var_name;");

$htmlForm .= $form->addButtonPassengerType('btn_passengertype');
$htmlForm .= $form->addInput('hidden', 'emp_int_id', false, array('value' => ''), false, false, false);
$htmlForm .= $form->addInput('hidden', 'emp_var_address', false, array('value' => ''), false, false, false);
$htmlForm .= $form->addInput('hidden', 'cit_int_id', false, array('value' => ''), false, false, false);
$htmlForm .= $form->addInput('hidden', 'dis_int_id', false, array('value' => ''), false, false, false);

$htmlForm .= '<div id="divEmployee">' . $form->addInput('text', 'emp_int_id_typeahead', 'Funcionário*', array('class' => '', 'size' => '100', 'maxlength' => '100','validate' => 'required', 'autocomplete' => 'off')) . '</div>';
$htmlForm .= '<div id="divPassenger">' . $form->addInput('text', 'req_var_passenger', 'Passageiro*', array('class' => '', 'size' => '80', 'maxlength' => '100','validate' => 'required')) . '</div>';

$htmlForm .= '<hr/>';

$htmlForm .= '<div class="row">';
$htmlForm .= '<div class="col-md-3">' . $form->addInput('text', 'req_dat_date', 'Data*', array('class' => '', 'size' => '80', 'maxlength' => '10','validate' => 'required')) . '</div>';
$htmlForm .= '<div class="col-md-2">' . $form->addSelect('req_var_hour', $__arrayHoras , '', 'Hora*', array('validate' => 'required'), false, false, true, '', 'Selecione...') . '</div>';
$htmlForm .= '<div class="col-md-3"><div id="divCocForm">';
if ($pro_cha_typecurrent == "ADM") {
    $htmlForm .= $form->addSelect('coc_int_id', $opt_coc_int_idall , '', 'Centro de Custo*', array('validate' => 'required'), false, false, true, '', 'Selecione...') ;
} else {
    $htmlForm .= $form->addSelect('coc_int_id', $opt_coc_int_id , '', 'Centro de Custo*', array('validate' => 'required'), false, false, true, '', 'Selecione...');
}
$htmlForm .= '</div></div>';
$htmlForm .= '<div class="col-md-4">' . $form->addSelect('mot_int_id', $opt_mot_int_id , '', 'Motivo*', array('validate' => 'required'), false, false, true, '', 'Selecione...') . '</div>';
$htmlForm .= '</div>';

$htmlForm .= '<hr/>';

$htmlForm .= $form->addButtonRequestType('req_cha_type', false, '');

$htmlForm .= '<div class="row">';
$htmlForm .= '<div id="divSource" class="col-md-6">';
$htmlForm .= '<h3>Endereço de Origem</h3>';
$htmlForm .= $form->addInput('text', 'req_var_addresssource', 'Endereço*', array('class' => '', 'size' => '80', 'maxlength' => '255'));
$htmlForm .= '<div class="row">';
$htmlForm .= '<div class="col-md-6">' . $form->addSelect('cit_int_idsource', $opt_cit_int_id , '', 'Cidade*', array('class' => 'combobox'), false, false, true, '', 'Selecione...', true, false) . '</div>';
$htmlForm .= '<div class="col-md-6" id="divComboDistrictSource"></div>';
$htmlForm .= '</div>';
$htmlForm .= '</div>';

$htmlForm .= '<div id="divDestination" class="col-md-6">';
$htmlForm .= '<h3>Endereço de Destino</h3>';
$htmlForm .= $form->addInput('text', 'req_var_addressdestination', 'Endereço*', array('class' => '', 'size' => '80', 'maxlength' => '255'));
$htmlForm .= '<div class="row">';
$htmlForm .= '<div class="col-md-6">' . $form->addSelect('cit_int_iddestination', $opt_cit_int_id , '', 'Cidade*', array('class' => 'combobox'), false, false, true, '', 'Selecione...', true, false) . '</div>';
$htmlForm .= '<div class="col-md-6" id="divComboDistrictDestination"></div>';
$htmlForm .= '</div>';
$htmlForm .= '</div>';
$htmlForm .= '</div>'; // .row

$htmlForm .= '<hr/>';

$htmlForm .= $form->addTextarea('req_txt_comment', '', 'Obs', array('class' => 'textarea', 'cols' => '10', 'rows' => '3'));


$htmlForm .= '<div class="form-actions">';
$htmlForm .= getBotoesAcao(true);
$htmlForm .= '</div>';
$htmlForm .= $form->close();
//</editor-fold>
$htmlForm .= getWidgetFooter();

echo $htmlForm;
?>
<script>
    $(function() {
        var pagCrud = 'request_crud.php';
        var pagTypeahead = 'request_typeahead.php';
        var today = new Date(); 
        $('#req_dat_date').datepicker({language:'pt-BR', format:  'dd/mm/yyyy', startDate: today});
        $('#req_dat_date').keydown(function () {
            return false;
        });
        $('#form').submit(function() {
            $('#p__selecionado').val($('#req_int_id').val());
            if ($('#form').gValidate()) {
                $.gAjax.execCallback(pagCrud, $('#form').serializeArray(), false, function(json) {
                    if (json.status) {
                        if ($('#p__usr_int_id option').length == 0) {
                            var usr_int_id = $('#p__usr_int_id').val();
                        }
                        var req_dat_date = $('#req_dat_date').val();
                        showList();
                        $('#p__usr_int_id').val(usr_int_id);
                        $('#req_dat_date').val(req_dat_date);
                        filtrar(1);
                    }
                });
            }
            return false;
        });

        $('#f__btn_cancelar, #f__btn_voltar').click(function() {
            showList();
            return false;
        });

        $('#f__btn_excluir').click(function() {
            var codigo = $('#req_int_id').val();
            var param = {acao: 'del', req_int_id: codigo};

            $.gDisplay.showYN("Do you really want to delete the selected item?", function() {
                $.gAjax.execCallback(pagCrud, param, false, function(json) {
                    if (json.status) {
                        showList(true);
                    }
                });
            });
        });

        $('#cit_int_idsource').change(function(){
            loadDistricSource($(this).val(), '');
        });
        $('#cit_int_iddestination').change(function(){
            loadDistricDestination($(this).val(), '');
        });

        $('#req_cha_type_group button').click(function(){
            changeType($(this).attr('rel'));
        });

        var employees = [];
        $("#emp_int_id_typeahead").typeahead({
            onSelect: function(item) {
                $.each(employees, function(i, employee){
                    if(employee.emp_int_id == item.value){
                        $('#emp_var_address').val(employee.emp_var_address);
                        $('#cit_int_id').val(employee.cit_int_id);
                        $('#dis_int_id').val(employee.dis_int_id);
                        $('#emp_int_id_typeahead, #req_var_passenger').val(employee.emp_var_name);
                        fillEmployeeData();
                        var param = {acao: 'comboCoc', emp_int_id: employee.emp_int_id, where: '<?php echo $coc_list; ?>'};
                        $.gAjax.load(pagCrud, param, '#divCocForm');
                    }
                });
                $('#emp_int_id').val(item.value);
            },
            ajax: {
                url: pagTypeahead,
                timeout: 300,
                valueField: "emp_int_id",
                displayField: "emp_var_namekey",
                triggerLength: 3,
                method: "post",
                preDispatch: function(query) {
                    $('#emp_int_id_typeahead').parent().append('<span class="loading-circle"></span>');
                    return {
                        emp_var_namekey: query
                    }
                },
                preProcess: function(data) {
                    employees = data;
                    $('#emp_int_id_typeahead').parent().find('.loading-circle').remove();
                    return data;
                }
            }
        });

        $('#emp_int_id_typeahead').blur(function(){
            //TODO: Não deixar ele sair do campo caso esteja
        });
        

        $('#emp_int_id').change(function(){
            changeEmployee($(this).val());
        });

        $('#btn_passengertype_group button').click(function(){
            changePassengerType($(this).attr('rel'));
        });
    });
</script>