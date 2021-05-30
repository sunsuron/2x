<?php

/**
 * total tasks
 */

$total_tasks = load_model('admin/task')->total_tasks($_SESSION['user']['user_id'])->row['total_rows'];

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
    'href' => '',
    'is_active' => true,
];

$data = [

    'header' => tpl('header.tpl', ['title' => lang('dashboard'), 'root' => 'admin', 'active' => 'admin']),
    'footer' => tpl('footer.tpl'),
    'breadcrumbs' => $breadcrumbs,
    'total_tasks' => $total_tasks,
];

tpl('admin/home.tpl', $data, true);
