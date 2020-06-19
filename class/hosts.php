<?php
define('DB_CONNECTION', 'mysql');
define('DB_HOST', 'mysql');
if (preg_match('/^localhost/',$_SERVER['HTTP_HOST'])) {
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('PATH_FOLDER', '/social_network');
    define('DB_DATABASE', 'social_network');
    define('MYSQL_HOST', 'localhost');
} else {
    define('DB_USERNAME', 'bigshop3_haim');
    define('DB_PASSWORD', '957846213Ha');
    define('PATH_FOLDER', '/');
    define('DB_DATABASE', 'bigshop3_fakebook');
    define('MYSQL_HOST', 'localhost');

}
