<?php
$__usr_cha_type = 'E';

require_once('../../_inc/global.php');
require_once('functions.php');
//--------------------------------------------------------------------

$form = new GForm();
//
$database = $_POST['cmb_databases'];
$arrTable = explode('|', $_POST['cmb_tables']);
$table = $arrTable[0];
$titleTable = $_POST['txt_title'];
$classe = ucfirst($_POST['txt_class']);
$objeto = lcfirst($_POST['txt_class']);
$procedure = $_POST['cmb_procedure'];

$mysql = new GDbMysql();
$mysql->execute("SHOW FULL COLUMNS FROM $database.$table;");

$libData = '';
$fields = '';
$fkFrom = '';
$i = 0;
$arrCol = array();
$pk = array();
$un = array();
$fk = array();

while ($mysql->fetch()) {
    $col = $mysql->res[0];
    $arrCol[$col]['field'] = $mysql->res[0];
    $arrCol[$col]['type'] = $mysql->res[1];
    $arrCol[$col]['key'] = $mysql->res[4];
    $arrCol[$col]['extra'] = $mysql->res[6];
    if ($_POST['obr_' . $col] != 'on')
        $arrCol[$col]['null'] = 'YES';
    else
        $arrCol[$col]['null'] = 'NO';
    $arrCol[$col]['default'] = $_POST['def_' . $col];
    $arrCol[$col]['title'] = $_POST['tit_' . $col];
    $arrCol[$col]['values'] = $_POST['val_' . $col];
    if (verificaTipoData($arrCol[$col]['type']))
        $libData = ',"datepicker"';
    $arrCol[$col]['fk_db'] = $_POST['fk_db_' . $col];
    $arrCol[$col]['fk_tb'] = $_POST['fk_tb_' . $col];
    $arrCol[$col]['fk_rel'] = $_POST['fk_rel_' . $col];

    if ($arrCol[$col]['key'] == 'PRI') {
        $pk[] = $arrCol[$col];
    }
    if ($arrCol[$col]['key'] == 'UNI') {
        $un[] = $arrCol[$col];
    }
    if ($arrCol[$col]['fk_rel'] == '') {
        if ($arrCol[$col]['values'] != '') {
            $case = "CASE " . $arrCol[$col]['field'] . " ";
            $valores = explode(";", $arrCol[$col]['values']);
            foreach ($valores as $value) {
                $arr = explode(":", $value);
                $case .= "WHEN '" . $arr[0] . "' THEN '" . $arr[1] . "' ";
            }
            $case .= "ELSE 'Valor Inválido' END as " . $col;
            $fields .= $case . ',';
        } else
            $fields .= $arrCol[$col]['field'] . ',';
    } else {
        $fk[] = $arrCol[$col];
        $fields .= substr($arrCol[$col]['fk_tb'], 0, 3) . '.' . $arrCol[$col]['field'] . ',' . $arrCol[$col]['fk_rel'] . ',';
        $fkFrom .= ' INNER JOIN ' . $arrCol[$col]['fk_tb'] . ' ' . substr($arrCol[$col]['fk_tb'], 0, 3) . ' ON (' . substr($arrCol[$col]['fk_tb'], 0, 3) . '.' . $col . ' = ' . substr($table, 0, 3) . '.' . $col . ') ';
    }
}
$fields = substr($fields, 0, -1);
$from = $table;
$fromFk = $table . ' ' . substr($table, 0, 3) . $fkFrom;

echo $form->open('form_crud', 'form-vertical', 'post', '_self', 'criarArquivos.php');
echo $form->addInput('hidden', 'hdn_pasta', false, array('value' => lcfirst($objeto)));

$foreingKey = array();
foreach ($fk as $i => $for) {
    $foreingKey[] = $for['field'];
}

$primaryKey = array();
foreach ($pk as $i => $pri) {
    $primaryKey[] = $pri['field'];
    $call .= "\n\t" . $pri['field'] . ':' . 'chave[' . $i . '],';
    if (in_array($pri['field'], $foreingKey)) {
        $massa .= "\n\t\t\t\t" . '$' . GF::removeAccent(lcfirst($pri["title"])) . ' = new ' . GF::removeAccent(ucfirst($pri["title"])) . '();';
        $massa .= "\n\t\t\t\t" . '$' . GF::removeAccent(lcfirst($pri["title"])) . '->set' . ucfirst($pri["field"]) . '($chave[' . $i . ']);';
        $massa .= "\n\t\t\t\t" . '$temp->set' . GF::removeAccent(ucfirst($pri["title"])) . '($' . GF::removeAccent(lcfirst($pri["title"])) . ');' . "\n";

        $massaUq .= "\n\t\t\t\t" . '$' . GF::removeAccent(lcfirst($pri["title"])) . ' = new ' . GF::removeAccent(ucfirst($pri["title"])) . '();';
        $massaUq .= "\n\t\t\t\t" . '$' . GF::removeAccent(lcfirst($pri["title"])) . '->set' . ucfirst($pri["field"]) . '($codigo);';
        $massaUq .= "\n\t\t\t\t" . '$temp->set' . GF::removeAccent(ucfirst($pri["title"])) . '($' . GF::removeAccent(lcfirst($pri["title"])) . ');' . "\n";

        $objUpd .= "\n\t\t" . '$' . GF::removeAccent(lcfirst($pri["title"])) . ' = new ' . GF::removeAccent(ucfirst($pri["title"])) . '();';
        $objUpd .= "\n\t\t" . '$' . GF::removeAccent(lcfirst($pri["title"])) . '->set' . ucfirst($pri["field"]) . '($chave[' . $i . ']);';
        $objUpd .= "\n\t\t" . '$' . $objeto . '->set' . GF::removeAccent(ucfirst($pri["title"])) . '($' . GF::removeAccent(lcfirst($pri["title"])) . ');' . "\n";

        $objUpdUq .= "\n\t\t" . '$' . GF::removeAccent(lcfirst($pri["title"])) . ' = new ' . GF::removeAccent(ucfirst($pri["title"])) . '();';
        $objUpdUq .= "\n\t\t" . '$' . GF::removeAccent(lcfirst($pri["title"])) . '->set' . ucfirst($pri["field"]) . '($codigo);';
        $objUpdUq .= "\n\t\t" . '$' . $objeto . '->set' . GF::removeAccent(ucfirst($pri["title"])) . '($' . GF::removeAccent(lcfirst($pri["title"])) . ');' . "\n";
    } else {
        $massa .= "\n\t\t\t\t" . '$temp->set' . ucfirst($pri['field']) . '($chave[' . $i . ']);';
        $massaUq .= "\t\t\t\t" . '$temp->set' . ucfirst(implode(",", $primaryKey)) . '($codigo);' . "\n";
        $objUpd .= '$' . $objeto . '->set' . ucfirst($pri['field']) . '($chave[' . $i . ']);';
        $objUpdUq .= '$' . $objeto . '->set' . ucfirst(implode(",", $primaryKey)) . '($codigo);';
    }
}
$call = substr($call, 0, -1);

