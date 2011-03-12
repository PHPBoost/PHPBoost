<?php
/*##################################################
 *		                   AdminNewsletterAddCategorieController.class.php
 *                            -------------------
 *   begin                : March 11, 2011
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

class AdminNewsletterAddCategorieController extends AdminController
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
		$this->init();
		$this->build_form();

		$tpl = new StringTemplate('<script type="text/javascript">
		<!--
			Event.observe(window, \'load\', function() {
				$("newsletter_admin_advanced_authorizations").fade({duration: 0.2});
			});
		-->		
		</script># INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$tpl->put('MSG', MessageHelper::display($this->lang['admin.success-add-categorie'], E_USER_SUCCESS, 4));
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
		$form = new HTMLForm('newsletter_admin');
		
		$fieldset = new FormFieldsetHTML('add-categorie', $this->lang['categories.add']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldTextEditor('name', $this->lang['categories.name'], '', array(
			'class' => 'text', 'maxlength' => 25, 'required' => true)
		));
		
		$fieldset->add_field(new FormFieldShortMultiLineTextEditor('description', $this->lang['categories.description'], '',
		array('rows' => 4, 'cols' => 47)
		));
		
		$fieldset->add_field(new FormFieldTextEditor('picture', $this->lang['categories.picture'], '/newsletter/newsletter.png', array(
			'class' => 'text',
			'events' => array('change' => '$(\'preview_picture\').src = HTMLForms.getField(\'picture\').getValue();')
		)));
		$fieldset->add_field(new FormFieldFree('preview_picture', $this->lang['categories.picture-preview'], '<img id="preview_picture" src="'. PATH_TO_ROOT .'/newsletter/newsletter.png" alt="" style="vertical-align:top" />'));
		
		$fieldset->add_field(new FormFieldCheckbox('visible', $this->lang['categories.visible'], FormFieldCheckbox::CHECKED));
		
		$fieldset_authorizations = new FormFieldsetHTML('authorizations', $this->lang['admin.newsletter-authorizations']);
		$form->add_fieldset($fieldset_authorizations);
		
		$fieldset_authorizations->add_field(new FormFieldCheckbox('active_authorizations', $this->lang['categories.active-advanced-authorizations'], FormFieldCheckbox::UNCHECKED, 
		array('events' => array('click' => '
		if (HTMLForms.getField("active_authorizations").getValue()) {
			$("newsletter_admin_advanced_authorizations").appear(); 
		} else { 
			$("newsletter_admin_advanced_authorizations").fade();
		}')
		)));
		
		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($this->lang['categories.auth.subscribers-read'], NewsletterConfig::AUTH_READ_SUBSCRIBERS),
			new ActionAuthorization($this->lang['categories.auth.subscribers-moderation'], NewsletterConfig::AUTH_MODERATION_SUBSCRIBERS),
			new ActionAuthorization($this->lang['categories.auth.register-newsletter'], NewsletterConfig::AUTH_REGISTER_NEWSLETTER), 
			new ActionAuthorization($this->lang['categories.auth.moderation-archive'], NewsletterConfig::AUTH_MODERATION_ARCHIVE))
		);
		$default_authorizations = array();
		$auth_settings->build_from_auth_array($default_authorizations);
		$auth_setter = new FormFieldAuthorizationsSetter('advanced_authorizations', $auth_settings, array());
		$fieldset_authorizations->add_field($auth_setter);
		
		$form->add_button(new FormButtonReset());
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);

		$this->form = $form;
	}

	private function save()
	{
		$auth = $this->form->get_value('active_authorizations') ? serialize($this->form->get_value('advanced_authorizations')) : null;
		PersistenceContext::get_querier()->inject(
			"INSERT INTO " . NewsletterSetup::$newsletter_table_cats . " (name, description, picture, visible, auth)
			VALUES (:name, :description, :picture, :visible, :auth)", array(
                'name' => $this->form->get_value('name'),
				'description' => $this->form->get_value('description'),
				'picture' => $this->form->get_value('picture'),
				'visible' => (int)$this->form->get_value('visible'),
				'auth' => $auth
		));
	}

	private function build_response(View $view)
	{
		$response = new AdminMenuDisplayResponse($view);
		$response->set_title($this->lang['newsletter']);
		$response->add_link($this->lang['admin.newsletter-subscribers'], DispatchManager::get_url('/newsletter', '/subscribers'), '/newsletter/newsletter.png');
		$response->add_link($this->lang['admin.newsletter-archives'], DispatchManager::get_url('/newsletter', '/archives'), '/newsletter/newsletter.png');
		$response->add_link($this->lang['admin.newsletter-categories'], DispatchManager::get_url('/newsletter', '/admin/categories'), '/newsletter/newsletter.png');
		$response->add_link($this->lang['admin.newsletter-config'], DispatchManager::get_url('/newsletter', '/admin/config'), '/newsletter/newsletter.png');

		$env = $response->get_graphical_environment();
		$env->set_page_title($this->lang['categories.add']);
		return $response;
	}
}

?>