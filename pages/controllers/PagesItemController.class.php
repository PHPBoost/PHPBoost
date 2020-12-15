<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 06 30
 * @since       PHPBoost 5.2 - 2020 06 15
*/

class PagesItemController extends ModuleController
{
	private $lang;
	private $view;

	private $item;

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
		$this->lang = LangLoader::get('common', 'pages');
		$this->view = new FileTemplate('pages/PagesItemController.tpl');
		$this->view->add_lang($this->lang);
	}

	private function get_item()
	{
		if ($this->item === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try {
					$this->item = PagesService::get_item('WHERE pages.id = :id', array('id' => $id));
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
				$this->item = new PagesItem();
		}
		return $this->item;
	}

	private function count_views_number(HTTPRequestCustom $request)
	{
		if (!$this->item->is_published())
		{
			$this->view->put('NOT_VISIBLE_MESSAGE', MessageHelper::display(LangLoader::get_message('element.not_visible', 'status-messages-common'), MessageHelper::WARNING));
		}
		else
		{
			if ($request->get_url_referrer() && !TextHelper::strstr($request->get_url_referrer(), PagesUrlBuilder::display_item($this->item->get_category()->get_id(), $this->item->get_category()->get_rewrited_name(), $this->item->get_id(), $this->item->get_rewrited_title())->rel()))
			{
				$this->item->set_views_number($this->item->get_views_number() + 1);
				PagesService::update_views_number($this->item);
			}
		}
	}

	private function build_view()
	{
		$config = PagesConfig::load();
		$comments_config = CommentsConfig::load();
		$content_management_config = ContentManagementConfig::load();
		$category = $this->item->get_category();

		$keywords = $this->item->get_keywords();
		$has_keywords = count($keywords) > 0;

		$this->view->put_all(array_merge($this->item->get_array_tpl_vars(), array(
			'C_ENABLED_COMMENTS' => $comments_config->module_comments_is_enabled('pages'),
			'C_KEYWORDS' => $has_keywords,
			'NOT_VISIBLE_MESSAGE' => MessageHelper::display(LangLoader::get_message('element.not_visible', 'status-messages-common'), MessageHelper::WARNING)
		)));

		if ($comments_config->module_comments_is_enabled('pages'))
		{
			$comments_topic = new PagesCommentsTopic($this->item);
			$comments_topic->set_id_in_module($this->item->get_id());
			$comments_topic->set_url(PagesUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $this->item->get_id(), $this->item->get_rewrited_title()));

			$this->view->put('COMMENTS', $comments_topic->display());
		}

		if ($has_keywords)
			$this->build_keywords_view($keywords);

		foreach ($this->item->get_sources() as $name => $url)
		{
			$this->view->assign_block_vars('sources', $this->item->get_array_tpl_source_vars($name));
		}
	}

	private function build_keywords_view($keywords)
	{
		$keywords_number = count($keywords);

		$i = 1;
		foreach ($keywords as $keyword)
		{
			$this->view->assign_block_vars('keywords', array(
				'C_SEPARATOR' => $i < $keywords_number,
				'NAME' => $keyword->get_name(),
				'URL' => PagesUrlBuilder::display_tag($keyword->get_rewrited_name())->rel(),
			));
			$i++;
		}
	}

	private function check_authorizations()
	{
		$item = $this->get_item();

		$current_user = AppContext::get_current_user();
		$not_authorized = !CategoriesAuthorizationsService::check_authorizations($item->get_id_category())->moderation() && !CategoriesAuthorizationsService::check_authorizations($item->get_id_category())->write() && (!CategoriesAuthorizationsService::check_authorizations($item->get_id_category())->contribution() || $item->get_author_user()->get_id() != $current_user->get_id());

		switch ($item->get_publishing_state()) {
			case PagesItem::PUBLISHED:
				if (!CategoriesAuthorizationsService::check_authorizations($item->get_id_category())->read())
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			case PagesItem::NOT_PUBLISHED:
				if ($not_authorized || ($current_user->get_id() == User::VISITOR_LEVEL))
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			case PagesItem::DEFERRED_PUBLICATION:
				if (!$item->is_published() && ($not_authorized || ($current_user->get_id() == User::VISITOR_LEVEL)))
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
		$category = $this->item->get_category();
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->item->get_title(), ($category->get_id() != Category::ROOT_CATEGORY ? $category->get_name() . ' - ' : '') . $this->lang['module.title']);
		$graphical_environment->get_seo_meta_data()->set_description($this->item->get_content());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(PagesUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $this->item->get_id(), $this->item->get_rewrited_title()));

		if ($this->item->has_thumbnail())
			$graphical_environment->get_seo_meta_data()->set_picture_url($this->item->get_thumbnail());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module.title'],PagesUrlBuilder::home());

		$categories = array_reverse(CategoriesService::get_categories_manager()->get_parents($this->item->get_id_category(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), PagesUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}
		$breadcrumb->add($this->item->get_title(), PagesUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $this->item->get_id(), $this->item->get_rewrited_title()));

		return $response;
	}
}
?>
