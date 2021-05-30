<?php

if (is_logged()) {
    redirect(u('/admin'));
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
        }
    }

    if (!isset($_POST['password']) || !$_POST['password']) {
        $_SESSION['error'][] = sprintf(lang('err_required'), lang('password'));
    }

    if (!$_SESSION['error']) {
        if (!login_email($_POST['email'], $_POST['password'])) {
            $_SESSION['error'][] = sprintf(lang('err_login'), $_POST['email']);
        }
    }

    if ($_SESSION['error']) {
        return false;
    }

    return true;
}

if (isset($_POST['login'])) {

    $redirect_url = '';

    if (goodpost()) {
        if (isset($_SESSION['user']['user_id']) && $_SESSION['user']['user_id'] && in_array($acl['icp'], $_SESSION['acl'])) {
            $_SESSION['error'][] = sprintf(lang('help_profile'));
            $redirect_url = u('/profile');
        } else {
            $redirect_url = u('/admin');
        }
    }

    /**
     * return json status
     */

    $default_msg = [];

    $return = ['err' => false, 'msg' => $default_msg, 'redirect_url' => $redirect_url];

    if ($_SESSION['error']) {
        $return = [
            'err' => true,
            'msg' => $_SESSION['error'],
            'redirect_url' => '',
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

$root = $active = '';

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
    'text' => lang('login'),
    'href' => '',
    'is_active' => true,
];

/**
 * script
 */

$scripts = [u(TEMPLATE . '/js/wazap-login.js')];

$data = [
    'header' => tpl('header.tpl', ['title' => lang('login'), 'root' => 'login', 'active' => $active]),
    'footer' => tpl('footer.tpl', [], false, $scripts),
    'breadcrumbs' => $breadcrumbs,
];

tpl('login.tpl', $data, true);
