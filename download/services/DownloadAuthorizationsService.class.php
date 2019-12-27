<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 27
 * @since       PHPBoost 4.0 - 2014 08 24
*/

class DownloadAuthorizationsService extends CategoriesAuthorizationsService
{
	const DISPLAY_DOWNLOAD_LINK_AUTHORIZATIONS = 32;

	public function display_download_link()
	{
		return $this->is_authorized(self::DISPLAY_DOWNLOAD_LINK_AUTHORIZATIONS);
	}

	protected function is_authorized($bit, $mode = Authorizations::AUTH_CHILD_PRIORITY)
	{
		if ($bit == self::DISPLAY_DOWNLOAD_LINK_AUTHORIZATIONS)
			$auth = DownloadConfig::load()->get_authorizations();
		else
			$auth = CategoriesService::get_categories_manager('download')->get_heritated_authorizations($this->id_category, $bit, $mode);

		return AppContext::get_current_user()->check_auth($auth, $bit);
	}
}
?>
