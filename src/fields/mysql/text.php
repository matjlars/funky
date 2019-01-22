<?php
namespace funky\fields\mysql;

class text extends \funky\fields\base\text
{
	public function dbtype()
	{
		$sqltype = 'varchar';
		if($this->length == $this->minlength){
			$sqltype = 'char('.$this->length.')';
		}else{
			if($this->length <= 255){
				$sqltype = 'varchar('.$this->length.')';
			}else if($this->length <= 4096){
				$sqltype = 'text';
			}else{
				throw new \exception('TODO add text type for length '.$this->length);
			}
		}
		return $sqltype;
	}
}
