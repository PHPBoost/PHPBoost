<?php
/**
 * @package     Content
 * @subpackage  Homepage
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2019 12 21
 * @since       PHPBoost 6.0 - 2019 12 20
*/

class DefaultHomePageDisplay implements HomePageExtensionPoint
{
	/**
	 * @var string the module identifier
	 */
	private $module_id;
	
	/**
	 * @var View view of the page
	 */
	private $view;

	/**
	 * DefaultHomePageDisplay constructor
	 * @param string $module_id the module id.
	 * @param View   $view the view of the page.
	 */
	public function __construct($module_id, View $view)
	{
		$this->module_id = $module_id;
		$this->view = $view;
	}

	/**
	 * @return string Return the id of the module
	 */
	public function get_module_id()
	{
		return $this->module_id;
	}

	/**
	 * @return View Return the view
	 */
	public function get_view()
	{
		return $this->view;
	}

	public function get_home_page()
	{
		return new DefaultHomePage($this->get_title(), $this->view);
	}

	private function get_title()
	{
		return ModulesManager::get_module($this->module_id)->get_configuration()->get_name();
	}
}
?>
