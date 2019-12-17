<?php
/**
 * This class allows you to manage css files of a module
 * @package     PHPBoost
 * @subpackage  Module\css
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 10 24
 * @since       PHPBoost 3.0 - 2012 03 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
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
	 * Adding css files to the module to display only the pages of the module
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
	 * Adding css files to display on all pages
	 * @param string $css_file containing css file name
	 */
	public function adding_running_module_displayed_file($css_file, $module_id = '')
	{
		$this->css_files_running_module_displayed[] = array('css_file' => $css_file, 'module_id' => $module_id);
	}

	public function get_css_files_running_module_displayed()
	{
		return $this->css_files_running_module_displayed;
	}
}
?>
