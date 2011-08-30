<?php
/*##################################################
 *                       AdminCustomizeInterfaceController.class.php
 *                            -------------------
 *   begin                : August 30, 2011
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

class AdminCustomizeInterfaceController extends AdminController
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
		$this->load_lang();		
		$this->build_form($request->get_value('theme', 'all'));

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$tpl->put('MSG', MessageHelper::display($this->lang['customization.interface.success'], E_USER_SUCCESS, 4));
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminCustomizationDisplayResponse($tpl, $this->lang['customization.interface']);
	}

	private function load_lang()
	{
		//$this->lang = LangLoader::get('admin-customization-common');
		$this->lang = array(
			'customization.interface' => 'Personnalisation de l\'interface',
			'customization.interface.success' => 'Succès',
			'customization.interface.all-themes' => 'Tous les thèmes',
			'customization.interface.select-theme' => 'Séléctionner le thème auquel vous souhaitez attribuer les changements',
			'customization.interface.theme-choise' => 'Choix du thème'
			
		);
	}

	private function build_form($theme_selected)
	{
		$form = new HTMLForm('customize-interface');
		
		$fieldset = new FormFieldsetHTML('theme-choise', $this->lang['customization.interface.theme-choise']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(
			new FormFieldSimpleSelectChoice('select_theme', $this->lang['customization.interface.select-theme'], $theme_selected,
				$this->list_themes(),
				array('events' => 
					array(
						'change' => 'document.location.href = "' . DispatchManager::get_url('/admin/customize', '/interface/')->absolute() . '" + HTMLForms.getField(\'select_theme\').getValue()'
					)
				)
			)
		);
		
		$form->add_button(new FormButtonReset());
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);

		$this->form = $form;
	}

	private function list_themes()
	{
		$choices_list = array();
		$choices_list[] = new FormFieldSelectChoiceOption($this->lang['customization.interface.all-themes'], 'all');
		foreach (ThemeManager::get_activated_themes_map() as $id => $value) 
		{
			if (UserAccountsConfig::load()->get_default_theme() == $id || (AppContext::get_user()->check_auth($value->get_authorizations(), AUTH_THEME)))
			{
				$choices_list[] = new FormFieldSelectChoiceOption($value->get_configuration()->get_name(), $id);
			}
		}
		return $choices_list;
	}
	
}
?>