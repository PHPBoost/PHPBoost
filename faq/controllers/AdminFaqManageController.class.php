<?php
/*##################################################
 *                      AdminFaqManageController.class.php
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

class AdminFaqManageController extends AdminModuleController
{
	const NUMBER_ITEMS_PER_PAGE = 20;
	
	private $lang;
	private $view;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$this->build_view($request);
		
		return new AdminFaqDisplayResponse($this->view, $this->lang['faq.management']);
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'faq');
		$this->view = new FileTemplate('faq/AdminFaqManageController.tpl');
		$this->view->add_lang($this->lang);
	}
	
	private function build_view(HTTPRequestCustom $request)
	{
		$categories = FaqService::get_categories_manager()->get_categories_cache()->get_categories();
		
		$mode = $request->get_getvalue('sort', 'desc');
		$field = $request->get_getvalue('field', 'date');
		
		$sort_mode = ($mode == 'asc') ? 'ASC' : 'DESC';
		
		switch ($field)
		{
			case 'category':
				$sort_field = 'id_category';
				break;
			case 'author':
				$sort_field = 'display_name';
				break;
			case 'question':
				$sort_field = 'question';
				break;
			default:
				$sort_field = 'creation_date';
				break;
		}
		
		$page = $request->get_getint('page', 1);
		$pagination = $this->get_pagination($page, $field, $mode);
		
		$result = PersistenceContext::get_querier()->select('SELECT *
		FROM '. FaqSetup::$faq_table .' faq
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = faq.author_user_id
		ORDER BY ' . $sort_field . ' ' . $sort_mode . '
		LIMIT :number_per_page OFFSET :start_limit',
			array(
				'number_per_page' => $pagination->get_number_items_per_page(),
				'start_limit' => $pagination->get_display_from()
		));
		
		while($row = $result->fetch())
		{
			$faq_question = new FaqQuestion();
			$faq_question->set_properties($row);
			
			$this->view->assign_block_vars('questions', $faq_question->get_array_tpl_vars());
		}
		$result->dispose();
		
		$this->view->put_all(array(
			'C_PAGINATION' => $pagination->has_several_pages(),
			'C_QUESTIONS' => !$pagination->current_page_is_empty(),
			'PAGINATION' => $pagination->display(),
			'U_SORT_QUESTION_ASC' => FaqUrlBuilder::manage('question', 'asc', $page)->rel(),
			'U_SORT_QUESTION_DESC' => FaqUrlBuilder::manage('question', 'desc', $page)->rel(),
			'U_SORT_CATEGORY_ASC' => FaqUrlBuilder::manage('category', 'asc', $page)->rel(),
			'U_SORT_CATEGORY_DESC' => FaqUrlBuilder::manage('category', 'desc', $page)->rel(),
			'U_SORT_AUTHOR_ASC' => FaqUrlBuilder::manage('author', 'asc', $page)->rel(),
			'U_SORT_AUTHOR_DESC' => FaqUrlBuilder::manage('author', 'desc', $page)->rel(),
			'U_SORT_DATE_ASC' => FaqUrlBuilder::manage('date', 'asc', $page)->rel(),
			'U_SORT_DATE_DESC' => FaqUrlBuilder::manage('date', 'desc', $page)->rel()
		));
	}
	
	private function get_pagination($page, $sort_field, $sort_mode)
	{
		$faq_questions_number = FaqService::count();
		
		$pagination = new ModulePagination($page, $faq_questions_number, self::NUMBER_ITEMS_PER_PAGE);
		$pagination->set_url(FaqUrlBuilder::manage($sort_field, $sort_mode, '%d'));
		
		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		
		return $pagination;
	}
}
?>
