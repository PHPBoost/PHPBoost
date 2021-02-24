<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 02 25
 * @since       PHPBoost 5.2 - 2020 06 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class PagesMemberItemsController extends ModuleController
{
	private $lang;
	private $view;
	private $category;
	private $config;
	private $comments_config;
	private $content_management_config;
	private $member;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->check_authorizations();

		$this->build_view($request);

		return $this->generate_response($request);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'pages');
		$this->view = new FileTemplate('pages/PagesSeveralItemsController.tpl');
		$this->view->add_lang($this->lang);
		$this->config = PagesConfig::load();

		$this->comments_config = CommentsConfig::load();
		$this->content_management_config = ContentManagementConfig::load();
	}

	private function build_view(HTTPRequestCustom $request)
	{
		$now = new Date();

		$authorized_categories = CategoriesService::get_authorized_categories($this->get_category()->get_id());

		$condition = 'WHERE id_category IN :authorized_categories
		AND pages.author_user_id = :user_id
		AND (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)))';
		$parameters = array(
			'authorized_categories' => $authorized_categories,
			'timestamp_now' => $now->get_timestamp(),
			'user_id' => $this->get_member()->get_id()
		);

		$result = PersistenceContext::get_querier()->select('SELECT pages.*, member.*, com.number_comments
		FROM ' . PagesSetup::$pages_table . ' pages
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = pages.author_user_id
		LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = pages.id AND com.module_id = \'pages\'
		' . $condition . '
		ORDER BY pages.update_date DESC
		', array_merge($parameters, array(
			'user_id' => $this->get_member()->get_id()
		)));

		$this->view->put_all(array(
			'C_NO_ITEM'       => $result->get_rows_count() == 0,
			'C_ITEMS'         => $result->get_rows_count() > 0,
			'C_MEMBER_ITEMS'  => true,
			'C_MY_ITEMS'      => $this->is_current_member_displayed(),
			'C_ITEMS'         => $result->get_rows_count() > 0,
			'C_SEVERAL_ITEMS' => $result->get_rows_count() > 1,
			'C_VIEWS_NUMBER'  => $this->config->get_views_number_enabled(),

			'CATEGORY_ID'     => $this->get_category()->get_id(),
			'CATEGORY_NAME'   => $this->get_category()->get_name(),
			'MEMBER_NAME'     => $this->get_member()->get_display_name()
		));

		while($row = $result->fetch())
		{
			$item = new PagesItem();
			$item->set_properties($row);

			$this->view->assign_block_vars('items', $item->get_template_vars());
			$this->build_sources_view($item);
			$this->build_keywords_view($item);
		}
		$result->dispose();
	}

	protected function get_member()
	{
		if ($this->member === null)
		{
			$this->member = UserService::get_user(AppContext::get_request()->get_getint('user_id', AppContext::get_current_user()->get_id()));
			if (!$this->member)
				DispatchManager::redirect(PHPBoostErrors::unexisting_element());
		}
		return $this->member;
	}

	protected function is_current_member_displayed()
	{
		return $this->member && $this->member->get_id() == AppContext::get_current_user()->get_id();
	}

	private function build_sources_view(PagesItem $item)
	{
		$sources = $item->get_sources();
		$nbr_sources = count($sources);
		if ($nbr_sources)
		{
			$this->view->put('items.C_SOURCES', $nbr_sources > 0);

			$i = 1;
			foreach ($sources as $name => $url)
			{
				$this->view->assign_block_vars('items.sources', array(
					'C_SEPARATOR' => $i < $nbr_sources,
					'NAME'        => $name,
					'URL'         => $url,
				));
				$i++;
			}
		}
	}

	private function get_category()
	{
		if ($this->category === null)
		{
			$id = AppContext::get_request()->get_getstring('id_category', 0);
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

	private function build_keywords_view(PagesItem $item)
	{
		$keywords = $item->get_keywords();
		$keywords_number = count($keywords);
		$this->view->put('C_KEYWORDS', $keywords_number > 0);

		$i = 1;
		foreach ($keywords as $keyword)
		{
			$this->view->assign_block_vars('keywords', array(
				'C_SEPARATOR' => $i < $keywords_number,
				'NAME'        => $keyword->get_name(),
				'URL'         => PagesUrlBuilder::display_tag($keyword->get_rewrited_name())->rel(),
			));
			$i++;
		}
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

	private function generate_response(HTTPRequestCustom $request)
	{
		$page_title = $this->is_current_member_displayed() ? $this->lang['my.items'] : $this->lang['member.items'] . ' ' . $this->get_member()->get_display_name();
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($page_title, $this->lang['my.items'], $this->lang['module.title']);
		$graphical_environment->get_seo_meta_data()->set_description(StringVars::replace_vars($this->lang['pages.seo.description.member'], array('author' => AppContext::get_current_user()->get_display_name())));
		$graphical_environment->get_seo_meta_data()->set_canonical_url(PagesUrlBuilder::display_member_items($this->get_member()->get_id()));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module.title'], PagesUrlBuilder::home());
		$breadcrumb->add($page_title, PagesUrlBuilder::display_member_items($this->get_member()->get_id()));

		$categories = array_reverse(CategoriesService::get_categories_manager()->get_parents($this->category->get_id(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), PagesUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}

		return $response;
	}

	public static function get_view()
	{
		$object = new self();
		$object->init();
		$object->check_authorizations();
		$object->build_view(AppContext::get_request());
		return $object->view;
	}
}
?>
