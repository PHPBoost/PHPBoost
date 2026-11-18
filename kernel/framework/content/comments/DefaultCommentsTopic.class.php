<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 08
 * @since       PHPBoost 6.0 - 2020 01 10
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DefaultCommentsTopic extends CommentsTopic
{
	protected ?Module $module = null;
	protected ?Item $item = null;

	public function __construct(string $module_id, ?Item $item = null, $url = null)
	{
		parent::__construct($module_id);
		$this->module = ModulesManager::get_module($module_id);

		if ($item)
		{
			$this->item = $item;
			$this->id_in_module = $item->get_id();
		}

		if ($url)
        {
			$this->url = $url;
        }
	}

	public function get_authorizations(): CommentsAuthorizations
	{
		$module_authorizations = $this->module->get_configuration()->has_categories() ? CategoriesAuthorizationsService::check_authorizations($this->get_item()->get_id_category(), $this->module_id) : ItemsAuthorizationsService::check_authorizations($this->module_id);

		$authorizations = new CommentsAuthorizations();
		$authorizations->set_authorized_access_module($module_authorizations->read());
		return $authorizations;
	}

	public function is_displayed(): bool
	{
		return $this->get_item()->is_published();
	}

	protected function get_item(): Item
	{
		if ($this->item === null)
		{
			$this->item = $this->get_item_from_manager();
		}
		return $this->item;
	}

	protected function get_item_from_manager(): Item
	{
		return ItemsService::get_items_manager($this->module_id)->get_item($this->get_id_in_module());
	}
}
?>
