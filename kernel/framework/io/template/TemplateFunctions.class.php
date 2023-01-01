<?php
/**
 * @package     IO
 * @subpackage  Template
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 09 11
*/

class TemplateFunctions
{
    /**
     * @var I18NMessages
     */
    private $i18n;

    public function __construct()
    {
        $this->i18n = new I18NMessages();
    }

    public function resources($resources)
    {
        $this->i18n->resources($resources);
    }

    private function add_resource($resource)
    {
        $this->i18n->add_resource($resource);
    }

    public function add_language_maps(array $lang)
    {
        $this->i18n->add_language_maps($lang);
    }

    public function i18n($key, array $parameters = null)
    {
        return $this->i18n->i18n($key, $parameters);
    }

    public function i18njs($key, array $parameters = null)
    {
        return $this->i18n->i18njs($key, $parameters);
    }

    public function i18njsraw($key, array $parameters = null)
    {
        return $this->i18n->i18njsraw($key, $parameters);
    }

    public function i18nraw($key, array $parameters = null)
    {
        return $this->i18n->i18nraw($key, $parameters);
    }

    /**
     * applies htmlspecialchars php function to the given string
     * @param $string A String
     * @return string the string without html special chars
     */
    public function escape($string)
    {
        return TextHelper::htmlspecialchars($string);
    }

    public function html($string)
    {
        return TextHelper::htmlspecialchars_decode($string);
    }

    /**
     * Exports a variable to be used in a javascript script.
     * @param string $string A PHP string to convert to a JS one
     * @param string $add_quotes If true, returned string will be bounded by quotes
     * @return string The js equivalent string
     */
    public function escapejs($string, $add_quotes = true)
    {
        return TextHelper::to_js_string($string, $add_quotes);
    }

    /**
     * Escape characters for use javascript script.
     * @param string $string string for conversion
     * @return string String escaped
     */
    public function escapejscharacters($string)
    {
        return strtr(Url::encode_rewrite($string), '-', '_');
    }

    public function set($string, array $parameters)
    {
        return StringVars::replace_vars($string, $parameters);
    }

	public function relative_url(Url $url)
	{
		return $url->rel();
	}

	public function absolute_url(Url $url)
	{
		return $url->absolute();
	}
}
?>
