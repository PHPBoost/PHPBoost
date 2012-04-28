<?php
/*##################################################
 *                         LangConfiguration.class.php
 *                            -------------------
 *   begin                : January 19, 2012
 *   copyright            : (C) 2012 Bruno MERCIER
 *   email                : aiglobulles@gmail.com
 *
 *
 *###################################################
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
 *###################################################
 */

 /**
 * @author Bruno MERCIER <aiglobulles@gmail.com>
 * @package {@package}
 */
class LangConfiguration
{
	private $name;
	private $author_name;
	private $author_mail;
	private $author_link;
	private $date;
	private $version;
	private $compatibility;
	
	public function __construct($config_ini_file)
	{
		$this->load_configuration($config_ini_file);
	}
	
	public function get_name()
	{
		return $this->name;
	}
	
	public function get_author_name()
	{
		return $this->author_name;
	}
	
	public function get_author_mail()
	{
		return $this->author_mail;
	}
	
	public function get_author_link()
	{
		return $this->author_link;
	}
	
	public function get_date()
	{
		return $this->date;
	}

	public function get_version()
	{
		return $this->version;
	}	
	
	public function get_compatibility()
	{
		return $this->compatibility;
	}
	
	
	private function load_configuration($config_ini_file)
	{
		$config = @parse_ini_file($config_ini_file);
		$this->check_parse_ini_file($config, $config_ini_file);
		
		$this->name = $config['name'];
		$this->author_name = $config['author'];
		$this->author_mail = $config['author_mail'];
		$this->author_link = $config['author_link'];
		$this->date = $config['date'];
		$this->version = $config['version'];
		$this->compatibility = $config['compatibility'];
	}

	private function check_parse_ini_file($parse_result, $ini_file)
	{
		if ($parse_result === false)
		{
			throw new IOException('Parse ini file "' . $ini_file . '" failed');
		}
	}
}
?>