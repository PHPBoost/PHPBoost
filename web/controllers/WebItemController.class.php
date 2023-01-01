<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 4.1 - 2014 08 21
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class WebItemController extends DefaultModuleController
{
	protected function get_template_to_use()
	{
		return new FileTemplate('web/WebItemController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->build_view();

		return $this->generate_response();
	}

	private function get_item()
	{
		if ($this->item === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try {
					$this->item = WebService::get_item($id);
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
				$this->item = new WebItem();
		}
		return $this->item;
	}

	private function build_view()
	{
		$comments_config = CommentsConfig::load();
		$content_management_config = ContentManagementConfig::load();

		$category = $this->item->get_category();

		$keywords = $this->item->get_keywords();
		$has_keywords = count($keywords) > 0;

		$this->view->put_all(array_merge($this->item->get_template_vars(), array(
			'C_ENABLED_COMMENTS' => $comments_config->module_comments_is_enabled('web'),
			'C_ENABLED_NOTATION' => $content_management_config->module_notation_is_enabled('web'),
			'C_KEYWORDS'         => $has_keywords,

			'NOT_VISIBLE_MESSAGE' => MessageHelper::display($this->lang['warning.element.not.visible'], MessageHelper::WARNING)
		)));

		if ($comments_config->module_comments_is_enabled('web'))
		{
			$comments_topic = new WebCommentsTopic($this->item);
			$comments_topic->set_id_in_module($this->item->get_id());
			$comments_topic->set_url(WebUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $this->item->get_id(), $this->item->get_rewrited_title()));

			$this->view->put('COMMENTS', $comments_topic->display());
		}

		if ($has_keywords)
			$this->build_keywords_view($keywords);
	}

	private function build_keywords_view($keywords)
	{
		$nbr_keywords = count($keywords);

		$i = 1;
		foreach ($keywords as $keyword)
		{
			$this->view->assign_block_vars('keywords', array(
				'C_SEPARATOR' => $i < $nbr_keywords,
				'NAME' => $keyword->get_name(),
				'URL' => WebUrlBuilder::display_tag($keyword->get_rewrited_name())->rel(),
			));
			$i++;
		}
	}

	private function check_authorizations()
	{
		$this->item = $this->get_item();

		$current_user = AppContext::get_current_user();
		$not_authorized = !CategoriesAuthorizationsService::check_authorizations($this->item->get_id_category())->moderation() && !CategoriesAuthorizationsService::check_authorizations($this->item->get_id_category())->write() && (!CategoriesAuthorizationsService::check_authorizations($this->item->get_id_category())->contribution() || $this->item->get_author_user()->get_id() != $current_user->get_id());

		switch ($this->item->get_publishing_state()) {
			case WebItem::PUBLISHED:
				if (!CategoriesAuthorizationsService::check_authorizations($this->item->get_id_category())->read())
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			case WebItem::NOT_PUBLISHED:
				if ($not_authorized || ($current_user->get_id() == User::VISITOR_LEVEL))
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			case WebItem::DEFERRED_PUBLICATION:
				if (!$this->item->is_published() && ($not_authorized || ($current_user->get_id() == User::VISITOR_LEVEL)))
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
		$graphical_environment->set_page_title($this->item->get_title(), ($category->get_id() != Category::ROOT_CATEGORY ? $category->get_name() . ' - ' : '') . $this->lang['web.module.title']);
		$graphical_environment->get_seo_meta_data()->set_description($this->item->get_real_summary());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(WebUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $this->item->get_id(), $this->item->get_rewrited_title()));

		if ($this->item->has_thumbnail())
			$graphical_environment->get_seo_meta_data()->set_picture_url($this->item->get_thumbnail());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['web.module.title'],WebUrlBuilder::home());

		$categories = array_reverse(CategoriesService::get_categories_manager()->get_parents($this->item->get_id_category(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), WebUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}
		$breadcrumb->add($this->item->get_title(), WebUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $this->item->get_id(), $this->item->get_rewrited_title()));

		return $response;
	}
}
?>
