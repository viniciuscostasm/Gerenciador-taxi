<?php
// Conexões com o banco de dados
if ($_SERVER['SERVER_NAME'] == 'localhost' || strpos($_SERVER['SERVER_NAME'], '192.168') !== false) {
    define('SERVER', 'D');
    define('MYSQL_HOST', '192.168.0.13');
    define('MYSQL_PORT', '3306');
    define('MYSQL_USER', 'continentaltaxi');
    define('MYSQL_PASS', 'num3r0l0g14');
    define('MYSQL_BASE', 'continentaltaxi');
    define('MYSQL_CHARSET', 'utf8');
    define('MYSQL_TIMEZONE', '-03:00');
    define('PHP_TIMEZONE', 'America/Bahia');
} else if (strpos($_SERVER['SERVER_NAME'], 'continentaltaxi.inactu.com.br') !== false) {
    define('SERVER', 'D');
    define('MYSQL_HOST', 'mysql.inactu.com.br');
    define('MYSQL_PORT', '3306');
    define('MYSQL_USER', 'inactu71');
    define('MYSQL_PASS', 'num3r0l0g14');
    define('MYSQL_BASE', 'inactu71');
    define('MYSQL_CHARSET', 'utf8');
    define('MYSQL_TIMEZONE', '-03:00');
    define('PHP_TIMEZONE', 'America/Sao_Paulo');
} else {
    define('SERVER', 'D');
    define('MYSQL_HOST', 'localhost');
    define('MYSQL_PORT', '3306');
    define('MYSQL_USER', '8duser');
    define('MYSQL_PASS', 'c0nt1n3nt@l');
    define('MYSQL_BASE', 'taxi');
    define('MYSQL_CHARSET', 'utf8');
    define('MYSQL_TIMEZONE', '-03:00');
    define('PHP_TIMEZONE', 'America/Sao_Paulo');
}
