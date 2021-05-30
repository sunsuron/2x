<?php

$acl = [
    'guest' => 1,
    'icp' => 2, // in-complete profile
    'admin' => 3,
    'superadmin' => 4,
    'marketing' => 5,
    'technical' => 6,
    'audit' => 7,
    'client' => 8
];

$aclaccess['guest'] = $aclaccess['icp'] = [
    '404',
    'permission',
    'home',
    'login',
    'logout',
    'register',
    'about',
    'contact',
    'profile',
    'profile/delete-image',
    'forgot-password',
    'reset-password',
    'learn-more',
    'jumbotron',
    'fix',
    'js/config',
    'cron/tt',
    'cron/tt-trend',
    'cron/yt',
];

$aclaccess['admin'] = [
    'admin',
    'admin/task',
    'admin/task/new',
    'admin/task/edit',
    'admin/task/delete',
    'admin/task/view',
    'cron/add-random-task',
    'cron/scan-completed-task-weekly'
];

$aclaccess['superadmin'] = [
    'superadmin',
    'superadmin/member',
    'superadmin/member/edit',
    'superadmin/member/delete',
    'superadmin/member/delete-image',
    'superadmin/member/login',
    'superadmin/member/search',
    'superadmin/jumbotron',
    'superadmin/jumbotron/delete',
    'task',
    'content',
    'payment',
];

$aclaccess['marketing'] = [
    'admin/client',
    'admin/client/add',
    'admin/client/edit',
    'admin/client/delete',
];
