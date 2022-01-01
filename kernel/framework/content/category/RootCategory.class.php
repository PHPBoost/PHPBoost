<?php
/**
 * @package     Content
 * @subpackage  Category
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 22
 * @since       PHPBoost 4.0 - 2013 01 31
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class RootCategory extends Category
{
	public function __construct()
	{
		$this->set_id(self::ROOT_CATEGORY);
		$this->set_id_parent(self::ROOT_CATEGORY);
		$this->set_name(LangLoader::get_message('common.root', 'common-lang'));
		$this->set_rewrited_name('root');
		$this->set_order(0);
	}

	/**
	 * @param string $module_id id of the module (optional)
	 * @return mixed[] Array of ActionAuthorization for AuthorizationsSettings
	 */
	public static function get_authorizations_settings($module_id = '')
	{
		return array_merge(
			DefaultCategoriesFormController::get_authorizations_settings($module_id), array(
			new MemberDisabledActionAuthorization(LangLoader::get_message('form.authorizations.categories', 'form-lang'), Category::CATEGORIES_MANAGEMENT_AUTHORIZATIONS)
		));
	}
}
?>
