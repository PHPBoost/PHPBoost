<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2017 04 13
 * @since   	PHPBoost 4.0 - 2014 09 02
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

		//Assign common menu variables to the tpl
		MenuService::assign_positions_conditions($tpl, $this->get_block());

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
