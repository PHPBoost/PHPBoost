<?php
/*##################################################
 *                       AdminContactController.class.php
 *                            -------------------
 *   begin                : May 2, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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

class AdminContactController extends AdminController
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

	public function execute(HTTPRequest $request)
	{
		$this->init();
		$this->build_form();

		$tpl = new FileTemplate('contact/AdminContactController.tpl');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$tpl->assign_vars(array(
				'C_SUBMITED' => true,
			));
		}

		$tpl->add_subtemplate('FORM', $this->form->display());

		return $this->build_response($tpl);
	}

	private function init()
	{
		$this->lang = LangLoader::get('contact_common', 'contact');
	}

	private function build_form()
	{
		$form = new HTMLForm('contact_admin');
		$config = ContactConfig::load();

		$fieldset = new FormFieldsetHTML('configuration', $this->lang['contact_config']);
		$form->add_fieldset($fieldset);
		$fieldset->add_field(new FormFieldCheckbox('enable_captcha', $this->lang['enable_captcha'], $config->is_captcha_enabled(),
			array('events' => array('click' => 'if (HTMLForms.getField("enable_captcha").getValue()) { HTMLForms.getField("captcha_difficulty_level").enable(); } else { HTMLForms.getField("captcha_difficulty_level").disable(); }'))));
		$fieldset->add_field(new FormFieldTextEditor('captcha_difficulty_level', $this->lang['captcha_difficulty'], $config->get_captcha_difficulty_level(),
			array('disabled' => !$config->is_captcha_enabled(), 'required' => true),
			array(new FormFieldConstraintIntegerRange(0, 4))));

		$form->add_button(new FormButtonReset());
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);

		$this->form = $form;
	}

	private function save()
	{
		$config = ContactConfig::load();
		if ($this->form->get_value('enable_captcha'))
		{
			$config->enable_captcha();
			$config->set_captcha_difficulty_level($this->form->get_value('captcha_difficulty_level'));
		}
		else
		{
			$config->disable_captcha();
		}
		ContactConfig::save();
	}

	private function build_response(View $view)
	{
		$response = new AdminMenuDisplayResponse($view);
		$response->set_title($this->lang['title_contact']);
		$response->add_link($this->lang['contact_config'], '/contact/' . url('index.php?url=/admin', 'admin/'), 'contact/contact.png');
		return $response;
	}
}

?>