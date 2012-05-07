<?php
/*##################################################
 *                      AdminThemesNotInstalledListController.class.php
 *                            -------------------
 *   begin                : April 20, 2011
 *   copyright            : (C) 2011 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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
	
	public function execute(HTTPRequest $request)
	{
		$this->init();
		
		$this->upload_form();
		
		$this->build_view();
		$this->save($request);
		
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->upload_theme();
		}
		
		$this->view->put('UPLOAD_FORM', $this->form->display());

		return new AdminThemesDisplayResponse($this->view, $this->lang['themes.add']);
	}
	
	private function build_view()
	{
		$not_installed_themes = $this->get_not_installed_themes();
		foreach($not_installed_themes as $name)
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
			}
		}
		$this->view->put_all(array(
			'C_THEME_INSTALL' => count($not_installed_themes) > 0 ? true : false,
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
			if ($name !== 'default' && !ThemeManager::get_theme_existed($name))
			{
				$themes_not_installed[] = $name;
			}
		}
		sort($themes_not_installed);
		return $themes_not_installed;
	}
	
	private function save(HTTPRequest $request)
	{
		foreach ($this->get_not_installed_themes() as $id_theme)
		{
			try {
				if ($request->get_string('add-' . $id_theme))
				{
					$activated = $request->get_bool('activated-' . $id_theme, false);
					$authorizations = Authorizations::auth_array_simple(Theme::ACCES_THEME, $id_theme);
					ThemeManager::install($id_theme, $authorizations, $activated);
					$error = ThemeManager::get_error();
					if ($error !== null)
					{
						$this->view->put('MSG', MessageHelper::display($error, MessageHelper::NOTICE, 10));
					}
					else
					{
						AppContext::get_response()->redirect(AdminThemeUrlBuilder::list_installed_theme());
					}
				}
			} catch (Exception $e) {
			}
		}
	}
	
	private function upload_form()
	{
		$form = new HTMLForm('upload_theme');
		
		$fieldset = new FormFieldsetHTML('upload', $this->lang['themes.upload']);
		$form->add_fieldset($fieldset);
	
		$fieldset->add_field(new FormFieldFilePicker('file', $this->lang['themes.upload.description']));
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);

		$this->form = $form;
	}
	
	private function upload_theme()
	{
		$folder_phpboost_themes = PATH_TO_ROOT . '/templates/';

        if (!is_writable($folder_phpboost_themes))
		{
			$is_writable = @chmod($dir, 0777);
		}
		else
		{
			$is_writable = true;
		}
        
		if ($is_writable)
		{
			$file = $this->form->get_value('file');
			if ($file !== null)
			{
				if (!ThemeManager::get_theme_existed($file->get_name_without_extension()))
				{
					$upload = new Upload($folder_phpboost_themes);
					if ($upload->file('upload_theme_file', '`([a-z0-9()_-])+\.(gzip|zip)+$`i'))
					{
						$archive = $folder_phpboost_themes . $upload->get_filename();
						
						if ($upload->get_extension() == 'gzip')
						{
							import('php/pcl/pcltar', LIB_IMPORT);
							PclTarExtract($upload->get_filename(), $folder_phpboost_themes);
							
							$file = new File($archive);
							$file->delete();
						}
						else if ($upload->get_extension() == 'zip')
						{
							import('php/pcl/pclzip', LIB_IMPORT);
							$zip = new PclZip($archive);
							$zip->extract(PCLZIP_OPT_PATH, $folder_phpboost_themes, PCLZIP_OPT_SET_CHMOD, 0755);
							
							$file = new File($archive);
							$file->delete();
						}
						else
						{
							$this->view->put('MSG', MessageHelper::display($this->lang['themes.upload.invalid_format'], MessageHelper::NOTICE, 4));
						}
						
						ThemeManager::install($file->get_name_without_extension());
						AppContext::get_response()->redirect(AdminThemeUrlBuilder::list_installed_theme());
					}
				}
				else
				{
					$this->view->put('MSG', MessageHelper::display($this->lang['themes.already_exist'], MessageHelper::NOTICE, 4));
				}
			}
			else
			{
				$this->view->put('MSG', MessageHelper::display(LangLoader::get_message('process.error', 'errors-common'), MessageHelper::NOTICE, 4));
			}
		}
	}
}
?>