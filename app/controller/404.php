<?php

/**
 * active page
 */

$root = $active = '';

if (strpos($_GET['route'], '/') !== false)
{
	list($root, $active) = explode('/', $_GET['route']);
}

/**
 * breadcrumbs
 */

$breadcrumbs[] = [
	'text' 		=> lang('home'),
	'href'		=> u('/'),
	'is_active'	=> false
];

$breadcrumbs[] = [
	'text' 		=> lang('page_not_found'),
	'href'		=> '',
	'is_active'	=> true
];

$data = [
	'header'		=> tpl('header.tpl', ['title' => lang('page_not_found'), 'root' => $root, 'active' => $active]),
	'footer'		=> tpl('footer.tpl'),
	'breadcrumbs'	=> $breadcrumbs
];

tpl('404.tpl', $data, true);
