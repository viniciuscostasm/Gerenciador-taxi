<?php

require_once("../../_inc/global.php");
require_once("../../_genesis/inc/MadMimi.class.php");

GF::importClass(array("user", "profile"));

$mysql = new GDbMysql();

$userDao = new UserDao();

$usr_int_id = $_POST["usr_int_id"];
list($pro_int_id, $pro_cha_type) = explode('-', $_POST["pro_int_id"]);

$profile = new Profile();
$profile->setPro_int_id($pro_int_id);

// $zone = new Zone();
// $zone->setZon_int_id($_POST['zon_int_id']);

$usr_int_id = $_POST["usr_int_id"];
$usr_var_name = $_POST["usr_var_name"];
$usr_var_email = $_POST["usr_var_email"];
$usr_var_function = $_POST["usr_var_function"];
$usr_var_phone = $_POST["usr_var_phone"];
$usr_cha_status = $_POST["usr_cha_status"];

$user = new User();
$user->setUsr_int_id($usr_int_id);
$user->setUsr_var_name($usr_var_name);
$user->setUsr_var_email($usr_var_email);
$user->setUsr_var_function($usr_var_function);
$user->setUsr_var_phone($usr_var_phone);
$user->setUsr_cha_status($usr_cha_status);
$user->setProfile($profile);
// $user->setZone($zone);

