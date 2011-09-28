<?php
/*##################################################
 *                       AdminModuleUpdateController.class.php
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

class AdminModuleUpdateController extends AdminController
{
	private $lang;
	private $view;
	private $form;
	private $submit_button;
	
	
	public function execute(HTTPRequest $request)
	{
		$this->init();
		$this->upload_form();
		$this->view->put('UPLOAD_FORM', $this->form->display());
		
		$this->build_view();
		$this->update($request);
		
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->upload_module();
		}
		
		return new AdminModulesDisplayResponse($this->view, $this->lang['modules.update_module']);
	}
	
	private function init()
	{	
		$this->load_lang();
		$this->view = new FileTemplate('admin/modules/AdminModuleUpdateController.tpl');
		$this->view->add_lang($this->lang);
	}
	
	//TODO faire fichier de langue
	private function load_lang()
	{
		$this->lang = LangLoader::get('admin-modules-common');
	}
	
	private function upload_form()
	{
		$form = new HTMLForm('upload_module');
		
		$fieldset = new FormFieldsetHTML('upload_module', $this->lang['modules.upload_module']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldFilePicker('upload_module', $this->lang['modules.upload_description']));
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);

		$this->form = $form;
	}
	
	private function build_view()
	{
		$modules_installed = ModulesManager::get_installed_modules_ids_list();
		foreach ($modules_installed as $module)
		{
			if (ModulesManager::module_is_upgradable($module))
			{
				$configuration = ModuleConfigurationManager::get($module);
				$author = $configuration->get_author();
				$author_email = $configuration->get_author_email();
				$author_website = $configuration->get_author_website();
				
				$this->view->assign_block_vars('modules_upgradable', array(
					'ID' => $module->get_id(),
					'NAME' => ucfirst($name),
					'ICON' => $module->get_id(),
					'VERSION' => $configuration->get_version(),
					'AUTHOR' => !empty($author_email) ? '<a href="mailto:' . $author_email . '">' . $author . '</a>' : $author,
					'AUTHOR_WEBSITE' => !empty($author_website) ? '<a href="' . $author_website . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/user_web.png" alt="" /></a>' : '',
					'DESCRIPTION' => $configuration->get_description(),
					'COMPATIBILITY' => $configuration->get_compatibility(),
					'PHP_VERSION' => $configuration->get_php_version(),
					'URL_REWRITE_RULES' => $configuration->get_url_rewrite_rules(),
					'C_UPDATE' => true	
				));
			}
			else
			{
				$this->view->put_all(array(
					'C_NO_UPGRADABLE_MODULES_AVAILABLE' => true
				));
			}	
		}
	}
	
	private function update(HTTPRequest $request)
	{
		$module_id = $request->get_string('id_module', '') ;
		
		if (!empty($module_id))
		{
			switch (ModulesManager::upgrade_module($module_id))
			{
				case UPGRADE_FAILED :
					$this->view->put('MSG', MessageHelper::display($this->lang['modules.upgrade_failed'], MessageHelper::WARNING, 10));
					break;
				case MODULE_NOT_UPGRADABLE :
					$this->view->put('MSG', MessageHelper::display($this->lang['modules.module_not_upgradable'], MessageHelper::WARNING, 10));
					break;
				case NOT_INSTALLED_MODULE :
					$this->view->put('MSG', MessageHelper::display($this->lang['modules.not_installed_module'], MessageHelper::WARNING, 10));
					break;
				case UNEXISTING_MODULE :
					$this->view->put('MSG', MessageHelper::display($this->lang['modules.unexisting_module'], MessageHelper::WARNING, 10));
					break;
				case MODULE_UPDATED :
					$this->view->put('MSG', MessageHelper::display($this->lang['modules.update_success'], MessageHelper::SUCCESS, 10));
					break;
			}
		}
		else 
		{
			$this->view->put('MSG', MessageHelper::display($this->lang['modules.error_id_module'], MessageHelper::WARNING, 10));
		}
	}
	
	private function upload_module()
	{
		$modules_folder = new Folder(PATH_TO_ROOT);
		
		if (!is_writable($modules_folder))
		{
			$is_writable = @chmod($dir, 0755);
		}
		else
		{
			$is_writable = true;
		
		}
		
		if (is_writable)
		{
			$file = $this->form->get_value('upload_module');
			if ($file !== null)
			{
				if (!ModulesManager::is_module_installed($file->get_name()))
				{
					$upload = new Upload($modules_folder);
					
					if ($upload->file('upload_module_upload_module', '`([a-z0-9()_-])+\.(gzip|zip)+$`i'))
					{
						$archive_path = $modules_folder. '/' .$upload->filename['upload_module_upload_module'];
						
						if ($upload->extension['upload_module_upload_module'] == 'gzip')
						{
							import('lib/pcl/pcltar', LIB_IMPORT);
							PclTarExtract($upload->filename['upload_module_upload_module'], $module_folder);
							
							$file = new File($archive_path);
							$file->delete();
							
							$this->view->put('MSG', MessageHelper::display($this->lang['module.upload_success'], MessageHelper::SUCCESS, 4));
						}
						else if ($upload->extension['upload_module_upload_module'] == 'zip')
						{
							import('lib/pcl/pclzip', LIB_IMPORT);
							$zip = new PclZip($archive_path);
							$zip->extract(PCLZIP_OPT_PATH, $module_folder, PCLZIP_OPT_SET_CHMOD, 0755);
							
							$file = new File($archive_path);
							$file->delete();
							
							$this->view->put('MSG', MessageHelper::display($this->lang['module.upload_success'], MessageHelper::SUCCESS, 4));
						}
						else
						{
							$this->view->put('MSG', MessageHelper::display($this->lang['module.upload_invalid_format'], MessageHelper::ERROR, 4));
						}
					}
				}
				else
				{
					$this->view->put('MSG', MessageHelper::display($this->lang['module.already_installed'], MessageHelper::ERROR, 4));
				}
			}
			else
			{
				$this->view->put('MSG', MessageHelper::display($this->lang['module.upload_error'], MessageHelper::ERROR, 4));
			}
		}
	}
	
	private function get_error_message($error = 11)
	{
		switch ($error)
		{
			case UPGRADE_FAILED :
				$this->view->put('MSG', MessageHelper::display($this->lang['modules.upgrade_failed'], MessageHelper::WARNING, 10));
				break;
			case MODULE_NOT_UPGRADABLE :
				$this->view->put('MSG', MessageHelper::display($this->lang['modules.module_not_upgradable'], MessageHelper::WARNING, 10));
				break;
			case NOT_INSTALLED_MODULE :
				$this->view->put('MSG', MessageHelper::display($this->lang['modules.not_installed_module'], MessageHelper::WARNING, 10));
				break;
			case UNEXISTING_MODULE :
				$this->view->put('MSG', MessageHelper::display($this->lang['modules.unexisting_module'], MessageHelper::WARNING, 10));
				break;
			default :
				$this->view->put('MSG', MessageHelper::display($this->lang['modules.error_id_module'], MessageHelper::WARNING, 10));
		}
	}
}

?>