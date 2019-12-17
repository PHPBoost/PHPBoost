<?php
/**
 * @package     PHPBoost
 * @subpackage  Langs
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Bruno MERCIER <aiglobulles@gmail.com>
 * @version     PHPBoost 5.3 - last update: 2019 03 21
 * @since       PHPBoost 3.0 - 2012 01 19
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
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
		$this->date = $config['date'];
		$this->version = $config['version'];
		$this->compatibility = $config['compatibility'];
		$this->identifier = $config['identifier'];

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
}
?>
