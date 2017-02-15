<?php

require_once("../../_inc/global.php");

$json = array();
$mysql = new GDbMysql();
$filter = new GFilter();

$emp_var_namekey = '%' . $_POST['emp_var_namekey'] . '%';

$user = GSec::getUserSession();
$where = '';
if ($user->getProfile()->getPro_cha_type() == 'SOL') {
    $usr_int_idcurrent = $user->getUsr_int_id();
    $filter->addFilter('AND', 'usr_int_id', '=', 'i', $usr_int_idcurrent);
    $query = "SELECT coc_int_id FROM vw_tax_costcenter_user ". $filter->getWhere();
    $param = $filter->getParam();
    $mysql->execute($query, $param);
    $coc_list = array();
    while ($mysql->fetch()) {
        $coc_list[] = $mysql->res['coc_int_id'];
    }
    $coc_list = implode(',', $coc_list);
}
if (!empty($coc_list)) {
    $where = "AND coc_int_id IN (" . $coc_list . ")";
}
try {
    $query = "SELECT emp_int_id, emp_var_name, CONCAT(emp_var_key,' - ',emp_var_name) as emp_var_namekey, emp_var_address, cit_int_id, dis_int_id 
                FROM vw_tax_employee 
                WHERE CONCAT(IFNULL(emp_var_key, ''),' - ',emp_var_name) LIKE ? " . $where ."
                LIMIT 20";
    $param = array('s', $emp_var_namekey);
    $mysql->execute($query, $param);

    while ($mysql->fetch()) {
        $json[] = array(
            'emp_int_id' => $mysql->res['emp_int_id'],
            'emp_var_name' => $mysql->res['emp_var_name'],
            'emp_var_namekey' => $mysql->res['emp_var_namekey'],
            'emp_var_address' => $mysql->res['emp_var_address'],
            'cit_int_id' => $mysql->res['cit_int_id'],
            'dis_int_id' => $mysql->res['dis_int_id']
        );
    }


    echo json_encode($json);
 
} catch (GDbException $exc) {
    echo $exc->getError();
}
