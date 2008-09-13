<?php
/*##################################################
 *                          admin_update_detail.php
 *                            -------------------
 *   begin                  : July 27, 2008
 *   copyright              : (C) 2008 Loïc Rouchon
 *   email                  : horn@phpboost.com
 *
 *   
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
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

require_once('../admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$identifier = retrieve(GET, 'identifier', '');
$tpl = new Template('admin/admin_update_detail.tpl');

$tpl->assign_vars(array(
    'L_WEBSITE_UPDATES' => $LANG['website_updates'],
    'L_KERNEL' => $LANG['kernel'],
    'L_MODULES' => $LANG['modules'],
    'L_THEMES' => $LANG['themes']
));


require_once(PATH_TO_ROOT . '/kernel/framework/members/contribution/administrator_alert_service.class.php');
if( ($update = AdministratorAlertService::find_by_identifier($identifier, 'updates', 'kernel')) !== null )
{
    require_once(PATH_TO_ROOT . '/kernel/framework/core/application.class.php');
    $app = unserialize($update->get_description());
    
    $new_features = $app->get_new_features();
    $improvments = $app->get_improvments();
    $bug_corrections = $app->get_bug_corrections();
    $security_improvments = $app->get_security_improvments();
    
    $tpl->assign_vars(array(
        'APP_NAME' => $app->get_id(),
        'APP_VERSION' => $app->get_version(),
        'APP_LANGUAGE' => $app->get_language(),
        'APP_PUBDATE' => $app->get_pubdate(),
        'APP_DESCRIPTION' => $app->get_description(),
        'APP_WARNING_LEVEL' => $app->get_warning_level(),
        'APP_WARNING' => $app->get_warning(),
        'L_WHAT_IS_NEW' => 'what_is_new',
        'L_NEW_FEATURES' => 'new_features',
        'L_IMPROVMENTS' => 'improvments',
        'L_FIXED_BUGS' => 'fixed_bugs',
        'L_SECURITY_IMPROVMENTS' => 'security_improvments',
        'C_NEW_FEATURES' => count($new_features) > 0 ? true : false,
        'C_IMPROVMENTS' => count($improvments) > 0 ? true : false,
        'C_BUG_CORRECTIONS' => count($bug_corrections) > 0 ? true : false,
        'C_SECURITY_IMPROVMENTS' => count($security_improvments) > 0 ? true : false
    ));
    
    $authors = $app->get_authors();
    foreach( $authors as $author )
        $tpl->Assign_block_vars('authors', array(
            'name' => $author['name'],
            'email' => $author['email'],
        ));
    
    foreach( $new_features as $new_feature )
        $tpl->Assign_block_vars('new_features', array('description' => $new_feature));
        
    foreach( $improvments as $improvment )
        $tpl->Assign_block_vars('improvments', array('description' => $improvment));
        
    $bug_corrections = $app->get_bug_corrections();
    foreach( $bug_corrections as $bug_correction )
        $tpl->Assign_block_vars('bugs', array('description' => $bug_correction));
    
    foreach( $security_improvments as $security_improvment )
        $tpl->Assign_block_vars('security', array('description' => $security_improvment));
}
else $tpl->assign_vars((array('L_UNEXISTING_UPDATE' => $LANG['unexisting_update'])));
    
$tpl->parse();
require_once('../admin/admin_footer.php');

?>