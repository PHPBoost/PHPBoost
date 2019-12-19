<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 05
 * @since       PHPBoost 5.3 - 2019 11 02
*/

class CategoriesAuthorizationsService
{
	public $id_category;
	public $module_id;
	public $id_category_field;

	public static function check_authorizations($id_category = Category::ROOT_CATEGORY, $module_id = '', $id_category_field = CategoriesItemsParameters::DEFAULT_FIELD_NAME)
	{
		$instance = new self();
		$instance->id_category = $id_category;
		$instance->module_id = $module_id;
		$instance->id_category_field = $id_category_field;
		return $instance;
	}

	public function read()
	{
		return $this->is_authorized(Category::READ_AUTHORIZATIONS, Authorizations::AUTH_PARENT_PRIORITY);
	}

	public function contribution()
	{
		return $this->is_authorized(Category::CONTRIBUTION_AUTHORIZATIONS);
	}

	public function write()
	{
		return $this->is_authorized(Category::WRITE_AUTHORIZATIONS);
	}

	public function moderation()
	{
		return $this->is_authorized(Category::MODERATION_AUTHORIZATIONS);
	}

	public function manage_categories()
	{
		return $this->is_authorized(Category::CATEGORIES_MANAGEMENT_AUTHORIZATIONS);
	}

	protected function is_authorized($bit, $mode = Authorizations::AUTH_CHILD_PRIORITY)
	{
		$auth = CategoriesService::get_categories_manager($this->module_id, $this->id_category_field)->get_heritated_authorizations($this->id_category, $bit, $mode);
		return AppContext::get_current_user()->check_auth($auth, $bit);
	}
}
?>
