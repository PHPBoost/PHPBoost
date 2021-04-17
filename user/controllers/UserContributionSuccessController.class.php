<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 04 17
 * @since       PHPBoost 3.0 - 2011 10 07
*/

class UserContributionSuccessController extends UserErrorController
{
	public function __construct()
	{
		$lang = LangLoader::get('user-lang');

		parent::__construct($lang['user.contribution.confirmed'], $lang['user.contribution.confirmed.messages'], self::SUCCESS);
	}
}
?>
