<?php
/*##################################################
 *                               FaqDisplayCategoryController.class.php
 *                            -------------------
 *   begin                : September 2, 2014
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

class FaqDisplayCategoryController extends ModuleController
{
	private $lang;
	private $tpl;
	
	private $category;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();
		
		$this->init();
		
		if ($request->get_value('submit', false))
		{
			$this->update_position($request);
			$this->tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.position.update', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}
		
		$this->build_view($request);
		
		return $this->generate_response();
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'faq');
		if (FaqAuthorizationsService::check_authorizations($this->get_category()->get_id())->moderation())
			$this->tpl = new FileTemplate('faq/FaqModerationDisplaySeveralFaqQuestionsController.tpl');
		else
			$this->tpl = new FileTemplate('faq/FaqDisplaySeveralFaqQuestionsController.tpl');
		$this->tpl->add_lang($this->lang);
	}
	
	private function build_view(HTTPRequestCustom $request)
	{
		$config = FaqConfig::load();
		$authorized_categories = FaqService::get_authorized_categories($this->get_category()->get_id());
		
		//Children categories
		$result = PersistenceContext::get_querier()->select('SELECT @id_cat:= faq_cats.id, faq_cats.*,
		(SELECT COUNT(*) FROM ' . FaqSetup::$faq_table . ' faq
		WHERE faq.approved = 1
		AND faq.id_category = @id_cat
		) AS questions_number
		FROM ' . FaqSetup::$faq_cats_table .' faq_cats
		WHERE faq_cats.id_parent = :id_category
		AND faq_cats.id IN :authorized_categories
		ORDER BY faq_cats.id_parent', array(
			'id_category' => $this->category->get_id(),
			'authorized_categories' => $authorized_categories
		));
		
		$nbr_cat_displayed = 0;
		while ($row = $result->fetch())
		{
			$category_image = new Url($row['image']);
			
			$this->tpl->assign_block_vars('sub_categories_list', array(
				'C_MORE_THAN_ONE_QUESTION' => $row['questions_number'] > 1,
				'CATEGORY_NAME' => $row['name'],
				'CATEGORY_IMAGE' => $category_image->rel(),
				'CATEGORY_DESCRIPTION' => FormatingHelper::second_parse($row['description']),
				'QUESTIONS_NUMBER' => $row['questions_number'],
				'U_CATEGORY' => FaqUrlBuilder::display_category($row['id'], $row['rewrited_name'])->rel()
			));
			
			$nbr_cat_displayed++;
		}
		$result->dispose();
		
		$nbr_column_cats = ($nbr_cat_displayed > $config->get_columns_number_per_line()) ? $config->get_columns_number_per_line() : $nbr_cat_displayed;
		$nbr_column_cats = !empty($nbr_column_cats) ? $nbr_column_cats : 1;
		$cats_columns_width = floor(100 / $nbr_column_cats);
		
		$result = PersistenceContext::get_querier()->select('SELECT *
		FROM '. FaqSetup::$faq_table .' faq
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = faq.author_user_id
		WHERE approved = 1
		AND faq.id_category = :id_category
		ORDER BY q_order ASC', array(
			'id_category' => $this->get_category()->get_id()
		));
		
		$category_description = FormatingHelper::second_parse($this->get_category()->get_description());
		
		$this->tpl->put_all(array(
			'C_CATEGORY' => true,
			'C_ROOT_CATEGORY' => $this->get_category()->get_id() == Category::ROOT_CATEGORY,
			'C_CATEGORY_DESCRIPTION' => !empty($category_description),
			'C_SUB_CATEGORIES' => $nbr_cat_displayed > 0,
			'C_QUESTIONS' => $result->get_rows_count() > 0,
			'C_MORE_THAN_ONE_QUESTION' => $result->get_rows_count() > 1,
			'C_DISPLAY_TYPE_INLINE' => $config->is_display_type_inline(),
			'CATS_COLUMNS_WIDTH' => $cats_columns_width,
			'CATEGORY_NAME' => $this->get_category()->get_name(),
			'CATEGORY_IMAGE' => $this->get_category()->get_image()->rel(),
			'CATEGORY_DESCRIPTION' => $category_description,
			'QUESTIONS_NUMBER' => $result->get_rows_count()
		));
		
		while ($row = $result->fetch())
		{
			$faq_question = new FaqQuestion();
			$faq_question->set_properties($row);
			
			$this->tpl->assign_block_vars('questions', array_merge($faq_question->get_array_tpl_vars()));
		}
		$result->dispose();
	}
	
	private function get_category()
	{
		if ($this->category === null)
		{
			$id = AppContext::get_request()->get_getint('id_category', 0);
			if (!empty($id))
			{
				try {
					$this->category = FaqService::get_categories_manager()->get_categories_cache()->get_category($id);
				} catch (CategoryNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
   					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->category = FaqService::get_categories_manager()->get_categories_cache()->get_category(Category::ROOT_CATEGORY);
			}
		}
		return $this->category;
	}
	
	private function check_authorizations()
	{
		$id_category = $this->get_category()->get_id();
		if (!FaqAuthorizationsService::check_authorizations($id_category)->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
	
	private function update_position(HTTPRequestCustom $request)
	{
		$value = '&' . $request->get_value('position', array());
		$array = @explode('&questions_list[]=', $value);
		foreach($array as $position => $id)
		{
			if ($position > 0)
				FaqService::update_position($id, $position);
		}
	}
	
	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->tpl);
		
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->get_category()->get_name(), $this->lang['module_title']);
		$graphical_environment->get_seo_meta_data()->set_description($this->get_category()->get_description());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(FaqUrlBuilder::display_category($this->get_category()->get_id(), $this->get_category()->get_rewrited_name(), AppContext::get_request()->get_getint('page', 1)));
		
		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], FaqUrlBuilder::home());
		
		$categories = array_reverse(FaqService::get_categories_manager()->get_parents($this->get_category()->get_id(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), FaqUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
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
