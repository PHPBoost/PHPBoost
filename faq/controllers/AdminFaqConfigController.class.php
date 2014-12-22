<?php
/*##################################################
 *                               AdminFaqConfigController.class.php
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

class AdminFaqConfigController extends AdminModuleController
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
	private $admin_common_lang;
	
	/**
	 * @var FaqConfig
	 */
	private $config;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$this->build_form();
		
		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);
		
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}
		
		$tpl->put('FORM', $this->form->display());
		
		return new AdminFaqDisplayResponse($tpl, $this->lang['module_config_title']);
	}
	
	private function init()
	{
		$this->config = FaqConfig::load();
		$this->lang = LangLoader::get('common', 'faq');
		$this->admin_common_lang = LangLoader::get('admin-common');
	}
	
	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHTML('config', $this->admin_common_lang['configuration']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldTextEditor('columns_number_per_line', $this->admin_common_lang['config.columns_number_per_line'], $this->config->get_columns_number_per_line(), 
			array('size' => 6, 'required' => true),
			array(new FormFieldConstraintRegex('`^[0-9]+$`i')
		)));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('display_type', $this->admin_common_lang['config.display_type'], $this->config->get_display_type(),
			array(
				new FormFieldSelectChoiceOption($this->admin_common_lang['config.display_type.inline'], FaqConfig::DISPLAY_TYPE_INLINE),
				new FormFieldSelectChoiceOption($this->admin_common_lang['config.display_type.block'], FaqConfig::DISPLAY_TYPE_BLOCK)
			),
			array('description' => $this->lang['config.display_type.explain'])
		));
		
		$fieldset->add_field(new FormFieldRichTextEditor('root_category_description', $this->lang['config.root_category_description'], $this->config->get_root_category_description(), 
			array('rows' => 8, 'cols' => 47)
		));
		
		$common_lang = LangLoader::get('common');
		$fieldset_authorizations = new FormFieldsetHTML('authorizations_fieldset', $common_lang['authorizations'],
			array('description' => $this->admin_common_lang['config.authorizations.explain'])
		);
		$form->add_fieldset($fieldset_authorizations);
		
		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($common_lang['authorizations.read'], Category::READ_AUTHORIZATIONS),
			new ActionAuthorization($common_lang['authorizations.write'], Category::WRITE_AUTHORIZATIONS),
			new ActionAuthorization($common_lang['authorizations.contribution'], Category::CONTRIBUTION_AUTHORIZATIONS),
			new ActionAuthorization($common_lang['authorizations.moderation'], Category::MODERATION_AUTHORIZATIONS),
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
		$this->config->set_columns_number_per_line($this->form->get_value('columns_number_per_line'));
		$this->config->set_display_type($this->form->get_value('display_type')->get_raw_value());
		$this->config->set_root_category_description($this->form->get_value('root_category_description'));
		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());
		
		FaqConfig::save();
		FaqService::get_categories_manager()->regenerate_cache();
		FaqCache::invalidate();
	}
}
?>
