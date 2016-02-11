<?php
/*##################################################
 *                               AdminForumConfigController.class.php
 *                            -------------------
 *   begin                : February 25, 2015
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

class AdminForumConfigController extends AdminModuleController
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
	 * @var ForumConfig
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
			$this->form->get_field_by_id('message_before_topic_title')->set_hidden(!$this->config->is_message_before_topic_title_displayed());
			$this->form->get_field_by_id('message_when_topic_is_unsolved')->set_hidden(!$this->config->is_message_before_topic_title_displayed());
			$this->form->get_field_by_id('message_when_topic_is_solved')->set_hidden(!$this->config->is_message_before_topic_title_displayed());
			$this->form->get_field_by_id('message_before_topic_title_icon_displayed')->set_hidden(!$this->config->is_message_before_topic_title_displayed());
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}
		
		$tpl->put('FORM', $this->form->display());
		
		return new AdminForumDisplayResponse($tpl, $this->lang['module_config_title']);
	}
	
	private function init()
	{
		$this->config = ForumConfig::load();
		$this->lang = LangLoader::get('common', 'forum');
		$this->admin_common_lang = LangLoader::get('admin-common');
	}
	
	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHTML('config', $this->admin_common_lang['configuration']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldTextEditor('forum_name', $this->lang['config.forum_name'], $this->config->get_forum_name(), 
			array('maxlength' => 255, 'required' => true)
		));
		
		$fieldset->add_field(new FormFieldNumberEditor('number_topics_per_page', $this->lang['config.number_topics_per_page'], $this->config->get_number_topics_per_page(), 
			array('min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));
		
		$fieldset->add_field(new FormFieldNumberEditor('number_messages_per_page', $this->lang['config.number_messages_per_page'], $this->config->get_number_messages_per_page(), 
			array('min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));
		
		$fieldset->add_field(new FormFieldNumberEditor('read_messages_storage_duration', $this->lang['config.read_messages_storage_duration'], $this->config->get_read_messages_storage_duration(), 
			array('min' => 1, 'max' => 365, 'required' => true, 'description' => $this->lang['config.read_messages_storage_duration.explain']),
			array(new FormFieldConstraintIntegerRange(1, 365))
		));
		
		$fieldset->add_field(new FormFieldNumberEditor('max_topic_number_in_favorite', $this->lang['config.max_topic_number_in_favorite'], $this->config->get_max_topic_number_in_favorite(), 
			array('min' => 1, 'max' => 500, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 500))
		));
		
		$fieldset->add_field(new FormFieldCheckbox('edit_mark_enabled', $this->lang['config.edit_mark_enabled'], $this->config->is_edit_mark_enabled()));
		
		$fieldset->add_field(new FormFieldCheckbox('multiple_posts_allowed', $this->lang['config.multiple_posts_allowed'], $this->config->are_multiple_posts_allowed(),
			array('description' => $this->lang['config.multiple_posts_allowed.explain'])
		));
		
		$fieldset->add_field(new FormFieldCheckbox('connexion_form_displayed', $this->lang['config.connexion_form_displayed'], $this->config->is_connexion_form_displayed()));
		
		$fieldset->add_field(new FormFieldCheckbox('left_column_disabled', $this->lang['config.left_column_disabled'], $this->config->is_left_column_disabled()));
		
		$fieldset->add_field(new FormFieldCheckbox('right_column_disabled', $this->lang['config.right_column_disabled'], $this->config->is_right_column_disabled()));
		
		$fieldset->add_field(new FormFieldCheckbox('message_before_topic_title_displayed', $this->lang['config.message_before_topic_title_displayed'], $this->config->is_message_before_topic_title_displayed(), array(
			'events' => array('click' => '
				if (HTMLForms.getField("message_before_topic_title_displayed").getValue()) {
					HTMLForms.getField("message_before_topic_title").enable();
					HTMLForms.getField("message_when_topic_is_unsolved").enable();
					HTMLForms.getField("message_when_topic_is_solved").enable();
					HTMLForms.getField("message_before_topic_title_icon_displayed").enable();
				} else {
					HTMLForms.getField("message_before_topic_title").disable();
					HTMLForms.getField("message_when_topic_is_unsolved").disable();
					HTMLForms.getField("message_when_topic_is_solved").disable();
					HTMLForms.getField("message_before_topic_title_icon_displayed").disable();
				}'
			)
		)));
		
		$fieldset->add_field(new FormFieldTextEditor('message_before_topic_title', $this->lang['config.message_before_topic_title'], $this->config->get_message_before_topic_title(), 
			array('maxlength' => 255, 'required' => true)
		));
		
		$fieldset->add_field(new FormFieldTextEditor('message_when_topic_is_unsolved', $this->lang['config.message_when_topic_is_unsolved'], $this->config->get_message_when_topic_is_unsolved(), 
			array('maxlength' => 255, 'required' => true)
		));
		
		$fieldset->add_field(new FormFieldTextEditor('message_when_topic_is_solved', $this->lang['config.message_when_topic_is_solved'], $this->config->get_message_when_topic_is_solved(), 
			array('maxlength' => 255, 'required' => true)
		));
		
		$fieldset->add_field(new FormFieldCheckbox('message_before_topic_title_icon_displayed', $this->lang['config.message_before_topic_title_icon_displayed'], $this->config->is_message_before_topic_title_icon_displayed(),
			array('description' => $this->lang['config.message_before_topic_title_icon_displayed.explain'])
		));
		
		$common_lang = LangLoader::get('common');
		$fieldset_authorizations = new FormFieldsetHTML('authorizations_fieldset', $common_lang['authorizations']);
		$form->add_fieldset($fieldset_authorizations);
		
		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($common_lang['authorizations.read'], Category::READ_AUTHORIZATIONS),
			new ActionAuthorization($common_lang['authorizations.write'], Category::WRITE_AUTHORIZATIONS),
			new ActionAuthorization($common_lang['authorizations.moderation'], Category::MODERATION_AUTHORIZATIONS),
			new ActionAuthorization($this->lang['authorizations.read_topics_content'], ForumAuthorizationsService::READ_TOPICS_CONTENT_AUTHORIZATIONS),
			new ActionAuthorization($this->lang['authorizations.flood'], ForumAuthorizationsService::FLOOD_AUTHORIZATIONS),
			new ActionAuthorization($this->lang['authorizations.hide_edition_mark'], ForumAuthorizationsService::HIDE_EDITION_MARK_AUTHORIZATIONS),
			new ActionAuthorization($this->lang['authorizations.unlimited_topics_tracking'], ForumAuthorizationsService::UNLIMITED_TOPICS_TRACKING_AUTHORIZATIONS),
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
		$this->config->set_forum_name($this->form->get_value('forum_name'));
		$this->config->set_number_topics_per_page($this->form->get_value('number_topics_per_page'));
		$this->config->set_number_messages_per_page($this->form->get_value('number_messages_per_page'));
		$this->config->set_read_messages_storage_duration($this->form->get_value('read_messages_storage_duration'));
		$this->config->set_max_topic_number_in_favorite($this->form->get_value('max_topic_number_in_favorite'));
		
		if ($this->form->get_value('edit_mark_enabled'))
			$this->config->enable_edit_mark();
		else
			$this->config->disable_edit_mark();
		
		if ($this->form->get_value('multiple_posts_allowed'))
			$this->config->allow_multiple_posts();
		else
			$this->config->forbid_multiple_posts();
		
		if ($this->form->get_value('connexion_form_displayed'))
			$this->config->display_connexion_form();
		else
			$this->config->hide_connexion_form();
		
		if ($this->form->get_value('left_column_disabled'))
			$this->config->disable_left_column();
		else
			$this->config->enable_left_column();
		
		if ($this->form->get_value('right_column_disabled'))
			$this->config->disable_right_column();
		else
			$this->config->enable_right_column();
		
		if ($this->form->get_value('message_before_topic_title_displayed'))
		{
			$this->config->display_message_before_topic_title();
			$this->config->set_message_before_topic_title($this->form->get_value('message_before_topic_title'));
			$this->config->set_message_when_topic_is_unsolved($this->form->get_value('message_when_topic_is_unsolved'));
			$this->config->set_message_when_topic_is_solved($this->form->get_value('message_when_topic_is_solved'));
			
			if ($this->form->get_value('message_before_topic_title_icon_displayed'))
				$this->config->display_message_before_topic_title_icon();
			else
				$this->config->hide_message_before_topic_title_icon();
		}
		else
			$this->config->hide_message_before_topic_title();
		
		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());
		
		ForumConfig::save();
		ForumService::get_categories_manager()->regenerate_cache();
	}
}
?>
