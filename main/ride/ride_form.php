<?php

require_once("../../_inc/global.php");

$html = '';
$mysql = new GDbMysql();
$form = new GForm();
$filter = new GFilter();

$rid_int_id = $_POST['rid_int_id'];

try {

    $query = "SELECT rid_cha_type, rid_dat_date
                FROM vw_tax_ride
               WHERE rid_int_id = ?";
    $param = array('i', $rid_int_id);
    $mysql->execute($query, $param);

    if ($mysql->fetch()) {
        $rid_cha_type = $mysql->res['rid_cha_type'];
        $rid_dat_date = $mysql->res['rid_dat_date'];
    }

    $mysql->freeResult();

    if (!empty($rid_cha_type)) {
        $filter->addFilter('AND', 'rid_cha_type', '=', 's', $rid_cha_type);
    }
    $filter->addFilter('AND', 'rid_cha_status', '<>', 's', 'CLO');
    $filter->addFilter('AND', 'rid_int_passengers', '<', 'i', '3');
    $filter->addFilter('AND', 'rid_int_id', '<>', 'i', $rid_int_id);
    $filter->addFilter('AND', 'rid_dat_date', '=', 's', $rid_dat_date);
    $query = "SELECT rid_int_id, CONCAT('Corrida ', rid_int_id, ' (', rid_hou_hour, ')  - ', rid_txt_passengerlist) FROM vw_tax_ride " . $filter->getWhere() . ' ORDER BY rid_int_id ASC';
    $opt_rid_int_id = $mysql->executeCombo($query, $filter->getParam());
    $query = "SELECT rid_int_id, rid_daf_date, rid_hou_hour, txc_int_id, rid_cha_status, zon_int_id, rid_cha_type, zon_var_namelist, rid_txt_passengerlist, rid_dec_total, rid_int_passengers,
                    IFNULL(fn_tax_sourcedestination(rid_int_id, 'S'), 'Continental') AS source,
                    IFNULL(fn_tax_sourcedestination(rid_int_id, 'D'), 'Continental') AS destination
                FROM vw_tax_ride
               WHERE rid_int_id = ?";
    $param = array('i', $rid_int_id);
    $mysql->execute($query, $param);

    if ($mysql->fetch()) {
        $zon_var_namelist = $mysql->res['zon_var_namelist'];
        $rid_txt_passengerlist = $mysql->res['rid_txt_passengerlist'];
        $rid_daf_date = $mysql->res['rid_daf_date'];
        $rid_hou_hour = $mysql->res['rid_hou_hour'];
        $rid_cha_status = $mysql->res['rid_cha_status'];
        $rid_dec_total = $mysql->res['rid_dec_total'];
        $source = $mysql->res['source'];
        $destination = $mysql->res['destination'];
        $txc_int_idcurrent = $mysql->res['txc_int_id'];
        $rid_int_passengers = $mysql->res['rid_int_passengers'];
        $zon_int_id = $mysql->res['zon_int_id'];

        $mysql->freeResult();

        $query = "SELECT txc_int_id, txc_var_name FROM vw_tax_taxicompany_zone where zon_int_id = ? ORDER BY txc_var_name ASC";
        $param = array('i', $zon_int_id);
        $opt_txc_var_name = $mysql->executeCombo($query, $param);


        $query = "SELECT req_int_id, usr_int_id, usr_var_name, req_var_passenger,
                         coc_var_name, req_daf_add, req_var_status
                    FROM vw_tax_request
                   WHERE rid_int_id = ? ORDER BY rid_int_order ASC";
        $param = array('i', $rid_int_id);
        $mysql->execute($query, $param);

        $html .= '<h4 class="font-green bold uppercase resumo">Corrida #' . $rid_int_id . ' - ' . $rid_daf_date .  ' - ' . $rid_hou_hour .  '</h4>';
        $html .= '<hr>';
        while ($mysql->fetch()) {

            $html .= '<div class="portlet light requests" id="' . $mysql->res['req_int_id'] . '">'; 
            $html .= '<div class="portlet-title">';
            $html .= '<div class="caption"><span class="caption-subject bold font-grey-gallery uppercase"> ' . primeiroUltimoNome($mysql->res['req_var_passenger']) . ' </span></div>';

            $html .= '<div class="tools">';
            $html .= '<a id=""><i class="btn_order fa fa-chevron-down" data-action="DOWN"></i></a>';
            $html .= '<a id=""><i class="btn_order fa fa-chevron-up" data-action="UP"></i></a>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '<div class="portlet-body">';
            $html .= '<p> Centro de custo: ' . $mysql->res['coc_var_name'] . '</p>';
            $html .= '<p>';
            if (!empty($rid_cha_type) && !empty($opt_rid_int_id) && $rid_cha_status != "CLO") {
                $html .= '<a class="btn_move font-green">Mover</a> | ';
            }
            if ($rid_int_passengers > 1 && $rid_cha_status != "CLO") {
                $html .= '<a class="btn_new font-green">Nova corrida</a> | ';
            }
            if ($rid_cha_status != "CLO") {
                $html .= '<a class="btn_reject font-green">Recusar</a></p>';
            }
            $html .= '</div>';
            $html .= '</div>';

            $html .= '</ul>';
            $html .= '</div>';
        }
        $bootboxMover = ' <form id="moveForm">';
        $bootboxMover .= '  <div id="move-box" class="modal fade bootbox" data-backdrop="true">';
        $bootboxMover .= '      <div class="modal-dialog" role="document">';
        $bootboxMover .= '          <div class="modal-content">';
        $bootboxMover .= '              <div class="modal-header"><button class="close" type="button" data-dismiss="modal">x</button><i class="icon-sitemap"></i> Mover </div>';
        $bootboxMover .= '              <div class="modal-body">';
        $bootboxMover .=                    $form->addSelect('rid_int_idmove', $opt_rid_int_id, 'Nova Corrida', false, array('class' => 'sepV_b hidden-phone', 'style' => 'width:auto;'), false, false, false, false, false, false);
        $bootboxMover .=                    $form->addInput('hidden', 'req_int_id', false, array('value' => ''), false, false, false);
        $bootboxMover .= '              </div>';
        $bootboxMover .= '              <div class="modal-footer">';
        $bootboxMover .= '                  <button id="b__btn_mover" type="submit" class="btn pull-right btn-info">Mover</button>';
        $bootboxMover .= '                  <button id="b__btn_fechar" class="btn pull-right sepV_a" data-dismiss="modal"><i class="fa fa-ban"></i> Fechar</button>';
        $bootboxMover .= '              </div>';
        $bootboxMover .= '          </div>';
        $bootboxMover .= '      </div>';
        $bootboxMover .= '  </div>';
        $bootboxMover .= '</form>';

        $html .= $bootboxMover;


        $bootboxLog = '<div id="log-box" class="modal fade bootbox" data-backdrop="true">';
        $bootboxLog .= '<div class="modal-dialog" role="document">';
        $bootboxLog .= '<div class="modal-content">';
        $bootboxLog .= '<div class="modal-header"><button class="close" type="button" data-dismiss="modal">x</button><i class="icon-search"></i> Log <b><span id="labelLog"></span></b></div>';
        $bootboxLog .= '<div class="modal-body">';
        $bootboxLog .= '<div id="loadLog"></div>';
        $bootboxLog .= '</div>';
        $bootboxLog .= '<div class="modal-footer">';
        $bootboxLog .= '<button id="b__btn_fechar" class="btn pull-right" data-dismiss="modal"><i class="icon-ban-circle"></i> Fechar</button>';
        $bootboxLog .= '</div>';
        $bootboxLog .= '</div>';
        $bootboxLog .= '</div>';
        $bootboxLog .= '</div>';

        $html .= $bootboxLog;

        $bootboxRecusar = '<form id="rejectForm">';
        $bootboxRecusar .= '<div id="reject-box" class="modal fade bootbox" data-backdrop="true">';
        $bootboxRecusar .= '<div class="modal-dialog" role="document">';
        $bootboxRecusar .= '<div class="modal-content">';

        $bootboxRecusar .= '<div class="modal-header"><button class="close" type="button" data-dismiss="modal">x</button><i class="icon-sitemap"></i> Recusar </div>';

        $bootboxRecusar .= '<div class="modal-body">';
        $bootboxRecusar .= $form->addTextarea('req_txt_comment', '', 'Justificativa', array('validate' => 'required'), false, false, true);
        $bootboxRecusar .= $form->addInput('hidden', 'req_int_id', false, array('value' => ''), false, false, false);
        $bootboxRecusar .= '</div>';

        $bootboxRecusar .= '<div class="modal-footer">';
        $bootboxRecusar .= '<button id="b__btn_recusar" type="submit" class="btn pull-right btn-danger"><i class="fa fa-ban"></i> Recusar</button>';
        $bootboxRecusar .= '<button id="b__btn_fechar" class="btn pull-right sepV_a" data-dismiss="modal">Fechar</button>';
        $bootboxRecusar .= '</div>';

        $bootboxRecusar .= '</div>';
        $bootboxRecusar .= '</div>';
        $bootboxRecusar .= '</div>';
        $bootboxRecusar .= '</form>';

        $html .= $bootboxRecusar;
        $html .= '<div class="row">';
        $html .= '<h4>Origem: ' . $source . '</h4>';
        $html .= '<h4>Destino: ' . $destination . '</h4>';
        $html .= '<h4 class="valor-corrida">Valor da corrida: ' . GF::numberFormat($rid_dec_total) . '</h4>';
        $html .= '</div>';
        $html .= '<div class="row sepH_c">';
        $html .= '<h4 style="float: left; height: 20px; margin: 7px 5px 0px 0px;">Mudar o horário da corrida para:</h4>';
        $html .= $form->addSelect('r__rid_hou_hour', $__arrayHoras , $rid_hou_hour, '', array('validate' => 'required', 'class' => 'sepV_b m-wrap', 'style' => 'width:auto;'), false, false, true, '', 'Hora', false);
        $html .= '<h4 style="float: left; height: 20px; margin: 7px 5px 0px 0px;">Companhia de Táxi:</h4>';
        $html .= $form->addSelect('r__txc_int_id', $opt_txc_var_name, $txc_int_idcurrent, '', array('class' => 'sepV_b hidden-phone', 'style' => 'width:auto;'), array('style' => 'text-align: center; margin: 0 auto; display: block'), false, true, '', 'Escolha uma companhia', false);
        $html .= '</div>';
        $html .= '<div class="buttons text-center row">';
        if ($rid_cha_status == "PEN") {
            $html .= '<button id="r__btn_aprovar" type="submit" class="btn btn-success">Enviar Pedido</button>';
        } else if ($rid_cha_status != "CLO") {
            $html .= '<button id="r__btn_cancelar" type="submit" class="btn sepV_a">Cancelar</button>';
        }
        $html .= '<button id="r__btn_log" type="submit" class="btn sepV_a">Log</button>';
        $html .= '</div>';

    } else {
        $html .= '<div class="nenhumResultado">Favor selecionar uma corrida.</div>';
    }

    echo $html;

} catch (GDbException $exc) {
    echo $exc->getError();
}
?>

