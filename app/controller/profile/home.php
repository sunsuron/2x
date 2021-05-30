<?php

function goodpost()
{
    global $acl;

    clean();

    $_SESSION['error'] = [];

    if (!isset($_POST['mobile_no']) || !$_POST['mobile_no']) {
        $_SESSION['error'][] = sprintf(lang('err_required'), lang('mobile_no'));
    } else {
        if (!preg_match('/^[0-9]{7,}$/', $_POST['mobile_no'])) {
            $_SESSION['error'][] = sprintf(lang('err_format'), lang('mobile_no'));
        } else {
            $res = db_exists('user_attr', ['mobile_no' => $_POST['mobile_no']]);
            if ($res->num_rows) {
                if ($res->row['user_id'] != $_SESSION['user']['user_id']) {
                    $msg = sprintf('The %s: <strong>%s</strong>', lang('mobile_no'), $_POST['mobile_no']);
                    $_SESSION['error'][] = sprintf(lang('err_exists'), $msg);
                }
            }
        }
    }

    if (!isset($_POST['password']) || !$_POST['password']) {
        if (in_array($acl['icp'], $_SESSION['acl'])) {
            $_SESSION['error'][] = sprintf(lang('err_required'), lang('password'));
        }
    }

    if (!isset($_POST['fullname']) || !$_POST['fullname']) {
        $_SESSION['error'][] = sprintf(lang('err_required'), lang('fullname'));
    }

    if ($_SESSION['error']) {
        return false;
    }

    return true;
}

if (isset($_POST['profile'])) {

    if (goodpost()) {

        mysqli_autocommit($mysqli, false);

        try {
            $time = time();

            $user_id = $_SESSION['user']['user_id'];

            if (isset($_POST['password']) && $_POST['password']) {

                $hashedcrypt = generate_hash($_POST['password']);

                /**
                 * new password
                 */

                db_update('user', ['password' => $hashedcrypt], ['user_id' => $user_id]);
            }

            /**
             * user_attr
             */

            $user_attr = [
                'mobile_no' => $_POST['mobile_no'],
                'fullname' => $_POST['fullname']
            ];

            db_update('user_attr', $user_attr, ['user_id' => $user_id]);

            /**
             * update $_SESSION['user']
             */

            $_SESSION['user']['mobile_no'] = $_POST['mobile_no'];

            /**
             * acl
             * - delete 'icp'
             * - insert 'admin'
             */

            $where = [
                'user_id' => $user_id,
                'acl_id' => $acl['icp'],
            ];

            db_delete('user_acl', $where);

            /**
             * add admin acl_id, i.e admin == member
             */

            $isadmin = db_exists('user_acl', ['user_id' => $user_id, 'acl_id' => $acl['admin']]);

            if (!$isadmin->num_rows) {
                $user_acl = [
                    'user_id' => $user_id,
                    'acl_id' => $acl['admin'],
                ];

                db_insert('user_acl', $user_acl);
            }

            acl_update(true);

            /**
             * commit DB
             */

            mysqli_commit($mysqli);

            /**
             * success
             */

            $_SESSION['success'][] = sprintf(lang('succ_updated'), lang('profile'));
        } catch (Exception $e) {
            mysqli_rollback($mysqli);
            $_SESSION['error'][] = $e->getMessage();
        }
    }

    /**
     * return json status
     */

    $default_msg = [sprintf(lang('succ_updated'), lang('profile'))];

    $return = ['err' => false, 'msg' => $default_msg];

    if ($_SESSION['error']) {
        $return = [
            'err' => true,
            'msg' => $_SESSION['error'],
        ];
    }

    unset($_SESSION['error']);
    $json = json_encode($return);
    header('Content-Type: application/json; charset=utf-8');
    exit($json);
}

/**
 * $user
 */

$user = load_model('superadmin/user')->user($_SESSION['user']['user_id'])->row;

/**
 * active page
 */

$root = $active = '';

if (strpos($_GET['route'], '/') !== false) {
    list($root, $active) = explode('/', $_GET['route']);
}

if (in_array($acl['icp'], $_SESSION['acl'])) {
    $active = $root = 'profile';
} else {
    $root = 'admin';
    $active = 'profile';
}

/**
 * breadcrumbs
 */

$breadcrumbs[] = [
    'text' => lang('dashboard'),
    'href' => u('admin'),
    'is_active' => false,
];

$breadcrumbs[] = [
    'text' => lang('profile'),
    'href' => '',
    'is_active' => true,
];

$scripts = [
    u(TEMPLATE . '/js/profile/home.js'),
];

$data = [
    'header' => tpl('header.tpl', ['title' => lang('profile'), 'root' => $root, 'active' => $active]),
    'footer' => tpl('footer.tpl', [], false, $scripts),
    'breadcrumbs' => $breadcrumbs,
    'user' => $user
];

tpl('profile/home.tpl', $data, true);
