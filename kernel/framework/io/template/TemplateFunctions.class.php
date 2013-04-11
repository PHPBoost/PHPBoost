<?php
/*##################################################
 *                     TemplateFunctions.class.php
 *                            -------------------
 *   begin                : September 11 2010
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
     * @desc applies htmlspecialchars php function to the given string
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
     * @desc Exports a variable to be used in a javascript script.
     * @param string $string A PHP string to convert to a JS one
     * @param string $add_quotes If true, returned string will be bounded by quotes
     * @return string The js equivalent string
     */
    public function escapejs($string, $add_quotes = true)
    {
        return TextHelper::to_js_string($string, $add_quotes);
    }
    
    /**
     * @desc Escape characters for use javascript script.
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