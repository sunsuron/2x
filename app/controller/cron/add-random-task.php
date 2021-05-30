<?php

/**
 * helper file to insert a random task data
 * usage: php -f add-random-task.php
 */

/**
 * no timeout
 * don't interrupt
 * wait until script finishes executing
 */

set_time_limit(0);

/**
 * boot cron
 */

require sprintf('%s%s%s', dirname(dirname(__FILE__)), DIRECTORY_SEPARATOR, 'boot-cron.php');

/**
 * init db
 */

$mysqli = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
mysqli_query($mysqli, "SET NAMES 'utf8mb4'");
mysqli_query($mysqli, "SET CHARACTER SET utf8mb4");
mysqli_query($mysqli, "SET CHARACTER_SET_CONNECTION=utf8mb4");
mysqli_query($mysqli, "SET SQL_MODE = ''");

/**
 * randomize lorem
 */

$lorem = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In rutrum porta enim a dapibus. Fusce eget augue mollis, ultricies eros pellentesque, dictum magna. Integer aliquam purus non vehicula lacinia. Sed sodales aliquet sapien mattis facilisis. Vivamus et velit vel dolor lacinia pharetra. Nullam quis ante porttitor, interdum mauris sit amet, sagittis felis. Etiam faucibus diam ac ex porttitor ultrices. Sed venenatis ligula ac venenatis semper. Sed tincidunt arcu tincidunt, tincidunt leo eget, euismod mauris. Etiam in tellus tortor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Sed euismod nisi sodales purus feugiat porta. Suspendisse tincidunt tellus at pulvinar pulvinar. Curabitur ac pharetra risus. Praesent lorem purus, gravida sed arcu ac, consequat sodales nisl. Aenean elementum aliquet diam in dignissim.';
$task_desc = explode(' ', $lorem);
shuffle($task_desc);
$lorem = implode(' ', $task_desc);

/**
 * data
 */

$data = [
    'created_by' => 4,
    'assigned_to' => 1,
    'task_desc' => $lorem,
    'task_datetime_start' => '',
    'task_datetime_end' => '',
    'total_datetime_spent' => '',
    'is_deleted' => 0,
    'in_progress' => 0,
    'is_completed' => 1,
    'is_canceled' => 0,
    'is_approved' => 0,
    'is_active' => 1
];

/**
 * insert data for March & April
 * March 1 - 31
 * April 1 - 30
 */

$year = 2021;
$months = [3, 4];

foreach ($months as $month) {

    if (!checkdate($month, 1, $year)) {
        $now = getdate();
        $month = $now['mon'];
        $year = $now['year'];
    } else {
        $month = $month;
        $year = $year;
    }

    $start = mktime(12, 0, 0, $month, 1, $year);
    $first_day = getdate($start);

    for ($count = 0; $count < (6 * 7); $count++) {
        $day = getdate($start);

        if (($count % 7) == 0) {
            if ($day['mon'] != $month) {
                break;
            }
        }

        if ($count > $first_day['wday'] && $day['mon'] == $month) {

            /**
             * - generate number of task between 1 task - 10 tasks a day
             */

            $task_per_day = rand(1, 10);

            /**
             * - generate task that completed between 8 hours - 13 hours a day
             */

            $total_datetime_spent = rand(8, 13);

            /**
             * hours counter
             */

            $total_hours = 0;

            /**
             * a_task_per_hour
             */

            $a_task_per_hour = ceil($total_datetime_spent / $task_per_day);
            if ($a_task_per_hour == 0) {
                $a_task_per_hour = 1;
            }

            /**
             * start working at 09:00:00 am
             */

            $task_datetime_start = date('Y-m-d H:i:s', mktime(9, 0, 0, $month, $day['mday'], $year));

            /**
             * insert task to DB
             */

            foreach (range(1, $task_per_day) as $number_of_task) {

                if ($total_hours > $total_datetime_spent)
                    break;

                /**
                 * assign task_datetime_end
                 */

                $datetime_start = new DateTime($task_datetime_start);
                $datetime_start->add(new DateInterval(sprintf('PT%dH', $a_task_per_hour)));
                $task_datetime_end = $datetime_start->format('Y-m-d H:i:s');

                /**
                 * update $data
                 */

                $data['task_datetime_start'] = $task_datetime_start;
                $data['task_datetime_end'] = $task_datetime_end;
                $data['total_datetime_spent'] = date('H:i:s', mktime($total_datetime_spent, 0, 0, 0, 0, 0));

                shuffle($task_desc);
                $lorem = implode(' ', $task_desc);
                $data['task_desc'] = $lorem;

                /**
                 * insert DB
                 */

                db_insert('task', $data);

                /**
                 * re-assign
                 * increment
                 */

                $task_datetime_start = $task_datetime_end;
                $total_hours += $a_task_per_hour;
            }

            $start += (60 * 60 * 24);
        }
    }
}

/**
 * close db connection
 */

mysqli_close($mysqli);