switch ($_POST["acao"]) {
    case "ins":
        $pro_int_id = $_POST['pro_int_id'];
        if ($pro_int_id == '4-SOL') {
            if (empty($_POST['coc_int_idlist'])) {
                echo json_encode(array("status" => false, "msg" => "Você deve selecionar pelo menos um centro de custo para o perfil solicitante."));
                break;
            }
        } else if ($pro_int_id == '8-GAR') {
            if (empty($_POST['coc_int_idlist'])) {
                echo json_encode(array("status" => false, "msg" => "Você deve selecionar pelo menos um centro de custo para o gestor de área."));
                break;
            }
        } else if ($pro_int_id == '5-EMP') {
            if (empty($_POST['txc_int_idlist'])) {
                echo json_encode(array("status" => false, "msg" => "Você deve selecionar uma empresa de táxi para o usuário."));
                break;
            }
        }
        $coc_int_idlist = (!empty($_POST['coc_int_idlist'])) ? implode('||', $_POST['coc_int_idlist']) : null;
        $txc_int_idlist = (!empty($_POST['txc_int_idlist'])) ? $_POST['txc_int_idlist'] : null;

        $senhaGerada = substr(md5(uniqid()), 0, 6);
        $usr_var_password = sha1($senhaGerada);
        $user->setUsr_var_password($usr_var_password);

        $ret = $userDao->insert($user, $coc_int_idlist, $txc_int_idlist);
        if ($ret['status']) {
            $conteudo = 'Dear ' . $usr_var_name . ',<br /><br />';
            $conteudo .= 'Follow the access credentials to <a href="' . URL_SYS . '">' . SYS_TITLE . '</a><br /><br />';
            $conteudo .= 'Email: ' . $user->getUsr_var_email() . '<br />';
            $conteudo .= 'Password: ' . $senhaGerada;

            $assunto = 'Access credentials from ' . SYS_TITLE;

            $mensagem = formatarEmail(array($assunto => $conteudo));
            
            GF::sendEmail(SYS_EMAIL_SUPORT, $user->getUsr_var_email(), $assunto, $mensagem);
        }
        echo json_encode($ret);
        break;
    case "upd":
        $coc_int_idlist = null;
        $txc_int_idlist = null;
        if ($_POST['pro_int_id'] == '4-SOL') {
            if (empty($_POST['coc_int_idlist'])) {
                echo json_encode(array("status" => false, "msg" => "Você deve selecionar um centro de custo para o perfil solicitante."));
                break;
            }
            $coc_int_idlist = (!empty($_POST['coc_int_idlist'])) ? implode(',', $_POST['coc_int_idlist']) : null;
        } else if ($_POST['pro_int_id'] == '5-EMP') {
            if (empty($_POST['txc_int_idlist'])) {
                echo json_encode(array("status" => false, "msg" => "Você deve selecionar uma empresa de táxi para o usuário."));
                break;
            }
            $txc_int_idlist = $_POST['txc_int_idlist'];
        }
        echo json_encode($userDao->update($user, $coc_int_idlist, $txc_int_idlist));
        break;
    case "del":
        echo json_encode($userDao->delete($user));
        break;
    case "sel":
        echo json_encode($userDao->selectByIdForm($user));
        break;
    case "pesqEmail":
        $usr_var_email = $_POST['usr_var_email'];

        try {
            $query = "SELECT usr_int_id, usr_var_name
                        FROM vw_adm_user
                       WHERE usr_var_email = ?";
            $param = array('is', $environment->getEnv_int_id(), $usr_var_email);
            $mysql->execute($query, $param);

            if ($mysql->fetch()) {
                if (is_null($mysql->res['usr_int_id'])) {
                    $ret = array('status' => true,
                        'usr_int_id' => $mysql->res['usr_int_id'],
                        'usr_var_name' => $mysql->res['usr_var_name'],
                    );
                } else {
                    $ret = array('status' => false,
                        'msg' => 'User already exists.');
                }
            } else {
                $ret = array('status' => true, 'msg' => '');
            }
        } catch (GDbException $exc) {
            $ret = array('status' => false, 'msg' => $exc->getError());
        }
        echo json_encode($ret);
        break;
    case "changeProfile":
        $form = new GForm();
        try {
            if($pro_cha_type == 'EMP'){
                $txc_int_idlist = (!empty($_POST['txc_int_idlist'])) ? $_POST['txc_int_idlist'] : null;
                $query = "SELECT txc_int_id, txc_var_name
                            FROM vw_tax_taxicompany
                        ORDER BY txc_var_name";
                $opt_txc_var_name = $mysql->executeCombo($query);
                echo $form->addSelect('txc_int_idlist', $opt_txc_var_name, $txc_int_idlist, 'Empresa de Táxi*', array('class' => 'pla_int_idlist'), false, false, true, '', 'Select...', true, false);
            } else if($pro_cha_type == 'SOL' || $pro_cha_type == 'GAR'){
                $coc_int_idlist = (!empty($_POST['coc_int_idlist'])) ? explode('||', $_POST['coc_int_idlist']) : array() ;

                $query = "SELECT coc_int_id, coc_var_name
                            FROM vw_tax_costcenter
                        ORDER BY coc_var_name";
                $mysql->execute($query);

                $html .= $form->addLabel('lbl_coc_int_idlist', 'Centros de Custo');
                while ($mysql->fetch()) {
                    $checked = (in_array($mysql->res['coc_int_id'], $coc_int_idlist)) ? array('checked' => 'checked') : array();
                    $html .= $form->addCheckbox('coc_int_idlist[]', $mysql->res['coc_var_name'], array('value' => $mysql->res['coc_int_id'], 'class' => 'icheck coc_int_idlist') + $checked);
                }

                $html .= '<script>
                    $(".icheck").iCheck({
                        checkboxClass: "icheckbox_minimal"
                    });
                </script>';
                echo $html;
            }
        } catch (GDbException $e) {
            
        }
        break;
    case "loadZone":
        $zon_int_id = $_POST['zon_int_id'];
        $cou_cha_country = $_POST['cou_cha_country'];
        $form = new GForm();
        try {
            $query = "SELECT zon_int_id, zon_var_name
                        FROM vw_adm_zone
                       WHERE cou_cha_country = ? 
                    ORDER BY zon_var_name";
            $param = array('s', $cou_cha_country);
            $opt_zon_var_name = $mysql->executeCombo($query, $param);
            echo $form->addSelect('zon_int_id', $opt_zon_var_name, $zon_int_id, 'Timezone*', array('validate' => 'required'), false, false, true, '', 'Select...', true, false);
        } catch (GDbException $e) {
            echo $e->getError();
        }
        break;
    default:
        echo json_encode(array("status" => false, "msg" => "Invalid action"));
        break;
}