$uniqueKey = array();
foreach ($un as $i => $uni) {
    $uniqueKey[] = $uni['field'];
}

if (sizeof($primaryKey) > 1) {
    $callbackExcluir = '
function callbackExcluir(grid, codigo){
    var chave = codigo.split(",");
    jQuery.gAjax.exec("crud_"+grid+".php", {
        acao: "del",' . $call . '
    }, "jQuery(\'#"+grid+"\').flexOptions().flexReload();", "");
}';
    $acaoMassa = "\t\t\t\t" . '$chave = explode(",", $codigo);' . "\n" . $massa;
    $objetosUpd = '$chave = explode(",", $_GET["codigo"]);' . "\n" . $objUpd;
} else {
    $callbackExcluir = '
function callbackExcluir(grid, codigo){
    jQuery.gAjax.exec("crud_"+grid+".php", {
        acao: "del",
        ' . implode(",", $primaryKey) . ':codigo
    }, "jQuery(\'#"+grid+"\').flexOptions().flexReload();", "");
}';
    $acaoMassa = $massaUq;
    $objetosUpd = $objUpdUq;
}

//<editor-fold desc="index">
$pgIndex = '
<?php
require_once("../_inc/global.php");

$header = new GHeader("' . $titleTable . '");
$header->addLib(array("flexigrid","colorbox"' . $libData . '));
$header->addScript("functions.js");
$header->show();
/* -------------------------------------------------------------------------- */

$idGrid = "' . $objeto . '";
$title = "' . $titleTable . '";
$primaryKey = "' . implode(",", $primaryKey) . '";
$post = "grid_' . $objeto . '.php";

$grid = new GFlexiGrid($idGrid, $title, $primaryKey, $post);' . "\n\n";

foreach ($arrCol as $key => $value) {
    if (in_array($value['field'], $primaryKey)) {
        $pgIndex .= '$grid->setCol("' . $value["title"] . '", "' . $value["field"] . '", "32", "true", "center", "false", "false");' . "\n";
    } else {
        if ($value['fk_rel'] != "") {
            $pgIndex .= '$grid->setCol("' . $value["field"] . '", "' . $value["field"] . '", "100", "true", "left", "false", "false");' . "\n";
            $pgIndex .= '$grid->setCol("' . $value["title"] . '", "' . $value["fk_rel"] . '", "100", "true", "left", "true", "true");' . "\n";
        } else {
            if ($value["null"] == 'NO')
                $pgIndex .= '$grid->setCol("' . $value["title"] . '", "' . $value["field"] . '", "100", "true", "left", "true", "true");' . "\n";
            else
                $pgIndex .= '$grid->setCol("' . $value["title"] . '", "' . $value["field"] . '", "100", "true", "left", "true", "false");' . "\n";
        }
    }
}
$pgIndex .= '$grid->setCol("Ações", "acoes", "36", "false", "left");
$grid->setActions("inserir:Inserir,editar:Alterar,excluir:Excluir");
$grid->setActionsMass("delM:Excluir");
$grid->show();

/* -------------------------------------------------------------------------- */
$footer = new GFooter();
$footer->show();
?>
';

//echo '<br><hr>';
//echo $form->addInput('button', 'btn_index', false, array('value' => 'index.php', 'style' => 'float:none;'));
//echo '<div id="lbl_index" class="invisivel">';
//echo $form->addTextarea('index', $pgIndex, 'Index', array("style" => "width:100%", "rows" => "15"));
//echo '</div>';
//</editor-fold>
//<editor-fold desc="functions">
$pgFunctions = '
function __inserir(grid){
    jQuery(".__inserir_"+grid).colorbox({
        iframe:true,
        width:"80%",
        height:"90%",
        href:"form_"+grid+".php",
        onClosed:function(){
            jQuery("#"+grid).flexOptions().flexReload();
        }
    });
}

function __editar(grid, codigo){
    jQuery(".__editar").colorbox({
        iframe:true,
        width:"80%",
        height:"90%",
        href:"form_"+grid+".php?codigo="+codigo,
        onClosed:function(){
            jQuery("#"+grid).flexOptions().flexReload();
        }
    });
}

function __excluir(grid, codigo){
    jQuery.gDisplay.showYN("Do you really want to delete the selected item?", "callbackExcluir(\'"+grid+"\', \'"+codigo+"\');");
    return false;
}

function __executarMassa(grid){
    if(jQuery("#__slc_massa_"+grid).val() == "-1"){
        jQuery.gDisplay.showError("Favor selecionar uma ação em Massa!");
        return false;
    }
    else if(jQuery("#__slc_massa_"+grid).val() == "delM") {
        if (jQuery("#__selecionados_"+grid).val() == "") {
            jQuery.gDisplay.showError("Favor selecionar pelo menos um registro para excluir!");
            return false;
        }
        jQuery.gDisplay.showYN("Você deseja realmente excluir os itens selecionados?", "callbackExcluirM(\'"+grid+"\');");
    }
    return false;
}
' . $callbackExcluir . '

function callbackExcluirM(grid){
    jQuery.gAjax.exec("crud_"+grid+".php", {
        acao: "delM",
        codigos:jQuery("#__selecionados_"+grid).val()
    }, "jQuery(\'#"+grid+"\').flexOptions().flexReload();", "jQuery(\'#"+grid+"\').flexOptions().flexReload();");
}
';

//echo '<br>';
//echo $form->addInput('button', 'btn_functions', false, array('value' => 'functions.js', 'style' => 'float:none;'));
//echo '<div id="lbl_functions" class="invisivel">';
//echo $form->addTextarea('functions', $pgFunctions, 'Functions', array("style" => "width:100%", "rows" => "15"));
//echo '</div>';
//</editor-fold>
//<editor-fold desc="grid">
$pgGrid = '
<?php
require_once("../_inc/global.php");
require_once(ROOT_GENESIS . "_inc/flexiGrid.lib.php");

