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

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$module_number = 1;
		foreach ($this->get_modules_not_installed() as $name => $module)
		{
			if ($request->get_string('add-' . $module->get_id(), false) || ($request->get_string('add-selected-modules', false) && $request->get_value('add-checkbox-' . $module_number, 'off') == 'on'))
			{
				$this->install_module($module->get_id(), true);
			}

			$module_number++;
		}

		$this->upload_form();

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->upload_module();
		}

		$this->build_view();

		$this->view->put('UPLOAD_FORM', $this->form->display());

		return new AdminModulesDisplayResponse($this->view, $this->lang['modules.add_module']);
	}

	private function init()
	{
		$this->lang = LangLoader::get('admin-modules-common');
		$this->view = new FileTemplate('admin/modules/AdminModuleAddController.tpl');
		$this->view->add_lang($this->lang);
	}

	private function upload_form()
	{
		$form = new HTMLForm('upload_module', '', false);

		$fieldset = new FormFieldsetHTML('upload', $this->lang['modules.upload_module']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldFree('warnings', '', $this->lang['modules.warning_before_install'], array('class' => 'full-field')));
        $fieldset->add_field(new FormFieldFilePicker('file', $this->lang['modules.upload_description'], array('class' => 'half-field')));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);

		$this->form = $form;
	}

	private function build_view()
	{
		$modules_not_installed = $this->get_modules_not_installed();
		$module_number = 1;
		foreach ($modules_not_installed as $id => $module)
		{
			$configuration = $module->get_configuration();
			$author_email = $configuration->get_author_email();
			$author_website = $configuration->get_author_website();

			$this->view->assign_block_vars('modules_not_installed', array(
				'C_AUTHOR_EMAIL' => !empty($author_email),
				'C_AUTHOR_WEBSITE' => !empty($author_website),
				'MODULE_NUMBER' => $module_number,
				'ID' => $module->get_id(),
				'NAME' => TextHelper::ucfirst($configuration->get_name()),
				'ICON' => $module->get_id(),
				'VERSION' => $configuration->get_version(),
				'AUTHOR' => $configuration->get_author(),
				'AUTHOR_EMAIL' => $author_email,
				'AUTHOR_WEBSITE' => $author_website,
				'DESCRIPTION' => $configuration->get_description(),
				'COMPATIBILITY' => $configuration->get_compatibility(),
				'PHP_VERSION' => $configuration->get_php_version(),
				'URL_REWRITE_RULES' => $configuration->get_url_rewrite_rules()
			));
			$module_number++;
		}

		$not_installed_modules_number = count($modules_not_installed);
		$this->view->put_all(array(
			'C_MORE_THAN_ONE_MODULE_AVAILABLE' => $not_installed_modules_number > 1,
			'C_MODULE_AVAILABLE' => $not_installed_modules_number > 0,
			'MODULES_NUMBER' => $not_installed_modules_number
		));
	}

	private function get_modules_not_installed()
	{
		$modules_not_installed = array();
		$modules_folder = new Folder(PATH_TO_ROOT);
		foreach ($modules_folder->get_folders() as $folder)
		{
			$folder_name = $folder->get_name();
			if ($folder_name != 'lang' && !ModulesManager::is_module_installed($folder_name))
			{
				try
				{
					$modules_not_installed[$folder_name] = new Module($folder_name);
				}
				catch (IOException $ex)
				{
					continue;
				}
			}
		}

		usort($modules_not_installed, array(__CLASS__, 'callback_sort_modules_by_name'));

		return $modules_not_installed;
	}

	private static function callback_sort_modules_by_name(Module $module1, Module $module2)
	{
		if (TextHelper::strtolower($module1->get_configuration()->get_name()) > TextHelper::strtolower($module2->get_configuration()->get_name()))
		{
			return 1;
		}
		return -1;
	}

	private function install_module($module_id, $activate)
	{
		switch(ModulesManager::install_module($module_id, $activate, true))
		{
			case ModulesManager::CONFIG_CONFLICT:
				$this->view->put('MSG', MessageHelper::display($this->lang['modules.config_conflict'], MessageHelper::WARNING, 10));
				break;
			case ModulesManager::UNEXISTING_MODULE:
				$this->view->put('MSG', MessageHelper::display(LangLoader::get_message('element.unexist', 'status-messages-common'), MessageHelper::WARNING, 10));
				break;
			case ModulesManager::MODULE_ALREADY_INSTALLED:
				$this->view->put('MSG', MessageHelper::display($this->lang['modules.already_installed'], MessageHelper::WARNING, 10));
				break;
			case ModulesManager::PHP_VERSION_CONFLICT:
				$this->view->put('MSG', MessageHelper::display(LangLoader::get_message('misfit.php', 'status-messages-common'), MessageHelper::WARNING, 10));
				break;
			case ModulesManager::PHPBOOST_VERSION_CONFLICT:
				$this->view->put('MSG', MessageHelper::display(LangLoader::get_message('misfit.phpboost', 'status-messages-common'), MessageHelper::WARNING, 10));
				break;
			case ModulesManager::MODULE_INSTALLED:
			default:
				$this->view->put('MSG', MessageHelper::display(LangLoader::get_message('process.success', 'status-messages-common'), MessageHelper::SUCCESS, 10));
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
				if ($upload->file('upload_module_file', '`([a-z0-9()_-])+\.(gz|zip)+$`iu'))
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

					$valid_archive = true;
					$archive_root_content = array();
					$required_files = array('/config.ini');
					$forbidden_files = array('/body.tpl', '/frame.tpl', '/theme/content.css', '/theme/design.css', '/theme/global.css');
					foreach ($archive_content as $element)
					{
						if (TextHelper::substr($element['filename'], -1) == '/')
							$element['filename'] = TextHelper::substr($element['filename'], 0, -1);
						if (TextHelper::substr_count($element['filename'], '/') == 0)
							$archive_root_content[] = array('filename' => $element['filename'], 'folder' => ((isset($element['folder']) && $element['folder'] == 1) || (isset($element['typeflag']) && $element['typeflag'] == 5)));
						if (isset($archive_root_content[0]))
						{
							$name_in_archive = str_replace($archive_root_content[0]['filename'] . '/', '/', $element['filename']);

							if (in_array($name_in_archive, $required_files))
							{
								unset($required_files[array_search($name_in_archive, $required_files)]);
							}

							if (in_array($name_in_archive, $forbidden_files))
							{
								$valid_archive = false;
							}
						}
					}

					if (count($archive_root_content) == 1 && $archive_root_content[0]['folder'] && empty($required_files) && $valid_archive)
					{
						$module_id = $archive_root_content[0]['filename'];
						if (!ModulesManager::is_module_installed($module_id))
						{
							if ($upload->get_extension() == 'gz')
								PclTarExtract($upload->get_filename(), $modules_folder);
							else
								$zip->extract(PCLZIP_OPT_PATH, $modules_folder, PCLZIP_OPT_SET_CHMOD, 0755);

							$this->install_module($module_id, true);
						}
						else
						{
							$this->view->put('MSG', MessageHelper::display(LangLoader::get_message('element.already_exists', 'status-messages-common'), MessageHelper::NOTICE));
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
					$this->view->put('MSG', MessageHelper::display(LangLoader::get_message('upload.invalid_format', 'status-messages-common'), MessageHelper::NOTICE));
				}
			}
			else
			{
				$this->view->put('MSG', MessageHelper::display(LangLoader::get_message('upload.error', 'status-messages-common'), MessageHelper::NOTICE));
			}
		}
	}
}
?>
