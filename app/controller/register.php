<?php

/**
 * redirect if already registrered & logged-in
 */

if (is_logged()) {
    redirect(u('/admin'));
}

/**
 * handle new registration with existing $_GET['vcode']
 */

function goodvcode()
{
    clean();

    $_SESSION['error'] = [];

    $email = db_exists('register_vlink', ['reg_vcode' => $_GET['vcode']]);

    if (!$email->num_rows) {
        $_SESSION['error'][] = sprintf(lang('err_invalid'), lang('verification_code'));
    } else {
        $user = db_exists('user', ['email' => $email->row['email'], 'is_active' => 1]);
        if ($user->num_rows) {
            $_SESSION['error'][] = sprintf(lang('err_exists'), $email->row['email']);
        }
    }

    if ($_SESSION['error']) {
        return false;
    }

    return true;
}

if (isset($_GET['vcode']) && $_GET['vcode']) {

    /**
     * auto-register internally - get internal $user_id
     * redirect to /admin - incomplete profile
     */

    if (goodvcode()) {

        mysqli_autocommit($mysqli, false);

        try
        {
            $user_id = 0;

            $time = time();

            $hashedcrypt = generate_hash(random_bytes(16));

            /**
             * user
             */

            $email = db_exists('register_vlink', ['reg_vcode' => $_GET['vcode']]);

            $user = [
                'email' => $email->row['email'],
                'password' => $hashedcrypt,
                'is_superadmin' => 0,
                'ip' => $_SERVER['REMOTE_ADDR'],
                'email_verified' => 1,
                'is_active' => 1
            ];

            db_insert('user', $user);

            $user_id = mysqli_insert_id($mysqli);

            /**
             * user_attr
             */

            $user_attr = [
                'user_id' => $user_id,
                'usertitle' => 0,
                'fullname' => '',
                'mobile_no' => ''
            ];

            db_insert('user_attr', $user_attr);

            /**
             * acl
             */

            $user_acl = [
                'user_id' => $user_id,
                'acl_id' => $acl['guest'],
            ];

            db_insert('user_acl', $user_acl);

            $user_acl = [
                'user_id' => $user_id,
                'acl_id' => $acl['icp'],
            ];

            db_insert('user_acl', $user_acl);

            /**
             * commit db
             */

            mysqli_commit($mysqli);

            /**
             * - auto-login
             * - redirect to profile
             */

            if (X($user_id)) {
                $_SESSION['error'][] = sprintf(lang('help_profile'));
                redirect(u('/profile'));
            }

        } catch (Exception $e) {
            mysqli_rollback($mysqli);
            $_SESSION['error'][] = $e->getMessage();
        }
    }
}

function goodpost()
{
    clean();

    $_SESSION['error'] = [];

    if (!isset($_POST['email']) || !$_POST['email']) {
        $_SESSION['error'][] = sprintf(lang('err_required'), lang('email'));
    } else {
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'][] = sprintf(lang('err_format'), lang('email'));
        } else {
            $res = db_exists('user', ['email' => $_POST['email'], 'is_active' => 1]);

            if ($res->num_rows) {
                $_SESSION['error'][] = sprintf(lang('err_exists'), $_POST['email']);
            } else {
                $_POST['email'] = strtolower($_POST['email']);
            }
        }
    }

    if ($_SESSION['error']) {
        return false;
    }

    return true;
}

/**
 * handle register_vlink
 * this is the first step to register by sending verification link
 */

if (isset($_POST['register'])) {

    if (goodpost()) {

        mysqli_autocommit($mysqli, false);

        try
        {
            $time = time();

            $email_vsalt = md5(random_bytes(16));

            $reg_vcode = md5($_POST['email'] . $email_vsalt);

            $register_vlink = [
                'email' => $_POST['email'],
                'email_vsalt' => $email_vsalt,
                'reg_vcode' => $reg_vcode
            ];

            /**
             * check email existance
             */

            $email = db_exists('register_vlink', ['email' => $_POST['email']]);

            /**
             * insert if the email does not exists
             * get existing data from DB if the email is already exists
             */

            if (!$email->num_rows) {
                db_insert('register_vlink', $register_vlink);
            } else {
                /**
                 * overwrite generated $reg_vcode above with the one from the DB
                 */
                $reg_vcode = $email->row['reg_vcode'];
            }

            /**
             * email
             */

            $replacements = [
                'fullname' => $_POST['email'],
                'verifyurl' => sprintf(URL . "register?vcode=%s", $reg_vcode),
            ];

            $emaildata = [
                'tpl' => 'register-vlink.tpl',
                'to' => $_POST['email'],
                'subject' => lang('es_register_vlink'),
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

    $default_msg = [sprintf(lang('register_confirm_vlink_email'), $_POST['email'])];

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
} else {
    $active = 'home';
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
    'text' => lang('register'),
    'href' => '',
    'is_active' => true,
];

$scripts = [u(TEMPLATE . '/js/wazap-register.js')];

$data = [
    'header' => tpl('header.tpl', ['title' => lang('register'), 'root' => 'register', 'active' => $active]),
    'footer' => tpl('footer.tpl', [], false, $scripts),
    'breadcrumbs' => $breadcrumbs
];

tpl('register.tpl', $data, true);
