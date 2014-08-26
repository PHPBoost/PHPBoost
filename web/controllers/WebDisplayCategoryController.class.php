<?php
/*##################################################
 *                               WebDisplayCategoryController.class.php
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

class WebDisplayCategoryController extends ModuleController
{
	private $lang;
	private $tpl;
	
	private $category;
	
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
		$this->tpl = new FileTemplate('web/WebDisplaySeveralWebLinksController.tpl');
		$this->tpl->add_lang($this->lang);
	}
	
	private function build_view()
	{
		$config = WebConfig::load();
		$authorized_categories = WebService::get_authorized_categories($this->get_category()->get_id());
		
		//Children categories
		$result = PersistenceContext::get_querier()->select('SELECT @id_cat:= web_cats.id, web_cats.*,
		(SELECT COUNT(*) FROM '. WebSetup::$web_table .' web
		WHERE web.id_category = @id_cat AND web.approved = 1) AS weblinks_number
		FROM ' . WebSetup::$web_cats_table .' web_cats
		WHERE web_cats.id_parent = :id_category AND web_cats.id IN (' . implode(', ', $authorized_categories) . ') 
		ORDER BY web_cats.id_parent', array(
			'id_category' => $this->category->get_id()
		));
		
		$nbr_cat_displayed = 0;
		while ($row = $result->fetch())
		{
			$category_image = new Url($row['image']);
			
			$this->tpl->assign_block_vars('sub_categories_list', array(
				'CATEGORY_NAME' => $row['name'],
				'CATEGORY_IMAGE' => $category_image->rel(),
				'CATEGORY_DESCRIPTION' => FormatingHelper::second_parse($row['description']),
				'WEBLINKS_NUMBER' => $row['weblinks_number'],
				'U_CATEGORY' => WebUrlBuilder::display_category($row['id'], $row['rewrited_name'])->rel()
			));
			
			$nbr_cat_displayed++;
		}
		$result->dispose();
		
		$nbr_column_cats = ($nbr_cat_displayed > $config->get_columns_number_per_line()) ? $config->get_columns_number_per_line() : $nbr_cat_displayed;
		$nbr_column_cats = !empty($nbr_column_cats) ? $nbr_column_cats : 1;
		$cats_columns_width = floor(100 / $nbr_column_cats);
		
		$pagination = $this->get_pagination($this->get_pagination($this->get_category()->get_id()););
		
		$result = PersistenceContext::get_querier()->select('SELECT web.*, member.*, com.number_comments, notes.average_notes, notes.number_notes, note.note
		FROM '. WebSetup::$web_table .' web
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = web.author_user_id
		LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = web.id AND com.module_id = \'web\'
		LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = web.id AND notes.module_name = \'web\'
		LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = web.id AND note.module_name = \'web\' AND note.user_id = :user_id
		WHERE web.approved = 1 AND web.id_category = :id_category
		ORDER BY ' . $config->get_sort_type() . ' ' . $config->get_sort_mode() . '
		LIMIT :number_items_per_page OFFSET :display_from', array(
			'user_id' => AppContext::get_current_user()->get_id(),
			'id_category' => $this->get_category()->get_id(),
			'number_items_per_page' => $pagination->get_number_items_per_page(),
			'display_from' => $pagination->get_display_from()
		));
		
		$category_description = FormatingHelper::second_parse($this->get_category()->get_description());
		
		$this->tpl->put_all(array(
			'C_CATEGORY' => true,
			'C_ROOT_CATEGORY' => $this->get_category()->get_id() == Category::ROOT_CATEGORY,
			'C_CATEGORY_DESCRIPTION' => !empty($category_description),
			'C_SUB_CATEGORIES' => $nbr_cat_displayed > 0,
			'C_WEBLINKS' => $result->get_rows_count() > 0,
			'C_CATEGORY_DISPLAYED_SUMMARY' => $config->is_category_displayed_summary(),
			'C_COMMENTS_ENABLED' => $config->are_comments_enabled(),
			'C_NOTATION_ENABLED' => $config->is_notation_enabled(),
			'C_PAGINATION' => $pagination->has_several_pages(),
			'PAGINATION' => $pagination->display(),
			'CATS_COLUMNS_WIDTH' => $cats_columns_width,
			'CATEGORY_NAME' => $this->get_category()->get_name(),
			'CATEGORY_IMAGE' => $this->get_category()->get_image()->rel(),
			'CATEGORY_DESCRIPTION' => $category_description
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
	
	private function get_pagination($id_category)
	{
		$weblinks_number = WebService::count(
			'WHERE approved = 1 AND id_category = :id_category', 
			array(
				'id_category' => $id_category
		));
		
		$page = AppContext::get_request()->get_getint('page', 1);
		$pagination = new ModulePagination($page, $weblinks_number, (int)WebConfig::load()->get_items_number_per_page());
		$pagination->set_url(WebUrlBuilder::display_category($this->get_category()->get_id(), $this->get_category()->get_rewrited_name(), '%d'));
		
		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		
		return $pagination;
	}
	
	private function get_category()
	{
		if ($this->category === null)
		{
			$id = AppContext::get_request()->get_getint('id_category', 0);
			if (!empty($id))
			{
				try {
					$this->category = WebService::get_categories_manager()->get_categories_cache()->get_category($id);
				} catch (CategoryNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
   					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->category = WebService::get_categories_manager()->get_categories_cache()->get_category(Category::ROOT_CATEGORY);
			}
		}
		return $this->category;
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
		$id_category = $this->get_category()->get_id();
		if (!WebAuthorizationsService::check_authorizations($id_category)->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
	
	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->tpl);
		
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->get_category()->get_name());
		$graphical_environment->get_seo_meta_data()->set_description($this->get_category()->get_description());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(WebUrlBuilder::display_category($this->get_category()->get_id(), $this->get_category()->get_rewrited_name(), AppContext::get_request()->get_getint('page', 1)));
		
		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], WebUrlBuilder::home());
		
		$categories = array_reverse(WebService::get_categories_manager()->get_parents($this->get_category()->get_id(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), WebUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}
		
		return $response;
	}
	
	public static function get_view()
	{
		$object = new self();
		$object->check_authorizations();
		$object->init();
		$object->build_view();
		return $object->tpl;
	}
}
?>
