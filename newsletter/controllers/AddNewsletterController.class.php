<?php
/*##################################################
 *                         AddNewsletterController.class.php
 *                            -------------------
 *   begin                : February 8, 2011
 *   copyright            : (C) 2011 Kévin MASSY
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

class AddNewsletterController extends ModuleController
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
		$type = $request->get_value('type', '');
		
		$this->init();
		$this->build_form($type);
		
		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save($type);
			$tpl->put('MSG', MessageHelper::display($this->lang['admin.success-add-newsletter'], E_USER_SUCCESS, 4));
		}
		
		$tpl->put('FORM', $this->form->display());
		
		return $this->build_response($tpl);
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('newsletter_common', 'newsletter');
	}
	
	private function build_form($type)
	{
		$form = new HTMLForm('newsletter');

		$fieldset = new FormFieldsetHTML('add-newsletter', $this->lang['newsletter-add']);
		$form->add_fieldset($fieldset);
		
		$id_cat = 1;
		$fieldset->set_description(
			StringVars::replace_vars($this->lang['newsletter.number-subscribers'], array('number' => NewsletterMailService::number_subscribers($id_cat)))
		);
		
		$fieldset->add_field(new FormFieldTextEditor('title', $this->lang['newsletter.title'], NewsletterConfig::load()->get_newsletter_name(), array(
			'class' => 'text', 'required' => true)
		));
		
		$fieldset->add_field($this->return_editor($type));
		
		$form->add_button(new FormButtonReset());
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);

		$this->form = $form;
	}
	
	private function save($type)
	{
		NewsletterMailService::send_mail(
			$type, 
			NewsletterConfig::load()->get_mail_sender(), 
			$this->form->get_value('title'), 
			$this->form->get_value('contents')
		);
	}
	
	private function build_response(View $view)
	{
		$response = new SiteDisplayResponse($view);
		$response->get_graphical_environment()->set_page_title($this->lang['newsletter-add']);
		return $response;
	}
	
	public function return_editor($type)
	{
		if ($type == 'html')
		{
			return new FormFieldMultiLineTextEditor('contents', $this->lang['newsletter.contents'], '', array(
				'rows' => 10, 'cols' => 47, 'required' => true)
			);
		}
		else if ($type == 'bbcode')
		{
			return new FormFieldRichTextEditor('contents', $this->lang['newsletter.contents'], '', array(
				'rows' => 10, 'cols' => 47, 'required' => true)
			);
		}
		else
		{
			return new FormFieldMultiLineTextEditor('contents', $this->lang['newsletter.contents'], '', array(
				'rows' => 10, 'cols' => 47, 'required' => true)
			);
		}
	}
	
}

?>