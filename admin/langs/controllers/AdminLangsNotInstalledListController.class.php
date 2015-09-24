<?php
/*##################################################
 *                      AdminLangsNotInstalledListController.class.php
 *                            -------------------
 *   begin                : April 20, 2011
 *   copyright            : (C) 2011 Kevin MASSY
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

class AdminLangsNotInstalledListController extends AdminController
{
	private $lang;
	private $view;
	private $form;
	private $submit_button;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$this->save($request);
		$this->upload_form();
	
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->upload();
		}
		
		$this->build_view();
		
		$this->view->put('UPLOAD_FORM', $this->form->display());

		return new AdminLangsDisplayResponse($this->view, $this->lang['langs.add_lang']);
	}
	
	private function build_view()
	{
		$not_installed_langs = $this->get_not_installed_langs();
		foreach($not_installed_langs as $id)
		{
			try {
				$configuration = LangConfigurationManager::get($id);
				$this->view->assign_block_vars('langs_not_installed', array(
					'C_WEBSITE' => $configuration->get_author_link() !== '',
					'ID' => $id,
					'NAME' => $configuration->get_name(),
					'VERSION' => $configuration->get_version(),
					'AUTHOR_NAME' => $configuration->get_author_name(),
					'AUTHOR_WEBSITE' => $configuration->get_author_link(),
					'AUTHOR_EMAIL' => $configuration->get_author_mail(),
					'COMPATIBILITY' => $configuration->get_compatibility(),
					'AUTHORIZATIONS' => Authorizations::generate_select(Lang::ACCES_LANG, array('r-1' => 1, 'r0' => 1, 'r1' => 1), array(2 => true), $id)
				));
			} catch (IOException $e) {
			}
		}
		$this->view->put_all(array(
			'C_LANG_INSTALL' => count($not_installed_langs) > 0,
			'L_ADD' => $this->lang['langs.add_lang']
		));
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('admin-langs-common');
		$this->view = new FileTemplate('admin/langs/AdminLangsNotInstalledListController.tpl');
		$this->view->add_lang($this->lang);
	}
	
	private function get_not_installed_langs()
	{
		$langs_not_installed = array();
		$folder_containing_phpboost_langs = new Folder(PATH_TO_ROOT .'/lang/');
		foreach($folder_containing_phpboost_langs->get_folders() as $lang)
		{
			$name = $lang->get_name();
			if (!LangsManager::get_lang_existed($name))
			{
				$langs_not_installed[] = $name;
			}
		}
		sort($langs_not_installed);
		return $langs_not_installed;
	}
	
	private function save(HTTPRequestCustom $request)
	{
		foreach ($this->get_not_installed_langs() as $id)
		{
			try {
				if ($request->get_string('add-' . $id))
				{
					$activated = $request->get_bool('activated-' . $id, false);
					$authorizations = Authorizations::auth_array_simple(Lang::ACCES_LANG, $id);
					LangsManager::install($id, $authorizations, $activated);
					$error = LangsManager::get_error();
					if ($error !== null)
					{
						$this->view->put('MSG', MessageHelper::display($error, MessageHelper::NOTICE, 10));
					}
					else
					{
						$this->view->put('MSG', MessageHelper::display(LangLoader::get_message('process.success', 'status-messages-common'), MessageHelper::SUCCESS, 10));
					}
				}
			} catch (Exception $e) {
			}
		}
	}
	
	private function install_lang($id_lang, $authorizations = array(), $activate = true)
	{
		LangsManager::install($id_theme, $authorizations, $activate);
		$error = LangsManager::get_error();
		if ($error !== null)
		{
			$this->view->put('MSG', MessageHelper::display($error, MessageHelper::NOTICE, 10));
		}
		else
		{
			$this->view->put('MSG', MessageHelper::display(LangLoader::get_message('process.success', 'status-messages-common'), MessageHelper::SUCCESS, 10));
		}
	}
	
	private function upload_form()
	{
		$form = new HTMLForm('upload_lang', '', false);
		
		$fieldset = new FormFieldsetHTML('upload', $this->lang['langs.upload_lang']);
		$form->add_fieldset($fieldset);
	
		$fieldset->add_field(new FormFieldFilePicker('file', $this->lang['langs.upload_description']));
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);

		$this->form = $form;
	}
	
	private function upload()
	{
		$folder_phpboost_langs = PATH_TO_ROOT . '/lang/';
        if (!is_writable($folder_phpboost_langs))
		{
			$is_writable = @chmod($dir, 0777);
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
				$upload = new Upload($folder_phpboost_langs);
				if ($upload->file('upload_lang_file', '`([a-z0-9()_-])+\.(gz|zip)+$`i'))
				{
					$archive = $folder_phpboost_langs . $upload->get_filename();
					
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
					$required_files = array('/config.ini', '/admin-common.php', '/common.php');
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
						$lang_id = $archive_root_content[0]['filename'];
						if (!LangsManager::get_lang_existed($lang_id))
						{
							if ($upload->get_extension() == 'gz')
								PclTarExtract($upload->get_filename(), $folder_phpboost_langs);
							else
								$zip->extract(PCLZIP_OPT_PATH, $folder_phpboost_langs, PCLZIP_OPT_SET_CHMOD, 0755);
							
							$this->install_lang($lang_id, array('r-1' => 1, 'r0' => 1, 'r1' => 1));
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
					$this->view->put('MSG', MessageHelper::display($this->lang['langs.upload.invalid_format'], MessageHelper::NOTICE));
				}
			}
			else
			{
				$this->view->put('MSG', MessageHelper::display(LangLoader::get_message('process.error', 'status-messages-common'), MessageHelper::NOTICE));
			}
		}
	}
}
?>