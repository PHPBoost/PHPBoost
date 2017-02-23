<?php
/*##################################################
 *                       AdminModuleDeleteController.class.php
 *                            -------------------
 *   begin                : September 20, 2011
 *   copyright            : (C) 2011 Patrick DUBEAU
 *   email                : daaxwizeman@gmail.com
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

class AdminModuleDeleteController extends AdminController
{
	private $form;
	private $lang;
	private $submit_button;
	private $module_id;
	private $tpl;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->module_id = $request->get_string('id_module', null);
		
		if ($this->module_installed())
		{
			$this->build_form();
			
			if ($this->submit_button->has_been_submited() && $this->form->validate())
			{	
				$drop_files = $this->form->get_value('drop_files')->get_raw_value();
				$this->delete_module($drop_files);
			}
			$this->tpl->put('FORM', $this->form->display());
			return new AdminModulesDisplayResponse($this->tpl, $this->lang['modules.delete_module']);
		}
		else
		{
			$error_controller = PHPBoostErrors::module_not_installed();
			DispatchManager::redirect($error_controller);
		}	
	}
	
	private function init()
	{	
		$this->load_lang();
		$this->tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$this->tpl->add_lang($this->lang);
	}
	
	private function load_lang()
	{
		$this->lang = LangLoader::get('admin-modules-common');
	}
	
	private function module_installed()
	{
		if ($this->module_id == null)
		{
			return false;
		}
		return ModulesManager::is_module_installed($this->module_id);
	}
	
	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHTML('delete_module', $this->lang['modules.delete_module']);
		$fieldset->add_field(new FormFieldFree('warnings', '', $this->lang['modules.warning_before_install']));
        $form->add_fieldset($fieldset);
	
		$fieldset->add_field(new FormFieldRadioChoice('drop_files', $this->lang['modules.drop_files'], '0',
			array(
				new FormFieldRadioChoiceOption(LangLoader::get_message('yes', 'common'), '1'),
				new FormFieldRadioChoiceOption(LangLoader::get_message('no', 'common'), '0')
			)
		));
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);

		$this->form = $form;
	}
	
	private function delete_module($drop_files)
	{
		$error = ModulesManager::uninstall_module($this->module_id, $drop_files);
		if (is_int($error))
		{
			switch($error)
			{
				case ModulesManager::MODULE_FILES_COULD_NOT_BE_DROPPED:
					$this->tpl->put('MSG', MessageHelper::display(LangLoader::get_message('files_del_failed', 'main'), MessageHelper::WARNING, 10));
					break;
				case ModulesManager::NOT_INSTALLED_MODULE:
					$this->tpl->put('MSG', MessageHelper::display($this->lang['modules.not_installed_module'], MessageHelper::WARNING, 10));
					break;
				case ModulesManager::MODULE_UNINSTALLED:
					AppContext::get_response()->redirect(AdminModulesUrlBuilder::list_installed_modules());
					break;
				default: 
					$this->tpl->put('MSG', MessageHelper::display(LangLoader::get_message('process.error', 'status-messages-common'), MessageHelper::WARNING, 10));
			}
		}
		else
		{
			$this->tpl->put('MSG', MessageHelper::display($error, MessageHelper::WARNING, 10));
		}
	}
}
?>