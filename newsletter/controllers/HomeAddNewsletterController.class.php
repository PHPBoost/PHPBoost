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

class HomeAddNewsletterController extends DefaultModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->build_form();

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$type = $request->get_value('type', '');

			if ($type !== '' && ($type == 'html' || $type == 'bbcode' || $type == 'text'))
			{
				AppContext::get_response()->redirect(NewsletterUrlBuilder::add_newsletter($type));
			}
		}

		$this->view->put('CONTENT', $this->form->display());

		return $this->build_response($this->view);
	}

	private function check_authorizations()
	{
		if (!NewsletterAuthorizationsService::default_authorizations()->create_newsletters())
		{
			NewsletterAuthorizationsService::get_errors()->create_newsletters();
		}
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('choices-fieldset');
		$form->add_fieldset($fieldset);

		if (NewsletterConfig::load()->get_mail_sender())
		{
			$fieldset->add_field(new FormFieldHTML('choices_table', $this->build_choices_table()->render()));

			$this->submit_button = new FormButtonDefaultSubmit($this->lang['form.next']);
			$form->add_button($this->submit_button);
		}
		else
		{
			$fieldset->add_field(new FormFieldHTML('mail_sender_not_configured_msg', MessageHelper::display($this->lang['newsletter.sender.email.not.configured' . (AppContext::get_current_user()->is_admin() ? '.for.admin' : '')], MessageHelper::WARNING)->render()));

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
		$breadcrumb->add($this->lang['newsletter.module.title'], NewsletterUrlBuilder::home()->rel());
		$breadcrumb->add($this->lang['newsletter.add.item'], NewsletterUrlBuilder::add_newsletter()->rel());

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['newsletter.add.item'], $this->lang['newsletter.module.title']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(NewsletterUrlBuilder::add_newsletter());

		return $response;
	}
}
?>
