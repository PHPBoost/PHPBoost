<?php
/**
 * @package     Content
 * @subpackage  Item\services
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 13
 * @since       PHPBoost 5.3 - 2020 01 01
*/

class ItemsAuthorizationsService
{
	public $module_id;

	public static function check_authorizations($module_id = '')
	{
		$class_name = get_called_class();
		$instance = new $class_name();
		$instance->module_id = $module_id;
		return $instance;
	}

	public function read()
	{
		return $this->is_authorized(Item::READ_AUTHORIZATIONS);
	}

	public function contribution()
	{
		return $this->is_authorized(Item::CONTRIBUTION_AUTHORIZATIONS);
	}

	public function write()
	{
		return $this->is_authorized(Item::WRITE_AUTHORIZATIONS);
	}

	public function moderation()
	{
		return $this->is_authorized(Item::MODERATION_AUTHORIZATIONS);
	}

	protected function is_authorized($bit)
	{
		$auth = ItemsService::get_items_manager($this->module_id)->get_heritated_authorizations();
		return AppContext::get_current_user()->check_auth($auth, $bit);
	}
}
?>
