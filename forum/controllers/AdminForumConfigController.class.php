<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 27
 * @since       PHPBoost 4.1 - 2015 02 15
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
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
	private $config_lang;

	/**
	 * @var ForumConfig
	 */
	private $config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->build_form();

		$view = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$view->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->form->get_field_by_id('message_before_topic_title')->set_hidden(!$this->config->is_message_before_topic_title_displayed());
			$this->form->get_field_by_id('message_when_topic_is_unsolved')->set_hidden(!$this->config->is_message_before_topic_title_displayed());
			$this->form->get_field_by_id('message_when_topic_is_solved')->set_hidden(!$this->config->is_message_before_topic_title_displayed());
			$this->form->get_field_by_id('message_before_topic_title_icon_displayed')->set_hidden(!$this->config->is_message_before_topic_title_displayed());
			$view->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}

		$view->put('FORM', $this->form->display());

		return new AdminForumDisplayResponse($view, $this->lang['forum.config.title']);
	}

	private function init()
	{
		$this->config = ForumConfig::load();
		$this->lang = LangLoader::get('common', 'forum');
		$this->config_lang = LangLoader::get('admin-common');
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTMLHeading('config', $this->config_lang['configuration'] . ': ' . $this->lang['forum.module.title']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('forum_name', $this->lang['config.forum.name'], $this->config->get_forum_name(),
			array('maxlength' => 255, 'required' => true)
		));

		$fieldset->add_field(new FormFieldNumberEditor('number_topics_per_page', $this->lang['config.number.topics.per.page'], $this->config->get_number_topics_per_page(),
			array('min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

		$fieldset->add_field(new FormFieldNumberEditor('number_messages_per_page', $this->lang['config.number.messages.per.page'], $this->config->get_number_messages_per_page(),
			array('min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

		$fieldset->add_field(new FormFieldNumberEditor('read_messages_storage_duration', $this->lang['config.read.messages.storage.duration'], $this->config->get_read_messages_storage_duration(),
			array('min' => 1, 'max' => 365, 'required' => true, 'description' => $this->lang['config.read.messages.storage.duration.explain']),
			array(new FormFieldConstraintIntegerRange(1, 365))
		));

		$fieldset->add_field(new FormFieldNumberEditor('max_topic_number_in_favorite', $this->lang['config.max.topic.number.in.favorite'], $this->config->get_max_topic_number_in_favorite(),
			array('min' => 1, 'max' => 500, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 500))
		));

		$fieldset->add_field(new FormFieldCheckbox('edit_mark_enabled', $this->lang['config.edit.mark.enabled'], $this->config->is_edit_mark_enabled(),
			array('class' => 'custom-checkbox')
		));

		$fieldset->add_field(new FormFieldCheckbox('multiple_posts_allowed', $this->lang['config.multiple.posts.allowed'], $this->config->are_multiple_posts_allowed(),
			array(
				'class' => 'custom-checkbox',
				'description' => $this->lang['config.multiple.posts.allowed.explain'])
		));

		$fieldset->add_field(new FormFieldCheckbox('connexion_form_displayed', $this->lang['config.connexion.form.displayed'], $this->config->is_connexion_form_displayed(),
			array('class' => 'custom-checkbox')
		));

		$fieldset->add_field(new FormFieldCheckbox('left_column_disabled', StringVars::replace_vars(LangLoader::get_message('config.hide_left_column', 'admin-common'), array('module' => "forum")), $this->config->is_left_column_disabled(),
			array('class' => 'custom-checkbox')
		));

		$fieldset->add_field(new FormFieldCheckbox('right_column_disabled', StringVars::replace_vars(LangLoader::get_message('config.hide_right_column', 'admin-common'), array('module' => "forum")), $this->config->is_right_column_disabled(),
			array('class' => 'custom-checkbox')
		));

		$fieldset->add_field(new FormFieldCheckbox('message_before_topic_title_displayed', $this->lang['config.message.before.topic.title.displayed'], $this->config->is_message_before_topic_title_displayed(),
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

		$fieldset->add_field(new FormFieldTextEditor('message_before_topic_title', $this->lang['config.message.before.topic.title'], $this->config->get_message_before_topic_title(),
			array('maxlength' => 255, 'required' => true)
		));

		$fieldset->add_field(new FormFieldTextEditor('message_when_topic_is_unsolved', $this->lang['config.message.when.topic.is.unsolved'], $this->config->get_message_when_topic_is_unsolved(),
			array('maxlength' => 255, 'required' => true)
		));

		$fieldset->add_field(new FormFieldTextEditor('message_when_topic_is_solved', $this->lang['config.message.when.topic.is.solved'], $this->config->get_message_when_topic_is_solved(),
			array('maxlength' => 255, 'required' => true)
		));

		$fieldset->add_field(new FormFieldCheckbox('message_before_topic_title_icon_displayed', $this->lang['config.message.before.topic.title.icon.displayed'], $this->config->is_message_before_topic_title_icon_displayed(),
			array(
				'class' => 'custom-checkbox',
				'description' => '<i class="fa fa-check success"></i> / <i class="fa fa-times error"></i>'
			)
		));

		$common_lang = LangLoader::get('common');
		$fieldset_authorizations = new FormFieldsetHTML('authorizations_fieldset', $common_lang['authorizations']);
		$form->add_fieldset($fieldset_authorizations);

		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($common_lang['authorizations.read'], Category::READ_AUTHORIZATIONS),
			new VisitorDisabledActionAuthorization($common_lang['authorizations.write'], Category::WRITE_AUTHORIZATIONS),
			new MemberDisabledActionAuthorization($common_lang['authorizations.moderation'], Category::MODERATION_AUTHORIZATIONS),
			new ActionAuthorization($this->lang['authorizations.read.topics.content'], ForumAuthorizationsService::READ_TOPICS_CONTENT_AUTHORIZATIONS),
			new ActionAuthorization($this->lang['authorizations.flood'], ForumAuthorizationsService::FLOOD_AUTHORIZATIONS),
			new ActionAuthorization($this->lang['authorizations.hide.edition.mark'], ForumAuthorizationsService::HIDE_EDITION_MARK_AUTHORIZATIONS),
			new ActionAuthorization($this->lang['authorizations.unlimited.topics.tracking'], ForumAuthorizationsService::UNLIMITED_TOPICS_TRACKING_AUTHORIZATIONS),
			new MemberDisabledActionAuthorization($common_lang['authorizations.categories_management'], ForumAuthorizationsService::CATEGORIES_MANAGEMENT_AUTHORIZATIONS),
			new VisitorDisabledActionAuthorization($this->lang['authorizations.multiple.posts'], ForumAuthorizationsService::MULTIPLE_POSTS_AUTHORIZATIONS)
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
		CategoriesService::get_categories_manager()->regenerate_cache();
	}
}
?>
