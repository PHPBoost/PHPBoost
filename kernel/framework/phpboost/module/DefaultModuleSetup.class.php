<?php
/**
 * @package     PHPBoost
 * @subpackage  Module
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 2.0 - 2009 01 16
*/

class DefaultModuleSetup implements ModuleSetup
{
	/* (non-PHPdoc)
	 * @see kernel/framework/phpboost/module/ModuleSetup#check_environment()
	 */
	public function check_environment()
	{
		return new ValidationResult();
	}

	/* (non-PHPdoc)
	 * @see kernel/framework/phpboost/module/ModuleSetup#install()
	 */
	public function install()
	{

	}

	/* (non-PHPdoc)
	 * @see kernel/framework/phpboost/module/ModuleSetup#uninstall()
	 */
	public function uninstall()
	{

	}

	/* (non-PHPdoc)
	 * @see kernel/framework/phpboost/module/ModuleSetup#upgrade()
	 */
	public function upgrade($installed_version)
	{
		return null;
	}
}
?>
