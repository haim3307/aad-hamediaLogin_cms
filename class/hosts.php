<?php
define('DB_CONNECTION', 'mysql');
define('DB_HOST', 'mysql');
define('DB_PORT', '3306');
define('DB_DATABASE', 'social_network');
echo '<pre style="direction: ltr;">';
var_dump($_SERVER);

echo '</pre>';
var_dump($_SERVER['HTTP_HOST']);
if (preg_match('/^localhost/',$_SERVER['HTTP_HOST'])) {
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('PATH_FOLDER', '/social_network');
} else {
    define('DB_USERNAME', 'haimt');
    define('DB_PASSWORD', '957846213Ha');
    define('PATH_FOLDER', '/');
}
