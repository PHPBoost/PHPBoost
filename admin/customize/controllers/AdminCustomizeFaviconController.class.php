<?php
/*##################################################
 *                       AdminCustomizeFaviconControllerr.class.php
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

class AdminCustomizeFaviconController extends AdminController
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
	
	private $config;

	public function execute(HTTPRequest $request)
	{
		$this->load_lang();
		$this->load_config();	
		$this->build_form();

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$favicon = $this->form->get_value('favicon', null);
			
			if($favicon !== null)
			{
				$this->save($favicon);
				$tpl->put('MSG', MessageHelper::display($this->lang['customization.favicon.success'], E_USER_SUCCESS, 4));
			}
			$tpl->put('MSG', MessageHelper::display($this->lang['customization.favicon.error'], E_USER_ERROR, 4));
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminCustomizationDisplayResponse($tpl, $this->lang['customization.interface']);
	}

	private function load_lang()
	{
		//$this->lang = LangLoader::get('admin-customization-common');
		$this->lang = array(
			'customization.interface' => 'Personnalisation de l\'interface',
			'customization.favicon' => 'Personnalisation du favicon',
			'customization.favicon.success' => 'Changement du favicon réussit',
			'customization.favicon.current' => 'Favicon actuel',
			'customization.favicon.current.null' => 'Vous n\'avez pas de favicon actuellement',
			'customization.favicon.current.change' => 'Changer votre favicon',
			'customization.favicon.current.erased' => '<span style="color:#B22222;font-weight:bold;">Le favicon que vous avez enregistré est visiblement supprimé de votre serveur, veuillez le remplacer par un autret</span>',
			'customization.favicon.error' => 'Le favicon n\'a pas pû être changé'
		);
	}
	
	private function load_config()
	{
		$this->config = CustomizationConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm('customize-favicon');
		
		$fieldset = new FormFieldsetHTML('customize-favicon', $this->lang['customization.favicon']);
		$form->add_fieldset($fieldset);
		
		if ($this->config->get_favicon_path() == null || $this->config->get_favicon_path() == '')
		{
			$fieldset->add_field(new FormFieldFree('current_favicon', $this->lang['customization.favicon.current'], $this->lang['customization.favicon.current.null']));
		}
		else
		{
			$picture_link = PATH_TO_ROOT . $this->config->get_favicon_path();
			if (file_exists($picture_link))
			{
				$picture = '<img src="' . $picture_link . '">';
				$fieldset->add_field(new FormFieldFree('current_favicon', $this->lang['customization.favicon.current'], $picture));
			}
			else
			{
				$fieldset->add_field(new FormFieldFree('current_favicon', $this->lang['customization.favicon.current'], $this->lang['customization.favicon.current.erased']));
			}
		}
		
		$fieldset->add_field(new FormFieldFilePicker('favicon', $this->lang['customization.favicon.current.change']));
		
		$form->add_button(new FormButtonReset());
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);

		$this->form = $form;
	}
	
	private function save($favicon)
	{
		move_uploaded_file($favicon->get_temporary_filename(), PATH_TO_ROOT . '/' . $favicon->get_name());

		$this->config->set_favicon_path('/' . $favicon->get_name());
		CustomizationConfig::save();
	}	
}
?>