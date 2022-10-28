<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 04 07
 * @since       PHPBoost 3.0 - 2012 02 26
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

abstract class ModuleUpdateVersion implements UpdateVersion
{
	protected static $module_id;
	
	protected $content_tables = array();
	
	protected static $delete_old_files_list = array();
	protected static $delete_old_folders_list = array();
	
	protected $database_columns_to_add = array();
	protected $database_columns_to_delete = array();
	protected $database_columns_to_modify = array();
	
	protected $database_keys_to_add = array();
	protected $database_keys_to_delete = array();
	
	protected $querier;
	protected $db_utils;
	protected $tables_list;

	public function __construct($module_id)
	{
		self::$module_id = $module_id;
		self::$delete_old_files_list = array();
		self::$delete_old_folders_list = array();
		$this->querier = PersistenceContext::get_querier();
		$this->db_utils = PersistenceContext::get_dbms_utils();
		$this->tables_list = $this->db_utils->list_tables(true);
	}

	public function get_module_id()
	{
		return self::$module_id;
	}

	public function execute()
	{
		self::delete_old_files();
		self::delete_old_folders();
		
		if (ModulesManager::is_module_installed(self::$module_id))
		{			
			$this->modify_database_columns();
			
			$this->add_database_columns();
			$this->add_database_keys();
			
			$this->execute_module_specific_changes();
			
			$this->delete_database_columns();
			$this->delete_database_keys();
			
			$this->update_content();
		}
	}

	/**
	 * Add columns in the database.
	 */
	private function add_database_columns()
	{
		foreach ($this->database_columns_to_add as $table)
		{
			if (in_array($table['table_name'], $this->tables_list))
			{
				$columns = $this->db_utils->desc_table($table['table_name']);
				
				foreach ($table['columns'] as $column_name => $attributes)
				{
					if (!isset($columns[$column_name]))
						$this->db_utils->add_column($table['table_name'], $column_name, $attributes);
				}
			}
		}
	}

	/**
	 * Delete columns in the database.
	 */
	private function delete_database_columns()
	{
		foreach ($this->database_columns_to_delete as $table)
		{
			if (in_array($table['table_name'], $this->tables_list))
			{
				$columns = $this->db_utils->desc_table($table['table_name']);
				
				foreach ($table['columns'] as $column_name)
				{
					if (isset($columns[$column_name]))
						$this->db_utils->drop_column($table['table_name'], $column_name);
				}
			}
		}
	}

	/**
	 * Updates columns in the database.
	 */
	private function modify_database_columns()
	{
		foreach ($this->database_columns_to_modify as $table)
		{
			if (in_array($table['table_name'], $this->tables_list))
			{
				$columns = $this->db_utils->desc_table($table['table_name']);
				
				foreach ($table['columns'] as $old_name => $new_name)
				{
					if (isset($columns[$old_name]))
						$this->querier->inject('ALTER TABLE ' . $table['table_name'] . ' CHANGE ' . $old_name . ' ' . $new_name);
				}
			}
		}
	}

	/**
	 * Add keys in tables of the database.
	 */
	private function add_database_keys()
	{
		foreach ($this->database_keys_to_add as $table)
		{
			if (in_array($table['table_name'], $this->tables_list))
			{
				if (isset($table['keys']))
				{
					$columns = $this->db_utils->desc_table($table['table_name']);
				
					foreach ($table['keys'] as $column_name => $fulltext)
					{
						if (!isset($columns[$column_name]['key']) || !$columns[$column_name]['key'])
							$this->querier->inject('ALTER TABLE ' . $table['table_name'] . ' ADD ' . ($fulltext ? 'FULLTEXT ' : '') . 'KEY `' . $column_name . '` (`' . $column_name . '`)');
					}
				}
			}
		}
	}

	/**
	 * Delete keys in tables of the database.
	 */
	private function delete_database_keys()
	{
		foreach ($this->database_keys_to_delete as $table)
		{
			if (in_array($table['table_name'], $this->tables_list))
			{
				if (isset($table['keys']))
				{
					$columns = $this->db_utils->desc_table($table['table_name']);
				
					foreach ($table['keys'] as $column_name)
					{
						if (isset($columns[$column_name]['key']) && $columns[$column_name]['key'])
							$this->querier->inject('ALTER TABLE ' . $table['table_name'] . ' DROP KEY `' . $column_name . '`');
					}
				}
			}
		}
	}
	
	/**
	 * Execute module specific changes
	 */
	protected function execute_module_specific_changes() {}

	/**
	 * Update the content to parse new code and css classes.
	 */
	protected function update_content()
	{
		foreach ($this->content_tables as $table)
		{
			if (is_array($table) && isset($table['name']))
			{
				$columns = $this->db_utils->desc_table($table['name']);
				$content_field = isset($table['content_field']) ? $table['content_field'] : (isset($columns['content']) ? 'content' : 'contents');
				$id_field = isset($table['id_field']) ? $table['id_field'] : 'id';
				UpdateServices::update_table_content($table['name'], $content_field, $id_field);
			}
			else
			{
				$columns = $this->db_utils->desc_table($table);
				UpdateServices::update_table_content($table, (isset($columns['content']) ? 'content' : 'contents'));
			}
		}
	}

	/**
	 * Deletes the old files of the modules which are not necessary anymore.
	 */
	public static function delete_old_files()
	{
		foreach (self::$delete_old_files_list as $file_name)
		{
			$file_name = !preg_match('~/' . self::$module_id . '/~', $file_name) ? PATH_TO_ROOT . '/' . self::$module_id . $file_name : $file_name;
			$file = new File($file_name);
			$file->delete();
		}
	}

	/**
	 * Deletes the old folders and their content of the modules which are not necessary anymore.
	 */
	public static function delete_old_folders()
	{
		foreach (self::$delete_old_folders_list as $folder_name)
		{
			$folder_name = !preg_match('~/' . self::$module_id . '/~', $folder_name) ? PATH_TO_ROOT . '/' . self::$module_id . $folder_name : $folder_name;
			$folder = new Folder($folder_name);
			if ($folder->exists())
				$folder->delete();
		}
	}
}
?>
