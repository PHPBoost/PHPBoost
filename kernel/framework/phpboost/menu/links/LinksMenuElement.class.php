<?php
/*##################################################
 *                             LinksMenuElement.class.php
 *                            -------------------
 *   begin                : July 08, 2008
 *   copyright            : (C) 2008 Régis Viarre; Loic Rouchon
 *   email                : crowkait@phpboost.com; loic.rouchon@phpboost.com
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

// Abstract class : Do not instanciate it
//      LinksMenuLink and LinksMenuLink classes are based on this class
//      use, on of these

/**
 * @author Loic Rouchon <loic.rouchon@phpboost.com>
 * @abstract
 * @desc A LinksMenuElement contains a Title, an url, and an image url
 * @package {@package}
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
	public $url = '';
	/**
	 * @access protected
	 * @var string the image url. Could be relative to the website root or absolute
	 */
	public $image = '';
	/**
	 * @access protected
	 * @var int Menu's uid
	 */
	public $uid = null;
	/**
	 * @access protected
	 * @var int Menu's depth
	 */
	public $depth = 0;

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
		$this->uid = AppContext::get_uid();
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
		if ($this->image)
		{
			if ($this->image instanceof Url)
				$url = $this->image;
			else
				$url = new Url($this->image);
			
			if (file_exists(PATH_TO_ROOT . $this->image))
			{
				if (!$url->is_relative())
					$image = new Image($url->absolute());
				else
					$image = new Image(PATH_TO_ROOT . $this->image);
				
				$template->put_all(array(
					'C_IMG' => !empty($this->image),
					'ABSOLUTE_IMG' => $url->absolute(),
					'RELATIVE_IMG' => $url->relative(),
					'REL_IMG' => $url->rel(),
					'IMG_HEIGHT' => $image->get_height(),
					'IMG_WIDTH' => $image->get_width()
				));
			}
		}
		
		parent::_assign($template);
		
		if ($this->url instanceof Url)
			$url = $this->url;
		else
			$url = new Url($this->url);
		
		$template->put_all(array(
			'C_MENU' => false,
			'C_DISPLAY_AUTH' => AppContext::get_current_user()->check_auth($this->get_auth(), Menu::MENU_AUTH_BIT),
			'TITLE' => $this->title,
			'DEPTH' => $this->depth,
			'PARENT_DEPTH' => $this->depth - 1,
			'C_URL' => $this->url,
			'ABSOLUTE_URL' => $url->absolute(),
			'RELATIVE_URL' => $url->relative(),
			'REL_URL' => $url->rel(),
			'ID' => $this->get_uid(),
			'ID_VAR' => $this->get_uid()
		));

		//Full displaying: we also show the authorization formulary
		if ($mode)
		{
			$template->put_all(array(
				'C_AUTH_MENU_HIDDEN' => $this->get_auth() == array('r-1' => Menu::MENU_AUTH_BIT, 'r0' => Menu::MENU_AUTH_BIT, 'r1' => Menu::MENU_AUTH_BIT),
				'AUTH_FORM' => Authorizations::generate_select(Menu::MENU_AUTH_BIT, $this->get_auth(), array(), 'menu_element_' . $this->uid . '_auth')
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
		if ($string_url instanceof Url)
			$url = $string_url;
		else
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
	protected function _parent($type)
	{
		$this->depth++;
	}

	## Setters ##
	/**
	 * @param string $image the value to set
	 */
	public function set_image($image)
	{
		$this->image = $image;
	}
	/**
	 * @param string $url the value to set
	 */
	public function set_url($url)
	{
		$this->url = $url;
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
		$this->uid = AppContext::get_uid();
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
