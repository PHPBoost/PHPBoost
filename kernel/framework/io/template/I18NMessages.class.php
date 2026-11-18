<?php
/**
 * @package     IO
 * @subpackage  Template
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 18
 * @since       PHPBoost 3.0 - 2010 09 05
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class I18NMessages
{
	private array $messages = [];

	public function __construct(array|string|null$resources = null)
	{
        $this->resources($resources);
	}

    public function resources(array|string|null$resources): void
    {
        if (is_string($resources))
        {
            $this->add_resource($resources);
        }
        elseif (is_array($resources))
        {
            foreach ($resources as $resource)
            {
                $this->add_resource($resource);
            }
        }
    }

    private function add_resource(string $resource): void
    {
        $module = '';
        $filename = '';
        $resource = trim($resource, '/');
        $slash_idx = TextHelper::strrpos($resource, '/');
        if ($slash_idx > -1)
        {
            $module = TextHelper::substr($resource, 0, $slash_idx);
            $filename = TextHelper::substr($resource, $slash_idx + 1);
        }
        else
        {
            $filename = $resource;
        }
        $this->add_language_maps(LangLoader::get($filename, $module));
    }

    public function add_language_maps(array $lang): void
    {
        if (empty($this->messages))
        {
            $this->messages = $lang;
        }
        else
        {
            $this->messages = array_merge($lang, $this->messages);
        }
    }

    public function i18n(string $key, ?array $parameters): string
    {
        return TextHelper::htmlspecialchars($this->i18nraw($key, $parameters));
    }

    public function i18njs(string $key, ?array $parameters): string
    {
        return TextHelper::to_js_string($this->i18n($key, $parameters));
    }

    public function i18njsraw(string $key, ?array $parameters): string
    {
        return TextHelper::to_js_string($this->i18nraw($key, $parameters));
    }

    public function i18nraw(string $key, ?array $parameters): ?string
    {
        if (!empty($parameters))
        {
            StringVars::replace_vars($this->messages[$key], $parameters);
        }
        return $this->messages[$key];
    }
}
?>
