<?php
/*##################################################
 *                       AdminCommentsConfigController.class.php
 *                            -------------------
 *   begin                : August 10, 2011
 *   copyright            : (C) 2011 K�vin MASSY
 *   email                : soldier.weasel@gmail.com
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

class AdminCommentsConfigController extends AdminController
{
	private $lang;
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonDefaultSubmit
	 */
	private $submit_button;
	
	private $configuration;

	public function execute(HTTPRequest $request)
	{
		$this->init();
		$this->build_form();

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$tpl->put('MSG', MessageHelper::display($this->lang['comments.config.success-saving'], E_USER_SUCCESS, 4));
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminCommentsDisplayResponse($tpl, $this->lang['comments.config']);
	}

	private function init()
	{
		$this->lang = LangLoader::get('admin-contents-common');
		$this->configuration = CommentsConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm('comments-config');
		
		$fieldset = new FormFieldsetHTML('comments-config', $this->lang['comments.config']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldCheckbox('display_captcha', $this->lang['comments.config.display-captcha'], $this->configuration->get_display_captcha()));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('captcha_difficulty', $this->lang['comments.config.captcha-difficulty'], $this->configuration->get_captcha_difficulty(),
			array(
				new FormFieldSelectChoiceOption('0', '0'),
				new FormFieldSelectChoiceOption('1', '1'),
				new FormFieldSelectChoiceOption('2', '2'),
				new FormFieldSelectChoiceOption('3', '3'),
				new FormFieldSelectChoiceOption('4', '4'),
			)
		));
		
		$fieldset->add_field(new FormFieldTextEditor('number_comments_display', $this->lang['comments.config.number-comments-display'], $this->configuration->get_number_comments_display(), array(
			'class' => 'text', 'maxlength' => 4, 'size' => 4, 'required' => true),
			array(new FormFieldConstraintRegex('`^([0-9]+)$`i', '`^([0-9]+)$`i', $this->lang['number-required']))
		));
		
		$fieldset->add_field(new FormFieldTextEditor('max_links_comment', $this->lang['comments.config.max-links-comment'], $this->configuration->get_max_links_comment(), array(
			'class' => 'text', 'maxlength' => 4, 'size' => 4, 'required' => true),
			array(new FormFieldConstraintRegex('`^([0-9]+)$`i', '`^([0-9]+)$`i', $this->lang['number-required']))
		));

		$fieldset->add_field(new FormFieldMultipleSelectChoice('forbidden_tags', $this->lang['comments.config.forbidden-tags'], $this->configuration->get_forbidden_tags(),
			$this->generate_forbidden_tags_option()
		));

		$fieldset = new FormFieldsetHTML('authorization', $this->lang['comments.config.authorization']);
		$form->add_fieldset($fieldset);
		
		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($this->lang['comments.config.authorization-read'], CommentsAuthorizations::READ_AUTHORIZATIONS),
			new ActionAuthorization($this->lang['comments.config.authorization-post'], CommentsAuthorizations::POST_AUTHORIZATIONS),
			new ActionAuthorization($this->lang['comments.config.authorization-moderation'], CommentsAuthorizations::MODERATION_AUTHORIZATIONS)
		));
		$auth_settings->build_from_auth_array($this->configuration->get_authorizations());
		$auth_setter = new FormFieldAuthorizationsSetter('authorizations', $auth_settings);
		$fieldset->add_field($auth_setter);
		
		$form->add_button(new FormButtonReset());
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);

		$this->form = $form;
	}

	private function save()
	{
		$this->configuration->set_authorizations($this->form->get_value('authorizations')->build_auth_array());
		$this->configuration->set_display_captcha($this->form->get_value('display_captcha'));
		$this->configuration->set_captcha_difficulty($this->form->get_value('captcha_difficulty')->get_raw_value());
		$this->configuration->set_number_comments_display($this->form->get_value('number_comments_display'));

	 	//$this->configuration->set_forbidden_tags($this->form->get_value('forbidden_tags'));
	 	throw new Exception('Debug a retrieve fonction in field MultipleSelect');
	 	
		$this->configuration->set_max_links_comment($this->form->get_value('max_links_comment'));
		CommentsConfig::save();
	}
	
	private function generate_forbidden_tags_option()
	{
		$options = array();
		foreach (AppContext::get_content_formatting_service()->get_available_tags() as $identifier => $name)
		{	
			$options[] = new FormFieldSelectChoiceOption($name, $identifier);
		}
		return $options;
	}
}
?>