<?php
/*##################################################
 *		               AdminConfigPHPBoostCaptcha.class.php
 *                            -------------------
 *   begin                : February 28, 2013
 *   copyright            : (C) 2013 Kevin MASSY
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

class AdminConfigPHPBoostCaptcha extends AdminModuleController
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
	 * @var PHPBoostCaptchaConfig
	 */
	private $config;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$this->build_form();
		
		$tpl = new StringTemplate('# INCLUDE FORM #');
		$tpl->add_lang($this->lang);
		
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
		}
		
		$tpl->put('FORM', $this->form->display());
		
		return $this->build_response($tpl);
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'PHPBoostCaptcha');
		$this->config = PHPBoostCaptchaConfig::load();
	}
	
	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHTML('config', $this->lang['admin.config']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('difficulty', $this->lang['difficulty'], $this->config->get_difficulty(),
			array(
				new FormFieldSelectChoiceOption('0', PHPBoostCaptcha::CAPTCHA_VERY_EASY),
				new FormFieldSelectChoiceOption('1', PHPBoostCaptcha::CAPTCHA_EASY),
				new FormFieldSelectChoiceOption('2', PHPBoostCaptcha::CAPTCHA_NORMAL),
				new FormFieldSelectChoiceOption('3', PHPBoostCaptcha::CAPTCHA_HARD),
				new FormFieldSelectChoiceOption('4', PHPBoostCaptcha::CAPTCHA_VERY_HARD),
		)));
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());
		
		$this->form = $form;
	}
	
	private function save()
	{
		$this->config->set_difficulty($this->form->get_value('difficulty'));
		PHPBoostCaptchaConfig::save();
	}
	
	private function build_response(View $tpl)
	{
		$response = new AdminMenuDisplayResponse($tpl);
		$response->set_title($this->lang['admin.config']);		        
		$response->add_link($this->lang['admin.config'], DispatchManager::get_url('/PHPBoostCaptcha', '/admin/config/'), 'PHPBoostCaptcha.png');
		$env = $response->get_graphical_environment();
		$env->set_page_title($this->lang['admin.config']);
		return $response;
	}
}
?>