<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 10 10
 * @since       PHPBoost 3.0 - 2010 01 17
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class BBCodeSetup extends DefaultModuleSetup
{
	public function uninstall()
	{
		return AppContext::get_content_formatting_service()->uninstall_editor('BBCode');
	}
}
?>
