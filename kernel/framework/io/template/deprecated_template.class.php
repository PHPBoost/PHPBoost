<?php
/*##################################################
 *                            deprecated_template.class.php
 *                            -------------------
 *   begin                : September 17, 2009
 *   copyright         : Loïc Rouchon
 *   email                : horn@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
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
 * The PHPboost template engine is actually based on sections of code from phpBB3 templates
 ###################################################*/

import('io/template/template');

class DeprecatedTemplate extends Template
{
	public function __construt()
	{
		$this->auto_load_frequent_vars();
	}

	/**
	 * @desc Loads several files in the same Template instance.
	 * @deprecated
	 * @param string[] $array_tpl A map file_identifier => file_path where file_identifier is the name you give to your file and file_path its path.
	 * See the class description to learn how to write the path.
	 */
	public function set_filenames($array_tpl)
	{
		foreach ($array_tpl as $identifier => $filename)
		{
			$new_template = new Template($filename, DO_NOT_AUTO_LOAD_FREQUENT_VARS);
			$this->bind_vars($new_template);
			$this->add_subtemplate($identifier, $new_template);
		}
	}
	
	/**
	 * @deprecated
	 * @desc Parses the file whose name is $parse_name and which has been declared with the set_filenames_method. It uses the variables you assigned (when you assign a
	 * variable it will be usable in every file handled by this object).
	 * @param string $parse_name The identifier of the file you want to parse.
	 */
	public function pparse($identifier)
	{
		$template =& $this->get_subtemplate($identifier);
		if ($template != null)
		{
			$template->parse();
		}
	}
	
	private function bind_vars($template)
	{
		$template->vars =& $this->vars;
		$template->blocks =& $this->blocks;
		$template->subtemplates =& $this->subtemplates;
		$template->langs =& $this->langs;
	}
}
?>
