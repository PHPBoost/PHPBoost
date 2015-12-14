<?php
/*##################################################
 *                           FormFieldActionLinkElement.class.php
 *                            -------------------
 *   begin                : April 14, 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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
 * @desc This class manage action links.
 * @package {@package}
 */
class FormFieldActionLinkElement
{
	private $title;
	private $url;
	private $css_class;
	private $img;

	/**
	 * @desc build an action link
	 * @param string $title the action title
	 * @param Url $url the action url
	 * @param string $css_class the action font awesome css class
	 * @param Url $img the action icon url
	 */
	public function __construct($title, $url, $css_class = '', $img = '')
	{
		$this->title = $title;
		$this->url = $this->convert_url($url);
		$this->css_class = $css_class;
		$this->img = !empty($img) ? $this->convert_url($img) : $img;
	}

	/**
	 * @return string
	 */
	public function get_title()
	{
		return $this->title;
	}

	/**
	 * @return Url
	 */
	public function get_url()
	{
		return $this->url;
	}

	/**
	 * @return bool
	 */
	public function has_css_class()
	{
		return !empty($this->css_class);
	}

	/**
	 * @return string
	 */
	public function get_css_class()
	{
		return $this->css_class;
	}

	/**
	 * @return bool
	 */
	public function has_img()
	{
		return !empty($this->img);
	}

	/**
	 * @return Url
	 */
	public function get_img()
	{
		return $this->img;
	}
	
	private function convert_url($url)
	{
		return $url instanceof Url ? $url : new Url($url);
	}
}
?>