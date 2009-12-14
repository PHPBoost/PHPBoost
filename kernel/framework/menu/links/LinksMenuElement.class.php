<?php
/*##################################################
 *                             menu_element.class.php
 *                            -------------------
 *   begin                : July 08, 2008
 *   copyright            : (C) 2008 Régis Viarre; Loïc Rouchon
 *   email                : crowkait@phpboost.com; loic.rouchon@phpboost.com
 *
 *
 ###################################################
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
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

/**
 * @author Loïc Rouchon <loic.rouchon@phpboost.com>
 * @abstract
 * @desc A LinksMenuElement contains a Title, an url, and an image url
 * @package menu
 * @subpackage linksmenu
 */
abstract class LinksMenuElement extends Menu
{
	const LINKS_MENU_ELEMENT__CLASS = 'LinksMenuElement';
	const LINKS_MENU_ELEMENT__FULL_DISPLAYING = true;
	const LINKS_MENU_ELEMENT__CLASSIC_DISPLAYING = false;
	
	/**
	 * @access protected
	 * @var string the LinksMenuElement url
	 */
	protected $url = '';
	/**
	 * @access protected
	 * @var string the image url. Could be relative to the website root or absolute
	 */
	protected $image = '';
	/**
	 * @access protected
	 * @var int Menu's uid
	 */
	protected $uid = null;
	/**
	 * @access protected
	 * @var int Menu's depth
	 */
	protected $depth = 0;
	
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

	/**
	 * Displays the menu according to the given template
	 *
	 * @param Template $template Template according to which the menu must be displayed.
	 * If it's not displayed, a default template will be used.
	 * @return string the HTML code of the menu
	 * @abstract
	 */
	//TODO Loic faire une bidouille pour que la surchage ne pose pas de problème
	//abstract public function display($template = false, $mode = self::LINKS_MENU_ELEMENT__CLASSIC_DISPLAYING);
	
	/**
	 * @desc returns the string to write in the cache file at the beginning of the Menu element
	 * @return string the string to write in the cache file at the beginning of the Menu element;
	 */
	public function cache_export_begin()
	{
		return str_replace('\'', '##', parent::cache_export_begin());
	}

	/**
	 * @desc returns the string to write in the cache file at the end of the Menu element
	 * @return string the string to write in the cache file at the end of the Menu element
	 */
	public function cache_export_end()
	{
		return str_replace('\'', '##', parent::cache_export_end());
	}

	/**
	 * @desc returns the string to write in the cache file
	 * @return string the string to write in the cache file
	 */
	public function cache_export($template = false)
	{
		return parent::cache_export();
	}

	/**
	 * @desc Assign tpl vars
	 * @access protected
	 * @param Template $template the template on which we gonna assign vars
	 * @param int $mode in LinksMenuElement::LINKS_MENU_ELEMENT__CLASSIC_DISPLAYING mode, the links menu is
	 * displayed. With the LinksMenuElement::LINKS_MENU_ELEMENT__FULL_DISPLAYING mode, the authorization form is
	 * also shown.
	 */
	protected function _assign($template, $mode = self::LINKS_MENU_ELEMENT__CLASSIC_DISPLAYING)
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
	 * @param string $string_url the url to relativize / absolutize
	 * @param bool $compute_relative_url If true, computes relative urls to the website root
	 * @return string the $string_url url
	 */
	private function _get_url($string_url, $compute_relative_url = true)
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
	 * @desc Increase the Menu Depth and set the menu type to its parent one
	 * @access protected
	 */
	protected function _parent()
	{
		$this->depth++;
	}
	
	## Setters ##
	/**
	 * @param string $image the value to set
	 */
	public function set_image($image)
	{
		$this->image = Url::get_relative($image);
	}
	/**
	 * @param string $url the value to set
	 */
	public function set_url($url)
	{
		$this->url = Url::get_relative($url);
	}

	## Getters ##
	/**
	 * Returns the menu uid
	 * @return int the menu uid
	 */
	public function get_uid()
	{
		return $this->uid;
	}
	/**
	 * Update the menu uid
	 */
	public function update_uid()
	{
		$this->uid = get_uid();
	}
	/**
	 * @param bool $compute_relative_url If true, computes relative urls to the website root
	 * @return string the link $url
	 */
	public function get_url($compute_relative_url = true)
	{
		return $this->_get_url($this->url, $compute_relative_url);
	}

	/**
	 * @param bool $compute_relative_url If true, computes relative urls to the website root
	 * @return string the $image url
	 */
	public function get_image($compute_relative_url = true)
	{
		return $this->_get_url($this->image, $compute_relative_url);
	}
}

?>
