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
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 01 28
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
    public const FORBID_JS_REGEX = '(?!javascript:)';
    public const PROTOCOL_REGEX = '[a-z0-9-_]+(?::[a-z0-9-_]+)*://';
    public const USER_REGEX = '[a-z0-9-_]+(?::[a-z0-9-_]+)?@';
    public const DOMAIN_REGEX = '(?:[a-z0-9-_~]+\.)*[a-z0-9-_~]+(?::[0-9]{1,5})?/';
    public const FOLDERS_REGEX = '/*(?:[A-Za-z0-9~_\.+@,-]+/+)*';
    public const FILE_REGEX = '[A-Za-z0-9-+_,~:/\.\%!=]+';
    public const ARGS_REGEX = '(/([\w/_\.#-]*(\?)?(\S+)?[^\.\s])?)?';
    public const STATUS_OK = 200;

    private static string $root = TPL_PATH_TO_ROOT;
    private static string $server = SERVER_URL;

    private string $url = '';
    private bool $is_relative = false;
    private string $path_to_root = '';
    private string $server_url = '';

    /**
     * Build a Url object.
     */
    public function __construct(string $url = '.', ?string $path_to_root = null, ?string $server_url = null)
    {
        if (!empty($url))
        {
            $this->path_to_root = $path_to_root ?? self::$root;
            $this->server_url = $server_url ?? self::$server;

            $anchor = '';
            if (($pos = TextHelper::strpos($url, '#')) !== false)
            {
                if ($pos === 0)
                {
                    $this->url = $url;
                    $this->is_relative = false;
                    return;
                }
                else
                {
                    $anchor = TextHelper::substr($url, $pos);
                    $url = TextHelper::substr($url, 0, $pos);
                }
            }

            if (preg_match('`^[a-z0-9]+\:(?!//).+`iuU', $url) > 0)
            {
                $this->url = $url;
                return;
            }
            elseif (TextHelper::strpos($url, 'www.') === 0)
            {
                $url = 'http://' . $url;
            }

            if (AppContext::get_request()->get_site_domain_name())
            {
                $url = preg_replace('`^https?://' . AppContext::get_request()->get_site_domain_name() . GeneralConfig::load()->get_site_path() . '`uUi', '', self::compress($url));
            }

            if (!TextHelper::strpos($url, '://') && TextHelper::substr($url, 0, 2) !== '//')
            {
                $this->is_relative = true;
                if (TextHelper::substr($url, 0, 1) === '/')
                {
                    $this->url = $url;
                }
                else
                {
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
     * @return bool
     */
    public function is_relative(): bool
    {
        return $this->is_relative;
    }

    /**
     * @return string
     */
    public function relative(): string
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
     * @return string
     */
    public function rel(): string
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
     * @return string
     */
    public function absolute(): string
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
     * @return string
     */
    public function root_to_local(): string
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
    public static function encode_rewrite(?string $url): string
    {
        $url = mb_convert_encoding(TextHelper::html_entity_decode($url), 'ISO-8859-1', 'UTF-8');
        $url = TextHelper::strtolower(strtr($url, mb_convert_encoding('²ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöø°ÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ()[]\'"~$&%*@ç!?;,:/\^¨€{}<>«»`|+.= #', 'ISO-8859-1', 'UTF-8'), '2aaaaaaaaaaaaoooooooooooooeeeeeeeecciiiiiiiiuuuuuuuuynn    --      c  ---    e         --- '));
        $url = str_replace(' ', '', $url);
        $url = str_replace('---', '-', $url);
        $url = str_replace('--', '-', $url);
        $url = trim($url, '-');

        return $url;
    }

    /**
     * @param string $url
     * @return int
     */
    public static function check_url_validity(string $url): int
    {
        if (empty($url))
        {
            return false;
        }

        $status = 0;

        if (!($url instanceof Url))
        {
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = new Url($url);
        }

        if ($url->absolute())
        {
            if (filter_var($url->absolute(), FILTER_VALIDATE_URL) === false || !in_array(strtolower(parse_url($url->absolute(), PHP_URL_SCHEME)), ['http', 'https'], true))
            {
                return false;
            }

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
                if (\PHP_VERSION_ID < 80100)
                {
                    curl_close($curl);
                }
            }
            else
            {
                if (function_exists('stream_context_set_default'))
                {
                    stream_context_set_default(['http' => ['method' => 'HEAD', 'timeout' => 1]]);
                }

                if (function_exists('get_headers'))
                {
                    $file_headers = @get_headers($url->absolute(), true);

                    if (is_array($file_headers))
                    {
                        $status_list = [];
                        foreach ($file_headers as $header)
                        {
                            if (!is_array($header) && preg_match('/^HTTP\/[12]\.[01] (\d\d\d)/u', $header, $matches))
                            {
                                $status_list[] = (int)$matches[1];
                            }
                        }
                        if ($status_list)
                        {
                            $status = end($status_list);
                        }
                    }
                    else
                    {
                        $status = self::STATUS_OK;
                    }
                }
                else
                {
                    $status = self::STATUS_OK;
                }
            }
        }

        return $status === self::STATUS_OK || ($status > self::STATUS_OK && $status < 400) || $status === 429;
    }

    /**
     * @param string $url
     * @return int
     */
    public static function get_url_file_size(string $url): int
    {
        $file_size = 0;

        if (!($url instanceof Url))
        {
            $url = new Url($url);
        }

        $file = new File($url->rel());
        if ($file->exists())
        {
            $file_size = $file->get_file_size();
        }

        if (empty($file_size) && $url->absolute())
        {
            if (function_exists('stream_context_set_default'))
            {
                stream_context_set_default(['http' => ['method' => 'HEAD', 'timeout' => 1]]);
            }

            if (function_exists('get_headers'))
            {
                $file_headers = @get_headers($url->absolute(), true);
                if (isset($file_headers[0]))
                {
                    $status = 0;
                    if (preg_match('/^HTTP\/[12]\.[01] (\d\d\d)/u', $file_headers[0], $matches))
                    {
                        $status = (int)$matches[1];
                    }

                    if ($status === self::STATUS_OK && isset($file_headers['Content-Length']))
                    {
                        $file_size = $file_headers['Content-Length'];
                    }
                }
            }
        }

        return $file_size;
    }

    /**
     * @param string $url
     * @return string
     */
    public static function compress(string $url): string
    {
        $args = '';
        if (($pos = TextHelper::strpos($url, '?')) !== false)
        {
            $args = TextHelper::substr($url, $pos);
            $url = TextHelper::substr($url, 0, $pos);
        }
        $url = preg_replace(['`([^:]|^)/+`u', '`(?<!\.)\./`u'], ['$1/', ''], $url);

        do
        {
            $url = preg_replace('`/?[^/]+/\.\.`u', '', $url);
        }
        while (preg_match('`/?[^/]+/\.\.`u', $url) > 0);

        return $url . $args;
    }

    /**
     * @return string
     */
    public static function get_absolute_root(): string
    {
        $config = GeneralConfig::load();
        return trim($config->get_complete_site_url(), '/');
    }

    /**
     * @param string $html_text
     * @param string $path_to_root
     * @param string $server_url
     * @return string
     */
    public static function html_convert_root_relative2absolute(string $html_text, string $path_to_root = PATH_TO_ROOT, string $server_url = SERVER_URL): string
    {
        $path_to_root_bak = self::$root;
        $server_url_bak = self::$server;

        self::$root = $path_to_root;
        self::$server = $server_url;

        $result = preg_replace_callback(self::build_html_match_regex(true), [self::class, 'convert_url_to_absolute'], $html_text);

        self::$root = $path_to_root_bak;
        self::$server = $server_url_bak;

        return $result;
    }

    /**
     * @param string $html_text
     * @param string $path_to_root
     * @param string $server_url
     * @return string
     */
    public static function html_convert_absolute2root_relative(string $html_text, string $path_to_root = PATH_TO_ROOT, string $server_url = SERVER_URL): string
    {
        $path_to_root_bak = self::$root;
        $server_url_bak = self::$server;

        self::$root = $path_to_root;
        self::$server = $server_url;

        $result = preg_replace_callback(self::build_html_match_regex(), [self::class, 'convert_url_to_root_relative'], $html_text);

        self::$root = $path_to_root_bak;
        self::$server = $server_url_bak;

        return $result;
    }

    /**
     * @param string $html_text
     * @param string $path_to_root
     * @param string $server_url
     * @return string
     */
    public static function html_convert_root_relative2relative(string $html_text, string $path_to_root = PATH_TO_ROOT, string $server_url = SERVER_URL): string
    {
        $path_to_root_bak = self::$root;
        $server_url_bak = self::$server;

        self::$root = $path_to_root;
        self::$server = $server_url;

        $result = preg_replace_callback(self::build_html_match_regex(true), [self::class, 'convert_url_to_relative'], $html_text);

        self::$root = $path_to_root_bak;
        self::$server = $server_url_bak;

        return $result;
    }

    /**
     * @param string $url
     * @param string|null $path_to_root
     * @param string|null $server_url
     * @return string
     */
    public static function get_relative(string $url, ?string $path_to_root = null, ?string $server_url = null): string
    {
        $o_url = new Url($url, $path_to_root, $server_url);
        return $o_url->relative();
    }

    /**
     * @param int $protocol
     * @param int $user
     * @param int $domain
     * @param int $folders
     * @param int $file
     * @param int $args
     * @param bool $forbid_js
     * @return string
     */
    public static function get_wellformness_regex(int $protocol = RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL, int $user = RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL, int $domain = RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL, int $folders = RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL, int $file = RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL, int $args = RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL, bool $forbid_js = true): string
    {
        $protocol_regex_secured = $forbid_js ? self::FORBID_JS_REGEX . self::PROTOCOL_REGEX : self::PROTOCOL_REGEX;

        $regex = RegexHelper::set_subregex_multiplicity($protocol_regex_secured, $protocol)
            . RegexHelper::set_subregex_multiplicity(self::USER_REGEX, $user)
            . RegexHelper::set_subregex_multiplicity(self::DOMAIN_REGEX, $domain)
            . RegexHelper::set_subregex_multiplicity(self::FOLDERS_REGEX, $folders)
            . RegexHelper::set_subregex_multiplicity(self::FILE_REGEX, $file)
            . RegexHelper::set_subregex_multiplicity(self::ARGS_REGEX, $args);

        return $regex;
    }

    /**
     * @param string $url
     * @param int $protocol
     * @param int $user
     * @param int $domain
     * @param int $folders
     * @param int $file
     * @param int $args
     * @param int $anchor
     * @param bool $forbid_js
     * @return bool
     */
    public static function check_wellformness(string $url, int $protocol = RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL, int $user = RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL, int $domain = RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL, int $folders = RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL, int $file = RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL, int $args = RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL, int $anchor = RegexHelper::REGEX_MULTIPLICITY_OPTIONNAL, bool $forbid_js = true): bool
    {
        return (bool)preg_match('`^' . self::get_wellformness_regex($protocol, $user, $domain, $folders, $file, $args, $anchor, $forbid_js) . '$`iu', $url);
    }

    /**
     * @param array $url_params
     * @return string
     */
    private static function convert_url_to_absolute(array $url_params): string
    {
        $url = new Url($url_params[2]);
        $url_params[2] = $url->absolute();
        return $url_params[1] . $url_params[2] . $url_params[3];
    }

    /**
     * @param array $url_params
     * @return string
     */
    private static function convert_url_to_root_relative(array $url_params): string
    {
        $url = new Url($url_params[2]);
        $url_params[2] = $url->relative();
        return $url_params[1] . $url_params[2] . $url_params[3];
    }

    /**
     * @param array $url_params
     * @return string
     */
    private static function convert_url_to_relative(array $url_params): string
    {
        $url = new Url($url_params[2]);
        if ($url->is_relative())
        {
            $url_params[2] = self::compress(self::$root . $url->relative());
        }
        return $url_params[1] . $url_params[2] . $url_params[3];
    }

    /**
     * @param bool $only_match_relative
     * @return array
     */
    private static function build_html_match_regex(bool $only_match_relative = false): array
    {
        static $regex_match_all = null;
        static $regex_only_match_relative = null;

        if ((!$only_match_relative && $regex_match_all === null) || ($only_match_relative && $regex_only_match_relative === null))
        {
            $regex = [];
            $nodes = ['a', 'img', 'form', 'object', 'param name="movie"'];
            $attributes = ['href', 'src', 'action', 'data', 'value'];

            foreach ($nodes as $i => $node)
            {
                $a_regex = '`(<' . $node . ' [^>]*(?<= )' . $attributes[$i] . '=")(';
                if ($only_match_relative)
                {
                    $a_regex .= '/';
                }
                $a_regex .= '[^"]+)(")`isuU';
                $regex[] = $a_regex;
            }

            $regex[] = '`(<script><!--\s*insert(?:Sound|Movie)Player\\(")(' . ($only_match_relative ? '/' : '') . '[^"]+)("\\)\s*--></script>)`isuU';

            if ($only_match_relative)
            {
                $regex_only_match_relative = $regex;
            }
            else
            {
                $regex_match_all = $regex;
            }
        }

        return $only_match_relative ? $regex_only_match_relative : $regex_match_all;
    }

    /**
     * @param mixed $url
     * @return string
     */
    public static function to_rel($url): string
    {
        if (!($url instanceof Url))
        {
            $url = new Url($url);
        }
        return $url->rel();
    }

    /**
     * @param mixed $url
     * @return string
     */
    public static function to_relative($url): string
    {
        if (!($url instanceof Url))
        {
            $url = new Url($url);
        }
        return $url->relative();
    }

    /**
     * @param mixed $url
     * @return string
     */
    public static function to_absolute($url): string
    {
        if (!($url instanceof Url))
        {
            $url = new Url($url);
        }
        return $url->absolute();
    }

    /**
     * @param string $check_url
     * @param bool $real_url
     * @return bool
     */
    public static function is_current_url(string $check_url, bool $real_url = false): bool
    {
        $general_config = GeneralConfig::load();
        $site_path = $general_config->get_default_site_path();
        $current_url = str_replace($site_path, '', REWRITED_SCRIPT);
        $other_home_page = trim($general_config->get_other_home_page(), '/');
        $path = trim($current_url, '/');

        if (!empty($path) || (!empty($other_home_page) && $path === $other_home_page))
        {
            $module_name = explode('/', $path);
            $running_module_name = $module_name[0];
        }
        else
        {
            $module_home_page = $general_config->get_module_home_page();
            $running_module_name = empty($other_home_page) && !empty($module_home_page) ? $module_home_page : '';
        }

        $current_url = ($current_url === '/' && $running_module_name) ? '/' . $running_module_name . '/' : $current_url;

        if ($real_url)
        {
            return $current_url === $check_url;
        }
        return TextHelper::strpos($current_url, $check_url) !== false;
    }
}
?>
