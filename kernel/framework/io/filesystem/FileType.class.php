<?php
/*##################################################
 *                               FileType.class.php
 *                            -------------------
 *   begin                : August 31, 2011
 *   copyright            : (C) 2011 Kevin MASSY
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
 * @package {@package}
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 */
class FileType
{
	private $file;
	private $extensions_image = array('png', 'gif', 'jpg', 'jpeg', 'bmp', 'tiff', 'ico', 'svg');
	private $extensions_audio = array('mp3', 'wav', 'raw', 'flac');
	private $extensions_video = array('mpeg', 'mp4', 'wmv', 'flv');
	
	public function __construct(File $file)
	{
		$this->file = $file;
	}
	
	public function is_picture()
	{
		return in_array($this->get_extension(), $this->extensions_image);
	}
	
	public function is_audio()
	{
		return in_array($this->get_extension(), $this->extensions_audio);
	}
	
	public function is_video()
	{
		return in_array($this->get_extension(), $this->extensions_video);
	}
	
	public function get_extension()
	{
		$file_name = $this->file->get_name();
		$parts = explode('.', $file_name);
		return array_pop($parts);
	}
}
?>