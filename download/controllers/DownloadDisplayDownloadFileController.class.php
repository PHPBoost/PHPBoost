<?php
/*##################################################
 *                               DownloadDisplayDownloadFileController.class.php
 *                            -------------------
 *   begin                : August 24, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
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
	
	private function build_view()
	{
		$config = DownloadConfig::load();
		$downloadfile = $this->get_downloadfile();
		$category = $downloadfile->get_category();
		
		$keywords = $downloadfile->get_keywords();
		$has_keywords = count($keywords) > 0;
		
		$this->tpl->put_all(array_merge($downloadfile->get_array_tpl_vars(), array(
			'C_AUTHOR_DISPLAYED' => $config->is_author_displayed(),
			'C_COMMENTS_ENABLED' => $config->are_comments_enabled(),
			'C_NOTATION_ENABLED' => $config->is_notation_enabled(),
			'C_KEYWORDS' => $has_keywords,
			'C_DISPLAY_DOWNLOAD_LINK' => DownloadAuthorizationsService::check_authorizations()->display_download_link(),
			'NOT_VISIBLE_MESSAGE' => MessageHelper::display(LangLoader::get_message('element.not_visible', 'status-messages-common'), MessageHelper::WARNING),
			'UNAUTHORIZED_TO_DOWNLOAD_MESSAGE' => MessageHelper::display($this->lang['download.message.warning.unauthorized_to_download_file'], MessageHelper::WARNING)
		)));
		
		if ($config->are_comments_enabled())
		{
			$comments_topic = new DownloadCommentsTopic($downloadfile);
			$comments_topic->set_id_in_module($downloadfile->get_id());
			$comments_topic->set_url(DownloadUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $downloadfile->get_id(), $downloadfile->get_rewrited_name()));
			
			$this->tpl->put('COMMENTS', $comments_topic->display());
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
		
		$not_authorized = !DownloadAuthorizationsService::check_authorizations($downloadfile->get_id_category())->moderation() && (!DownloadAuthorizationsService::check_authorizations($downloadfile->get_id_category())->write() && $downloadfile->get_author_user()->get_id() != AppContext::get_current_user()->get_id());
		
		switch ($downloadfile->get_approbation_type()) {
			case DownloadFile::APPROVAL_NOW:
				if (!DownloadAuthorizationsService::check_authorizations($downloadfile->get_id_category())->read() && $not_authorized)
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			case DownloadFile::NOT_APPROVAL:
				if ($not_authorized)
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			case DownloadFile::APPROVAL_DATE:
				if (!$downloadfile->is_visible() && $not_authorized)
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
		$graphical_environment->set_page_title($downloadfile->get_name(), $this->lang['module_title']);
		$graphical_environment->get_seo_meta_data()->set_description($downloadfile->get_short_contents());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(DownloadUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $downloadfile->get_id(), $downloadfile->get_rewrited_name()));
		
		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'],DownloadUrlBuilder::home());
		
		$categories = array_reverse(DownloadService::get_categories_manager()->get_parents($downloadfile->get_id_category(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), DownloadUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}
		$breadcrumb->add($downloadfile->get_name(), DownloadUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $downloadfile->get_id(), $downloadfile->get_rewrited_name()));
		
		return $response;
	}
}
?>
