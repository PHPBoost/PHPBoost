<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 02 20
 * @since       PHPBoost 5.2 - 2020 06 15
*/

class PagesTagController extends ModuleController
{
	private $view;
	private $lang;

	private $keyword;

	private $config;
	private $comments_config;
	private $content_management_config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$this->build_view($request);

		return $this->generate_response($request);
	}

	public function init()
	{
		$this->lang = LangLoader::get('common', 'pages');
		$this->view = new FileTemplate('pages/PagesSeveralItemsController.tpl');
		$this->view->add_lang($this->lang);
		$this->config = PagesConfig::load();
		$this->comments_config = CommentsConfig::load();
		$this->content_management_config = ContentManagementConfig::load();
	}

	public function build_view(HTTPRequestCustom $request)
	{
		$now = new Date();

		$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY);

		$condition = 'WHERE relation.id_keyword = :id_keyword
		AND id_category IN :authorized_categories
		AND (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)))';
		$parameters = array(
			'id_keyword' => $this->get_keyword()->get_id(),
			'authorized_categories' => $authorized_categories,
			'timestamp_now' => $now->get_timestamp()
		);

		$result = PersistenceContext::get_querier()->select('SELECT pages.*, member.*, com.number_comments
		FROM ' . PagesSetup::$pages_table . ' pages
		LEFT JOIN ' . DB_TABLE_KEYWORDS_RELATIONS . ' relation ON relation.module_id = \'pages\' AND relation.id_in_module = pages.id
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = pages.author_user_id
		LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = pages.id AND com.module_id = \'pages\'
		' . $condition . '
		ORDER BY id_category, i_order', array_merge($parameters, array(
			'user_id' => AppContext::get_current_user()->get_id()
		)));

		$this->view->put_all(array(
			'C_ITEMS' => $result->get_rows_count() > 0,
			'C_SEVERAL_ITEMS' => $result->get_rows_count() > 1,
			'C_CATEGORY_DESCRIPTION' => !empty($category_description),
			'C_ENABLED_COMMENTS' => $this->comments_config->module_comments_is_enabled('pages'),
			'C_CATEGORIES' => true,
			'CATEGORY_NAME' => $this->get_keyword()->get_name()
		));

		while ($row = $result->fetch())
		{
			$item = new PagesItem();
			$item->set_properties($row);

			$keywords = $item->get_keywords();
			$has_keywords = count($keywords) > 0;

			$this->view->assign_block_vars('items', array_merge($item->get_array_tpl_vars(), array(
				'C_KEYWORDS' => $has_keywords
			)));

			if ($has_keywords)
				$this->build_keywords_view($keywords);

			foreach ($item->get_sources() as $name => $url)
			{
				$this->view->assign_block_vars('items.sources', $item->get_template_source_vars($name));
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

	private function build_keywords_view($keywords)
	{
		$keywords_number = count($keywords);

		$i = 1;
		foreach ($keywords as $keyword)
		{
			$this->view->assign_block_vars('items.keywords', array(
				'C_SEPARATOR' => $i < $keywords_number,
				'NAME' => $keyword->get_name(),
				'URL' => PagesUrlBuilder::display_tag($keyword->get_rewrited_name())->rel(),
			));
			$i++;
		}
	}

	private function check_authorizations()
	{
		if (!CategoriesAuthorizationsService::check_authorizations()->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response(HTTPRequestCustom $request)
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->get_keyword()->get_name(), $this->lang['module.title']);
		$graphical_environment->get_seo_meta_data()->set_description(StringVars::replace_vars($this->lang['pages.seo.description.tag'], array('subject' => $this->get_keyword()->get_name())));
		$graphical_environment->get_seo_meta_data()->set_canonical_url(PagesUrlBuilder::display_tag($this->get_keyword()->get_rewrited_name()));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module.title'], PagesUrlBuilder::home());
		$breadcrumb->add($this->get_keyword()->get_name(), PagesUrlBuilder::display_tag($this->get_keyword()->get_rewrited_name()));

		return $response;
	}
}
?>
