<?php
/*##################################################
 *                     ModuleCssFiles.class.php
 *                            -------------------
 *   begin                : March 27, 2012
 *   copyright            : (C) 2012 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 * @desc This class allows you to manage css files of a module
 * @package {@package}
 */
class ModuleCssFiles implements CssFilesExtensionPoint
{
	/**
	 * @var string[]
	 */
	private $css_files_always_displayed = array();
	
	/**
	 * @var string[]
	 */
	private $css_files_running_module_displayed = array();
	
	
	/**
	 * @desc Adding css files to the module to display only the pages of the module
	 * @param string $css_file containing css file name
	 */
	public function adding_always_displayed_file($css_file)
	{
		$this->css_files_always_displayed[] = $css_file;
	}
	
	public function get_css_files_always_displayed()
	{
		return $this->css_files_always_displayed;
	}

	/**
	 * @desc Adding css files to display on all pages
	 * @param string $css_file containing css file name
	 */
	public function adding_running_module_displayed_file($css_file)
	{
		$this->css_files_running_module_displayed[] = $css_file;
	}
	
	public function get_css_files_running_module_displayed()
	{
		return $this->css_files_running_module_displayed;
	}
}
?>