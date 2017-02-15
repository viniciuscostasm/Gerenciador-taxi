<?php
$__usr_cha_type = 'E';

require_once('../../_inc/global.php');

//--------------------------------------------------------------------
$form = new GForm();

$database = $_POST['database'];
 
$mysql = new GDbMysql();
$mysql->execute("SELECT TABLE_NAME, TABLE_COMMENT FROM information_schema.tables WHERE table_schema = '" . $database . "'");
$arrTb = array();
while ($mysql->fetch()) {
    $arrTb[$mysql->res[0] . '|' . $mysql->res[1]] = $mysql->res[0];
}
$mysql->close();
echo $form->addSelect('cmb_tables', $arrTb, '-1', 'Tabelas');
echo $form->addSelect('cmb_procedure', array('0' => 'Não', '1' => 'Sim'), '1', 'Procedure');
echo $form->addInput('button', 'btn_carregar_campos', false, array('value' => 'Carregar Campos')) . "<br/>";
?>
<script>
    jQuery(":button").button();
    jQuery("#btn_carregar_campos").click(function() {
        var db = jQuery("#cmb_databases").val();
        var tb = jQuery("#cmb_tables").val();
        if (db != '-1' && tb != '-1')
            jQuery.gAjax.load('carregarCampos.php', {database: db, table: tb}, '#lbl_campos');
        else
            jQuery.gDisplay.showError('Selecione um Database ou uma Tabela');
    });
</script>