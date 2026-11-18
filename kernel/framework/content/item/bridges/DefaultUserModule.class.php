<?php
/**
 * @package     Content
 * @subpackage  Item\bridges
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 09
 * @since       PHPBoost 6.0 - 2021 02 17
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DefaultUserModule implements UserExtensionPoint
{
	protected string $module_id = '';

	public function __construct(string $module_id)
	{
		$this->module_id = $module_id;
	}

	public function get_publications_module_view(int $user_id): string
	{
		return ItemsUrlBuilder::display_member_items($user_id, $this->module_id)->rel();
	}

	public function get_publications_module_name(): string
	{
		return ModulesManager::get_module($this->module_id)->get_configuration()->get_name();
	}

	public function get_publications_module_id(): string
	{
		return $this->module_id;
	}

	public function get_publications_module_icon(): string
	{
		return '';
	}

	public function get_publications_number(int $user_id): int
	{
		return ItemsService::get_items_manager($this->module_id)->count('WHERE author_user_id = :user_id', ['user_id' => $user_id]);
	}
}
?>
