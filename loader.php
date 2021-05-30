<?php

function load_model($model)
{
	require_once 'app/model/'.$model.'.php';
	
	if (strpos($model, '/') !== false)
	{
		$class = preg_replace('@/@', '_', $model);
		
		$obj = new $class;
	}
	
	else $obj = new $model;
	
	return $obj;
}

function load_language($code = 'en')
{
	global $_;
	
	require_once 'app/lang/'.$code.'.php';
}
