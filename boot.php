<?php
/**
 * url helpers
 *
 * @param string $query_string
 * @param string $replacements
 * @return string
 */
function u($query_string, $replacements = '')
{
    $url = '';

    $query_string = ltrim($query_string, '/');

    $url = sprintf($query_string, $replacements);

    $url = URL . $url;

    return $url;
}

/**
 * debug
 */

$start = -microtime(true);

$sql_count = 0;

/**
 * check requirement
 */

if (version_compare(phpversion(), '7.0.0', '<') == true) {
    exit('PHP 7.0+ Required');
}

/**
 * init
 */

date_default_timezone_set('Asia/Kuala_Lumpur');

error_reporting(E_ALL);

ini_set('display_errors', true);

mb_internal_encoding('UTF-8');

mb_http_output('UTF-8');

session_start();

/**
 * configuration
 */

require_once 'config.php';

/**
 * loader
 */

require_once 'loader.php';

/**
 * init db
 */

$mysqli = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

mysqli_query($mysqli, "SET NAMES 'utf8mb4'");
mysqli_query($mysqli, "SET CHARACTER SET utf8mb4");
mysqli_query($mysqli, "SET CHARACTER_SET_CONNECTION=utf8mb4");
mysqli_query($mysqli, "SET SQL_MODE = ''");

require_once 'database.php';

/**
 * acl
 */

require_once 'acl.php';

/**
 * vendors
 */

require 'vendor/autoload.php';

/**
 * language
 */

$languages = [];

$langs = load_model('language')->get()->rows;

foreach ($langs as $lang) {
    $languages[$lang['code']] = $lang;
}

$detect = '';

if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && $_SERVER['HTTP_ACCEPT_LANGUAGE']) {
    $browser_languages = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);

    foreach ($browser_languages as $browser_language) {
        foreach ($languages as $key => $value) {
            $locale = explode(',', $value['locale']);

            if (in_array($browser_language, $locale)) {
                $detect = $key;

                break;
            }
        }

        if ($detect) {
            break;
        }

    }
}

if (isset($_SESSION['language']) && array_key_exists($_SESSION['language'], $languages)) {
    $code = $_SESSION['language'];
} elseif (isset($_COOKIE['language']) && array_key_exists($_COOKIE['language'], $languages)) {
    $code = $_COOKIE['language'];
} elseif ($detect) {
    $code = $detect;
} else {
    $code = 'en';
}

if (!isset($_SESSION['language']) || $_SESSION['language'] != $code) {
    $_SESSION['language'] = $code;
}

if (!isset($_COOKIE['language']) || $_COOKIE['language'] != $code) {
    setcookie('language', $code, time() + 60 * 60 * 24 * 30, '/', $_SERVER['HTTP_HOST']);
}

load_language($code);

/**
 * function
 */

require_once 'function.php';

/**
 * routine
 */

require_once 'routine.php';

/**
 * simple router
 */

if (MAINTENANCE) {
    require_once 'app/controller/maintenance.php';
} elseif (isset($_GET['route'])) {
    $_GET['route'] = trim($_GET['route'], '/');

    route($_GET['route']);
} else {
    require_once 'app/controller/home.php';
}

/**
 * clean
 */

unset
    (
    $_SESSION['error'],
    $_SESSION['success']
);

/**
 * close database connection
 */

mysqli_close($mysqli);
