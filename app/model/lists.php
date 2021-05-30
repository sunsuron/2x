<?php

class lists
{
    public function total_get($table = '', $page = 1, $limit = PAGE_LIMIT)
    {
        $sql = "SELECT

			COUNT(*) As total_rows

		FROM list_%s WHERE 1=1";

        $sql = sprintf($sql, $table);

        $res = db_query($sql);

        return $res;
    }

    public function get($table = '', $page = 1, $limit = PAGE_LIMIT, $order_by = 'ORDER BY name')
    {
        $sql = "SELECT

			name

		FROM list_%s WHERE 1=1
		{$order_by}
        LIMIT " . (($page - 1) * $limit) . ", " . $limit;

        $sql = sprintf($sql, $table);

        $res = db_query($sql);

        $res->total_rows = $this->total_get($table)->row['total_rows'];

        return $res;
    }
}
