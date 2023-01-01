<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 03 08
 * @since       PHPBoost 4.1 - 2015 02 15
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminForumConfigController extends DefaultAdminModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		$this->build_form();

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->form->get_field_by_id('message_before_topic_title')->set_hidden(!$this->config->is_message_before_topic_title_displayed());
			$this->form->get_field_by_id('message_when_topic_is_unsolved')->set_hidden(!$this->config->is_message_before_topic_title_displayed());
			$this->form->get_field_by_id('message_when_topic_is_solved')->set_hidden(!$this->config->is_message_before_topic_title_displayed());
			$this->form->get_field_by_id('message_before_topic_title_icon_displayed')->set_hidden(!$this->config->is_message_before_topic_title_displayed());
			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.success.config'], MessageHelper::SUCCESS, 5));
		}

		$this->view->put('CONTENT', $this->form->display());

		return new AdminForumDisplayResponse($this->view, $this->lang['form.configuration'] . ': ' . $this->lang['forum.module.title']);
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('config', $this->lang['form.configuration'] . ': ' . $this->lang['forum.module.title']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('forum_name', $this->lang['forum.config.forum.name'], $this->config->get_forum_name(),
			array('maxlength' => 255, 'required' => true)
		));

		$fieldset->add_field(new FormFieldNumberEditor('number_topics_per_page', $this->lang['forum.config.topics.per.page'], $this->config->get_number_topics_per_page(),
			array('min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

		$fieldset->add_field(new FormFieldNumberEditor('number_messages_per_page', $this->lang['forum.config.messages.per.page'], $this->config->get_number_messages_per_page(),
			array('min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

		$fieldset->add_field(new FormFieldNumberEditor('read_messages_storage_duration', $this->lang['forum.config.read.messages.storage'], $this->config->get_read_messages_storage_duration(),
			array('min' => 1, 'max' => 365, 'required' => true, 'description' => $this->lang['forum.config.read.messages.storage.clue']),
			array(new FormFieldConstraintIntegerRange(1, 365))
		));

		$fieldset->add_field(new FormFieldNumberEditor('max_topic_number_in_favorite', $this->lang['forum.config.favorite.topics.number'], $this->config->get_max_topic_number_in_favorite(),
			array('min' => 1, 'max' => 500, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 500))
		));

		$fieldset->add_field(new FormFieldCheckbox('edit_mark_enabled', $this->lang['forum.config.enable.edit.marker'], $this->config->is_edit_mark_enabled(),
			array('class' => 'custom-checkbox')
		));

		$fieldset->add_field(new FormFieldCheckbox('multiple_posts_allowed', $this->lang['forum.config.enable.multiple.posts'], $this->config->are_multiple_posts_allowed(),
			array(
				'class' => 'custom-checkbox',
				'description' => $this->lang['forum.config.enable.multiple.posts.clue'])
		));

		$fieldset->add_field(new FormFieldCheckbox('connexion_form_displayed', $this->lang['forum.config.display.connexion.form'], $this->config->is_connexion_form_displayed(),
			array('class' => 'custom-checkbox')
		));

		$fieldset->add_field(new FormFieldCheckbox('left_column_disabled', StringVars::replace_vars($this->lang['form.hide.left.column'], array('module' => "forum")), $this->config->is_left_column_disabled(),
			array('class' => 'custom-checkbox')
		));

		$fieldset->add_field(new FormFieldCheckbox('right_column_disabled', StringVars::replace_vars($this->lang['form.hide.right.column'], array('module' => "forum")), $this->config->is_right_column_disabled(),
			array('class' => 'custom-checkbox')
		));

		$fieldset->add_field(new FormFieldCheckbox('display_thumbnails', $this->lang['forum.config.display.thumbnails'], $this->config->are_thumbnails_displayed(),
			array('class' => 'custom-checkbox')
		));

		$fieldset->add_field(new FormFieldCheckbox('message_before_topic_title_displayed', $this->lang['forum.config.display.message.before.topic'], $this->config->is_message_before_topic_title_displayed(),
			array(
				'class' => 'custom-checkbox',
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
			)
		));

		$fieldset->add_field(new FormFieldSpacer('1_separator', ''));

		$fieldset->add_field(new FormFieldTextEditor('message_before_topic_title', $this->lang['forum.config.message.before.topic'], $this->config->get_message_before_topic_title(),
			array('maxlength' => 255, 'required' => true)
		));

		$fieldset->add_field(new FormFieldTextEditor('message_when_topic_is_unsolved', $this->lang['forum.config.status.message.unsolved'], $this->config->get_message_when_topic_is_unsolved(),
			array('maxlength' => 255, 'required' => true)
		));

		$fieldset->add_field(new FormFieldTextEditor('message_when_topic_is_solved', $this->lang['forum.config.status.message.solved'], $this->config->get_message_when_topic_is_solved(),
			array('maxlength' => 255, 'required' => true)
		));

		$fieldset->add_field(new FormFieldCheckbox('message_before_topic_title_icon_displayed', $this->lang['forum.config.display.issue.status.icon'], $this->config->is_message_before_topic_title_icon_displayed(),
			array(
				'class' => 'custom-checkbox',
				'description' => '<i class="fa fa-check success"></i> / <i class="fa fa-times error"></i>'
			)
		));

		$fieldset_authorizations = new FormFieldsetHTML('authorizations_fieldset', $this->lang['form.authorizations']);
		$form->add_fieldset($fieldset_authorizations);

		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($this->lang['form.authorizations.read'], Category::READ_AUTHORIZATIONS),
			new VisitorDisabledActionAuthorization($this->lang['form.authorizations.write'], Category::WRITE_AUTHORIZATIONS),
			new MemberDisabledActionAuthorization($this->lang['form.authorizations.moderation'], Category::MODERATION_AUTHORIZATIONS),
			new ActionAuthorization($this->lang['forum.authorizations.read.topics.content'], ForumAuthorizationsService::READ_TOPICS_CONTENT_AUTHORIZATIONS),
			new ActionAuthorization($this->lang['forum.authorizations.flood'], ForumAuthorizationsService::FLOOD_AUTHORIZATIONS),
			new ActionAuthorization($this->lang['forum.authorizations.hide.edition.mark'], ForumAuthorizationsService::HIDE_EDITION_MARK_AUTHORIZATIONS),
			new ActionAuthorization($this->lang['forum.authorizations.unlimited.topics.tracking'], ForumAuthorizationsService::UNLIMITED_TOPICS_TRACKING_AUTHORIZATIONS),
			new MemberDisabledActionAuthorization($this->lang['form.authorizations.categories'], ForumAuthorizationsService::CATEGORIES_MANAGEMENT_AUTHORIZATIONS),
			new VisitorDisabledActionAuthorization($this->lang['forum.authorizations.multiple.posts'], ForumAuthorizationsService::MULTIPLE_POSTS_AUTHORIZATIONS)
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

		if ($this->form->get_value('display_thumbnails'))
			$this->config->display_thumbnails();
		else
			$this->config->hide_thumbnails();

		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());

		ForumConfig::save();
		CategoriesService::get_categories_manager('forum')->regenerate_cache();

		HooksService::execute_hook_action('edit_config', self::$module_id, array('title' => StringVars::replace_vars($this->lang['form.module.title'], array('module_name' => self::get_module_configuration()->get_name())), 'url' => ModulesUrlBuilder::configuration()->rel()));
	}
}
?>
