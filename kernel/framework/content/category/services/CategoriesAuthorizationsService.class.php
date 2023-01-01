<?php
/**
 * @package     Content
 * @subpackage  Category\services
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 03 28
 * @since       PHPBoost 6.0 - 2019 11 02
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CategoriesAuthorizationsService
{
	public $id_category;
	public $module_id;

	public static function check_authorizations($id_category = Category::ROOT_CATEGORY, $module_id = '')
	{
		$class_name = get_called_class();
		$instance = new $class_name();
		$instance->id_category = $id_category;
		$instance->module_id = $module_id;
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

	public function manage()
	{
		return $this->is_authorized(Category::CATEGORIES_MANAGEMENT_AUTHORIZATIONS);
	}

	protected function is_authorized($bit, $mode = Authorizations::AUTH_CHILD_PRIORITY)
	{
		$auth = CategoriesService::get_categories_manager($this->module_id)->get_heritated_authorizations($this->id_category, $bit, $mode);
		return AppContext::get_current_user()->check_auth($auth, $bit);
	}
}
?>
