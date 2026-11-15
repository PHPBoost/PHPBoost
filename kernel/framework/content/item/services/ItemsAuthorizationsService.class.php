<?php
/**
 * @package     Content
 * @subpackage  Item\services
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 09
 * @since       PHPBoost 6.0 - 2020 01 01
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ItemsAuthorizationsService
{
	public string $module_id = '';

	public static function check_authorizations(string $module_id = ''): mixed
	{
		$class_name = get_called_class();
		$instance = new $class_name();
		$instance->module_id = $module_id;
		return $instance;
	}

	public function read(): bool
	{
		return $this->is_authorized(Item::READ_AUTHORIZATIONS);
	}

	public function contribution(): bool
	{
		return $this->is_authorized(Item::CONTRIBUTION_AUTHORIZATIONS);
	}

	public function duplication(): bool
	{
		return $this->is_authorized(Item::DUPLICATION_AUTHORIZATIONS);
	}

	public function write(): bool
	{
		return $this->is_authorized(Item::WRITE_AUTHORIZATIONS);
	}

	public function moderation(): bool
	{
		return $this->is_authorized(Item::MODERATION_AUTHORIZATIONS);
	}

	protected function is_authorized(int $bit): bool
	{
		$auth = ItemsService::get_items_manager($this->module_id)->get_heritated_authorizations();
		return AppContext::get_current_user()->check_auth($auth, $bit);
	}
}
?>
