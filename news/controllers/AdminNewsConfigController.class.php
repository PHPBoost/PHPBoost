<?php
/*##################################################
 *		                         AdminNewsConfigController.class.php
 *                            -------------------
 *   begin                : February 13, 2013
 *   copyright            : (C) 2013 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

/**
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 */
class AdminNewsConfigController extends AdminModuleController
{
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonSubmit
	 */
	private $submit_button;
	
	private $lang;
	
	/**
	 * @var NewsConfig
	 */
	private $config;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$this->build_form();
		
		$tpl = new StringTemplate('# INCLUDE FORM #');
		$tpl->add_lang($this->lang);
		
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
		}
		
		$tpl->put('FORM', $this->form->display());
		
		return new AdminNewsDisplayResponse($tpl, $this->lang['admin.config']);
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'news');
		$this->config = NewsConfig::load();
	}
	
	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHTML('config', $this->lang['admin.config']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldTextEditor('number_news_per_page', $this->lang['admin.config.number_news_per_page'], $this->config->get_number_news_per_page(), 
			array('size' => 6), array(new FormFieldConstraintRegex('`^[0-9]+$`i'))
		));
		
		$fieldset->add_field(new FormFieldTextEditor('number_columns_display_news', $this->lang['admin.config.number_columns_display_news'], $this->config->get_number_columns_display_news(), 
			array('size' => 6), array(new FormFieldConstraintRegex('`^[0-9]+$`i'))
		));
		
		$fieldset->add_field(new FormFieldCheckbox('display_condensed', $this->lang['admin.config.display_condensed'], $this->config->get_display_condensed_enabled(), array(
		'events' => array('click' => '
			if (HTMLForms.getField("display_condensed").getValue()) {
				HTMLForms.getField("number_character_to_cut").enable();
			} else { 
				HTMLForms.getField("number_character_to_cut").disable();
			}'
		))));
		
		$fieldset->add_field(new FormFieldTextEditor('number_character_to_cut', $this->lang['admin.config.number_character_to_cut'], $this->config->get_number_character_to_cut(), 
			array('hidden' => !$this->config->get_display_condensed_enabled(), 'size' => 6), 
			array(new FormFieldConstraintRegex('`^[0-9]+$`i')
		)));
		
		$fieldset->add_field(new FormFieldCheckbox('comments_enabled', $this->lang['admin.config.comments_enabled'], $this->config->get_comments_enabled()));
		
		$fieldset->add_field(new FormFieldCheckbox('news_suggestions_enabled', $this->lang['admin.config.news_suggestions_enabled'], $this->config->get_news_suggestions_enabled()));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('display_type', $this->lang['admin.config.display_type'], $this->config->get_display_type(),
			array(
				new FormFieldSelectChoiceOption($this->lang['admin.config.display_type.block'], NewsConfig::DISPLAY_BLOCK),
				new FormFieldSelectChoiceOption($this->lang['admin.config.display_type.list'], NewsConfig::DISPLAY_LIST),
			)
		));
				
		$fieldset_authorizations = new FormFieldsetHTML('authorizations_fieldset', $this->lang['admin.config.authorizations']);
		$form->add_fieldset($fieldset_authorizations);
		
		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($this->lang['admin.config.authorizations.read'], Category::READ_AUTHORIZATIONS),
			new ActionAuthorization($this->lang['admin.config.authorizations.write'], Category::WRITE_AUTHORIZATIONS),
			new ActionAuthorization($this->lang['admin.config.authorizations.contribution'], Category::CONTRIBUTION_AUTHORIZATIONS),
			new ActionAuthorization($this->lang['admin.config.authorizations.moderation'], Category::MODERATION_AUTHORIZATIONS),
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
		$this->config->set_number_news_per_page($this->form->get_value('number_news_per_page'));
		$this->config->set_number_columns_display_news($this->form->get_value('number_columns_display_news'));
		$this->config->set_display_condensed_enabled($this->form->get_value('display_condensed'));
		$this->config->set_number_character_to_cut($this->form->get_value('number_character_to_cut'));
		$this->config->set_comments_enabled($this->form->get_value('comments_enabled'));
		$this->config->set_news_suggestions_enabled($this->form->get_value('news_suggestions_enabled'));
		$this->config->set_display_type($this->form->get_value('display_type')->get_raw_value());
		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());
		NewsConfig::save();
		NewsService::get_categories_manager()->regenerate_cache();
	}
}
?>