$idGrid     = $_POST["idGrid"];
$page       = isset($_POST["page"])      ? $_POST["page"]       : 1;
$rp         = isset($_POST["rp"])        ? $_POST["rp"]         : 10;
$sortname   = isset($_POST["sortname"])  ? $_POST["sortname"]   : "' . implode(",", $primaryKey) . '";
$sortorder  = isset($_POST["sortorder"]) ? $_POST["sortorder"]  : "desc";
$actions    = $_POST["actions"];
$query      = $_POST["query"];
$qtype      = $_POST["qtype"];
$where      = $_POST["where"];
$sort       = "ORDER BY $sortname $sortorder ";
$start      = (($page-1) * $rp);
$limit      = "LIMIT $start, $rp";

$fields     = "' . $fields . '";
$from       = "' . $fromFk . '";

if (isset($query) && isset($qtype))
    $arrWhere  = makeWhere($qtype, $query, $where);
$final      = "$sort $limit";

echo sqlGrid($idGrid, $fields, $from, $arrWhere, $final, $actions, $page, "' . implode(",", $primaryKey) . '");
?>
';

//echo '<br>';
//echo $form->addInput('button', 'btn_grid', false, array('value' => 'grid_' . $objeto . '.php', 'style' => 'float:none;'));
//echo '<div id="lbl_grid" class="invisivel">';
//echo $form->addTextarea('grid', $pgGrid, 'Grid', array("style" => "width:100%", "rows" => "15"));
//echo '</div>';
//</editor-fold>

$camposFormUpd = '';

$importClass = '';
$dadosFk = '';
$classFk = '';

$atributos = '';
$getSet = '';
$objetoSel = '';

$camposSet = '';
$camposParam = '';
$camposTipos = '';
$camposValor = '';

$camposTableSelIns = '';
$camposTableSel = '';
$camposTableIns = '';
$camposTableUpd = '';

$camposTableKey = '';
$camposTableKeyIns = '';
$camposTiposKey = '';
$camposValorKey = '';
$camposTiposKeyIns = '';
$camposValorKeyIns = '';

$camposProcedureIns = '';
$camposProcedureUpd = '';
$camposProcedureDel = '';
$camposProcedureKey = '';
$camposProcedureKeyIns = '';

