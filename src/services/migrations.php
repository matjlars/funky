<?php
namespace funky\services;

class migrations{
	public function getall(){
		$migrations = [];
		$migrations += $this->mysql_getcreatetables();
		$migrations += $this->mysql_getmodelfields();
		$migrations += $this->mysql_getdroptables();
		return $migrations;
	}

	protected function mysql_getcreatetables(){
		$migrations = array();
		foreach($this->model_classes() as $modelclass){
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

	public function mysql_getdroptables(){
		$migrations = array();
		$tables = f()->db->tables();
		// get an array of model table names:
		$modeltables = array();
		foreach($this->model_classes() as $modelclass){
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

	private function mysql_getmodelfields(){
		$migrations = array();
		// run through every field of every table
		foreach($this->model_classes() as $modelclass){
			$extrafields = array();
			$missingfields = array();
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

				// allow virtual fields by setting dbtype to null. they do not exist in the db.
				if(is_null($dbtype)) continue;

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
			$field_names = [];
			foreach($fields as $field){
				$field_names[$field->name()] = true;
			}

			foreach($schema as $column=>$s){
				$type = $s['Type'];
				if($column == 'id') continue;
				if(!isset($field_names[$column])){
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

	// returns the sql necessary to create a table given a model class name
	public function create_table_sql($modelclass){
		$table = $modelclass::table();
		$sql = 'CREATE TABLE `'.$table.'`(';
		// generate an array of sql strings for each field
		$sql .= '`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,';
		foreach($modelclass::fields() as $field){
			$dbtype = $field->dbtype();
			if($dbtype !== null){
				$sql .= '`'.$field->name().'` '.$dbtype;
				if(!$field->isnullable()) $sql .= ' NOT NULL';
				$sql .= ',';
			}
		}
		$sql .= 'PRIMARY KEY (`id`))';
		return $sql;
	}

	// returns an array of all model classes to consider generating migrations for
	// override this if you want to get fancy with which migrations to run where
	protected function model_classes(){
		$classes = [];
		foreach(f()->info->models() as $modelname){
			$classes[] = '\\models\\'.$modelname;
		}
		return $classes;
	}
}