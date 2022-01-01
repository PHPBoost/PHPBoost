<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 04
 * @since       PHPBoost 1.6 - 2008 07 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

define('PATH_TO_ROOT', '../..');

require_once(PATH_TO_ROOT . '/admin/admin_begin.php');

$lang = LangLoader::get_all_langs();

define('TITLE', $lang['admin.updates'] . ' - ' . $lang['admin.administration']);
require_once(PATH_TO_ROOT . '/admin/admin_header.php');

$identifier = retrieve(GET, 'identifier', '');
$view = new FileTemplate('admin/updates/detail.tpl');
$view->add_lang($lang);

$app = null;
$server_configuration = new ServerConfiguration();

if (($update = AdministratorAlertService::find_by_identifier($identifier, 'updates')) !== null)
{
	$app = TextHelper::unserialize($update->get_alert_properties());
}

if ($app instanceof Application)
{
	$installation_error = $installation_success = false;
	if (retrieve(POST, 'execute_update', '') && $server_configuration->has_curl_library() && Url::check_url_validity($app->get_autoupdate_url()))
	{
		$temporary_dir_path = PATH_TO_ROOT . '/cache/phpboost-diff';
		$temporary_folder = new Folder($temporary_dir_path);
		$temporary_folder->create();

		$major_version = GeneralConfig::load()->get_phpboost_major_version();
		$actual_minor_version = Environment::get_phpboost_minor_version();
		$new_major_version = basename($app->get_version());
		$new_minor_version = str_replace($major_version . '.', '', $app->get_version());

		if ($app->get_type() == 'kernel' && $major_version == $new_major_version && $actual_minor_version < ($new_minor_version - 1))
		{
			$unread_alerts = AdministratorAlertService::get_unread_alerts();

			for ($processed_version = $actual_minor_version ; $processed_version < $new_minor_version ; $processed_version++)
			{
				$url = str_replace(array($major_version . '.' . ($new_minor_version - 1), $major_version . '.' . $new_minor_version), array($major_version . '.' . $processed_version, $major_version . '.' . ($processed_version + 1)), $app->get_autoupdate_url());

				$download_status = FileSystemHelper::download_remote_file($url, $temporary_dir_path);
				if (!$download_status)
				{
					$view->put('MESSAGE_HELPER', MessageHelper::display(StringVars::replace_vars($lang['warning.download.file.error'], array('filename' => $url)), MessageHelper::ERROR));
					$installation_error = true;
					break;
				}

				foreach ($unread_alerts as $key => $alert)
				{
					$details = TextHelper::unserialize($alert->get_alert_properties());
					if ($details instanceof Application && $details->get_version() == $major_version . '.' . ($processed_version + 1))
					{
						// Set admin alert status to processed
						$alert->set_status(AdministratorAlert::ADMIN_ALERT_STATUS_PROCESSED);
						AdministratorAlertService::save_alert($alert);

						unset($unread_alerts[$key]);
					}
				}
			}
		}
		else
		{
			$download_status = FileSystemHelper::download_remote_file($app->get_autoupdate_url(), $temporary_dir_path);
			if (!$download_status)
			{
				$view->put('MESSAGE_HELPER', MessageHelper::display(StringVars::replace_vars($lang['warning.download.file.error'], array('filename' => $app->get_autoupdate_url())), MessageHelper::ERROR));
				$installation_error = true;
			}
		}

		if ($temporary_folder->exists())
		{
			if (!$installation_error)
			{
				if ($app->get_type() == 'module')
				{
					$existing_folder = new Folder(PATH_TO_ROOT . '/' . $f->get_name());
					if ($existing_folder->exists())
					{
						FileSystemHelper::copy_folder($temporary_dir_path . '/' . $f->get_name() . '/', PATH_TO_ROOT . '/' . $f->get_name() . '/');

						switch (ModulesManager::upgrade_module($f->get_name()))
						{
							case ModulesManager::UPGRADE_FAILED:
								$view->put('MESSAGE_HELPER', MessageHelper::display($lang['warning.process.error'], MessageHelper::ERROR));
								$installation_error = true;
								break;
							case ModulesManager::MODULE_NOT_UPGRADABLE:
								$view->put('MESSAGE_HELPER', MessageHelper::display($lang['addon.modules.not.upgradable'], MessageHelper::WARNING));
								$installation_error = true;
								break;
							case ModulesManager::MODULE_UPDATED:
								$installation_success = true;
								break;
						}
					}
					else
					{
						$view->put('MESSAGE_HELPER', MessageHelper::display($lang['addon.modules.not.installed'], MessageHelper::ERROR));
						$installation_error = true;
					}
				}
				else if ($app->get_type() == 'template')
				{
					$existing_folder = new Folder(PATH_TO_ROOT . '/templates/' . $f->get_name());
					if ($existing_folder->exists())
					{
						FileSystemHelper::copy_folder($temporary_dir_path . '/templates/' . $f->get_name() . '/', PATH_TO_ROOT . '/templates/' . $f->get_name() . '/');
						$installation_success = true;
					}
					else
					{
						$view->put('MESSAGE_HELPER', MessageHelper::display($lang['warning.process.error'], MessageHelper::ERROR));
						$installation_error = true;
					}
				}
				else
				{
					foreach ($temporary_folder->get_folders() as $f)
					{
						$existing_folder = new Folder(PATH_TO_ROOT . '/' . $f->get_name());
						if ($f->get_name() != 'lang' && $f->get_name() != 'templates' && $existing_folder->exists())
						{
							FileSystemHelper::copy_folder($temporary_dir_path . '/' . $f->get_name() . '/', PATH_TO_ROOT . '/' . $f->get_name() . '/');
						}
						else if ($f->get_name() == 'lang')
						{
							$lang_folder = new Folder($temporary_dir_path . '/lang');
							foreach ($lang_folder->get_folders() as $f_lang)
							{
								$existing_folder = new Folder(PATH_TO_ROOT . '/lang/' . $f_lang->get_name());
								if ($existing_folder->exists())
									FileSystemHelper::copy_folder($temporary_dir_path . '/lang/' . $f_lang->get_name() . '/', PATH_TO_ROOT . '/lang/' . $f_lang->get_name() . '/');
							}
						}
						else if ($f->get_name() == 'templates')
						{
							$templates_folder = new Folder($temporary_dir_path . '/templates');
							foreach ($templates_folder->get_folders() as $f_templates)
							{
								$existing_folder = new Folder(PATH_TO_ROOT . '/templates/' . $f_templates->get_name());
								if ($existing_folder->exists())
									FileSystemHelper::copy_folder($temporary_dir_path . '/templates/' . $f_templates->get_name() . '/', PATH_TO_ROOT . '/templates/' . $f_templates->get_name() . '/');
							}
						}
					}
					$installation_success = true;
					AppContext::get_cache_service()->clear_cache();
					HtaccessFileCache::regenerate();
					NginxFileCache::regenerate();
				}

				if ($installation_success)
				{
					// Set admin alert status to processed
					$update->set_status(AdministratorAlert::ADMIN_ALERT_STATUS_PROCESSED);
					AdministratorAlertService::save_alert($update);

					$view->put('MESSAGE_HELPER', MessageHelper::display($lang['warning.process.success'], MessageHelper::SUCCESS, 4));
				}
			}

			FileSystemHelper::remove_folder($temporary_dir_path);
		}
		else
		{
			$view->put('MESSAGE_HELPER', MessageHelper::display($lang['warning.process.error'], MessageHelper::ERROR));
			$installation_error = true;
		}
	}

	$authors = $app->get_authors();
	$new_features = $app->get_new_features();
	$warning = $app->get_warning();
	$improvements = $app->get_improvements();
	$bug_corrections = $app->get_bug_corrections();
	$security_improvements = $app->get_security_improvements();

	$authors_number = count($authors);
	$has_new_feature = count($new_features) > 0;
	$has_warning = !empty($warning);
	$has_improvements = count($improvements) > 0;
	$has_bug_corrections = count($bug_corrections) > 0;
	$has_security_improvements = count($security_improvements) > 0;

	switch ($update->get_priority())
	{
		case AdministratorAlert::ADMIN_ALERT_VERY_HIGH_PRIORITY:
			$priority_css_class = 'error';
			break;
		case AdministratorAlert::ADMIN_ALERT_HIGH_PRIORITY:
			$priority_css_class = 'warning';
			break;
		case AdministratorAlert::ADMIN_ALERT_MEDIUM_PRIORITY:
			$priority_css_class = 'success';
			break;
		case AdministratorAlert::ADMIN_ALERT_LOW_PRIORITY:
			$priority_css_class = 'question';
			break;
		default:
			$priority_css_class = 'notice';
			break;
	}

	switch ($app->get_warning_level())
	{
		case 'high':
			$warning_css_class = 'error';
			break;
		case 'medium':
			$warning_css_class = 'warning';
			break;
		default:
			$warning_css_class = 'question';
			break;
	}

	$view->put_all(array(
		'C_DISPLAY_LINKS_AND_PRIORITY' => !$installation_success && $app->check_compatibility() && ($app->get_type() == 'kernel' ? version_compare(Environment::get_phpboost_version(), $app->get_version(), '<') : true),
		'C_DISPLAY_UPDATE_BUTTON'      => $app->get_autoupdate_url() != '' && $server_configuration->has_curl_library() && Url::check_url_validity($app->get_autoupdate_url()) && !$installation_error,
		'C_NEW_FEATURES'               => $has_new_feature,
		'C_APP_WARNING'                => $has_warning,
		'C_IMPROVEMENTS'               => $has_improvements,
		'C_BUG_CORRECTIONS'            => $has_bug_corrections,
		'C_SECURITY_IMPROVEMENTS'      => $has_security_improvements,
		'C_NEW'                        => $has_new_feature || $has_improvements || $has_bug_corrections || $has_security_improvements,

		'APP_NAME'           => $app->get_name(),
		'APP_VERSION'        => $app->get_version(),
		'APP_LANGUAGE'       => $app->get_localized_language(),
		'APP_PUBDATE'        => $app->get_pubdate(),
		'APP_DESCRIPTION'    => $app->get_description(),
		'APP_WARNING_LEVEL'  => $app->get_warning_level(),
		'APP_WARNING'        => $warning,
		'PRIORITY'           => $update->get_priority_name(),
		'PRIORITY_CSS_CLASS' => $priority_css_class,
		'WARNING_CSS_CLASS'  => $warning_css_class,
		'IDENTIFIER'         => $identifier,

		'U_APP_DOWNLOAD' => $app->get_download_url(),
		'U_APP_UPDATE'   => $app->get_update_url(),

		'L_APP_UPDATE_MESSAGE' => $update->get_entitled(),
	));

	foreach ($authors as $author)
		$view->assign_block_vars('authors', array(
			'NAME'  => $author['name'],
			'EMAIL' => $author['email']
		));

	foreach ($new_features as $new_feature)
		$view->assign_block_vars('new_features', array('DESCRIPTION' => $new_feature));

	foreach ($improvements as $improvement)
		$view->assign_block_vars('improvements', array('DESCRIPTION' => $improvement));

	foreach ($bug_corrections as $bug_correction)
		$view->assign_block_vars('bugs', array('DESCRIPTION' => $bug_correction));

	foreach ($security_improvements as $security_improvement)
		$view->assign_block_vars('security', array('DESCRIPTION' => $security_improvement));
}
else
{
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}

$view->display();
require_once(PATH_TO_ROOT . '/admin/admin_footer.php');
?>
