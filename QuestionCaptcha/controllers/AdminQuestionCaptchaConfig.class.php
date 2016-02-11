<?php
/*##################################################
 *		               AdminQuestionCaptchaConfig.class.php
 *                            -------------------
 *   begin                : May 9, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
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

class AdminQuestionCaptchaConfig extends AdminModuleController
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
	 * @var QuestionCaptchaConfig
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
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}
		
		$tpl->put('FORM', $this->form->display());
		
		return $this->build_response($tpl);
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'QuestionCaptcha');
		$this->config = QuestionCaptchaConfig::load();
	}
	
	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHTML('config', $this->lang['config.title']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new QuestionCaptchaFormFieldQuestions('questions', $this->lang['form.questions'], $this->config->get_questions(), array(
			'description' => $this->lang['form.questions.explain']
		)));
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());
		
		$this->form = $form;
	}
	
	private function save()
	{
		$this->config->set_questions($this->form->get_value('questions'));
		
		QuestionCaptchaConfig::save();
	}
	
	private function build_response(View $tpl)
	{
		$title = LangLoader::get_message('configuration', 'admin');
		
		$response = new AdminMenuDisplayResponse($tpl);
		$response->set_title($title);
		$response->add_link($this->lang['config.title'], DispatchManager::get_url('/QuestionCaptcha', '/admin/config/'));
		$env = $response->get_graphical_environment();
		$env->set_page_title($title);
		
		return $response;
	}
}
?>
