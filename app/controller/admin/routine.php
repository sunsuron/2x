<?php

if ( ! isset($_SESSION['user']['user_id']) || !$_SESSION['user']['user_id'])
{
	logout();
	
	redirect('/login');
}

/**
 * in-complete profile
 */

if (isset($_SESSION['user']['user_id']) && $_SESSION['user']['user_id'] && in_array($acl['icp'], $_SESSION['acl']))
{
	$_SESSION['error'][] = sprintf(lang('help_profile'));

	redirect(u('/profile'));
}
