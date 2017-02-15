<?php

require_once("../../_inc/global.php");

$html = '';
$mysql = new GDbMysql();
$form = new GForm();

$rid_int_id = $_POST['rid_int_id'];

try {
    $query = "SELECT req_int_id, req_var_passenger,
                     IFNULL(fn_tax_requestsourcedestination(req_int_id, 'S'), 'Continental') AS source,
                     IFNULL(fn_tax_requestsourcedestination(req_int_id, 'D'), 'Continental') AS destination,
                     coc_var_name,req_txt_comment
                FROM vw_tax_request
               WHERE rid_int_id = ? ORDER BY rid_int_order ASC";
    $param = array('i', $rid_int_id);
    $mysql->execute($query, $param);

    $title = '<span class="acaoTitulo">Encerramento de Corrida</span>';
    $tools = '<a id="f__btn_voltar"><i class="fa fa-arrow-left font-blue-steel"></i> <span class="hidden-phone font-blue-steel bold uppercase">Voltar</span></a>';
    $html .= getWidgetHeader($title, $tools);

    $html .= $form->open('form', 'form-vertical form');
    $html .= $form->addInput('hidden', 'acao', false, array('value' => 'close'), false, false, false);
    $html .= $form->addInput('hidden', 'rid_int_id', false, array('value' => $rid_int_id), false, false, false);
    $html .= '<div class="row">';
    $html .= '<div class="col-md-3">' . $form->addInput('text', 'rid_daf_arrival', 'Data*', array('class' => '', 'size' => '80', 'maxlength' => '10','validate' => 'required')) . '</div>';
    $html .= '<div class="col-md-2">' . $form->addInput('text', 'rid_hou_arrival', 'Hora*', array('class' => '', 'size' => '80', 'maxlength' => '5','validate' => 'required', 'placeholder' => 'hh:mm')) . '</div>';
    $html .= '</div>';
    $html .= '<div class="row">';
    $html .= '<div class="col-md-2">' . $form->addInput('text', 'rid_hor_stopped', 'Horas Paradas', array('class' => '', 'size' => '80', 'maxlength' => '5', 'placeholder' => 'hh:mm')) . '</div>';
    $html .= '<div class="col-md-3">' . $form->addInput('text', 'rid_dec_parking', 'Valor do Estacionamento', array('class' => '', 'size' => '80', 'maxlength' => '10', 'placeholder' => '0,00')) . '</div>';
    $html .= '<div class="col-md-2 pull-right">' . $form->addCheckbox('rid_cha_transfer', 'Deslocamento', array('value' => 'Y')) . '</div>';
    $html .= '</div>';
    $html .= '<div class="row">';
    $html .= '<div class="col-md-6">' . $form->addInput('text', 'rid_var_driver', 'Nome do Motorista*', array('class' => '', 'size' => '80', 'validate' => 'required')) . '</div>';
    $html .= '<div class="col-md-4">' . $form->addInput('text', 'rid_var_plate', 'Placa do Veículo*', array('class' => '', 'size' => '80', 'maxlength' => '8','validate' => 'required', 'placeholder' => 'ABC-1234')) . '</div>';
    $html .= '</div>';
    $html .= '<div class="row">';
    $html .= '<div class="col-md-12">' . $form->addTextarea('req_txt_comment', '', 'Observações', array('class' => 'textarea', 'cols' => '10', 'rows' => '3')) . '</div>';
    $html .= '</div>';
    $html .= '<div id="requests-table"></div>';
    // if ($mysql->numRows() > 0) {
    //     $html .= '<table class="table table-striped table-hover">';
    //     $html .= '<thead>';
    //     $html .= '<tr>';
    //     $html .= '<th>Nome</th>';
    //     $html .= '<th>Centro de Custo</th>';
    //     $html .= '<th>Origem</th>';
    //     $html .= '<th>Destino</th>';
    //     $html .= '<th>Observações</th>';
    //     $html .= '<th>Ação</th>';
    //     $html .= '</tr>';
    //     $html .= '</thead>';
    //     $html .= '<tbody>';
    //     while ($mysql->fetch()) {
    //         $html .= '<tr id="' . $mysql->res['req_int_id'] . '">';
    //         $html .= '<td class="name">' . $mysql->res['req_var_passenger'] . '</td>';
    //         $html .= '<td class="coc">' . $mysql->res['coc_var_name'] . '</td>';
    //         $html .= '<td>' . $mysql->res['source'] . '</td>';
    //         $html .= '<td>' . $mysql->res['destination'] . '</td>';
    //         $html .= '<td class="comment">' . $mysql->res['req_txt_comment'] . '</td>';
    //         $html .= '<td>' . $form->addButton('btn_commentrequest', '<i class="fa fa-pencil"></i>', array('class' => 'btn hidden-phone btn_commentrequest')) . '</td>';
    //         $html .= '</tr>';
    //     }
    //     $html .= '</tbody>';
    //     $html .= '</table>';
    // } else {
    //     $html .= '<div class="nenhumResultado">Nenhum resultado encontrado.</div>';
    // }
    $html .= '<div class="row">';
    $html .= '<div class="col-md-12">';
    $html .= '<button id="f__btn_salvar" type="submit" class="btn btn-success pull-left">Encerrar Corrida</button>';
    $html .= '<button id="f__btn_cancelar" type="submit" class="btn sepV_a pull-left">Cancelar</button>';
    $html .= '<button id="f__btn_imprimir" type="button" class="btn blue-steel pull-right">Emitir Voucher</button>';
    $html .= '</div>';
    $html .= '</div>';

    $html .= $form->close();

    $bootboxComentario = '<form id="passageiroForm">';
    $bootboxComentario .= '<div id="passageiro-box" class="modal fade bootbox" data-backdrop="true">';
    $bootboxComentario .= '<div class="modal-dialog" role="document">';
    $bootboxComentario .= '<div class="modal-content">';

    $bootboxComentario .= '<div class="modal-header"><button class="close" type="button" data-dismiss="modal">x</button><i class="fa fa-user"></i> Passageiro </div>';

    $bootboxComentario .= '<div class="modal-body">';
    $bootboxComentario .= '<div class="row">';
    $bootboxComentario .= '<div class="col-md-4">';
    $bootboxComentario .= '<div class="passageiro-name"></div>';
    $bootboxComentario .= '</div>';
    $bootboxComentario .= '<div class="col-md-4">';
    $bootboxComentario .= '<div class="passageiro-coc"></div>';
    $bootboxComentario .= '</div>';
    $bootboxComentario .= '<div class="col-md-4">';
    $bootboxComentario .= $form->addCheckbox('req_cha_absent', 'Não compareceu', array('value' => 'Y'));
    $bootboxComentario .= '</div>';
    $bootboxComentario .= '</div>';
    $bootboxComentario .= '<div class="row">';
    $bootboxComentario .= $form->addInput('hidden', 'acao', false, array('value' => 'commentRequest'), false, false, false);
    $bootboxComentario .= $form->addInput('hidden', 'req_int_id', false, array('value' => ''), false, false, false);
    $bootboxComentario .= '<div class="col-md-12">';
    $bootboxComentario .= $form->addTextarea('req_txt_comment', '', 'Observações:', array('validate' => 'required'), false, false, true);
    $bootboxComentario .= '</div>';
    $bootboxComentario .= '</div>';
    $bootboxComentario .= '</div>';

    $bootboxComentario .= '<div class="modal-footer">';
    $bootboxComentario .= '<button id="b__btn_salvar" type="submit" class="btn btn-success pull-right btn-danger">Salvar</button>';
    $bootboxComentario .= '<button id="b__btn_fechar" class="btn pull-right sepV_a" data-dismiss="modal">Fechar</button>';
    $bootboxComentario .= '</div>';

    $bootboxComentario .= '</div>';
    $bootboxComentario .= '</div>';
    $bootboxComentario .= '</div>';
    $bootboxComentario .= '</form>';

    $html .= $bootboxComentario;
    $html .= getWidgetFooter();
    echo $html;

} catch (GDbException $exc) {
    echo $exc->getError();
}
?>

