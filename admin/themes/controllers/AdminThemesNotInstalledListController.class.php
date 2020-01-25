<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 25
 * @since       PHPBoost 3.0 - 2011 04 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminThemesNotInstalledListController extends AdminController
{
	private $lang;
	private $view;
	private $form;
	private $submit_button;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

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

		return new AdminThemesDisplayResponse($this->view, $this->lang['themes.add_theme']);
	}

	private function build_view()
	{
		$phpboost_version = GeneralConfig::load()->get_phpboost_major_version();
		$not_installed_themes = $this->get_not_installed_themes();
		$theme_number = 1;
		foreach($not_installed_themes as $theme)
		{
			$configuration = $theme->get_configuration();
			$author_email = $configuration->get_author_mail();
			$author_website = $configuration->get_author_link();
			$pictures = $configuration->get_pictures();

			$this->view->assign_block_vars('themes_not_installed', array(
				'C_AUTHOR_EMAIL'   => !empty($author_email),
				'C_AUTHOR_WEBSITE' => !empty($author_website),
				'C_COMPATIBLE'     => $configuration->get_compatibility() == $phpboost_version,
				'C_PICTURES'       => count($pictures) > 0,
				'THEME_NUMBER'     => $theme_number,
				'ID'               => $theme->get_id(),
				'NAME'             => $configuration->get_name(),
				'CREATION_DATE'    => $configuration->get_creation_date(),
				'LAST_UPDATE'      => $configuration->get_last_update(),
				'VERSION'          => $configuration->get_version(),
				'MAIN_PICTURE'     => count($pictures) > 0 ? Url::to_rel('/templates/' . $theme->get_id() . '/' . current($pictures)) : '',
				'AUTHOR'           => $configuration->get_author_name(),
				'AUTHOR_EMAIL'     => $author_email,
				'AUTHOR_WEBSITE'   => $author_website,
				'DESCRIPTION'      => $configuration->get_description() !== '' ? $configuration->get_description() : $this->lang['themes.bot_informed'],
				'COMPATIBILITY'    => $configuration->get_compatibility(),
				'AUTHORIZATIONS'   => Authorizations::generate_select(Theme::ACCES_THEME, array('r-1' => 1, 'r0' => 1, 'r1' => 1), array(2 => true), $theme->get_id()),
				'HTML_VERSION'     => $configuration->get_html_version() !== '' ? $configuration->get_html_version() : $this->lang['themes.bot_informed'],
				'CSS_VERSION'      => $configuration->get_css_version() !== '' ? $configuration->get_css_version() : $this->lang['themes.bot_informed'],
				'MAIN_COLOR'       => $configuration->get_main_color() !== '' ? $configuration->get_main_color() : $this->lang['themes.bot_informed'],
				'WIDTH'            => $configuration->get_variable_width() ? $this->lang['themes.variable-width'] : $configuration->get_width(),
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
			'C_MORE_THAN_ONE_THEME_AVAILABLE' => $not_installed_themes_number > 1,
			'C_THEME_AVAILABLE' => $not_installed_themes_number > 0,
			'THEMES_NUMBER' => $not_installed_themes_number
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
		foreach ($folder_containing_phpboost_themes->get_folders() as $folder)
		{
			$folder_name = $folder->get_name();
			if ($folder_name != 'default' && !ThemesManager::get_theme_existed($folder_name))
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
			$this->view->put('MSG', MessageHelper::display($error, MessageHelper::WARNING, 10));
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

		$fieldset->add_field(new FormFieldFree('warnings', '', $this->lang['themes.add.warning_before_install'], array('class' => 'full-field')));

		$fieldset->add_field(new FormFieldFilePicker('file', StringVars::replace_vars($this->lang['themes.upload_description'],
			array('max_size' => File::get_formated_size(ServerConfiguration::get_upload_max_filesize()))),
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

					$archive_root_content = array();
					$required_files = array('/config.ini', '/body.tpl', '/frame.tpl', '/theme/content.css', '/theme/design.css', '/theme/global.css');
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
