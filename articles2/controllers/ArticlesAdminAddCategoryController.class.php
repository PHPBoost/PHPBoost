<?php
/*##################################################
 *		                   ArticlesAdminAddCategoryController.class.php
 *                            -------------------
 *   begin                : October 15, 2011
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
class ArticlesAdminAddCategoryController extends AdminModuleController
{
	private $lang;
	private $tpl;
	private $form;
	private $submit_button;
	
	function execute(HTTPRequest $request)
	{
		$this->init();
		$this->build_form();

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->add();
			$tpl->put('MSG', MessageHelper::display($this->lang['add_category.success-saving'], MessageHelper::SUCCESS, 4));
		}
		
		 $this->tpl->put('FORM', $this->form->display());

         return new AdminArticlesDisplayResponse($this->tpl, $this->lang['add_category']);
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
	
	private function build_form()
	{
		$this->form = new HTMLForm('add_category');
		
		$fieldset = new FormFieldsetHTML('add_category', $this->lang['categories']);
		$this->form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldTextEditor('category_name', $this->lang['category_name'], '',
			array('maxlength' => 100, 'size' => 45, 'required' => true)
		));
		
		$fieldset->add_field(new ArticlesFormFieldSelectCategories('category_location', $this->lang['category_location'], $this->lang['default_category_location'], 
			array('required' => true)
		));
		
		$fieldset->add_field(new ArticlesFormFieldSelectIcons('category_icon', $this->lang['add_category.category_icon'], $this->lang['add_category.default_category_icon'],
			array('events' => array('change' => 
									'if (HTMLForms.getField("category_icon").getValue() == "other")
									{
										HTMLForms.getField("category_icon_path").enable();
									}
									else
									{
										HTMLForms.getField("category_icon_path").disable();
									}'
									))
		));
		
		$fieldset->add_field(new FormFieldTextEditor('category_icon_path', $this->lang['category_icon_path'], '',
			array('class' => 'small_text', 'size' => 40, 'required' => false, 'hidden' => true)
		));
		
		$fieldset->add_field(new FormFieldRichTextEditor('category_description', $this->lang['category_description'], '', 
			array('class' => 'text', 'rows' => 16, 'cols' => 47)
		));
		
		$fieldset->add_field(new FormFieldCheckbox('category_notation', $this->lang['category_notation'], ArticlesCategory::ENABLED_NOTATION));
		
		$fieldset->add_field(new FormFieldCheckbox('category_comments', $this->lang['category_comments'], ArticlesCategory::ENABLED_COMMENTS));
		
		$fieldset->add_field(new FormFieldCheckbox('category_publishing_state', $this->lang['category_publishing_state'], ArticlesCategory::PUBLISHED));
		
		$fieldset = new FormFieldsetHTML('authorizations', $this->lang['special_authorizations']);
		$this->form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldCheckbox('assign_special_authorizations', $this->lang['special_authorizations'], FormFieldCheckbox::UNCHECKED,
			array('events' => array('click' => 
				'if (HTMLForms.getField("assign_special_authorizations").getValue()) { 
					HTMLForms.getField("special_authorizations").enable(); 
				} else { 
					HTMLForms.getField("special_authorizations").disable(); 
				}
				'))
		));
		
		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($this->lang['articles_configuration.authorizations-read'], ArticlesAuthorizationsService::AUTHORIZATIONS_READ),
			new ActionAuthorization($this->lang['articles_configuration.config.authorizations-contribution'], ArticlesAuthorizationsService::AUTHORIZATIONS_CONTRIBUTION),
			new ActionAuthorization($this->lang['articles_configuration.config.authorizations-write'], ArticlesAuthorizationsService::AUTHORIZATIONS_WRITE),
			new ActionAuthorization($this->lang['articles_configuration.config.authorizations-moderation'], ArticlesAuthorizationsService::AUTHORIZATIONS_MODERATION)
		));
		
		$articles_config = ArticlesConfig::load();
		$auth_settings->build_from_auth_array($articles_config->get_authorizations());
		$auth_setter = new FormFieldAuthorizationsSetter('special_authorizations', $auth_settings, array('hidden' => true));
		$fieldset->add_field($auth_setter);  
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$this->form->add_button($this->submit_button);
		$this->form->add_button(new FormButtonReset());
	}
	
	private function add()
	{
		$category = new ArticlesCategory();
		
		$category->set_name($this->form->get_value('category_name'));
		$category->set_id_parent($this->form->get_value('category_location')->get_raw_value());
		$category->set_picture_path($this->form->get_value('category_icon')->get_raw_value());
		
		if ($this->form->field_is_disabled('category_icon_path'))
		{
			$category->set_picture_path($this->form->get_value('category_icon_path'));
		}
		else
		{
			$category->set_picture_path($this->form->get_value('category_icon')->get_raw_value());
		}
		
		$category->set_description($this->form->get_value('category_description'));
		$category->set_authorizations($this->form->get_value('special_authorizations')->build_auth_array());
		$category->set_disable_notation_system($this->form->get_value('category_notation'));
		$category->set_disable_comments_system($this->form->get_value('category_comments'));
		$category->set_publishing_state($this->form->get_value('category_publishing_state'));
		
		ArticlesCategoriesService::add($category);
	}
}
?>