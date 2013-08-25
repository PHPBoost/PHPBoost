<?php
/*##################################################
 *                   		SEOMetaData.class.php
 *                            -------------------
 *   begin                : October 03, 2012
 *   copyright            : (C) 2012 Kevin MASSY
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
 * @desc This class manage the meta tags ans title for the SEO
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 */
class SEOMetaData
{
	private $title;
	private $description;
	private $keywords;
	
	public function __construct($description, $keywords)
	{
		$this->description = $description;
		$this->keywords = $keywords;
	}
	
	public function set_title($title)
	{
		$this->title = $title;
	}
	
	public function get_title()
	{
		return $this->title;
	}
	
	public function get_full_title()
	{
		if (Environment::get_running_module_name())
		{
			return $this->title . ' - ' . GeneralConfig::load()->get_site_name();
		}
		else
		{
			// HomePage
			return GeneralConfig::load()->get_site_name() . ' - ' . $this->title;
		}
	}
	
	public function set_description($description)
	{
		$this->description = $description;
	}
	
	public function complete_description($additional_description)
	{
		$this->description = $this->description . ' ' . $additional_description;
	}
	
	public function get_description()
	{
		return $this->description;
	}
	
	public function get_full_description()
	{
		if (Environment::get_running_module_name())
		{
			return $this->description . ' ' . $this->get_title();
		}
		else
		{
			// HomePage
			return $this->description;
		}
	}
	
	public function set_keywords($keywords)
	{
		$this->keywords = $keywords;
	}
	
	public function add_keyword($keyword)
	{
		$this->keywords = $this->keywords . ', ' . $keyword;
	}
	
	public function get_keywords()
	{
		return $this->keywords;
	}
}
?>