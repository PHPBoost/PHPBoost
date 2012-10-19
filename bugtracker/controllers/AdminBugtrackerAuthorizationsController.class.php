<?php
/*##################################################
 *                              AdminBugtrackerAuthorizationsController.class.php
 *                            -------------------
 *   begin                : October 18, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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

class AdminBugtrackerAuthorizationsController extends AdminModuleController
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
		$this->init();
		$this->build_form();

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$tpl->put('MSG', MessageHelper::display($this->lang['admin.success-saving-config'], E_USER_SUCCESS, 4));
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminBugtrackerDisplayResponse($tpl, $this->lang['bugs.titles.admin.authorizations']);
	}

	private function init()
	{
		$this->lang = LangLoader::get('bugtracker_common', 'bugtracker');
	}

	private function build_form()
	{
		$form = new HTMLForm('bugtracker_admin');
		$bugtracker_config = BugtrackerConfig::load();

		$fieldset_authorizations = new FormFieldsetHTML('authorizations', $this->lang['bugs.titles.admin.authorizations']);
		$form->add_fieldset($fieldset_authorizations);
		
		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($this->lang['bugs.config.auth.read'], BugtrackerConfig::BUG_READ_AUTH_BIT),
			new ActionAuthorization($this->lang['bugs.config.auth.create'], BugtrackerConfig::BUG_CREATE_AUTH_BIT),
			new ActionAuthorization($this->lang['bugs.config.auth.create_advanced'], BugtrackerConfig::BUG_CREATE_ADVANCED_AUTH_BIT, $this->lang['bugs.config.auth.create_advanced_explain']),
			new ActionAuthorization($this->lang['bugs.config.auth.moderate'], BugtrackerConfig::BUG_MODERATE_AUTH_BIT)
		));
		
		$auth_settings->build_from_auth_array($bugtracker_config->get_authorizations());
		$auth_setter = new FormFieldAuthorizationsSetter('authorizations', $auth_settings);
		$fieldset_authorizations->add_field($auth_setter);
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());
		
		$this->form = $form;
	}
	
	private function save()
	{
		$bugtracker_config = BugtrackerConfig::load();
		$bugtracker_config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());
		BugtrackerConfig::save();
	}
}
?>