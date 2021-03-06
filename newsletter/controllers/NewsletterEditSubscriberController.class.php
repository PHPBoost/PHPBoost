<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 24
 * @since       PHPBoost 3.0 - 2011 03 16
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class NewsletterEditSubscriberController extends ModuleController
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
		if (!NewsletterAuthorizationsService::default_authorizations()->moderation_subscribers())
		{
			NewsletterAuthorizationsService::get_errors()->moderation_subscribers();
		}

		$id = $request->get_getint('id', 0);
		$this->init();

		$verificate_is_edit = PersistenceContext::get_querier()->count(NewsletterSetup::$newsletter_table_subscribers, "WHERE id = '". $id ."' AND user_id = -1") > 0;
		if (!$this->subscriber_exist($id) || !$verificate_is_edit)
		{
			$controller = new UserErrorController(LangLoader::get_message('warning.error', 'warning-lang'), LangLoader::get_message('newsletter.subscriber.not.exists', 'common', 'newsletter'));
			DispatchManager::redirect($controller);
		}

		$this->build_form($id);

		$view = new StringTemplate('# INCLUDE MESSAGE_HELPER # # INCLUDE FORM #');
		$view->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save($id);
			$view->put('MESSAGE_HELPER', MessageHelper::display(LangLoader::get_message('warning.process.success', 'warning-lang'), MessageHelper::SUCCESS, 4));
		}

		$view->put('FORM', $this->form->display());

		return $this->build_response($view, $id);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'newsletter');
	}

	private function build_form($id)
	{
		$form = new HTMLForm(__CLASS__);
		$form->set_layout_title($this->lang['newsletter.subscriber.edit']);

		$columns = array('*');
		$condition = "WHERE id = '". $id ."' AND user_id = -1";
		$row = PersistenceContext::get_querier()->select_single_row(NewsletterSetup::$newsletter_table_subscribers, $columns, $condition);

		$fieldset = new FormFieldsetHTML('edit-subscriber', LangLoader::get_message('form.parameters', 'form-lang'));
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldMailEditor('mail', $this->lang['newsletter.subscriber.email'], $row['mail'],
			array('required' => true)
		));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function save($id)
	{
		$condition = "WHERE id = :id";
		$columns = array('mail' => $this->form->get_value('mail'));
		$parameters = array('id' => $id);
		PersistenceContext::get_querier()->update(NewsletterSetup::$newsletter_table_subscribers, $columns, $condition, $parameters);

		NewsletterStreamsCache::invalidate();
	}

	private function build_response(View $view, $id)
	{
		$body_view = new FileTemplate('newsletter/NewsletterBody.tpl');
		$body_view->add_lang($this->lang);
		$body_view->put('TEMPLATE', $view);
		$response = new SiteDisplayResponse($body_view);
		$breadcrumb = $response->get_graphical_environment()->get_breadcrumb();
		$breadcrumb->add($this->lang['newsletter.module.title'], NewsletterUrlBuilder::home()->rel());
		$breadcrumb->add($this->lang['newsletter.subscriber.edit'], '');

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['newsletter.subscriber.edit'], $this->lang['newsletter.module.title']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(NewsletterUrlBuilder::edit_subscriber($id));

		return $response;
	}

	private static function subscriber_exist($id)
	{
		return PersistenceContext::get_querier()->count(NewsletterSetup::$newsletter_table_subscribers, "WHERE id = '" . $id . "'") > 0;
	}
}
?>