<script type="text/javascript">
    $(function(){
        var pagCrud = 'ride_crud.php';
        var rid_int_id = '<?php echo $rid_int_id; ?>';
        var rid_daf_date = '<?php echo $rid_daf_date; ?>';

        $('.requests .btn_order').click(function(){
            var req_int_id = $(this).parents('.requests').attr('id');
            var action = $(this).attr('data-action');
            var param = {acao: 'order', req_int_id:req_int_id, action:action};
            $.gAjax.execCallback(pagCrud, param, false, function(json) {
                if (json.status) {
                    filtrar();
                    loadRide(rid_int_id);
                }
            });
        });

        $('.requests .btn_move').click(function(){
            $('#move-box').modal('show');
            var req_int_id = $(this).parents('.requests').attr('id');
            $('#moveForm #req_int_id').val(req_int_id);
        });

        $('#r__btn_log').click(function() {
            var param = {rid_int_id: rid_int_id}

            $.gAjax.load('ride_log.php', param, '#loadLog');
            $('#log-box').modal('show');
        });

        $('#moveForm').submit(function(ev) {
            ev.preventDefault();
            var param = {acao: 'move', req_int_id: $('#moveForm #req_int_id').val(), rid_int_idmove:$('#moveForm #rid_int_idmove').val()};
            $.gAjax.execCallback(pagCrud, param, false, function(json) {
                if (json.status) {
                    filtrar();
                    loadRide(rid_int_id);
                    $('#move-box').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }
            });
        });

        $('.requests .btn_new').click(function(){
            var req_int_id = $(this).parents('.requests').attr('id');
            var param = {acao: 'new', req_int_id:req_int_id};
            $.gDisplay.showYN("Você realmente deseja mover o passageiro para uma nova corrida?", function() {
                $.gAjax.execCallback(pagCrud, param, false, function(json) {
                    if (json.status) {
                        filtrar();
                        loadRide(rid_int_id);
                    }
                });
            });
        });

        $('.requests .btn_reject').click(function(){
            $('#reject-box').modal('show');
            var req_int_id = $(this).parents('.requests').attr('id');
            $('#rejectForm #req_int_id').val(req_int_id);
        });

        $('#rejectForm').submit(function(ev) {
            var param = {acao: 'reject', req_int_id: $('#rejectForm #req_int_id').val(), req_txt_comment: $('#rejectForm #req_txt_comment').val()};
            if ($('#rejectForm').gValidate()) {
                $.gAjax.execCallback(pagCrud, param, false, function(json) {
                    if (json.status) {
                        filtrar();
                        loadRide(rid_int_id);
                        $('#reject-box').modal('hide');
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                    }
                });
            }
            return false;
        });

        $('#r__txc_int_id').change(function(event) {
            var param = {acao: 'taxiCompany', req_int_id: $(this).parents('.requests').attr('id'), rid_int_id: rid_int_id, txc_int_id: $(this).val()};
            var txc_int_id = $(this).val();
            $.gAjax.execCallback(pagCrud, param, false, function(json) {
                if (json.status) {
                    filtrar();
                    $('.valor-corrida').text('Valor da Corrida: '+numberFormat(json.valor));
                }
            });
        });

        $('#r__rid_hou_hour').change(function(event) {
            rid_hou_hour = $(this).val()
            var param = {acao: 'changeHour', rid_int_id: rid_int_id, rid_hou_hour: rid_hou_hour};
            $.gAjax.execCallback(pagCrud, param, false, function(json) {
                if (json.status) {
                    filtrar();
                    loadRide(rid_int_id);
                }
            });
        });

        $('#r__btn_aprovar').click(function(event) {
            var param = {acao: 'approve', rid_int_id: rid_int_id, txc_int_id: $('#r__txc_int_id').val()};
            $.gDisplay.showYN("Tem certeza que deseja <b>aprovar</b> esta corrida?", function() {
                $.gAjax.execCallback(pagCrud, param, false, function(json) {
                    if (json.status) {
                        filtrar();
                        $('#loadRide').html('');
                    }
                });
            });
        });

        $('#r__btn_cancelar').click(function(event) {
            var param = {acao: 'cancel', rid_int_id: rid_int_id};
            $.gDisplay.showYN("Tem certeza que deseja <b>cancelar</b> esta corrida?", function() {
                $.gAjax.execCallback(pagCrud, param, false, function(json) {
                    if (json.status) {
                        filtrar();
                    }
                });
            });
        });

    });
</script>
