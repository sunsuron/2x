
<?php

if (isset($_GET['page']) && $_GET['page']) {
    $page = (int) $_GET['page'];
} else {
    $page = 1;
}

/**
 * tasks
 */

$tasks = load_model('admin/task')->tasks($_SESSION['user']['user_id'], $page);

/**
 * optional
 * recalculate total_datetime_spent
 */

foreach ($tasks->rows as $k => $task) {
    $task_datetime_start = $task['task_datetime_start'];
    $task_datetime_end = $task['task_datetime_end'];
    if ($task['task_datetime_start'] != '0000-00-00 00:00:00' && $task['task_datetime_end'] != '0000-00-00 00:00:00') {
        $total_datetime_spent = total_datetime_spent($task_datetime_start, $task_datetime_end);
        $tasks->rows[$k]['total_datetime_spent'] = $total_datetime_spent;
        db_update('task', ['total_datetime_spent' => $total_datetime_spent], ['task_id' => $task['task_id']]);
    }
}

/**
 * pagination
 */

$pagination = pagination($page, $tasks->total_rows, u('admin/task?page={page}'));

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
    'text' => lang('my_task'),
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
    'header' => tpl('header.tpl', ['title' => lang('my_task'), 'root' => $root, 'active' => $active], false, [], $links),
    'footer' => tpl('footer.tpl', [], false, $scripts),
    'breadcrumbs' => $breadcrumbs,
    'tasks' => $tasks,
    'pagination' => $pagination,
    'page' => $page,
];

tpl('admin/task/home.tpl', $data, true);
