<?php
define('DB_CONNECTION', 'mysql');
define('DB_HOST', 'mysql');
define('DB_PORT', '3306');
define('DB_DATABASE', 'aadhamedina');
if ($_SERVER['HTTP_HOST'] == 'localhost') {
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
} else {
    define('DB_USERNAME', 'haimt');
    define('DB_PASSWORD', '957846213Ha');
}