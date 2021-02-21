<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 02 20
 * @since       PHPBoost 5.2 - 2020 06 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class PagesPendingItemsController extends ModuleController
{
	private $view;
	private $lang;
	private $user_lang;
	private $config;

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
		$this->user_lang = LangLoader::get('user-common');
		$this->view = new FileTemplate('pages/PagesSeveralItemsController.tpl');
		$this->view->add_lang($this->lang);
		$this->config = PagesConfig::load();
	}

	public function build_view(HTTPRequestCustom $request)
	{
		$now = new Date();
		$comments_config = CommentsConfig::load();
		$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY);

		$condition = 'WHERE id_category IN :authorized_categories
		' . (!CategoriesAuthorizationsService::check_authorizations()->moderation() ? ' AND author_user_id = :user_id' : '') . '
		AND (published = 0 OR (published = 2 AND (publishing_start_date > :timestamp_now OR (publishing_end_date != 0 AND publishing_end_date < :timestamp_now))))';
		$parameters = array(
			'user_id' => AppContext::get_current_user()->get_id(),
			'authorized_categories' => $authorized_categories,
			'timestamp_now' => $now->get_timestamp()
		);

		$result = PersistenceContext::get_querier()->select('SELECT pages.*, member.*, com.number_comments
		FROM '. PagesSetup::$pages_table .' pages
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = pages.author_user_id
		LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = pages.id AND com.module_id = \'pages\'
		' . $condition . '
		ORDER BY update_date', array_merge($parameters, array(
		)));

		$this->view->put_all(array(
			'C_PENDING_ITEMS' => true,
			'C_NO_ITEM' => $result->get_rows_count() == 0,
			'C_ITEMS' => $result->get_rows_count() > 0,
			'C_SEVERAL_ITEMS' => $result->get_rows_count() > 1,
			'C_CATEGORY_DESCRIPTION' => !empty($category_description),
			'C_ENABLED_COMMENTS' => $comments_config->module_comments_is_enabled('pages')
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
				$this->view->assign_block_vars('pages.sources', $item->get_template_source_vars($name));
			}
		}
		$result->dispose();
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
		if (!(CategoriesAuthorizationsService::check_authorizations()->write() || CategoriesAuthorizationsService::check_authorizations()->contribution() || CategoriesAuthorizationsService::check_authorizations()->moderation()))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response(HTTPRequestCustom $request)
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['pending.items'], $this->lang['module.title']);
		$graphical_environment->get_seo_meta_data()->set_description($this->lang['pages.seo.description.pending']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(PagesUrlBuilder::display_pending());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module.title'], PagesUrlBuilder::home());
		$breadcrumb->add($this->lang['pending.items'], PagesUrlBuilder::display_pending());

		return $response;
	}
}
?>
