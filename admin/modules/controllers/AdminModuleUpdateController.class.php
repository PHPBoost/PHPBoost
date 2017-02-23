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
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->upload_form();
		
		$this->upgrade_module($request);
		
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->upload_module();
		}
		
		$this->build_view();
		
		$this->view->put('UPLOAD_FORM', $this->form->display());
		
		return new AdminModulesDisplayResponse($this->view, $this->lang['modules.update_module']);
	}
	
	private function init()
	{	
		$this->load_lang();
		$this->view = new FileTemplate('admin/modules/AdminModuleUpdateController.tpl');
		$this->view->add_lang($this->lang);
	}
	
	private function load_lang()
	{
		$this->lang = LangLoader::get('admin-modules-common');
	}
	
	private function upload_form()
	{
		$form = new HTMLForm('upload_module', '', false);
		
		$fieldset = new FormFieldsetHTML('upload', $this->lang['modules.update_module']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldFree('warnings', '', $this->lang['modules.warning_before_install']));
        $fieldset->add_field(new FormFieldFilePicker('file', $this->lang['modules.upload_description']));
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		
		$this->form = $form;
	}
	
	private function build_view()
	{
		$modules_upgradable = 0;
		foreach (ModulesManager::get_installed_modules_map() as $module)
		{
			if (ModulesManager::module_is_upgradable($module->get_id()))
			{
				$configuration = $module->get_configuration();
				$author = $configuration->get_author();
				$author_email = $configuration->get_author_email();
				$author_website = $configuration->get_author_website();
				
				$this->view->assign_block_vars('modules_upgradable', array(
					'ID' => $module->get_id(),
					'NAME' => ucfirst($configuration->get_name()),
					'ICON' => $module->get_id(),
					'VERSION' => $configuration->get_version(),
					'AUTHOR' => !empty($author_email) ? '<a href="mailto:' . $author_email . '">' . $author . '</a>' : $author,
					'AUTHOR_WEBSITE' => !empty($author_website) ? '<a href="' . $author_website . '" class="basic-button smaller">Web</a>' : '',
					'DESCRIPTION' => $configuration->get_description(),
					'COMPATIBILITY' => $configuration->get_compatibility(),
					'PHP_VERSION' => $configuration->get_php_version(),
					'URL_REWRITE_RULES' => $configuration->get_url_rewrite_rules()
				));
				
				$modules_upgradable++;
			}
		}
		
		$this->view->put_all(array(
			'C_UPDATES' => !empty($modules_upgradable)
		));
	}
	
	private function upgrade_module(HTTPRequestCustom $request, $module_id = '')
	{
		if (empty($module_id))
		{
			$installed_modules = ModulesManager::get_installed_modules_map();
			
			foreach ($installed_modules as $module)
			{
				if ($request->get_string('upgrade-' . $module->get_id(), ''))
				{
					$module_id = $module->get_id();
				}
			}
		}
		
		if (!empty($module_id))
		{
			switch (ModulesManager::upgrade_module($module_id))
			{
				case ModulesManager::UPGRADE_FAILED:
					$this->view->put('MSG', MessageHelper::display(LangLoader::get_message('process.error', 'status-messages-common'), MessageHelper::WARNING, 10));
					break;
				case ModulesManager::MODULE_NOT_UPGRADABLE:
					$this->view->put('MSG', MessageHelper::display($this->lang['modules.module_not_upgradable'], MessageHelper::WARNING, 10));
					break;
				case ModulesManager::NOT_INSTALLED_MODULE:
					$this->view->put('MSG', MessageHelper::display($this->lang['modules.not_installed_module'], MessageHelper::WARNING, 10));
					break;
				case ModulesManager::UNEXISTING_MODULE:
					$this->view->put('MSG', MessageHelper::display(LangLoader::get_message('element.unexist', 'status-messages-common'), MessageHelper::WARNING, 10));
					break;
				case ModulesManager::MODULE_UPDATED:
					$this->view->put('MSG', MessageHelper::display(LangLoader::get_message('process.success', 'status-messages-common'), MessageHelper::SUCCESS, 10));
					break;
			}
		}
	}
	
	private function upload_module()
	{
		$modules_folder = PATH_TO_ROOT . '/';
		if (!is_writable($modules_folder))
		{
			$is_writable = @chmod($dir, 0755);
		}
		else
		{
			$is_writable = true;
		}
		
		if ($is_writable)
		{
			$uploaded_file = $this->form->get_value('file');
			if ($uploaded_file !== null)
			{
				$upload = new Upload($modules_folder);
				$upload->disableContentCheck();
				if ($upload->file('upload_module_file', '`([A-Za-z0-9-_]+)\.(gz|zip)+$`i', false, 100000000, false))
				{
					$archive = $modules_folder . $upload->get_filename();
					if ($upload->get_extension() == 'gz')
					{
						include_once(PATH_TO_ROOT . '/kernel/lib/php/pcl/pcltar.lib.php');
						$archive_content = PclTarList($upload->get_filename());
					}
					else
					{
						include_once(PATH_TO_ROOT . '/kernel/lib/php/pcl/pclzip.lib.php');
						$zip = new PclZip($archive);
						$archive_content = $zip->listContent();
					}
					
					$archive_root_content = array();
					$required_files = array('/config.ini');
					foreach ($archive_content as $element)
					{
						if (substr($element['filename'], -1) == '/')
							$element['filename'] = substr($element['filename'], 0, -1);
						if (substr_count($element['filename'], '/') == 0)
							$archive_root_content[] = array('filename' => $element['filename'], 'folder' => ((isset($element['folder']) && $element['folder'] == 1) || (isset($element['typeflag']) && $element['typeflag'] == 5)));
						if (isset($archive_root_content[0]))
						{
							$name_in_archive = str_replace($archive_root_content[0]['filename'] . '/', '/', $element['filename']);
							
							if (in_array($name_in_archive, $required_files))
							{
								unset($required_files[array_search($name_in_archive, $required_files)]);
							}
						}
					}
					
					if (count($archive_root_content) == 1 && $archive_root_content[0]['folder'] && empty($required_files))
					{
						$module_id = $archive_root_content[0]['filename'];
						if (ModulesManager::is_module_installed($module_id))
						{
							if ($upload->get_extension() == 'gz')
								PclTarExtract($upload->get_filename(), $modules_folder);
							else
								$zip->extract(PCLZIP_OPT_PATH, $modules_folder, PCLZIP_OPT_SET_CHMOD, 0755);
							
							$this->upgrade_module(AppContext::get_request(), $module_id);
						}
						else
						{
							$this->view->put('MSG', MessageHelper::display($this->lang['modules.not_installed_module'], MessageHelper::NOTICE));
						}
					}
					else
					{
						$this->view->put('MSG', MessageHelper::display(LangLoader::get_message('error.invalid_archive_content', 'status-messages-common'), MessageHelper::NOTICE));
					}
					
					$uploaded_file = new File($archive);
					$uploaded_file->delete();
				}
				else
				{
					$this->view->put('MSG', MessageHelper::display($this->lang['modules.upload_invalid_format'], MessageHelper::NOTICE));
				}
			}
			else
			{
				$this->view->put('MSG', MessageHelper::display($this->lang['modules.upload_error'], MessageHelper::NOTICE));
			}
		}
	}
}
?>