foreach ($arrCol as $key => $value) {
    if (stripos($value['field'], '_dti_add') === FALSE) {
        $obrigatorio = ($value["null"] == 'NO') ? true : false;
        // se for a primary key
        if (in_array($value['field'], $primaryKey)) {
            $validacao = ($obrigatorio) ? ',"validate" => "required"' : '';
            if (in_array($value['field'], $foreingKey)) {
                $classFk .= '$' . GF::removeAccent(lcfirst($value["title"])) . ' = new ' . GF::removeAccent(ucfirst($value["title"])) . '();' . "\n";
                $classFk .= '$' . GF::removeAccent(lcfirst($value["title"])) . '->set' . ucfirst($value["field"]) . '($_POST["' . $value["field"] . '"]);' . "\n";
                $camposSet .= '$' . $objeto . '->set' . GF::removeAccent(ucfirst($value["title"])) . '($' . GF::removeAccent(lcfirst($value["title"])) . ');' . "\n";
                $importClass .= ',"' . GF::removeAccent(lcfirst($value["title"])) . '"';
                $dadosFk .= '$opt_' . $value["fk_rel"] . ' = $mysql->executeCombo("SELECT ' . $value["field"] . ', ' . $value["fk_rel"] . ' FROM ' . $value["fk_tb"] . ' ORDER BY ' . $value["fk_rel"] . ';");' . "\n";
                $camposFormIns .= "\t" . '$html .= $form->addSelect("' . $value["field"] . '", $opt_' . $value["fk_rel"] . ' , "-1", "' . $value["title"] . '", array("class" => "combobox"' . $validacao . '));' . "\n";
                $camposFormUpd .= "\t\t" . '$html .= $form->addSelect("' . $value["field"] . '", $opt_' . $value["fk_rel"] . ' , $' . $objeto . '->get' . ucfirst(GF::removeAccent($value["title"])) . '()->get' . ucfirst($value["field"]) . '()' . ', "' . $value["title"] . '", array("class" => "combobox"' . $validacao . '));' . "\n";
                $atributos .= "\t" . '/* @var $' . GF::removeAccent(lcfirst($value["title"])) . ' ' . GF::removeAccent(ucfirst($value["title"])) . ' */' . "\n";
                $atributos .= "\t" . 'private $' . GF::removeAccent(lcfirst($value["title"])) . ';' . "\n";
                $getSet .= "\n\t" . '/** @return ' . GF::removeAccent(ucfirst($value["title"])) . ' */' . "\n";
                $getSet .= "\t" . 'public function get' . GF::removeAccent(ucfirst($value["title"])) . '() {' . "\n";
                $getSet .= "\t\t" . 'return $this->' . GF::removeAccent(lcfirst($value["title"])) . ';' . "\n";
                $getSet .= "\t" . '}' . "\n";
                $getSet .= "\n\t" . '/** @param ' . GF::removeAccent(ucfirst($value["title"])) . ' $' . GF::removeAccent(lcfirst($value["title"])) . ' */' . "\n";
                $getSet .= "\t" . 'public function set' . GF::removeAccent(ucfirst($value["title"])) . '($' . GF::removeAccent(lcfirst($value["title"])) . ') {' . "\n";
                $getSet .= "\t\t" . '$this->' . GF::removeAccent(lcfirst($value["title"])) . ' = $' . GF::removeAccent(lcfirst($value["title"])) . ';' . "\n";
                $getSet .= "\t" . '}' . "\n";
                $objetoSel .= "\n\t\t\t\t" . '$' . GF::removeAccent(lcfirst($value["title"])) . ' = new ' . GF::removeAccent(ucfirst($value["title"])) . '();' . "\n";
                $objetoSel .= "\t\t\t\t" . '$' . GF::removeAccent(lcfirst($value["title"])) . '->set' . ucfirst($value["field"]) . '($mysql->res["' . $value["field"] . '"]);' . "\n";
                $objetoSel .= "\t\t\t\t" . '$' . GF::removeAccent(lcfirst($value["title"])) . 'Dao = new ' . GF::removeAccent(ucfirst($value["title"])) . 'Dao();' . "\n";
                $objetoSel .= "\t\t\t\t" . '$' . GF::removeAccent(lcfirst($value["title"])) . ' = $' . GF::removeAccent(lcfirst($value["title"])) . 'Dao->selectById($' . GF::removeAccent(lcfirst($value["title"])) . ');' . "\n";
                $objetoSel .= "\t\t\t\t" . '$' . $objeto . '->set' . GF::removeAccent(ucfirst($value["title"])) . '($' . GF::removeAccent(lcfirst($value["title"])) . ');' . "\n";
                $camposTableIns .= $value["field"] . ',';
                $camposTableUpd .= $value["field"] . ' = ?,';

//            $camposTiposKeyIns .= ( verificaTipoInteger($value["type"])) ? 'i' : 's';
                $tipo = 's';
                if (verificaTipoInteger($value["type"])) {
                    $tipo = 'i';
                } else if (verificaTipoFloat($value["type"])) {
                    $tipo = 'd';
                }
                $camposTiposKeyIns .= $tipo;

                $camposValorKeyIns .= '$' . $objeto . '->get' . GF::removeAccent(ucfirst($value["title"])) . '()->get' . ucfirst($value["field"]) . '(),';
                $camposProcedureKeyIns .= '?,';
                $camposTableKeyIns .= $value["field"] . ' = ? AND ';
                $camposTableSelIns .= $value["field"] . ',';
            } else {
//            $camposTiposKey .= ( verificaTipoInteger($value["type"])) ? 'i' : 's';
                $tipo = 's';
                if (verificaTipoInteger($value["type"])) {
                    $tipo = 'i';
                } else if (verificaTipoFloat($value["type"])) {
                    $tipo = 'd';
                }
                $camposTiposKey .= $tipo;

                $camposValorKey .= '$' . $objeto . '->get' . ucfirst($value["field"]) . '(),';
                $atributos .= "\t" . 'private $' . $value["field"] . ';' . "\n";
                $camposSet .= '$' . $objeto . '->set' . ucfirst($value["field"]) . '($_POST["' . $value["field"] . '"]);' . "\n";
                $camposFormUpd .= "\t\t" . '$html .= $form->addInput("hidden", "' . $value["field"] . '", false, array("value" => $' . $objeto . '->get' . ucfirst($value["field"]) . '()' . $validacao . '));' . "\n";
                $getSet .= "\n\t" . 'public function get' . ucfirst($value["field"]) . '() {' . "\n";
                $getSet .= "\t\t" . 'return $this->' . $value["field"] . ';' . "\n";
                $getSet .= "\t" . '}' . "\n";
                $getSet .= "\n\t" . 'public function set' . ucfirst($value["field"]) . '($' . $value["field"] . ') {' . "\n";
                $getSet .= "\t\t" . '$this->' . $value["field"] . ' = $' . $value["field"] . ';' . "\n";
                $getSet .= "\t" . '}' . "\n";
                $objetoSel .= "\t\t\t\t" . '$' . $objeto . '->set' . ucfirst($value["field"]) . '($mysql->res["' . $value["field"] . '"]);' . "\n";
                $camposProcedureKey .= '?,';
                $camposTableKey .= $value["field"] . ' = ? AND ';
                $camposTableSel .= $value["field"] . ',';
            }
        } else if ($value["fk_rel"] != '') {
            $atributos .= "\t" . '/* @var $' . GF::removeAccent(lcfirst($value["title"])) . ' ' . GF::removeAccent(ucfirst($value["title"])) . ' */' . "\n";
            $atributos .= "\t" . 'private $' . GF::removeAccent(lcfirst($value["title"])) . ';' . "\n";
            $getSet .= "\n\t" . '/** @return ' . GF::removeAccent(ucfirst($value["title"])) . ' */' . "\n";
            $getSet .= "\t" . 'public function get' . GF::removeAccent(ucfirst($value["title"])) . '() {' . "\n";
            $getSet .= "\t\t" . 'return $this->' . GF::removeAccent(lcfirst($value["title"])) . ';' . "\n";
            $getSet .= "\t" . '}' . "\n";
            $getSet .= "\n\t" . '/** @param ' . GF::removeAccent(ucfirst($value["title"])) . ' $' . GF::removeAccent(lcfirst($value["title"])) . ' */' . "\n";
            $getSet .= "\t" . 'public function set' . GF::removeAccent(ucfirst($value["title"])) . '($' . GF::removeAccent(lcfirst($value["title"])) . ') {' . "\n";
            $getSet .= "\t\t" . '$this->' . GF::removeAccent(lcfirst($value["title"])) . ' = $' . GF::removeAccent(lcfirst($value["title"])) . ';' . "\n";
            $getSet .= "\t" . '}' . "\n";
            $objetoSel .= "\n\t\t\t\t" . '$' . GF::removeAccent(lcfirst($value["title"])) . ' = new ' . GF::removeAccent(ucfirst($value["title"])) . '();' . "\n";
            $objetoSel .= "\t\t\t\t" . '$' . GF::removeAccent(lcfirst($value["title"])) . '->set' . ucfirst($value["field"]) . '($mysql->res["' . $value["field"] . '"]);' . "\n";
            $objetoSel .= "\t\t\t\t" . '$' . GF::removeAccent(lcfirst($value["title"])) . 'Dao = new ' . GF::removeAccent(ucfirst($value["title"])) . 'Dao();' . "\n";
            $objetoSel .= "\t\t\t\t" . '$' . GF::removeAccent(lcfirst($value["title"])) . ' = $' . GF::removeAccent(lcfirst($value["title"])) . 'Dao->selectById($' . GF::removeAccent(lcfirst($value["title"])) . ');' . "\n";
            $objetoSel .= "\t\t\t\t" . '$' . $objeto . '->set' . GF::removeAccent(ucfirst($value["title"])) . '($' . GF::removeAccent(lcfirst($value["title"])) . ');' . "\n";
            $camposTableIns .= $value["field"] . ',';
            $camposTableUpd .= $value["field"] . ' = ?,';
            $dadosFk .= '$opt_' . $value["fk_rel"] . ' = $mysql->executeCombo("SELECT ' . $value["field"] . ', ' . $value["fk_rel"] . ' FROM ' . $value["fk_tb"] . ' ORDER BY ' . $value["fk_rel"] . ';");' . "\n";
            $importClass .= ',"' . GF::removeAccent(lcfirst($value["title"])) . '"';
            $classFk .= '$' . GF::removeAccent(lcfirst($value["title"])) . ' = new ' . GF::removeAccent(ucfirst($value["title"])) . '();' . "\n";
            $classFk .= '$' . GF::removeAccent(lcfirst($value["title"])) . '->set' . ucfirst($value["field"]) . '($_POST["' . $value["field"] . '"]);' . "\n";
            $camposSet .= '$' . $objeto . '->set' . GF::removeAccent(ucfirst($value["title"])) . '($' . GF::removeAccent(lcfirst($value["title"])) . ');' . "\n";
            $camposValor .= '$' . $objeto . '->get' . GF::removeAccent(ucfirst($value["title"])) . '()->get' . ucfirst($value["field"]) . '(),';
            $camposParam .= '?,';

//        $camposTipos .= ( verificaTipoInteger($value["type"])) ? 'i' : 's';
            $tipo = 's';
            if (verificaTipoInteger($value["type"])) {
                $tipo = 'i';
            } else if (verificaTipoFloat($value["type"])) {
                $tipo = 'd';
            }
            $camposTipos .= $tipo;

            $validacao = ($obrigatorio) ? ',"validate" => "([~] != -1)|Obrigatório"' : '';
            $camposFormIns .= "\t" . '$html .= $form->addSelect("' . $value["field"] . '", $opt_' . $value["fk_rel"] . ' , "-1", "' . $value["title"] . '", array("class" => "combobox"' . $validacao . '));' . "\n";
            $camposFormUpd .= "\t\t" . '$html .= $form->addSelect("' . $value["field"] . '", $opt_' . $value["fk_rel"] . ' , $' . $objeto . '->get' . ucfirst(GF::removeAccent($value["title"])) . '()->get' . ucfirst($value["field"]) . '()' . ', "' . $value["title"] . '", array("class" => "combobox"' . $validacao . '));' . "\n";
            $camposTableSel .= $value["field"] . ',';
        } else {
            $atributos .= "\t" . 'private $' . $value["field"] . ';' . "\n";
            $getSet .= "\n\t" . 'public function get' . ucfirst($value["field"]) . '() {' . "\n";
            $getSet .= "\t\t" . 'return $this->' . $value["field"] . ';' . "\n";
            $getSet .= "\t" . '}' . "\n";
            $getSet .= "\n\t" . 'public function set' . ucfirst($value["field"]) . '($' . $value["field"] . ') {' . "\n";
            $getSet .= "\t\t" . '$this->' . $value["field"] . ' = $' . $value["field"] . ';' . "\n";
            $getSet .= "\t" . '}' . "\n";
            $objetoSel .= "\t\t\t\t" . '$' . $objeto . '->set' . ucfirst($value["field"]) . '($mysql->res["' . $value["field"] . '"]);' . "\n";
            $camposTableIns .= $value["field"] . ',';
            $camposTableUpd .= $value["field"] . ' = ?,';
            // carregar campos do formulario
            if (((verificaTipoInteger($value["type"])) || (verificaTipoFormChar($value["type"]))) && (verificaFormCombo($value) == true)) {
                $valores = str_replace(':', '" => "', $value['values']);
                $valores = '"' . $valores . '"';
                $valores = str_replace(';', '", "', $valores);
                $validacao = ($obrigatorio) ? ',"validate" => "([~] != -1)|Obrigatório"' : '';
                $camposFormIns .= "\t" . '$html .= $form->addSelect("' . $value["field"] . '", array(' . $valores . '), "-1", "' . $value["title"] . '", array("class" => "combobox"' . $validacao . '));' . "\n";
                $camposFormUpd .= "\t\t" . '$html .= $form->addSelect("' . $value["field"] . '", array(' . $valores . '), $' . $objeto . '->get' . ucfirst($value["field"]) . '()' . ', "' . $value["title"] . '", array("class" => "combobox"' . $validacao . '));' . "\n";
            } else if (verificaTipoFormChar($value["type"]) || verificaTipoInteger($value["type"])) {
                $validacao = ($obrigatorio) ? ',"validate" => "required"' : '';
                $camposFormIns .= "\t" . '$html .= $form->addInput("text", "' . $value["field"] . '", "' . $value["title"] . '", array("class" => "input", "size" => "' . size($value["type"]) . '", "maxlength" => "' . maxlength($value["type"]) . '"' . $validacao . '));' . "\n";
                $camposFormUpd .= "\t\t" . '$html .= $form->addInput("text", "' . $value["field"] . '", "' . $value["title"] . '", array("value" => $' . $objeto . '->get' . ucfirst($value["field"]) . '()' . ', "class" => "input", "size" => "' . size($value["type"]) . '", "maxlength" => "' . maxlength($value["type"]) . '"' . $validacao . '));' . "\n";
            } else if (verificaTipoFormText($value["type"])) {
                $validacao = ($obrigatorio) ? ',"validate" => "required"' : '';
                if ($_POST['ck_' . $value['field']] == 'on') {
                    $libData .= ',"ckeditor"';
                    $camposFormIns .= "\t" . '$html .= $form->addCKEditor("' . $value["field"] . '", "","' . $value["title"] . '", array("class" => "ckeditor"), array("width" => "\'500px\'","height" => "\'300px\'"' . $validacao . '));' . "\n";
                    $camposFormUpd .= "\t\t" . '$html .= $form->addCKEditor("' . $value["field"] . '", $' . $objeto . '->get' . ucfirst($value["field"]) . '()' . ',"' . $value["title"] . '", false, array("width" => "\'500px\'","height" => "\'300px\'"' . $validacao . '));' . "\n";
                } else {
                    $camposFormIns .= "\t" . '$html .= $form->addTextarea("' . $value["field"] . '", "", "' . $value["title"] . '", array("class" => "textarea", "cols" => "10", "rows" => "3"' . $validacao . '));' . "\n";
                    $camposFormUpd .= "\t\t" . '$html .= $form->addTextarea("' . $value["field"] . '", $' . $objeto . '->get' . ucfirst($value["field"]) . '()' . ', "' . $value["title"] . '", array("class" => "textarea", "cols" => "10", "rows" => "3"' . $validacao . '));' . "\n";
                }
            } else if (verificaTipoData($value["type"])) {
                $validacao = ($obrigatorio) ? ',"validate" => "required"' : '';
                if ((strtoupper($value["type"]) == 'DATE') || (strtoupper($value["type"]) == 'YEAR')) {
                    $camposFormIns .= "\t" . '$html .= $form->addDateField("' . $value["field"] . '", "' . $value["title"] . '", false, array("size" => "10"));' . "\n";
                    $camposFormUpd .= "\t\t" . '$html .= $form->addDateField("' . $value["field"] . '", "' . $value["title"] . '", false, array("value" => $' . $objeto . '->get' . ucfirst($value["field"]) . '()' . ',"size" => "10"' . $validacao . '));' . "\n";
                } else {
                    $camposFormIns .= "\t" . '$html .= $form->addDateField("' . $value["field"] . '", "' . $value["title"] . '", true, array("size" => "17"));' . "\n";
                    $camposFormUpd .= "\t\t" . '$html .= $form->addDateField("' . $value["field"] . '", "' . $value["title"] . '", true, array("value" => $' . $objeto . '->get' . ucfirst($value["field"]) . '()' . ',"size" => "17"' . $validacao . '));' . "\n";
                }
            }
            $camposSet .= '$' . $objeto . '->set' . ucfirst($value["field"]) . '($_POST["' . $value["field"] . '"]);' . "\n";
            $camposValor .= '$' . $objeto . '->get' . ucfirst($value["field"]) . '(),';
            $camposParam .= '?,';

//        $camposTipos .= ( verificaTipoInteger($value["type"])) ? 'i' : 's';
            $tipo = 's';
            if (verificaTipoInteger($value["type"])) {
                $tipo = 'i';
            } else if (verificaTipoFloat($value["type"])) {
                $tipo = 'd';
            }
            $camposTipos .= $tipo;

            $camposTableSel .= $value["field"] . ',';
        }
    }
}

