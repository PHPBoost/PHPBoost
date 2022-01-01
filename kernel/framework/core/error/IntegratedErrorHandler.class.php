<?php
/**
 * @package     Core
 * @subpackage  Error
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 11 11
*/

class IntegratedErrorHandler extends ErrorHandler
{
	protected function display_debug()
	{
		parent::display_debug();
	}

	protected function display_fatal()
	{
		AppContext::get_response()->clean_output();
		die(ErrorHandler::FATAL_MESSAGE);
	}
}
?>
