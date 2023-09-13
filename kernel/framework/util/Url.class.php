<?php
/**
 * This class offers a simple way to transform an absolute or relative link
 * to a relative one to the website root.
 * It can also deals with absolute url and will convert only those from this
 * site into relatives ones.
 * Usage :
 * <ul>
 *   <li>In content, get the url with the absolute() method. It will allow content include at multiple level</li>
 *   <li>In forms, get the url with the relative() method. It's a faster way to display url</li>
 * </ul>
 * @package     Util
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 09 13
 * @since       PHPBoost 2.0 - 2009 01 14
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor janus57 <janus57@janus57.fr>
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

define('SERVER_URL', $_SERVER['PHP_SELF']);

class Url
{
	const FORBID_JS_REGEX = '(?!javascript:)';
	const PROTOCOL_REGEX = '[a-z0-9-_]+(?::[a-z0-9-_]+)*://';
	const USER_REGEX = '[a-z0-9-_]+(?::[a-z0-9-_]+)?@';
	const DOMAIN_REGEX = '(?:[a-z0-9-_~]+\.)*[a-z0-9-_~]+(?::[0-9]{1,5})?/';
	const FOLDERS_REGEX = '/*(?:[A-Za-z0-9~_\.+@,-]+/+)*';
	const FILE_REGEX = '[A-Za-z0-9-+_,~:/\.\%!=]+';
	const ARGS_REGEX = '(/([\w/_\.#-]*(\?)?(\S+)?[^\.\s])?)?';

	const STATUS_OK = 200;

	private static $root = TPL_PATH_TO_ROOT;
	private static $server = SERVER_URL;

	private $url = '';
	private $is_relative = false;
	private $path_to_root = '';
	private $server_url = '';

	/**
	 * Build a Url object. By default, builds an Url object representing the current path.
	 * If the url is empty, no computation is done and an empty string will be returned
	 * when asking for both relative and absolute form of the url.
	 * @param string $url the url string relative to the current path,
	 * to the website root if beginning with a "/" or an absolute url
	 * @param string $path_to_root url context. default is PATH_TO_ROOT
	 */
	public function __construct($url = '.', $path_to_root = null, $server_url = null)
	{
		if (!empty($url))
		{
			if ($path_to_root !== null)
			{
				$this->path_to_root = $path_to_root;
			}
			else
			{
				$this->path_to_root = self::$root;
			}

			if ($server_url !== null)
			{
				$this->server_url = $server_url;
			}
			else
			{
				$this->server_url = self::$server;
			}

			$anchor = '';
			if (($pos = TextHelper::strpos($url, '#')) !== false)
			{
				// Backup url arguments in order to restore them after compression
				if ($pos == 0)
				{
					// anchor to the current page
					$this->url = $url;
					$this->is_relative = false; // forbids all url transformations
					return;
				}
				else
				{
					$anchor = TextHelper::substr($url, $pos);
					$url = TextHelper::substr($url, 0, $pos);
				}
			}

			if (preg_match('`^[a-z0-9]+\:(?!//).+`iuU', $url) > 0)
			{	// This is a special protocol link and we don't try to convert it.
				$this->url = $url;
				return;
			}
			else if (TextHelper::strpos($url, 'www.') === 0)
			{   // If the url begins with 'www.', it's an absolute one
				$url = 'http://' . $url;
			}

			if (AppContext::get_request()->get_site_domain_name())
				$url = preg_replace('`^https?://' . AppContext::get_request()->get_site_domain_name() . GeneralConfig::load()->get_site_path() . '`uUi', '', self::compress($url));
			
			if (!TextHelper::strpos($url, '://') && TextHelper::substr($url, 0, 2) != '//')
			{
				$this->is_relative = true;
				if (TextHelper::substr($url, 0, 1) == '/')
				{   // Relative url from the website root (good form)
					$this->url = $url;
				}
				else
				{   // The url is relative to the current folder
					$this->url = $this->root_to_local() . $url;
				}
			}
			else
			{
				$this->is_relative = false;
				$this->url = $url;
			}
			$this->url = self::compress($this->url) . $anchor;
		}
	}

	/**
	 * @return bool true if the url is a relative one
	 */
	public function is_relative()
	{
		return $this->is_relative;
	}

	/**
	 * Returns the root relative url if defined, else the absolute one
	 * @return string the root relative url if defined, else the absolute one
	 */
	public function relative()
	{
		if ($this->is_relative())
		{
			return $this->url;
		}
		else
		{
			return $this->absolute();
		}
	}

	/**
     * Returns the relative url if defined, else the absolute one
     * @return string the relative url if defined, else the absolute one
     */
	public function rel()
	{
		if ($this->is_relative())
		{
			return TPL_PATH_TO_ROOT . '/' . ltrim($this->relative(), '/');
		}
		else
		{
			return $this->absolute();
		}
	}

	/**
	 * Returns the absolute url
	 * @return string the absolute url
	 */
	public function absolute()
	{
		if ($this->is_relative())
		{
			return self::compress($this->get_absolute_root() . $this->url);
		}
		else
		{
			return $this->url;
		}
	}

	/**
	 * Returns the relative path from the website root to the current path if working on a relative url
	 * @return string the relative path from the website root to the current path if working on a relative url
	 */
	public function root_to_local()
	{
		$local_path = $this->server_url;
		$local_path = TextHelper::substr(trim($local_path, '/'), TextHelper::strlen(trim(GeneralConfig::load()->get_site_path(), '/')));
		$file_begun = TextHelper::strrpos($local_path, '/');
		if ($file_begun >= 0)
		{
			$local_path = TextHelper::substr($local_path, 0, $file_begun) . '/';
		}

		return '/' . ltrim($local_path, '/');
	}

	/**
	 * Prepares a string for it to be used in an URL (with only a-z, 0-9 and - characters).
	 * @param string $string String to encode.
	 * @return string The encoded string.
	 */
	public static function encode_rewrite($url)
	{
		$url = mb_convert_encoding(TextHelper::html_entity_decode($url), 'ISO-8859-1', 'UTF-8');
        $url = TextHelper::strtolower(strtr($url, mb_convert_encoding('²ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöø°ÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ()[]\'"~$&%*@ç!?;,:/\^¨€{}<>«»`|+.= #', 'ISO-8859-1', 'UTF-8'), '2aaaaaaaaaaaaoooooooooooooeeeeeeeecciiiiiiiiuuuuuuuuynn    --      c  ---    e         --- '));
		$url = str_replace(' ', '', $url);
		$url = str_replace('---', '-', $url);
		$url = str_replace('--', '-', $url);
		$url = trim($url,'-');

		return $url;
	}

	/**
	 * Checks the status of an url.
	 * @param string $url Url to check.
	 * @return int The status of the url.
	 */
	public static function check_url_validity($url)
	{
		if (empty($url))
			return false;

		$status = 0;

		if (!($url instanceof Url))
		{
			// Remove all illegal characters from a url
			$url = filter_var($url, FILTER_SANITIZE_URL);

			$url = new Url($url);
		}

		if ($url->absolute())
		{
			// Validate URI
			if (filter_var($url->absolute(), FILTER_VALIDATE_URL) === FALSE	|| !in_array(strtolower(parse_url($url->absolute(), PHP_URL_SCHEME)), ['http','https'], true )) // check only for http/https schemes.
				return false;

			$server_configuration = new ServerConfiguration();
			if ($server_configuration->has_curl_library())
			{
				$curl = curl_init($url->absolute());
				curl_setopt($curl, CURLOPT_NOBODY, true);
				$result = curl_exec($curl);

				if ($result !== false)
				{
					$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
				}
				curl_close($curl);
			}
			else
			{
				if (function_exists('stream_context_set_default'))
					stream_context_set_default(array('http' => array('method' => 'HEAD', 'timeout' => 1)));

				if (function_exists('get_headers'))
				{
					$file_headers = @get_headers($url->absolute(), true);

					if (is_array($file_headers))
					{
						$status_list = array();
						foreach ($file_headers as $header)
						{
							if (!is_array($header) && preg_match('/^HTTP\/[12]\.[01] (\d\d\d)/u', $header, $matches))
								$status_list[] = (int)$matches[1];
						}
						if ($status_list)
							$status = end($status_list);
					}
					else
						$status = self::STATUS_OK;
				}
				else
					$status = self::STATUS_OK;
			}
		}

		return $status == self::STATUS_OK || ($status > self::STATUS_OK && $status < 400) || $status == 429; // 429 status code for Youtube video sometimes
	}

	/**
	 * Retrieves the size of a file in url.
	 * @param string $url Url to check.
	 * @return int The size of the url file.
	 */
	public static function get_url_file_size($url)
	{
		$file_size = 0;

		if (!($url instanceof Url))
			$url = new Url($url);

		$file = new File($url->rel());
		if ($file->exists())
			$file_size = $file->get_file_size();

		if (empty($file_size) && $url->absolute())
		{
			if (function_exists('stream_context_set_default'))
				stream_context_set_default(array('http' => array('method' => 'HEAD', 'timeout' => 1)));

			if (function_exists('get_headers'))
			{
				$file_headers = @get_headers($url->absolute(), true);
				if (isset($file_headers[0]))
				{
					$status = 0;
					if(preg_match('/^HTTP\/[12]\.[01] (\d\d\d)/u', $file_headers[0], $matches))
						$status = (int)$matches[1];

					if ($status == self::STATUS_OK && isset($file_headers['Content-Length']))
						$file_size = $file_headers['Content-Length'];
				}
			}
		}

		return $file_size;
	}

	/**
	 * Compress a url by removing all "folder/.." occurrences
	 * @param string $url the url to compress
	 * @return string the compressed url
	 */
	public static function compress($url)
	{
		$args = '';
		if (($pos = TextHelper::strpos($url, '?')) !== false)
		{
			// Backup url arguments inn order to restore them after compression
			$args = TextHelper::substr($url, $pos);
			$url = TextHelper::substr($url, 0, $pos);
		}
		$url = preg_replace(array('`([^:]|^)/+`u', '`(?<!\.)\./`u'), array('$1/', ''), $url);

		do
		{
			$url = preg_replace('`/?[^/]+/\.\.`u', '', $url);

		}
		while (preg_match('`/?[^/]+/\.\.`u', $url) > 0);
		return $url . $args;
	}

	/**
	 * Returns the absolute website root Url
	 * @return string the absolute website root Url
	 */
	public static function get_absolute_root()
	{
		$config = GeneralConfig::load();
		return trim($config->get_complete_site_url(), '/');
	}

	/**
	 * Returns the HTML text with only absolutes urls
	 * @param string $html_text The HTML text in which we gonna search for
	 * root relatives urls (only those beginning by '/') to convert into absolutes ones.
	 * @param string $path_to_root Path to root of the page to which you want to fit the URL.
	 * @param string $server_url Path from the site root of the page to which you want to fit the URL.
	 * @return string The HTML text with only absolutes urls
	 */
	public static function html_convert_root_relative2absolute($html_text, $path_to_root = PATH_TO_ROOT, $server_url = SERVER_URL)
	{
		$path_to_root_bak = self::$root;
		$server_url_bak = self::$server;

		self::$root = $path_to_root;
		self::$server = $server_url;

		$result = preg_replace_callback(self::build_html_match_regex(true),
		array('Url', 'convert_url_to_absolute'), $html_text);

		self::$root = $path_to_root_bak;
		self::$server = $server_url_bak;

		return $result;
	}

	/**
	 * Returns the HTML text with only relatives urls
	 * @param string $html_text The HTML text in which we gonna search for absolutes urls to convert into relatives ones.
	 * @param string $path_to_root Path to root of the page to which you want to fit the URL.
	 * @param string $server_url Path from the site root of the page to which you want to fit the URL.
	 * @return string The HTML text with only absolutes urls
	 */
	public static function html_convert_absolute2root_relative($html_text, $path_to_root = PATH_TO_ROOT, $server_url = SERVER_URL)
	{
		$path_to_root_bak = self::$root;
		$server_url_bak = self::$server;

		self::$root = $path_to_root;
		self::$server = $server_url;

		$result = preg_replace_callback(self::build_html_match_regex(),
		array('Url', 'convert_url_to_root_relative'), $html_text);

		self::$root = $path_to_root_bak;
		self::$server = $server_url_bak;

		return $result;
	}

	/**
	 * Transforms the relative URL whose base is the site root (for instance /images/mypic.png) to the real relative path fited to the current page.
	 * @param string $html_text The HTML text in which you want to replace the paths
	 * @param string $path_to_root Path to root of the page to which you want to fit the URL.
	 * @param string $server_url Path from the site root of the page to which you want to fit the URL.
	 * @return string The transformed string
	 */
	public static function html_convert_root_relative2relative($html_text, $path_to_root = PATH_TO_ROOT, $server_url = SERVER_URL)
	{
		$path_to_root_bak = self::$root;
		$server_url_bak = self::$server;

		self::$root = $path_to_root;
		self::$server = $server_url;

		$result = preg_replace_callback(self::build_html_match_regex(true),
		array('Url', 'convert_url_to_relative'), $html_text);

		self::$root = $path_to_root_bak;
		self::$server = $server_url_bak;

		return $result;
	}

	/**
	 * @param string $url the url to "relativize"
	 * @param string $path_to_root Path to root of the page to which you want to fit the URL
	 * @param string $server_url Path from the site root of the page to which you want to fit the URL.
	 * @return string the relative url of the $url parameter
	 */
	public static function get_relative($url, $path_to_root = null, $server_url = null)
	{
		$o_url = new Url($url, $path_to_root, $server_url);
		return $o_url->relative();
	}

	/**
	 * Returns the regex matching the requested url form
	 * @param int $protocol REGEX_MULTIPLICITY_OPTION for the protocol sub-regex
	 * @param int $user REGEX_MULTIPLICITY_OPTION for the user:password@ sub-regex
	 * @param int $domain REGEX_MULTIPLICITY_OPTION for the domain sub-regex
	 * @param int $folders REGEX_MULTIPLICITY_OPTION for the folders sub-regex
	 * @param int $file REGEX_MULTIPLICITY_OPTION for the file sub-regex
	 * @param int $args REGEX_MULTIPLICITY_OPTION for the arguments sub-regex
	 * @param int $anchor REGEX_MULTIPLICITY_OPTION for the anchor sub-regex
	 * @param bool $forbid_js true if you want to forbid javascript uses in urls
	 * @return the regex matching the requested url form
	 * @see RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL
	 * @see REGEX_MULTIPLICITY_NEEDED
	 * @see RegexHelper::REGEX_MULTIPLICITY_AT_LEAST_ONE
	 * @see RegexHelper::REGEX_MULTIPLICITY_ALL
	 * @see RegexHelper::REGEX_MULTIPLICITY_NOT_USED
	 */
	public static function get_wellformness_regex($protocol = RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL,
	$user = RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL, $domain = RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL,
	$folders = RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL, $file = RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL,
	$args = RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL, $forbid_js = true)
	{
		if ($forbid_js)
		{
			$protocol_regex_secured = self::FORBID_JS_REGEX . self::PROTOCOL_REGEX;
		}
		else
		{
			$protocol_regex_secured = self::PROTOCOL_REGEX;
		}

		$regex = RegexHelper::set_subregex_multiplicity($protocol_regex_secured, $protocol) .
		RegexHelper::set_subregex_multiplicity(self::USER_REGEX, $user) .
		RegexHelper::set_subregex_multiplicity(self::DOMAIN_REGEX, $domain) .
		RegexHelper::set_subregex_multiplicity(self::FOLDERS_REGEX, $folders) .
		RegexHelper::set_subregex_multiplicity(self::FILE_REGEX, $file);
		$regex .= RegexHelper::set_subregex_multiplicity(self::ARGS_REGEX, $args);

		return $regex;
	}

	/**
	 * Returns true if the url match the requested url form
	 * @param int $protocol REGEX_MULTIPLICITY_OPTION for the protocol sub-regex
	 * @param int $user REGEX_MULTIPLICITY_OPTION for the user:password@ sub-regex
	 * @param int $domain REGEX_MULTIPLICITY_OPTION for the domain sub-regex
	 * @param int $folders REGEX_MULTIPLICITY_OPTION for the folders sub-regex
	 * @param int $file REGEX_MULTIPLICITY_OPTION for the file sub-regex
	 * @param int $args REGEX_MULTIPLICITY_OPTION for the arguments sub-regex
	 * @param int $anchor REGEX_MULTIPLICITY_OPTION for the anchor sub-regex
	 * @param bool $forbid_js true if you want to forbid javascript uses in urls
	 * @return true if the url match the requested url form
	 * @see RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL
	 * @see REGEX_MULTIPLICITY_NEEDED
	 * @see RegexHelper::REGEX_MULTIPLICITY_AT_LEAST_ONE
	 * @see RegexHelper::REGEX_MULTIPLICITY_ALL
	 * @see RegexHelper::REGEX_MULTIPLICITY_NOT_USED
	 */
	public static function check_wellformness($url, $protocol = RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL,
	$user = RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL, $domain = RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL,
	$folders = RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL, $file = RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL,
	$args = RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL, $anchor = RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL, $forbid_js = true)
	{
		return (bool) preg_match('`^' . self::get_wellformness_regex($protocol, $user, $domain,
		$folders, $file, $args, $anchor, $forbid_js) . '$`iu', $url);
	}

	/**
	 * replace a relative url by the corresponding absolute one
	 * @param string[] $url_params Array containing the attributes containing the url and the url
	 * @return string the replaced url
	 */
	private static function convert_url_to_absolute($url_params)
	{
		$url = new Url($url_params[2]);
		$url_params[2] = $url->absolute();
		return $url_params[1] . $url_params[2] . $url_params[3];
	}

	/**
	 * replace an absolute url by the corresponding root relative one if possible
	 * @param string[] $url_params Array containing the attributes containing the url and the url
	 * @return string the replaced url
	 */
	private static function convert_url_to_root_relative($url_params)
	{
		$url = new Url($url_params[2]);
		$url_params[2] = $url->relative();
		return $url_params[1] . $url_params[2] . $url_params[3];
	}

	/**
	 * replace an absolute url by the corresponding relative one if possible
	 * @param string[] $url_params Array containing the attributes containing the url and the url
	 * @return string the replaced url
	 */
	private static function convert_url_to_relative($url_params)
	{
		$url = new Url($url_params[2]);
		if ($url->is_relative())
		{
			$url_params[2] = self::compress(self::$root . $url->relative());
		}
		return $url_params[1] . $url_params[2] . $url_params[3];
	}


	private static function build_html_match_regex($only_match_relative = false)
	{
		static $regex_match_all = null;
		static $regex_only_match_relative = null;

		// regex cache is empty, builds it
		if ((!$only_match_relative && $regex_match_all === null) || ($only_match_relative && $regex_only_match_relative === null))
		{
			$regex = array();
			$nodes =      array('a',    'img', 'form',   'object', 'param name="movie"');
			$attributes = array('href', 'src', 'action', 'data', 'value');

			$nodes_length = count($nodes);
			for ($i = 0; $i < $nodes_length; $i++)
			{
				$a_regex = '`(<' . $nodes[$i] . ' [^>]*(?<= )' . $attributes[$i] . '=")(';
				if ($only_match_relative)
				{
					$a_regex .= '/';
				}
				$a_regex .= '[^"]+)(")`isuU';
				$regex[] = $a_regex;
			}

			$regex[] = '`(<script><!--\s*insert(?:Sound|Movie)Player\\(")(' . ($only_match_relative ? '/' : '') . '[^"]+)("\\)\s*--></script>)`isuU';

			// Update regex cache
			if ($only_match_relative)
			{
				$regex_only_match_relative = $regex;
			}
			else
			{
				$regex_match_all = $regex;
			}
		}

		if ($only_match_relative)
		{
			return $regex_only_match_relative;
		}
		else
		{
			return $regex_match_all;
		}
	}

	/**
	 * Returns an url relative from the server root
	 * @param mixed $url the url representation. Could be a string or an Url object
	 * @return string an url relative from the server root
	 */
	public static function to_rel($url)
	{
		if (!($url instanceof Url))
		{
			$url = new Url($url);
		}
		return $url->rel();
	}

	/**
	 * Returns an url relative from PHPBoost root
	 * @param mixed $url the url representation. Could be a string or an Url object
	 * @return string an url relative from PHPBoost root
	 */
	public static function to_relative($url)
	{
		if (!($url instanceof Url))
		{
			$url = new Url($url);
		}
		return $url->relative();
	}

	/**
	 * Returns an absolute url
	 * @param mixed $url the url representation. Could be a string or an Url object
	 * @return string an absolute url
	 */
	public static function to_absolute($url)
	{
		if (!($url instanceof Url))
		{
			$url = new Url($url);
		}
		return $url->absolute();
	}

	/**
	 * Returns true if $check_url is current url
	 * @param string $check_url check url
	 * @param bool $real_url true if check real url or false for verificate $check_url is containing in current url
	 */
	public static function is_current_url($check_url, $real_url = false)
	{
		$general_config = GeneralConfig::load();

		$site_path = $general_config->get_default_site_path();
		$current_url = str_replace($site_path, '', REWRITED_SCRIPT);

		$other_home_page = trim($general_config->get_other_home_page(), '/');
		$path = trim($current_url, '/');

		if (!empty($path) || (!empty($other_home_page) && $path == $other_home_page))
		{
			$module_name = explode('/', $path);
			$running_module_name = $module_name[0];
		}
		else
		{
			$module_home_page = $general_config->get_module_home_page();
			if (empty($other_home_page) && !empty($module_home_page))
				$running_module_name = $module_home_page;
			else
				$running_module_name = '';
		}

		$current_url = ($current_url == '/' && $running_module_name) ? '/' . $running_module_name . '/' : $current_url;

		if ($real_url)
		{
			return $current_url == $check_url;
		}
		return TextHelper::strpos($current_url, $check_url) !== false;
	}
}
?>
