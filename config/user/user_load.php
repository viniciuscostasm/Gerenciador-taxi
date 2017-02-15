<?php

//sleep(100);
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

$usr_var_name = $_POST['p__usr_var_name'];
$pro_int_id = $_POST['p__pro_int_id'];
$usr_cha_status = $_POST['p__usr_cha_status'];
list($pro_int_id, $pro_cha_type) = explode('-', $_POST["p__pro_int_id"]);


if (!empty($usr_var_name)) {
    $filter->addFilter('AND', 'usr_var_name', 'LIKE', 's', '%' . str_replace(' ', '%', $usr_var_name) . '%');
}
if (!empty($pro_int_id)) {
    $filter->addFilter('AND', 'pro_int_id', '=', 'i', $pro_int_id);
}
if (!empty($usr_cha_status)) {
    $filter->addFilter('AND', 'usr_cha_status', '=', 's', $usr_cha_status);
}
// $filter->addClause("AND pro_cha_type NOT IN ('ADM')");
//-------------------------------- Filtros -----------------------------------//

try {
    if ($type == 'C') {
        $query = "SELECT count(1) FROM vw_adm_user " . $filter->getWhere();
        $param = $filter->getParam();
        $mysql->execute($query, $param);
        if ($mysql->fetch()) {
            $count = ceil($mysql->res[0] / $rp);
        }
        $count = $count == 0 ? 1 : $count;
        echo json_encode(array('count' => $count));
    } else if ($type == 'R') {

        $filter->setOrder(array('usr_cha_status' => 'ASC', 'usr_var_name' => 'ASC'));
        $filter->setLimit($start, $rp);

        $query = "SELECT usr_int_id, usr_var_name, pro_var_name,
                         usr_cha_status, usr_var_status
                    FROM vw_adm_user " . $filter->getWhere();
        $param = $filter->getParam();
        $mysql->execute($query, $param);

        $html .= '<div class="row-fluid">';
        $html .= '<div class="span12">';
        if ($mysql->numRows() > 0) {
            $html .= '<table class="table table-striped table-hover">';
            $html .= '<thead>';
            $html .= '<tr>';
            $html .= '<th>Name</th>';
            $html .= '<th>Profile</th>';
            $html .= '<th class="__acenter hidden-phone" width="50px">Status</th>';
            $html .= '<th class="__acenter hidden-phone" width="100px">Actions</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';
            while ($mysql->fetch()) {
                $class = ($_POST['p__selecionado'] == $mysql->res['usr_int_id']) ? 'success' : '';
                $html .= '<tr id="' . $mysql->res['usr_int_id'] . '" rel="' . $mysql->res['usr_int_id'] . '" class="linhaRegistro" >';
                $html .= '<td>' . $mysql->res['usr_var_name'] . '</td>';
                $html .= '<td>' . $mysql->res['pro_var_name'] . '</td>';
                $html .= '<td class="__acenter hidden-phone"><span class="badge badge-' . (($mysql->res['usr_cha_status'] == 'I') ? 'danger' : 'success') . '">' . $mysql->res['usr_var_status'] . '</span></td>';
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
            $html .= '<div class="widget-container">';
            $html .= '<div class="nenhumResultado">Nenhum resultado encontrado.</div>';
            $html .= '</div>';
        }
        $html .= '</div>'; //span12
        $html .= '</div>'; //row-fluid

        echo $html;
    }
} catch (GDbException $exc) {
    echo $exc->getError();
}
?>