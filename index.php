<?php

$__externo = true;
require_once '_inc/global.php';

if (GSec::validarLogin()) {
    header('Location: dashboard/dashboard.php');
} else {
    header('Location: login/login.php');
}
?>