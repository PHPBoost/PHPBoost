<?php
/*##################################################
 *                       AdminCommentsConfigController.class.php
 *                            -------------------
 *   begin                : August 10, 2011
 *   copyright            : (C) 2011 Kevin MASSY
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
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHTML('comments-config', $this->lang['comments.config']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldNumberEditor('number_comments_display', $this->lang['comments.config.number-comments-display'], $this->configuration->get_number_comments_display(),
			array('required' => true),
			array(new FormFieldConstraintRegex('`^([0-9]+)$`i', '', LangLoader::get_message('form.doesnt_match_number_regex', 'status-messages-common')))
		));
		
		$fieldset->add_field(new FormFieldNumberEditor('max_links_comment', $this->lang['comments.config.max-links-comment'], $this->configuration->get_max_links_comment(),
			array('required' => true),
			array(new FormFieldConstraintRegex('`^([0-9]+)$`i', '', LangLoader::get_message('form.doesnt_match_number_regex', 'status-messages-common')))
		));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('order_display_comments', $this->lang['comments.config.order-display-comments'], $this->configuration->get_order_display_comments(),
			array(
				new FormFieldSelectChoiceOption($this->lang['comments.config.order-display-comments.asc'], CommentsConfig::ASC_ORDER),
				new FormFieldSelectChoiceOption($this->lang['comments.config.order-display-comments.desc'], CommentsConfig::DESC_ORDER)
			)
		));
		
		$fieldset->add_field(new FormFieldMultipleSelectChoice('forbidden_tags', $this->lang['comments.config.forbidden-tags'], $this->configuration->get_forbidden_tags(),
			$this->generate_forbidden_tags_option(), array('size' => 10)
		));

		$fieldset = new FormFieldsetHTML('authorization', $this->lang['comments.config.authorization']);
		$form->add_fieldset($fieldset);
		
		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($this->lang['comments.config.authorization-read'], CommentsAuthorizations::READ_AUTHORIZATIONS),
			new ActionAuthorization($this->lang['comments.config.authorization-post'], CommentsAuthorizations::POST_AUTHORIZATIONS),
			new ActionAuthorization($this->lang['comments.config.authorization-moderation'], CommentsAuthorizations::MODERATE_AUTHORIZATIONS)
		));
		$auth_settings->build_from_auth_array($this->configuration->get_authorizations());
		$auth_setter = new FormFieldAuthorizationsSetter('authorizations', $auth_settings);
		$fieldset->add_field($auth_setter);
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());
		
		$this->form = $form;
	}

	private function save()
	{
		$this->configuration->set_authorizations($this->form->get_value('authorizations')->build_auth_array());		
		$this->configuration->set_number_comments_display($this->form->get_value('number_comments_display'));
		
		$forbidden_tags = array();
		foreach ($this->form->get_value('forbidden_tags') as $field => $option)
		{
			$forbidden_tags[] = $option->get_raw_value();
		}
			
	 	$this->configuration->set_forbidden_tags($forbidden_tags);
		$this->configuration->set_max_links_comment($this->form->get_value('max_links_comment'));
		$this->configuration->set_order_display_comments($this->form->get_value('order_display_comments')->get_raw_value());
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