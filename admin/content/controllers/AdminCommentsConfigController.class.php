<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 25
 * @since       PHPBoost 3.0 - 2011 08 10
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminCommentsConfigController extends AdminController
{
	private $lang;
	private $admin_common_lang;
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonDefaultSubmit
	 */
	private $submit_button;

	private $configuration;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->build_form();

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->regenerate_cache();
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('process.success', 'status-messages-common'), MessageHelper::SUCCESS, 4));
			$this->form->get_field_by_id('number_comments_display')->set_hidden(!$this->configuration->are_comments_enabled());
			$this->form->get_field_by_id('max_links_comment')->set_hidden(!$this->configuration->are_comments_enabled());
			$this->form->get_field_by_id('order_display_comments')->set_hidden(!$this->configuration->are_comments_enabled());
			$this->form->get_field_by_id('forbidden_tags')->set_selected_options($this->configuration->get_forbidden_tags());
			$this->form->get_field_by_id('forbidden_tags')->set_hidden(!$this->configuration->are_comments_enabled());
			$this->form->get_field_by_id('comments_unauthorized_modules')->set_hidden(!$this->configuration->are_comments_enabled());
			$this->form->get_field_by_id('comments_unauthorized_modules')->set_selected_options($this->configuration->get_comments_unauthorized_modules());
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminCommentsDisplayResponse($tpl, $this->lang['comments.config']);
	}

	private function init()
	{
		$this->lang              = LangLoader::get('admin-contents-common');
		$this->admin_common_lang = LangLoader::get('admin-common');
		$this->configuration     = CommentsConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTMLHeading('comments-config', $this->lang['comments.config']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldCheckbox('comments_enabled', $this->lang['comments.config.enabled'], $this->configuration->are_comments_enabled(),
			array(
				'class' => 'custom-checkbox',
				'events' => array('click' => '
					if (HTMLForms.getField("comments_enabled").getValue()) {
						HTMLForms.getField("number_comments_display").enable();
						HTMLForms.getField("max_links_comment").enable();
						HTMLForms.getField("order_display_comments").enable();
						HTMLForms.getField("forbidden_tags").enable();
						HTMLForms.getField("comments_unauthorized_modules").enable();
					} else {
						HTMLForms.getField("number_comments_display").disable();
						HTMLForms.getField("max_links_comment").disable();
						HTMLForms.getField("order_display_comments").disable();
						HTMLForms.getField("forbidden_tags").disable();
						HTMLForms.getField("comments_unauthorized_modules").disable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldNumberEditor('number_comments_display', $this->lang['comments.config.number-comments-display'], $this->configuration->get_number_comments_display(),
			array(
				'required' => true,
				'hidden' => !$this->configuration->are_comments_enabled()
			),
			array(new FormFieldConstraintRegex('`^([0-9]+)$`iu', '', LangLoader::get_message('form.doesnt_match_number_regex', 'status-messages-common')))
		));

		$fieldset->add_field(new FormFieldNumberEditor('max_links_comment', $this->lang['comments.config.max-links-comment'], $this->configuration->get_max_links_comment(),
			array(
				'required' => true,
				'hidden' => !$this->configuration->are_comments_enabled()
			),
			array(new FormFieldConstraintRegex('`^([0-9]+)$`iu', '', LangLoader::get_message('form.doesnt_match_number_regex', 'status-messages-common')))
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('order_display_comments', $this->lang['comments.config.order-display-comments'], $this->configuration->get_order_display_comments(),
			array(
				new FormFieldSelectChoiceOption($this->lang['comments.config.order-display-comments.asc'], CommentsConfig::ASC_ORDER),
				new FormFieldSelectChoiceOption($this->lang['comments.config.order-display-comments.desc'], CommentsConfig::DESC_ORDER)
			),
			array('hidden' => !$this->configuration->are_comments_enabled())
		));

		$fieldset->add_field(new FormFieldMultipleSelectChoice('forbidden_tags', $this->lang['comments.config.forbidden-tags'], $this->configuration->get_forbidden_tags(), $this->generate_forbidden_tags_option(),
			array(
				'size' => 12,
				'hidden' => !$this->configuration->are_comments_enabled()
			)
		));

		$fieldset->add_field(new FormFieldMultipleSelectChoice('comments_unauthorized_modules', $this->admin_common_lang['config.forbidden-module'], $this->configuration->get_comments_unauthorized_modules(), ModulesManager::generate_unauthorized_module_option('comments'),
			array(
				'size' => 12,
				'description' => $this->admin_common_lang['config.comments.forbidden-module-explain'],
				'hidden' => !$this->configuration->are_comments_enabled()
			)
		));

		$fieldset = new FormFieldsetHTML('authorization', $this->lang['comments.config.authorization']);
		$form->add_fieldset($fieldset);

		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($this->lang['comments.config.authorization-read'], CommentsAuthorizations::READ_AUTHORIZATIONS),
			new ActionAuthorization($this->lang['comments.config.authorization-post'], CommentsAuthorizations::POST_AUTHORIZATIONS),
			new MemberDisabledActionAuthorization($this->lang['comments.config.authorization-moderation'], CommentsAuthorizations::MODERATE_AUTHORIZATIONS)
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

			$this->configuration->set_number_comments_display($this->form->get_value('number_comments_display'));

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
