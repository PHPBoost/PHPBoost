<?php
/**
 * @package     IO
 * @subpackage  Template
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 10 28
 * @since       PHPBoost 3.0 - 2010 09 05
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class I18NMessages
{
	private $messages = array();

	public function __construct($resources = null)
	{
        $this->resources($resources);
	}

    public function resources($resources)
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

    private function add_resource($resource)
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

    public function add_language_maps(array $lang)
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

    public function i18n($key, $parameters)
    {
        return TextHelper::htmlspecialchars($this->i18nraw($key, $parameters));
    }

    public function i18njs($key, $parameters)
    {
        return TextHelper::to_js_string($this->i18n($key, $parameters));
    }

    public function i18njsraw($key, $parameters)
    {
        return TextHelper::to_js_string($this->i18nraw($key, $parameters));
    }

    public function i18nraw($key, $parameters)
    {
        if (!empty($parameters))
        {
        	StringVars::replace_vars($this->messages[$key], $parameters);
        }
        return $this->messages[$key];
    }
}
?>
