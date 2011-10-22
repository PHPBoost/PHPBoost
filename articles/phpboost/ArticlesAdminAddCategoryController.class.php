<?php
/*##################################################
 *		                   ArticlesAdminConfigController.class.php
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
class ArticlesAdminConfigController extends AdminModuleController
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
		$this->tpl = new StringTemplate('
		<script type="text/javascript">
        <!--
        	Event.observe(window, \'load\', function() {
            	$("add_category_special_authorizations").fade({duration: 0.2});
            });
        -->
        </script>#INCLUDE MSG# #INCLUDE FORM#');
		$this->load_lang();
		$this->tpl->add_lang($this->lang);
	}
	
	private function load_lang()
	{
		$this->lang = LangLoader::get('articles-common');
	}
	
	private function build_form()
	{
		$form = new HTMLForm('add_category');
		
		$fieldset = new FormFieldsetHTML('add_category', $this->lang['add_category.categories']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldTextEditor('category_name', $this->lang['add_category.category_name'], '',
			array('class' => 'text', 'maxlength' => 100, 'size' => 65, 'required' => true)
		));
		
		$fieldset->add_field(new ArticlesFormFieldSelectCategories('category_location', $this->lang['add_category.category_location'], 'Racine', 
			array('required' => true)
		));
		
		$fieldset->add_field(new ArticlesFormFieldSelectCategoryIcons('category_icon', $this->lang['add_category.category_icon'], 'articles.png'));
		
		$fieldset->add_field(new FormFieldTextEditor('category_icon_path', $this->lang['add_category.category_icon_path'], '',
			array('class' => 'small_text', 'size' => 40, 'required' => false)
		));
		
		$fieldset->add_field(new FormFieldRichTextEditor('category_description', $this->lang['add_category.category_description'], '', 
			array('class' => 'text', 'rows' => 16, 'cols' => 47)
		));
		
		$default_option_notation = new FormFieldRadioChoiceOption('Oui', 1);
		$fieldset->add_field(new FormFieldRadioChoice('category_notation', $this->lang['add_category.category_notation'], $default_option_notation, 
			array($default_option_notation,
				  new FormFieldRadioChoiceOption('Non', 0))
		));
		
		$default_option_comments = new FormFieldRadioChoiceOption('Oui', 1);
		$fieldset->add_field(new FormFieldRadioChoice('category_comments', $this->lang['add_category.category_comments'], $default_option_comments, 
			array($default_option_comments,
				  new FormFieldRadioChoiceOption('Non', 0))
		));
		
		$default_option_publication = new FormFieldRadioChoiceOption('Oui', 1);
		$fieldset->add_field(new FormFieldRadioChoice('category_publishing_state', $this->lang['add_category.category_publishing_state'], $default_option_publication, 
			array($default_option_publication,
				  new FormFieldRadioChoiceOption('Non', 0))
		));
		
		$fieldset = new FormFieldsetHTML('special_authorizations', $this->lang['add_category.special_authorizations']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldCheckbox('assign_special_authorizations', $this->lang['add_category.special_authorizations'], FormFieldCheckbox::UNCHECKED,
			array('events' => array('click' => 
				'if (HTMLForms.getField("assign_special_authorizations").getValue()) { 
					$("add_category_special_authorizations").appear(); 
				} else { 
					$("add_category_special_authorizations").fade(); 
				}'))
		));
		
		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($this->lang['articles_configuration.authorizations-read'], ArticlesAuthorizationsService::AUTHORIZATIONS_READ),
			new ActionAuthorization($this->lang['articles_configuration.config.authorizations-contribution'], ArticlesAuthorizationsService::AUTHORIZATIONS_CONTRIBUTION),
			new ActionAuthorization($this->lang['articles_configuration.config.authorizations-write'], ArticlesAuthorizationsService::AUTHORIZATIONS_WRITE),
			new ActionAuthorization($this->lang['articles_configuration.config.authorizations-moderation_contributions'], ArticlesAuthorizationsService::AUTHORIZATIONS_MODERATION_CONTRIBUTIONS)
		));
		
		$article_config = ArticlesConfig::load();
		
		$auth_settings->build_from_auth_array($articles_config->get_authorizations());
		$auth_setter = new FormFieldAuthorizationsSetter('special_authorizations', $auth_settings);
		$fieldset->add_field($auth_setter);  
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());
		
		$this->form = $form;
	}
	
	private function add()
	{
		$category = new ArticlesCategory();
		
		$category->set_name($this->form->get_value('category_name'));
		$category->set_id_parent($this->form->get_value('category_location')->get_raw_value());
		$category->set_picture_path($this->form->get_value('category_icon')->get_raw_value());
		
		if ($this->form->get_value('category_icon_path') != '')
		{
			$category->set_picture_path($this->form->get_value('category_icon_path'));
		}
		else
		{
			$category->set_picture_path($this->form->get_value('category_icon')->get_raw_value());
		}
		
		$category->set_description($this->form->get_value('category_description'));
		$category->set_authorizations($this->form->get_value('special_authorizations')->build_auth_array());
		$category->set_disable_notation_system($this->form->get_value('category_notation')->get_raw_value());
		$category->set_disable_comments_system($this->form->get_value('category_comments')->get_raw_value());
		$category->set_publishing_state($this->form->get_value('category_publishing_state')->get_raw_value());
		
		ArticlesCategoriesService::add($category);
	}
}
?>