$camposTableSel = substr($camposTableSel, 0, -1);
$camposTableSelIns = substr($camposTableSelIns, 0, -1);
$camposTableIns = substr($camposTableIns, 0, -1);
$camposTableUpd = substr($camposTableUpd, 0, -1);

$camposTableKey = substr($camposTableKey, 0, -4);
$camposTableKeyIns = substr($camposTableKeyIns, 0, -4);
$camposValorKey = substr($camposValorKey, 0, -1);
$camposValorKeyIns = substr($camposValorKeyIns, 0, -1);
$camposProcedureKey = substr($camposProcedureKey, 0, -1);
$camposProcedureKeyIns = substr($camposProcedureKeyIns, 0, -1);

$camposParam = substr($camposParam, 0, -1);
$camposValor = substr($camposValor, 0, -1);

$camposValorKeyAntes = ($camposValorKey != "") ? ',' . $camposValorKey : '';
$camposValorKeyInsAntes = ($camposValorKeyIns != "") ? ',' . $camposValorKeyIns : '';
$camposValorAntes = ($camposValor != "") ? ',' . $camposValor : '';
$camposParamAntes = ($camposParam != "") ? ',' . $camposParam : '';
$camposParamInsAntes = (($camposProcedureKeyIns != "") && ($camposParam != "")) ? ',' . $camposParam : $camposParam;

