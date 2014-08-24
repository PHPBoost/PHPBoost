<?php
/*##################################################
 *                               WebDisplayWebLinkTagController.class.php
 *                            -------------------
 *   begin                : August 21, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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
 * @author Julien BRISWALTER <julienseth78@phpboost.com>
 */

class WebDisplayWebLinkTagController extends ModuleController
{
	private $tpl;
	private $lang;
	
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
		$this->lang = LangLoader::get('common', 'web');
		$this->tpl = new FileTemplate('web/WebDisplaySeveralWebLinksController.tpl');
		$this->tpl->add_lang($this->lang);
	}
	
	public function build_view()
	{
		$config = WebConfig::load();
		$authorized_categories = WebService::get_authorized_categories(Category::ROOT_CATEGORY);
		$pagination = $this->get_pagination($authorized_categories);
		
		$result = PersistenceContext::get_querier()->select('SELECT web.*, member.*, com.number_comments, notes.average_notes, notes.number_notes, note.note
		FROM ' . WebSetup::$web_table . ' web
		LEFT JOIN ' . DB_TABLE_KEYWORDS_RELATIONS . ' relation ON relation.module_id = \'web\' AND relation.id_in_module = web.id 
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = web.author_user_id
		LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = web.id AND com.module_id = \'web\'
		LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = web.id AND notes.module_name = \'web\'
		LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = web.id AND note.module_name = \'web\' AND note.user_id = ' . AppContext::get_current_user()->get_id() . '
		WHERE relation.id_keyword = :id_keyword AND web.approved = 1 AND web.id_category IN :authorized_categories
		ORDER BY ' . $config->get_sort_type() . ' ' . $config->get_sort_mode() . '
		LIMIT :number_items_per_page OFFSET :display_from', array(
			'id_keyword' => $this->get_keyword()->get_id(),
			'authorized_categories' => $authorized_categories,
			'number_items_per_page' => $pagination->get_number_items_per_page(),
			'display_from' => $pagination->get_display_from()
		));
		
		$this->tpl->put_all(array(
			'C_WEBLINKS' => $result->get_rows_count() > 0,
			'C_CATEGORY_DISPLAYED_SUMMARY' => $config->is_category_displayed_summary(),
			'C_COMMENTS_ENABLED' => $config->are_comments_enabled(),
			'C_NOTATION_ENABLED' => $config->is_notation_enabled(),
			'C_PAGINATION' => $pagination->has_several_pages(),
			'PAGINATION' => $pagination->display(),
			'NOT_APPROVED_MESSAGE' => MessageHelper::display($this->lang['web.message.not_approved'], MessageHelper::WARNING),
			'CATEGORY_NAME' => $this->get_keyword()->get_name()
		));
		
		while ($row = $result->fetch())
		{
			$weblink = new WebLink();
			$weblink->set_properties($row);
			
			$keywords = $weblink->get_keywords();
			$nbr_keywords = count($keywords);
			
			$this->tpl->assign_block_vars('weblinks', array_merge($weblink->get_array_tpl_vars(), array(
				'C_KEYWORDS' => $nbr_keywords > 0
			)));
			
			$this->build_keywords_view($keywords);
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
					$this->keyword = WebService::get_keywords_manager()->get_keyword('WHERE rewrited_name=:rewrited_name', array('rewrited_name' => $rewrited_name));
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
	
	private function get_pagination($authorized_categories)
	{
		$result = PersistenceContext::get_querier()->select_single_row_query('SELECT COUNT(*) AS weblinks_number
		FROM '. WebSetup::$web_table .' web
		LEFT JOIN '. DB_TABLE_KEYWORDS_RELATIONS .' relation ON relation.module_id = \'web\' AND relation.id_in_module = web.id 
		WHERE relation.id_keyword = :id_keyword AND web.approved AND web.id_category IN :authorized_categories', array(
			'id_keyword' => $this->get_keyword()->get_id(),
			'authorized_categories' => $authorized_categories,
		));
		
		$page = AppContext::get_request()->get_getint('page', 1);
		$pagination = new ModulePagination($page, $result['weblinks_number'], (int)WebConfig::load()->get_items_number_per_page());
		$pagination->set_url(WebUrlBuilder::display_tag($this->get_keyword()->get_rewrited_name(), '%d'));
		
		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		
		return $pagination;
	}
	
	private function build_keywords_view($keywords)
	{
		$nbr_keywords = count($keywords);
		
		$i = 1;
		foreach ($keywords as $keyword)
		{
			$this->tpl->assign_block_vars('weblinks.keywords', array(
				'C_SEPARATOR' => $i < $nbr_keywords,
				'NAME' => $keyword->get_name(),
				'URL' => WebUrlBuilder::display_tag($keyword->get_rewrited_name())->rel(),
			));
			$i++;
		}
	}
	
	private function check_authorizations()
	{
		if (!WebAuthorizationsService::check_authorizations()->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
	
	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->tpl);
		
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->get_keyword()->get_name());
		$graphical_environment->get_seo_meta_data()->set_description(StringVars::replace_vars($this->lang['web.seo.description.tag'], array('subject' => $this->get_keyword()->get_name())));
		$graphical_environment->get_seo_meta_data()->set_canonical_url(WebUrlBuilder::display_tag($this->get_keyword()->get_rewrited_name(), AppContext::get_request()->get_getint('page', 1)));
		
		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], WebUrlBuilder::home());
		$breadcrumb->add($this->get_keyword()->get_name(), WebUrlBuilder::display_tag($this->get_keyword()->get_rewrited_name(), AppContext::get_request()->get_getint('page', 1)));
		
		return $response;
	}
}
?>
