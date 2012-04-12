<?php
/*##################################################
 *                       BufferedFileWriter.class.php
 *                            -------------------
 *   begin                : May 29, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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

class BufferedFileWriter implements FileWriter
{
	const DEFAULT_BUFFER_SIZE = 100000;
	
	/**
	 * @var File
	 */
	private $file;
	private $buffer = '';
	private $buffer_max_size;
	
	public function __construct(File $file, $buffer_size = self::DEFAULT_BUFFER_SIZE)
	{
		$this->file = $file;
		$this->buffer_max_size = $buffer_size;
	}
	
	public function append($content)
	{
		if ($this->will_exceed_buffer_size($content))
		{
			$this->flush();
			$this->buffer = $content;
		}
		else
		{
			$this->append_to_buffer($content);
		}
	}
	
	private function will_exceed_buffer_size($content)
	{
		return strlen($this->buffer) + strlen($content) > $this->buffer_max_size;
	}
	
	public function flush()
	{
		$this->file->append($this->buffer);
		
	}
	
	private function append_to_buffer($content)
	{
		$this->buffer .= $content;
	}
}
?>