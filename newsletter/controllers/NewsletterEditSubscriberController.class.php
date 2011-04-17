<?php
/*##################################################
 *		                   NewsletterEditSubscriberController.class.php
 *                            -------------------
 *   begin                : March 16, 2011
 *   copyright            : (C) 2011 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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

class NewsletterEditSubscriberController extends AdminController
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
		if (!NewsletterAuthorizationsService::default_authorizations()->moderation_subscribers())
		{
			NewsletterAuthorizationsService::get_errors()->moderation_subscribers();
		}
		
		$id = $request->get_getint('id', 0);
		$this->init();
		
		$verificate_is_edit = PersistenceContext::get_querier()->count(NewsletterSetup::$newsletter_table_subscribers, "WHERE id = '". $id ."' AND user_id = 0") > 0 ? true : false;
		if (!$this->subscriber_exist($id) || !$verificate_is_edit)
		{
			$controller = new UserErrorController(LangLoader::get_message('error', 'errors'), LangLoader::get_message('error-subscriber-not-existed', 'newsletter_common', 'newsletter'));
			DispatchManager::redirect($controller);
		}

		$this->build_form($id);

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);
		
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save($id);
			$tpl->put('MSG', MessageHelper::display($this->lang['success-edit-subscriber'], E_USER_SUCCESS, 4));
		}

		$tpl->put('FORM', $this->form->display());

		return $this->build_response($tpl);
	}

	private function init()
	{
		$this->lang = LangLoader::get('newsletter_common', 'newsletter');
	}

	private function build_form($id)
	{
		$form = new HTMLForm('subscriber');
		
		$columns = array('*');
		$condition = "WHERE id = '". $id ."' AND user_id = 0";
		$row = PersistenceContext::get_querier()->select_single_row(NewsletterSetup::$newsletter_table_subscribers, $columns, $condition);

		$fieldset = new FormFieldsetHTML('edit-subscriber', $this->lang['subscriber.edit']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldTextEditor('mail', $this->lang['subscribe.mail'], $row['mail'], array(
			'class' => 'text', 'required' => true),
			array(new FormFieldConstraintMailAddress())
		));

		$form->add_button(new FormButtonReset());
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);

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
		$response = new SiteDisplayResponse($view);
		$breadcrumb = $response->get_graphical_environment()->get_breadcrumb();
		$breadcrumb->add($this->lang['newsletter'], PATH_TO_ROOT . '/newsletter/');
		$breadcrumb->add($this->lang['subscriber.edit'], '');
		$response->get_graphical_environment()->set_page_title($this->lang['subscriber.edit']);
		return $response;
	}	
	
	private static function subscriber_exist($id)
	{
		return PersistenceContext::get_querier()->count(NewsletterSetup::$newsletter_table_subscribers, "WHERE id = '" . $id . "'") > 0 ? true : false;
	}
}

?>