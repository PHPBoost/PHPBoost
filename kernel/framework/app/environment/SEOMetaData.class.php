<?php
/**
 * This class manage the meta tags ans title for the SEO
 * @package     PHPBoost
 * @subpackage  Environment
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 20
 * @since       PHPBoost 3.0 - 2012 10 03
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @author      janus57 <janus57@janus57.fr>
*/

class SEOMetaData
{
	private mixed $title = null;
	private mixed $full_title = null;
	private mixed $description = null;
	private ?Url $canonical_url = null;
	private ?Url $picture_url = null;
	private string $page_type = 'website';
	private array $additionnal_properties = [];

	public function set_title(string $title, string $section = '', int $page = 1): void
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

	public function get_title(): mixed
	{
		return $this->title;
	}

	public function get_full_title(): mixed
	{
		return $this->full_title;
	}

	public function get_description(): mixed
	{
		return $this->description;
	}

	public function set_description(string $description, $page = 1): void
	{
		$this->description = $description . ($page > 1 ? ' (' . TextHelper::lcfirst(LangLoader::get_message('common.page', 'common-lang')) . ' ' . $page . ')' : '');
	}

	public function complete_description(string $additional_description): void
	{
		$this->description = $this->description . ' ' . $additional_description;
	}

	public function get_full_description(): string
	{
		/** For PHP8.1 : @see https://wiki.php.net/rfc/deprecate_null_to_scalar_internal_arg */
		if ($this->description === null)
        {
			$this->description = '';
        }
		if (Environment::home_page_running())
        {
			return GeneralConfig::load()->get_site_description();
        }
		else
        {
			return strip_tags($this->description);
        }
	}

	public function set_canonical_url(Url $canonical_url): void
	{
		$this->canonical_url = $canonical_url;
	}

	public function canonical_link_exists(): bool
	{
		return $this->canonical_url !== null;
	}

	public function get_canonical_link(): mixed
	{
		if ($this->canonical_url !== null)
        {
			return $this->canonical_url->absolute();
        }
        return null;
	}

	public function set_picture_url(Url $picture_url): void
	{
		$this->picture_url = $picture_url;
	}

	public function picture_url_exists(): bool
	{
		return $this->picture_url !== null || ContentManagementConfig::load()->get_site_default_picture_url()->absolute();
	}

	public function get_picture_url(): mixed
	{
		$site_default_picture_url = ContentManagementConfig::load()->get_site_default_picture_url();
		if ($this->picture_url !== null)
        {
			return $this->picture_url->absolute();
        }
		else if ($site_default_picture_url->absolute())
        {
			return $site_default_picture_url->absolute();
        }
        return null;
	}

	public function set_page_type(mixed $page_type): void
	{
		$this->page_type = $page_type;
	}

	public function get_page_type(): string
	{
		return $this->page_type;
	}

	public function set_additionnal_properties(array $additionnal_properties): void
	{
		$this->additionnal_properties = $additionnal_properties;
	}

	public function get_additionnal_properties(): array
	{
		return $this->additionnal_properties;
	}
}
?>
