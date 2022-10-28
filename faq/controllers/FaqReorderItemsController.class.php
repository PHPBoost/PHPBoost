<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 01 19
 * @since       PHPBoost 4.0 - 2014 08 02
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FaqReorderItemsController extends DefaultModuleController
{
	private $category;

	protected function get_template_to_use()
	{
		return new FileTemplate('faq/FaqReorderItemsController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		if ($request->get_value('submit', false))
		{
			$this->update_position($request);
			AppContext::get_response()->redirect(FaqUrlBuilder::display_category($this->get_category()->get_id(), $this->get_category()->get_rewrited_name()), $this->lang['warning.success.position.update']);
		}

		$this->build_view($request);

		return $this->generate_response();
	}

	private function build_view(HTTPRequestCustom $request)
	{
		$result = PersistenceContext::get_querier()->select('SELECT *
		FROM '. FaqSetup::$faq_table .' faq
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = faq.author_user_id
		WHERE approved = 1
		AND faq.id_category = :id_category
		ORDER BY q_order ASC', array(
			'id_category' => $this->get_category()->get_id()
		));

		$category_description = FormatingHelper::second_parse($this->get_category()->get_description());

		$this->view->put_all(array(
			'C_ROOT_CATEGORY'        => $this->get_category()->get_id() == Category::ROOT_CATEGORY,
			'C_HIDE_NO_ITEM_MESSAGE' => $this->get_category()->get_id() == Category::ROOT_CATEGORY && !empty($category_description),
			'C_CATEGORY_DESCRIPTION' => !empty($category_description),
			'C_ITEMS'                => $result->get_rows_count() > 0,
			'C_SEVERAL_ITEMS'        => $result->get_rows_count() > 1,

			'CATEGORY_ID'          => $this->get_category()->get_id(),
			'CATEGORY_NAME'        => $this->get_category()->get_name(),
			'CATEGORY_DESCRIPTION' => $category_description,
			'ITEMS_NUMBER'         => $result->get_rows_count(),

			'U_CATEGORY_THUMBNAIL' => $this->get_category()->get_thumbnail()->rel(),
			'U_EDIT_CATEGORY'      => $this->get_category()->get_id() == Category::ROOT_CATEGORY ? FaqUrlBuilder::configuration()->rel() : CategoriesUrlBuilder::edit($this->get_category()->get_id(), 'faq')->rel()
		));

		while ($row = $result->fetch())
		{
			$item = new FaqItem();
			$item->set_properties($row);

			$this->view->assign_block_vars('items', $item->get_template_vars());
		}
		$result->dispose();
	}

	private function get_category()
	{
		if ($this->category === null)
		{
			$id = AppContext::get_request()->get_getint('id_category', 0);
			if (!empty($id))
			{
				try {
					$this->category = CategoriesService::get_categories_manager()->get_categories_cache()->get_category($id);
				} catch (CategoryNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->category = CategoriesService::get_categories_manager()->get_categories_cache()->get_category(Category::ROOT_CATEGORY);
			}
		}
		return $this->category;
	}

	private function check_authorizations()
	{
		$id_category = $this->get_category()->get_id();
		if (!CategoriesAuthorizationsService::check_authorizations($id_category)->moderation())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function update_position(HTTPRequestCustom $request)
	{
		$questions_list = json_decode(TextHelper::html_entity_decode($request->get_value('tree')));
		foreach($questions_list as $position => $tree)
		{
			FaqService::update_position($tree->id, $position);
		}
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();

		if ($this->get_category()->get_id() != Category::ROOT_CATEGORY)
			$graphical_environment->set_page_title($this->get_category()->get_name(), $this->lang['faq.module.title']);
		else
			$graphical_environment->set_page_title($this->lang['faq.module.title']);

		$description = $this->get_category()->get_description() . ' ' . $this->lang['faq.questions.reorder'];
		if (empty($description))
			$description = StringVars::replace_vars($this->lang['faq.seo.description.root'], array('site' => GeneralConfig::load()->get_site_name())) . ($this->get_category()->get_id() != Category::ROOT_CATEGORY ? ' ' . $this->lang['category.category'] . ' ' . $this->get_category()->get_name() : '') . ' ' . $this->lang['faq.questions.reorder'];
		$graphical_environment->get_seo_meta_data()->set_description($description);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(FaqUrlBuilder::display_category($this->get_category()->get_id(), $this->get_category()->get_rewrited_name()));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['faq.module.title'], FaqUrlBuilder::home());

		$categories = array_reverse(CategoriesService::get_categories_manager()->get_parents($this->get_category()->get_id(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), FaqUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}

		$breadcrumb->add($this->lang['faq.questions.reorder'], FaqUrlBuilder::reorder_items($this->get_category()->get_id(), $this->get_category()->get_rewrited_name()));

		return $response;
	}
}
?>
