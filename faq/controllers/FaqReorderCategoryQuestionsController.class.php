<?php
/*##################################################
 *                               FaqReorderCategoryQuestionsController.class.php
 *                            -------------------
 *   begin                : August 2, 2017
 *   copyright            : (C) 2017 Julien BRISWALTER
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

class FaqReorderCategoryQuestionsController extends ModuleController
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
		$this->tpl = new FileTemplate('faq/FaqReorderCategoryQuestionsController.tpl');
		$this->tpl->add_lang($this->lang);
	}
	
	private function build_view(HTTPRequestCustom $request)
	{
		$config = FaqConfig::load();
		
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
			'C_ROOT_CATEGORY' => $this->get_category()->get_id() == Category::ROOT_CATEGORY,
			'C_HIDE_NO_ITEM_MESSAGE' => $this->get_category()->get_id() == Category::ROOT_CATEGORY && !empty($category_description),
			'C_CATEGORY_DESCRIPTION' => !empty($category_description),
			'C_QUESTIONS' => $result->get_rows_count() > 0,
			'C_MORE_THAN_ONE_QUESTION' => $result->get_rows_count() > 1,
			'C_DISPLAY_TYPE_ANSWERS_HIDDEN' => $config->is_display_type_answers_hidden(),
			'ID_CAT' => $this->get_category()->get_id(),
			'CATEGORY_NAME' => $this->get_category()->get_name(),
			'CATEGORY_IMAGE' => $this->get_category()->get_image()->rel(),
			'CATEGORY_DESCRIPTION' => $category_description,
			'U_EDIT_CATEGORY' => $this->get_category()->get_id() == Category::ROOT_CATEGORY ? FaqUrlBuilder::configuration()->rel() : FaqUrlBuilder::edit_category($this->get_category()->get_id())->rel(),
			'QUESTIONS_NUMBER' => $result->get_rows_count()
		));
		
		while ($row = $result->fetch())
		{
			$faq_question = new FaqQuestion();
			$faq_question->set_properties($row);
			
			$this->tpl->assign_block_vars('questions', $faq_question->get_array_tpl_vars());
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
		if (!FaqAuthorizationsService::check_authorizations($id_category)->moderation())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
	
	private function update_position(HTTPRequestCustom $request)
	{
		$questions_list = json_decode(TextHelper::html_entity_decode($request->get_value('tree')));
		foreach($questions_list as $position => $tree)
		{
			FaqService::update_position($tree->id, $position);
		}
	}
	
	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->tpl);
		
		$graphical_environment = $response->get_graphical_environment();
		
		if ($this->get_category()->get_id() != Category::ROOT_CATEGORY)
			$graphical_environment->set_page_title($this->get_category()->get_name(), $this->lang['module_title']);
		else
			$graphical_environment->set_page_title($this->lang['module_title']);
		
		$graphical_environment->get_seo_meta_data()->set_description($this->get_category()->get_description());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(FaqUrlBuilder::display_category($this->get_category()->get_id(), $this->get_category()->get_rewrited_name()));
		
		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], FaqUrlBuilder::home());
		
		$categories = array_reverse(FaqService::get_categories_manager()->get_parents($this->get_category()->get_id(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), FaqUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}
		
		$breadcrumb->add($this->lang['faq.questions_order_management'], FaqUrlBuilder::reorder_questions($this->get_category()->get_id(), $this->get_category()->get_rewrited_name()));
		
		return $response;
	}
}
?>
