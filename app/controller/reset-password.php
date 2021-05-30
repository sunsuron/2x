<?php

/**
 * redirect if already registrered & logged-in
 */

if (is_logged()) {
    redirect(u('/admin'));
}

/**
 * verify vcode
 */

if (!goodget()) {
    redirect(u('/'));
}

global $reset;

function goodget()
{
    global $reset;

    clean();

    $_SESSION['error'] = [];

    if (!isset($_REQUEST['vcode']) || !$_REQUEST['vcode']) {
        $_SESSION['error'][] = sprintf(lang('err_required'), lang('verification_code'));
    } else {
        if (!preg_match('/^[a-z0-9]+$/i', $_REQUEST['vcode'])) {
            $_SESSION['error'][] = sprintf(lang('err_format'), lang('verification_code'));
        } else {
            $res = $reset = $cache = db_exists('forgot_password', ['fp_vcode' => db_escape($_REQUEST['vcode'])]);

            if (!$res->num_rows) {
                $_SESSION['error'][] = sprintf(lang('err_nonexistence'), lang('verification_code'));
            } else {
                $res = db_get('reset_password', sprintf("reset_success != 0 AND forgot_password_id = %d", (int) $res->row['forgot_password_id']));

                if ($res->num_rows) {
                    $_SESSION['error'][] = sprintf(lang('err_verified'), lang('verification_code'));
                } else {
                    /**
                     * check user_id + email + salt
                     */

                    if (md5($cache->row['user_id'] . $cache->row['email'] . $cache->row['email_vsalt']) != $_REQUEST['vcode']) {
                        $_SESSION['error'][] = sprintf(lang('err_invalid'), lang('verification_code'));
                    }
                }
            }
        }
    }

    if ($_SESSION['error']) {
        return false;
    }

    return true;
}

function goodpost()
{
    clean();

    $_SESSION['error'] = [];

    $p1okay = $p2okay = false;

    if (!isset($_POST['password']) || !$_POST['password']) {
        $_SESSION['error'][] = sprintf(lang('err_required'), lang('password'));
    } else {
        $p1okay = true;
    }

    if (!isset($_POST['password_again']) || !$_POST['password_again']) {
        $_SESSION['error'][] = sprintf(lang('err_required'), lang('password_again'));
    } else {
        $p2okay = true;
    }

    if ($p1okay && $p2okay) {
        if ($_POST['password'] != $_POST['password_again']) {
            $_SESSION['error'][] = sprintf(lang('err_mismatched'), lang('password'));
        }
    }

    if ($_SESSION['error']) {
        return false;
    }

    return true;
}

if (isset($_POST['rpasswd'])) {

    if (goodpost()) {

        mysqli_autocommit($mysqli, false);

        try
        {
            $time = time();

            $hashedcrypt = hasher($_POST['password']);

            /**
             * user
             */

            $user = [
                'password' => $hashedcrypt,
                'updated_at' => date('Y-m-d H:i:s', $time),
            ];

            db_update('user', $user, ['user_id' => $reset->row['user_id']]);

            /**
             * reset_password
             */

            $reset_password = [
                'forgot_password_id' => $reset->row['forgot_password_id'],
                'reset_success' => 1,
                'created_at' => date('Y-m-d H:i:s', $time),
                'updated_at' => date('Y-m-d H:i:s', $time),
            ];

            db_insert('reset_password', $reset_password);

            /**
             * commit DB
             */

            mysqli_commit($mysqli);

            /**
             * success
             * - autologin
             */

            X($reset->row['user_id']);

            /**
             * email
             */

            $user = load_model('superadmin/user')->user($reset->row['user_id'])->row;

            $emaildata = [
                'tpl' => 'reset-password.tpl',
                'to' => $_SESSION['user']['email'],
                'subject' => lang('es_rpass_confirmation'),
                'replacements' => ['email' => $_SESSION['user']['email']],
            ];

            firemail($emaildata);

        } catch (Exception $e) {
            mysqli_rollback($mysqli);
            $_SESSION['error'][] = $e->getMessage();
        }
    }

    /**
     * return json status
     */

    $default_msg = [sprintf(lang('succ_updated'), lang('password'))];

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
 * active page
 */

if (strpos($_GET['route'], '/') !== false) {
    list($root, $active) = explode('/', $_GET['route']);
}

/**
 * breadcrumbs
 */

$breadcrumbs[] = [
    'text' => lang('home'),
    'href' => u('/'),
    'is_active' => false,
];

$breadcrumbs[] = [
    'text' => lang('reset_password'),
    'href' => '',
    'is_active' => true,
];

$scripts = [u(TEMPLATE . '/js/wazap-reset-password.js')];

$data = [
    'header' => tpl('header.tpl', ['title' => lang('reset_password'), 'root' => 'reset-password', 'active' => 'reset-password']),
    'footer' => tpl('footer.tpl', [], false, $scripts),
    'breadcrumbs' => $breadcrumbs,
];

tpl('reset-password.tpl', $data, true);
