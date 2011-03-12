<?php
/*##################################################
 *                             menu.class.php
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

define('MENU__CLASS','Menu');

define('MENU_AUTH_BIT', 1);
define('MENU_ENABLE_OR_NOT', 42);
define('MENU_ENABLED', true);
define('MENU_NOT_ENABLED', false);

define('BLOCK_POSITION__NOT_ENABLED',       0);
define('BLOCK_POSITION__HEADER',            1);
define('BLOCK_POSITION__SUB_HEADER',        2);
define('BLOCK_POSITION__TOP_CENTRAL',       3);
define('BLOCK_POSITION__BOTTOM_CENTRAL',    4);
define('BLOCK_POSITION__TOP_FOOTER',        5);
define('BLOCK_POSITION__FOOTER',            6);
define('BLOCK_POSITION__LEFT',              7);
define('BLOCK_POSITION__RIGHT',             8);
define('BLOCK_POSITION__ALL',               9);

/**
 * @author Loïc Rouchon <horn@phpboost.com>
 * @desc This class represents a menu element and is used to build any kind of menu
 * @abstract
 * @package menu
 */
class Menu
{
    ## Public Methods ##
    /**
     * @desc Build a Menu element.
     * @param string $title the Menu title
     * @param int $id its id in the database
     */
    function Menu($title)
    {
       $this->title = strprotect($title, HTML_PROTECT, ADDSLASHES_NONE);
    }
    
    ## Setters ##
    /**
     * @param string $image the value to set
     */
    function set_title($title) { $this->title = strprotect($title, HTML_PROTECT, ADDSLASHES_NONE); }
    /**
     * @param array $url the authorisation array to set
     */
    function set_auth($auth) { $this->auth = $auth; }
    /**
     * @param bool $enabled Enable or not the Menu
     */
    function enabled($enabled = MENU_ENABLED) { $this->enabled = $enabled; }
    /**
     * @return int the Menu $block position
     */
    function set_block($block) { $this->block = $block; }
    /**
     * @param int $position the Menu position to set
     */
    function set_block_position($position) { $this->position = $position; }

    ## Getters ##
    /**
     * @return string the link $title
     */
    function get_title() { return $this->title; }
    /**
     * @return array the authorization array $auth
     */
    function get_auth() { return is_array($this->auth) ? $this->auth : array('r-1' => AUTH_MENUS, 'r0' => AUTH_MENUS, 'r1' => AUTH_MENUS); }
    /**
     * @return int the $id of the menu in the database
     */
    function get_id() { return $this->id; }
    /**
     * @return int the Menu $block position
     */
    function get_block() { return $this->block; }
    /**
     * @return int the Menu $position
     */
    function get_block_position() { return $this->position; }
    /**
     * @return bool true if the Menu is enabled, false otherwise
     */
    function is_enabled() { return $this->enabled; }
    
     
    /**
     * @desc Display the menu
     * @abstract
     * @param Template $template the template to use
     * @return string the menu parsed in xHTML
     */
    function display($tpl = false)
    {
        return '';
    }

    
    /**
     * @desc Display the menu admin gui
     * @return string the menu parsed in xHTML
     */
    function admin_display()
    {
        return $this->display();
    }
    
    
    /**
     * @abstract
     * @return string the string the string to write in the cache file
     */
    function cache_export()
    { }
    /**
     * @return string the string to write in the cache file at the beginning of the Menu element;
     */
    function cache_export_begin()
    {
        if (is_array($this->auth))
            return '\'; $__auth=' . preg_replace('`[\s]+`', '', var_export($this->auth, true)) . ';if ($User->check_auth($__auth,1)){$__menu.=\'';
        return '';
    }
    
    /**
     * @return string the string to write in the cache file at the end of the Menu element
     */
    function cache_export_end()
    {
        if (is_array($this->auth))
            return '\';}$__menu.=\'';
        return '';
    }
    
    /**
     * @param int $id Set the Menu database id
     */
    function id($id) { $this->id = $id; }
    
    
    ## Private Methodss ##
    /**
     * @desc Assign tpl vars
     * @access protected
     * @param Template $template the template on which we gonna assign vars
     */
    function _assign(&$template)
    {
    	import('core/menu_service');
    	MenuService::assign_positions_conditions($template, $this->get_block());
    }
    
    /**
     * @desc Check the user authorization to see the LinksMenuElement
     * @return bool true if the user is authorised, false otherwise
     */
    function _check_auth()
    {
        global $User;
        return empty($this->auth) || $User->check_auth($this->auth, MENU_AUTH_BIT);
    }
    
    ## Private Attributes ##
    /**
     * @access protected
     * @var int the element identifier, only used by the service
     */
    var $id = 0;
    /**
     * @access protected
     * @var string the Menu title
     */
    var $title = '';
    /**
     * @access protected
     * @var int[string] Represents the Menu authorisations array
     */
    var $auth = null;
    /**
     * @access protected
     * @var bool true if the Menu is used
     */
    var $enabled = MENU_NOT_ENABLED;
    /**
     * @access protected
     * @var int The Menu block position
     */
    var $block = BLOCK_POSITION__NOT_ENABLED;
    /**
     * @access protected
     * @var int The Menu position on the website
     */
    var $position = -1;
}

?>