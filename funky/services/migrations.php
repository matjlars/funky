<?php
class migrations
{
	public function getall()
	{
		$migrations = array();
		$migrations += $this->getcreatetables();
		$migrations += $this->getmodelfields();
		$migrations += $this->getdroptables();
		return $migrations;
	}
	public function getcreatetables(){
		$migrations = array();
		foreach(f()->info->models() as $modelname){
			$modelclass = '\\models\\'.$modelname;
			$table = $modelclass::table();
			if(!f()->db->table_exists($table)){
				$migrations[] = array(
					'name'=>'Create Table '.$table,
					'sql'=>$this->create_table_sql($modelclass),
				);
			}
		}
		return $migrations;
	}
	public function getdroptables(){
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
	public function getmodelfields(){
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
			$schema = f()->db->query('describe '.$table)->map('Field', 'Type');
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
	protected function create_table_sql($modelclass){
		$table = $modelclass::table();
		$sql = 'CREATE TABLE `'.$table.'`(';
		// generate an array of sql strings for each field
		$field_sqls = array();
		$sql .= '`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,';
		foreach($modelclass::fields() as $field){
			$sql .= '`'.$field->name().'` '.$field->dbtype().' NOT NULL,';
		}
		$sql .= implode(', ', $field_sqls);
		$sql .= 'PRIMARY KEY (`id`))';
		return $sql;
	}
}