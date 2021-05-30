<?php

class Admin_Content
{
    public function total_contents($user_id = 0)
    {
        $sql = "SELECT DISTINCT
			COUNT(*) As total_rows
        FROM content c
        WHERE 1=1
            AND c.is_deleted = 0
            AND c.created_by = %d
        ";

        $sql = sprintf($sql, (int) $user_id);

        $res = db_query($sql);

        return $res;
    }

    public function contents($user_id = 0, $page = 1, $limit = PAGE_LIMIT)
    {
        $sql = "SELECT DISTINCT
            *
        FROM content c
        WHERE 1=1

            AND c.is_deleted = 0
            AND c.created_by = %d

        ORDER BY c.created_at DESC

		LIMIT " . (($page - 1) * $limit) . ", " . $limit;

        $sql = sprintf($sql, (int) $user_id);

        $res = db_query($sql);

        $res->total_rows = $this->total_contents($user_id)->row['total_rows'];

        return $res;
    }
}
