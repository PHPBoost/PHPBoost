<?php
/*##################################################
 *                             menu.class.php
 *                            -------------------
 *   begin                : November 15, 2008
 *   copyright            : (C) 2008 Loïc Rouchon
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
 * @author Loïc Rouchon <loic.rouchon@phpboost.com>
 * @desc This class represents a menu element and is used to build any kind of menu
 * @abstract
 * @package menu
 */
abstract class Menu
{
	const MENU_AUTH_BIT = 1;
	const MENU_ENABLE_OR_NOT = 42;
	const MENU_ENABLED = true;
	const MENU_NOT_ENABLED = false;

	const BLOCK_POSITION__NOT_ENABLED = 0;
	const BLOCK_POSITION__HEADER = 1;
	const BLOCK_POSITION__SUB_HEADER = 2;
	const BLOCK_POSITION__TOP_CENTRAL = 3;
	const BLOCK_POSITION__BOTTOM_CENTRAL = 4;
	const BLOCK_POSITION__TOP_FOOTER = 5;
	const BLOCK_POSITION__FOOTER = 6;
	const BLOCK_POSITION__LEFT = 7;
	const BLOCK_POSITION__RIGHT = 8;
	const BLOCK_POSITION__ALL = 9;
	
	const MENU__CLASS = 'Menu';
	
    /**
	 * @access protected
	 * @var int the element identifier, only used by the service
	 */
    protected $id = 0;
	/**
	 * @access protected
	 * @var string the Menu title
	 */
    protected $title = '';
    /**
	 * @access protected
	 * @var int[string] Represents the Menu authorisations array
	 */
    protected $auth = null;
    /**
	 * @access protected
	 * @var bool true if the Menu is used
	 */
    protected $enabled = self::MENU_NOT_ENABLED;
    /**
	 * @access protected
	 * @var int The Menu block position
	 */
    protected $block = self::BLOCK_POSITION__NOT_ENABLED;
    /**
	 * @access protected
	 * @var int The Menu position on the website
	 */
    protected $position = -1;
    
    /**
	 * @desc Build a Menu element.
	 * @param string $title the Menu title
	 * @param int $id its id in the database
	 */
    public function __construct($title)
    {
       $this->title = strprotect($title, HTML_PROTECT, ADDSLASHES_NONE);
    }
    
    /**
	 * @desc Display the menu
	 * @abstract
	 * @param Template $template the template to use
	 * @return string the menu parsed in xHTML
	 */
    abstract public function display($tpl = false);
	/**
	 * @abstract
	 * @return string the string the string to write in the cache file
	 */
    abstract public function cache_export();
    
    /**
	 * @desc Display the menu admin gui
	 * @return string the menu parsed in xHTML
	 */
    public function admin_display()
    {
        return $this->display();
    }
    
    /**
	 * @return string the string to write in the cache file at the beginning of the Menu element;
	 */
    public function cache_export_begin()
    {
        if (is_array($this->auth))
            return '\'; $__auth=' . preg_replace('`[\s]+`', '', var_export($this->auth, true)) . ';if ($User->check_auth($__auth,1)){$__menu.=\'';
        return '';
    }
    
    /**
	 * @return string the string to write in the cache file at the end of the Menu element
	 */
    public function cache_export_end()
    {
        if (is_array($this->auth))
            return '\';}$__menu.=\'';
        return '';
    }
    
    /**
	 * @param int $id Set the Menu database id
	 */
    public function id($id) { $this->id = $id; }
    
    /**
	 * @desc Assign tpl vars
	 * @access protected
	 * @param Template $template the template on which we gonna assign vars
	 */
    protected function _assign($template)
    {
    	MenuService::assign_positions_conditions($template, $this->get_block());
    }
    
    /**
	 * @desc Check the user authorization to see the LinksMenuElement
	 * @return bool true if the user is authorised, false otherwise
	 */
    protected function _check_auth()
    {
        global $User;
        return empty($this->auth) || $User->check_auth($this->auth, self::MENU_AUTH_BIT);
    }
    
    ## Setters ##
    /**
	 * @param string $image the value to set
	 */
    public function set_title($title) { $this->title = strprotect($title, HTML_PROTECT, ADDSLASHES_NONE); }
    /**
	 * @param array $url the authorisation array to set
	 */
    public function set_auth($auth) { $this->auth = $auth; }
    /**
	 * @param bool $enabled Enable or not the Menu
	 */
    public function enabled($enabled = self::MENU_ENABLED) { $this->enabled = $enabled; }
    /**
	 * @return int the Menu $block position
	 */
    public function set_block($block) { $this->block = $block; }
    /**
	 * @param int $position the Menu position to set
	 */
    public function set_block_position($position) { $this->position = $position; }

    ## Getters ##
    /**
	 * @return string the displayable Menu $title
	 */
    public function get_formated_title() { return $this->title; }
	/**
	 * @return string the Menu $title
	 */
    public function get_title() { return $this->title; }
	/**
	 * @return array the authorization array $auth
	 */
    public function get_auth() { return is_array($this->auth) ? $this->auth : array('r-1' => AUTH_MENUS, 'r0' => AUTH_MENUS, 'r1' => AUTH_MENUS); }
    /**
	 * @return int the $id of the menu in the database
	 */
    public function get_id() { return $this->id; }
    /**
	 * @return int the Menu $block position
	 */
    public function get_block() { return $this->block; }
    /**
	 * @return int the Menu $position
	 */
    public function get_block_position() { return $this->position; }
    /**
	 * @return bool true if the Menu is enabled, false otherwise
	 */
    public function is_enabled() { return $this->enabled; }
}

?>