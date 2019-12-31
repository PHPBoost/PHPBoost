<?php
/**
 * @package     Content
 * @subpackage  Category
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 31
 * @since       PHPBoost 4.0 - 2013 01 31
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class RootCategory extends Category
{
	public function __construct()
	{
		$this->set_id(self::ROOT_CATEGORY);
		$this->set_id_parent(self::ROOT_CATEGORY);
		$this->set_name(LangLoader::get_message('root', 'main'));
		$this->set_rewrited_name('root');
		$this->set_order(0);
	}

	/**
	 * @return mixed[] Array of ActionAuthorization for AuthorizationsSettings
	 */
	public static function get_authorizations_settings()
	{
		return array_merge(
			DefaultCategoriesFormController::get_authorizations_settings(), array(
			new MemberDisabledActionAuthorization(LangLoader::get_message('authorizations.categories_management', 'common'), Category::CATEGORIES_MANAGEMENT_AUTHORIZATIONS)
		));
	}
}
?>
