<?php
/*##################################################
 *                       AdminModuleAddController.class.php
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

class AdminModuleAddController extends AdminController
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
		$this->save($request);
		
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->upload_module();
		}
		
		return new AdminModuleDisplayResponse($this->view, $this->lang['admin-module.add_module']);
	}
	
	private function init()
	{	
		$this->load_lang();
		$this->view = new FileTemplate('admin/module/AdminModuleAddController.tpl');
		$this->view->add_lang($this->lang);
	}
	
	//TODO faire fichier de langue
	private function load_lang()
	{
		$this->lang = LangLoader::get('admin-module-common');
	}
	
	private function upload_form()
	{
		$form = new HTMLForm('upload_module');
		
		$fieldset = new FormFieldsetHTML('upload_module', $this->lang['admin-module.upload_module']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldFilePicker('upload_module', $this->lang['admin-module.upload_description']));
		
		$this->submit_button = new FormButtonSubmit($this->lang['admin-module.install_module'], '');
		$form->add_button($this->submit_button);	
		
		$this->form = $form;
	}
	
	private function build_view()
	{
		$modules_not_installed = $this->get_modules_not_installed();
		foreach ($modules_not_installed as $name)
		{
			$module = new Module($name);
			$configuration = ModuleConfigurationManager::get($name);
			$author = $configuration->get_author();
			$author_email = $configuration->get_author_email();
			$author_website = $configuration->get_author_website();
			
			$this->view->assign_block_vars('available', array(
				'ID' => $module->get_id(),
				'NAME' => ucfirst($name),
				'ICON' => $module->get_id(),
				'VERSION' => $configuration->get_version(),
				'AUTHOR' => !empty($author_email) ? '<a href="mailto:' . $author_email . '">' . $author . '</a>' : $author,
				'AUTHOR_WEBSITE' => !empty($author_website) ? '<a href="' . $author_website . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/user_web.png" alt="" /></a>' : '',
				'DESCRIPTION' => $configuration->get_description(),
				'COMPATIBILITY' => $configuration->get_compatibility(),
				'PHP_VERSION' => $configuration->get_php_version(),
				'URL_REWRITE_RULES' => $configuration->get_url_rewrite_rules()		
			));
		}
		
		$this->view->put_all(array(
			'C_MODULES_AVAILABLE' => count($modules_not_installed) > 0 ? true : false,
		));
	}
	
	private function get_modules_not_installed()
	{
		$modules_not_installed = array();
		$modules_folder = new Folder(PATH_TO_ROOT);
		foreach ($modules_folder->get_folders() as $module)
		{
			$name = $module->get_name();
			if ($name !== 'lang' && !ModulesManager::is_module_installed($name))
			{
				$modules_not_installed[] = $name;
			}
			sort($modules_not_installed);
			return $modules_not_installed;
		}
	}
	
	private function save(HTTPRequest $request)
	{
		$module_id = $request->get_string('id_module', '') ;
		
		if (!empty($module_id))
		{
			switch(ModulesManager::install_module($module_id, true, ModulesManager::GENERATE_CACHE_AFTER_THE_OPERATION))
			{
				case CONFIG_CONFLICT:
					$this->view->put('MSG', MessageHelper::display(LangLoader::get('e_config_conflict'), MessageHelper::WARNING, 10));
					break;
				case UNEXISTING_MODULE:
					$this->view->put('MSG', MessageHelper::display(LangLoader::get('e_unexist_module'), MessageHelper::WARNING, 10));
					break;
				case MODULE_ALREADY_INSTALLED:
					$this->view->put('MSG', MessageHelper::display(LangLoader::get('e_installed_module'), MessageHelper::WARNING, 10));
					break;
				case PHP_VERSION_CONFLICT:
					$this->view->put('MSG', MessageHelper::display(LangLoader::get('e_php_version_conflict'), MessageHelper::WARNING, 10));
					break;
				case MODULE_INSTALLED:
				default: 
					$this->view->put('MSG', MessageHelper::display(LangLoader::get('e_installed_module'), MessageHelper::NOTICE, 10));
			}
			
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
							
							$this->view->put('MSG', MessageHelper::display($this->lang['module.upload.success'], MessageHelper::SUCCESS, 4));
						}
						else if ($upload->extension['upload_module_upload_module'] == 'zip')
						{
							import('lib/pcl/pclzip', LIB_IMPORT);
							$zip = new PclZip($archive_path);
							$zip->extract(PCLZIP_OPT_PATH, $module_folder, PCLZIP_OPT_SET_CHMOD, 0755);
							
							$file = new File($archive_path);
							$file->delete();
							
							$this->view->put('MSG', MessageHelper::display($this->lang['admin-module.upload_success'], MessageHelper::SUCCESS, 4));
						}
						else
						{
							$this->view->put('MSG', MessageHelper::display($this->lang['admin-module.upload_invalid_format'], MessageHelper::ERROR, 4));
						}
					}
				}
				else
				{
					$this->view->put('MSG', MessageHelper::display($this->lang['admin-module.already_installed'], MessageHelper::ERROR, 4));
				}
			}
			else
			{
				$this->view->put('MSG', MessageHelper::display($this->lang['admin-module.upload_error'], MessageHelper::ERROR, 4));
			}
		}
	}
}

?>