<?php

if (is_logged())
{
	acl_update();
}
else
{
	$_SESSION['aclaccess'] = $aclaccess['guest'];
}

/**
 * client unique identity cookie
 */

if (!isset($_COOKIE['client']) || !$_COOKIE['client'])
{
    $client = md5(microtime() . random_bytes(16));
	
	setcookie('client', $client, time() + 60 * 60 * 24 * 30, '/', $_SERVER['HTTP_HOST']);
}
