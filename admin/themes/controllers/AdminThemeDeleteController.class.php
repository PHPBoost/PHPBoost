<?php
/*##################################################
 *                      AdminThemeDeleteController.class.php
 *                            -------------------
 *   begin                : April 21, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

class AdminThemeDeleteController extends AdminController
{
	private $form;
	private $lang;
	private $submit_button;
	private $theme_id;
	private $multiple = false;
	private $tpl;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$ids = explode('--', $request->get_value('id', null));
		
		if (count($ids) > 1)
		{
			$theme_ids = array();
			$theme_number = 1;
			foreach (ThemesManager::get_installed_themes_map_sorted_by_localized_name() as $theme)
			{
				if (in_array($theme_number, $ids))
				{
					$theme_ids[] = $theme->get_id();
				}
				$theme_number++;
			}
			$this->theme_id = $theme_ids;
			$this->multiple = true;
		}
		else
			$this->theme_id = $request->get_value('id', null);
		
		if ($this->theme_exists())
		{
			$this->build_form();
			if ($this->submit_button->has_been_submited() && $this->form->validate())
			{
				$drop_files = $this->form->get_value('drop_files')->get_raw_value();
				$this->delete_theme($drop_files);

				AppContext::get_response()->redirect(AdminThemeUrlBuilder::list_installed_theme(), LangLoader::get_message('process.success', 'status-messages-common'));
			}
			
			$this->tpl->put('FORM', $this->form->display());

			return new AdminThemesDisplayResponse($this->tpl, $this->multiple ? $this->lang['themes.delete_theme_multiple'] : $this->lang['themes.delete_theme']);
		}
		else
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('admin-themes-common');
		$this->tpl = new StringTemplate('# INCLUDE FORM #');
		$this->tpl->add_lang($this->lang);
	}
	
	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHTML('delete_theme', $this->multiple ? $this->lang['themes.delete_theme_multiple'] : $this->lang['themes.delete_theme']);
		$form->add_fieldset($fieldset);
	
		$fieldset->add_field(new FormFieldRadioChoice('drop_files', $this->multiple ? $this->lang['themes.drop_files_multiple'] : $this->lang['themes.drop_files'], '0',
			array(
				new FormFieldRadioChoiceOption(LangLoader::get_message('yes', 'common'), '1'),
				new FormFieldRadioChoiceOption(LangLoader::get_message('no', 'common'), '0')
			)
		));
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);

		$this->form = $form;
	}
	
	private function delete_theme($drop_files)
	{
		if ($this->multiple)
		{
			foreach ($this->theme_id as $id)
			{
				ThemesManager::uninstall($id, $drop_files);
			}
		}
		else
			ThemesManager::uninstall($this->theme_id, $drop_files);
	}
	
	private function theme_exists()
	{
		if ($this->theme_id == null)
		{
			return false;
		}
		
		if ($this->multiple)
		{
			$theme_exists = false;
			foreach ($this->theme_id as $id)
			{
				if (ThemesManager::get_theme_existed($id))
				{
					$theme_exists = true;
					break;
				}
			}
			return $theme_exists;
		}
		else
			return ThemesManager::get_theme_existed($this->theme_id);
	}
}
?>