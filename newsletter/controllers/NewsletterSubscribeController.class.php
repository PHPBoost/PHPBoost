<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 3.0 - 2011 02 08
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class NewslettersubscribeController extends DefaultModuleController
{
	private $current_user;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();
		$this->init();
		$this->build_form();

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save($view);
		}

		$this->view->put('CONTENT', $this->form->display());

		return $this->build_response($this->view);
	}

	private function init()
	{
		$this->current_user = AppContext::get_current_user();
	}

	private function check_authorizations()
	{
		if (!NewsletterAuthorizationsService::check_authorizations()->subscribe())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
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
		// $form->set_layout_title($this->lang['newsletter.subscribe.streams']);

		$fieldset = new FormFieldsetHTML('newsletter.subscribe.streams', $this->lang['newsletter.subscribe.streams']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldMailEditor('mail', $this->lang['newsletter.subscriber.email'], $email,
			array('required' => true)
		));

		if ($this->current_user->check_level(User::MEMBER_LEVEL) && $email == $this->current_user->get_email())
		{
			$newsletter_subscribe = NewsletterService::get_member_id_streams($this->current_user->get_id());
		}
		else if ($this->current_user->is_admin())
		{
			if ($user = UserService::get_user_by_email($email))
				$newsletter_subscribe = NewsletterService::get_member_id_streams($user->get_id());
			else
				$newsletter_subscribe = NewsletterService::get_visitor_id_streams($email);
		}
		else
			$newsletter_subscribe = array();

		$fieldset->add_field(new FormFieldMultipleCheckbox('newsletter_choice', $this->lang['newsletter.subscribe.item.clue'], $newsletter_subscribe, $this->get_streams(),
			array('required' => true)
		));

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
		$breadcrumb->add($this->lang['newsletter.module.title'], NewsletterUrlBuilder::home()->rel());
		$breadcrumb->add($this->lang['newsletter.subscribe.streams'], NewsletterUrlBuilder::subscribe()->rel());

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['newsletter.subscribe.streams'], $this->lang['newsletter.module.title']);
		$graphical_environment->get_seo_meta_data()->set_description($this->lang['newsletter.seo.suscribe']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(NewsletterUrlBuilder::subscribe());

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

	private function save(&$view)
	{
		$streams = array();
		foreach ($this->form->get_value('newsletter_choice') as $field => $option)
		{
			$streams[] = $option->get_id();
		}

		if ($this->current_user->check_level(User::MEMBER_LEVEL) && $this->form->get_value('mail') == $this->current_user->get_email())
		{
			if ($streams != NewsletterService::get_member_id_streams($this->current_user->get_id()))
				NewsletterService::update_subscriptions_member_registered($streams, $this->current_user->get_id());
			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.process.success'], MessageHelper::SUCCESS, 4));
		}
		else
		{
			if ($this->current_user->is_guest() && !UserService::get_user_by_email($this->form->get_value('mail')))
			{
				if (!NewsletterDAO::mail_existed($this->form->get_value('mail')) || ($streams != NewsletterService::get_visitor_id_streams($this->form->get_value('mail'))))
				{
					NewsletterService::update_subscriptions_visitor($streams, $this->form->get_value('mail'));
					$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.process.success'], MessageHelper::SUCCESS, 4));
				}
				else if (NewsletterDAO::mail_existed($this->form->get_value('mail')))
					$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['newsletter.subscriber.already.exists'], MessageHelper::ERROR));
			}
			else if ($this->current_user->is_admin())
			{
				if ($user = UserService::get_user_by_email($this->form->get_value('mail')))
				{
					if (!NewsletterDAO::user_id_existed($user->get_id()) || ($streams != NewsletterService::get_member_id_streams($user->get_id())))
					{
						NewsletterService::update_subscriptions_member_registered($streams, $user->get_id());
						$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.process.success'], MessageHelper::SUCCESS, 4));
					}
				}
				else
				{
					if (!NewsletterDAO::mail_existed($this->form->get_value('mail')) || ($streams != NewsletterService::get_visitor_id_streams($this->form->get_value('mail'))))
					{
						NewsletterService::update_subscriptions_visitor($streams, $this->form->get_value('mail'));
						$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.process.success'], MessageHelper::SUCCESS, 4));
					}
					else if (NewsletterDAO::mail_existed($this->form->get_value('mail')))
						$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['newsletter.subscriber.already.exists'], MessageHelper::ERROR));
				}
			}
		}
	}
}
?>
