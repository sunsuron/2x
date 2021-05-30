<?php

function goodget()
{
    clean();

    $_SESSION['error'] = [];

    if (!isset($_GET['task_id']) && !$_GET['task_id']) {
        $_SESSION['error'][] = sprintf(lang('err_required'), lang('task_id'));
    } else {
        $_GET['task_id'] = (int)$_GET['task_id'];
        $task = load_model('admin/task')->task($_GET['task_id'], $_SESSION['user']['user_id']);
        if (!$task->num_rows) {
            $_SESSION['error'][] = sprintf(lang('err_nonexistence'), lang('task_id'));
        }
    }

    if ($_SESSION['error']) {
        return false;
    }

    return true;
}

if (!goodget()) {
    redirect(u('/admin/task'));
}

if (isset($_POST['update_master_task'])) {

    mysqli_autocommit($mysqli, false);

    try {

        /**
         * this task from DB
         */

        $task = load_model('admin/task')->task($_GET['task_id'], $_SESSION['user']['user_id'])->row;

        /**
         * flags
         * in_progress
         * - in_progress = 1
         * - is_canceled = 0
         * - is_completed = 0
         * - is_deleted = 0
         */

        if (isset($_POST['master_task_status']) && ($_POST['master_task_status'] == 'in_progress')) {
            $in_progress = 1;
            $is_completed = $is_canceled = $is_deleted = 0;
        }

        /**
         * flags
         * is_completed
         * - in_progress = 0
         * - is_completed = 1
         * - is_canceled = 0
         * - is_deleted = 0
         */

        if (isset($_POST['master_task_status']) && ($_POST['master_task_status'] == 'is_completed')) {
            $is_completed = 1;
            $in_progress = $is_canceled = $is_deleted = 0;
        }

        /**
         * flags
         * is_canceled
         * - in_progress = 0
         * - is_completed = 0
         * - is_canceled = 1
         * - is_deleted = 0
         */

        if (isset($_POST['master_task_status']) && ($_POST['master_task_status'] == 'is_canceled')) {
            $is_canceled = 1;
            $in_progress = $is_completed = $is_deleted = 0;
        }

        /**
         * flags
         * is_deleted
         * - in_progress = 0
         * - is_completed = 0
         * - is_canceled = 0
         * - is_deleted = 1
         */

        if (isset($_POST['master_task_status']) && ($_POST['master_task_status'] == 'is_deleted')) {
            $is_deleted = 1;
            $in_progress = $is_completed = $is_canceled = 0;
        }

        /**
         * init values, reseted
         */

        $task_datetime_start = $task_datetime_end = $total_datetime_spent = '0000-00-00 00:00:00';

        /**
         * 'in_progress' is marked,
         * start date will be 'now',
         * old values will be overwritten
         */

        if ($in_progress) {
            $task_datetime_start = date('Y-m-d H:i:s', time());
        }

        /**
         * 'is_completed' is marked,
         * end date will be 'now'
         */

        if ($is_completed) {
            if ($task['task_datetime_end'] == '0000-00-00 00:00:00' && $task['task_datetime_start'] != '0000-00-00 00:00:00') {

                $task_datetime_start = $task['task_datetime_start'];
                $task_datetime_end = date('Y-m-d H:i:s', time());

                /**
                 * by this time, we can
                 * calculate the total datetime spent
                 */

                $total_datetime_spent = total_datetime_spent($task_datetime_start, $task_datetime_end);
            } else {
                /**
                 * preserve old values from DB
                 */

                $task_datetime_start = $task['task_datetime_start'];
                $task_datetime_end = $task['task_datetime_end'];
                $total_datetime_spent = $task['total_datetime_spent'];
            }
        }

        /**
         * 'is_canceled' is marked,
         * restore 'task_datetime_start' & 'task_datetime_end'
         */

        if ($is_canceled) {

            /**
             * preserve old values from DB
             */

            $task_datetime_start = $task['task_datetime_start'];
            $task_datetime_end = $task['task_datetime_end'];
            $total_datetime_spent = $task['total_datetime_spent'];
        }

        $task = [
            'task_datetime_start' => $task_datetime_start,
            'task_datetime_end' => $task_datetime_end,
            'total_datetime_spent' => $total_datetime_spent,
            'in_progress' => $in_progress,
            'is_completed' => $is_completed,
            'is_canceled' => $is_canceled,
            'is_deleted' => $is_deleted
        ];

        db_update('task', $task, ['task_id' => $_GET['task_id']]);
        mysqli_commit($mysqli);
    } catch (Exception $e) {
        mysqli_rollback($mysqli);
        $_SESSION['error'][] = $e->getMessage();
    }

    /**
     * return json status
     */

    $default_msg = [sprintf(lang('succ_updated'), lang('task'))];

    $return = ['err' => false, 'msg' => $default_msg];

    if ($_SESSION['error']) {
        $return = [
            'err' => true,
            'msg' => $_SESSION['error']
        ];
    }

    unset($_SESSION['error']);
    $json = json_encode($return);
    header('Content-Type: application/json; charset=utf-8');
    exit($json);
}

/**
 * task
 */

$task = load_model('admin/task')->task((int) $_GET['task_id'], $_SESSION['user']['user_id'])->row;


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
    'href' => u('/admin/task'),
    'is_active' => false,
];

$breadcrumbs[] = [
    'text' => lang('edit_task'),
    'href' => '',
    'is_active' => true,
];

$links = [
    u(TEMPLATE . '/css/components/datepicker.css'),
    u(TEMPLATE . '/css/components/timepicker.css')
];

$scripts = [
    u(TEMPLATE . '/js/components/moment.js'),
    u(TEMPLATE . '/js/components/timepicker.js'),
    u(TEMPLATE . '/js/components/datepicker.js'),
    u(TEMPLATE . '/js/admin/task/edit.js'),
];

$data = [
    'header' => tpl('header.tpl', ['title' => lang('my_task'), 'root' => $root, 'active' => $active], false, [], $links),
    'footer' => tpl('footer.tpl', [], false, $scripts),
    'breadcrumbs' => $breadcrumbs,
    'task' => $task
];

tpl('admin/task/edit.tpl', $data, true);
