<?php
$form = new GForm();
$mysql = new GDbMysql();

//<editor-fold desc="Header">
$title = 'Importar Centro de Custo';
$tools = '<a id="f__btn_voltar"><i class="fa fa-arrow-left font-blue-steel"></i> <span class="hidden-phone font-blue-steel bold uppercase">Voltar</span></a>';
$htmlForm .= getWidgetHeader($title, $tools);
//</editor-fold>
//<editor-fold desc="Formulário">
$htmlForm .= $form->open('form', 'form-vertical form');
$htmlForm .= $form->addInput('hidden', 'acao', false, array('value' => 'ins', 'class' => 'acao'), false, false, false);

$htmlForm .= '
    <div class="row" style="margin-bottom: 10px;">
        <div class="col-md-6">
            <span class="btn btn-success fileinput-button sepV_b">
                <i class="fa fa-cloud-upload"></i>
                <span>Selecionar arquivo de importação</span>
                <input id="fileupload" type="file" name="files[]" multiple>
            </span>
        </div>
    </div>';

$htmlForm .= '<div class="resultado-importacao"><h4>Resultado da Importação</h4><div class="result" style="min-height: 20px; background-color: #efefef; margin-bottom: 20px;"></div></div>';
$htmlForm .= '<div class="form-actions">';
$htmlForm .= $form->addButton('f__btn_cancelar', '<i class="fa fa-chevron-left"></i> <span class="hidden-phone">Voltar</span>', array('class' => 'btn sepH_a sepV_a pull-left'));

$htmlForm .= '</div>';
$htmlForm .= $form->close();
//</editor-fold>
$htmlForm .= getWidgetFooter();

echo $htmlForm;
?>
<script>
    // $(function() {
    //     var pagCrud = 'motive_crud.php';

    //     $('#form').submit(function() {
    //         $('#p__selecionado').val($('#mot_int_id').val());
    //         if ($('#form').gValidate()) {
    //             $.gAjax.execCallback(pagCrud, $('#form').serializeArray(), false, function(json) {
    //                 if (json.status) {
    //                     showList(true);
    //                 }
    //             });
    //         }
    //         return false;
    //     });
    var pagCrud = 'costcenter_crud.php';

        $('#f__btn_cancelar, #f__btn_voltar').click(function() {
            showList();
            filtrar(1);
            $('.resultado-importacao h4').text('Resultado da Importação');
            $('.resultado-importacao .result').html('');
            return false;
        });

        $('#fileupload').fileupload({
            url: pagCrud,
            dataType: 'json',
            formData: {acao: 'insArq'},
            limitConcurrentUploads: 1,
            imageMaxWidth: 128,
            imageMaxHeight: 96,
            done: function(e, data) {
                $.gDisplay.loadStop('html');
                if (data.result != null) {
                    $('.resultado-importacao h4').text("Resultado da Importação - " + data.files[0].name);
                    $('.resultado-importacao .result').html(data.result.msg);
                } else {
                    console.log('erro');
                }
            },
            add: function(e, data) {
                var goUpload = true;
                var uploadFile = data.files[0];
                if (!(/\.(csv)$/i).test(uploadFile.name)) {
                    $.gDisplay.showError('O tipo do arquivo <b>' + uploadFile.name + '</b> é inválido');
                    goUpload = false;
                }
                if (goUpload === true) {
                    $.gDisplay.loadStart('html');
                    data.submit();
                }
            }
        });
</script>