<?php
namespace funky\services;

// provides some handy methods for getting unique things
class unique
{
	// given a full filepath (including the filename), this function returns a new unique value
	// for example, use this when uploading a file to ensure the filename is unique
	// returns false if it cannot find a unique name
	public function filename($filepath)
	{
		// try to add "2" etc. to the end of the filename
		$pathinfo = pathinfo($filepath);
		$num = 2;
		while(file_exists($filepath)){
			$filepath = $pathinfo['dirname'].'/'.$pathinfo['filename'].$num.'.'.$pathinfo['extension'];
			$num++;
			if($num > 1000) return false;
		}
		return $filepath;
	}

	// given a db table, db col name, and a value, this function returns a new value
	// the value it returns could be different than the one given
	// and it is guaranteed to be unique for that column
	public function dbval($table, $col, $val)
	{
		// escape input
		$table = f()->db->escape($table);
		$col = f()->db->escape($col);
		$val = f()->db->escape($val);

		// keep trying to find a unique one
		$newval = $val;
		$num = 1;
		$count = 1;
		$safetynet = 0;
		while($count > 0){
			$sql = 'SELECT COUNT(1) AS count FROM `'.$table.'` WHERE `'.$col.'` = "'.$newval.'"';
			$count = intval(f()->db->query($sql)->val('count'));
			if($count > 0){
				$num++;
				$newval = $val.'-'.$num;
			}
			if($safetynet++ > 100){
				throw new \exception('f()->unique->dbval() could not generate a unique value after 100 attempts.');
			}
		}

		return $newval;
	}
}