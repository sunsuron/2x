<?php
class Superadmin_User
{
	/**
	 * get all active members
	 */

	function total_users($where = '')
	{
		$sql = "SELECT DISTINCT
				
				COUNT(*) As total_rows
			
			FROM user u
			LEFT JOIN user_attr ua ON u.user_id = ua.user_id
			LEFT JOIN user_image ui ON ui.user_id = u.user_id
			WHERE 1=1
				
				AND u.is_active = 1
				
				{$where}
		";

		$res = db_query($sql);

		return $res;
	}

	function users($kv = [], $page = 1, $limit = PAGE_LIMIT)
	{
		$where = '';

		if (isset($kv['fullname']) && $kv['fullname']) {
			$kv['fullname'] = db_escape($kv['fullname']);

			$where .= " AND ua.fullname LIKE '%" . $kv['fullname'] . "%'";
		}

		if (isset($kv['email']) && $kv['email']) {
			$kv['email'] = db_escape($kv['email']);

			$where .= " AND u.email LIKE '%" . $kv['email'] . "%'";
		}

		if (isset($kv['mobile_no']) && $kv['mobile_no']) {
			$kv['mobile_no'] = db_escape($kv['mobile_no']);

			$where .= " AND ua.mobile_no LIKE '%" . $kv['mobile_no'] . "%'";
		}

		$sql = "SELECT DISTINCT
				
				u.*,
                ua.*,
                ui.*
			
			FROM user u
			LEFT JOIN user_attr ua ON ua.user_id = u.user_id
			LEFT JOIN user_image ui ON ui.user_id = u.user_id
			WHERE 1=1
				
				AND u.is_active = 1
				
				{$where}
				
			ORDER BY u.user_id DESC
			
			LIMIT " . (($page - 1) * $limit) . ", " . $limit;

		$res = db_query($sql);

		if ($res->num_rows) {
			foreach ($res->rows as $k => $v) {
				$res->rows[$k]['dimension'] = unserialize($v['dimension']);
			}

			$res->row['dimension'] = unserialize($res->row['dimension']);
		}

		$res->total_rows = $this->total_users($where)->row['total_rows'];

		return $res;
	}

	function user($user_id = 0)
	{
		$sql = "SELECT
				
				u.*,
                ua.*,
                ui.*
				
			FROM user u
			LEFT JOIN user_attr ua ON ua.user_id = u.user_id
			LEFT JOIN user_image ui ON ui.user_id = u.user_id
			WHERE 1=1
				
				AND u.is_active = 1
				AND u.user_id = %d
			
			LIMIT 1
		";

		$sql = sprintf($sql, (int)$user_id);

		$res = db_query($sql);

		if ($res->num_rows) {
			foreach ($res->rows as $k => $v) {
				$res->rows[$k]['dimension'] = unserialize($v['dimension']);
			}

			$res->row['dimension'] = unserialize($res->row['dimension']);
		}

		return $res;
	}

	function total_guides()
	{
		$sql = "SELECT
		
				COUNT(*) As total_rows
		
			FROM user u
			LEFT JOIN user_attr ua ON ua.user_id = u.user_id
			LEFT JOIN user_image ui ON ui.user_id = u.user_id
			WHERE 1=1
		
				AND u.is_active = 1
				AND ua.is_premium = 1
		";

		$res = db_query($sql);

		return $res;
	}

	function image($user_image_id = 0, $user_id = 0)
	{
		$sql = "SELECT * FROM user_image WHERE 1=1 AND user_image_id = %d AND user_id = %d";

		$sql = sprintf($sql, $user_image_id, $user_id);

		$res = db_query($sql);

		if ($res->num_rows) {
			foreach ($res->rows as $k => $v) {
				$res->rows[$k]['dimension'] = unserialize($v['dimension']);
			}

			$res->row['dimension'] = unserialize($res->row['dimension']);
		}

		return $res;
	}
}
