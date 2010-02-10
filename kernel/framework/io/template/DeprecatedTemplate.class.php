<?php
/*##################################################
 *                         DeprecatedTemplate.class.php
 *                            -------------------
 *   begin                : September 17, 2009
 *   copyright            : Loïc Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @package io
 * @subpackage template
 * @deprecated
 * @desc This class exists only to be compliant with legacy code. It manages several templates in the same objet.
 * In fact, a single instance of this class loads several templates which share the same data.
 * As of PHPBoost 3.1, it's forbidden to use it, but it won't be removed while the legacy code will use it.
 * @author Loïc Rouchon <loic.rouchon@phpboost.com>
 */
class DeprecatedTemplate extends FileTemplate
{
	private $modules = array();

	public function __construct()
	{
		parent::__construct('');
		$this->data->auto_load_frequent_vars();
	}

	/**
	 * @deprecated
	 * @desc Loads several files in the same Template instance.
	 * @param string[] $array_tpl A map file_identifier => file_path where file_identifier is the name you give to your file and file_path its path.
	 * See the class description to learn how to write the path.
	 */
	public function set_filenames($array_tpl)
	{
		foreach ($array_tpl as $identifier => $filename)
		{
			$new_template = new FileTemplate($filename, Template::DONOT_LOAD_FREQUENT_VARS);
			$module_data_path = $new_template->get_data()->get_var('PICTURES_DATA_PATH');
				
			$this->bind_vars($new_template);
			$this->add_subtemplate($identifier, $new_template);
			$this->find_module($filename, $identifier);
				
			// Auto assign the module data path
			// Use of MODULE_DATA_PATH is deprecated
			$new_template->assign_vars(array('PICTURES_DATA_PATH' => $module_data_path));
		}
	}

	/**
	 * @deprecated
	 * @desc Retrieves the path of the module. This path will be used to write the relative paths in your templates.
	 * @param string $module Name of the module for which you want to know the data path.
	 * @return string The relative path.
	 */
	public function get_module_data_path($module)
	{
		return '';
	}

	/**
	 * @deprecated
	 * @desc Parses the file whose name is $parse_name and which has been declared with the set_filenames_method. It uses the variables you assigned (when you assign a
	 * variable it will be usable in every file handled by this object).
	 * @param string $parse_name The identifier of the file you want to parse.
	 */
	public function pparse($identifier)
	{
		$template = $this->get_data()->get_subtemplate($identifier);
		if ($template != null)
		{
			$template->display();
		}
	}

	private function bind_vars($template)
	{
		$this->data->bind_vars($template->data);
	}

	private function find_module($identifier, $real_identifier)
	{
		$trimmed_identifier = trim($identifier, '/');
		$module = null;
		if (($idx = strpos($trimmed_identifier, '/')) > 0)
		{
			$module = substr($trimmed_identifier, 0, $idx);
		}
		else
		{
			$module =& $trimmed_identifier;
		}

		if (empty($this->modules[$module]))
		{
			$this->modules[$module] =& $this->subtemplates[$real_identifier];
		}
	}
}
?>
