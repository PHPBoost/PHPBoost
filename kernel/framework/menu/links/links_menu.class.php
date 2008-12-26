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

import('menu/links/links_menu_link');

define('LINKS_MENU__CLASS', 'LinksMenu');

## Menu types ##
define('VERTICAL_MENU', 'vertical');
define('HORIZONTAL_MENU', 'horizontal');
define('TREE_MENU', 'tree');
define('VERTICAL_SCROLLING_MENU', 'vertical_scrolling');
define('HORIZONTAL_SCROLLING_MENU', 'horizontal_scrolling');


/**
 * @author Loïc Rouchon horn@phpboost.com
 * @desc Create a Menu with children.
 * Children could be Menu or LinksMenuLink objects
 * @package Menu
 * @subpackage LinksMenu
 */
class LinksMenu extends LinksMenuElement
{
	## Public Methods ##
	/**
	 * @desc Constructor
	 * @param string $title Menu title
	 * @param string $url Destination url
	 * @param string $image Menu's image url relative to the website root or absolute
     * @param string $type Menu's type
     * @param int $id The Menu's id in the database
	 */
	function LinksMenu($title, $url, $image = '', $type = VERTICAL_SCROLLING_MENU)
	{
		// Set the menu type
		$menu_types = array(VERTICAL_MENU, HORIZONTAL_MENU, TREE_MENU, VERTICAL_SCROLLING_MENU, HORIZONTAL_SCROLLING_MENU);
		$this->type = in_array($type, $menu_types) ? $type : VERTICAL_SCROLLING_MENU;
		
		// Build the menu element on witch is based the menu
		parent::LinksMenuElement($title, $url, $image);
	}
	
	/**
	 * @desc Add a list of LinksMenu or (sub)Menu to the current one
	 * @param &LinksMenuElement[] &$menu_elements A reference to a list of LinksMenuLink and / or Menu to add
	 */
	function add_array(&$menu_elements)
	{
		foreach ($menu_elements as $element)
			$this->add($element);
	}
	
	/**
	 * @desc Add a single LinksMenuLink or (sub) Menu
	 * @param LinksMenuElement $element the LinksMenuLink or Menu to add
	 */
	function add($element)
	{
	    if (get_class($element) == get_class($this))
	       $element->_parent($this->type);
		$this->elements[] = $element;
	}
	
	/**
	 * @desc Display the menu
	 * @param Template $template the template to use
	 * @return string the menu parsed in xHTML
	 */
	function display($template = false)
	{
	    // Stop if the user isn't authorised
		if (!$this->_check_auth())
    	    return '';
		
    	// Get the good Template object
	    if (!is_object($template) || strtolower(get_class($template)) != 'template')
			$tpl = new Template('framework/menus/links/menu_' . $this->type . '.tpl');
		else
			$tpl = $template->copy();
        $original_tpl = $tpl->copy();
		
        // Children assignment
        foreach ($this->elements as $element)
		{   // We use a new Tpl to avoid overwrite issues
			$tpl->assign_block_vars('elements', array('DISPLAY' => $element->display($original_tpl->copy())));
		}
		
		// Menu assignment
		parent::_assign($tpl);
        $tpl->assign_vars(array(
            'C_MENU' => true,
            'C_NEXT_MENU' => ($this->depth > 0) ? true : false,
            'C_FIRST_MENU' => ($this->depth == 0) ? true : false,
            'DEPTH' => $this->depth
        ));
        
		return $tpl->parse(TEMPLATE_STRING_MODE);
	}
	

	/**
     * @return string the string to write in the cache file
     */
    function cache_export()
    {
        $tpl = new Template('framework/menus/links/menu_' . $this->type . '.tpl');
        $original_tpl = $tpl->copy();
        
        // Children assignment
        foreach ($this->elements as $element)
        {   // We use a new Tpl to avoid overwrite issues
            $tpl->assign_block_vars('elements', array('DISPLAY' => $element->cache_export($original_tpl->copy())));
        }
        
        // Menu assignment
        parent::_assign($tpl);
        $tpl->assign_vars(array(
            'C_MENU' => true,
            'C_NEXT_MENU' => ($this->depth > 0) ? true : false,
            'C_FIRST_MENU' => ($this->depth == 0) ? true : false,
            'DEPTH' => $this->depth,
            'ID' => '##.#GET_UID#.##',
            'ID_VAR' => '##.#GET_UID_VAR#.##',
        ));
        
        if ($this->depth == 0)
        {   // We protect and unprotect only on the top level
            $cache_str = var_export($tpl->parse(TEMPLATE_STRING_MODE), true);
            $cache_str = str_replace(
                array('#GET_UID#', '#GET_UID_VAR#', '##'),
                array('($__uid = get_uid())', '$__uid', '\''),
                $cache_str
            );
            return parent::cache_export_begin() . '\'.' . $cache_str  . '.\'' . parent::cache_export_end();
        }
        return parent::cache_export_begin() . $tpl->parse(TEMPLATE_STRING_MODE) . parent::cache_export_end();
    }
	
	## Getters ##
    /**
     * @return string the menu type
     */
    function get_type() { return $this->type; }
    /**
     * @return LinksMenuElement[] the menu children elements
     */
    function get_children() { return $this->elements; }
	
	## Private Methods ##
	
	/**
	 * @desc Increase the Menu Depth and set the menu type to its parent one
	 * @access protected
	 * @param string $type the type of the menu
	 */
	function _parent($type)
	{
	    $this->depth++;
	    $this->type = $type;
	    foreach ($this->elements as $element)
	    {
	        if (get_class($element) == get_class($this))
                $element->_parent($type);
	    }
	}
	
	## Private attributes ##
	
	/**
     * @access protected
	 * @var string menu's type
	 */
	var $type;
	/**
     * @access protected
	 * @var LinksMenuElement[] Direct menu children list
	 */
	var $elements = array();
	/**
     * @access protected
	 * @var int Menu's depth
	 */
	var $depth = 0;
}

?>
