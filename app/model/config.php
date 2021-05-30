<?php
class Config
{
	function configs()
	{
		$sql = "SELECT * FROM config";
		
		$res = db_query($sql);
		
		$ret = [];
		
		if ($res->num_rows)
		{
			foreach ($res->rows as $k => $v)
			{
				if ($k == 0) $res->row[$v['ckey']] = $v['cval'];
				
				$ret[$v['ckey']] = $v['cval'];
			}
			
			$res->rows = $ret;
		}
		
		return $res;
	}
}