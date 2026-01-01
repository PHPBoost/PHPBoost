<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2025 01 07
 * @since       PHPBoost 3.0 - 2011 10 07
*/

class UserContributionSuccessController extends UserErrorController
{
	public function __construct()
	{
		$lang = LangLoader::get_all_langs();

		parent::__construct($lang['contribution.confirmed'], $lang['contribution.confirmed.messages'], self::SUCCESS);

        $full_url = AppContext::get_request()->get_current_url();
        $path = AppContext::get_request()->get_value('url', '');
        $module_url = str_replace($path, '', $full_url) . '/';
		$this->set_correction_link(LangLoader::get_message('common.back', 'common-lang'), $module_url);
	}
}
?>
