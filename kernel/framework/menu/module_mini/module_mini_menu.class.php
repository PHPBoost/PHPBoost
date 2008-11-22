<?php
/*##################################################
 *                          modules_mini_menu.class.php
 *                            -------------------
 *   begin                : November 15, 2008
 *   copyright            : (C) 2008 Loïc Rouchon
 *   email                : horn@phpboost.com
 *
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

import('menu/menu');

define('MODULE_MINI_MENU__CLASS','ModuleMiniMenu');

/**
 * @author Loïc Rouchon horn@phpboost.com
 * @desc
 * @package Menu
 * @subpackage ModulesMiniMenu
 */
class ModuleMiniMenu extends Menu
{
    ## Public Methods ##
    /**
     * @desc Build a ModuleMiniMenu element.
     * @param string $title its name (according the the module folder name)
     */
    function ModuleMiniMenu($title)
    {
       parent::Menu($title);
    }
    
    /**
     * @return string the string the string to write in the cache file
     */
    function cache_export()
    {
        $title = strtolower($this->get_title());
        $cache_str = '\';include_once PATH_TO_ROOT.\'/' . $title . '/' . $title . '_mini.php\';';
        $cache_str.= '$__menu.=' . $title . '_mini().\'';
        return parent::cache_export_begin() . $cache_str . parent::cache_export_end();
    }
}

?>