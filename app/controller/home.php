<?php

$data = [
    'header' => tpl('header.tpl', ['title' => lang('home'), 'root' => 'home', 'active' => 'home']),
    'footer' => tpl('footer.tpl')
];

tpl('home.tpl', $data, true);

