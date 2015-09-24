<?php
/*##################################################
 *                      AdminThemesNotInstalledListController.class.php
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

class AdminThemesNotInstalledListController extends AdminController
{
	private $lang;
	private $view;
	private $form;
	private $submit_button;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		foreach ($this->get_not_installed_themes() as $id_theme)
		{
			try {
				if ($request->get_string('add-' . $id_theme))
				{
					$activated = $request->get_bool('activated-' . $id_theme, false);
					$authorizations = Authorizations::auth_array_simple(Theme::ACCES_THEME, $id_theme);
					$this->install_theme($id_theme, $authorizations, $activated);
				}
			} catch (UnexistingHTTPParameterException $e) {
			}
		}
		
		$this->upload_form();
		
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->upload_theme();
		}
		
		$this->build_view();
		
		$this->view->put('UPLOAD_FORM', $this->form->display());

		return new AdminThemesDisplayResponse($this->view, $this->lang['themes.add_theme']);
	}
	
	private function build_view()
	{
		$not_installed_themes = $this->get_not_installed_themes();
		foreach($not_installed_themes as $key => $name)
		{
			try {
				$configuration = ThemeConfigurationManager::get($name);
				$pictures = $configuration->get_pictures();
				$id_theme = $name;
				
				$this->view->assign_block_vars('themes_not_installed', array(
					'C_WEBSITE' => $configuration->get_author_link() !== '',
					'C_PICTURES' => count($pictures) > 0,
					'ID' => $id_theme,
					'NAME' => $configuration->get_name(),
					'VERSION' => $configuration->get_version(),
					'MAIN_PICTURE' => count($pictures) > 0 ? Url::to_rel('/templates/' . $id_theme . '/' . current($pictures)) : '',
					'AUTHOR_NAME' => $configuration->get_author_name(),
					'AUTHOR_WEBSITE' => $configuration->get_author_link(),
					'AUTHOR_EMAIL' => $configuration->get_author_mail(),
					'DESCRIPTION' => $configuration->get_description() !== '' ? $configuration->get_description() : $this->lang['themes.bot_informed'],
					'COMPATIBILITY' => $configuration->get_compatibility(),
					'AUTHORIZATIONS' => Authorizations::generate_select(Theme::ACCES_THEME, array('r-1' => 1, 'r0' => 1, 'r1' => 1), array(2 => true), $id_theme),
					'HTML_VERSION' => $configuration->get_html_version() !== '' ? $configuration->get_html_version() : $this->lang['themes.bot_informed'],
					'CSS_VERSION' => $configuration->get_css_version() !== '' ? $configuration->get_css_version() : $this->lang['themes.bot_informed'],
					'MAIN_COLOR' => $configuration->get_main_color() !== '' ? $configuration->get_main_color() : $this->lang['themes.bot_informed'],
					'WIDTH' => $configuration->get_variable_width() ? $this->lang['themes.variable-width'] : $configuration->get_width(),
				));
				
				if (count($pictures) > 0)
				{
					unset($pictures[0]);
					foreach ($pictures as $picture)
					{
						$this->view->assign_block_vars('themes_not_installed.pictures', array(
							'URL' => Url::to_rel('/templates/' . $id_theme . '/' . $picture)
						));
					}
				}
			} catch (IOException $e) {
				unset($not_installed_themes[$key]);
			}
		}
		$this->view->put_all(array(
			'C_THEME_INSTALL' => count($not_installed_themes) > 0,
			'L_ADD' => $this->lang['themes.add_theme']
		));
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('admin-themes-common');
		$this->view = new FileTemplate('admin/themes/AdminThemesNotInstalledListController.tpl');
		$this->view->add_lang($this->lang);
	}
	
	private function get_not_installed_themes()
	{
		$themes_not_installed = array();
		$folder_containing_phpboost_themes = new Folder(PATH_TO_ROOT .'/templates/');
		foreach($folder_containing_phpboost_themes->get_folders() as $theme)
		{
			$name = $theme->get_name();
			if ($name !== 'default' && !ThemesManager::get_theme_existed($name))
			{
				$themes_not_installed[] = $name;
			}
		}
		sort($themes_not_installed);
		return $themes_not_installed;
	}

	private function install_theme($id_theme, $authorizations = array(), $activate = true)
	{
		ThemesManager::install($id_theme, $authorizations, $activate);
		$error = ThemesManager::get_error();
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
		$form = new HTMLForm('upload_theme', '', false);
		
		$fieldset = new FormFieldsetHTML('upload', $this->lang['themes.upload_theme']);
		$form->add_fieldset($fieldset);
	
		$fieldset->add_field(new FormFieldFilePicker('file', $this->lang['themes.upload_description']));
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);

		$this->form = $form;
	}
	
	private function upload_theme()
	{
		$folder_phpboost_themes = PATH_TO_ROOT . '/templates/';
		
		if (!is_writable($folder_phpboost_themes))
		{
			$is_writable = @chmod($folder_phpboost_themes, 0777);
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
				$upload = new Upload($folder_phpboost_themes);
				if ($upload->file('upload_theme_file', '`([A-Za-z0-9-_]+)\.(gz|zip)+$`i'))
				{
					$archive = $folder_phpboost_themes . $upload->get_filename();
					
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
					$required_files = array('/config.ini', '/body.tpl', '/frame.tpl', '/theme/content.css', '/theme/design.css', '/theme/global.css');
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
						$theme_id = $archive_root_content[0]['filename'];
						if (!ThemesManager::get_theme_existed($theme_id))
						{
							if ($upload->get_extension() == 'gz')
								PclTarExtract($upload->get_filename(), $folder_phpboost_themes);
							else
								$zip->extract(PCLZIP_OPT_PATH, $folder_phpboost_themes, PCLZIP_OPT_SET_CHMOD, 0755);
							
							$this->install_theme($theme_id, array('r-1' => 1, 'r0' => 1, 'r1' => 1));
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
					$this->view->put('MSG', MessageHelper::display($this->lang['themes.upload_invalid_format'], MessageHelper::NOTICE));
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