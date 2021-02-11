<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 02 11
 * @since       PHPBoost 5.2 - 2013 06 14
*/

class NewsMemberItemsController extends ModuleController
{
	private $view;
	private $lang;
	private $config;
	private $user;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$user_id = $request->get_getint('user_id', AppContext::get_current_user()->get_id());
		try {
			$this->user = PersistenceContext::get_querier()->select_single_row(PREFIX . 'member', array('*'), 'WHERE user_id=:user_id', array('user_id' => $user_id));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_element();
			DispatchManager::redirect($error_controller);
		}

		$this->build_view($this->user['user_id']);

		return $this->generate_response();
	}

	public function init()
	{
		$common_lang = LangLoader::get('common');
		$this->lang = LangLoader::get('common', 'news');
		$this->view = new FileTemplate('news/NewsSeveralItemsController.tpl');
		$this->view->add_lang(array_merge($this->lang, $common_lang));
		$this->config = NewsConfig::load();
	}

	public function build_view($user_id)
	{
		$now = new Date();
		$comments_config = CommentsConfig::load();
		$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, $this->config->is_summary_displayed_to_guests());

		$condition = 'WHERE id_category IN :authorized_categories
		AND author_user_id = :user_id
		AND (published = 1 OR (published = 2 AND (publishing_start_date > :timestamp_now OR (publishing_end_date != 0 AND publishing_end_date < :timestamp_now))))';
		$parameters = array(
			'authorized_categories' => $authorized_categories,
			'user_id' => $this->user['user_id'],
			'timestamp_now' => $now->get_timestamp()
		);

		$page = AppContext::get_request()->get_getint('page', 1);
		$pagination = $this->get_pagination($condition, $parameters, $page);

		$result = PersistenceContext::get_querier()->select('SELECT news.*, member.*
		FROM '. NewsSetup::$news_table .' news
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = news.author_user_id
		' . $condition . '
		ORDER BY top_list_enabled DESC, news.update_date DESC
		LIMIT :number_items_per_page OFFSET :display_from', array_merge($parameters, array(
			'number_items_per_page' => $pagination->get_number_items_per_page(),
			'display_from' => $pagination->get_display_from()
		)));


		$this->view->put_all(array(
			'C_MEMBER_ITEMS' => true,
			'C_MY_ITEMS' => $user_id == AppContext::get_current_user()->get_id(),
			'C_GRID_VIEW' => $this->config->get_display_type() == NewsConfig::GRID_VIEW,
			'C_LIST_VIEW' => $this->config->get_display_type() == NewsConfig::LIST_VIEW,
			'C_FULL_ITEM_DISPLAY' => $this->config->get_full_item_display(),
			'C_COMMENTS_ENABLED' => $comments_config->module_comments_is_enabled('news'),

			'C_NO_ITEM' => $result->get_rows_count() == 0,
			'C_PAGINATION' => $pagination->has_several_pages(),
			'PAGINATION' => $pagination->display(),

			'ITEMS_PER_ROW' => $this->config->get_items_per_row(),
			'MEMBER_NAME' => $this->user['display_name']
		));

		while ($row = $result->fetch())
		{
			$item = new NewsItem();
			$item->set_properties($row);

			$this->view->assign_block_vars('items', $item->get_array_tpl_vars());

			foreach ($item->get_sources() as $name => $url)
			{
				$this->view->assign_block_vars('items.sources', $item->get_array_tpl_source_vars($name));
			}
		}
		$result->dispose();
	}

	private function get_pagination($condition, $parameters, $page)
	{
		$items_number = NewsService::count($condition, $parameters);

		$pagination = new ModulePagination($page, $items_number, (int)NewsConfig::load()->get_items_per_page());
		$pagination->set_url(NewsUrlBuilder::display_member_items('%d'));

		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		return $pagination;
	}

	private function check_authorizations()
	{
		if (!(CategoriesAuthorizationsService::check_authorizations()->write() || CategoriesAuthorizationsService::check_authorizations()->contribution() || CategoriesAuthorizationsService::check_authorizations()->moderation()))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response()
	{
		$page = AppContext::get_request()->get_getint('page', 1);
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		if($this->user['user_id'] == AppContext::get_current_user()->get_id())
			$graphical_environment->set_page_title($this->lang['my.items'], $this->lang['module.title'], $page);
		else
			$graphical_environment->set_page_title($this->lang['member.items'] . ' ' . $this->user['display_name'], $this->lang['module.title'], $page);
		$graphical_environment->get_seo_meta_data()->set_description(StringVars::replace_vars($this->lang['news.seo.description.member'], array('author' => AppContext::get_current_user()->get_display_name())), $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(NewsUrlBuilder::display_member_items($page));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module.title'], NewsUrlBuilder::home());
		if($this->user['user_id'] == AppContext::get_current_user()->get_id())
			$breadcrumb->add($this->lang['my.items'], NewsUrlBuilder::display_member_items($page));
		else
			$breadcrumb->add($this->lang['member.items'] . ' ' . $this->user['display_name'], NewsUrlBuilder::display_member_items($page));

		return $response;
	}
}
?>
