<?php
/*##################################################
/**
 *                         ThemeConfiguration.class.php
 *                            -------------------
 *   begin                : April 10, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 * @package {@package}
 */
class ThemeConfiguration
{
	private $name;
	private $author_name;
	private $author_mail;
	private $author_link;
	private $version;
	private $description = '';
	private $date;
	private $compatibility;
	private $require_copyright = false;
	private $html_version = '1.0 Strict';
	private $css_version = 2.1;
	private $columns_disabled = null;
	private $main_color = '';
	private $variable_width;
	private $width;
	private $pictures;
	private $repository;
	
	public function __construct($config_ini_file, $desc_ini_file)
	{
		$this->load_configuration($config_ini_file);
		$this->load_description($desc_ini_file);
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
	
	public function get_version()
	{
		return $this->version;
	}
	
	public function get_description()
	{
		return $this->description;
	}
	
	public function get_compatibility()
	{
		return $this->compatibility;
	}
	
	public function get_require_copyright()
	{
		return $this->require_copyright;
	}
	
	public function get_html_version()
	{
		return $this->html_version;
	}
	
	public function get_css_version()
	{
		return $this->css_version;
	}
	
	public function get_columns_disabled()
	{
		return $this->columns_disabled;
	}
	
	public function get_main_color()
	{
		return $this->main_color;
	}
	
	public function get_variable_width()
	{
		return $this->variable_width;
	}
	
	public function get_width()
	{
		return $this->width;
	}
	
	public function get_pictures()
	{
		return $this->pictures;
	}
	
	public function get_first_picture()
	{
		if (isset($this->pictures[0]))
		{
			return $this->pictures[0];
		}
		return '';
	}
	
	public function get_repository()
	{
		return $this->repository;
	}
	
	private function load_configuration($config_ini_file)
	{
		$config = @parse_ini_file($config_ini_file);
		
		if ($config === false)
		{
			throw new IOException('Load ini file "'. $config_ini_file .'" failed');
		}
		
		$this->check_parse_ini_file($config, $config_ini_file);
		
		$this->author_name = $config['author'];
		$this->author_mail = $config['author_mail'];
		$this->author_link = $config['author_link'];
		$this->version = $config['version'];
		$this->date = isset($config['date']) ? $config['date'] : '';
		$this->compatibility = $config['compatibility'];
		$this->require_copyright = (bool)$config['require_copyright'];
		$this->html_version = isset($config['html_version']) ? $config['html_version'] : '';
		$this->css_version = isset($config['css_version']) ? $config['css_version'] : '';
		$this->columns_disabled = isset($config['columns_disabled']) ? $this->parse_columns_disabled_array($config['columns_disabled']) : new ColumnsDisabled();
		$this->variable_width = (bool)$config['variable_width'];
		$this->width = isset($config['width']) ? $config['width'] : '';
		$this->pictures = isset($config['pictures']) ? $this->parse_pictures_array($config['pictures']) : array();
		$this->repository = !empty($config['repository']) ? $config['repository'] : Updates::PHPBOOST_OFFICIAL_REPOSITORY;

	}

	private function load_description($desc_ini_file)
	{
		$desc = @parse_ini_file($desc_ini_file);
		$this->check_parse_ini_file($desc, $desc_ini_file);
		$this->name = $desc['name'];
		$this->description = $desc['desc'];
		$this->main_color = $desc['main_color'];
	}

	private function parse_pictures_array($pictures)
	{
		return explode(',', $pictures);
	}
	
	private function parse_columns_disabled_array($columns_disabled)
	{
		$columns_disabled_array = explode(',', $columns_disabled);
		$columns_disabled_class = new ColumnsDisabled();
		$columns_disabled_class->set_columns_disabled($columns_disabled_array);
		
		return $columns_disabled_class;
	}
	
	private function check_parse_ini_file($parse_result, $ini_file)
	{
		if ($parse_result === false)
		{
			throw new Exception('Parse ini file "' . $ini_file . '" failed');
		}
	}
}
?>