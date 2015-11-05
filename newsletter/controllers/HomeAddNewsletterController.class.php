<?php
/*##################################################
 *                         HomeAddNewsletterController.class.php
 *                            -------------------
 *   begin                : February 8, 2011
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

class HomeAddNewsletterController extends ModuleController
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
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();
		
		$this->init();
		
		$this->build_form();
		
		$tpl = new StringTemplate('# INCLUDE FORM #');
		
		$tpl->add_lang($this->lang);
		
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$type = $request->get_value('type', '');
			
			if ($type !== '' && ($type == 'html' || $type == 'bbcode' || $type == 'text'))
			{
				AppContext::get_response()->redirect(NewsletterUrlBuilder::add_newsletter($type));
			}
		}
		
		$tpl->put('FORM', $this->form->display());
		
		return $this->build_response($tpl);
	}
	
	private function check_authorizations()
	{
		if (!NewsletterAuthorizationsService::default_authorizations()->create_newsletters())
		{
			NewsletterAuthorizationsService::get_errors()->create_newsletters();
		}
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'newsletter');
	}
	
	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHTML('choices-fieldset');
		$form->add_fieldset($fieldset);
		
		if (NewsletterConfig::load()->get_mail_sender())
		{
			$fieldset->add_field(new FormFieldHTML('choices_table', $this->build_choices_table()->render()));
			
			$this->submit_button = new FormButtonDefaultSubmit($this->lang['newsletter.types.next']);
			$form->add_button($this->submit_button);
		}
		else
		{
			$fieldset->add_field(new FormFieldHTML('mail_sender_not_configured_msg', MessageHelper::display($this->lang['error.sender-mail-not-configured' . (AppContext::get_current_user()->is_admin() ? '-for-admin' : '')], MessageHelper::WARNING)->render()));
			
			$this->submit_button = new FormButtonDefaultSubmit();
		}
		
		$this->form = $form;
	}
	
	private function build_choices_table()
	{
		$choices_table = new FileTemplate('newsletter/HomeAddNewsletterController.tpl');
		$choices_table->add_lang($this->lang);
		
		return $choices_table;
	}
	
	private function build_response(View $view)
	{
		$body_view = new FileTemplate('newsletter/NewsletterBody.tpl');
		$body_view->add_lang($this->lang);
		$body_view->put('TEMPLATE', $view);
		$response = new SiteDisplayResponse($body_view);
		$breadcrumb = $response->get_graphical_environment()->get_breadcrumb();
		$breadcrumb->add($this->lang['newsletter'], NewsletterUrlBuilder::home()->rel());
		$breadcrumb->add($this->lang['newsletter-add'], NewsletterUrlBuilder::add_newsletter()->rel());
		
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['newsletter-add'], $this->lang['newsletter']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(NewsletterUrlBuilder::add_newsletter());
		
		return $response;
	}
}
?>