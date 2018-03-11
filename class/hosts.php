<?php
define('DB_CONNECTION', 'mysql');
define('DB_HOST', 'mysql');
define('DB_PORT', '3306');
if (preg_match('/^localhost/',$_SERVER['HTTP_HOST'])) {
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('PATH_FOLDER', '/social_network');
    define('DB_DATABASE', 'social_network');
} else {
    define('DB_USERNAME', 'u346959931_haimt');
    define('DB_PASSWORD', '957846213');
    define('PATH_FOLDER', '/');
    define('DB_DATABASE', 'u346959931_socia');
}
