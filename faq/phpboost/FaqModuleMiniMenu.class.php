<?php
/*##################################################
 *                               FaqModuleMiniMenu.class.php
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

class FaqModuleMiniMenu extends ModuleMiniMenu
{
	public function get_default_block()
	{
		return self::BLOCK_POSITION__LEFT;
	}
	
	public function display($tpl = false)
	{
		if (FaqAuthorizationsService::check_authorizations()->read())
		{
			//Load module lang
			$lang = LangLoader::get('common', 'faq');
			
			//Create file template
			$tpl = new FileTemplate('faq/FaqModuleMiniMenu.tpl');
			
			//Assign the lang file to the tpl
			$tpl->add_lang($lang);
			
			//Assign menu default position
			MenuService::assign_positions_conditions($tpl, $this->get_block());
			
			//Load module cache
			$faq_cache = FaqCache::load();
			
			//Get authorized categories for the current user
			$authorized_categories = FaqService::get_authorized_categories(Category::ROOT_CATEGORY);
			
			$categories = array_intersect($faq_cache->get_categories(), $authorized_categories);
			
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
			
			return $tpl->render();
		}
		return '';
	}
}
?>
