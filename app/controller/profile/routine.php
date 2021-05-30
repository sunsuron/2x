<?php

if ( ! isset($_SESSION['user']['user_id']) || !$_SESSION['user']['user_id'])
{
	logout();
	
	redirect('/login');
}