<script type="text/javascript">
    $(function(){
        var pagCrud = 'closing_crud.php';
        var rid_int_id = '<?php echo $rid_int_id; ?>';

        var today = new Date();
        $('#rid_daf_arrival').datepicker({language:'pt-BR', format:  'dd/mm/yyyy', startDate: today});


        $('#rid_dec_parking').maskMoney({decimal: ",", thousands: "."});
        $.mask.definitions['A'] = "[A-Za-z]"
        $('#rid_hou_arrival, #rid_hor_stopped').mask('99:99');
        $('#rid_var_plate').mask('AAA-9999');

        $('body').on('click', '.btn_commentrequest', function() {
            $('#passageiro-box').modal('show');
            var req_int_id = $(this).parents('tr').attr('id');
            var nome = $(this).parents('tr').find('td.name').text();
            var coc = $(this).parents('tr').find('td.coc').text();
            var req_int_id = $(this).parents('tr').attr('id');
            $('#passageiroForm #req_int_id').val(req_int_id);
            $('#passageiroForm .passageiro-name').html('<h5>Passageiro:</h5><p>'+nome+'</p>');
            $('#passageiroForm .passageiro-coc').html('<h5>Centro de Custo:</h5><p>'+coc+'</p>');
        });

        $('#passageiroForm').submit(function(ev) {
            ev.preventDefault();
            var param = $('#passageiroForm').serializeObject();
            if ($('#passageiroForm').gValidate()) {
                $.gAjax.execCallback(pagCrud, param, false, function(json) {
                    if (json.status) {
                        filtrar();
                        $('#passageiro-box').modal('hide');
                        $('.requests-table tr#'+param.req_int_id+' td.comment').text(param.req_txt_comment);
                        clearForm('#passageiroForm');
                        $('#passageiroForm #acao').val(param.acao);
                    }
                });
            }
            return false;
        });

        $('#f__btn_salvar').click(function(event) {
            if ($('#form').gValidate()) {
                $.gDisplay.showYN("Tem certeza que deseja <b>encerrar</b> esta corrida?", function() {
                    $.gAjax.execCallback(pagCrud, $('#form').serializeArray(), false, function(json) {
                        if (json.status) {
                            $('#loadClosing').hide();
                            $('.default-content').show();
                            filtrar(1);
                        }
                    });
                });
            }
            return false;
        });

        $('#f__btn_imprimir').click(function(event) {
            window.open('../voucher/voucher_print.php?id='+$('#rid_int_id').val(),'_blank');
            return false;
        });

        $('#f__btn_cancelar, #f__btn_voltar').click(function(event) {
            showList();
            return false;
        });

    });
</script>
