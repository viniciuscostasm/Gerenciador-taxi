<?php

require_once("../../_inc/global.php");

$html = '';
$mysql = new GDbMysql();
$form = new GForm();
//------------------------------ Parâmetros ----------------------------------//
$type = $_POST['type'];
$page = $_POST['page'];
$count = $_POST['count'];
$rp = (int) $_POST['rp'];
$start = (($page - 1) * $rp);
//------------------------------ Parâmetros ----------------------------------//
//-------------------------------- Filtros -----------------------------------//
$filter = new GFilter();

$zon_var_name = $_POST['p__zon_var_name'];

if (!empty($zon_var_name)) {
    $filter->addFilter('AND', 'zon_var_name', 'LIKE', 's', '%' . str_replace(' ', '%', $zon_var_name) . '%');
}
//-------------------------------- Filtros -----------------------------------//

try {
    if ($type == 'C') {
        $query = "SELECT count(1) FROM vw_tax_zone " . $filter->getWhere();
        $param = $filter->getParam();
        $mysql->execute($query, $param);
        if ($mysql->fetch()) {
            $count = ceil($mysql->res[0] / $rp);
        }
        $count = $count == 0 ? 1 : $count;
        echo json_encode(array('count' => $count));
    } else if ($type == 'R') {
        $filter->setOrder(array('zon_var_name' => 'ASC'));
        $filter->setLimit($start, $rp);

        $query = "SELECT zon_int_id, zon_var_name FROM vw_tax_zone " . $filter->getWhere();
        $param = $filter->getParam();
        $mysql->execute($query, $param);

        if ($mysql->numRows() > 0) {
            $html .= '<table class="table table-striped table-hover">';
            $html .= '<thead>';
            $html .= '<tr>';
            $html .= '<th>Nome</th>';
            $html .= '<th class="__acenter hidden-phone" width="100px">Actions</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';
            while ($mysql->fetch()) {
                $class = ($_POST['p__selecionado'] == $mysql->res['zon_int_id']) ? 'success' : '';
                $html .= '<tr id="' . $mysql->res['zon_int_id'] . '" class="linhaRegistro ' . $class . '">';
                $html .= '<td>' . $mysql->res['zon_var_name'] . '</td>';
                //<editor-fold desc="Actions">
                $html .= '<td class="__acenter hidden-phone acoes">';
                $html .= $form->addButton('l__btn_editar', '<i class="fa fa-pencil"></i>', array('class' => 'btn btn-small btn-icon-only l__btn_editar', 'title' => 'Edit'));
                $html .= $form->addButton('l__btn_excluir', '<i class="fa fa-trash"></i>', array('class' => 'btn btn-small btn-icon-only l__btn_excluir', 'title' => 'Remove'));
                $html .= '</td>';
                //</editor-fold>
                $html .= '</tr>';
            }
            $html .= '</tbody>';
            $html .= '</table>';
        } else {
            $html .= '<div class="nenhumResultado">Nenhum resultado encontrado.</div>';
        }

        echo $html;
    }
} catch (GDbException $exc) {
    echo $exc->getError();
}
?>