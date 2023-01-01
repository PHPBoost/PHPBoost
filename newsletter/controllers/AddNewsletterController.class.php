<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 3.0 - 2011 02 08
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AddNewsletterController extends DefaultModuleController
{
	private $send_test_button;

	public function execute(HTTPRequestCustom $request)
	{
		if (!NewsletterAuthorizationsService::default_authorizations()->create_newsletters())
		{
			NewsletterAuthorizationsService::get_errors()->create_newsletters();
		}

		$type = $request->get_value('type', '');

		$this->build_form($type);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->send_mail($type);
			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['newsletter.item.success.add'], MessageHelper::SUCCESS, 5));
		}
		else if ($this->send_test_button->has_been_submited() && $this->form->validate())
		{
			$this->send_test($type);
			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['newsletter.success.send.test'], MessageHelper::SUCCESS, 5));
		}

		$this->view->put('CONTENT', $this->form->display());

		return $this->build_response($this->view, $type);
	}

	private function build_form($type)
	{
		$form = new HTMLForm(__CLASS__);
		$form->set_layout_title($this->lang['newsletter.add.item']);

		$fieldset = new FormFieldsetHTML('add-newsletter', LangLoader::get_message('form.parameters', 'form-lang'));
		$form->add_fieldset($fieldset);

		if (NewsletterConfig::load()->get_mail_sender())
		{
			$streams = $this->get_streams();
			$fieldset->add_field(new FormFieldMultipleCheckbox('newsletter_choice', $this->lang['newsletter.choose.streams'], (count($streams) == 1 ? array('1') : array()), $streams,
				array('required' => true)
			));

			$fieldset->add_field(new FormFieldTextEditor('title', $this->lang['newsletter.title'], $this->config->get_newsletter_name(), array(
				'required' => true)
			));

			$fieldset->add_field($this->return_editor($type));

			$this->submit_button = new FormButtonDefaultSubmit();
			$this->send_test_button = new FormButtonSubmit($this->lang['newsletter.send.test'], 'send_test');
			$form->add_button($this->submit_button);
			$form->add_button($this->send_test_button);
			$form->add_button(new FormButtonReset());
		}
		else
		{
			$fieldset->add_field(new FormFieldHTML('mail_sender_not_configured_msg', MessageHelper::display($this->lang['newsletter.sender.email.not.configured' . (AppContext::get_current_user()->is_admin() ? '.for.admin' : '')], MessageHelper::WARNING)->render()));

			$this->submit_button = new FormButtonDefaultSubmit();
		}

		$this->form = $form;
	}

	private function send_mail($type)
	{
		$streams = array();
		foreach ($this->form->get_value('newsletter_choice') as $field => $option)
		{
			$streams[] = $option->get_id();
		}

		NewsletterService::add_newsletter(
			$streams,
			$this->form->get_value('title'),
			$this->form->get_value('content'),
			$type
		);
	}

	private function send_test($type)
	{
		$subscribers[] = array('user_id' => AppContext::get_current_user()->get_id(), 'display_name' => AppContext::get_current_user()->get_display_name());
		NewsletterMailFactory::send_mail(
			$subscribers,
			$type,
			$this->config->get_mail_sender(),
			$this->form->get_value('title'),
			$this->form->get_value('content')
		);
	}

	private function build_response(View $view, $type)
	{
		$body_view = new FileTemplate('newsletter/NewsletterBody.tpl');
		$body_view->add_lang($this->lang);
		$body_view->put('TEMPLATE', $view);
		$response = new SiteDisplayResponse($body_view);
		$breadcrumb = $response->get_graphical_environment()->get_breadcrumb();
		$breadcrumb->add($this->lang['newsletter.module.title'], NewsletterUrlBuilder::home()->rel());
		$breadcrumb->add($this->lang['newsletter.add.item'], NewsletterUrlBuilder::add_newsletter()->rel());
		$breadcrumb->add($this->lang['newsletter.types.' . $type], NewsletterUrlBuilder::add_newsletter($type)->rel());
		$response->get_graphical_environment()->set_page_title($this->lang['newsletter.add.item'], $this->lang['newsletter.module.title']);
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

	public function return_editor($type)
	{
		if ($type == 'bbcode')
		{
			return new FormFieldRichTextEditor('content', $this->lang['newsletter.content'], $this->config->get_default_content(), array(
				'rows' => 10, 'cols' => 47, 'description' => $this->lang['newsletter.content.clue'], 'required' => true)
			);
		}
		else
		{
			return new FormFieldMultiLineTextEditor('content', $this->lang['newsletter.content'], ($type == 'html' ? $this->config->get_default_content() : ''), array(
				'rows' => 10, 'cols' => 47, 'description' => $this->lang['newsletter.content.clue'], 'required' => true)
			);
		}
	}
}
?>
