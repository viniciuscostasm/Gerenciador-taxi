<?php
$__usr_cha_type = 'E';

require_once('../../_inc/global.php');
require_once('functions.php');
//--------------------------------------------------------------------

$form = new GForm();

$database = $_POST['database'];
$arrTable = explode('|', $_POST['table']);
$table = $arrTable[0];
$titleTable = $arrTable[1];
$procedure = $_POST['procedure'];

$mysql = new GDbMysql();
$mysql->execute("SHOW FULL COLUMNS FROM $database.$table;");

$libData = '';
$fields = '';
$pk = '';
$un = '';
$fk = false;
$arrCol = array();
while ($mysql->fetch()) {
    $col = $mysql->res[0];
    $arrCol[$col]['field'] = $mysql->res[0];
    $arrCol[$col]['type'] = $mysql->res[1];
    $arrCol[$col]['null'] = $mysql->res[3];
    $arrCol[$col]['key'] = $mysql->res[4];
    $arrCol[$col]['default'] = $mysql->res[5];
    $arrCol[$col]['extra'] = $mysql->res[6];
    $arrCol[$col]['comment'] = $mysql->res[8];
    if (strpos($arrCol[$col]['comment'], '|')) {
        $comment = explode("|", $arrCol[$col]['comment']);
        $arrCol[$col]['title'] = $comment[0];
        $arrCol[$col]['values'] = $comment[1];
    } else {
        $arrCol[$col]['title'] = $arrCol[$col]['comment'];
        $arrCol[$col]['values'] = '';
    }
    if ($arrCol[$col]['key'] == 'PRI') {
        $pk = $arrCol[$col];
    }
    if ($arrCol[$col]['key'] == 'UNI') {
        $un = $arrCol[$col];
    }
}
$mysql->close();

$arrFk = array();

$mysql = new GDbMysql();
$query = "SELECT constraint_name, column_name, referenced_table_schema, referenced_table_name, referenced_column_name
          FROM information_schema.key_column_usage
          WHERE table_schema = ?
          AND table_name = ?;";

$parametros = array('ss', $database, $table);
$mysql->execute($query, $parametros);

while ($mysql->fetch()) {
    $fk = strtoupper(substr($mysql->res[0], 0, 3));

    if ($fk == 'FK_') {
        $col = $mysql->res[1];
        $arrFk[$col]['name'] = $mysql->res[0];
        $arrFk[$col]['col_name'] = $mysql->res[1];
        $arrFk[$col]['db_ref'] = $mysql->res[2];
        $arrFk[$col]['tab_ref'] = $mysql->res[3];
        $arrFk[$col]['col_ref'] = $mysql->res[4];

        $mysql2 = new GDbMysql();
        $mysql2->execute("SHOW COLUMNS FROM " . $arrFk[$col]['db_ref'] . "." . $arrFk[$col]['tab_ref'] . ";");
        $arrCamposFk = array();
        while ($mysql2->fetch()) {
            $field = $mysql2->res['Field'];
            $arrCamposFk[$field] = $field;
        }
        $mysql2->close();
        $arrFk[$col]['col_ref'] = $form->addSelect('fk_rel_' . $arrFk[$col]['col_name'], $arrCamposFk, '', false, false, false, false, false);
        $arrFk[$col]['col_ref'] .= $form->addInput('hidden', 'fk_db_' . $arrFk[$col]['col_name'], false, array('value' => $arrFk[$col]['db_ref']));
        $arrFk[$col]['col_ref'] .= $form->addInput('hidden', 'fk_tb_' . $arrFk[$col]['col_name'], false, array('value' => $arrFk[$col]['tab_ref']));
        $arrCol[$col]['fk_db'] = $arrFk[$col]['db_ref'];
        $arrCol[$col]['fk_tb'] = $arrFk[$col]['tab_ref'];
        $arrCol[$col]['fk'] = $arrFk[$col];
    }
}
$mysql->close();

