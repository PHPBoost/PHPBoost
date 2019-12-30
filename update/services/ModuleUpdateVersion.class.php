<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 30
 * @since       PHPBoost 3.0 - 2012 02 26
*/

abstract class ModuleUpdateVersion implements UpdateVersion
{
	protected $module_id;
	
	protected $content_tables = array();
	
	protected $delete_old_files_list = array();
	protected $delete_old_folders_list = array();
	
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
		$this->module_id = $module_id;
		$this->querier = PersistenceContext::get_querier();
		$this->db_utils = PersistenceContext::get_dbms_utils();
	}

	public function get_module_id()
	{
		return $this->module_id;
	}

	public function execute()
	{
		if (ModulesManager::is_module_installed($this->module_id))
		{
			$this->tables_list = $this->db_utils->list_tables(true);

			$this->add_database_columns();
			$this->delete_database_columns();
			$this->modify_database_columns();
			$this->modify_database_categories_columns();

			$this->add_database_keys();
			$this->delete_database_keys();
			
			$this->update_content();
		}

		$this->delete_old_files();
		$this->delete_old_folders();
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
	 * Updates categories columns in the database.
	 */
	private function modify_database_categories_columns()
	{
		$module_configuration = ModulesManager::get_module($this->module_id)->get_configuration();
		
		if ($module_configuration->has_categories())
		{
			$table_name = $module_configuration->get_categories_table_name();
			if (in_array($table_name, $this->tables_list))
			{
				$columns = $this->db_utils->desc_table($table_name);
				
				if (isset($columns['image']))
					$this->querier->inject('ALTER TABLE ' . $table_name . ' CHANGE image thumbnail VARCHAR(255) NOT NULL DEFAULT ""');
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
				$columns = $this->db_utils->desc_table($table['table_name']);
				
				foreach ($table['keys'] as $column_name => $fulltext)
				{
					if (!isset($columns[$column_name]['key']) || !$columns[$column_name]['key'])
						$this->querier->inject('ALTER TABLE ' . $table['table_name'] . ' ADD ' . ($fulltext ? 'FULLTEXT ' : '') . 'KEY `' . $column_name . '` (`' . $column_name . '`)');
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
				$columns = $this->db_utils->desc_table($table['table_name']);
				
				foreach ($table['keys'] as $column_name)
				{
					if (isset($columns[$column_name]['key']) && $columns[$column_name]['key'])
						$this->querier->inject('ALTER TABLE ' . $table['table_name'] . ' DROP KEY `' . $column_name . '`');
				}
			}
		}
	}

	/**
	 * Update the content to parse new code and css classes.
	 */
	protected function update_content()
	{
		foreach ($this->content_tables as $table)
		{
			if (is_array($table) && isset($table['name']))
			{
				$name = $table['name'];
				$contents = isset($table['contents']) ? $table['contents'] : 'contents';
				$id = isset($table['id']) ? $table['id'] : 'id';
			}
			else
			{
				$name = $table;
				$contents = 'contents';
				$id = 'id';
			}
			
			UpdateServices::update_table_content($name, $contents, $id);
		}
	}

	/**
	 * Deletes the old files of the modules which are not necessary anymore.
	 */
	private function delete_old_files()
	{
		foreach ($this->delete_old_files_list as $file_name)
		{
			$file_name = !preg_match('~/' . $this->module_id . '/~', $file_name) ? PATH_TO_ROOT . '/' . $this->module_id . $file_name : $filename;
			$file = new File($file_name);
			$file->delete();
		}
	}

	/**
	 * Deletes the old folders and their content of the modules which are not necessary anymore.
	 */
	private function delete_old_folders()
	{
		foreach ($this->delete_old_folders_list as $folder_name)
		{
			$folder_name = !preg_match('~/' . $this->module_id . '/~', $folder_name) ? PATH_TO_ROOT . '/' . $this->module_id . $folder_name : $folder_name;
			$folder = new Folder($folder_name);
			if ($folder->exists())
				$folder->delete();
		}
	}
}
?>
