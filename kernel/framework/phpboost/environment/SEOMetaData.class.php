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
	private $full_title;
	private $description;
	private $canonical_url;
	private $picture_url;
	private $page_type = 'website';
	private $additionnal_properties = array();
	
	public function set_title($title, $section = '', $page = 1)
	{
		$this->title = $title . ($page > 1 ? ' - ' . LangLoader::get_message('page', 'main') . ' ' . $page : '');

		if (!Environment::home_page_running())
		{
			$this->full_title = (empty($section) ? $this->title : $this->title . ' - ' . $section);
		}
		else
		{
			// HomePage
			$this->full_title = GeneralConfig::load()->get_site_name() . ' - ' . $this->title;
		}
	}
	
	public function get_title()
	{
		return $this->title;
	}
	
	public function get_full_title()
	{
		return $this->full_title;
	}
	
	public function set_description($description, $page = 1)
	{
		$this->description = $description . ($page > 1 ? ' (' . TextHelper::lcfirst(LangLoader::get_message('page', 'main')) . ' ' . $page . ')' : '');
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
		if (Environment::home_page_running())
			return GeneralConfig::load()->get_site_description();
		else
			return strip_tags($this->description);
	}
	
	public function set_canonical_url(Url $canonical_url)
	{
		$this->canonical_url = $canonical_url;
	}
	
	public function canonical_link_exists()
	{
		return $this->canonical_url !== null;
	}
	
	public function get_canonical_link()
	{
		if ($this->canonical_url !== null)
			return $this->canonical_url->absolute();
	}

	public function set_picture_url(Url $picture_url)
	{
		$this->picture_url = $picture_url;
	}
	
	public function picture_url_exists()
	{
		return $this->picture_url !== null || ContentManagementConfig::load()->get_site_default_picture_url()->absolute();
	}
	
	public function get_picture_url()
	{
		$site_default_picture_url = ContentManagementConfig::load()->get_site_default_picture_url();
		if ($this->picture_url !== null)
			return $this->picture_url->absolute();
		else if ($site_default_picture_url->absolute())
			return $site_default_picture_url->absolute();
	}

	public function set_page_type($page_type)
	{
		$this->page_type = $page_type;
	}
	
	public function get_page_type()
	{
		return $this->page_type;
	}

	public function set_additionnal_properties(array $additionnal_properties)
	{
		$this->additionnal_properties = $additionnal_properties;
	}
	
	public function get_additionnal_properties()
	{
		return $this->additionnal_properties;
	}
}
?>
