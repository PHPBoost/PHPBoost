<?php
/*##################################################
 *		                   ArticlesAdminEditCategoryController.class.php
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
class ArticlesAdminEditCategoryController extends AdminModuleController
{
	private $lang;
	private $tpl;
	private $form;
	private $submit_button;
	
	function execute(HTTPRequest $request)
	{
		$id_category = $request->get_string('id', 0);
		$this->init();
		
		if (!ArticlesCategoriesService::category_exist($id_category))
		{
			$controller = new UserErrorController(LangLoader::get_message('error', 'errors'), $this->lang['category_inexistent']);
			DispatchManager::redirect($controller);
		}
		
		$this->build_form($id_category);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->update();
			$tpl->put('MSG', MessageHelper::display($this->lang['edit_category.success-saving'], MessageHelper::SUCCESS, 4));
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
		$this->form = new HTMLForm('edit_category');
		
		$row = ArticlesCategoriesService::get_category($id_category);
		
		$fieldset = new FormFieldsetHTML('edit_category', $this->lang['categories']);
		$this->form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldTextEditor('category_name', $this->lang['category_name'], $row['name'],
			array('class' => 'text', 'maxlength' => 100, 'size' => 65, 'required' => true)
		));
		
		$fieldset->add_field(new ArticlesFormFieldSelectCategories('category_location', $this->lang['category_location'], $row['c_order'], 
			array('required' => true)
		));
		
		$pos = (strpos($row['picture_path'], '/') !== false);
		if ($pos)
		{
			$icons_selected = '--';
			$other_picture_path = $row['picture_path'];
		}
		else
		{
			$icon_selected = $row['picture_path'];
			$other_picture_path = '';
		}
		$fieldset->add_field(new ArticlesFormFieldSelectIcons('category_icon', $this->lang['category_icon'], $row['picture_path'],
			array('events' => array('change' => 
									'if (HTMLForms.getField("category_icon").getValue() == "--")
									{
										HTMLForms.getField("category_icon_path").enable();
									}
									else
									{
										HTMLForms.getField("category_icon_path").disable();
										HTMLForms.getField("category_icon").setValue("");
									}'))
		));
		
		 
		$fieldset->add_field(new FormFieldTextEditor('category_icon_path', $this->lang['category_icon_path'], '',
			array('class' => 'small_text', 'size' => 40, 'required' => false, 'hidden' => true), 
			array('events' => array('click' => 'HTMLForms.getField("category_icon").setValue("--");'))
		));
		
		$fieldset->add_field(new FormFieldRichTextEditor('category_description', $this->lang['category_description'], $row['description'], 
			array('class' => 'text', 'rows' => 16, 'cols' => 47)
		));
		
		$fieldset->add_field(new FormFieldCheckbox('category_notation', $this->lang['category_notation'], $row['notation_disabled']));
		
		$fieldset->add_field(new FormFieldCheckbox('category_comments', $this->lang['category_comments'], $row['comments_disabled']));
		
		$fieldset->add_field(new FormFieldCheckbox('category_publishing_state', $this->lang['category_publishing_state'], $row['published']));
		
		$fieldset = new FormFieldsetHTML('authorizations', $this->lang['special_authorizations']);
		$this->form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldCheckbox('assign_special_authorizations', $this->lang['special_authorizations'], FormFieldCheckbox::UNCHECKED,
			array('events' => array('click' => 
				'if (HTMLForms.getField("assign_special_authorizations").getValue()) { 
					HTMLForms.getField("special_authorizations").enable(); 
				} else { 
					HTMLForms.getField("special_authorizations").disable(); 
				}'))
		));
		
		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($this->lang['articles_configuration.authorizations-read'], ArticlesAuthorizationsService::AUTHORIZATIONS_READ),
			new ActionAuthorization($this->lang['articles_configuration.config.authorizations-contribution'], ArticlesAuthorizationsService::AUTHORIZATIONS_CONTRIBUTION),
			new ActionAuthorization($this->lang['articles_configuration.config.authorizations-write'], ArticlesAuthorizationsService::AUTHORIZATIONS_WRITE),
			new ActionAuthorization($this->lang['articles_configuration.config.authorizations-moderation'], ArticlesAuthorizationsService::AUTHORIZATIONS_MODERATION)
		));
		
		$auth_settings->build_from_auth_array($row['authorizations']);
		$auth_setter = new FormFieldAuthorizationsSetter('special_authorizations', $auth_settings, array('hidden' => true));
		$fieldset->add_field($auth_setter);  
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$this->form->add_button($this->submit_button);
		$this->form->add_button(new FormButtonReset());
		
		$this->form = $form;
	}
	
	private function update()
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
		
		ArticlesCategoriesService::update($category);
	}
}
?>