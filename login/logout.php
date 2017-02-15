<?php

$__externo = true;
$__assistente = true;
require_once("../_inc/global.php");

unset($_SESSION["s_user"]);

session_destroy();
echo 'Aguarde...<script>self.location = "' . URL_SYS . '";</script>';