echo '<br><br><hr>';
//echo $form->addInput('text', 'txt_title', 'Título do Grid', array('size' => '100', 'value' => $titleTable));
echo $form->addInput('text', 'txt_class', 'Classe', array('size' => '100', 'value' => GF::removeAccent($titleTable)));

echo '<br><br>';
echo '<table width="100%" cellspacing="2" cellpadding="2">';
echo '<thead class="listagemCampos">';
echo '<td>Campo</td>';
echo '<td>Tipo</td>';
echo '<td>Nulo</td>';
echo '<td>Key</td>';
echo '<td>Default</td>';
echo '<td>Extra</td>';
echo '<td>Título</td>';
echo '<td>Valores</td>';
echo '<td>Obrigatório</td>';
echo '<td>Campo Ref.</td>';
echo '<td>CKEditor</td>';
echo '</thead>';

$js = '';

foreach ($arrCol as $value) {
    $idDef = 'def_' . $value['field'];
    $idTit = 'tit_' . $value['field'];
    $idVal = 'val_' . $value['field'];
    $idObr = 'obr_' . $value['field'];
    $idCk = 'ck_' . $value['field'];

    $idFk = 'fk_rel_' . $value['field'];
    $fkDb = 'fk_db_' . $value['field'];
    $fkTb = 'fk_tb_' . $value['field'];

    echo '<tr>';
    echo '<td>' . $value['field'] . '</td>';
    echo '<td>' . $value['type'] . '</td>';
    echo '<td>' . $value['null'] . '</td>';
    echo '<td>' . $value['key'] . '</td>';
    echo '<td>' . $form->addInput('text', $idDef, false, array('value' => $value['default'], 'size' => '5')) . '</td>';
    echo '<td>' . $value['extra'] . '</td>';
    echo '<td>' . $form->addInput('text', $idTit, false, array('value' => $value['title'], 'size' => '10')) . '</td>';
    echo '<td>' . $form->addInput('text', $idVal, false, array('value' => $value['values'], 'size' => '7')) . '</td>';

    $checked = '';
    if ($value['null'] == 'NO')
        $checked = 'checked';
    echo '<td><input type="checkbox" id="' . $idObr . '" name="' . $idObr . '" ' . $checked . ' /></td>';
    echo '<td>' . $value['fk']['col_ref'] . '</td>';

    $checkCk = '';
    if (verificaTipoFormText($value['type'])) {
        $checkCk = '<input type="checkbox" id="' . $idCk . '" name="' . $idCk . '" />';
    }
    echo '<td>' . $checkCk . '</td>';

    if ($value['fk']['col_ref'] != '') {
        $js .= $fkDb . ' = \'' . $value['fk_db'] . '\';';
        $js .= $fkTb . ' = \'' . $value['fk_tb'] . '\';';
        $js .= $idFk . ' = jQuery("#' . $idFk . '").val();' . "\n";
        $js .= 'if(' . $idFk . ' == "-1"){ jQuery.gDisplay.showError("Selecione um campo de referência da chave estrangeira <b>' . $idFk . '</b>"); return false; }';
    }
    echo '</tr>';
}
echo '</table>';
echo '<br>';

echo '<input type="button" id="btn_gerar" value="Gerar o Crud" />';
?>

<script>
    jQuery(":button").button();
    jQuery("#btn_gerar").click(function() {
        var title = jQuery("#txt_title").val();
        var sp = jQuery("#cmb_procedure").val();

        if (title == '') {
            jQuery.gDisplay.showError('Informe o Título do Grid');
            return false;
        }

<?php echo $js; ?>

        var param = jQuery("#form_campos").serializeArray();
        if (sp != '-1')
            jQuery.gAjax.load('gerarCrud.php', param, '#lbl_gerador');
        else
            jQuery.gDisplay.showError('Selecione o tipo de crud se Procedure ou não?');
    });
</script>