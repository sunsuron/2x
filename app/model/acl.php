<?php
class Acl
{
	public function get($user_id = 0, $acl_id = 0)
	{
		$res = null;
		
		if ($acl_id)
		{
			$sql = "SELECT
					
					ua.*,
					u.is_superadmin
				
				FROM user_acl ua,
				LEFT JOIN user u ON (u.user_id = ua.user_id)
				WHERE 1=1
					
					AND ua.user_id = %d
					AND ua.acl_id  = %d
			";
			
			$sql = sprintf($sql, (int)$user_id, (int)$acl_id);
			
			$res = db_query($sql);
		}
		else
		{
			$sql = "SELECT
					
					ua.*,
					u.is_superadmin
				
				FROM user_acl ua
				LEFT JOIN user u ON (u.user_id = ua.user_id)
				WHERE 1=1
					
					AND ua.user_id  = %d
			
			";
				
			$sql = sprintf($sql, (int)$user_id);
				
			$res = db_query($sql);
		}
		
		return $res;
	}
}




