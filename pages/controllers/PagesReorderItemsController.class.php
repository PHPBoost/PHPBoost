<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 06 30
 * @since       PHPBoost 5.2 - 2020 06 15
*/

class PagesReorderItemsController extends ModuleController
{
	private $lang;
	private $common_lang;
	private $view;

	private $category;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		if ($request->get_value('submit', false))
		{
			$this->update_position($request);
			AppContext::get_response()->redirect(PagesUrlBuilder::display_category($this->get_category()->get_id(), $this->get_category()->get_rewrited_name()), LangLoader::get_message('message.success.position.update', 'status-messages-common'));
		}

		$this->build_view($request);

		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'pages');
		$this->common_lang = LangLoader::get('common');
		$this->view = new FileTemplate('pages/PagesReorderItemsController.tpl');
		$this->view->add_lang($this->lang);
		$this->view->add_lang($this->common_lang);
	}

	private function build_view(HTTPRequestCustom $request)
	{
		$now = new Date();
		$config = PagesConfig::load();

		$result = PersistenceContext::get_querier()->select('SELECT *
		FROM '. PagesSetup::$pages_table .' pages
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = pages.author_user_id
		WHERE id_category = :id_category
		AND (publication = 1 OR (publication = 2 AND start_date < :timestamp_now AND (end_date > :timestamp_now OR end_date = 0)))
		ORDER BY i_order ASC', array(
			'id_category' => $this->get_category()->get_id(),
			'timestamp_now' => $now->get_timestamp()
		));

		$category_description = FormatingHelper::second_parse($this->get_category()->get_description());

		$this->view->put_all(array(
			'C_ROOT_CATEGORY' => $this->get_category()->get_id() == Category::ROOT_CATEGORY,
			'C_SEVERAL_ITEMS' => $this->get_category()->get_id() == Category::ROOT_CATEGORY && !empty($category_description),
			'C_CATEGORY_DESCRIPTION' => !empty($category_description),
			'C_ITEMS' => $result->get_rows_count() > 0,
			'C_SEVERAL_ITEMS' => $result->get_rows_count() > 1,
			'ID_CAT' => $this->get_category()->get_id(),
			'CATEGORY_NAME' => $this->get_category()->get_name(),
			'U_CATEGORY_THUMBNAIL' => $this->get_category()->get_thumbnail()->rel(),
			'CATEGORY_DESCRIPTION' => $category_description,
			'U_EDIT_CATEGORY' => $this->get_category()->get_id() == Category::ROOT_CATEGORY ? PagesUrlBuilder::configuration()->rel() : CategoriesUrlBuilder::edit_category($this->get_category()->get_id())->rel(),
			'ITEMS_NUMBER' => $result->get_rows_count()
		));

		while ($row = $result->fetch())
		{
			$item = new Page();
			$item->set_properties($row);

			$this->view->assign_block_vars('items', $item->get_array_tpl_vars());
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
			PagesService::update_position($tree->id, $position);
		}
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();

		if ($this->get_category()->get_id() != Category::ROOT_CATEGORY)
			$graphical_environment->set_page_title($this->get_category()->get_name(), $this->lang['module.title']);
		else
			$graphical_environment->set_page_title($this->lang['module.title']);

		$description = $this->get_category()->get_description() . ' ' . $this->common_lang['reorder'];
		if (empty($description))
			$description = StringVars::replace_vars(LangLoader::get_message('pages.seo.description.root', 'common', 'pages'), array('site' => GeneralConfig::load()->get_site_name())) . ($this->get_category()->get_id() != Category::ROOT_CATEGORY ? ' ' . LangLoader::get_message('category', 'categories-common') . ' ' . $this->get_category()->get_name() : '') . ' ' . $this->common_lang['reorder'];
		$graphical_environment->get_seo_meta_data()->set_description($description);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(PagesUrlBuilder::display_category($this->get_category()->get_id(), $this->get_category()->get_rewrited_name()));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module.title'], PagesUrlBuilder::home());

		$categories = array_reverse(CategoriesService::get_categories_manager()->get_parents($this->get_category()->get_id(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), PagesUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}

		$breadcrumb->add($this->common_lang['reorder'], PagesUrlBuilder::reorder_items($this->get_category()->get_id(), $this->get_category()->get_rewrited_name()));

		return $response;
	}
}
?>
