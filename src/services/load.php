<?php
namespace funky\services;

class load
{
	public function field($name, $typename, $args=array())
	{
		$class = '\\funky\\fields\\'.f()->db->type().'\\'.$typename;
		$field = new $class($name, $args);
		return $field;
	}
	public function fields($arr)
	{
		$f = array();
		foreach($arr as $a){
			$name = $a[0];
			$type = $a[1];
			$args = array();
			if(isset($a[2])) $args = $a[2];
			$f[$name] = f()->load->field($name, $type, $args);
		}
		return $f;
	}
}