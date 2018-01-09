<?php
/*##################################################
 *                      AdminUninstallLangController.class.php
 *                            -------------------
 *   begin                : January 20, 2012
 *   copyright            : (C) 2012 Kevin MASSY
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

class AdminUninstallLangController extends AdminController
{
	private $form;
	private $lang;
	private $submit_button;
	private $lang_id;
	private $multiple = false;
	private $tpl;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$ids = explode('---', $request->get_value('id', null));
		
		if (count($ids) > 1)
		{
			$this->lang_id = $ids;
			$this->multiple = true;
		}
		else
			$this->lang_id = $request->get_value('id', null);
		
		if ($this->lang_exists())
		{
			$this->build_form();
			if ($this->submit_button->has_been_submited() && $this->form->validate())
			{
				$this->uninstall($this->form->get_value('drop_files')->get_raw_value());

				AppContext::get_response()->redirect(AdminLangsUrlBuilder::list_installed_langs(), LangLoader::get_message('process.success', 'status-messages-common'));
			}
			
			$this->tpl->put('FORM', $this->form->display());

			return new AdminLangsDisplayResponse($this->tpl, $this->multiple ? $this->lang['langs.uninstall_lang_multiple'] : $this->lang['langs.delete_lang']);
		}
		else
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('admin-langs-common');
		$this->tpl = new StringTemplate('# INCLUDE FORM #');
		$this->tpl->add_lang($this->lang);
	}
	
	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHTML('uninstall_lang', $this->multiple ? $this->lang['langs.uninstall_lang_multiple'] : $this->lang['langs.delete_lang']);
		$form->add_fieldset($fieldset);
	
		$fieldset->add_field(new FormFieldRadioChoice('drop_files', $this->multiple ? $this->lang['langs.drop_files_multiple'] : $this->lang['langs.drop_files'], '0',
			array(
				new FormFieldRadioChoiceOption(LangLoader::get_message('yes', 'common'), '1'),
				new FormFieldRadioChoiceOption(LangLoader::get_message('no', 'common'), '0')
			)
		));
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);

		$this->form = $form;
	}
	
	private function uninstall($drop_files)
	{
		if ($this->multiple)
		{
			foreach ($this->lang_id as $id)
			{
				LangsManager::uninstall($id, $drop_files);
			}
		}
		else
			LangsManager::uninstall($this->lang_id, $drop_files);
	}
	
	private function lang_exists()
	{
		if ($this->lang_id == null)
		{
			return false;
		}
		if ($this->multiple)
		{
			$lang_exists = false;
			foreach ($this->lang_id as $id)
			{
				if (LangsManager::get_lang_existed($id))
				{
					$lang_exists = true;
					break;
				}
			}
			return $lang_exists;
		}
		else
			return LangsManager::get_lang_existed($this->lang_id);
	}
}

?>