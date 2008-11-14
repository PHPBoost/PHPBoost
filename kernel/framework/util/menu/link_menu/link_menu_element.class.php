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
//      LinkMenuLink and LinkMenuLink classes are based on this class
//      use, on of these

define('LINK_MENU_ELEMENT__CLASS','LinkMenuElement');

/**
 * @author Loïc Rouchon horn@phpboost.com
 * @abstract
 * @desc A LinkMenuElement contains a Title, an url, and an image url
 * @package util
 * @subpackage menu
 */
class LinkMenuElement
{
	## Public Methods ##
	/**
	 * @desc Build a LinkMenuElement object
	 * @param $title
	 * @param $url
	 * @param $image
     * @param int $id The Menu's id in the database
	 */
	function LinkMenuElement($title, $url, $image = '', $id = 0)
	{
       $this->title = $title;
       $this->url = $url;
       $this->image = $image;
	}
	
	## Setters ##
    /**
     * @param string $image the value to set
     */
	function set_image($image) { $this->image = $image; }
    /**
     * @param string $title the value to set
     */
	function set_title($title) { $this->title = $title; }
	/**
	 * @param string $url the value to set
	 */
	function set_url($url) { $this->url = $url; }
    /**
     * @param array $url the authorisation array to set
     */
    function set_auth($auth) { $this->auth = $auth; }
	
	## Getters ##
    /**
     * @return string the link $title
     */
	function get_title() { return $this->title; }
    /**
     * @param bool $compute_relative_url If true, computes relative urls to the website root
     * @return string the link $url
     */
	function get_url($compute_relative_url = true)
	{
        if( $compute_relative_url )
	       return url(strpos($this->url, '://') > 0 ? $this->url : PATH_TO_ROOT . $this->url);
       return url($this->url);
	}
    /**
     * @param bool $compute_relative_url If true, computes relative urls to the website root
     * @return string the link $image url
     */
    function get_image($compute_relative_url = true)
    {
        if( $compute_relative_url )
            return strpos($this->image, '://') > 0 ? $this->image : PATH_TO_ROOT . $this->image;
        return $this->image;
    }
	/**
	 * @return array the authorization array $auth
	 */
	function get_auth() { return $this->auth; }
    /**
     * @return int the $id of the menu in the database
     */
    function get_id() { return $this->id; }
	
	## Private Methods ##
	
	/**
	 * @desc Check the user authorization to see the LinkMenuElement
	 * @return bool true if the user is authorised, false otherwise
	 */
	function _check_auth()
	{
	    global $User;
	    return empty($this->auth) || $User->check_auth($this->auth, 1);
	}
	
    /**
     * @desc Assign tpl vars
     * @access protected
     * @param Template $template the template on which we gonna assign vars
     */
    function _assign(&$template)
    {
        $template->assign_vars(array(
            'C_IMG' => !empty($this->image),
            'TITLE' => $this->title,
            'IMG' => $this->get_image(),
            'URL' => $this->get_url()
        ));
    }
	
	## Private attributes ##
	
    /**
     * @access protected
     * @var int the element identifier, only used by the service
     */
    var $id = 0;
	/**
	 * @access protected
	 * @var string the LinkMenuElement title
	 */
	var $title = '';
    /**
     * @access protected
     * @var string the LinkMenuElement url
     */
    var $url = '';
    /**
     * @access protected
     * @var string the image url. Could be relative to the website root or absolute
     */
	var $image = '';
	/**
	 * @access protected
	 * @var int[string] Represents the LinkMenuElement authorisations array
	 */
	var $auth = array();
}

?>