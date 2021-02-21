<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 02 21
 * @since       PHPBoost 5.2 - 2020 06 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class PagesHomeController extends ModuleController
{
	private $lang;
	private $view;
	private $config;

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
		$this->view = new FileTemplate('pages/PagesHomeController.tpl');
		$this->view->add_lang($this->lang);
		$this->config = PagesConfig::load();
	}

	private function build_view()
	{
		$now = new Date();
		$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, true, 'pages');

		$this->view->put_all(array(
			'C_CONTROLS'             => AppContext::get_current_user()->get_level() == User::ADMIN_LEVEL,
			'C_CATEGORY_DESCRIPTION' => !empty($this->config->get_root_category_description()),
			'CATEGORY_DESCRIPTION'   => FormatingHelper::second_parse($this->config->get_root_category_description()),
			'TOTAL_ITEMS'            => ItemsService::get_items_manager('pages')->count()
		));

		// Root category pages
		$root_result = PersistenceContext::get_querier()->select('SELECT *
			FROM '. PagesSetup::$pages_table .' pages
			WHERE pages.id_category = 0
			AND (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)))
			ORDER BY pages.i_order', array(
				'timestamp_now' => $now->get_timestamp()
		));

		while ($row = $root_result->fetch())
		{
				$item = new PagesItem();
				$item->set_properties($row);

				$this->view->assign_block_vars('root_items', $item->get_template_vars());
		}
		$root_result->dispose();

		foreach (CategoriesService::get_categories_manager('pages')->get_categories_cache()->get_categories() as $id => $category)
		{
			if ($id != Category::ROOT_CATEGORY && in_array($id, $authorized_categories))
			{
				$result = PersistenceContext::get_querier()->select('SELECT *
					FROM '. PagesSetup::$pages_table .' pages
					WHERE id_category = :id_cat
					AND (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)))
					ORDER BY i_order', array(
						'id_cat' => $category->get_id(),
						'timestamp_now' => $now->get_timestamp()
				));

				$this->view->assign_block_vars('categories', array(
					'C_ITEMS'            => $category->get_elements_number() > 0,
					'C_SEVERAL_ITEMS'    => $category->get_elements_number() > 1,
					'ITEMS_NUMBER'       => $category->get_elements_number(),
					'CATEGORY_ID'        => $category->get_id(),
					'CATEGORY_SUB_ORDER' => $category->get_order(),
					'CATEGORY_PARENT_ID' => $category->get_id_parent(),
					'CATEGORY_NAME'      => $category->get_name(),
					'U_CATEGORY'         => PagesUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())->rel(),
					'U_REORDER_ITEMS'    => PagesUrlBuilder::reorder_items($category->get_id(), $category->get_rewrited_name())->rel()
				));
				while ($row = $result->fetch())
				{
					$item = new PagesItem();
					$item->set_properties($row);

					$this->view->assign_block_vars('categories.items', $item->get_template_vars());
				}
				$result->dispose();
			}
		}
	}

	private function get_category()
	{
		if ($this->category === null)
		{
			$id = AppContext::get_request()->get_getint('id_category', 0);
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
			if ((!Authorizations::check_auth(RANK_TYPE, User::MEMBER_LEVEL, $this->get_category()->get_authorizations(), Category::READ_AUTHORIZATIONS) || !CategoriesAuthorizationsService::check_authorizations($this->get_category()->get_id())->read()))
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
		$page = AppContext::get_request()->get_getint('page', 1);
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();

		if ($this->get_category()->get_id() != Category::ROOT_CATEGORY)
			$graphical_environment->set_page_title($this->get_category()->get_name(), $this->lang['items']);
		else
			$graphical_environment->set_page_title(TextHelper::ucfirst($this->lang['items']), '');

		$description = $this->get_category()->get_description();
		if (empty($description))
			$description = StringVars::replace_vars($this->lang['pages.seo.description.root'], array('site' => GeneralConfig::load()->get_site_name())) . ($this->get_category()->get_id() != Category::ROOT_CATEGORY ? ' ' . LangLoader::get_message('category', 'categories-common') . ' ' . $this->get_category()->get_name() : '');
		$graphical_environment->get_seo_meta_data()->set_description($description);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(PagesUrlBuilder::display_category($this->get_category()->get_id(), $this->get_category()->get_rewrited_name()));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['items'], PagesUrlBuilder::home());

		$categories = array_reverse(CategoriesService::get_categories_manager('pages')->get_parents($this->get_category()->get_id(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), PagesUrlBuilder::home());
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
