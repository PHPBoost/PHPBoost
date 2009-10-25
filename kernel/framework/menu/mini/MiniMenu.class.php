<?php
/*##################################################
 *                          modules_mini_menu.class.php
 *                            -------------------
 *   begin                : November 15, 2008
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

import('menu/Menu');

define('MINI_MENU__CLASS', 'MiniMenu');

/**
 * @author Loïc Rouchon <loic.rouchon@phpboost.com>
 * @desc
 * @package menu
 * @subpackage minimenu
 */
class MiniMenu extends Menu
{
    private $function_name = '';
    
    public function __construct($title, $filename)
    {
        $this->function_name = 'menu_' . strtolower($title) . '_' . strtolower($filename);
        parent::__construct($title . '/' . $filename);
    }

    public function display($tpl = false)
    {
    	return '';
    }
    
    /**
	* @return string the string the string to write in the cache file
	*/
    public function cache_export()
    {
        $cache_str = '\';include_once PATH_TO_ROOT.\'/menus/' . strtolower($this->title) . '.php\';';
        $cache_str.= 'if(function_exists(\'' . $this->function_name . '\')) { $__menu.=' . $this->function_name . '(' . $this->position . ',' . $this->block . ');} $__menu.=\'';
        return parent::cache_export_begin() . $cache_str . parent::cache_export_end();
    }
	
	public function get_formated_title()
    {
		return ucfirst(substr($this->title, 0, strpos($this->title, '/')));
    }
}

?>