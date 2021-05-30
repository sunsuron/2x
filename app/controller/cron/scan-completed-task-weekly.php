<?php

/**
 * helper file to scan the total amount of time spent in the previous 7 days
 * Execute file every monday at 00:00
 * Crontab Syntax: 0 0 * * 1 "At 00:00 on Monday."
 * usage: php -f scan-completed-task-weekly.php
 */

/**
 * no timeout
 * don't interrupt
 * wait until script finishes executing
 */

set_time_limit(0);

/**
 * today
 * example:
 * On 2021-04-05 (Monday @ 00:00 am)
 * the linux crontab executed this script
 */

$today_uxt = mktime(0, 0, 0, 4, 5, 2021);
$lastweek_uxt = strtotime('-7 days', $today_uxt);

$today = date('Y-m-d', $today_uxt);
$lastweek = date('Y-m-d', strtotime('-7 days', $today_uxt));

/**
 * get the total time spent from $lastweek to $yesterday
 */

$sql = "SELECT

    SEC_TO_TIME(SUM(UNIX_TIMESTAMP(task_datetime_end) - UNIX_TIMESTAMP(task_datetime_start))) AS sum_time

FROM task
WHERE
    task_datetime_start >= '%s' AND task_datetime_end <= '%s'
    AND is_completed = 1
    AND is_approved = 0
";

$sql = sprintf($sql, $lastweek, $today);

$res = db_query($sql);

if (!$res->num_rows) {
    return;
}

if ($res->row['sum_time'] >= 40) {

    /**
     * the SUM of all the hours between $lastweek <-> $today is greater than 40 hours 
     * send email
     */

    echo print_r([
        'today' => $today,
        'lastweek' => $lastweek,
        'sum_time' => $res->row['sum_time'],
        'send_email' => true
    ], true);
}
