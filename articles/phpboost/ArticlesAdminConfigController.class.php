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
class ArticlesAdminConfigController extends AdminController
{
	private $lang;
	private $articles_config;
	private $tpl;
	private $form;
	private $submit_button;
	
	function execute(HTTPRequest $request)
	{
		$this->init();
		$this->build_form();

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$tpl->put('MSG', MessageHelper::display($this->lang['articles_configuration.success-saving'], MessageHelper::SUCCESS, 4));
		}
		
		 $this->tpl->put('FORM', $this->form->display());

         return new AdminArticlesDisplayResponse($this->tpl, $this->lang['articles_configuration']);
	}
	
	private function init()
	{	
		$this->tpl = new StringTemplate('#INCLUDE MSG# #INCLUDE FORM#');
		$this->load_lang();
		$this->tpl->add_lang($this->lang);
		$this->load_config();
	}
	
	private function load_lang()
	{
		$this->lang = LangLoader::get('articles-common');
	}
	
	private function load_config()
	{
		$this->articles_config = ArticlesConfig::load();
	}
	
	private function build_form()
	{
		$form = new HTMLForm('articles_configuration');
		
		$fieldset = new FormFieldsetHTML('articles_configuration', $this->lang['articles_configuration']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldTextEditor('number_articles_per_page', $this->lang['articles_configuration.number_articles_per_page'], $this->articles_config->get_number_articles_per_page(),
			array('class' => 'text', 'maxlength' => 3, 'size' => 4, 'required' => true)
		));
		
		$fieldset->add_field(new FormFieldTextEditor('number_categories_per_page', $this->lang['articles_configuration.number_categories_per_page'], $this->articles_config->get_number_categories_per_page(),
			array('class' => 'text', 'maxlength' => 3, 'size' => 4, 'required' => true)
		));
		
		$fieldset->add_field(new FormFieldTextEditor('number_columns_displayed', $this->lang['articles_configuration.number_columns_displayed'], $this->articles_config->get_number_columns_displayed(),
			array('class' => 'text', 'maxlength' => 3, 'size' => 4, 'required' => true)
		));
		
		$fieldset->add_field(new FormFieldTextEditor('notation_scale', $this->lang['articles_configuration.notation_scale'], $this->articles_config->get_notation_scale(),
			array('class' => 'text', 'maxlength' => 2, 'size' => 4, 'required' => true)
		));
		
		$fieldset = new FormFieldsetHTML('authorizations', $this->lang['articles_configuration_authorizations'],
			array('description' => $this->lang['articles_configuration.authorizations_explain']));
		$form->add_fieldset($fieldset);
		
		
		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($this->lang['articles_configuration.authorizations-read'], ArticlesAuthorizationsService::AUTHORIZATIONS_READ),
			new ActionAuthorization($this->lang['articles_configuration.config.authorizations-contribution'], ArticlesAuthorizationsService::AUTHORIZATIONS_CONTRIBUTION),
			new ActionAuthorization($this->lang['articles_configuration.config.authorizations-write'], ArticlesAuthorizationsService::AUTHORIZATIONS_WRITE),
			new ActionAuthorization($this->lang['articles_configuration.config.authorizations-moderation_contributions'], ArticlesAuthorizationsService::AUTHORIZATIONS_MODERATION_CONTRIBUTIONS)
		));
		
		$auth_settings->build_from_auth_array($this->articles_config->get_authorizations());
		$auth_setter = new FormFieldAuthorizationsSetter('authorizations', $auth_settings);
		$fieldset->add_field($auth_setter);  
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());
		
		$this->form = $form;
	}
	
	private function save()
	{
		$this->articles_config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());
		$this->articles_config->set_number_articles_per_page($this->form->get_value('number_articles_per_page'));
		$this->articles_config->set_number_categories_per_page($this->form->get_value('number_categories_per_page'));
		$this->articles_config->set_number_columns_displayed($this->form->get_value('number_columns_displayed'));
		$this->articles_config->set_notation_scale($this->form->get_value('notation_scale'));
		
		ArticlesConfig::save();
	}
}
?>