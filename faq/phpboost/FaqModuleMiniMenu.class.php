<?php
/*##################################################
 *                               FaqModuleMiniMenu.class.php
 *                            -------------------
 *   begin                : September 2, 2014
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

class FaqModuleMiniMenu extends ModuleMiniMenu
{
	public function get_default_block()
	{
		return self::BLOCK_POSITION__LEFT;
	}
	
	public function get_menu_id()
	{
		return 'module-mini-faq';
	}
	
	public function get_menu_title()
	{
		return LangLoader::get_message('faq.random_question', 'common', 'faq');
	}
	
	public function is_displayed()
	{
		return FaqAuthorizationsService::check_authorizations()->read();
	}
	
	public function get_menu_content()
	{
		//Create file template
		$tpl = new FileTemplate('faq/FaqModuleMiniMenu.tpl');
		
		//Assign the lang file to the tpl
		$tpl->add_lang(LangLoader::get('common', 'faq'));
		
		//Load module cache
		$faq_cache = FaqCache::load();
		
		//Get authorized categories for the current user
		$authorized_categories = FaqService::get_authorized_categories(Category::ROOT_CATEGORY);
		
		$categories = array_intersect($faq_cache->get_categories(), $authorized_categories);
		
		if (!empty($categories))
		{
			$id_category = $categories[array_rand($categories)];
			$category_questions = $faq_cache->get_category_questions($id_category);
			$random_question = $category_questions[array_rand($category_questions)];
			
			if (!empty($random_question))
			{
				$category = FaqService::get_categories_manager()->get_categories_cache()->get_category($id_category);
				
				$tpl->put_all(array(
					'C_QUESTION' => true,
					'QUESTION' => $random_question['question'],
					'U_LINK' => FaqUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $random_question['id'])->rel()
				));
			}
		}
		
		return $tpl->render();
	}
}
?>
