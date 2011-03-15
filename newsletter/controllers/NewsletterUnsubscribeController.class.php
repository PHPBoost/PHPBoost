<?php
/*##################################################
 *                         NewsletterUnSubscribeController.class.php
 *                            -------------------
 *   begin                : March 13, 2011
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

class NewsletterUnSubscribeController extends ModuleController
{
	private $lang;
	private $view;
	private $form;
	
	public function execute(HTTPRequest $request)
	{
		$this->init();
		$this->build_form();
		
		$tpl = new StringTemplate('# INCLUDE MSG ## INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$tpl->put('MSG', MessageHelper::display($this->lang['admin.success-subscribe'], E_USER_SUCCESS, 4));
		}
		
		$tpl->put('FORM', $this->form->display());
		
		return $this->build_response($tpl);
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('newsletter_common', 'newsletter');
	}
	
	private function build_form()
	{
		if (AppContext::get_user()->check_level(MEMBER_LEVEL))
		{
			$email = AppContext::get_user()->get_attribute('user_mail');
		}
		else
		{
			$email = AppContext::get_request()->get_string('mail_newsletter', '');
		}
		
		$form = new HTMLForm('newsletter');
		
		$fieldset = new FormFieldsetHTML('unsubscribe.newsletter', $this->lang['unsubscribe.newsletter']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldTextEditor('mail', $this->lang['subscribe.mail'], $email, array(
			'class' => 'text', 'required' => true),
			array(new FormFieldConstraintMailAddress())
		));
		
		$fieldset->add_field(new FormFieldCheckbox('all_categories', $this->lang['newsletter.delete_all_cats'], FormFieldCheckbox::UNCHECKED, 
		array('events' => array('click' => '
		if (HTMLForms.getField("all_categories").getValue()) {
			HTMLForms.getField("newsletter_choice").disable();
		} else { 
			HTMLForms.getField("newsletter_choice").enable();
		}')
		)));

		$array_cats = is_array(unserialize(NewsletterDAO::get_id_categories_subscribes_by_user_id(AppContext::get_user()->get_attribute('user_id')))) ? unserialize(NewsletterDAO::get_id_categories_subscribes_by_user_id(AppContext::get_user()->get_attribute('user_id'))) : array();
		$newsletter_subscribe = AppContext::get_user()->check_level(MEMBER_LEVEL) ? $array_cats : array();
		$fieldset->add_field(new FormFieldMultipleSelectChoice('newsletter_choice', $this->lang['unsubscribe.newsletter_choice'], $newsletter_subscribe, $this->get_categories()));
		
		$form->add_button(new FormButtonReset());
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);

		$this->form = $form;
	}
	
	private function build_response(View $view)
	{
		$response = new SiteDisplayResponse($view);
		$breadcrumb = $response->get_graphical_environment()->get_breadcrumb();
		$breadcrumb->add($this->lang['newsletter'], PATH_TO_ROOT . '/newsletter/');
		$breadcrumb->add($this->lang['unsubscribe.newsletter'], DispatchManager::get_url('/newsletter', '/unsubscribe/')->absolute());
		$response->get_graphical_environment()->set_page_title($this->lang['unsubscribe.newsletter']);
		
		return $response;
	}
	
	private function get_categories()
	{
		$categories = array();
		$result = PersistenceContext::get_querier()->select("SELECT id, name, description, visible, auth FROM " . NewsletterSetup::$newsletter_table_streams . "");
		while ($row = $result->fetch())
		{
			$read_auth = is_array($row['auth']) ? AppContext::get_user()->check_auth($row['auth'], NewsletterConfig::CAT_AUTH_SUBSCRIBE) : AppContext::get_user()->check_auth(NewsletterConfig::load()->get_authorizations(), NewsletterConfig::AUTH_SUBSCRIBE);
			if ($read_auth)
			{
				$categories[] = new FormFieldSelectChoiceOption($row['name'], $row['id']);
			}
		}
		return $categories;
	}
	
	private function save()
	{
		$all_categories = $this->form->get_value('all_categories');
		if ($all_categories)
		{
			// TOTO replace for new fonctions
			if (AppContext::get_user()->check_level(MEMBER_LEVEL))
			{
				NewsletterService::unsubscribe_member_all(AppContext::get_user()->get_attribute('user_id'));
			}
			else
			{
				NewsletterService::unsubscribe_visitor_all($this->form->get_value('mail'));
			}
		}
		else
		{
			$streams = $this->form->get_value('newsletter_choice');
			if (AppContext::get_user()->check_level(MEMBER_LEVEL))
			{
				NewsletterService::update_subscribtions_member_registered($streams, AppContext::get_user()->get_attribute('user_id'));
			}
			else
			{
				NewsletterService::update_subscribtions_visitor($streams, $this->form->get_value('mail'));
			}
		}
	}
}

?>