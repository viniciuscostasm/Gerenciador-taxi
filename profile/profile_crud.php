<?php

require_once("../_inc/global.php");

GF::importClass(array("user"));

$userDao = new UserDao();

$acao = $_POST['acao'];
switch ($acao) {
    case 'changePassword':
        $usr_var_currentpassword = sha1($_POST['usr_var_currentpassword']);
        $usr_var_newpassword = sha1($_POST['usr_var_newpassword']);
        echo json_encode($userDao->changePassword($usr_var_currentpassword, $usr_var_newpassword));
        break;
    case 'updateProfile':
        $user = GSec::getUserSession();
        $user->setUsr_var_name($_POST['usr_var_name']);
        $user->setUsr_var_email($_POST['usr_var_email']);
        $user->setUsr_var_phone($_POST['usr_var_phone']);
        $user->setUsr_var_function($_POST['usr_var_function']);
        $ret = $userDao->profile($user);
        if ($ret['status']) {
            GSec::updateUserSession($user);
        }
        echo json_encode($ret);
        break;
    default:
        echo json_encode(array('status' => false, 'msg' => 'Invalid action'));
        break;
}
?>
