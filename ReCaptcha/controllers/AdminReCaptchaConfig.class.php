<?php
/*##################################################
 *		               AdminReCaptchaConfig.class.php
 *                            -------------------
 *   begin                : September 18, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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

class AdminReCaptchaConfig extends AdminModuleController
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
	 * @var ReCaptchaConfig
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
			$this->form->get_field_by_id('site_key')->set_hidden(!$this->config->is_recaptchav2_enabled());
			$this->form->get_field_by_id('secret_key')->set_hidden(!$this->config->is_recaptchav2_enabled());
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}
		
		$tpl->put('FORM', $this->form->display());
		
		return $this->build_response($tpl);
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'ReCaptcha');
		$this->config = ReCaptchaConfig::load();
	}
	
	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHTML('config', $this->lang['config.title']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldFree('explain', '', $this->lang['config.recaptcha-explain']));
		
		$fieldset->add_field(new FormFieldCheckbox('recaptchav2_enabled', $this->lang['config.recaptchav2_enabled'], $this->config->is_recaptchav2_enabled(),
			array('events' => array('click' => '
				if (HTMLForms.getField("recaptchav2_enabled").getValue()) {
					HTMLForms.getField("site_key").enable();
					HTMLForms.getField("secret_key").enable();
				} else {
					HTMLForms.getField("site_key").disable();
					HTMLForms.getField("secret_key").disable();
				}')
			)
		));
		
		$fieldset->add_field(new FormFieldTextEditor('site_key', $this->lang['config.site_key'], $this->config->get_site_key(),
			array('required' => true, 'hidden' => !$this->config->is_recaptchav2_enabled()),
			array(new FormFieldConstraintLengthMin(30))
		));
		
		$fieldset->add_field(new FormFieldPasswordEditor('secret_key', $this->lang['config.secret_key'], $this->config->get_secret_key(),
			array('required' => true, 'hidden' => !$this->config->is_recaptchav2_enabled()),
			array(new FormFieldConstraintLengthMin(30))
		));
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());
		
		$this->form = $form;
	}
	
	private function save()
	{
		if ($this->form->get_value('recaptchav2_enabled'))
		{
			$this->config->enable_recaptchav2();
			$this->config->set_site_key($this->form->get_value('site_key'));
			$this->config->set_secret_key($this->form->get_value('secret_key'));
		}
		else
			$this->config->disable_recaptchav2();
		
		ReCaptchaConfig::save();
	}
	
	private function build_response(View $tpl)
	{
		$title = LangLoader::get_message('configuration', 'admin');
		
		$response = new AdminMenuDisplayResponse($tpl);
		$response->set_title($title);
		$response->add_link($this->lang['config.title'], DispatchManager::get_url('/ReCaptcha', '/admin/config/'));
		$env = $response->get_graphical_environment();
		$env->set_page_title($title);
		
		return $response;
	}
}
?>
