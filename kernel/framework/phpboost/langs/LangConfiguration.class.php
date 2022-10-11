<?php
/**
 * @package     PHPBoost
 * @subpackage  Langs
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Bruno MERCIER <aiglobulles@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2022 10 11
 * @since       PHPBoost 3.0 - 2012 01 19
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
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
	private $identifier;
	private $repository;
	private $picture_url;

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

	public function get_identifier()
	{
		return $this->identifier;
	}

	public function get_repository()
	{
		return $this->repository;
	}

	public function get_picture_url()
	{
		return $this->picture_url;
	}

	public function has_picture()
	{
		$picture = $this->picture_url->rel();
		return !empty($picture);
	}

	private function load_configuration($config_ini_file)
	{
		$config = @parse_ini_file($config_ini_file);
		$this->check_parse_ini_file($config, $config_ini_file);

		$this->name = $config['name'];
		$this->author_name = $config['author'];
		$this->author_mail = $config['author_mail'];
		$this->author_link = $config['author_link'];
		$this->date = $config['creation_date'];
		$this->version = $config['version'];
		$this->compatibility = $config['compatibility'];
		$this->identifier = $config['identifier'];
		$this->repository = !empty($config['repository']) ? $config['repository'] : Updates::PHPBOOST_OFFICIAL_REPOSITORY;

		$url = '/images/stats/countries/' . $this->identifier . '.png';
		$picture = new File(PATH_TO_ROOT . $url);
		if (!$picture->exists())
			$url = '';

		$this->picture_url = new Url($url);
	}

	private function check_parse_ini_file($parse_result, $ini_file)
	{
		if ($parse_result === false)
		{
			throw new IOException('Parse ini file "' . $ini_file . '" failed');
		}
	}

	public function get_properties()
	{
		return array(
			'name'          => $this->name,
			'author_name'   => $this->author_name,
			'author_mail'   => $this->author_mail,
			'author_link'   => $this->author_link,
			'date'          => $this->date,
			'version'       => $this->version,
			'compatibility' => $this->compatibility,
			'identifier'    => $this->identifier,
			'picture_url'   => $this->picture_url->rel()
		);
	}
}
?>
