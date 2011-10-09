<?php
/*##################################################
 *                          ModuleMiniMenu.class.php
 *                            -------------------
 *   begin                : November 15, 2008
 *   copyright            : (C) 2008 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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

/**
 * @author Loic Rouchon <loic.rouchon@phpboost.com>
 * @desc
 * @package {@package}
 */
class ModuleMiniMenu extends Menu
{
	const MODULE_MINI_MENU__CLASS = 'ModuleMiniMenu';

    /**
	 * @desc Build a ModuleMiniMenu element.
	 */
    public function __construct()
    {
        parent::__construct($this->get_formated_title());
    }
    
	public function get_formated_title()
    {
    	return get_class($this);
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
    	$load_class = '\'; $class = new '. get_class($this) .'(); 
        $class->set_block(' . $this->block . '); 
        $class->set_block_position(' . $this->position . ');';
        $cache_str = '$__menu.= $class->display(); $__menu.=\'';
        return $load_class . parent::cache_export_begin() . $cache_str . parent::cache_export_end();
    }
}

?>