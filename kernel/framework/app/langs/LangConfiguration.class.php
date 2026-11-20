<?php
/**
 * @package     PHPBoost
 * @subpackage  Langs
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Bruno MERCIER <aiglobulles@gmail.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 20
 * @since       PHPBoost 3.0 - 2012 01 19
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class LangConfiguration
{
	private string $addon_type = '';
	private string $name = '';
	private string $author_name = '';
	private string $author_mail = '';
	private string $author_link = '';
	private string $date = '';
	private string $version = '';
	private string $compatibility = '';
	private string $identifier = '';
	private string $repository = '';
	private ?Url $picture_url = null;

	public function __construct(string $config_ini_file)
	{
		$this->load_configuration($config_ini_file);
	}

	public function get_addon_type(): string
	{
		return $this->addon_type;
	}

	public function get_name(): string
	{
		return $this->name;
	}

	public function get_author_name(): string
	{
		return $this->author_name;
	}

	public function get_author_mail(): string
	{
		return $this->author_mail;
	}

	public function get_author_link(): string
	{
		return $this->author_link;
	}

	public function get_date(): string
	{
		return $this->date;
	}

	public function get_version(): string
	{
		return $this->version;
	}

	public function get_compatibility(): string
	{
		return $this->compatibility;
	}

	public function get_identifier(): string
	{
		return $this->identifier;
	}

	public function get_repository(): string
	{
		return $this->repository;
	}

	public function get_picture_url(): Url
	{
		return $this->picture_url;
	}

	public function has_picture(): bool
	{
		$picture = $this->picture_url->rel();
		return !empty($picture);
	}

	private function load_configuration(string $config_ini_file): void
	{
		$config = @parse_ini_file($config_ini_file);
		$this->check_parse_ini_file($config, $config_ini_file);

		$this->addon_type      = isset($config['addon_type']) ? $config['addon_type'] : '';
		$this->name            = isset($config['name']) ? $config['name'] : '';
		$this->author_name     = isset($config['author']) ? $config['author'] : '';
		$this->author_mail     = isset($config['author_mail']) ? $config['author_mail'] : '';
		$this->author_link     = isset($config['author_link']) ? $config['author_link'] : '';
		$this->date            = isset($config['creation_date']) ? $config['creation_date'] : '';
		$this->version         = isset($config['version']) ? $config['version'] : '';
		$this->compatibility   = isset($config['compatibility']) ? $config['compatibility'] : '';
		$this->identifier      = isset($config['identifier']) ? $config['identifier'] : '';
		$this->repository      = isset($config['repository']) ? (!empty($config['repository']) ? $config['repository'] : Updates::PHPBOOST_OFFICIAL_REPOSITORY) : '';

		$url = '/images/stats/countries/' . $this->identifier . '.png';
		$picture = new File(PATH_TO_ROOT . $url);
		if (!$picture->exists())
        {
			$url = '';
        }

		$this->picture_url = new Url($url);
	}

	private function check_parse_ini_file(mixed $parse_result, string $ini_file): void
	{
		if ($parse_result === false)
		{
			throw new IOException('Parse ini file "' . $ini_file . '" failed');
		}
	}

	public function get_properties(): array
	{
		return [
			'addon_type'    => $this->addon_type,
			'name'          => $this->name,
			'author_name'   => $this->author_name,
			'author_mail'   => $this->author_mail,
			'author_link'   => $this->author_link,
			'date'          => $this->date,
			'version'       => $this->version,
			'compatibility' => $this->compatibility,
			'identifier'    => $this->identifier,
			'picture_url'   => $this->picture_url->rel()
		];
	}
}
?>
