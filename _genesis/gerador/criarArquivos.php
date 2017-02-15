<?php
$__usr_cha_type = 'E';

require_once("../../_inc/global.php");
require_once("functions.php");

$header = new GHeader('Geradores - ' . SYS_DESC);
$header->addScript("functions.js");
$header->show(true);
//--------------------------------------------------------------------

$pasta = $_POST["hdn_pasta"];
$crud = stripslashes($_POST["crud"]);
$class = stripslashes($_POST["class"]);
$classDao = stripslashes($_POST["classDao"]);
$procedures = stripslashes($_POST["procedures"]);
$form = stripslashes($_POST["form"]);

$print = '';
$flag = false;

if (isset($pasta)) {

    $pastaCrud = 'gerados/' . $pasta;

    $classPg = ROOT_SYS_CLASS . $pasta . ".php";
    $classDaoPg = ROOT_SYS_CLASS . $pasta . "Dao.php";

    $crudPg = $pastaCrud . "/" . $pasta . "_crud.php";
    $proceduresPg = $pastaCrud . "/procedures.sql";

    $formPg = $pastaCrud . "/" . $pasta . "_form.php";

    if (file_exists($pastaCrud)) {
        chmod($pastaCrud, 1777);
        try {
            if (file_exists($crudPg))
                unlink($crudPg);
            if (file_exists($classPg))
                unlink($classPg);
            if (file_exists($classDaoPg))
                unlink($classDaoPg);
            if (file_exists($proceduresPg))
                unlink($proceduresPg);
            if (file_exists($pastaCrud))
                rmdir($pastaCrud);
            $print = "Arquivos Anteriores excluídos com sucesso!<br/>";
        } catch (GException $exc) {
            $print = "Erro ao excluir arquivos";
            echo $exc->getTraceAsString();
        }
    }
    if (mkdir($pastaCrud, 0777)) {
        try {
            $arquivoCrud = fopen($crudPg, "wb");
            fwrite($arquivoCrud, $crud);
            fclose($arquivoCrud);

            $arquivoClass = fopen($classPg, "wb");
            fwrite($arquivoClass, $class);
            fclose($arquivoClass);

            $arquivoClassDao = fopen($classDaoPg, "wb");
            fwrite($arquivoClassDao, $classDao);
            fclose($arquivoClassDao);

            $arquivoProcedures = fopen($proceduresPg, "wb");
            fwrite($arquivoProcedures, $procedures);
            fclose($arquivoProcedures);

            $arquivoForm = fopen($formPg, "wb");
            fwrite($arquivoForm, $form);
            fclose($arquivoForm);

            $print = "Arquivos criados com sucesso!";
            $flag = true;
        } catch (GException $exc) {
            $print = "Erro ao criar arquivos";
            echo $exc->getTraceAsString();
        }
    } else
        $print = "Erro ao criar pasta";
} else
    $print = "Dados inválidos";

$alert = array();
if ($flag) {
    $alert[0] = 'success';
    $alert[1] = $print;
    $alert[2] = 'Sucesso';
} else {
    $alert[0] = 'error';
    $alert[1] = $print;
    $alert[2] = 'Atenção';
}
?>
<script>
    alert('<?php echo $alert[1]; ?>');
    window.location = 'index.php';
</script>
<?php
//--------------------------------------------------------------------
$footer = new GFooter();
$footer->show(true);

function recurse_chown_chgrp($mypath, $uid, $gid) {
    $d = opendir($mypath);
    while (($file = readdir($d)) !== false) {
        if ($file != "." && $file != "..") {

            $typepath = $mypath . "/" . $file;
            if (filetype($typepath) == 'dir') {
                recurse_chown_chgrp($typepath, $uid, $gid);
            }

            chown($typepath, $uid);
            chgrp($typepath, $gid);
        }
    }
}
?>

