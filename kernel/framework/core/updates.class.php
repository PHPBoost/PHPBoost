<?php
/*##################################################
 *                             updates.class.php
 *                            -------------------
 *   begin                : August 17 2008
 *   copyright            : (C) 2008 Loïc Rouchon
 *   email                : loic.rouchon@phpboost.com
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
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

define('PHPBOOST_OFFICIAL_REPOSITORY', '../../../tools/repository/repository.xml'); // Test repository
//define('PHPBOOST_OFFICIAL_REPOSITORY', 'http://www.phpboost.com/repository/main.xml');    // Official repository

require_once(PATH_TO_ROOT . '/kernel/framework/core/application.class.php');
require_once(PATH_TO_ROOT . '/kernel/framework/core/repository.class.php');

class Updates
{    
    function Updates()
    {
        $this->_load_apps();
        $this->_load_repositories();
        $this->_check_repositories();
    }
    
    function _load_apps()
    {
        global $CONFIG;
        // Add the kernel to the check list
        $this->apps[] = new Application('kernel', $CONFIG['lang'], APPLICATION_TYPE__KERNEL, $CONFIG['version'], PHPBOOST_OFFICIAL_REPOSITORY);
        
        global $MODULES;
        // Add Modules
        $kModules = array_keys($MODULES);
        foreach( $kModules as $module )
        {
            $infos = load_ini_file(PATH_TO_ROOT . '/' . $module . '/lang/', $CONFIG['lang']);
            if( !empty($infos['repository']) )
                $this->apps[] = new Application($module, $CONFIG['lang'], APPLICATION_TYPE__MODULE, $infos['version'], $infos['repository']);
        }
        
        global $THEME_CONFIG;
        // Add Themes
        $kThemes = array_keys($THEME_CONFIG);
        foreach( $kThemes as $theme )
        {
            $infos = load_ini_file(PATH_TO_ROOT . '/templates/' . $theme . '/config/', $CONFIG['lang']);
            if( !empty($infos['repository']) )
                $this->apps[] = new Application($theme, $CONFIG['lang'], APPLICATION_TYPE__TEMPLATE, $infos['css_version'], $infos['repository']);
        }
        
    }
    
    function _load_repositories()
    {
        foreach( $this->apps as $app )
        {
            $rep = $app->get_repository();
            if( !empty($rep) && !isset($this->repositories[$rep]) )
                $this->repositories[$rep] = new Repository($rep);
        }
    }
    
    function _check_repositories()
    {
        foreach( $this->apps as $app )
        {
            print_r($app);
            $result = $this->repositories[$app->get_repository()]->check($app);
            if( $result !== array() )
            {   // processing to the update notification
                echo '<hr /><pre>'; print_r($result); echo '</pre><br /><br /><hr /><br /><br />';
            }
        }
    }
    
    var $repositories = array();
    var $apps = array();
};

?>