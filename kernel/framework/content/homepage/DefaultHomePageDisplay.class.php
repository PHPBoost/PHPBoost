<?php
/**
 * @package     Content
 * @subpackage  Homepage
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 09
 * @since       PHPBoost 6.0 - 2019 12 20
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DefaultHomePageDisplay implements HomePageExtensionPoint
{
	private string $module_id = '';
	private ?View $view = null;

	/**
	 * DefaultHomePageDisplay constructor
	 * @param string $module_id the module id.
	 * @param View   $view the view of the page.
	 */
	public function __construct(string $module_id, View $view)
	{
		$this->module_id = $module_id;
		$this->view = $view;
	}

	public function get_module_id(): string
	{
		return $this->module_id;
	}

	public function get_view(): View
	{
		return $this->view;
	}

	public function get_home_page(): DefaultHomePage
	{
		return new DefaultHomePage($this->get_title(), $this->view);
	}

	private function get_title(): string
	{
		return ModulesManager::get_module($this->module_id)->get_configuration()->get_name();
	}
}
?>
