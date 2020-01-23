<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 23
 * @since       PHPBoost 4.0 - 2014 08 24
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DownloadDisplayDownloadFileController extends ModuleController
{
	private $lang;
	private $tpl;

	private $downloadfile;

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
		$this->lang = LangLoader::get('common', 'download');
		$this->tpl = new FileTemplate('download/DownloadDisplayDownloadFileController.tpl');
		$this->tpl->add_lang($this->lang);
	}

	private function get_downloadfile()
	{
		if ($this->downloadfile === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try {
					$this->downloadfile = DownloadService::get_downloadfile('WHERE download.id = :id', array('id' => $id));
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
				$this->downloadfile = new DownloadFile();
		}
		return $this->downloadfile;
	}

	private function count_views_number(HTTPRequestCustom $request)
	{
		if (!$this->downloadfile->is_visible())
		{
			$this->tpl->put('NOT_VISIBLE_MESSAGE', MessageHelper::display(LangLoader::get_message('element.not_visible', 'status-messages-common'), MessageHelper::WARNING));
		}
		else
		{
			if ($request->get_url_referrer() && !TextHelper::strstr($request->get_url_referrer(), DownloadUrlBuilder::display($this->downloadfile->get_category()->get_id(), $this->downloadfile->get_category()->get_rewrited_name(), $this->downloadfile->get_id(), $this->downloadfile->get_rewrited_title())->rel()))
			{
				$this->downloadfile->set_views_number($this->downloadfile->get_views_number() + 1);
				DownloadService::update_views_number($this->downloadfile);
			}
		}
	}

	private function build_view()
	{
		$config = DownloadConfig::load();
		$comments_config = CommentsConfig::load();
		$content_management_config = ContentManagementConfig::load();
		$downloadfile = $this->get_downloadfile();
		$category = $downloadfile->get_category();

		$keywords = $downloadfile->get_keywords();
		$has_keywords = count($keywords) > 0;

		$this->tpl->put_all(array_merge($downloadfile->get_array_tpl_vars(), array(
			'C_AUTHOR_DISPLAYED' => $config->is_author_displayed(),
			'C_ENABLED_COMMENTS' => $comments_config->module_comments_is_enabled('download'),
			'C_ENABLED_NOTATION' => $content_management_config->module_notation_is_enabled('download'),
			'C_KEYWORDS' => $has_keywords,
			'C_DISPLAY_DOWNLOAD_LINK' => DownloadAuthorizationsService::check_authorizations()->display_download_link(),
			'NOT_VISIBLE_MESSAGE' => MessageHelper::display(LangLoader::get_message('element.not_visible', 'status-messages-common'), MessageHelper::WARNING),
			'UNAUTHORIZED_TO_DOWNLOAD_MESSAGE' => MessageHelper::display($this->lang['download.message.warning.unauthorized.to.download.file'], MessageHelper::WARNING)
		)));

		if ($comments_config->module_comments_is_enabled('download'))
		{
			$comments_topic = new DownloadCommentsTopic($downloadfile);
			$comments_topic->set_id_in_module($downloadfile->get_id());
			$comments_topic->set_url(DownloadUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $downloadfile->get_id(), $downloadfile->get_rewrited_title()));

			$this->tpl->put('COMMENTS', $comments_topic->display());
		}

		if ($has_keywords)
			$this->build_keywords_view($keywords);

		foreach ($downloadfile->get_sources() as $name => $url)
		{
			$this->tpl->assign_block_vars('sources', $downloadfile->get_array_tpl_source_vars($name));
		}
	}

	private function build_keywords_view($keywords)
	{
		$nbr_keywords = count($keywords);

		$i = 1;
		foreach ($keywords as $keyword)
		{
			$this->tpl->assign_block_vars('keywords', array(
				'C_SEPARATOR' => $i < $nbr_keywords,
				'NAME' => $keyword->get_name(),
				'URL' => DownloadUrlBuilder::display_tag($keyword->get_rewrited_name())->rel(),
			));
			$i++;
		}
	}

	private function check_authorizations()
	{
		$downloadfile = $this->get_downloadfile();

		$current_user = AppContext::get_current_user();
		$not_authorized = !DownloadAuthorizationsService::check_authorizations($downloadfile->get_id_category())->moderation() && !DownloadAuthorizationsService::check_authorizations($downloadfile->get_id_category())->write() && (!DownloadAuthorizationsService::check_authorizations($downloadfile->get_id_category())->contribution() || $downloadfile->get_author_user()->get_id() != $current_user->get_id());

		switch ($downloadfile->get_approbation_type()) {
			case DownloadFile::APPROVAL_NOW:
				if (!DownloadAuthorizationsService::check_authorizations($downloadfile->get_id_category())->read())
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			case DownloadFile::NOT_APPROVAL:
				if ($not_authorized || ($current_user->get_id() == User::VISITOR_LEVEL))
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			case DownloadFile::APPROVAL_DATE:
				if (!$downloadfile->is_visible() && ($not_authorized || ($current_user->get_id() == User::VISITOR_LEVEL)))
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
		$downloadfile = $this->get_downloadfile();
		$category = $downloadfile->get_category();
		$response = new SiteDisplayResponse($this->tpl);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($downloadfile->get_title(), ($category->get_id() != Category::ROOT_CATEGORY ? $category->get_name() . ' - ' : '') . $this->lang['module.title']);
		$graphical_environment->get_seo_meta_data()->set_description($downloadfile->get_real_summary());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(DownloadUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $downloadfile->get_id(), $downloadfile->get_rewrited_title()));

		if ($downloadfile->has_thumbnail())
			$graphical_environment->get_seo_meta_data()->set_picture_url($downloadfile->get_thumbnail());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module.title'],DownloadUrlBuilder::home());

		$categories = array_reverse(CategoriesService::get_categories_manager()->get_parents($downloadfile->get_id_category(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), DownloadUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}
		$breadcrumb->add($downloadfile->get_title(), DownloadUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $downloadfile->get_id(), $downloadfile->get_rewrited_title()));

		return $response;
	}
}
?>
