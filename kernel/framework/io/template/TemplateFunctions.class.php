<?php
/**
 * @package     IO
 * @subpackage  Template
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 18
 * @since       PHPBoost 3.0 - 2010 09 11
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class TemplateFunctions
{
    private I18NMessages $i18n;

    public function __construct()
    {
        $this->i18n = new I18NMessages();
    }

    public function resources(array|string|null $resources): void
    {
        $this->i18n->resources($resources);
    }

    public function add_language_maps(array $lang): void
    {
        $this->i18n->add_language_maps($lang);
    }

    public function i18n(string $key, ?array $parameters = null): string
    {
        return $this->i18n->i18n($key, $parameters);
    }

    public function i18njs(string $key, ?array $parameters = null): string
    {
        return $this->i18n->i18njs($key, $parameters);
    }

    public function i18njsraw(string $key, ?array $parameters = null): string
    {
        return $this->i18n->i18njsraw($key, $parameters);
    }

    public function i18nraw(string $key, ?array $parameters = null): ?string
    {
        return $this->i18n->i18nraw($key, $parameters);
    }

    /**
     * applies htmlspecialchars php function to the given string
     * @param $string A String
     * @return string the string without html special chars
     */
    public function escape(?string $string): string
    {
        return TextHelper::htmlspecialchars($string);
    }

    public function html(?string $string): string
    {
        return TextHelper::htmlspecialchars_decode($string);
    }

    /**
     * Exports a variable to be used in a javascript script.
     * @param string $string A PHP string to convert to a JS one
     * @param string $add_quotes If true, returned string will be bounded by quotes
     * @return string The js equivalent string
     */
    public function escapejs(?string $string, bool $add_quotes = true): string
    {
        return TextHelper::to_js_string($string, $add_quotes);
    }

    /**
     * Escape characters for use javascript script.
     * @param string $string string for conversion
     * @return string String escaped
     */
    public function escapejscharacters(?string $string): string
    {
        return strtr(Url::encode_rewrite($string), '-', '_');
    }

    public function set(string $string, array $parameters): string
    {
        return StringVars::replace_vars($string, $parameters);
    }

	public function relative_url(Url $url): string
	{
		return $url->rel();
	}

	public function absolute_url(Url $url): string
	{
		return $url->absolute();
	}
}
?>
