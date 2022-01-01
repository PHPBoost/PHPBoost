<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 04
 * @since       PHPBoost 3.0 - 2011 08 10
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminCommentsConfigController extends DefaultAdminController
{
	private $configuration;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->build_form();

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->regenerate_cache();
			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.process.success'], MessageHelper::SUCCESS, 4));
			$this->form->get_field_by_id('visitor_email_enabled')->set_hidden(!$this->configuration->are_comments_enabled());
			$this->form->get_field_by_id('comments_number_display')->set_hidden(!$this->configuration->are_comments_enabled());
			$this->form->get_field_by_id('max_links_comment')->set_hidden(!$this->configuration->are_comments_enabled());
			$this->form->get_field_by_id('order_display_comments')->set_hidden(!$this->configuration->are_comments_enabled());
			$this->form->get_field_by_id('forbidden_tags')->set_selected_options($this->configuration->get_forbidden_tags());
			$this->form->get_field_by_id('forbidden_tags')->set_hidden(!$this->configuration->are_comments_enabled());
			$this->form->get_field_by_id('comments_unauthorized_modules')->set_hidden(!$this->configuration->are_comments_enabled());
			$this->form->get_field_by_id('comments_unauthorized_modules')->set_selected_options($this->configuration->get_comments_unauthorized_modules());
		}

		$this->view->put('CONTENT', $this->form->display());

		return new AdminCommentsDisplayResponse($this->view, $this->lang['comment.configuration']);
	}

	private function init()
	{
		$this->configuration = CommentsConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('comments-config', $this->lang['comment.configuration']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldCheckbox('comments_enabled', $this->lang['comment.enable'], $this->configuration->are_comments_enabled(),
			array(
				'class' => 'custom-checkbox',
				'events' => array('click' => '
					if (HTMLForms.getField("comments_enabled").getValue()) {
						HTMLForms.getField("visitor_email_enabled").enable();
						HTMLForms.getField("comments_number_display").enable();
						HTMLForms.getField("max_links_comment").enable();
						HTMLForms.getField("order_display_comments").enable();
						HTMLForms.getField("forbidden_tags").enable();
						HTMLForms.getField("comments_unauthorized_modules").enable();
					} else {
						HTMLForms.getField("visitor_email_enabled").disable();
						HTMLForms.getField("comments_number_display").disable();
						HTMLForms.getField("max_links_comment").disable();
						HTMLForms.getField("order_display_comments").disable();
						HTMLForms.getField("forbidden_tags").disable();
						HTMLForms.getField("comments_unauthorized_modules").disable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldCheckbox('visitor_email_enabled', $this->lang['comment.visitor.email'], $this->configuration->is_visitor_email_enabled(),
			array(
				'class' => 'custom-checkbox',
				'description' => $this->lang['comment.visitor.email.clue']
			)
		));

		$fieldset->add_field(new FormFieldNumberEditor('comments_number_display', $this->lang['comment.display.number'], $this->configuration->get_comments_number_display(),
			array(
				'required' => true,
				'hidden' => !$this->configuration->are_comments_enabled()
			),
			array(new FormFieldConstraintRegex('`^([0-9]+)$`iu', '', $this->lang['warning.regex.number']))
		));

		$fieldset->add_field(new FormFieldNumberEditor('max_links_comment', $this->lang['comment.max.links'], $this->configuration->get_max_links_comment(),
			array(
				'required' => true,
				'hidden' => !$this->configuration->are_comments_enabled()
			),
			array(new FormFieldConstraintRegex('`^([0-9]+)$`iu', '', $this->lang['warning.regex.number']))
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('order_display_comments', $this->lang['comment.display.order'], $this->configuration->get_order_display_comments(),
			array(
				new FormFieldSelectChoiceOption($this->lang['comment.display.order.asc'], CommentsConfig::ASC_ORDER),
				new FormFieldSelectChoiceOption($this->lang['comment.display.order.desc'], CommentsConfig::DESC_ORDER)
			),
			array('hidden' => !$this->configuration->are_comments_enabled())
		));

		$fieldset->add_field(new FormFieldSpacer('format', ''));

		$fieldset->add_field(new FormFieldMultipleSelectChoice('forbidden_tags', $this->lang['form.forbidden.tags'], $this->configuration->get_forbidden_tags(), $this->generate_forbidden_tags_option(),
			array(
				'size' => 12,
				'hidden' => !$this->configuration->are_comments_enabled()
			)
		));

		$fieldset->add_field(new FormFieldMultipleSelectChoice('comments_unauthorized_modules', $this->lang['form.forbidden.module'], $this->configuration->get_comments_unauthorized_modules(), ModulesManager::generate_unauthorized_module_option('comments'),
			array(
				'size' => 12,
				'description' => $this->lang['comment.forbidden.module.clue'],
				'hidden' => !$this->configuration->are_comments_enabled()
			)
		));

		$fieldset = new FormFieldsetHTML('authorization', $this->lang['form.authorizations']);
		$form->add_fieldset($fieldset);

		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($this->lang['form.authorizations.read'], CommentsAuthorizations::READ_AUTHORIZATIONS),
			new ActionAuthorization($this->lang['form.authorizations.write'], CommentsAuthorizations::POST_AUTHORIZATIONS),
			new MemberDisabledActionAuthorization($this->lang['form.authorizations.moderation'], CommentsAuthorizations::MODERATE_AUTHORIZATIONS)
		));
		$auth_settings->build_from_auth_array($this->configuration->get_authorizations());
		$fieldset->add_field(new FormFieldAuthorizationsSetter('authorizations', $auth_settings));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function save()
	{
		if ($this->form->get_value('comments_enabled'))
		{
			$this->configuration->set_comments_enabled(true);
			$this->configuration->set_visitor_email_enabled($this->form->get_value('visitor_email_enabled'));

			$this->configuration->set_comments_number_display($this->form->get_value('comments_number_display'));

			$forbidden_tags = array();
			foreach ($this->form->get_value('forbidden_tags') as $field => $option)
			{
				$forbidden_tags[] = $option->get_raw_value();
			}

	 		$this->configuration->set_forbidden_tags($forbidden_tags);
			$this->configuration->set_max_links_comment($this->form->get_value('max_links_comment'));
			$this->configuration->set_order_display_comments($this->form->get_value('order_display_comments')->get_raw_value());

			$unauthorized_modules = array();
			foreach ($this->form->get_value('comments_unauthorized_modules') as $field => $option)
			{
				$unauthorized_modules[] = $option->get_raw_value();
			}
			$this->configuration->set_comments_unauthorized_modules($unauthorized_modules);
		}
		else
			$this->configuration->set_comments_enabled(false);

		$this->configuration->set_authorizations($this->form->get_value('authorizations')->build_auth_array());
		CommentsConfig::save();

		HooksService::execute_hook_action('edit_config', 'kernel', array('title' => $this->lang['comment.configuration'], 'url' => DispatchManager::get_url('/admin/content/', '/comments/config/')->rel()));
	}

	private function generate_forbidden_tags_option()
	{
		$options = array();
		$available_tags = AppContext::get_content_formatting_service()->get_available_tags();
		foreach ($available_tags as $identifier => $name)
		{
			$options[] = new FormFieldSelectChoiceOption($name, $identifier);
		}
		return $options;
	}

	private function regenerate_cache()
	{
		CommentsCache::invalidate();
	}
}
?>
