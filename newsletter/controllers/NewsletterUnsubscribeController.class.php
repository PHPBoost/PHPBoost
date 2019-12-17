<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 10 28
 * @since       PHPBoost 3.0 - 2011 03 13
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class NewsletterUnsubscribeController extends ModuleController
{
	private $lang;
	private $view;
	private $form;
	private $submit_button;
	private $current_user;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->build_form();

		$tpl = new StringTemplate('# INCLUDE MSG ## INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save($tpl);
		}

		$tpl->put('FORM', $this->form->display());

		return $this->build_response($tpl);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'newsletter');
		$this->current_user = AppContext::get_current_user();
	}

	private function build_form()
	{
		$mail_request = AppContext::get_request()->get_string('mail_newsletter', '');
		if ($this->current_user->check_level(User::MEMBER_LEVEL) && empty($mail_request))
		{
			$email = $this->current_user->get_email();
		}
		else
		{
			$email = $mail_request;
		}

		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTMLHeading('unsubscribe.newsletter', $this->lang['unsubscribe.newsletter']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldMailEditor('mail', $this->lang['subscribe.mail'], $email,
			array('required' => true)
		));

		$fieldset->add_field(new FormFieldCheckbox('delete_all_streams', $this->lang['newsletter.delete_all_streams'], FormFieldCheckbox::UNCHECKED,
		array('events' => array('click' => '
		if (HTMLForms.getField("delete_all_streams").getValue()) {
			HTMLForms.getField("choice").disable();
		} else {
			HTMLForms.getField("choice").enable();
		}')
		)));

		if ($this->current_user->check_level(User::MEMBER_LEVEL) && $email == $this->current_user->get_email())
		{
			$newsletter_subscribe = NewsletterService::get_member_id_streams($this->current_user->get_id());
		}
		else if ($this->current_user->is_admin())
		{
			if ($user = UserService::get_user_by_email($this->form->get_value('mail')))
				$newsletter_subscribe = NewsletterService::get_member_id_streams($this->current_user->get_id());
			else
				$newsletter_subscribe = NewsletterService::get_visitor_id_streams($email);
		}
		else
			$newsletter_subscribe = array();
		
		$fieldset->add_field(new FormFieldMultipleCheckbox('choice', $this->lang['unsubscribe.newsletter_choice'], $newsletter_subscribe, $this->get_streams()));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function build_response(View $view)
	{
		$body_view = new FileTemplate('newsletter/NewsletterBody.tpl');
		$body_view->add_lang($this->lang);
		$body_view->put('TEMPLATE', $view);
		$response = new SiteDisplayResponse($body_view);
		$breadcrumb = $response->get_graphical_environment()->get_breadcrumb();
		$breadcrumb->add($this->lang['newsletter'], NewsletterUrlBuilder::home()->rel());
		$breadcrumb->add($this->lang['unsubscribe.newsletter'], NewsletterUrlBuilder::unsubscribe()->rel());

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['unsubscribe.newsletter'], $this->lang['newsletter']);
		$graphical_environment->get_seo_meta_data()->set_description($this->lang['newsletter.seo.unsuscribe']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(NewsletterUrlBuilder::unsubscribe());

		return $response;
	}

	private function get_streams()
	{
		$streams = array();
		$newsletter_streams = NewsletterStreamsCache::load()->get_streams();
		foreach ($newsletter_streams as $id => $stream)
		{
			if ($id != Category::ROOT_CATEGORY && NewsletterAuthorizationsService::id_stream($id)->subscribe())
				$streams[] = new FormFieldMultipleCheckboxOption($id, $stream->get_name());
		}
		return $streams;
	}

	private function save(&$tpl)
	{
		$delete_all_streams = $this->form->get_value('delete_all_streams');
		if ($delete_all_streams)
		{
			if ($this->current_user->check_level(User::MEMBER_LEVEL) && $this->form->get_value('mail') == $this->current_user->get_email())
			{
				NewsletterService::unsubscriber_all_streams_member($this->current_user->get_id());
				$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('process.success', 'status-messages-common'), MessageHelper::SUCCESS, 4));
			}
			else
			{
				if ($this->current_user->is_guest() && !UserService::get_user_by_email($this->form->get_value('mail')))
				{
					if (NewsletterDAO::mail_existed($this->form->get_value('mail')))
					{
						NewsletterService::unsubscriber_all_streams_visitor($this->form->get_value('mail'));
						$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('process.success', 'status-messages-common'), MessageHelper::SUCCESS, 4));
					}
				}
				else if ($this->current_user->is_admin())
				{
					if ($user = UserService::get_user_by_email($this->form->get_value('mail')))
					{
						if (NewsletterDAO::user_id_existed($user->get_id()))
						{
							NewsletterService::unsubscriber_all_streams_member($user->get_id());
							$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('process.success', 'status-messages-common'), MessageHelper::SUCCESS, 4));
						}
						else
							$tpl->put('MSG', MessageHelper::display($this->lang['error-subscriber-not-existed'], MessageHelper::ERROR));
					}
					else
					{
						if (NewsletterDAO::mail_existed($this->form->get_value('mail')))
						{
							NewsletterService::unsubscriber_all_streams_visitor($this->form->get_value('mail'));
							$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('process.success', 'status-messages-common'), MessageHelper::SUCCESS, 4));
						}
						else
							$tpl->put('MSG', MessageHelper::display($this->lang['error-subscriber-not-existed'], MessageHelper::ERROR));
					}
				}
				else
					$tpl->put('MSG', MessageHelper::display($this->lang['error-subscriber-not-existed'], MessageHelper::ERROR));
			}
		}
		else
		{
			$streams = array();
			foreach ($this->form->get_value('choice') as $field => $option)
			{
				$streams[] = $option->get_id();
			}

			if ($this->current_user->check_level(User::MEMBER_LEVEL) && $this->form->get_value('mail') == $this->current_user->get_email() && ($streams != NewsletterService::get_member_id_streams($this->current_user->get_id())) && !empty($streams))
			{
				NewsletterService::update_subscriptions_member_registered($streams, $this->current_user->get_id());
				$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('process.success', 'status-messages-common'), MessageHelper::SUCCESS, 4));
			}
			else
			{
				if ($this->current_user->is_guest() && !UserService::get_user_by_email($this->form->get_value('mail')))
				{
					if (NewsletterDAO::mail_existed($this->form->get_value('mail')) && ($streams != NewsletterService::get_visitor_id_streams($this->form->get_value('mail'))))
					{
						NewsletterService::update_subscriptions_visitor($streams, $this->form->get_value('mail'));
						$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('process.success', 'status-messages-common'), MessageHelper::SUCCESS, 4));
					}
				}
				else if ($this->current_user->is_admin())
				{
					if ($user = UserService::get_user_by_email($this->form->get_value('mail')))
					{
						if (NewsletterDAO::user_id_existed($user->get_id()) && ($streams != NewsletterService::get_member_id_streams($user->get_id())))
						{
							NewsletterService::update_subscriptions_member_registered($streams, $user->get_id());
							$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('process.success', 'status-messages-common'), MessageHelper::SUCCESS, 4));
						}
					}
					else
					{
						if (NewsletterDAO::mail_existed($this->form->get_value('mail')) && ($streams != NewsletterService::get_visitor_id_streams($this->form->get_value('mail'))))
						{
							NewsletterService::update_subscriptions_visitor($streams, $this->form->get_value('mail'));
							$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('process.success', 'status-messages-common'), MessageHelper::SUCCESS, 4));
						}
					}
				}
			}
		}

		NewsletterStreamsCache::invalidate();
	}
}
?>
