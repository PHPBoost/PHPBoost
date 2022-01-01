<?php
/**
 * @package     PHPBoost
 * @subpackage  Menu\extension-point
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2012 05 07
*/

class ModuleMenus implements MenusExtensionPoint
{
	private $menus = array();

	public function __construct(Array $menus)
	{
		$this->menus = $menus;
	}

	public function get_menus()
	{
		return $this->menus;
	}
}
?>
