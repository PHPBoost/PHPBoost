<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 05
 * @since       PHPBoost 4.1 - 2015 02 10
*/

class GalleryCategoriesFormController extends DefaultRichCategoriesFormController
{
	/**
	 * @return mixed[] Array of ActionAuthorization for AuthorizationsSettings
	 */
	public function get_authorizations_settings()
	{
		return array(
			new ActionAuthorization(self::$common_lang['authorizations.read'], Category::READ_AUTHORIZATIONS),
			new VisitorDisabledActionAuthorization(self::$common_lang['authorizations.write'], Category::WRITE_AUTHORIZATIONS),
			new MemberDisabledActionAuthorization(self::$common_lang['authorizations.moderation'], Category::MODERATION_AUTHORIZATIONS),
		);
	}
}
?>
