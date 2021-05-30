<?php

/**
 * init
 */

date_default_timezone_set('Asia/Kuala_Lumpur');

error_reporting(E_ALL);

ini_set('display_errors', true);

mb_internal_encoding('UTF-8');

/**
 * configuration
 */

require_once 'config.php';

/**
 * db
 */

require_once 'database.php';
