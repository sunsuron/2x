<?php

/**
 * redirect if already registrered & logged-in
 */

if (is_logged()) {
    redirect(u('/admin'));
}

function goodpost()
{
    clean();

    $_SESSION['error'] = array();

    if (!isset($_POST['email']) || !$_POST['email']) {
        $_SESSION['error'][] = sprintf(lang('err_required'), lang('email'));
    } else {
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'][] = sprintf(lang('err_format'), lang('email'));
        } else {
            $res = db_exists('user', ['email' => $_POST['email'], 'is_active' => 1]);

            if (!$res->num_rows) {
                $_SESSION['error'][] = sprintf(lang('err_nonexistence'), $_POST['email']);
            }
        }
    }

    if ($_SESSION['error']) {
        return false;
    }

    return true;
}

if (isset($_POST['fpasswd'])) {

    if (goodpost()) {

        mysqli_autocommit($mysqli, false);

        try
        {
            $user_id = db_exists('user', ['email' => $_POST['email'], 'is_active' => 1])->row['user_id'];

            $user = load_model('superadmin/user')->user($user_id)->row;

            $time = time();

            $email_vsalt = md5(random_bytes(16));

            $fp_vcode = md5($user_id . $_POST['email'] . $email_vsalt);

            $forgot_password = [
                'user_id' => $user_id,
                'email' => $_POST['email'],
                'email_vsalt' => $email_vsalt,
                'fp_vcode' => $fp_vcode,
                'created_at' => date('Y-m-d H:i:s', $time),
                'updated_at' => date('Y-m-d H:i:s', $time),
            ];

            db_insert('forgot_password', $forgot_password);

            /**
             * email
             */

            $replacements = [
                'email' => $_POST['email'],
                'verifyurl' => sprintf(URL . "reset-password?vcode=%s", $fp_vcode),
            ];

            $emaildata = [
                'tpl' => 'forgot-password.tpl',
                'to' => $_POST['email'],
                'subject' => lang('es_fpass_verification'),
                'replacements' => $replacements,
            ];

            firemail($emaildata);

            /**
             * commit db
             */

            mysqli_commit($mysqli);

        } catch (Exception $e) {
            mysqli_rollback($mysqli);
            $_SESSION['error'][] = $e->getMessage();
        }
    }

    /**
     * return json status
     */

    $default_msg = [sprintf(lang('succ_fpasswd'), $_POST['email'], SMTP_FROM)];

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
    'text' => lang('forgot_password'),
    'href' => '',
    'is_active' => true,
];

$scripts = [u(TEMPLATE . '/js/wazap-forgot-password.js')];

$data = [
    'header' => tpl('header.tpl', ['title' => lang('forgot_password'), 'root' => 'forgot-password', 'active' => 'forgot-password']),
    'footer' => tpl('footer.tpl', [], false, $scripts),
    'breadcrumbs' => $breadcrumbs,
];

tpl('forgot-password.tpl', $data, true);