//$camposValorKeyDepois = ($camposValorKey != "") ? $camposValorKey . ',' : '';
//$camposValorDepois = ($camposValor != "") ? $camposValor . ',' : '';

if ($procedure == '0') {
    // query insert
    $camposProcedureIns = '
        $return = array();
        $param = array("' . $camposTipos . $camposTiposKeyIns . '"' . $camposValorAntes . $camposValorKeyInsAntes . ');
        try{
            $mysql = new GDbMysql();
            $mysql->execute("INSERT INTO ' . $from . '(' . $camposTableIns . ') VALUES (' . $camposParam . ');", $param, false);
            if ($mysql->affectedRows()) {
                $return["status"] = true;
                $return["msg"] = "' . ucfirst($objeto) . ' inserido com sucesso!";
                $return["insertId"] = $mysql->insertId();
            } else {
                $return["status"] = false;
                $return["msg"] = "Não foi possível inserir!";
                $return["insertId"] = null;
            }';
    // query update
    $camposProcedureUpd = '
        $return = array();
        $param = array("' . $camposTipos . $camposTiposKey . '"' . $camposValorAntes . $camposValorKeyAntes . ');
        try{
            $mysql = new GDbMysql();
            $mysql->execute("UPDATE ' . $from . ' SET ' . $camposTableUpd . ' WHERE ' . $camposTableKey . ';", $param, false);
            if ($mysql->affectedRows()) {
                $return["status"] = true;
                $return["msg"] = "' . ucfirst($objeto) . ' alterado com sucesso!";
            } else {
                $return["status"] = false;
                $return["msg"] = "Nenhum dado foi alterado!";
            }';
    // query delete
    $camposProcedureDel = '
        $return = array();
        $param = array("' . $camposTiposKeyIns . $camposTiposKey . '" ' . $camposValorKeyInsAntes . $camposValorKeyAntes . ');
        try {
            $mysql = new GDbMysql();
            $mysql->execute("DELETE FROM ' . $from . ' WHERE ' . $camposTableKey . ';", $param, false);
            if ($mysql->affectedRows()) {
                $return["status"] = true;
                $return["msg"] = "' . ucfirst($objeto) . ' excluído com sucesso!";
            } else {
                $return["status"] = false;
                $return["msg"] = "Não foi possível excluir!";
            }';
} else {
    // procedure insert
    $camposProcedureIns = '
        $return = array();
        $param = array("' . $camposTiposKeyIns . $camposTipos . '"' . $camposValorKeyInsAntes . $camposValorAntes . ');
        try{
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_' . $table . '_ins(' . $camposProcedureKeyIns . $camposParamInsAntes . ', @p_status, @p_msg, @p_insert_id);", $param, false);
            $mysql->execute("SELECT @p_status, @p_msg, @p_insert_id");
            $mysql->fetch();
            $return["status"] = ($mysql->res[0]) ? true : false;
            $return["msg"] = $mysql->res[1];
            $return["insertId"] = $mysql->res[2];';
    // procedure update
    $camposProcedureUpd = '
        $return = array();
        $param = array("' . $camposTiposKey . $camposTipos . '"' . $camposValorKeyAntes . $camposValorAntes . ');
        try{
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_' . $table . '_upd(' . $camposProcedureKey . $camposParamAntes . ', @p_status, @p_msg);", $param, false);
            $mysql->execute("SELECT @p_status, @p_msg");
            $mysql->fetch();
            $return["status"] = ($mysql->res[0]) ? true : false;
            $return["msg"] = $mysql->res[1];';
    // procedure delete
    $camposProcedureDel = '
        $return = array();
        $param = array("' . $camposTiposKeyIns . $camposTiposKey . '"' . $camposValorKeyInsAntes . $camposValorKeyAntes . ');
        try {
            $mysql = new GDbMysql();
            $mysql->execute("CALL sp_' . $table . '_del(' . $camposProcedureKeyIns . $camposProcedureKey . ', @p_status, @p_msg);", $param, false);
            $mysql->execute("SELECT @p_status, @p_msg");
            $mysql->fetch();
            $return["status"] = ($mysql->res[0]) ? true : false;
            $return["msg"] = $mysql->res[1];';
}
$catch = '
            $mysql->close();
        } catch (GDbException $e) {
            $return["status"] = false;
            $return["msg"] = $e->getError();
        }
        return $return;';
$camposProcedureIns .= $catch;
$camposProcedureUpd .= $catch;
$camposProcedureDel .= $catch;

//<editor-fold desc="crud">
$pgCrud = '
<?php
require_once("../../_inc/global.php");
GF::importClass(array("' . lcfirst($classe) . '"' . $importClass . '));

' . $classFk . '
$' . $objeto . ' = new ' . $classe . '();
' . $camposSet . '
$' . $objeto . 'Dao = new ' . $classe . 'Dao();

switch ($_POST["acao"]) {
    case "ins":
        echo json_encode($' . $objeto . 'Dao->insert($' . $objeto . '));
        break;
    case "upd":
        echo json_encode($' . $objeto . 'Dao->update($' . $objeto . '));
        break;
    case "del":
        echo json_encode($' . $objeto . 'Dao->delete($' . $objeto . '));
        break;
    case "sel":
        echo json_encode($' . $objeto . 'Dao->selectByIdForm($' . $objeto . '));
        break;
    default:
        echo json_encode(array("status" => false, "msg" => "Invalid action"));
        break;
}
';
echo '<br>';
echo $form->addInput('button', 'btn_crud', false, array('value' => $objeto . '_crud.php', 'style' => 'float:none;'));
echo '<div id="lbl_crud" class="invisivel">';
echo $form->addTextarea('crud', $pgCrud, 'Crud', array("style" => "width:100%", "rows" => "15"));
echo '</div>';
//</editor-fold>
//<editor-fold desc="form">
$pgForm = '<?php
' . $dadosFk . '
' . $camposFormIns . '
?>';

echo '<br>';
echo $form->addInput('button', 'btn_form', false, array('value' => 'form_' . $objeto . '.php', 'style' => 'float:none;'));
echo '<div id="lbl_form" class="invisivel">';
echo $form->addTextarea('form', $pgForm, 'Form', array("style" => "width:100%", "rows" => "15"));
echo '</div>';
//</editor-fold>
//<editor-fold desc="class">
$pgClass = '<?php
class ' . $classe . '{
' . $atributos . '
' . $getSet . '
}
';

echo '<br>';
echo $form->addInput('button', 'btn_class', false, array('value' => $classe . '.php', 'style' => 'float:none;'));
echo '<div id="lbl_class" class="invisivel">';
echo $form->addTextarea('class', $pgClass, $classe, array("style" => "width:100%", "rows" => "15"));
echo '</div>';
//</editor-fold>
//<editor-fold desc="classDao">
$pgClassDao = '<?php
require_once(ROOT_SYS_CLASS . "' . lcfirst($classe) . '.php");

GF::importClass(array("' . lcfirst($classe) . '"' . $importClass . '));

class ' . $classe . 'Dao {
    /** @param ' . $classe . ' $' . $objeto . ' */
    public function selectByIdForm($' . $objeto . ') {
        $ret = array();
        try {
            $mysql = new GDbMysql();
            $mysql->execute("SELECT ' . $camposTableSel . ' FROM vw_' . $from . ' WHERE ' . $camposTableKey . '", array("' . $camposTiposKey . '", ' . $camposValorKey . '));
            if ($mysql->fetch()) {
                $ret = $mysql->res;
            }
            $mysql->close();
        } catch (GDbException $e) {
            echo $e->getError();
        }
        return $ret;
    }

    /** @param ' . $classe . ' $' . $objeto . ' */
    public function insert($' . $objeto . ') {
' . $camposProcedureIns . '
    }

    /** @param ' . $classe . ' $' . $objeto . ' */
    public function update($' . $objeto . ') {
' . $camposProcedureUpd . '
    }

    /** @param ' . $classe . ' $' . $objeto . ' */
    public function delete($' . $objeto . ') {
' . $camposProcedureDel . '
    }
}
';

echo '<br>';
echo $form->addInput('button', 'btn_classDao', false, array('value' => $classe . 'Dao.php', 'style' => 'float:none;'));
echo '<div id="lbl_classDao" class="invisivel">';
echo $form->addTextarea('classDao', $pgClassDao, $classe . 'Dao', array("style" => "width:100%", "rows" => "15"));
echo '</div>';
//</editor-fold>
//<editor-fold desc="procedure">
$q2 = "SELECT TABLE_NAME,COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE CONSTRAINT_SCHEMA = ? AND REFERENCED_TABLE_NAME = ?;";
$param = array("ss", $database, $table);
$mysql2 = new GDbMysql();
$mysql2->execute($q2, $param);

$depTables = array();
$depColumns = array();
while ($mysql2->fetch()) {
    $depTables[] = $mysql2->res[0];
    $depColumns[] = $mysql2->res[1];
}

$printProc = '';

if ($procedure == '1') {
    $paramIns = '';
    $campoIns = '';
    $valueIns = '';
    $paramUpd = '';
    $campoUpd = '';
    $whereKey = '';
    $whereUniKey = '';
    $paramDel = '';
    $keyValida = '';
    foreach ($arrCol as $key => $value) {
        if (stripos($value['field'], '_dti_add') === FALSE) {
            if (!in_array($value['field'], $primaryKey)) {
                $paramIns .= "" . 'IN p_' . $value["field"] . ' ' . trim(str_replace('UNSIGNED', '', strtoupper($value["type"]))) . ', ';
                $campoIns .= "\n\t\t" . $value["field"] . ',';
                $valueIns .= "\n\t\t" . 'p_' . $value["field"] . ',';
                $campoUpd .= "\n\t\t" . $value["field"] . ' = p_' . $value["field"] . ',';
            } else {
                $paramUpd .= "" . 'IN p_' . $value["field"] . ' ' . trim(str_replace('UNSIGNED', '', strtoupper($value["type"]))) . ', ';
                $paramDel .= $paramUpd;
                $whereKey .= ( $whereKey == '') ? 'WHERE ' . $value["field"] . ' = p_' . $value["field"] : "\n\t\t" . 'AND ' . $value["field"] . ' = p_' . $value["field"];

                $keyValida = 'p_' . $value["field"];
            }
            if (in_array($value['field'], $uniqueKey)) {
                $whereUniKey .= ( $whereUniKey == '') ? 'WHERE ' . $value["field"] . ' = p_' . $value["field"] : "\n\t\t" . 'AND ' . $value["field"] . ' = p_' . $value["field"];
            }
        }
    }

    $paramIns = substr(trim($paramIns), 0, -1) . "";
    $campoIns = substr($campoIns, 0, -1);
    $valueIns = substr($valueIns, 0, -1);
    $paramUpd .= $paramIns;
    $campoUpd = substr($campoUpd, 0, -1);
    $paramDel = substr(trim($paramDel), 0, -1) . "";

    $pgProc = "
DELIMITER $$
-- Procedure de Insert --
CREATE PROCEDURE sp_" . $table . "_ins(" . $paramIns . ",INOUT p_status BOOLEAN, INOUT p_msg TEXT, INOUT p_insert_id INT(11))
    NOT DETERMINISTIC
    CONTAINS SQL
    SQL SECURITY INVOKER
    COMMENT 'Procedure de Insert'
BEGIN

  DECLARE EXIT HANDLER FOR SQLEXCEPTION
  BEGIN
    ROLLBACK;
    SET p_status = FALSE;
    SET p_msg = 'Error while performing the procedure.';
  END;

  SET p_msg = '';
  SET p_status = FALSE;

  -- VALIDATIONS
  -- IF condicao THEN
  --    SET p_msg = concat(p_msg, 'Mensagem.<br />');
  -- END IF;

  IF p_msg = '' THEN

    START TRANSACTION;

    INSERT INTO " . $table . "(" .
            $campoIns . "
    ) VALUES (" .
            $valueIns . "
    );

    COMMIT;

    SET p_status = TRUE;
    SET p_msg = 'A new record has been successfully inserted.';
    SET p_insert_id = LAST_INSERT_ID();

  END IF;

END$$
\n\n";

    $pgProc .= "
DELIMITER $$
-- Procedure de Update --
CREATE PROCEDURE sp_" . $table . "_upd(" . $paramUpd . ",INOUT p_status BOOLEAN, INOUT p_msg TEXT)
    NOT DETERMINISTIC
    CONTAINS SQL
    SQL SECURITY INVOKER
    COMMENT 'Procedure de Update'
BEGIN

  DECLARE v_existe BOOLEAN;

  DECLARE EXIT HANDLER FOR SQLEXCEPTION
  BEGIN
    ROLLBACK;
    SET p_status = FALSE;
    SET p_msg = 'Error while performing the procedure.';
  END;

  SET p_msg = '';
  SET p_status = FALSE;

  -- VALIDATIONS
  SELECT IF(count(1) = 0, FALSE, TRUE)
  INTO v_existe
  FROM " . $table . "
  " . $whereKey . ";

  IF NOT v_existe THEN
    SET p_msg = concat(p_msg, 'Record not found.<br />');
  END IF;

  IF p_msg = '' THEN
    START TRANSACTION;

    UPDATE " . $table . "
    SET " . $campoUpd . "
    " . $whereKey . ";

    COMMIT;

    SET p_status = TRUE;
    SET p_msg = 'The record was successfully changed';

  END IF;

END$$
\n\n";

    $pgProc .= "
DELIMITER $$
-- Procedure de Delete --
CREATE PROCEDURE sp_" . $table . "_del(" . $paramDel . ",INOUT p_status BOOLEAN, INOUT p_msg TEXT)
    NOT DETERMINISTIC
    CONTAINS SQL
    SQL SECURITY INVOKER
    COMMENT 'Procedure de Delete'
BEGIN

  DECLARE v_existe BOOLEAN;
  DECLARE v_row_count int DEFAULT 0;

  DECLARE EXIT HANDLER FOR SQLEXCEPTION
  BEGIN
    ROLLBACK;
    SET p_status = FALSE;
    SET p_msg = 'Error while performing the procedure.';
  END;

  SET p_msg = '';
  SET p_status = FALSE;

  -- VALIDATIONS
  SELECT IF(count(1) = 0, FALSE, TRUE)
  INTO v_existe
  FROM " . $table . "
  " . $whereKey . ";

  IF NOT v_existe THEN
    SET p_msg = concat(p_msg, 'Record not found.<br />');
  END IF;

  CALL sp_adm_dependence('" . $table . "', " . $keyValida . ", @dependencias);
  SET p_msg = concat(p_msg,IF(@dependencias IS NULL, '', @dependencias));

  IF p_msg = '' THEN
    START TRANSACTION;

    DELETE FROM " . $table . "
    " . $whereKey . ";

    SELECT ROW_COUNT() INTO v_row_count;

    COMMIT;

    IF (v_row_count > 0) THEN
      SET p_status = TRUE;
      SET p_msg = 'The record was successfully deleted';
    END IF;

  END IF;

END$$
DELIMITER ;";

    echo '<br>';
    echo $form->addInput('button', 'btn_proc', false, array('value' => 'procedures.sql', 'style' => 'float:none;'));
    echo '<div id="lbl_proc" class="invisivel">';
    echo $form->addTextarea('procedures', $pgProc, 'Procedures', array("style" => "width:100%", "rows" => "15"));
    echo '</div>';
}
//</editor-fold>

echo '<br><br>';
echo $form->addInput('submit', 'btn_arquivos', false, array('value' => 'Criar Arquivos', 'style' => 'float:none;'));
echo $form->close();
?>

<div id="lbl_resposta"></div>


<script>
    jQuery(document).ready(function() {

        jQuery(":button, :submit").button();

        jQuery(".invisivel").hide();

        jQuery("#btn_index").click(function() {
            jQuery("#lbl_index").toggle();
        });

        jQuery("#btn_crud").click(function() {
            jQuery("#lbl_crud").toggle();
        });

        jQuery("#btn_form").click(function() {
            jQuery("#lbl_form").toggle();
        });

        jQuery("#btn_class").click(function() {
            jQuery("#lbl_class").toggle();
        });

        jQuery("#btn_classDao").click(function() {
            jQuery("#lbl_classDao").toggle();
        });

        jQuery("#btn_functions").click(function() {
            jQuery("#lbl_functions").toggle();
        });

        jQuery("#btn_grid").click(function() {
            jQuery("#lbl_grid").toggle();
        });

        jQuery("#btn_proc").click(function() {
            jQuery("#lbl_proc").toggle();
        });
    });
</script>