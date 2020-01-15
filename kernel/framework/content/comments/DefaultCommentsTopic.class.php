<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 15
 * @since       PHPBoost 5.3 - 2020 01 10
*/

class DefaultCommentsTopic extends CommentsTopic
{
	protected $module_id;
	protected $module;
	protected $item;

	public function __construct($module_id, Item $item = null)
	{
		parent::__construct($module_id);
		$this->module_id = $module_id;
		$this->module = ModulesManager::get_module($module_id);
		$this->item = $item;
	}

	public function get_authorizations()
	{
		$module_authorizations = $this->module->get_configuration()->has_categories() ? CategoriesAuthorizationsService::check_authorizations($this->get_item()->get_id_category(), $this->module_id) : ItemsAuthorizationsService::check_authorizations($this->module_id);
		
		$authorizations = new CommentsAuthorizations();
		$authorizations->set_authorized_access_module($module_authorizations->read());
		return $authorizations;
	}

	public function is_display()
	{
		return $this->get_item()->is_published();
	}

	protected function get_item()
	{
		if ($this->item === null)
		{
			$this->item = ItemsService::get_items_manager($this->module_id)->get_item($this->get_id_in_module());
		}
		return $this->item;
	}
}
?>
