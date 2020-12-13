<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 13
 * @since       PHPBoost 4.0 - 2013 02 13
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class NewsPendingItemsController extends ModuleController
{
	private $view;
	private $lang;
	private $config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$this->build_view();

		return $this->generate_response();
	}

	public function init()
	{
		$this->lang = LangLoader::get('common', 'news');
		$this->view = new FileTemplate('news/NewsSeveralItemsController.tpl');
		$this->view->add_lang($this->lang);
		$this->config = NewsConfig::load();
	}

	public function build_view()
	{
		$now = new Date();
		$comments_config = CommentsConfig::load();
		$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, $this->config->is_summary_displayed_to_guests());

		$condition = 'WHERE id_category IN :authorized_categories
		' . (!CategoriesAuthorizationsService::check_authorizations()->moderation() ? ' AND author_user_id = :user_id' : '') . '
		AND (published = 0 OR (published = 2 AND (publishing_start_date > :timestamp_now OR (publishing_end_date != 0 AND publishing_end_date < :timestamp_now))))';
		$parameters = array(
			'authorized_categories' => $authorized_categories,
			'user_id' => AppContext::get_current_user()->get_id(),
			'timestamp_now' => $now->get_timestamp()
		);

		$page = AppContext::get_request()->get_getint('page', 1);
		$pagination = $this->get_pagination($condition, $parameters, $page);

		$result = PersistenceContext::get_querier()->select('SELECT news.*, member.*
		FROM '. NewsSetup::$news_table .' news
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = news.author_user_id
		' . $condition . '
		ORDER BY top_list_enabled DESC, news.creation_date DESC
		LIMIT :number_items_per_page OFFSET :display_from', array_merge($parameters, array(
			'number_items_per_page' => $pagination->get_number_items_per_page(),
			'display_from' => $pagination->get_display_from()
		)));

		$this->view->put_all(array(
			'C_PENDING_ITEMS' => true,
			'C_GRID_VIEW' => $this->config->get_display_type() == NewsConfig::GRID_VIEW,
			'C_LIST_VIEW' => $this->config->get_display_type() == NewsConfig::LIST_VIEW,
			'C_FULL_ITEM_DISPLAY' => $this->config->get_full_item_display(),
			'C_COMMENTS_ENABLED' => $comments_config->module_comments_is_enabled('news'),

			'C_NO_ITEM' => $result->get_rows_count() == 0,
			'C_PAGINATION' => $pagination->has_several_pages(),
			'PAGINATION' => $pagination->display(),

			'ITEMS_PER_ROW' => $this->config->get_items_per_row()
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
		$pagination->set_url(NewsUrlBuilder::display_pending_items('%d'));

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
		$graphical_environment->set_page_title($this->lang['news.pending.items'], $this->lang['module.title'], $page);
		$graphical_environment->get_seo_meta_data()->set_description($this->lang['news.seo.description.pending'], $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(NewsUrlBuilder::display_pending_items($page));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module.title'], NewsUrlBuilder::home());
		$breadcrumb->add($this->lang['news.pending.items'], NewsUrlBuilder::display_pending_items($page));

		return $response;
	}
}
?>
