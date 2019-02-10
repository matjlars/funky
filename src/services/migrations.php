<?php
namespace funky\services;

class migrations
{
	public function getall()
	{
		$migrations = [];

		if(f()->db->type() == 'mysql'){
			$migrations += $this->mysql_getcreatetables();
			$migrations += $this->mysql_getmodelfields();
			$migrations += $this->mysql_getdroptables();
		}elseif(f()->db->type() == 'sqlite'){
			$migrations += $this->sqlite_getcreatetables();
			$migrations += $this->sqlite_getmodelfields();
			$migrations += $this->sqlite_getdroptables();
		}else{
			throw new \Exception('unsupported type '.f()->db->type());
		}

		return $migrations;
	}

	private function mysql_getcreatetables(){
		$migrations = array();
		foreach(f()->info->models() as $modelname){
			$modelclass = '\\models\\'.$modelname;
			$table = $modelclass::table();
			if(!f()->db->table_exists($table)){
				$migrations[] = array(
					'name'=>'Create Table '.$table,
					'sql'=>$this->mysql_create_table_sql($modelclass),
				);
			}
		}
		return $migrations;
	}

	public function mysql_getdroptables(){
		$migrations = array();
		$tables = f()->db->tables();
		$models = f()->info->models();
		// get an array of model table names:
		$modeltables = array();
		foreach($models as $modelname){
			$modelclass = '\\models\\'.$modelname;
			$modeltables[] = $modelclass::table();
		}
		// find all tables that exist that are not model table names:
		$badtables = array_diff($tables, $modeltables);
		foreach($badtables as $table){
			$migrations[] = array(
				'name'=>'Drop table '.$table,
				'sql'=>'DROP TABLE `'.$table.'`'
			);
		}
		return $migrations;
	}

	private function mysql_getmodelfields()
	{
		$migrations = array();
		// run through every field of every table
		foreach(f()->info->models() as $modelname){
			$extrafields = array();
			$missingfields = array();
			$modelclass = '\\models\\'.$modelname;
			$table = $modelclass::table();
			if(!f()->db->table_exists($table)){
				// this table doesn't even exist, so the create table migration trumps this one.
				continue;
			}
			// get this table's schema
			$schema = array();
			foreach(f()->db->query('describe `'.$table.'`') as $schemarow){
				$schema[$schemarow['Field']] = $schemarow;
			}
			$fields = $modelclass::fields();
			foreach($fields as $field){
				$fieldname = $field->name();
				$dbtype = $field->dbtype();

				// determine if this field will be nullable
				$nullstr = ' NOT NULL';
				if($field->isnullable()) $nullstr = '';

				// if this field doesn't exist in the schema, add a migration to add it:
				if(!isset($schema[$fieldname])){
					$missingfields[$fieldname] = $dbtype;
					$migrations[] = array(
						'name'=>'Add field '.$table.'.'.$fieldname,
						'sql'=>'ALTER TABLE `'.$table.'` ADD `'.$fieldname.'` '.$dbtype.$nullstr,
					);
					continue;
				}
				
				// in this context, the field exists in the current schema, so check if the type is good:
				if($schema[$fieldname]['Type'] != $dbtype){
					$migrations[] = array(
						'name'=>'Alter '.$table.'.'.$fieldname,
						'sql'=>'ALTER TABLE `'.$table.'` MODIFY `'.$fieldname.'` '.$dbtype.$nullstr,
					);
					continue;
				}

				// if the field's isnullable value doesn't match the one in the database, add a migration for that
				if(($schema[$fieldname]['Null'] == 'NO' && $field->isnullable()) || ($schema[$fieldname]['Null'] == 'YES' && !$field->isnullable())){
					$migrations[] = array(
						'name'=>'Alter '.$table.'.'.$fieldname."'s nullability",
						'sql'=>'ALTER TABLE `'.$table.'` MODIFY `'.$fieldname.'` '.$dbtype.$nullstr,
					);
					continue;
				}
			}
			
			// now, find all fields in the schema that are not in $fields:
			// this means the column is in the database but it shouldn't be.
			foreach($schema as $column=>$s){
				$type = $s['Type'];
				if($column == 'id') continue;
				if(!isset($fields[$column])){
					$extrafields[] = $column;
					$migrations[] = array(
						'name'=>'Remove column '.$table.'.'.$column.' and LOSE ALL DATA',
						'sql'=>'ALTER TABLE `'.$table.'` DROP COLUMN `'.$column.'`',
					);
				}
			}
			
			// now, if there are both extra and missing fields, automatically generate rename migrations:
			if(!empty($extrafields) && !empty($missingfields)){
				foreach($extrafields as $extrafield){
					foreach($missingfields as $missingfield=>$missingfieldtype){
						$migrations[] = array(
							'name'=>'Rename column in '.$table.' from '.$extrafield.' to '.$missingfield. ' and keep data',
							'sql'=>'ALTER TABLE `'.$table.'` CHANGE `'.$extrafield.'` `'.$missingfield.'` '.$missingfieldtype,
						);
					}
				}
			}
		}
		return $migrations;
	}


	private function sqlite_getcreatetables()
	{
		$migrations = [];
		foreach(f()->info->models() as $modelname){
			$modelclass = '\\models\\'.$modelname;
			$table = $modelclass::table();
			if(!f()->db->table_exists($table)){
				$migrations[] = array(
					'name'=>'Create Table '.$table,
					'sql'=>$this->sqlite_create_table_sql($modelclass),
				);
			}
		}
		return $migrations;
	}

