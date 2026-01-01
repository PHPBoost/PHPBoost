<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2024 01 12
 * @since       PHPBoost 5.0 - 2016 07 01
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class BugtrackerBugSubmitSuccessController extends UserErrorController
{
	public function __construct($id)
	{
		$lang = LangLoader::get_all_langs('bugtracker');

		parent::__construct(StringVars::replace_vars($lang['success.add'], array('id' => $id)), $lang['success.add.details'], self::SUCCESS);

		$this->set_correction_link(LangLoader::get_message('common.back', 'common-lang'), PATH_TO_ROOT);
	}
}
?>
