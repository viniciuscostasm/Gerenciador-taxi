<?php

$__externo = true;
require_once("../_inc/global.php");

GF::importClass(array("user"));

$mysql = new GDbMysql();

$user = new User();
$userDao = new UserDao();

$acao = $_POST['acao'];
switch ($acao) {
    case 'login':
        $user->setUsr_var_email($_POST["l_usr_var_email"]);
        $user->setUsr_var_password(sha1($_POST["l_usr_var_password"]));

        echo json_encode($userDao->login($user));
        break;
    case 'esqueci':
        $ret = array();
        try {
            $usr_var_email = $_POST['r_usr_var_email'];

            $query = "SELECT usr_var_name, usr_cha_status, usr_var_token, usr_var_email
                        FROM vw_adm_user
                       WHERE usr_var_email = ?";
            $param = array('s', $usr_var_email);
            $mysql->execute($query, $param);
            if ($mysql->fetch()) {
                $usr_cha_status = $mysql->res['usr_cha_status'];
                if ($usr_cha_status == 'A') {
                    $usr_var_name = $mysql->res['usr_var_name'];
                    $usr_var_email = $mysql->res['usr_var_email'];
                    $url = URL_SIGNIN . '?token=' . $mysql->res['usr_var_token'] . '&type=forgot';

                    $conteudo = $usr_var_name . ',<br /><br />';
                    $conteudo .= 'You requested to change your password in <b>' . SYS_TITLE . '</b>.<br />';
                    $conteudo .= '<a href="' . $url . '">Click here</a> or copy the link below and paste it in your browser.<br /><br />';
                    $conteudo .= $url;

                    $assunto = 'Recover password ' . SYS_TITLE;
                    $mensagem = formatarEmail(array($assunto => $conteudo));

                    $retEmail = GF::sendEmail(SYS_EMAIL_SUPORT, $usr_var_email, $assunto, $mensagem);

                    if ($retEmail['status']) {
                        $ret['status'] = true;
                        $ret['msg'] = 'The instructions were sent to the email <b>' . $usr_var_email . '</b>';
                    } else {
                        $ret = $retEmail;
                    }
                } else {
                    $ret['status'] = false;
                    $ret['msg'] = 'Inactive user';
                }
            } else {
                $ret['status'] = false;
                $ret['msg'] = 'Invalid email';
            }
        } catch (GDbException $exc) {
            $ret['status'] = false;
            $ret['msg'] = $exc->getError();
        }

        echo json_encode($ret);
        break;
    case 'forgot':
        $ret = array();
        $token = $_POST['token'];
        $usr_var_password = sha1($_POST['r_usr_var_password']);
        try {
            $query = "SELECT usr_int_id, usr_cha_status, usr_var_email FROM vw_adm_user WHERE usr_var_token = ? ";
            $param = array('s', $token);
            $mysql->execute($query, $param);
            if ($mysql->fetch()) {
                $usr_cha_status = $mysql->res['usr_cha_status'];
                $usr_int_id = $mysql->res["usr_int_id"];
                $usr_var_email = $mysql->res["usr_var_email"];
                $mysql->close();
                if ($usr_cha_status == 'A') {
                    $query = "CALL sp_adm_userpassword_change(?,?);";
                    $param = array('is', $usr_int_id, $usr_var_password);
                    $mysql->execute($query, $param);
                    $mysql->fetch();
                    if ($mysql->res[0]) {
                        $user->setUsr_var_email($usr_var_email);
                        $user->setUsr_var_password($usr_var_password);
                        $ret = $userDao->login($user);
                    } else {
                        $ret['status'] = false;
                        $ret['msg'] = $mysql->res[1];
                    }
                } else {
                    $ret['status'] = false;
                    $ret['msg'] = 'Inactive user.';
                }
            } else {
                $ret['status'] = false;
                $ret['msg'] = 'Invalid link, please contact the support team';
            }
        } catch (GDbException $exc) {
            $ret['status'] = false;
            $ret['msg'] = $exc->getError();
        }

        echo json_encode($ret);
        break;

    default:
        echo json_encode(array('status' => false, 'msg' => 'Invalid action'));
        break;
}
?>
