<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 05
 * @since       PHPBoost 5.0 - 2016 07 01
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class BugtrackerBugSubmitSuccessController extends UserErrorController
{
	public function __construct($id)
	{
		$lang = LangLoader::get_all_langs('bugtracker');

		parent::__construct(StringVars::replace_vars($lang['success.add'], array('id' => $id)), $lang['success.add.details'], self::SUCCESS);
	}
}
?>
