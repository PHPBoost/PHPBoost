<?php
/*##################################################
 *                             menu_element.class.php
 *                            -------------------
 *   begin                : July 08, 2008
 *   copyright            : (C) 2008 Régis Viarre; Loïc Rouchon
 *   email                : crowkait@phpboost.com; horn@phpboost.com
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

// Abstract class : Do not instanciate it
//      LinksMenuLink and LinksMenuLink classes are based on this class
//      use, on of these

import('menu/menu');

define('LINKS_MENU_ELEMENT__CLASS','LinksMenuElement');
define('LINKS_MENU_ELEMENT__FULL_DISPLAYING', true);
define('LINKS_MENU_ELEMENT__CLASSIC_DISPLAYING', false);

/**
 * @author Loïc Rouchon horn@phpboost.com
 * @abstract
 * @desc A LinksMenuElement contains a Title, an url, and an image url
 * @package Menu
 * @subpackage LinksMenu
 */
class LinksMenuElement extends Menu
{
	## Public Methods ##
	/**
	 * @desc Build a LinksMenuElement object
	 * @param $title
	 * @param $url
	 * @param $image
     * @param int $id The Menu's id in the database
	 */
	function LinksMenuElement($title, $url, $image = '')
	{
       $this->url = $url;
       $this->image = $image;
       parent::Menu($title);
	}
	
	## Setters ##
    /**
     * @param string $image the value to set
     */
	function set_image($image) { $this->image = $image; }
	/**
	 * @param string $url the value to set
	 */
	function set_url($url) { $this->url = $url; }
     
	## Getters ##
    /**
     * @param bool $compute_relative_url If true, computes relative urls to the website root
     * @return string the link $url
     */
	function get_url($compute_relative_url = true)
	{
        if ($compute_relative_url)
	       return url(strpos($this->url, '://') > 0 ? $this->url : PATH_TO_ROOT . $this->url);
       return url($this->url);
	}
    /**
     * @param bool $compute_relative_url If true, computes relative urls to the website root
     * @return string the link $image url
     */
    function get_image($compute_relative_url = true)
    {
        if ($compute_relative_url)
            return strpos($this->image, '://') > 0 ? $this->image : PATH_TO_ROOT . $this->image;
        return $this->image;
    }
	
    /**
     * @desc returns the string to write in the cache file at the beginning of the Menu element
     * @return string the string to write in the cache file at the beginning of the Menu element;
     */
    function cache_export_begin()
    {
        return str_replace('\'', '##', parent::cache_export_begin());
    }
    
    /**
     * @desc returns the string to write in the cache file at the end of the Menu element
     * @return string the string to write in the cache file at the end of the Menu element
     */
    function cache_export_end()
    {
        return str_replace('\'', '##', parent::cache_export_end());
    }
    
    /**
     * Displays the menu according to the given template
     *
     * @param Template $template Template according to which the menu must be displayed.
     * If it's not displayed, a default template will be used.
     * @return string the HTML code of the menu
     */
    function display($template = false, $mode = LINKS_MENU_ELEMENT__CLASSIC_DISPLAYING)
    {
    	return '';
    }
	
    ## Private Methods ##
    /**
     * @desc Assign tpl vars
     * @access protected
     * @param Template $template the template on which we gonna assign vars
     */
    function _assign(&$template, $mode)
    {
        $template->assign_vars(array(
            'C_IMG' => !empty($this->image),
            'TITLE' => $this->title,
            'IMG' => $this->get_image(),
            'URL' => $this->get_url(),
            'ID' => $uid = get_uid(),
            'ID_VAR' => $uid
        ));
        
    	//Full displaying: we also show the authorization formulary
  		if ($mode)
  		{
  			$template->assign_vars(array(
  				'AUTH_FORM' => Authorizations::generate_select(AUTH_MENUS, $this->auth, array(), 'menu_element_' . $uid . '_auth')
  			));
		}
    }
	
    
    /**
     * @desc returns the string to write in the cache file
     * @return string the string to write in the cache file
     */
    function cache_export()
    {
        return parent::cache_export();
    }
    
	## Private attributes ##
	/**
     * @access protected
     * @var string the LinksMenuElement url
     */
    var $url = '';
    /**
     * @access protected
     * @var string the image url. Could be relative to the website root or absolute
     */
	var $image = '';
}

?>