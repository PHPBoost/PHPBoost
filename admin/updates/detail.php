<?php
/*##################################################
 *                           admin_update_detail.php
 *                            -------------------
 *   begin                  : July 27, 2008
 *   copyright              : (C) 2008 Loic Rouchon
 *   email                  : loic.rouchon@phpboost.com
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

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

if (($update = AdministratorAlertService::find_by_identifier($identifier, 'updates')) !== null)
{
    $app = unserialize($update->get_properties());
}

if ($app !== null && $app->check_compatibility())
{
    $authors = $app->get_authors();
    $new_features = $app->get_new_features();
    $improvments = $app->get_improvments();
    $bug_corrections = $app->get_bug_corrections();
    $security_improvments = $app->get_security_improvments();
    
    $nb_authors = count($authors);
    $has_new_feature = count($new_features) > 0 ? true : false;
    $has_improvments = count($improvments) > 0 ? true : false;
    $has_bug_corrections = count($bug_corrections) > 0 ? true : false;
    $has_security_improvments = count($security_improvments) > 0 ? true : false;
    
    switch ($update->get_priority())
    {
        case AdministratorAlert::ADMIN_ALERT_VERY_HIGH_PRIORITY:
            $priority = 'priority_very_high';
            break;
        case AdministratorAlert::ADMIN_ALERT_HIGH_PRIORITY:
            $priority = 'priority_high';
            break;
        case AdministratorAlert::ADMIN_ALERT_MEDIUM_PRIORITY:
            $priority = 'priority_medium';
            break;
        default:
            $priority = 'priority_low';
            break;
    }
    
    $tpl->put_all(array(
        'APP_NAME' => $app->get_name(),
        'APP_VERSION' => $app->get_version(),
        'APP_LANGUAGE' => $app->get_localized_language(),
        'APP_PUBDATE' => $app->get_pubdate(),
        'APP_DESCRIPTION' => $app->get_description(),
        'APP_WARNING_LEVEL' => $app->get_warning_level(),
        'APP_WARNING' => $app->get_warning(),
        'U_APP_DOWNLOAD' => $app->get_download_url(),
        'U_APP_UPDATE' => $app->get_update_url(),
        'PRIORITY_CSS_CLASS' => 'row_' . $priority,
        'L_AUTHORS' => $nb_authors > 1 ? $LANG['authors'] : $LANG['author'],
        'L_NEW_FEATURES' => $LANG['new_features'],
        'L_IMPROVMENTS' => $LANG['improvments'],
        'L_FIXED_BUGS' => $LANG['fixed_bugs'],
        'L_SECURITY_IMPROVMENTS' => $LANG['security_improvments'],
        'L_DOWNLOAD' => $LANG['app_update__download'],
        'L_DOWNLOAD_PACK' => $LANG['app_update__download_pack'],
        'L_UPDATE_PACK' => $LANG['app_update__update_pack'],
        'L_WARNING' => $LANG['warning'],
        'L_APP_UPDATE_MESSAGE' => $update ->get_entitled(),
        'C_NEW_FEATURES' => $has_new_feature,
        'C_IMPROVMENTS' => $has_improvments,
        'C_BUG_CORRECTIONS' => $has_bug_corrections,
        'C_SECURITY_IMPROVMENTS' => $has_security_improvments,
        'C_NEW' => $has_new_feature || $has_improvments || $has_bug_corrections || $has_security_improvments
    ));
    
    foreach ($authors as $author)
        $tpl->assign_block_vars('authors', array('name' => $author['name'], 'email' => $author['email']));
    
    foreach ($new_features as $new_feature)
        $tpl->assign_block_vars('new_features', array('description' => $new_feature));
        
    foreach ($improvments as $improvment)
        $tpl->assign_block_vars('improvments', array('description' => $improvment));
    
    foreach ($bug_corrections as $bug_correction)
        $tpl->assign_block_vars('bugs', array('description' => $bug_correction));
    
    foreach ($security_improvments as $security_improvment)
        $tpl->assign_block_vars('security', array('description' => $security_improvment));
}
else
{
	$error_controller = PHPBoostErrors::unexisting_page();
   	DispatchManager::redirect($error_controller);
}
    
$tpl->display();
require_once(PATH_TO_ROOT . '/admin/admin_footer.php');
?>