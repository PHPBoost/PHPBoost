<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2019 02 14
 * @since   	PHPBoost 1.6 - 2008 07 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

define('PATH_TO_ROOT', '../..');

require_once(PATH_TO_ROOT . '/admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once(PATH_TO_ROOT . '/admin/admin_header.php');

$identifier = retrieve(GET, 'identifier', '');
$tpl = new FileTemplate('admin/updates/detail.tpl');

$tpl->put_all(array(
	'L_WEBSITE_UPDATES' => $LANG['website_updates'],
	'L_KERNEL' => $LANG['kernel'],
	'L_MODULES' => $LANG['modules'],
	'L_THEMES' => $LANG['themes']
));

$app = null;
$server_configuration = new ServerConfiguration();

if (($update = AdministratorAlertService::find_by_identifier($identifier, 'updates')) !== null)
{
	$app = TextHelper::unserialize($update->get_properties());
}

if ($app instanceof Application)
{
	$installation_error = $installation_success = false;
	if (retrieve(POST, 'execute_update', '') && $server_configuration->has_curl_library() && Url::check_url_validity($app->get_autoupdate_url()))
	{
		$update_zip_name = basename($app->get_autoupdate_url());
		$zip_file_name = PATH_TO_ROOT . '/cache/' . $update_zip_name;
		
		$ch = curl_init($app->get_autoupdate_url());
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$content = curl_exec($ch);
		curl_close($ch);
		file_put_contents($zip_file_name, $content);

		$file = new File($zip_file_name);
		if ($file->exists())
		{
			$temporary_dir = PATH_TO_ROOT . '/cache/phpboost-diff';
			$folder = new Folder($temporary_dir);
			$folder->create();
			
			$zip_archive = new ZipArchive();
			$zip_archive->open($zip_file_name);
			$zip_archive->extractTo($temporary_dir);
			$zip_archive->close();
			unlink($zip_file_name);
			
			if ($folder->exists())
			{
				if ($app->get_type() == 'module')
				{
					$existing_folder = new Folder(PATH_TO_ROOT . '/' . $f->get_name());
					if ($existing_folder->exists())
					{
						FileSystemHelper::copy_folder($temporary_dir . '/' . $f->get_name() . '/', PATH_TO_ROOT . '/' . $f->get_name() . '/');
						
						switch (ModulesManager::upgrade_module($f->get_name()))
						{
							case ModulesManager::UPGRADE_FAILED:
								$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('process.error', 'status-messages-common'), MessageHelper::ERROR));
								$installation_error = true;
								break;
							case ModulesManager::MODULE_NOT_UPGRADABLE:
								$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('modules.module_not_upgradable', 'admin-modules-common'), MessageHelper::WARNING));
								$installation_error = true;
								break;
							case ModulesManager::MODULE_UPDATED:
								$installation_success = true;
								break;
						}
					}
					else
					{
						$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('modules.not_installed_module', 'admin-modules-common'), MessageHelper::ERROR));
						$installation_error = true;
					}
				}
				else if ($app->get_type() == 'template')
				{
					$existing_folder = new Folder(PATH_TO_ROOT . '/templates/' . $f->get_name());
					if ($existing_folder->exists())
					{
						FileSystemHelper::copy_folder($temporary_dir . '/templates/' . $f->get_name() . '/', PATH_TO_ROOT . '/templates/' . $f->get_name() . '/');
						$installation_success = true;
					}
					else
					{
						$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('process.error', 'status-messages-common'), MessageHelper::ERROR));
						$installation_error = true;
					}
				}
				else
				{
					foreach ($folder->get_folders() as $f)
					{
						$existing_folder = new Folder(PATH_TO_ROOT . '/' . $f->get_name());
						if ($f->get_name() != 'lang' && $f->get_name() != 'templates' && $existing_folder->exists())
						{
							FileSystemHelper::copy_folder($temporary_dir . '/' . $f->get_name() . '/', PATH_TO_ROOT . '/' . $f->get_name() . '/');
						}
						else if ($f->get_name() == 'lang')
						{
							$lang_folder = new Folder($temporary_dir . '/lang');
							foreach ($lang_folder->get_folders() as $f_lang)
							{
								$existing_folder = new Folder(PATH_TO_ROOT . '/lang/' . $f_lang->get_name());
								if ($existing_folder->exists())
									FileSystemHelper::copy_folder($temporary_dir . '/lang/' . $f_lang->get_name() . '/', PATH_TO_ROOT . '/lang/' . $f_lang->get_name() . '/');
							}
						}
						else if ($f->get_name() == 'templates')
						{
							$templates_folder = new Folder($temporary_dir . '/templates');
							foreach ($templates_folder->get_folders() as $f_templates)
							{
								$existing_folder = new Folder(PATH_TO_ROOT . '/templates/' . $f_templates->get_name());
								if ($existing_folder->exists())
									FileSystemHelper::copy_folder($temporary_dir . '/templates/' . $f_templates->get_name() . '/', PATH_TO_ROOT . '/templates/' . $f_templates->get_name() . '/');
							}
						}
					}
					$installation_success = true;
				}
				
				FileSystemHelper::remove_folder($temporary_dir);
				
				// Set admin alert status to processed
				$update->set_status(Event::EVENT_STATUS_PROCESSED);
				AdministratorAlertService::save_alert($update);
				
				$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('process.success', 'status-messages-common'), MessageHelper::SUCCESS, 4));
			}
			else
			{
				$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('process.error', 'status-messages-common'), MessageHelper::ERROR));
				$installation_error = true;
			}
		}
		else
		{
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('process.error', 'status-messages-common'), MessageHelper::ERROR));
			$installation_error = true;
		}
	}
	
	$authors = $app->get_authors();
	$new_features = $app->get_new_features();
	$warning = $app->get_warning();
	$improvements = $app->get_improvements();
	$bug_corrections = $app->get_bug_corrections();
	$security_improvements = $app->get_security_improvements();

	$nb_authors = count($authors);
	$has_new_feature = count($new_features) > 0;
	$has_warning = !empty($warning);
	$has_improvements = count($improvements) > 0;
	$has_bug_corrections = count($bug_corrections) > 0;
	$has_security_improvements = count($security_improvements) > 0;
	
	switch ($update->get_priority())
	{
		case AdministratorAlert::ADMIN_ALERT_VERY_HIGH_PRIORITY:
			$priority_css_class = 'alert-very-high-priority';
			break;
		case AdministratorAlert::ADMIN_ALERT_HIGH_PRIORITY:
			$priority_css_class = 'alert-high-priority';
			break;
		case AdministratorAlert::ADMIN_ALERT_MEDIUM_PRIORITY:
			$priority_css_class = 'alert-medium-priority';
			break;
		case AdministratorAlert::ADMIN_ALERT_LOW_PRIORITY:
			$priority_css_class = 'alert-low-priority';
			break;
		default:
			$priority_css_class = 'alert-very-low-priority';
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

	$tpl->put_all(array(
		'APP_NAME' => $app->get_name(),
		'APP_VERSION' => $app->get_version(),
		'APP_LANGUAGE' => $app->get_localized_language(),
		'APP_PUBDATE' => $app->get_pubdate(),
		'APP_DESCRIPTION' => $app->get_description(),
		'APP_WARNING_LEVEL' => $app->get_warning_level(),
		'APP_WARNING' => $warning,
		'U_APP_DOWNLOAD' => $app->get_download_url(),
		'U_APP_UPDATE' => $app->get_update_url(),
		'PRIORITY' => $update->get_priority_name(),
		'PRIORITY_CSS_CLASS' => $priority_css_class,
		'WARNING_CSS_CLASS' => $warning_css_class,
		'IDENTIFIER' => $identifier,
		'L_AUTHORS' => $nb_authors > 1 ? $LANG['authors'] : $LANG['author'],
		'L_NEW_FEATURES' => $LANG['new_features'],
		'L_IMPROVEMENTS' => $LANG['improvements'],
		'L_FIXED_BUGS' => $LANG['fixed_bugs'],
		'L_SECURITY_IMPROVEMENTS' => $LANG['security_improvements'],
		'L_DOWNLOAD' => $LANG['app_update__download'],
		'L_DOWNLOAD_PACK' => $LANG['app_update__download_pack'],
		'L_UPDATE_PACK' => $LANG['app_update__update_pack'],
		'L_WARNING' => $LANG['warning'],
		'L_APP_UPDATE_MESSAGE' => $update->get_entitled(),
		'C_DISPLAY_LINKS_AND_PRIORITY' => !$installation_success && $app->check_compatibility() && ($app->get_type() == 'kernel' ? version_compare(Environment::get_phpboost_version(), $app->get_version(), '<') : true),
		'C_DISPLAY_UPDATE_BUTTON' => $app->get_autoupdate_url() != '' && $server_configuration->has_curl_library() && Url::check_url_validity($app->get_autoupdate_url()) && !$installation_error,
		'C_NEW_FEATURES' => $has_new_feature,
		'C_APP_WARNING' => $has_warning,
		'C_IMPROVEMENTS' => $has_improvements,
		'C_BUG_CORRECTIONS' => $has_bug_corrections,
		'C_SECURITY_IMPROVEMENTS' => $has_security_improvements,
		'C_NEW' => $has_new_feature || $has_improvements || $has_bug_corrections || $has_security_improvements
	));

	foreach ($authors as $author)
		$tpl->assign_block_vars('authors', array('name' => $author['name'], 'email' => $author['email']));

	foreach ($new_features as $new_feature)
		$tpl->assign_block_vars('new_features', array('description' => $new_feature));

	foreach ($improvements as $improvement)
		$tpl->assign_block_vars('improvements', array('description' => $improvement));

	foreach ($bug_corrections as $bug_correction)
		$tpl->assign_block_vars('bugs', array('description' => $bug_correction));

	foreach ($security_improvements as $security_improvement)
		$tpl->assign_block_vars('security', array('description' => $security_improvement));
}
else
{
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}

$tpl->display();
require_once(PATH_TO_ROOT . '/admin/admin_footer.php');
?>
