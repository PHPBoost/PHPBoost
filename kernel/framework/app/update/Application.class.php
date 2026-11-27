<?php
/**
 * @package     PHPBoost
 * @subpackage  Update
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 27
 * @since       PHPBoost 2.0 - 2008 08 17
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class Application
{
	private string $id = '';
	private string $name = '';
	private string $language = '';
	private string $localized_language = '';
	private string $type = '';

	private string $repository = '';

	private string $version = '';
	private string $compatibility_min = '';
	private string $compatibility_max = '';
	private ?Date $pubdate = null;
	private ?string $priority = null;
	private bool $security_update = false;

	private string $download_url = '';
	private string $update_url = '';
	private string $autoupdate_url = '';

	private array $authors = [];

	private string $description = '';
	private array $new_features = [];
	private array $improvements = [];
	private array $bug_corrections = [];
	private array $security_improvements = [];

	private string $warning_level = '';
	private string $warning = '';

	const KERNEL_TYPE = 'kernel';
	const MODULE_TYPE = 'module';
	const TEMPLATE_TYPE = 'template';

	/**
	 * constructor of the class
	 * @param $id
	 * @param $language
	 * @param $type  see APPLICATION_TYPE_xxx constants
	 * @param $version
	 * @param $repository
	 */
	public function __construct(string $id, string $language, string $type = self::MODULE_TYPE, string $version = '0', string $repository = '')
	{
		$this->id = $id;
		$this->name = $id;
		$this->language = $language;
		$this->type = $type;

		$this->repository = $repository;

		$this->version = $version;

		$this->pubdate = new Date();
	}
	/**
	 * Loads an XML description
	 * @param $xml_desc reference to xml description
	 */
	public function load(mixed $xml_desc): void
	{
		$attributes = $xml_desc->attributes();

		$name = self::get_attribute($xml_desc, 'name');
		$this->name = !empty($name) ? $name : $this->id;

		$this->language = self::get_attribute($xml_desc, 'language');

		$language = self::get_attribute($xml_desc, 'localized_language');
		$this->localized_language = !empty($language) ? ($language) : $this->language;

		$this->version = self::get_attribute($xml_desc, 'num');

		$this->compatibility_min = self::get_attribute($xml_desc, 'min', 'compatibility');
		$this->compatibility_max = self::get_attribute($xml_desc, 'max', 'compatibility');

		$pubdate = self::get_attribute($xml_desc, 'pubdate');
		if (!empty($pubdate))
		{
			$this->pubdate = new Date($pubdate);
		}
		else
		{
			$this->pubdate = new Date();
		}

		$this->security_update = self::get_attribute($xml_desc, 'security-update');
		$this->security_update = TextHelper::strtolower($this->security_update) == 'true';

		$this->priority = self::get_attribute($xml_desc, 'priority');
		switch ($this->priority)
		{
			case 'high':
				$this->priority = AdministratorAlert::ADMIN_ALERT_HIGH_PRIORITY;
				break;
			case 'medium':
				$this->priority = AdministratorAlert::ADMIN_ALERT_MEDIUM_PRIORITY;
				break;
			default:
				$this->priority = AdministratorAlert::ADMIN_ALERT_LOW_PRIORITY;
				break;
		}
		if ($this->security_update)
        {
            $this->priority++;
        }

		$this->download_url = self::get_attribute($xml_desc, 'url', 'download');
		$this->update_url = self::get_attribute($xml_desc, 'url', 'update');
		$this->autoupdate_url = self::get_attribute($xml_desc, 'url', 'autoupdate');

		$this->authors = [];
		$authors_elts = $xml_desc->xpath('authors/author');
		foreach ($authors_elts as $author)
		{
			$this->authors[] = [
				'name'  => self::get_attribute($author, 'name'),
				'email' => self::get_attribute($author, 'email')
			];
		}

		$this->description = (string) $xml_desc->description;

		$this->new_features = [];
		$this->improvements = [];
		$this->bug_corrections = [];
		$this->security_improvements = [];

		$novelties = $xml_desc->xpath('whatsnew/new');
		foreach ($novelties  as $novelty )
		{
			$attributes = $novelty->attributes();
			$type = isset($attributes['type']) ? $attributes['type'] : 'feature';
			switch ($type)
			{
				case 'improvement':
					$this->improvements[] = (string) $novelty;
					break;
				case 'bug':
					$this->bug_corrections[] = (string) $novelty;
					break;
				case 'security':
					$this->security_improvements[] = (string) $novelty;
					break;
				default:
					$this->new_features[] = (string) $novelty;
					break;
			}
		}

		$this->warning_level = self::get_attribute($xml_desc, 'level', 'warning');
		if (!empty($this->warning_level))
		{
			$this->warning = (string) $xml_desc->warning;
		}
	}

    /**
	 * Gets an identifier of the application
	 * @return string identifier
	 */
	public function get_identifier(): string
	{
		return md5($this->type . '_' . $this->id . '_' . $this->version . '_' . $this->language);
	}
	/**
	 * Checks compatibility with limits
	 * @return boolean TRUE if compatible FALSE if not
	 */
	public function check_compatibility(): bool
	{
		$current_version = $this->get_installed_version();

		if ($current_version == '0')
		{
			return false;
		}

		$phpboost_version = Environment::get_phpboost_version();

		return version_compare($current_version, $this->get_version(), '<') > 0 &&
		(($phpboost_version >= $this->compatibility_min) && ($this->compatibility_max == null ||
		($phpboost_version <= $this->compatibility_max && $this->compatibility_max >= $this->compatibility_min)));
	}

	## PUBLIC ACCESSORS ##
	/**
	 * Accessor of id
	 */
	public function get_id(): string { return $this->id; }
	/**
	 * Accessor of name
	 */
	public function get_name(): string { return $this->name; }
	/**
	 * Accessor of Language
	 */
	public function get_language(): string { return $this->language; }
	/**
	 * Accessor of Localized language
	 */
	public function get_localized_language(): string { return !empty($this->localized_language) ? $this->localized_language : $this->language; }
	/**
	 * Accessor of Type
	 */
	public function get_type(): string { return $this->type; }
	/**
	 * Accessor of Repository
	 */
	public function get_repository(): string { return $this->repository; }
	/**
	 * Accessor of Version
	 */
	public function get_version(): string { return $this->version; }
	/**
	 * Accessor of Compatibility min value
	 */
	public function get_compatibility_min(): string { return $this->compatibility_min; }
	/**
	 * Accessor of Compatibility Max value
	 */
	public function get_compatibility_max(): string { return $this->compatibility_max; }
	/**
	 * Accessor of Publication Date
	 */
	public function get_pubdate(): Date|string { return !empty($this->pubdate) && is_object($this->pubdate) ? $this->pubdate->format(Date::FORMAT_DAY_MONTH_YEAR, Timezone::USER_TIMEZONE) : ''; }
	/**
	 * Accessor of Priority
	 */
	public function get_priority(): int { return $this->priority; }
	/**
	 * Accessor of Security Update
	 */
	public function get_security_update(): string { return $this->security_update; }
	/**
	 * Accessor of Download URL
	 */
	public function get_download_url(): string { return $this->download_url; }
	/**
	 * Accessor of Update URL
	 */
	public function get_update_url(): string { return $this->update_url; }
	/**
	 * Accessor of Auto Update URL
	 */
	public function get_autoupdate_url(): string { return $this->autoupdate_url; }
	/**
	 * Accessor of Authors
	 */
	public function get_authors(): array { return $this->authors; }
	/**
	 * Accessor of Description Text
	 */
	public function get_description(): string { return $this->description; }
	/**
	 * Accessor of New Features Text
	 */
	public function get_new_features(): array { return $this->new_features; }
	/**
	 * Accessor of improvements Text
	 */
	public function get_improvements(): array { return $this->improvements; }
	/**
	 * Accessor of Bug Corrections Text
	 */
	public function get_bug_corrections(): array { return $this->bug_corrections; }
	/**
	 * Accessor of Security improvements
	 */
	public function get_security_improvements(): array { return $this->security_improvements; }
	/**
	 * Accessor of Warning level
	 */
	public function get_warning_level(): string { return $this->warning_level; }
	/**
	 * Accessor of Warning
	 */
	public function get_warning(): string { return $this->warning; }

	## PRIVATE METHODS ##
	/**
	 * Get attribute of xml token
	 * @param $xdoc reference to xml document
	 * @param $attibute_name name of the attribute
	 * @param $xpath_query
	 * @return string attrbute value
	 */
	private function get_attribute(mixed $xdoc, string $attibute_name, string $xpath_query = '.'): string
	{
		$elements = $xdoc->xpath($xpath_query);
		if (count($elements) > 0)
		{
			$attributes = $elements[0]->attributes();
			return isset($attributes[$attibute_name]) ? (string) $attributes[$attibute_name] : '';
		}
		return '';
	}

	/**
	 * Get the number of the installed version
	 * @return string version
	 */
	private function get_installed_version(): string
	{
		switch ($this->type)
		{
			case self::KERNEL_TYPE:
				return Environment::get_phpboost_version();
			case self::MODULE_TYPE:
				if (ModulesManager::is_module_installed($this->id))
				{
					return ModulesManager::get_module($this->id)->get_configuration()->get_version();
				}
				return '0';
			case self::TEMPLATE_TYPE:
				if (ThemesManager::get_theme_existed($this->id))
				{
					return ThemesManager::get_theme($this->id)->get_configuration()->get_version();
				}
				return '0';
			default:
				return '0';
		}
	}
}
?>
