<?php
/*##################################################
 *		                   ArticlesAdminDeleteCategoryController.class.php
 *                            -------------------
 *   begin                : October 25, 2011
 *   copyright            : (C) 2011 Patrick DUBEAU
 *   email                : daaxwizeman@gmail.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/
class ArticlesAdminDeleteCategoryController extends AdminModuleController
{
	private $lang;
	private $form;
	private $submit_button;
	
	function execute(HTTPRequest $request)
	{
		$id_category = $request->get_string('id', 0);
		$this->init();
		
		if (!ArticlesCategoriesService::exists($id_category))
		{
			$controller = new UserErrorController(LangLoader::get_message('error', 'errors'), $this->lang['category_inexistent']);
			DispatchManager::redirect($controller);
		}
		
		if (ArticlesCategoriesService::number_articles_contained($id_category) > 0)
		{
			$this->build_form($id_category);
		}
		else
		{
			ArticlesCategoriesService::delete($id_category);
		}
		

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			if ($this->form->get_value('choice_solution')->get_raw_value() == '1')
			{
				$this->move($id_category);
			}
			else 
			{
				ArticlesCategoriesService::delete($id_category);
			}
			
			$tpl->put('MSG', MessageHelper::display($this->lang['delete_category.success-saving'], MessageHelper::SUCCESS, 4));
		}
		
		 $this->tpl->put('FORM', $this->form->display());

         return new AdminArticlesDisplayResponse($this->tpl, $this->lang['edit_category']);
	}
	
	private function init()
	{	
		$this->tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$this->load_lang();
		$this->tpl->add_lang($this->lang);
	}
	
	private function load_lang()
	{
		$this->lang = LangLoader::get('articles-common', 'articles');
	}
	
	private function build_form($id_category)
	{
		
		$this->form = new HTMLForm('delete_category');
		
		$category = new ArticlesCategory();
		$category = ArticlesCategoriesService::get_category($id_category);
		
		$fieldset = new FormFieldsetHTML('delete_category', $this->lang['delete_category'], array('description' => $this->lang['delete_category.explain']));
		$this->form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldRadioChoice('choice_solution', $this->lang['delete_category.choice_solution'], $this->lang['delete_category.choice_2'], 
			array(new FormFieldRadioChoiceOption($this->lang['delete_category.choice_1'], '1')),
				  new FormFieldRadioChoiceOption($this->lang['delete_category.choice_2'], '2'), 
			array('events' => array('change' => 
									'if (HTMLForms.getField("choice_solution").getValue() == "2")
									{
										HTMLForms.getField("category_location").enable();
									}
									else
									{
										HTMLForms.getField("category_location").disable();
									}'
									))	
		));
		
		$fieldset->add_field(new ArticlesFormFieldSelectCategories('category_location', $this->lang['category_location'], $this->lang['default_category_location'], 
			array('hidden' => true)
		));
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$this->form->add_button($this->submit_button);
		
		$this->form = $form;
	}
	
	private function move($id_category)
	{
		ArticlesCategoriesService::move($id_category, $this->form->get_value('category_location')->get_raw_value());
		ArticlesCategoriesService::delete($id_category);
	}
}
?>