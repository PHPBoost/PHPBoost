<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 03 10
 * @since       PHPBoost 4.0 - 2013 02 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class NewsDisplayNewsController extends ModuleController
{
	private $lang;
	private $tpl;

	private $news;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$this->count_views_number($request);

		$this->build_view();

		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'news');
		$this->tpl = new FileTemplate('news/NewsDisplayNewsController.tpl');
		$this->tpl->add_lang($this->lang);
	}

	private function get_news()
	{
		if ($this->news === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try {
					$this->news = NewsService::get_news('WHERE id=:id', array('id' => $id));
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
				$this->news = new News();
		}
		return $this->news;
	}

	private function count_views_number(HTTPRequestCustom $request)
	{
		if (!$this->news->is_visible())
		{
			$this->tpl->put('NOT_VISIBLE_MESSAGE', MessageHelper::display(LangLoader::get_message('element.not_visible', 'status-messages-common'), MessageHelper::WARNING));
		}
		else
		{
			if ($request->get_url_referrer() && !TextHelper::strstr($request->get_url_referrer(), NewsUrlBuilder::display_news($this->news->get_category()->get_id(), $this->news->get_category()->get_rewrited_name(), $this->news->get_id(), $this->news->get_rewrited_title())->rel()))
			{
				$this->news->set_views_number($this->news->get_views_number() + 1);
				NewsService::update_views_number($this->news);
			}
		}
	}

	private function build_view()
	{
		$news = $this->get_news();
		$news_config = NewsConfig::load();
		$comments_config = CommentsConfig::load();
		$category = $news->get_category();

		$this->tpl->put_all(array_merge($news->get_array_tpl_vars(), array(
			'C_COMMENTS_ENABLED' => $comments_config->module_comments_is_enabled('news'),
			'NOT_VISIBLE_MESSAGE' => MessageHelper::display(LangLoader::get_message('element.not_visible', 'status-messages-common'), MessageHelper::WARNING)
		)));

		if ($comments_config->module_comments_is_enabled('news'))
		{
			$comments_topic = new NewsCommentsTopic($news);
			$comments_topic->set_id_in_module($news->get_id());
			$comments_topic->set_url(NewsUrlBuilder::display_news($category->get_id(), $category->get_rewrited_name(), $news->get_id(), $news->get_rewrited_title()));

			$this->tpl->put_all(array(
				'COMMENTS' => $comments_topic->display()
			));
		}

		foreach ($news->get_sources() as $name => $url)
		{
			$this->tpl->assign_block_vars('sources', $news->get_array_tpl_source_vars($name));
		}

		$this->build_keywords_view($news);
		$this->build_suggested_news($news);
		$this->build_navigation_links($news);
	}

	private function build_keywords_view(News $news)
	{
		$keywords = $news->get_keywords();
		$nbr_keywords = count($keywords);
		$this->tpl->put('C_KEYWORDS', $nbr_keywords > 0);

		$i = 1;
		foreach ($keywords as $keyword)
		{
			$this->tpl->assign_block_vars('keywords', array(
				'C_SEPARATOR' => $i < $nbr_keywords,
				'NAME' => $keyword->get_name(),
				'URL' => NewsUrlBuilder::display_tag($keyword->get_rewrited_name())->rel(),
			));
			$i++;
		}
	}

	private function build_suggested_news(News $news)
	{
		$now = new Date();

		$result = PersistenceContext::get_querier()->select('SELECT
			id, name, id_category, rewrited_name, picture_url,
			(2 * FT_SEARCH_RELEVANCE(name, :search_content) + FT_SEARCH_RELEVANCE(contents, :search_content) / 3) AS relevance
		FROM ' . NewsSetup::$news_table . '
		WHERE (FT_SEARCH(name, :search_content) OR FT_SEARCH(contents, :search_content)) AND id <> :excluded_id
		AND (approbation_type = 1 OR (approbation_type = 2 AND start_date < :timestamp_now AND (end_date > :timestamp_now OR end_date = 0)))
		ORDER BY relevance DESC LIMIT 0, 10', array(
			'excluded_id' => $news->get_id(),
			'search_content' => $news->get_title() .','. $news->get_contents(),
			'timestamp_now' => $now->get_timestamp()
		));

		$this->tpl->put('C_SUGGESTED_NEWS', ($result->get_rows_count() > 0 && NewsConfig::load()->get_news_suggestions_enabled()));

		while ($row = $result->fetch())
		{
			$this->tpl->assign_block_vars('suggested', array(
				'CATEGORY_NAME' => CategoriesService::get_categories_manager()->get_categories_cache()->get_category($row['id_category'])->get_name(),
				'U_CATEGORY' =>  NewsUrlBuilder::display_category($row['id_category'], CategoriesService::get_categories_manager()->get_categories_cache()->get_category($row['id_category'])->get_rewrited_name())->rel(),
				'TITLE' => $row['name'],
				'U_ITEM' => NewsUrlBuilder::display_news($row['id_category'], CategoriesService::get_categories_manager()->get_categories_cache()->get_category($row['id_category'])->get_rewrited_name(), $row['id'], $row['rewrited_name'])->rel(),
				'U_THUMBNAIL' => !empty($row['picture_url']) ? Url::to_rel($row['picture_url']) : $news->get_default_thumbnail()->rel()
			));
		}
		$result->dispose();
	}

	private function build_navigation_links(News $news)
	{
		$now = new Date();
		$timestamp_news = $news->get_creation_date()->get_timestamp();

		$result = PersistenceContext::get_querier()->select('
		(SELECT id, name, id_category, rewrited_name, picture_url, \'PREVIOUS\' as type
		FROM '. NewsSetup::$news_table .'
		WHERE (approbation_type = 1 OR (approbation_type = 2 AND start_date < :timestamp_now AND (end_date > :timestamp_now OR end_date = 0))) AND creation_date < :timestamp_news AND id_category IN :authorized_categories ORDER BY creation_date DESC LIMIT 1 OFFSET 0)
		UNION
		(SELECT id, name, id_category, rewrited_name, picture_url, \'NEXT\' as type
		FROM '. NewsSetup::$news_table .'
		WHERE (approbation_type = 1 OR (approbation_type = 2 AND start_date < :timestamp_now AND (end_date > :timestamp_now OR end_date = 0))) AND creation_date > :timestamp_news AND id_category IN :authorized_categories ORDER BY creation_date ASC LIMIT 1 OFFSET 0)
		', array(
			'timestamp_now' => $now->get_timestamp(),
			'timestamp_news' => $timestamp_news,
			'authorized_categories' => array($news->get_id_cat())
		));

		while ($row = $result->fetch())
		{
			$this->tpl->put_all(array(
				'C_RELATED_LINKS' => true,
				'C_'. $row['type'] .'_ITEM' => true,
				$row['type'] . '_ITEM' => $row['name'],
				'U_'. $row['type'] .'_ITEM' => NewsUrlBuilder::display_news($row['id_category'], CategoriesService::get_categories_manager()->get_categories_cache()->get_category($row['id_category'])->get_rewrited_name(), $row['id'], $row['rewrited_name'])->rel(),
				'U_'. $row['type'] .'_THUMBNAIL' => !empty($row['picture_url']) ? Url::to_rel($row['picture_url']) : $news->get_default_thumbnail()->rel()
			));
		}
		$result->dispose();
	}

	private function check_authorizations()
	{
		$news = $this->get_news();

		$current_user = AppContext::get_current_user();
		$not_authorized = !CategoriesAuthorizationsService::check_authorizations($news->get_id_cat())->moderation() && !CategoriesAuthorizationsService::check_authorizations($news->get_id_cat())->write() && (!CategoriesAuthorizationsService::check_authorizations($news->get_id_cat())->contribution() || $news->get_author_user()->get_id() != $current_user->get_id());

		switch ($news->get_approbation_type()) {
			case News::APPROVAL_NOW:
				if (!CategoriesAuthorizationsService::check_authorizations($news->get_id_cat())->read())
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			case News::NOT_APPROVAL:
				if ($not_authorized || ($current_user->get_id() == User::VISITOR_LEVEL))
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			case News::APPROVAL_DATE:
				if (!$news->is_visible() && ($not_authorized || ($current_user->get_id() == User::VISITOR_LEVEL)))
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			default:
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			break;
		}
	}

	private function generate_response()
	{
		$category = $this->get_news()->get_category();
		$response = new SiteDisplayResponse($this->tpl);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->get_news()->get_title(), ($category->get_id() != Category::ROOT_CATEGORY ? $category->get_name() . ' - ' : '') . $this->lang['news']);
		$graphical_environment->get_seo_meta_data()->set_description($this->get_news()->get_real_summary());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(NewsUrlBuilder::display_news($category->get_id(), $category->get_rewrited_name(), $this->get_news()->get_id(), $this->get_news()->get_rewrited_title()));

		if ($this->get_news()->has_thumbnail())
			$graphical_environment->get_seo_meta_data()->set_picture_url($this->get_news()->get_thumbnail());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['news'], NewsUrlBuilder::home());

		$categories = array_reverse(CategoriesService::get_categories_manager()->get_parents($this->get_news()->get_id_cat(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), NewsUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}
		$breadcrumb->add($this->get_news()->get_title(), NewsUrlBuilder::display_news($category->get_id(), $category->get_rewrited_name(), $this->get_news()->get_id(), $this->get_news()->get_rewrited_title()));

		return $response;
	}
}
?>
