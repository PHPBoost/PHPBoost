<?php
/*##################################################
 *                         FeedItemEnclosure.class.php
 *                         -------------------
 *   begin                : April 16, 2013
 *   copyright            : (C) 2013 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 * @desc Contains meta-informations and informations about a feed item enclosure
 * @package {@package}
 */
class FeedItemEnclosure
{
	private $lenght;
	private $type;
	private $url;
	
	/**
	 * @desc Defines the lenght (in bytes) of the media file
	 * @param int $lenght
	 */
	public function set_lenght($lenght)
	{
		$this->lenght = $lenght;
	}
	
	public function get_lenght()
	{
		return $this->lenght;
	}
	
	/**
	 * @desc Defines the type of media file
	 * @param string $type
	 */
	public function set_type($type)
	{
		$this->type = $type;
	}
	
	public function get_type()
	{
		return $this->type;
	}
	
	/**
	 * @desc Defines the URL to the media file
	 * @param mixed $url a string url or an Url object
	 */
	public function set_url($url)
	{
		if (!($url instanceof Url))
        {
            $url = new Url($url);
        }
        $this->url = $url->rel();
	}
	
	public function get_url()
	{
		return $this->url;
	}
}
?>