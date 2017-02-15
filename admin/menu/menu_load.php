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

$men_var_name = $_POST['p__men_var_name'];

if (!empty($men_var_name)) {
    $filter->addFilter('AND', 'men_var_name', 'LIKE', 's', '%' . str_replace(' ', '%', $men_var_name) . '%');
}
//-------------------------------- Filtros -----------------------------------//
try {
    if ($type == 'C') {
        $query = "SELECT count(1) FROM vw_adm_menu " . $filter->getWhere();
        $param = $filter->getParam();
        $mysql->execute($query, $param);
        if ($mysql->fetch()) {
            $count = ceil($mysql->res[0] / $rp);
        }
        $count = $count == 0 ? 1 : $count;
        echo json_encode(array('count' => $count));
    } else if ($type == 'R') {
        $filter->setOrder(array('men_var_key' => 'ASC'));
        $filter->setLimit($start, $rp);

        $query = "SELECT men_int_id, men_var_name, men_int_level, men_var_icon, men_cha_consolidator, men_cha_status, men_var_status, men_int_order, men_var_type
                    FROM vw_adm_menu " . $filter->getWhere();
        $param = $filter->getParam();
        $mysql->execute($query, $param);

        if ($mysql->numRows() > 0) {
            $html .= '<table class="table table-striped table-hover">';
            $html .= '<thead>';
            $html .= '<tr>';
            $html .= '<th>Name</th>';
            $html .= '<th class="__acenter hidden-phone" width="90px">Order</th>';
            $html .= '<th class="__acenter hidden-phone" width="90px">Type</th>';
            $html .= '<th class="__acenter hidden-phone" width="90px">Status</th>';
            $html .= '<th class="__acenter hidden-phone" width="100px">Actions</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';
            while ($mysql->fetch()) {
                $class = ($_POST['p__selecionado'] == $mysql->res['men_int_id']) ? 'success' : '';

                $padding = ($mysql->res['men_int_level'] - 1) * 30;

                $html .= '<tr id="' . $mysql->res['men_int_id'] . '" class="linhaRegistro ' . $class . '">';
                $html .= '<td><span  style="padding-left:' . $padding . 'px"><i class="' . $mysql->res['men_var_icon'] . '"></i> ' . $mysql->res['men_var_name'] . '</span></td>';
                $html .= '<td class="__acenter hidden-phone">' . $mysql->res['men_int_order'] . '</td>';
                $html .= '<td class="__acenter hidden-phone">' . $mysql->res['men_var_type'] . '</td>';
                $html .= '<td class="__acenter hidden-phone"><span class="badge badge-' . (($mysql->res['men_cha_status'] == 'I') ? 'important' : 'success') . '">' . $mysql->res['men_var_status'] . '</span></td>';
                //<editor-fold desc="Ações">
                $html .= '<td class="__acenter hidden-phone acoes">';
                $html .= $form->addButton('l__btn_editar', '<i class="fa fa-pencil"></i>', array('class' => 'btn btn-icon-only l__btn_editar', 'title' => 'Editar'));
                $html .= $form->addButton('l__btn_excluir', '<i class="fa fa-trash"></i>', array('class' => 'btn btn-icon-only l__btn_excluir', 'title' => 'Excluir'));
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