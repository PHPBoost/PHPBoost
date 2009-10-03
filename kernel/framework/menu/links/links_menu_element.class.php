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
import('util/url');

define('LINKS_MENU_ELEMENT__CLASS','LinksMenuElement');
define('LINKS_MENU_ELEMENT__FULL_DISPLAYING', true);
define('LINKS_MENU_ELEMENT__CLASSIC_DISPLAYING', false);

/**
 * @author Loïc Rouchon <horn@phpboost.com>
 * @abstract
 * @desc A LinksMenuElement contains a Title, an url, and an image url
 * @package menu
 * @subpackage linksmenu
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
	public function __construct($title, $url, $image = '')
	{
		parent::__construct($title);
		$this->set_url($url);
		$this->set_image($image);
		$this->uid = get_uid();
	}

	## Setters ##
	/**
	 * @param string $image the value to set
	 */
	function set_image($image)
	{
		$this->image = Url::get_relative($image);
	}
	/**
	 * @param string $url the value to set
	 */
	function set_url($url)
	{
		$this->url = Url::get_relative($url);
	}

	## Getters ##
	/**
	 * Returns the menu uid
	 * @return int the menu uid
	 */
	function get_uid()
	{
		return $this->uid;
	}
	/**
	 * Update the menu uid
	 */
	function update_uid()
	{
		$this->uid = get_uid();
	}
	/**
	 * @param bool $compute_relative_url If true, computes relative urls to the website root
	 * @return string the link $url
	 */
	function get_url($compute_relative_url = true)
	{
		return $this->_get_url($this->url, $compute_relative_url);
	}

	/**
	 * @param bool $compute_relative_url If true, computes relative urls to the website root
	 * @return string the $image url
	 */
	function get_image($compute_relative_url = true)
	{
		return $this->_get_url($this->image, $compute_relative_url);
	}

	/**
	 * @param string $string_url the url to relativize / absolutize
	 * @param bool $compute_relative_url If true, computes relative urls to the website root
	 * @return string the $string_url url
	 */
	function _get_url($string_url, $compute_relative_url = true)
	{
		$url = new Url($string_url);
		if ($compute_relative_url)
		{
			return $url->relative();
		}
		else
		{
			return $url->absolute();
		}
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
	 * @abstract
	 */
	function display($template = false, $mode = LINKS_MENU_ELEMENT__CLASSIC_DISPLAYING)
	{
	}

	/**
	 * @desc returns the string to write in the cache file
	 * @return string the string to write in the cache file
	 */
	function cache_export($template = false)
	{
		return parent::cache_export();
	}

	## Private Methods ##
	/**
	 * @desc Assign tpl vars
	 * @access protected
	 * @param Template $template the template on which we gonna assign vars
	 * @param int $mode in LINKS_MENU_ELEMENT__CLASSIC_DISPLAYING mode, the links menu is
	 * displayed. With the LINKS_MENU_ELEMENT__FULL_DISPLAYING mode, the authorization form is
	 * also shown.
	 */
	function _assign(&$template, $mode = LINKS_MENU_ELEMENT__CLASSIC_DISPLAYING)
	{
		parent::_assign($template);
		$template->assign_vars(array(
            'TITLE' => $this->title,
            'C_FIRST_LEVEL' => $this->depth == 1,
            'DEPTH' => $this->depth,
            'PARENT_DEPTH' => $this->depth - 1,
            'C_URL' => !empty($this->url),
            'C_IMG' => !empty($this->image),
            'ABSOLUTE_URL' => $this->get_url(false),
            'ABSOLUTE_IMG' => $this->get_image(false),
            'RELATIVE_URL' => $this->get_url(true),
            'RELATIVE_IMG' => $this->get_image(true),
            'ID' => $this->get_uid(),
            'ID_VAR' => $this->get_uid()
		));

		//Full displaying: we also show the authorization formulary
		if ($mode)
		{
			$template->assign_vars(array(
  				'AUTH_FORM' => Authorizations::generate_select(AUTH_MENUS, $this->get_auth(), array(), 'menu_element_' . $this->uid . '_auth')
			));
		}
	}

	/**
	 * @desc Increase the Menu Depth and set the menu type to its parent one
	 * @access protected
	 */
	function _parent()
	{
		$this->depth++;
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
	/**
	 * @access protected
	 * @var int Menu's uid
	 */
	var $uid = null;
	/**
	 * @access protected
	 * @var int Menu's depth
	 */
	var $depth = 0;
}

?>
