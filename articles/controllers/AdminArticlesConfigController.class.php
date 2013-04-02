<?php
/*##################################################
 *                   AdminArticlesConfigController.class.php
 *                            -------------------
 *   begin                : February 27, 2013
 *   copyright            : (C) 2013 Patrick DUBEAU
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
class AdminArticlesConfigController extends AdminModuleController
{
	private $lang;
	private $config;
	private $tpl;
	private $form;
	private $submit_button;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->build_form();

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->tpl->put('MSG', MessageHelper::display($this->lang['articles_configuration.success-saving'], MessageHelper::SUCCESS, 4));
		}
		
		 $this->tpl->put('FORM', $this->form->display());

		return new AdminArticlesDisplayResponse($this->tpl, $this->lang['articles_configuration']);
	}
	
	private function init()
	{			
		$this->tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$this->load_lang();
		$this->tpl->add_lang($this->lang);
		$this->load_config();
	}
	
	private function load_lang()
	{
		$this->lang = LangLoader::get('articles-common', 'articles');
	}
	
	private function load_config()
	{
		$this->config = ArticlesConfig::load();
	}
	
	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHTML('articles_configuration', $this->lang['articles_configuration']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldTextEditor('number_articles_per_page', $this->lang['articles_configuration.number_articles_per_page'], $this->config->get_number_articles_per_page(),
			array('maxlength' => 3, 'size' => 4, 'required' => true), array(new FormFieldConstraintRegex('`^[0-9]+$`i'))
		));
		
		$fieldset->add_field(new FormFieldTextEditor('number_categories_per_page', $this->lang['articles_configuration.number_categories_per_page'], $this->config->get_number_categories_per_page(),
			array('maxlength' => 3, 'size' => 4, 'required' => true), array(new FormFieldConstraintRegex('`^[0-9]+$`i'))
		));
		
		$fieldset->add_field(new FormFieldTextEditor('number_columns_displayed', $this->lang['articles_configuration.number_columns_displayed'], $this->config->get_number_columns_displayed(),
			array('maxlength' => 3, 'size' => 4, 'required' => true), array(new FormFieldConstraintRegex('`^[0-9]+$`i'))
		));
		
		$fieldset->add_field(new FormFieldTextEditor('notation_scale', $this->lang['articles_configuration.notation_scale'], $this->config->get_notation_scale(),
			array('maxlength' => 2, 'size' => 4),
			array(new FormFieldConstraintRegex('`^[0-9]+$`i'))
		));
		
		$fieldset->add_field(new FormFieldCheckbox('comments_enabled', $this->lang['articles_configuration.comments_enabled'], $this->config->get_comments_enabled()));
		
		$fieldset_authorizations = new FormFieldsetHTML('authorizations', $this->lang['articles_configuration_authorizations'],
			array('description' => $this->lang['articles_configuration.authorizations.explain'])
		);
		
		$form->add_fieldset($fieldset_authorizations);
			
		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($this->lang['articles_configuration.authorizations.read'], Category::READ_AUTHORIZATIONS),
			new ActionAuthorization($this->lang['articles_configuration.config.authorizations.write'], Category::WRITE_AUTHORIZATIONS),
			new ActionAuthorization($this->lang['articles_configuration.config.authorizations.contribution'], Category::CONTRIBUTION_AUTHORIZATIONS),
			new ActionAuthorization($this->lang['articles_configuration.config.authorizations.moderation'], Category::MODERATION_AUTHORIZATIONS)
		));
		
		$auth_setter = new FormFieldAuthorizationsSetter('authorizations', $auth_settings);
		$auth_settings->build_from_auth_array($this->config->get_authorizations());
		$fieldset_authorizations->add_field($auth_setter);  
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());
		
		$this->form = $form;
	}
	
	private function save()
	{	
		$this->config->set_number_articles_per_page($this->form->get_value('number_articles_per_page'));
		$this->config->set_number_categories_per_page($this->form->get_value('number_categories_per_page'));
		$this->config->set_number_columns_displayed($this->form->get_value('number_columns_displayed'));
		$this->config->set_notation_scale($this->form->get_value('notation_scale'));
		$this->config->set_comments_enabled($this->form->get_value('comments_enabled'));
		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());
		
		ArticlesConfig::save();
		ArticlesService::get_categories_manager()->regenerate_cache();
	}
}
?>