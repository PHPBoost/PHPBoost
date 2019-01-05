<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2014 12 22
 * @since   	PHPBoost 3.0 - 2012 02 26
*/

abstract class ModuleUpdateVersion implements UpdateVersion
{
	protected $module_id;

	public function __construct($module_id)
	{
		$this->module_id = $module_id;
	}

	public function get_module_id()
	{
		return $this->module_id;
	}
}
?>
