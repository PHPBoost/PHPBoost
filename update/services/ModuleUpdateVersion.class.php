<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version   	PHPBoost 5.3 - last update: 2019 04 09
 * @since   	PHPBoost 3.0 - 2012 02 26
*/

abstract class ModuleUpdateVersion implements UpdateVersion
{
	protected $module_id;
	protected $content_tables = array();
	protected $delete_old_files_list = array();
	protected $delete_old_folders_list = array();
	protected $querier;
	protected $db_utils;

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
			$this->update_content();
		}

		$this->delete_old_files();
		$this->delete_old_folders();
	}

	/**
	 * Update the content to parse new code and css classes.
	 */
	protected function update_content()
	{
		foreach ($this->content_tables as $table)
		{
			UpdateServices::update_table_content($table);
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
