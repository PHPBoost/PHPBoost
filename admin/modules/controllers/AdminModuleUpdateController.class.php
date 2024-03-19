<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2024 03 13
 * @since       PHPBoost 3.0 - 2011 09 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminModuleUpdateController extends DefaultAdminController
{
	protected function get_template_to_use()
	{
        return new FileTemplate('admin/modules/AdminModuleUpdateController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$message_success = $message_warning = '';
		$modules_selected = $modules_success = 0;
		$module_number = 1;
		foreach (ModulesManager::get_installed_modules_map() as $name => $module)
		{
			if ($request->get_string('upgrade-' . $module->get_id(), false) || ($request->get_string('upgrade-selected-modules', false) && $request->get_value('upgrade-checkbox-' . $module_number, 'off') == 'on'))
			{
				$modules_selected++;

				$result = $this->upgrade_module($module->get_id());

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

		return new AdminModulesDisplayResponse($this->view, $this->lang['addon.modules.update']);
	}

	private function upload_form()
	{
		$form = new HTMLForm('upload_module', '', false);

		$fieldset = new FormFieldsetHTML('upload', $this->lang['addon.modules.update']);
		$form->add_fieldset($fieldset);

		$fieldset->set_description(MessageHelper::display($this->lang['addon.modules.warning.update'], MessageHelper::NOTICE)->render());

        $fieldset->add_field(new FormFieldFilePicker('file', MessageHelper::display(StringVars::replace_vars($this->lang['addon.upload.clue'], array('max_size' => File::get_formated_size(ServerConfiguration::get_upload_max_filesize()), 'addon' => $this->lang['addon.modules.directory'])), MessageHelper::QUESTION)->render(),
			array('class' => 'full-field', 'authorized_extensions' => 'gz|zip')
		));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);

		$this->form = $form;
	}

	private function build_view()
	{
		$phpboost_version = GeneralConfig::load()->get_phpboost_major_version();
		$modules_upgradable = 0;
		$module_number = 1;
		foreach (ModulesManager::get_installed_modules_map_sorted_by_localized_name() as $module)
		{
			if (ModulesManager::module_is_upgradable($module->get_id()))
			{
				$configuration = $module->get_configuration();
				$author_email = $configuration->get_author_email();
				$author_website = $configuration->get_author_website();

				$this->view->assign_block_vars('modules_upgradable', array(
					'C_AUTHOR_EMAIL'       => !empty($author_email),
					'C_AUTHOR_WEBSITE'     => !empty($author_website),
                    'C_COMPATIBLE'         => $configuration->get_addon_type() == 'module' && $configuration->get_compatibility() == $phpboost_version,
                    'C_COMPATIBLE_ADDON'   => $configuration->get_addon_type() == 'module',
                    'C_COMPATIBLE_VERSION' => $configuration->get_compatibility() == $phpboost_version,

					'MODULE_NUMBER'    => $module_number,
					'MODULE_ID'               => $module->get_id(),
					'MODULE_NAME'             => TextHelper::ucfirst($configuration->get_name()),
					'CREATION_DATE'    => $configuration->get_creation_date(),
					'LAST_UPDATE'      => $configuration->get_last_update(),
					'VERSION'          => $configuration->get_version(),
					'AUTHOR'           => $configuration->get_author(),
					'AUTHOR_EMAIL'     => $author_email,
					'AUTHOR_WEBSITE'   => $author_website,
					'DESCRIPTION'      => $configuration->get_description(),
					'COMPATIBILITY'    => $configuration->get_compatibility(),
					'PHP_VERSION'      => $configuration->get_php_version()
				));

				$modules_upgradable++;
				$module_number++;
			}
		}

		$this->view->put_all(array(
			'C_SEVERAL_MODULES_AVAILABLE' => $modules_upgradable > 1,
			'C_UPDATES' => $modules_upgradable > 0,
			'MODULES_NUMBER' => $modules_upgradable
		));
	}

	private function upgrade_module($module_id)
	{
		switch (ModulesManager::upgrade_module($module_id))
		{
			case ModulesManager::UPGRADE_FAILED:
				return array('msg' => $this->lang['warning.process.error'], 'type' => MessageHelper::WARNING);
				break;
			case ModulesManager::MODULE_NOT_UPGRADABLE:
				return array('msg' => $this->lang['addon.modules.not.upgradable'], 'type' => MessageHelper::WARNING);
				break;
			case ModulesManager::NOT_INSTALLED_MODULE:
				return array('msg' => $this->lang['addon.modules.not.installed'], 'type' => MessageHelper::WARNING);
				break;
			case ModulesManager::UNEXISTING_MODULE:
				return array('msg' => $this->lang['warning.element.unexists'], 'type' => MessageHelper::WARNING);
				break;
			case ModulesManager::MODULE_UPDATED:
			default:
				ModulesManager::set_module_activation($module_id, true);
				$module = ModulesManager::get_module($module_id);
				HooksService::execute_hook_typed_action('update', 'module', $module_id, array_merge(array('title' => $module->get_configuration()->get_name(), 'url' => AdminModulesUrlBuilder::list_installed_modules()->rel()), $module->get_configuration()->get_properties()));
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
				if ($upload->file('upload_module_file', '`([A-Za-z0-9-_]+)\.(gz|zip)+$`iu', false, 100000000, false))
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
					$archive_root_content = array();
					$required_files = array('/config.ini');
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
						}
					}

					if ($archive_root_content[0]['folder'] && empty($required_files))
					{
						$module_id = $archive_root_content[0]['filename'];
						if (ModulesManager::is_module_installed($module_id))
						{
							if ($upload->get_extension() == 'gz')
								PclTarExtract($upload->get_filename(), $modules_folder);
							else
								$zip->extract(PCLZIP_OPT_PATH, $modules_folder, PCLZIP_OPT_SET_CHMOD, 0755);

							$result = $this->upgrade_module($module_id);

							if ($result['type'] == MessageHelper::SUCCESS)
								$this->view->put('MESSAGE_HELPER_SUCCESS', MessageHelper::display($result['msg'], MessageHelper::SUCCESS, 10));
							else
								$this->view->put('MESSAGE_HELPER_WARNING', MessageHelper::display($result['msg'], MessageHelper::WARNING));
						}
						else
						{
							$this->view->put('MESSAGE_HELPER_WARNING', MessageHelper::display($this->lang['addon.modules.not.installed'], MessageHelper::WARNING));
						}
					}
					else
					{
						$this->view->put('MESSAGE_HELPER_WARNING', MessageHelper::display($this->lang['warning.invalid_archive_content'], MessageHelper::WARNING));
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
