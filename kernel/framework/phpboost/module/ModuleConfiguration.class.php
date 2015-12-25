<?php
/*##################################################
/**
 *                         ModuleConfiguration.class.php
 *                            -------------------
 *   begin                : December 12, 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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
 * @author Loic Rouchon <loic.rouchon@phpboost.com>
 * @package {@package}
 */
class ModuleConfiguration
{
	private $name;
	private $description;
	private $author;
	private $author_email;
	private $author_website;
	private $version;
	private $date;
	private $compatibility;
	private $php_version;
	private $repository;
	private $admin_main_page;
	private $admin_menu;
	private $admin_links;
	private $home_page;
	private $contribution_interface;
	private $url_rewrite_rules;

	public function __construct($config_ini_file, $desc_ini_file)
	{
		$this->load_configuration($config_ini_file);
		$this->load_description($desc_ini_file);
	}

	public function get_name()
	{
		return $this->name;
	}

	public function get_description()
	{
		return $this->description;
	}

	public function get_author()
	{
		return $this->author;
	}

	public function get_author_email()
	{
		return $this->author_email;
	}

	public function get_author_website()
	{
		return $this->author_website;
	}

	public function get_version()
	{
		return $this->version;
	}

	public function get_date()
	{
		return $this->date;
	}

	public function get_compatibility()
	{
		return $this->compatibility;
	}

	public function get_php_version()
	{
		return $this->php_version;
	}

	public function get_repository()
	{
		return $this->repository;
	}

	public function get_admin_main_page()
	{
		return $this->admin_main_page;
	}

	public function get_admin_menu()
	{
		return $this->admin_menu;
	}

	public function get_admin_links()
	{
		return $this->admin_links;
	}

	public function get_home_page()
	{
		return $this->home_page;
	}

	public function get_contribution_interface()
	{
		return $this->contribution_interface;
	}

	public function get_mini_modules()
	{
		return $this->mini_modules;
	}

	public function get_url_rewrite_rules()
	{
		return $this->url_rewrite_rules;
	}

	private function load_configuration($config_ini_file)
	{
		$config = parse_ini_file($config_ini_file);
		$this->check_parse_ini_file($config, $config_ini_file);

		$this->author = $config['author'];
		$this->author_email = $config['author_mail'];
		$this->author_website = $config['author_website'];
		$this->version = $config['version'];
		$this->date = $config['date'];
		$this->compatibility = $config['compatibility'];
		$this->php_version = !empty($config['php_version']) ? $config['php_version'] : ServerConfiguration::MIN_PHP_VERSION;
		$this->repository = !empty($config['repository']) ? $config['repository'] : Updates::PHPBOOST_OFFICIAL_REPOSITORY;
		$this->admin_main_page = !empty($config['admin_main_page']) ? $config['admin_main_page'] : '';
		$this->admin_menu = !empty($config['admin_menu']) ? $config['admin_menu'] : '';
		$this->home_page = !empty($config['home_page']) ? $config['home_page'] : '';
		$this->contribution_interface = !empty($config['contribution_interface']) ? $config['contribution_interface'] : '';
		$this->url_rewrite_rules = !empty($config['rewrite_rules']) ? $config['rewrite_rules'] : array();
	}

	private function load_description($desc_ini_file)
	{
		$desc = @parse_ini_file($desc_ini_file);
		$this->check_parse_ini_file($desc, $desc_ini_file);
		$this->name = $desc['name'];
		$this->description = $desc['desc'];
		$this->admin_links = !empty($desc['admin_links']) ? $this->parse_ini_array($desc['admin_links']) : array();
	}

	private function check_parse_ini_file($parse_result, $ini_file)
	{
		if ($parse_result === false)
		{
			throw new IOException('Parse ini file ' . $ini_file . ' failed');
		}
	}

	/**
	 * @desc Parses a table written in a special syntax which is user-friendly and can be inserted in a ini file (PHP serialized arrays cannot be inserted because they contain the " character).
	 * The syntax is very easy, it really looks like the PHP array declaration: key => value, key2 => value2
	 * You can nest some elements: key => (key1 => value1, key2 => value2), key2 => value2
	 * @param string $links_format Serialized array
	 * @return string[] The unserialized array.
	 */
	private function parse_ini_array($links_format)
	{
		$links_format = preg_replace('` ?=> ?`', '=', $links_format);
		$links_format = preg_replace(' ?, ?', ',', $links_format) . ' ';
		list($key, $value, $open, $cursor, $check_value, $admin_links) = array('', '', '', 0, false, array());
		$string_length = strlen($links_format);
		while ($cursor < $string_length) //Parcours linéaire.
		{
			$char = substr($links_format, $cursor, 1);
			if (!$check_value) //On récupère la clé.
			{
				if ($char != '=')
				{
					$key .= $char;
				}
				else
				{
					$check_value =  true;
				}
			}
			else //On récupère la valeur associé à la clé, une fois celle-ci récupérée.
			{
				if ($char == '(') //On marque l'ouverture de la parenthèse.
				{
					$open = $key;
				}

				if ($char != ',' && $char != '(' && $char != ')' && ($cursor+1) < $string_length) //Si ce n'est pas un caractère délimiteur, on la fin => on concatène.
				{
					$value .= $char;
				}
				else
				{
					if (!empty($open) && !empty($value)) //On insère dans la clé marqué précédemment à l'ouveture de la parenthèse.
					{
						$admin_links[$open][$key] = $value;
					}
					else
					{
						$admin_links[$key] = $value; //Ajout simple.
					}
					list($key, $value, $check_value) = array('', '', false);
				}
				if ($char == ')')
				{
					$open = ''; //On supprime le marqueur.
					$cursor++; //On avance le curseur pour faire sauter la virugle après la parenthèse.
				}
			}
			$cursor++;
		}
		return $admin_links;
	}
}
?>