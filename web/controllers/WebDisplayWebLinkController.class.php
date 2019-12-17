<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 09
 * @since       PHPBoost 4.1 - 2014 08 21
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class WebDisplayWebLinkController extends ModuleController
{
	private $lang;
	private $tpl;

	private $weblink;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$this->build_view();

		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'web');
		$this->tpl = new FileTemplate('web/WebDisplayWebLinkController.tpl');
		$this->tpl->add_lang($this->lang);
	}

	private function get_weblink()
	{
		if ($this->weblink === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try {
					$this->weblink = WebService::get_weblink('WHERE web.id = :id', array('id' => $id));
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
				$this->weblink = new WebLink();
		}
		return $this->weblink;
	}

	private function build_view()
	{
		$config = WebConfig::load();
		$comments_config = CommentsConfig::load();
		$content_management_config = ContentManagementConfig::load();
		$weblink = $this->get_weblink();
		$category = $weblink->get_category();

		$keywords = $weblink->get_keywords();
		$has_keywords = count($keywords) > 0;

		$this->tpl->put_all(array_merge($weblink->get_array_tpl_vars(), array(
			'C_COMMENTS_ENABLED' => $comments_config->module_comments_is_enabled('web'),
			'C_NOTATION_ENABLED' => $content_management_config->module_notation_is_enabled('web'),
			'C_KEYWORDS' => $has_keywords,
			'NOT_VISIBLE_MESSAGE' => MessageHelper::display(LangLoader::get_message('element.not_visible', 'status-messages-common'), MessageHelper::WARNING)
		)));

		if ($comments_config->module_comments_is_enabled('web'))
		{
			$comments_topic = new WebCommentsTopic($weblink);
			$comments_topic->set_id_in_module($weblink->get_id());
			$comments_topic->set_url(WebUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $weblink->get_id(), $weblink->get_rewrited_name()));

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
				'URL' => WebUrlBuilder::display_tag($keyword->get_rewrited_name())->rel(),
			));
			$i++;
		}
	}

	private function check_authorizations()
	{
		$weblink = $this->get_weblink();

		$current_user = AppContext::get_current_user();
		$not_authorized = !CategoriesAuthorizationsService::check_authorizations($weblink->get_id_category())->moderation() && !CategoriesAuthorizationsService::check_authorizations($weblink->get_id_category())->write() && (!CategoriesAuthorizationsService::check_authorizations($weblink->get_id_category())->contribution() || $weblink->get_author_user()->get_id() != $current_user->get_id());

		switch ($weblink->get_approbation_type()) {
			case WebLink::APPROVAL_NOW:
				if (!CategoriesAuthorizationsService::check_authorizations($weblink->get_id_category())->read())
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			case WebLink::NOT_APPROVAL:
				if ($not_authorized || ($current_user->get_id() == User::VISITOR_LEVEL))
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			case WebLink::APPROVAL_DATE:
				if (!$weblink->is_visible() && ($not_authorized || ($current_user->get_id() == User::VISITOR_LEVEL)))
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
		$weblink = $this->get_weblink();
		$category = $weblink->get_category();
		$response = new SiteDisplayResponse($this->tpl);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($weblink->get_name(), ($category->get_id() != Category::ROOT_CATEGORY ? $category->get_name() . ' - ' : '') . $this->lang['module_title']);
		$graphical_environment->get_seo_meta_data()->set_description($weblink->get_real_short_contents());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(WebUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $weblink->get_id(), $weblink->get_rewrited_name()));

		if ($weblink->has_picture())
			$graphical_environment->get_seo_meta_data()->set_picture_url($weblink->get_picture());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'],WebUrlBuilder::home());

		$categories = array_reverse(CategoriesService::get_categories_manager()->get_parents($weblink->get_id_category(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), WebUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}
		$breadcrumb->add($weblink->get_name(), WebUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $weblink->get_id(), $weblink->get_rewrited_name()));

		return $response;
	}
}
?>
