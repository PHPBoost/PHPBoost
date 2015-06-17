<?php
/*##################################################
 *		                   NewsletterEditSubscriberController.class.php
 *                            -------------------
 *   begin                : March 16, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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
			$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), LangLoader::get_message('error-subscriber-not-existed', 'common', 'newsletter'));
			DispatchManager::redirect($controller);
		}

		$this->build_form($id);

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);
		
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save($id);
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('process.success', 'status-messages-common'), MessageHelper::SUCCESS, 4));
		}

		$tpl->put('FORM', $this->form->display());

		return $this->build_response($tpl);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'newsletter');
	}

	private function build_form($id)
	{
		$form = new HTMLForm(__CLASS__);
		
		$columns = array('*');
		$condition = "WHERE id = '". $id ."' AND user_id = -1";
		$row = PersistenceContext::get_querier()->select_single_row(NewsletterSetup::$newsletter_table_subscribers, $columns, $condition);

		$fieldset = new FormFieldsetHTML('edit-subscriber', $this->lang['subscriber.edit']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldMailEditor('mail', $this->lang['subscribe.mail'], $row['mail'],
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

	private function build_response(View $view)
	{
		$body_view = new FileTemplate('newsletter/NewsletterBody.tpl');
		$body_view->add_lang($this->lang);
		$body_view->put('TEMPLATE', $view);
		$response = new SiteDisplayResponse($body_view);
		$breadcrumb = $response->get_graphical_environment()->get_breadcrumb();
		$breadcrumb->add($this->lang['newsletter'], NewsletterUrlBuilder::home()->rel());
		$breadcrumb->add($this->lang['subscriber.edit'], '');
		
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['subscriber.edit'], $this->lang['newsletter']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(NewsletterUrlBuilder::edit_subscriber($request->get_getint('id', 0)));
		
		return $response;
	}
	
	private static function subscriber_exist($id)
	{
		return PersistenceContext::get_querier()->count(NewsletterSetup::$newsletter_table_subscribers, "WHERE id = '" . $id . "'") > 0;
	}
}
?>