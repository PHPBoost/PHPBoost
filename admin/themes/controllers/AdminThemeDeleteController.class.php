<?php
/*##################################################
 *                      AdminThemeDeleteController.class.php
 *                            -------------------
 *   begin                : April 21, 2011
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

class AdminThemeDeleteController extends AdminController
{
	private $form;
	private $lang;
	private $submit_button;
	private $theme_id;
	private $tpl;

	public function execute(HTTPRequest $request)
	{
		$this->init();
		$this->theme_id = $request->get_value('id', null);

		if ($this->theme_exist())
		{
			$this->build_form();
			if ($this->submit_button->has_been_submited() && $this->form->validate())
			{
				$drop_files = $this->form->get_value('drop_files')->get_raw_value();
				$this->delete_theme($drop_files);

				$this->tpl->put('MSG', MessageHelper::display($this->lang['themes.delete.success'], MessageHelper::SUCCESS, 4));
			}
				
			$this->tpl->put('FORM', $this->form->display());

			return new AdminThemesDisplayResponse($this->tpl, $this->lang['themes.delete']);
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
		$this->tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$this->tpl->add_lang($this->lang);
	}

	private function build_form()
	{
		$form = new HTMLForm('delete_theme');

		$fieldset = new FormFieldsetHTML('delete_theme', $this->lang['themes.delete']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldRadioChoice('drop_files', $this->lang['themes.delete.drop_files'], '0',
		array(
		new FormFieldRadioChoiceOption($this->lang['themes.yes'], '1'),
		new FormFieldRadioChoiceOption($this->lang['themes.no'], '0')
		)
		));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);

		$this->form = $form;
	}

	private function delete_theme($drop_files)
	{
		ThemeManager::uninstall($this->theme_id, $drop_files);

		$this->delete_css_cache();
	}

	private function theme_exist()
	{
		if ($this->theme_id == null)
		{
			return false;
		}
		return ThemeManager::get_theme_existed($this->theme_id);
	}

	private function delete_css_cache()
	{
		$modules_cache = PATH_TO_ROOT .'/cache/css/css-cache-modules-'. $this->theme_id .'.css';
		$theme_cache = PATH_TO_ROOT .'/cache/css/css-cache-theme-'. $this->theme_id .'.css';

		if (file_exists($theme_cache))
		{
			$file = new File($theme_cache);
			$file->delete();
		}
		if (file_exists($modules_cache))
		{
			$file = new File($modules_cache);
			$file->delete();
		}
	}
}

?>