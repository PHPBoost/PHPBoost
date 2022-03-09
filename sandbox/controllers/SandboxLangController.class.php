<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 03 09
 * @since       PHPBoost 6.0 - 2021 11 27
*/

class SandboxLangController extends DefaultModuleController
{
	protected function get_template_to_use()
	{
		return new FileTemplate('sandbox/SandboxLangController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->build_view();

		return $this->generate_response();
	}

	private function build_view()
	{
		$this->view->put_all(array(
			'SANDBOX_SUBMENU' => SandboxSubMenu::get_submenu(),
		));
		$this->build_general_lang_vars();
		$this->build_modules_lang_vars();
	}

	public function build_general_lang_vars()
	{
		$lang_directory = new Folder(PATH_TO_ROOT . '/lang/' . AppContext::get_current_user()->get_locale());
		$files = $lang_directory->get_files();

		foreach($files as $file)
		{
			$filename = $file->get_name_without_extension();
			if (!in_array($filename, array('config')))
			{
				$this->view->assign_block_vars('lang_file', array(
					'LANG_FILE_ID' => str_replace('.', '-', $filename),
					'LANG_FILE_NAME' => $filename,
				));
				foreach(LangLoader::get($filename) as $var => $desc)
				{
					if(!is_array($desc))
					$this->view->assign_block_vars('lang_file.items', array(
						'VAR' => $var,
						'DESC' => $desc
					));
					else {
						foreach($desc as $sub_var => $sub_desc)
						{
							if(!is_array($sub_desc))
								$this->view->assign_block_vars('lang_file.items', array(
									'VAR' => $sub_var,
									'DESC' => $sub_desc
								));
							else
							{
								foreach($sub_desc as $sub_sub_var => $sub_sub_desc)
								{
									$this->view->assign_block_vars('lang_file.items', array(
										'VAR' => $sub_sub_var,
										'DESC' => $sub_sub_desc
									));
								}
							}
						}
					}
				}
			}
		}
	}

	public function build_modules_lang_vars()
	{
		$modules_list = ModulesManager::get_installed_modules_map_sorted_by_localized_name();
		foreach($modules_list as $module)
		{
			$module_id = $module->get_id();
			$module_directory = new Folder(PATH_TO_ROOT . '/' . $module_id . '/lang/' . AppContext::get_current_user()->get_locale());
			$files = $module_directory->get_files();

			if (count($files) > 1)
			{
				$this->view->assign_block_vars('module', array(
					'MODULE_NAME' => $module->get_configuration()->get_name(),
					'MODULE_ID' => $module_id
				));
				foreach($files as $file)
				{
					$filename = $file->get_name_without_extension();
					if (!in_array($filename, array('desc', 'install')))
					{
						$this->view->assign_block_vars('module.module_file', array(
							'MODULE_FILE_ID' => str_replace('.', '-', $filename),
							'MODULE_FILE_NAME' => $filename,
						));
						foreach(LangLoader::get($filename, $module_id) as $var => $desc)
						{
							if(!is_array($desc))
								$this->view->assign_block_vars('module.module_file.items', array(
									'VAR' => $var,
									'DESC' => $desc
								));
							else
							{
								foreach($desc as $sub_var => $sub_desc)
								{
									if(!is_array($sub_desc))
										$this->view->assign_block_vars('module.module_file.items', array(
											'VAR' => $sub_var,
											'DESC' => $sub_desc
										));
									else
									{
										foreach($sub_desc as $sub_sub_var => $sub_sub_desc)
										{
											$this->view->assign_block_vars('module.module_file.items', array(
												'VAR' => $sub_sub_var,
												'DESC' => $sub_sub_desc
											));
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}

	private function check_authorizations()
	{
		if (!SandboxAuthorizationsService::check_authorizations()->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['sandbox.lang'], $this->lang['sandbox.module.title']);

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['sandbox.module.title'], SandboxUrlBuilder::home()->rel());
		$breadcrumb->add($this->lang['sandbox.lang'], SandboxUrlBuilder::lang()->rel());

		return $response;
	}
}
?>
