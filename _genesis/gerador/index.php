<?php
$__usr_cha_type = 'E';

require_once('../../_inc/global.php');
require_once('functions.php');

$header = new GHeader('Gerador de CRUD', 'back');
$header->addScript('functions.js');
$header->show(true);
//--------------------------------------------------------------------
$form = new GForm();

$mysql = new GDbMysql();
$mysql->execute("SHOW DATABASES;");
$arrDb = array();
while ($mysql->fetch()) {
    $arrDb[$mysql->res[0]] = $mysql->res[0];
}
$mysql->close();
echo $form->open('form_campos');
echo $form->addSelect('cmb_databases', $arrDb, MYSQL_BASE, 'Databases');
echo $form->addInput('button', 'btn_carregar_tabelas', false, array('value' => 'Carregar Tabelas'));

echo '<div id="lbl_tabelas"></div>';
echo '<div id="lbl_campos"></div>';
echo $form->close();
echo '<div id="lbl_gerador"></div>';


//--------------------------------------------------------------------
$footer = new GFooter();
$footer->show(true);
?>
<script>
    $(function() {
        jQuery('#btn_carregar_tabelas').trigger('click');
    });
</script>