	private function sqlite_getmodelfields()
	{
		$migrations = [];
		// run through every field of every table
		foreach(f()->info->models() as $modelname){
			$extrafields = array();
			$missingfields = array();
			$modelclass = '\\models\\'.$modelname;
			$table = $modelclass::table();
			if(!f()->db->table_exists($table)){
				// this table doesn't even exist, so the create table migration trumps this one.
				continue;
			}
			// get this table's schema
			$schema = $this->sqlite_schema($table);
			$fields = $modelclass::fields();
			foreach($fields as $field){
				$fieldname = $field->name();
				$dbtype = $field->dbtype();

				// if this field doesn't exist in the schema, add a migration to add it:
				if(!isset($schema[$fieldname])){
					$missingfields[$fieldname] = $dbtype;
					$migrations[] = array(
						'name'=>'Add field '.$table.'.'.$fieldname,
						'sql'=>'ALTER TABLE `'.$table.'` ADD `'.$fieldname.'` '.$dbtype,
					);
					continue;
				}
				
				// in this context, the field exists in the current schema, so check if the type is good:
				if($schema[$fieldname] != $dbtype){
					$migrations[] = array(
						'name'=>'Alter '.$table.'.'.$fieldname,
						'sql'=>'ALTER TABLE `'.$table.'` MODIFY `'.$fieldname.'` '.$dbtype,
					);
					continue;
				}
			}
			
			// now, find all fields in the schema that are not in $fields:
			// this means the column is in the database but it shouldn't be.
			foreach($schema as $column=>$type){
				if($column == 'id') continue;
				if(!isset($fields[$column])){
					$extrafields[] = $column;
					$migrations[] = array(
						'name'=>'Remove column '.$table.'.'.$column.' and LOSE ALL DATA',
						'sql'=>'ALTER TABLE `'.$table.'` DROP COLUMN `'.$column.'`',
					);
				}
			}
			
			// now, if there are both extra and missing fields, automatically generate rename migrations:
			if(!empty($extrafields) && !empty($missingfields)){
				foreach($extrafields as $extrafield){
					foreach($missingfields as $missingfield=>$missingfieldtype){
						$migrations[] = array(
							'name'=>'Rename column in '.$table.' from '.$extrafield.' to '.$missingfield. ' and keep data',
							'sql'=>'ALTER TABLE `'.$table.'` CHANGE `'.$extrafield.'` `'.$missingfield.'` '.$missingfieldtype,
						);
					}
				}
			}
		}
		return $migrations;
	}

	private function sqlite_getdroptables()
	{
		$migrations = [];
		$tables = f()->db->tables();
		$models = f()->info->models();
		// get an array of model table names:
		$modeltables = array();
		foreach($models as $modelname){
			$modelclass = '\\models\\'.$modelname;
			$modeltables[] = $modelclass::table();
		}
		// find all tables that exist that are not model table names:
		$badtables = array_diff($tables, $modeltables);
		foreach($badtables as $table){
			$migrations[] = array(
				'name'=>'Drop table '.$table,
				'sql'=>'DROP TABLE `'.$table.'`'
			);
		}
		return $migrations;
	}

	public function create_table_sql($modelclass)
	{
		if(f()->db->type() == 'mysql'){
			return $this->mysql_create_table_sql($modelclass);
		}elseif(f()->db->type() == 'sqlite'){
			return $this->sqlite_create_table_sql($modelclass);
		}else{
			throw new \Exception('unsupported db type '.f()->db->type());
		}
	}

	// returns the sql necessary to create a table given a model class name
	private function mysql_create_table_sql($modelclass){
		$table = $modelclass::table();
		$sql = 'CREATE TABLE `'.$table.'`(';
		// generate an array of sql strings for each field
		$sql .= '`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,';
		foreach($modelclass::fields() as $field){
			$sql .= '`'.$field->name().'` '.$field->dbtype();
			if(!$field->isnullable()) $sql .= ' NOT NULL';
			$sql .= ',';
		}
		$sql .= 'PRIMARY KEY (`id`))';
		return $sql;
	}

	// returns the sql necessary to create a table given a model class name
	private function sqlite_create_table_sql($modelclass){
		$table = $modelclass::table();
		$sql = 'CREATE TABLE "'.$table.'" (';
		$sql .= 'id integer PRIMARY KEY,';
		foreach($modelclass::fields() as $field){
			$sql .= '"'.$field->name().'" '.$field->dbtype().',';
		}
		$sql = rtrim($sql, ',');
		$sql .= ')';
		return $sql;
	}

	public function get_schema($table)
	{
		if(f()->db->type() == 'mysql'){
			return $this->mysql_get_schema($table);
		}elseif(f()->db->type() == 'sqlite'){
			return $this->sqlite_get_schema($table);
		}else{
			throw new \Exception('unsupported db type '.f()->db->type());
		}
	}

	private function sqlite_schema($table)
	{
		$res = f()->sqlite->query('select sql from sqlite_master where type = "table" and name = "'.$table.'"');
		$sql = $res->val('sql');
		$leftP = strpos($sql, '(')+1;
		$rightP = strpos($sql, ')');
		$fieldString = substr($sql, $leftP, $rightP - $leftP);
		$fields = explode(',', $fieldString);
		$schema = [];
		foreach($fields as $field){
			preg_match('/([a-zA-Z_]+)[^a-zA-Z_]* ([a-zA-Z_]+)/', $field, $matches);
			if(isset($matches[1]) && isset($matches[2])){
				$schema[$matches[1]] = $matches[2];
			}
		}
		return $schema;
	}
}