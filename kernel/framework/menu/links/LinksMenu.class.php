<?php
/*##################################################
*                             menu.class.php
*                            -------------------
*   begin               : July 08, 2008
*   copyright           : (C) 2008 Régis Viarre; Loïc Rouchon
*   email               : crowkait@phpboost.com; horn@phpboost.com
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

import('menu/links/LinksMenuLink');

define('LINKS_MENU__CLASS', 'LinksMenu');

## Menu types ##
define('VERTICAL_MENU', 'vertical');
define('HORIZONTAL_MENU', 'horizontal');
define('TREE_MENU', 'tree');
define('VERTICAL_SCROLLING_MENU', 'vertical_scrolling');
define('HORIZONTAL_SCROLLING_MENU', 'horizontal_scrolling');


/**
* @author Loïc Rouchon <horn@phpboost.com>
* @desc Create a Menu with children.
* Children could be Menu or LinksMenuLink objects
* @package menu
* @subpackage linksmenu
*/
class LinksMenu extends LinksMenuElement
{
    /**
	* @access protected
	* @var string menu's type
	*/
    protected $type;
    /**
	* @access protected
	* @var LinksMenuElement[] Direct menu children list
	*/
    protected $elements = array();
	
    /**
	* @desc Constructor
	* @param string $title Menu title
	* @param string $url Destination url
	* @param string $image Menu's image url relative to the website root or absolute
	* @param string $type Menu's type
	* @param int $id The Menu's id in the database
	*/
    public function __construct($title, $url, $image = '', $type = VERTICAL_SCROLLING_MENU)
    {
        // Set the menu type
        $this->type = in_array($type, self::get_menu_types_list()) ? $type : VERTICAL_SCROLLING_MENU;
        
        // Build the menu element on witch is based the menu
        parent::__construct($title, $url, $image);
    }
    
	/**
	* @desc Add a list of LinksMenu or (sub)Menu to the current one
	* @param &LinksMenuElement[] &$menu_elements A reference to a list of LinksMenuLink and / or Menu to add
	*/
    public function add_array(&$menu_elements)
    {
        foreach ($menu_elements as $element)
        {
        	$this->add($element);
        }
    }
    
	/**
	* @desc Add a single LinksMenuLink or (sub) Menu
	* @param LinksMenuElement $element the LinksMenuLink or Menu to add
	*/
    public function add($element)
    {
        if (get_class($element) == get_class($this))
        {
        	$element->_parent($this->type);
        }
        else
        {
        	$element->_parent();
        }
        
        $this->elements[] = $element;
    }
    
    /**
	* Update the menu uid
	*/
    public function update_uid()
    {
        parent::update_uid();
        foreach ($this->elements as $element)
        {
        	$element->update_uid();
        }
    }
    
	/**
	* @desc Display the menu
	* @param Template $template the template to use
	* @return string the menu parsed in xHTML
	*/
    public function display($template = false, $mode = LINKS_MENU_ELEMENT__CLASSIC_DISPLAYING)
    {
        // Stop if the user isn't authorised
        if (!$this->_check_auth())
        {
            return '';
        }
        
        // Get the good Template object
        if (!is_object($template) || strtolower(get_class($template)) != 'template')
        {
            $tpl = new Template('framework/menus/links/' . $this->type . '.tpl');
        }
        else
        {
            $tpl = $template->copy();
        }
        $original_tpl = $tpl->copy();
        
        // Children assignment
        foreach ($this->elements as $element)
        {   // We use a new Tpl to avoid overwrite issues
            $tpl->assign_block_vars('elements', array('DISPLAY' => $element->display($original_tpl->copy(), $mode)));
        }
        
        // Menu assignment
        parent::_assign($tpl, $mode);
        $tpl->assign_vars(array(
            'C_MENU' => true,
            'C_NEXT_MENU' => ($this->depth > 0) ? true : false,
            'C_FIRST_MENU' => ($this->depth == 0) ? true : false,
            'C_HAS_CHILD' => count($this->elements) > 0
        ));
        
        return $tpl->parse(Template::TEMPLATE_PARSER_STRING);
    }
    

    /**
	* @return string the string to write in the cache file
	*/
    public function cache_export($template = false)
    {
        // Get the good Template object
        if (!is_object($template) || strtolower(get_class($template)) != 'template')
        {
            $tpl = new Template('framework/menus/links/' . $this->type . '.tpl');
        }
        else
        {
            $tpl = $template->copy();
        }
        $original_tpl = $tpl->copy();
        
        // Children assignment
        foreach ($this->elements as $element)
        {   // We use a new Tpl to avoid overwrite issues
            $tpl->assign_block_vars('elements', array('DISPLAY' => $element->cache_export($original_tpl->copy())));
        }
        
        // Menu assignment
        parent::_assign($tpl, LINKS_MENU_ELEMENT__CLASSIC_DISPLAYING);
        $tpl->assign_vars(array(
            'C_MENU' => true,
            'C_NEXT_MENU' => $this->depth > 0,
            'C_FIRST_MENU' => $this->depth == 0,
            'C_HAS_CHILD' => count($this->elements) > 0,
            'ID' => '##.#GET_UID#.##',
            'ID_VAR' => '##.#GET_UID_VAR#.##',
        ));
        
        if ($this->depth == 0)
        {   // We protect and unprotect only on the top level
            $cache_str = parent::cache_export_begin() . '\'.' .
                var_export($tpl->parse(Template::TEMPLATE_PARSER_STRING), true) .
                '.\'' . parent::cache_export_end();
            $cache_str = str_replace(
                array('#GET_UID#', '#GET_UID_VAR#', '##'),
                array('($__uid = get_uid())', '$__uid', '\''),
                $cache_str
            );
            return $cache_str;
        }
        return parent::cache_export_begin() . $tpl->parse(Template::TEMPLATE_PARSER_STRING) . parent::cache_export_end();
    }   
     
    /**
	* static method which returns all the menu types
	*
	* @return string[] The list of the menu types
	* @static
	*/
    public static function get_menu_types_list()
    {
        return array(VERTICAL_MENU, HORIZONTAL_MENU, VERTICAL_SCROLLING_MENU, HORIZONTAL_SCROLLING_MENU/*, TREE_MENU*/);
    }
   
    /**
	* @desc Increase the Menu Depth and set the menu type to its parent one
	* @access protected
	* @param string $type the type of the menu
	*/
    protected function _parent($type)
    {
        parent::_parent($type);
        
        $this->type = $type;
        foreach ($this->elements as $element)
        {
            $element->_parent($type);
        }
    }
    
    ## Getters ##
	/**
	* @return string the menu type
	*/
    public function get_type() { return $this->type; }
    
    /**
	* Sets the type of the menu
	*
	* @param string $type Type of the menu
	*/
    public function set_type($type) { $this->type = $type; }
    
    /**
	* @return LinksMenuElement[] the menu children elements
	*/
    public function get_children() { return $this->elements; }
}

?>
