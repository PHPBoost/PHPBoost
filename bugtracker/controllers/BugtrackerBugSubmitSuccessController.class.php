<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 07 05
 * @since       PHPBoost 5.0 - 2016 07 01
*/

class BugtrackerBugSubmitSuccessController extends UserErrorController
{
	public function __construct($id)
	{
		$lang = LangLoader::get('common', 'bugtracker');

		parent::__construct(StringVars::replace_vars($lang['success.add'], array('id' => $id)), $lang['success.add.details'], self::SUCCESS);
	}
}
?>
