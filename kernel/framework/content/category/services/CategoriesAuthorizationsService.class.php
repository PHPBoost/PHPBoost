<?php
/**
 * @package     Content
 * @subpackage  Category\services
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 27
 * @since       PHPBoost 6.0 - 2019 11 02
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CategoriesAuthorizationsService
{
	public int $id_category = 0;
	public string $module_id = '';

	public static function check_authorizations(int $id_category = Category::ROOT_CATEGORY, string $module_id = ''): mixed
	{
		$class_name = get_called_class();
		$instance = new $class_name();
		$instance->id_category = $id_category;
		$instance->module_id = $module_id;
		return $instance;
	}

	public function read(): int
	{
		return $this->is_authorized(Category::READ_AUTHORIZATIONS, Authorizations::AUTH_PARENT_PRIORITY);
	}

	public function contribution(): int
	{
		return $this->is_authorized(Category::CONTRIBUTION_AUTHORIZATIONS);
	}

	public function duplication(): int
	{
		return $this->is_authorized(Category::DUPLICATION_AUTHORIZATIONS);
	}

	public function write(): int
	{
		return $this->is_authorized(Category::WRITE_AUTHORIZATIONS);
	}

	public function moderation(): int
	{
		return $this->is_authorized(Category::MODERATION_AUTHORIZATIONS);
	}

	public function manage(): int
	{
		return $this->is_authorized(Category::CATEGORIES_MANAGEMENT_AUTHORIZATIONS);
	}

	protected function is_authorized(int $bit, int $mode = Authorizations::AUTH_CHILD_PRIORITY): int
	{
		$auth = CategoriesService::get_categories_manager($this->module_id)->get_heritated_authorizations($this->id_category, $bit, $mode);
		return AppContext::get_current_user()->check_auth($auth, $bit);
	}
}
?>
