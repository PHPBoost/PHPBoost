<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 03 29
 * @since       PHPBoost 3.0 - 2011 04 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminThemesNotInstalledListController extends DefaultAdminController
{
	protected function get_template_to_use()
	{
	   return new FileTemplate('admin/themes/AdminThemesNotInstalledListController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$theme_number = 1;
		foreach ($this->get_not_installed_themes() as $theme)
		{
			if ($request->get_string('add-' . $theme->get_id(), false) || ($request->get_string('add-selected-themes', false) && $request->get_value('add-checkbox-' . $theme_number, 'off') == 'on'))
			{
				$authorizations = Authorizations::auth_array_simple(Theme::ACCES_THEME, $theme->get_id());
				$this->install_theme($theme->get_id(), $authorizations);
			}
			$theme_number++;
		}

		$this->upload_form();

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->upload_theme();
		}

		$this->build_view();

		$this->view->put('UPLOAD_FORM', $this->form->display());

		return new AdminThemesDisplayResponse($this->view, $this->lang['addon.themes.add']);
	}

	private function build_view()
	{
		$phpboost_version = GeneralConfig::load()->get_phpboost_major_version();
		$not_installed_themes = $this->get_not_installed_themes();
		$theme_number = 1;
		foreach($not_installed_themes as $theme)
		{
			$configuration = $theme->get_configuration();
			$theme_has_parent = $configuration->get_parent_theme() != '' && $configuration->get_parent_theme() != '__default__';

			$author_email = $configuration->get_author_mail();
			$author_website = $configuration->get_author_link();
			$pictures = $configuration->get_pictures();
			$this->view->assign_block_vars('themes_not_installed', array(
				'C_AUTHOR_EMAIL'   => !empty($author_email),
				'C_AUTHOR_WEBSITE' => !empty($author_website),
				'C_COMPATIBLE'     => $configuration->get_compatibility() == $phpboost_version && ($theme_has_parent ? ThemesManager::get_theme_existed($configuration->get_parent_theme()) : true),
				'C_VERSION_COMPAT' => $configuration->get_compatibility() == $phpboost_version,
				'C_PARENT_THEME'   => $theme_has_parent,
				'C_PARENT_COMPAT'  => $theme_has_parent ? ThemesManager::get_theme_existed($configuration->get_parent_theme()) : true,
				'C_THUMBNAIL'      => count($pictures) > 0,

				'THEME_NUMBER'   => $theme_number,
				'MODULE_ID'      => $theme->get_id(),
				'MODULE_NAME'    => $configuration->get_name(),
				'CREATION_DATE'  => $configuration->get_creation_date(),
				'LAST_UPDATE'    => $configuration->get_last_update(),
				'VERSION'        => $configuration->get_version(),
				'AUTHOR'         => $configuration->get_author_name(),
				'AUTHOR_EMAIL'   => $author_email,
				'AUTHOR_WEBSITE' => $author_website,
				'DESCRIPTION'    => $configuration->get_description() !== '' ? $configuration->get_description() : $this->lang['common.unspecified'],
				'COMPATIBILITY'  => $configuration->get_compatibility(),
				'AUTHORIZATIONS' => Authorizations::generate_select(Theme::ACCES_THEME, array('r-1' => 1, 'r0' => 1, 'r1' => 1), array(2 => true), $theme->get_id()),
				'HTML_VERSION'   => $configuration->get_html_version() !== '' ? $configuration->get_html_version() : $this->lang['common.unspecified'],
				'CSS_VERSION'    => $configuration->get_css_version() !== '' ? $configuration->get_css_version() : $this->lang['common.unspecified'],
				'MAIN_COLOR'     => $configuration->get_main_color() !== '' ? $configuration->get_main_color() : $this->lang['common.unspecified'],
				'WIDTH'          => $configuration->get_variable_width() ? $this->lang['addon.themes.variable.width'] : $configuration->get_width(),
				'PARENT_THEME'   => $theme_has_parent ? (ThemesManager::get_theme_existed($configuration->get_parent_theme()) ? ThemesManager::get_theme($configuration->get_parent_theme())->get_configuration()->get_name() : $configuration->get_parent_theme()) : '',

				'U_MAIN_THUMBNAIL' => count($pictures) > 0 ? Url::to_rel('/templates/' . $theme->get_id() . '/' . current($pictures)) : '',
				'L_PARENT_COMPAT'  => StringVars::replace_vars($this->lang['addon.themes.parent.theme.not.installed'], array('id_parent' => $configuration->get_parent_theme())),
			));

			if (count($pictures) > 0)
			{
				unset($pictures[0]);
				foreach ($pictures as $picture)
				{
					$url = '/templates/' . (!preg_match('/\/default\//', $picture) ? $theme->get_id() . '/' : '') . $picture;
					$this->view->assign_block_vars('themes_not_installed.pictures', array(
						'URL' => Url::to_rel($url)
					));
				}
			}
			$theme_number++;
		}

		$not_installed_themes_number = count($not_installed_themes);
		$this->view->put_all(array(
			'C_SEVERAL_THEMES_AVAILABLE' => $not_installed_themes_number > 1,
			'C_THEME_AVAILABLE'          => $not_installed_themes_number > 0,

			'THEMES_NUMBER' => $not_installed_themes_number
		));
	}

	private function get_not_installed_themes()
	{
		$themes_not_installed = array();
		$folder_containing_phpboost_themes = new Folder(PATH_TO_ROOT .'/templates/');
		foreach ($folder_containing_phpboost_themes->get_folders() as $folder)
		{
			$folder_name = $folder->get_name();
			if ($folder_name != '__default__' && $folder->get_files('/config\.ini/') && !ThemesManager::get_theme_existed($folder_name))
			{
				try
				{
					$themes_not_installed[$folder_name] = new Theme($folder_name);
				}
				catch (IOException $ex)
				{
					continue;
				}
			}
		}

		usort($themes_not_installed, array(__CLASS__, 'callback_sort_themes_by_name'));

		return $themes_not_installed;
	}

	private static function callback_sort_themes_by_name(Theme $theme1, Theme $theme2)
	{
		if (TextHelper::strtolower($theme1->get_configuration()->get_name()) > TextHelper::strtolower($theme2->get_configuration()->get_name()))
		{
			return 1;
		}
		return -1;
	}

	private function install_theme($id_theme, $authorizations = array())
	{
		ThemesManager::install($id_theme, $authorizations);
		$error = ThemesManager::get_error();
		if ($error !== null)
		{
			$this->view->put('MESSAGE_HELPER', MessageHelper::display($error, MessageHelper::WARNING));
		}
		else
		{
			$theme = ThemesManager::get_theme($id_theme);
			HooksService::execute_hook_typed_action('install', 'theme', $id_theme, array_merge(array('title' => $theme->get_configuration()->get_name(), 'url' => AdminThemeUrlBuilder::list_installed_theme()->rel()), $theme->get_configuration()->get_properties()));
			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.process.success'], MessageHelper::SUCCESS, 10));
		}
	}

	private function upload_form()
	{
		$form = new HTMLForm('upload_theme', '', false);

		$fieldset = new FormFieldsetHTML('upload', $this->lang['addon.themes.upload']);
		$form->add_fieldset($fieldset);

		$fieldset->set_description(MessageHelper::display($this->lang['addon.themes.warning.install'], MessageHelper::NOTICE)->render());

		$fieldset->add_field(new FormFieldFilePicker('file', MessageHelper::display(StringVars::replace_vars($this->lang['addon.upload.clue'], array('max_size' => File::get_formated_size(ServerConfiguration::get_upload_max_filesize()), 'addon' => $this->lang['addon.themes.directory'])), MessageHelper::QUESTION)->render(),
			array('class' => 'full-field', 'authorized_extensions' => 'gz|zip')
		));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);

		$this->form = $form;
	}

	private function upload_theme()
	{
		$folder_phpboost_themes = PATH_TO_ROOT . '/templates/';

		if (!is_writable($folder_phpboost_themes))
		{
			$is_writable = @chmod($folder_phpboost_themes, 0755);
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
				if ($upload->file('upload_theme_file', '`([A-Za-z0-9-_]+)\.(gz|zip)+$`iu'))
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

					$theme_name = TextHelper::substr($upload->get_filename(), 0, TextHelper::strpos($upload->get_filename(), '.'));
					$archive_root_content = array();
					$required_files = array('/config.ini', '/body.tpl', '/frame.tpl');
					foreach ($archive_content as $element)
					{
						if (TextHelper::strpos($element['filename'], $theme_name) === 0)
						{
							$element['filename'] = str_replace($theme_name . '/', '', $element['filename']);
							$archive_root_content[0] = array('filename' => $theme_name, 'folder' => 1);
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
							$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.element.already.exists'], MessageHelper::WARNING));
						}
					}
					else
					{
						$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.invalid.archive.content'], MessageHelper::WARNING));
					}

					$uploaded_file = new File($archive);
					$uploaded_file->delete();
				}
				else
				{
					$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.file.upload.invalid.format'], MessageHelper::WARNING));
				}
			}
			else
			{
				$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.file.upload.error'], MessageHelper::WARNING));
			}
		}
	}
}
?>
