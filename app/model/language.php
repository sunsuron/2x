<?php
class Language
{
	public function get()
	{
		$sql = "SELECT * FROM language";
		
		$res = db_query($sql);
		
		return $res;
	}
	
	public function key()
	{
		$sql = "SELECT * FROM language_key";
		
		$res = db_query($sql);
		
		return $res;
	}
}
