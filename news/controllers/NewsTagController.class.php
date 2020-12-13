<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 13
 * @since       PHPBoost 4.0 - 2013 02 16
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class NewsTagController extends ModuleController
{
	private $view;
	private $lang;
	private $config;

	/**
	 * @var NewsKeyword
	 */
	private $keyword;

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

		$condition = 'WHERE relation.id_keyword = :id_keyword
		AND id_category IN :authorized_categories
		AND (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)))';
		$parameters = array(
			'id_keyword' => $this->get_keyword()->get_id(),
			'authorized_categories' => $authorized_categories,
			'timestamp_now' => $now->get_timestamp()
		);

		$page = AppContext::get_request()->get_getint('page', 1);
		$pagination = $this->get_pagination($condition, $parameters, $page);

		$result = PersistenceContext::get_querier()->select('SELECT news.*, member.*
		FROM '. NewsSetup::$news_table .' news
		LEFT JOIN '. DB_TABLE_KEYWORDS_RELATIONS .' relation ON relation.module_id = \'news\' AND relation.id_in_module = news.id
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = news.author_user_id
		' . $condition . '
		ORDER BY top_list_enabled DESC, news.creation_date DESC
		LIMIT :number_items_per_page OFFSET :display_from', array_merge($parameters, array(
			'number_items_per_page' => $pagination->get_number_items_per_page(),
			'display_from' => $pagination->get_display_from()
		)));

		$this->view->put_all(array(
			'C_GRID_VIEW' => $this->config->get_display_type() == NewsConfig::GRID_VIEW,
			'C_LIST_VIEW' => $this->config->get_display_type() == NewsConfig::LIST_VIEW,
			'C_FULL_ITEM_DISPLAY' => $this->config->get_full_item_display(),
			'C_COMMENTS_ENABLED' => $comments_config->module_comments_is_enabled('news'),
			'CATEGORY_NAME' => $this->get_keyword()->get_name(),

			'C_NO_ITEM' => $result->get_rows_count() == 0,
			'C_PAGINATION' => $pagination->has_several_pages(),
			'PAGINATION' => $pagination->display(),

			'ITEMS_PER_ROW' => $this->config->get_items_per_row()
		));

		while ($row = $result->fetch())
		{
			$item = new NewsItem();
			$item->set_properties($row);

			$this->view->assign_block_vars('items', array_merge($item->get_array_tpl_vars(), array(
				'L_COMMENTS' => CommentsService::get_number_and_lang_comments('news', $row['id']),
				'NUMBER_COM' => !empty($row['number_comments']) ? $row['number_comments'] : 0
			)));

			foreach ($item->get_sources() as $name => $url)
			{
				$this->view->assign_block_vars('items.sources', $item->get_array_tpl_source_vars($name));
			}
		}
		$result->dispose();
	}

	private function get_keyword()
	{
		if ($this->keyword === null)
		{
			$rewrited_name = AppContext::get_request()->get_getstring('tag', '');
			if (!empty($rewrited_name))
			{
				try {
					$this->keyword = KeywordsService::get_keywords_manager()->get_keyword('WHERE rewrited_name=:rewrited_name', array('rewrited_name' => $rewrited_name));
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}
		}
		return $this->keyword;
	}

	private function get_pagination($condition, $parameters, $page)
	{
		$result = PersistenceContext::get_querier()->select_single_row_query('SELECT COUNT(*) AS items_number
		FROM '. NewsSetup::$news_table .' news
		LEFT JOIN '. DB_TABLE_KEYWORDS_RELATIONS .' relation ON relation.module_id = \'news\' AND relation.id_in_module = news.id
		' . $condition, $parameters);

		$pagination = new ModulePagination($page, $result['items_number'], (int)NewsConfig::load()->get_items_per_page());
		$pagination->set_url(NewsUrlBuilder::display_tag($this->get_keyword()->get_rewrited_name(), '%d'));

		if ($pagination->current_page_is_empty() && $page > 1)
        {
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		return $pagination;
	}

	private function check_authorizations()
	{
		if (!CategoriesAuthorizationsService::check_authorizations()->read())
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
		$graphical_environment->set_page_title($this->get_keyword()->get_name(), $this->lang['module.title'], $page);
		$graphical_environment->get_seo_meta_data()->set_description(StringVars::replace_vars($this->lang['news.seo.description.tag'], array('subject' => $this->get_keyword()->get_name())), $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(NewsUrlBuilder::display_tag($this->get_keyword()->get_rewrited_name(), $page));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module.title'], NewsUrlBuilder::home());
		$breadcrumb->add($this->get_keyword()->get_name(), NewsUrlBuilder::display_tag($this->get_keyword()->get_rewrited_name(), $page));

		return $response;
	}
}
?>
