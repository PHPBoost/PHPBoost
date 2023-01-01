<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 11 30
 * @since       PHPBoost 5.2 - 2020 06 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class PagesReorderItemsController extends DefaultSeveralItemsController
{
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->check_authorizations();

		if ($request->get_value('submit', false))
		{
			$this->update_position($request);
			AppContext::get_response()->redirect(ItemsUrlBuilder::display_category($this->get_category()->get_id(), $this->get_category()->get_rewrited_name(), self::$module_id), $this->lang['warning.success.position.update']);
		}

		$this->build_view();

		return $this->generate_response();
	}

	protected function init()
	{
		parent::init();
		$this->customized_page_title = $this->lang['items.reordering'];
		$this->page_description = '';
		$this->current_url = ItemsUrlBuilder::specific_page('reorder', self::$module_id, $this->category->get_id() != Category::ROOT_CATEGORY ? array($this->get_category()->get_id() . '-' . $this->get_category()->get_rewrited_name()) : array());
	}

	protected function build_view()
	{
		$items_number = 0;
		foreach (self::get_items_manager()->get_items($this->sql_condition, $this->sql_parameters) as $item)
		{
			$this->view->assign_block_vars('items', $item->get_template_vars());
			$items_number++;
		}

		$category_thumbnail = $this->get_category()->get_thumbnail()->rel();
		$this->view->put_all(array(
			'C_ITEMS'              => $items_number > 0,
			'C_SEVERAL_ITEMS'      => $items_number > 1,
			'C_CATEGORY_THUMBNAIL' => !empty($category_thumbnail),
			'CATEGORY_ID'          => $this->get_category()->get_id(),
			'CATEGORY_NAME'	       => $this->get_category()->get_name(),
			'ITEMS_NUMBER'         => $items_number,
			'CATEGORY_DESCRIPTION' => FormatingHelper::second_parse($this->get_category()->get_description()),
			'U_CATEGORY_THUMBNAIL' => $category_thumbnail,
			'U_EDIT_CATEGORY'      => $this->get_category()->get_id() == Category::ROOT_CATEGORY ? ModulesUrlBuilder::configuration()->rel() : CategoriesUrlBuilder::edit($this->get_category()->get_id(), self::$module_id)->rel()
		));
	}

	protected function check_authorizations()
	{
		return CategoriesAuthorizationsService::check_authorizations($this->get_category()->get_id(), self::$module_id)->moderation() ? true : $this->display_user_not_authorized_page();
	}

	protected function get_template_to_use()
	{
		return new FileTemplate('pages/PagesReorderItemsController.tpl');
	}

	protected function update_position(HTTPRequestCustom $request)
	{
		foreach(json_decode(TextHelper::html_entity_decode($request->get_value('tree'))) as $position => $tree)
		{
			self::get_items_manager()->update_position($tree->id, $position);
		}
	}
}
?>
