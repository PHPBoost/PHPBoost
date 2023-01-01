<?php
/**
 * This class enables you to manages the PHPBoost packages which are nothing else than the modules.
 * @package     PHPBoost
 * @subpackage  Module\tree-links
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 05 01
 * @since       PHPBoost 4.1 - 2013 11 15
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class AdminModuleLink extends ModuleLink
{
	public function __construct($name, $url)
	{
		parent::__construct($name, $url, AppContext::get_current_user()->check_level(User::ADMINISTRATOR_LEVEL));
	}
}
?>
