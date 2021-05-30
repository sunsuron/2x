
<?php

/**
 * active page
 */

if (strpos($_GET['route'], '/') !== false) {
    list($root, $active) = explode('/', $_GET['route']);
}

/**
 * breadcrumbs
 */

$breadcrumbs[] = [
    'text' => lang('dashboard'),
    'href' => u('/admin'),
    'is_active' => false,
];

$breadcrumbs[] = [
    'text' => lang('my_payment'),
    'href' => '',
    'is_active' => true,
];

$data = [
    'header' => tpl('header.tpl', ['title' => lang('my_payment'), 'root' => $root, 'active' => $active]),
    'footer' => tpl('footer.tpl'),
    'breadcrumbs' => $breadcrumbs,
];

tpl('admin/payment/home.tpl', $data, true);
