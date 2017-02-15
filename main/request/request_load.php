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

// $usr_int_id = GSec::getUserSession()->getUsr_int_id();
$req_var_passenger = $_POST['p__req_var_passenger'];
$req_dat_date = $_POST['p__req_dat_date'];
$zon_int_id = $_POST['p__zon_int_id'];
$cit_int_id = $_POST['p__cit_int_id'];
$coc_int_id = $_POST['p__coc_int_id'];
$usr_int_id = $_POST['p__usr_int_id'];
$req_cha_status = $_POST['p__req_cha_status'];

$user = GSec::getUserSession();
$usr_int_idcurrent = $user->getUsr_int_id();

$query = "SELECT coc_int_id FROM vw_tax_costcenter_user where usr_int_id = ?";
$param = array("i", $usr_int_idcurrent);
$mysql->execute($query, $param);
$coc_int_idlist = array();
while ($mysql->fetch()) {
    $coc_int_idlist[] = $mysql->res['coc_int_id'];
}

if (!empty($coc_int_idlist)) {
    $coc_int_idlist = implode(',', $coc_int_idlist);

}

// $filter->addFilter('AND', 'usr_int_id', '=', 'i', $usr_int_id);

if (!empty($req_var_passenger)) {
    $filter->addFilter('AND', 'req_var_passenger', 'LIKE', 's', '%' . str_replace(' ', '%', $req_var_passenger) . '%');
}

if (!empty($zon_int_id)) {
    $filter->addClause('AND (zon_int_iddestination = ? OR zon_int_idsource = ?)', 'ii', array($zon_int_id, $zon_int_id));
}

if (!empty($cit_int_id)) {
    $filter->addClause('AND (cit_int_iddestination = ? OR cit_int_idsource = ?)', 'ii', array($cit_int_id, $cit_int_id));
} 

if (!empty($coc_int_id)) {
    $filter->addFilter('AND', 'coc_int_id', '=', 'i', $coc_int_id);
} else {
    if (!empty($coc_int_idlist)) {
        $filter->addClause('AND coc_int_id IN (' . $coc_int_idlist . ')');
    }
}

if (!empty($usr_int_id)) {
    $filter->addFilter('AND', 'usr_int_id', '=', 'i', $usr_int_id);
}

if (!empty($req_cha_status)) {
    $filter->addFilter('AND', 'req_cha_status', '=', 's', $req_cha_status);
}
if (!empty($req_dat_date)) {
    $arrData = explode('-', $req_dat_date);
    if (empty($arrData[1])) {
        $filter->addFilter('AND', 'req_dat_date', '=', 's', GF::convertDate($arrData[0]));
    } else {
        $inicio = GF::convertDate($arrData[0]);
        $fim = GF::convertDate($arrData[1]);
        $filter->addFilter('AND', 'req_dat_date', 'BETWEEN', 'ss', array($inicio, $fim), 'F');
    }
}

//-------------------------------- Filtros -----------------------------------//

try {
    if ($type == 'C') {
        $query = "SELECT count(1) FROM vw_tax_request " . $filter->getWhere();
        $param = $filter->getParam();
        $mysql->execute($query, $param);
        if ($mysql->fetch()) {
            $count = ceil($mysql->res[0] / $rp);
        }
        $count = $count == 0 ? 1 : $count;
        echo json_encode(array('count' => $count));
    } else if ($type == 'R') {
        $filter->setOrder(array('req_dat_date' => 'DESC'));
        $filter->setLimit($start, $rp);

        $query = "SELECT req_int_id, usr_int_id, usr_var_name, req_var_passenger,
                         coc_var_name, req_daf_date, req_var_hour, req_daf_add, req_var_status
                    FROM vw_tax_request " . $filter->getWhere();
        $param = $filter->getParam();
        $mysql->execute($query, $param);

        if ($mysql->numRows() > 0) {
            $html .= '<table class="table table-striped table-hover">';
            $html .= '<thead>';
            $html .= '<tr>';
            $html .= '<th>Viagem</th>';
            $html .= '<th>Passageiro</th>';
            $html .= '<th>Centro de custo</th>';
            $html .= '<th width="50px">Status</th>';
            $html .= '<th class="__acenter hidden-phone" width="50px">Actions</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';
            while ($mysql->fetch()) {
                $class = ($_POST['p__selecionado'] == $mysql->res['req_int_id']) ? 'success' : '';
                $html .= '<tr id="' . $mysql->res['req_int_id'] . '" class="linhaRegistro ' . $class . '">';
                $html .= '<td>' . $mysql->res['req_daf_date'] . ' às ' . $mysql->res['req_var_hour'] . '</td>';
                $html .= '<td>' . $mysql->res['req_var_passenger'] . '</td>';
                $html .= '<td>' . $mysql->res['coc_var_name'] . '</td>';
                $html .= '<td>' . $mysql->res['req_var_status'] . '</td>';
                //<editor-fold desc="Actions">
                $html .= '<td class="__acenter hidden-phone acoes">';
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