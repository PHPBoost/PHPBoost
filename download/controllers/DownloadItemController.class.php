<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 14
 * @since       PHPBoost 4.0 - 2014 08 24
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DownloadItemController extends DefaultModuleController
{
	protected function get_template_to_use()
	{
		return new FileTemplate('download/DownloadItemController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->build_view();
		$this->count_views_number($request);
		$this->check_authorizations();

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
					$this->item = DownloadService::get_item($id);
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
				$this->item = new DownloadItem();
		}
		return $this->item;
	}

	private function count_views_number(HTTPRequestCustom $request)
	{
		if (!$this->item->is_published())
		{
			$this->view->put('NOT_VISIBLE_MESSAGE', MessageHelper::display($this->lang['warning.element.not.visible'], MessageHelper::WARNING));
		}
		else
		{
			if ($request->get_url_referrer() && !TextHelper::strstr($request->get_url_referrer(), DownloadUrlBuilder::display($this->item->get_category()->get_id(), $this->item->get_category()->get_rewrited_name(), $this->item->get_id(), $this->item->get_rewrited_title())->rel()))
			{
				$this->item->set_views_number($this->item->get_views_number() + 1);
				DownloadService::update_views_number($this->item);
			}
		}
	}

	private function build_view()
	{
		$config = DownloadConfig::load();
		$comments_config = CommentsConfig::load();
		$content_management_config = ContentManagementConfig::load();
		$item = $this->get_item();
		$category = $item->get_category();

		$keywords = $item->get_keywords();
		$has_keywords = count($keywords) > 0;

		$this->view->put_all(array_merge($item->get_template_vars(), array(
			'C_AUTHOR_DISPLAYED'      => $config->is_author_displayed(),
			'C_ENABLED_COMMENTS'      => $comments_config->module_comments_is_enabled('download'),
			'C_ENABLED_NOTATION'      => $content_management_config->module_notation_is_enabled('download'),
			'C_KEYWORDS'              => $has_keywords,
			'C_DISPLAY_DOWNLOAD_LINK' => DownloadAuthorizationsService::check_authorizations()->display_download_link(),
			'NOT_VISIBLE_MESSAGE'              => MessageHelper::display($this->lang['warning.element.not.visible'], MessageHelper::WARNING),
			'UNAUTHORIZED_TO_DOWNLOAD_MESSAGE' => MessageHelper::display($this->lang['download.message.warning.unauthorized.download'], MessageHelper::WARNING)
		)));

		if ($comments_config->module_comments_is_enabled('download'))
		{
			$comments_topic = new DownloadCommentsTopic($item);
			$comments_topic->set_id_in_module($item->get_id());
			$comments_topic->set_url(DownloadUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $item->get_id(), $item->get_rewrited_title()));

			$this->view->put('COMMENTS', $comments_topic->display());
		}

		if ($has_keywords)
			$this->build_keywords_view($keywords);

		foreach ($item->get_sources() as $name => $url)
		{
			$this->view->assign_block_vars('sources', $item->get_array_tpl_source_vars($name));
		}
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
				'URL'  => DownloadUrlBuilder::display_tag($keyword->get_rewrited_name())->rel(),
			));
			$i++;
		}
	}

	private function check_authorizations()
	{
		$item = $this->get_item();

		$current_user = AppContext::get_current_user();
		$not_authorized = !DownloadAuthorizationsService::check_authorizations($item->get_id_category())->moderation() && !DownloadAuthorizationsService::check_authorizations($item->get_id_category())->write() && (!DownloadAuthorizationsService::check_authorizations($item->get_id_category())->contribution() || $item->get_author_user()->get_id() != $current_user->get_id());

		switch ($item->get_publishing_state()) {
			case DownloadItem::PUBLISHED:
				if (!DownloadAuthorizationsService::check_authorizations($item->get_id_category())->read())
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			case DownloadItem::NOT_PUBLISHED:
				if ($not_authorized || ($current_user->get_id() == User::VISITOR_LEVEL))
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			case DownloadItem::DEFERRED_PUBLICATION:
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
		$item = $this->get_item();
		$category = $item->get_category();
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($item->get_title(), ($category->get_id() != Category::ROOT_CATEGORY ? $category->get_name() . ' - ' : '') . $this->lang['download.module.title']);
		$graphical_environment->get_seo_meta_data()->set_description($item->get_real_summary());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(DownloadUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $item->get_id(), $item->get_rewrited_title()));

		if ($item->has_thumbnail())
			$graphical_environment->get_seo_meta_data()->set_picture_url($item->get_thumbnail());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['download.module.title'],DownloadUrlBuilder::home());

		$categories = array_reverse(CategoriesService::get_categories_manager()->get_parents($item->get_id_category(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), DownloadUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}
		$breadcrumb->add($item->get_title(), DownloadUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $item->get_id(), $item->get_rewrited_title()));

		return $response;
	}
}
?>
