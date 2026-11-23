<?php
/**
 * @package     PHPBoost
 * @subpackage  Menu\extension-point
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 22
 * @since       PHPBoost 3.0 - 2012 05 07
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ModuleMenus implements MenusExtensionPoint
{
	private array $menus = [];

	public function __construct(Array $menus)
	{
		$this->menus = $menus;
	}

	public function get_menus(): array
	{
		return $this->menus;
	}
}
?>
