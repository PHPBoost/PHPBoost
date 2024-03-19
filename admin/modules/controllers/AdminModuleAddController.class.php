<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2024 03 12
 * @since       PHPBoost 3.0 - 2011 09 20
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor janus57 <janus57@janus57.fr>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminModuleAddController extends DefaultAdminController
{
	protected function get_template_to_use()
	{
        return new FileTemplate('admin/modules/AdminModuleAddController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$message_success = $message_warning = '';
		$modules_selected = $modules_success = 0;
		$module_number = 1;
		foreach ($this->get_modules_not_installed() as $name => $module)
		{
			if ($request->get_string('add-' . $module->get_id(), false) || ($request->get_string('add-selected-modules', false) && $request->get_value('add-checkbox-' . $module_number, 'off') == 'on'))
			{
				$modules_selected++;

				$result = $this->install_module($module->get_id());

				if ($result['type'] == MessageHelper::SUCCESS)
				{
					$modules_success++;
					$message_success .= '<b>' . $module->get_configuration()->get_name() . '</b> : ' . $result['msg'] . '<br />';
				}
				else
					$message_warning .= '<b>' . $module->get_configuration()->get_name() . '</b> : ' . $result['msg'] . '<br />';
			}

			$module_number++;
		}

		if ($modules_selected > 0 && $modules_selected == $modules_success)
			$this->view->put('MESSAGE_HELPER_SUCCESS', MessageHelper::display($this->lang['warning.process.success'], MessageHelper::SUCCESS, 10));
		else
		{
			if ($message_warning)
				$this->view->put('MESSAGE_HELPER_WARNING', MessageHelper::display($message_warning, MessageHelper::WARNING, -1));
			if ($message_success)
				$this->view->put('MESSAGE_HELPER_SUCCESS', MessageHelper::display($message_success, MessageHelper::SUCCESS, 10));
		}

		$this->upload_form();

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->upload_module();
		}

		$this->build_view();

		$this->view->put('CONTENT', $this->form->display());

		return new AdminModulesDisplayResponse($this->view, $this->lang['addon.modules.add']);
	}

	private function upload_form()
	{
		$form = new HTMLForm('upload_module', '', false);

		$fieldset = new FormFieldsetHTML('upload', $this->lang['addon.modules.upload']);
		$form->add_fieldset($fieldset);

		$fieldset->set_description(MessageHelper::display($this->lang['addon.modules.warning.install'], MessageHelper::NOTICE)->render());

		$fieldset->add_field(new FormFieldFilePicker('file', MessageHelper::display(StringVars::replace_vars($this->lang['addon.upload.clue'], array('max_size' => File::get_formated_size(ServerConfiguration::get_upload_max_filesize()), 'addon'=> $this->lang['addon.modules.directory'])), MessageHelper::QUESTION)->render(),
			array('class' => 'full-field', 'authorized_extensions' => 'gz|zip')
		));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);

		$this->form = $form;
	}

	private function build_view()
	{
		$phpboost_version = GeneralConfig::load()->get_phpboost_major_version();
		$modules_not_installed = $this->get_modules_not_installed();
		$module_number = 1;
		foreach ($modules_not_installed as $id => $module)
		{
			$configuration = $module->get_configuration();
			$author_email = $configuration->get_author_email();
			$author_website = $configuration->get_author_website();

			$fa_icon = $configuration->get_fa_icon();
			$hexa_icon = $configuration->get_hexa_icon();
			$thumbnail = new File(PATH_TO_ROOT . '/' . $module->get_id() . '/' . $module->get_id() . '.png');

			$this->view->assign_block_vars('modules_not_installed', array(
				'C_THUMBNAIL'          => $thumbnail->exists(),
				'C_FA_ICON' 	       => !empty($fa_icon),
				'C_HEXA_ICON' 	       => !empty($hexa_icon),
				'C_AUTHOR_EMAIL'       => !empty($author_email),
				'C_AUTHOR_WEBSITE'     => !empty($author_website),
				'C_COMPATIBLE'         => $configuration->get_addon_type() == 'module' && $configuration->get_compatibility() == $phpboost_version,
				'C_COMPATIBLE_ADDON'   => $configuration->get_addon_type() == 'module',
				'C_COMPATIBLE_VERSION' => $configuration->get_compatibility() == $phpboost_version,

				'MODULE_NUMBER'  => $module_number,
				'MODULE_ID'      => $module->get_id(),
				'MODULE_NAME'           => TextHelper::ucfirst($configuration->get_name()),
				'CREATION_DATE'  => $configuration->get_creation_date(),
				'LAST_UPDATE'    => $configuration->get_last_update(),
				'VERSION'        => $configuration->get_version(),
				'AUTHOR'         => $configuration->get_author(),
				'AUTHOR_EMAIL'   => $author_email,
				'AUTHOR_WEBSITE' => $author_website,
				'DESCRIPTION'    => $configuration->get_description(),
				'COMPATIBILITY'  => $configuration->get_compatibility(),
				'PHP_VERSION'    => $configuration->get_php_version(),
				'FA_ICON' 		 => $fa_icon,
				'HEXA_ICON' 	 => $hexa_icon,
			));
			$module_number++;
		}

		$not_installed_modules_number = count($modules_not_installed);
		$this->view->put_all(array(
			'C_SEVERAL_MODULES_AVAILABLE' => $not_installed_modules_number > 1,
			'C_MODULE_AVAILABLE'          => $not_installed_modules_number > 0,

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
			if ($folder->get_files('/config\.ini/') && !ModulesManager::is_module_installed($folder_name))
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

	private function install_module($module_id)
	{
		switch(ModulesManager::install_module($module_id))
		{
			case ModulesManager::CONFIG_CONFLICT:
				return array('msg' => $this->lang['addon.modules.config.conflict'], 'type' => MessageHelper::WARNING);
				break;
			case ModulesManager::UNEXISTING_MODULE:
				return array('msg' => $this->lang['warning.element.unexists'], 'type' => MessageHelper::WARNING);
				break;
			case ModulesManager::MODULE_ALREADY_INSTALLED:
				return array('msg' => $this->lang['addon.modules.already.installed'], 'type' => MessageHelper::WARNING);
				break;
			case ModulesManager::PHP_VERSION_CONFLICT:
				return array('msg' => $this->lang['warning.misfit.php'], 'type' => MessageHelper::WARNING);
				break;
			case ModulesManager::PHPBOOST_VERSION_CONFLICT:
				return array('msg' => $this->lang['warning.misfit.phpboost'], 'type' => MessageHelper::WARNING);
				break;
			case ModulesManager::MODULE_INSTALLED:
			default:
				$module = ModulesManager::get_module($module_id);
				HooksService::execute_hook_typed_action('install', 'module', $module_id, array_merge(array('title' => $module->get_configuration()->get_name(), 'url' => AdminModulesUrlBuilder::list_installed_modules()->rel()), $module->get_configuration()->get_properties()));
				return array('msg' => $this->lang['warning.process.success'], 'type' => MessageHelper::SUCCESS);
		}
	}

	private function upload_module()
	{
		$modules_folder = PATH_TO_ROOT . '/';
		if (!is_writable($modules_folder))
		{
			$is_writable = @chmod($modules_folder, 0755);
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

					$module_name = TextHelper::substr($upload->get_filename(), 0, TextHelper::strpos($upload->get_filename(), '.'));
					$valid_archive = true;
					$archive_root_content = array();
					$required_files = array('/config.ini');
					$forbidden_files = array('theme/@import.css', 'admin-lang.php');
					foreach ($archive_content as $element)
					{
						if (TextHelper::strpos($element['filename'], $module_name) === 0)
						{
							$element['filename'] = str_replace($module_name . '/', '', $element['filename']);
							$archive_root_content[0] = array('filename' => $module_name, 'folder' => 1);
						}
						if (TextHelper::substr($element['filename'], -1) == '/')
							$element['filename'] = TextHelper::substr($element['filename'], 0, -1);
						if (TextHelper::substr_count($element['filename'], '/') == 0)
							$archive_root_content[] = array('filename' => $element['filename'], 'folder' => ((isset($element['folder']) && $element['folder'] == 1) || (isset($element['typeflag']) && $element['typeflag'] == 5)));
						if (isset($archive_root_content[0]))
						{
							$name_in_archive = str_replace($archive_root_content[0]['filename'] . '/', '/', $element['filename']);

							if (in_array($name_in_archive, $required_files))
								unset($required_files[array_search($name_in_archive, $required_files)]);
							else if (in_array('/' . $name_in_archive, $required_files))
								unset($required_files[array_search('/' . $name_in_archive, $required_files)]);

							if (in_array($name_in_archive, $forbidden_files) || in_array('/' . $name_in_archive, $forbidden_files))
							{
								$valid_archive = false;
							}
						}
					}

					if ($archive_root_content[0]['folder'] && empty($required_files) && $valid_archive)
					{
						$module_id = $archive_root_content[0]['filename'];
						if (!ModulesManager::is_module_installed($module_id))
						{
							if ($upload->get_extension() == 'gz')
								PclTarExtract($upload->get_filename(), $modules_folder);
							else
								$zip->extract(PCLZIP_OPT_PATH, $modules_folder, PCLZIP_OPT_SET_CHMOD, 0755);

							$result = $this->install_module($module_id);

							if ($result['type'] == MessageHelper::SUCCESS)
								$this->view->put('MESSAGE_HELPER_SUCCESS', MessageHelper::display($result['msg'], MessageHelper::SUCCESS, 10));
							else
								$this->view->put('MESSAGE_HELPER_WARNING', MessageHelper::display($result['msg'], MessageHelper::WARNING));
						}
						else
						{
							$this->view->put('MESSAGE_HELPER_WARNING', MessageHelper::display($this->lang['warning.element.already.exists'], MessageHelper::WARNING));
						}
					}
					else
					{
						$this->view->put('MESSAGE_HELPER_WARNING', MessageHelper::display($this->lang['warning.invalid.archive.content'], MessageHelper::WARNING));
					}

					$uploaded_file = new File($archive);
					$uploaded_file->delete();
				}
				else
				{
					$this->view->put('MESSAGE_HELPER_WARNING', MessageHelper::display($this->lang['warning.file.invalid.format'], MessageHelper::WARNING));
				}
			}
			else
			{
				$this->view->put('MESSAGE_HELPER_WARNING', MessageHelper::display($this->lang['warning.file.upload.error'], MessageHelper::WARNING));
			}
		}
	}
}
?>
