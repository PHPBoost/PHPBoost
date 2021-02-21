<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 02 05
 * @since       PHPBoost 5.2 - 2020 06 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class PagesCategoryController extends ModuleController
{
	private $lang;
	private $config;
	private $comments_config;
	private $notation_config;
	private $category;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->check_authorizations();

		$this->build_view();

		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'pages');
		$this->view = new FileTemplate('pages/PagesSeveralItemsController.tpl');
		$this->view->add_lang($this->lang);
		$this->config = PagesConfig::load();
	}

	private function build_view()
	{
		$now = new Date();
		$request = AppContext::get_request();

		$this->build_categories_listing_view($now);
		$this->build_items_listing_view($now);
	}

	private function build_items_listing_view(Date $now)
	{
		$condition = 'WHERE id_category = :id_category
		AND (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)))';
		$parameters = array(
			'id_category' => $this->get_category()->get_id(),
			'timestamp_now' => $now->get_timestamp()
		);

		$result = PersistenceContext::get_querier()->select('SELECT pages.*, member.*
		FROM ' . PagesSetup::$pages_table . ' pages
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = pages.author_user_id
		' . $condition . '
		ORDER BY i_order', array_merge($parameters, array(
			'user_id' => AppContext::get_current_user()->get_id()
		)));

		$this->view->put_all(array(
			'C_CONTROLS' => AppContext::get_current_user()->get_level() == User::ADMIN_LEVEL,
			'C_NO_ITEM' => $result->get_rows_count() == 0,
			'C_ITEMS' => $result->get_rows_count() > 0,
			'C_CATEGORY_THUMBNAIL' => !empty($this->get_category()->get_thumbnail()->rel()),
			'C_SEVERAL_ITEMS' => $result->get_rows_count() > 1,
			'CATEGORY_ID' => $this->get_category()->get_id(),
			'CATEGORY_NAME' => $this->get_category()->get_name(),
			'U_CATEGORY_THUMBNAIL' => $this->get_category()->get_thumbnail()->rel(),
			'U_EDIT_CATEGORY' => $this->get_category()->get_id() == Category::ROOT_CATEGORY ? PagesUrlBuilder::configuration()->rel() : CategoriesUrlBuilder::edit_category($this->get_category()->get_id())->rel(),
			'U_REORDER_ITEMS' => PagesUrlBuilder::reorder_items($this->get_category()->get_id(),$this->get_category()->get_rewrited_name())->rel()
		));

		while($row = $result->fetch())
		{
			$item = new PagesItem();
			$item->set_properties($row);

			$this->view->assign_block_vars('items', $item->get_template_vars());
		}
		$result->dispose();
	}

	private function build_categories_listing_view(Date $now)
	{
		$subcategories = CategoriesService::get_categories_manager('pages')->get_categories_cache()->get_children($this->get_category()->get_id(), CategoriesService::get_authorized_categories($this->get_category()->get_id(), true, 'pages'));
		$categories_number_displayed = 0;
		foreach ($subcategories as $id => $category)
		{
			$categories_number_displayed++;

			$this->view->assign_block_vars('sub_categories_list', array(
				'C_SEVERAL_ITEMS' => $category->get_elements_number() > 1,
				'CATEGORY_ID' => $category->get_id(),
				'CATEGORY_NAME' => $category->get_name(),
				'ITEMS_NUMBER' => $category->get_elements_number(),
				'U_CATEGORY' => PagesUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())->rel()
			));
		}

		$category_description = FormatingHelper::second_parse($this->get_category()->get_description());

		$this->view->put_all(array(
			'C_CATEGORIES' => true,
			'C_NO_ITEM_MESSAGE' => $this->get_category()->get_id() == Category::ROOT_CATEGORY && ($categories_number_displayed != 0 || !empty($category_description)),
			'C_CATEGORY_DESCRIPTION' => !empty($category_description) || !empty($category_thumbnail),
			'C_SUB_CATEGORIES' => $categories_number_displayed > 0,
			'CATEGORY_NAME' => $this->get_category()->get_name(),
			'CATEGORY_DESCRIPTION' => $category_description,
			'CATEGORY_IMAGE' => $this->get_category()->get_thumbnail()->rel(),
			'ITEMS_NUMBER' => $this->get_category()->get_elements_number(),
		));
	}

	private function get_category()
	{
		if ($this->category === null)
		{
			$id = AppContext::get_request()->get_getstring('id_category', 0);
			if (!empty($id))
			{
				try {
					$this->category = CategoriesService::get_categories_manager('pages')->get_categories_cache()->get_category($id);
				} catch (CategoryNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->category = CategoriesService::get_categories_manager('pages')->get_categories_cache()->get_category(Category::ROOT_CATEGORY);
			}
		}
		return $this->category;
	}

	private function check_authorizations()
	{
		if (AppContext::get_current_user()->is_guest())
		{
			if ((!Authorizations::check_auth(RANK_TYPE, User::MEMBER_LEVEL, $this->get_category()->get_authorizations(), Category::READ_AUTHORIZATIONS)) || (!CategoriesAuthorizationsService::check_authorizations($this->get_category()->get_id())->read()))
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!CategoriesAuthorizationsService::check_authorizations($this->get_category()->get_id())->read())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();

		if ($this->category->get_id() != Category::ROOT_CATEGORY)
			$graphical_environment->set_page_title($this->category->get_name(), $this->lang['module.title']);
		else
			$graphical_environment->set_page_title($this->lang['module.title']);

		$graphical_environment->get_seo_meta_data()->set_description($this->category->get_description());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(PagesUrlBuilder::display_category($this->category->get_id(), $this->category->get_rewrited_name(),  AppContext::get_request()->get_getint('page', 1)));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module.title'], PagesUrlBuilder::home());

		$categories = array_reverse(CategoriesService::get_categories_manager('pages')->get_parents($this->category->get_id(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), PagesUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name(), AppContext::get_request()->get_getint('page', 1)));
		}

		return $response;
	}

	public static function get_view()
	{
		$object = new self();
		$object->init();
		$object->check_authorizations();
		$object->build_view();
		return $object->view;
	}
}
?>
