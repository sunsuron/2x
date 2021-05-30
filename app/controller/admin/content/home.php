<?php

if (isset($_GET['page']) && $_GET['page']) {
    $page = (int) $_GET['page'];
} else {
    $page = 1;
}

/**
 * contents
 */

$contents = load_model('admin/content')->contents($_SESSION['user']['user_id']);

/**
 * pagination
 */

$pagination = pagination($page, $contents->total_rows, u('admin/content?page={page}'));

/**
 * list_content type
 */

$content_types = load_model('lists')->get('content_type', 1, 100, 'ORDER BY name DESC')->rows;

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
    'text' => lang('my_content'),
    'href' => '',
    'is_active' => true,
];

$links = [
    u(TEMPLATE . '/css/components/bs-select.css'),
];

$scripts = [
    u(TEMPLATE . '/js/components/bs-select.js'),
    u(TEMPLATE . '/js/admin/content/home.js'),
];

$data = [
    'header' => tpl('header.tpl', ['title' => lang('my_content'), 'root' => $root, 'active' => $active]),
    'footer' => tpl('footer.tpl', [], false, $scripts, $links),
    'breadcrumbs' => $breadcrumbs,
    'contents' => $contents,
    'content_types' => $content_types,
    'pagination' => $pagination,
    'page' => $page,
];

tpl('admin/content/home.tpl', $data, true);
