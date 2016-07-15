<?php
/*##################################################
 *                               AdminMediaConfigController.class.php
 *                            -------------------
 *   begin                : February 3, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */

class AdminMediaConfigController extends AdminModuleController
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
	 * @var MediaConfig
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
			$this->form->get_field_by_id('notation_scale')->set_hidden(!$this->config->is_notation_enabled());
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}
		
		$tpl->put('FORM', $this->form->display());
		
		return new AdminMediaDisplayResponse($tpl, $this->lang['module_config_title']);
	}
	
	private function init()
	{
		$this->config = MediaConfig::load();
		$this->lang = LangLoader::get('common', 'media');
		$this->admin_common_lang = LangLoader::get('admin-common');
	}
	
	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHTML('config', $this->admin_common_lang['configuration']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldNumberEditor('items_number_per_page', $this->admin_common_lang['config.items_number_per_page'], $this->config->get_items_number_per_page(), 
			array('min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));
		
		$fieldset->add_field(new FormFieldNumberEditor('categories_number_per_page', $this->admin_common_lang['config.categories_number_per_page'], $this->config->get_categories_number_per_page(),
			array('min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));
		
		$fieldset->add_field(new FormFieldNumberEditor('columns_number_per_line', $this->admin_common_lang['config.columns_number_per_line'], $this->config->get_columns_number_per_line(),
			array('min' => 1, 'max' => 4, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 4))
		));
		
		$fieldset->add_field(new FormFieldCheckbox('author_displayed', $this->admin_common_lang['config.author_displayed'], $this->config->is_author_displayed()));
		
		$fieldset->add_field(new FormFieldCheckbox('comments_enabled', $this->admin_common_lang['config.comments_enabled'], $this->config->are_comments_enabled()));
		
		$fieldset->add_field(new FormFieldCheckbox('notation_enabled', $this->admin_common_lang['config.notation_enabled'], $this->config->is_notation_enabled(), array(
			'events' => array('click' => '
				if (HTMLForms.getField("notation_enabled").getValue()) {
					HTMLForms.getField("notation_scale").enable();
				} else {
					HTMLForms.getField("notation_scale").disable();
				}'
			)
		)));
		
		$fieldset->add_field(new FormFieldNumberEditor('notation_scale', $this->admin_common_lang['config.notation_scale'], $this->config->get_notation_scale(), 
			array('min' => 3, 'max' => 20, 'required' => true, 'hidden' => !$this->config->is_notation_enabled()),
			array(new FormFieldConstraintIntegerRange(3, 20))
		));
		
		$fieldset->add_field(new FormFieldNumberEditor('max_video_width', $this->lang['config.max_video_width'], $this->config->get_max_video_width(), 
			array('min' => 50, 'max' => 2000, 'required' => true),
			array(new FormFieldConstraintIntegerRange(50, 2000))
		));
		
		$fieldset->add_field(new FormFieldNumberEditor('max_video_height', $this->lang['config.max_video_height'], $this->config->get_max_video_height(), 
			array('min' => 50, 'max' => 2000, 'required' => true),
			array(new FormFieldConstraintIntegerRange(50, 2000))
		));
		
		$fieldset->add_field(new FormFieldRichTextEditor('root_category_description', $this->admin_common_lang['config.root_category_description'], $this->config->get_root_category_description(), 
			array('rows' => 8, 'cols' => 47)
		));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('root_category_content_type', $this->lang['config.root_category_content_type'], $this->config->get_root_category_content_type(),
			array(
				new FormFieldSelectChoiceOption($this->lang['content_type.music_and_video'], MediaConfig::CONTENT_TYPE_MUSIC_AND_VIDEO),
				new FormFieldSelectChoiceOption($this->lang['content_type.music'], MediaConfig::CONTENT_TYPE_MUSIC),
				new FormFieldSelectChoiceOption($this->lang['content_type.video'], MediaConfig::CONTENT_TYPE_VIDEO)
			)
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
			new ActionAuthorization($common_lang['authorizations.categories_management'], Category::CATEGORIES_MANAGEMENT_AUTHORIZATIONS)
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
		$this->config->set_items_number_per_page($this->form->get_value('items_number_per_page'));
		$this->config->set_categories_number_per_page($this->form->get_value('categories_number_per_page'));
		$this->config->set_columns_number_per_line($this->form->get_value('columns_number_per_line'));
		
		if ($this->form->get_value('author_displayed'))
			$this->config->display_author();
		else
			$this->config->hide_author();
		
		if ($this->form->get_value('comments_enabled'))
			$this->config->enable_comments();
		else
			$this->config->disable_comments();
		
		if ($this->form->get_value('notation_enabled'))
		{
			$this->config->enable_notation();
			$this->config->set_notation_scale($this->form->get_value('notation_scale'));
			if ($this->form->get_value('notation_scale') != $this->config->get_notation_scale())
				NotationService::update_notation_scale('media', $this->config->get_notation_scale(), $this->form->get_value('notation_scale'));
		}
		else
			$this->config->disable_notation();
		
		$this->config->set_max_video_width($this->form->get_value('max_video_width'));
		$this->config->set_max_video_height($this->form->get_value('max_video_height'));
		$this->config->set_root_category_description($this->form->get_value('root_category_description'));
		$this->config->set_root_category_content_type($this->form->get_value('root_category_content_type')->get_raw_value());
		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());
		
		MediaConfig::save();
		MediaService::get_categories_manager()->regenerate_cache();
	}
}
?>
