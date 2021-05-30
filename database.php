<?php

function db_escape($str)
{
	global $mysqli;

	return mysqli_real_escape_string($mysqli, $str);
}

function db_query($sql)
{
	global $mysqli, $sql_count;
	
	$result = mysqli_query($mysqli, $sql);
	
	$sql_count++;
	
	if (is_object($result) && get_class($result) == 'mysqli_result')
	{
	    $i = 0;
	    
	    $data = array();
	    
	    while ($row = mysqli_fetch_assoc($result))
	    {
	        $data[$i] = $row;
	        
	        $i++;
	    }
	    
	    $query = new stdClass();
	    
	    $query->row = isset($data[0]) ? $data[0] : array();
	    
	    $query->rows = $data;
	    
	    $query->num_rows = mysqli_num_rows($result);
	    
	    unset($data);
	    
	    return $query;
	}
	
	else if ($result === true)
	{
	    return mysqli_affected_rows($mysqli);
	}
	
	else
	{
	    throw new Exception(mysqli_error($mysqli));
	}
}

function db_insert($table, $data)
{
	global $mysqli;

	$sets = array();

	foreach ($data as $field => $value)
	{
		$sets[] = "`". $field ."` = '". mysqli_real_escape_string($mysqli, $value) ."'";
	}

	$set = implode(',', $sets);

	$sql = sprintf("INSERT INTO `". $table ."` SET %s", $set);
	
	$res = db_query($sql);

	return $res;
}

function db_update($table, $data, $where)
{
	global $mysqli;

	$sets = array();

	foreach ($data as $field => $value)
	{
		$sets[] = "`". $field ."` = '". mysqli_real_escape_string($mysqli, $value) ."'";
	}

	$set = implode(',', $sets);

	$where_sets = array();

	foreach ($where as $field => $value)
	{
		$where_sets[] = "`". $field ."` = '". mysqli_real_escape_string($mysqli, $value) ."'";
	}

	$where_sets = implode(' AND ', $where_sets);

	$sql = sprintf("UPDATE `". $table ."` SET %s WHERE 1=1 AND %s", $set, $where_sets);

	$res = db_query($sql);

	return $res;
}

function db_replace($table, $data) // insert or (delete & insert)
{
	global $mysqli;

	$sets = array();

	foreach ($data as $field => $value)
	{
		$sets[] = "`". $field ."` = '". mysqli_real_escape_string($mysqli, $value) ."'";
	}

	$set = implode(',', $sets);

	$sql = sprintf("REPLACE INTO `". $table ."` SET %s", $set);

	$res = db_query($sql);

	return $res;
}

function db_delete($table, $where)
{
	global $mysqli;

	$where_sets = array();

	foreach ($where as $field => $value)
	{
		$where_sets[] = "`". $field ."` = '". mysqli_real_escape_string($mysqli, $value) ."'";
	}

	$where_sets = implode(' AND ', $where_sets);

	$sql = sprintf("DELETE FROM `". $table ."` WHERE 1=1 AND %s", $where_sets);

	$res = db_query($sql);

	return $res;
}

function db_get($table, $where) // use with cautions
{
	global $mysqli;

	$sql = sprintf("SELECT * FROM `". $table ."` WHERE 1=1 AND %s", $where);

	$res = db_query($sql);

	return $res;
}

function db_exists($table, $where)
{
	global $mysqli;

	$where_sets = array();

	foreach ($where as $field => $value)
	{
		$value = mb_strtolower($value);

		$where_sets[] = "LOWER(`". $field ."`) = '". mysqli_real_escape_string($mysqli, $value) ."'";
	}

	$where_sets = implode(' AND ', $where_sets);

	$sql = sprintf("SELECT * FROM `". $table ."` WHERE 1=1 AND %s LIMIT 1", $where_sets);

	$res = db_query($sql);

	return $res;
}
