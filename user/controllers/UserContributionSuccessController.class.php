<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 05
 * @since       PHPBoost 3.0 - 2011 10 07
*/

class UserContributionSuccessController extends UserErrorController
{
	public function __construct()
	{
		$lang = LangLoader::get('contribution-lang');

		parent::__construct($lang['contribution.confirmed'], $lang['contribution.confirmed.messages'], self::SUCCESS);
	}
}
?>
