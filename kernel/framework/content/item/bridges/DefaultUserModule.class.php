<?php
/**
 * @package     Content
 * @subpackage  Item\bridges
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 02 17
 * @since       PHPBoost 6.0 - 2021 02 17
*/

class DefaultUserModule implements UserExtensionPoint
{
	/**
	 * @var string the module identifier
	 */
	protected $module_id;

	public function __construct($module_id)
	{
		$this->module_id = $module_id;
	}

	public function get_publications_module_view($user_id)
	{
		return ItemsUrlBuilder::display_member_items($user_id, $this->module_id)->rel();
	}

	public function get_publications_module_name()
	{
		return ModulesManager::get_module($this->module_id)->get_configuration()->get_name();
	}

	public function get_publications_module_id()
	{
		return $this->module_id;
	}

	public function get_publications_module_icon()
	{
		return '';
	}

	public function get_publications_number($user_id)
	{
		return ItemsService::get_items_manager($this->module_id)->count('WHERE author_user_id = :user_id', array('user_id' => $user_id));
	}
}
?>
