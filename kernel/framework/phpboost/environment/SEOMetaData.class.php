<?php
/**
 * This class manage the meta tags ans title for the SEO
 * @package     PHPBoost
 * @subpackage  Environment
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 10 19
 * @since       PHPBoost 3.0 - 2012 10 03
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor janus57 <janus57@janus57.fr>
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
		$this->title = $title . ($page > 1 ? ' - ' . LangLoader::get_message('common.page', 'common-lang') . ' ' . $page : '');

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
		$this->description = $description . ($page > 1 ? ' (' . TextHelper::lcfirst(LangLoader::get_message('common.page', 'common-lang')) . ' ' . $page . ')' : '');
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
		/* For PHP8.1 see : https://wiki.php.net/rfc/deprecate_null_to_scalar_internal_arg */
		if ($this->description === null)
			$this->description = '';
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
