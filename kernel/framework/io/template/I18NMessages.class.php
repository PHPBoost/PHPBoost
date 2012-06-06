<?php
/*##################################################
 *                     I18NMessages.class.php
 *                            -------------------
 *   begin                : September 05 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : horn@phpboost.com
 *
 *
 ###################################################
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
 ###################################################*/

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
        $slash_idx = strrpos($resource, '/');
        if ($slash_idx > -1)
        {
            $module = substr($resource, 0, $slash_idx);
            $filename = substr($resource, $slash_idx + 1);
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