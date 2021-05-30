<?php

class Admin_Task
{
    public function total_tasks($user_id = 0)
    {
        $sql = "SELECT DISTINCT
			COUNT(*) As total_rows
        FROM task t
        WHERE 1=1
            AND t.is_deleted = 0
            AND t.assigned_to = %d
        ";

        $sql = sprintf($sql, (int) $user_id);

        $res = db_query($sql);

        return $res;
    }

    public function tasks($user_id = 0, $page = 1, $limit = PAGE_LIMIT)
    {
        $sql = "SELECT DISTINCT
            *
        FROM task t
        WHERE 1=1

            AND t.is_deleted = 0
            AND t.assigned_to = %d

        ORDER BY t.task_datetime_end DESC

		LIMIT " . (($page - 1) * $limit) . ", " . $limit;

        $sql = sprintf($sql, (int) $user_id);

        $res = db_query($sql);

        $res->total_rows = $this->total_tasks($user_id)->row['total_rows'];

        return $res;
    }

    public function task($task_id = 0, $user_id = 0)
    {
        $sql = "SELECT DISTINCT
            *
        FROM task t
        WHERE 1=1

            AND t.task_id = %d
            AND t.is_deleted = 0
            AND t.assigned_to = %d
        ";

        $sql = sprintf($sql, (int) $task_id, (int) $user_id);

        $res = db_query($sql);

        return $res;
    }
}
