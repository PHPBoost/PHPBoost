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
        $this->messages = array_merge(LangLoader::get($filename, $module), $this->messages);
    }
    
    public function i18n($key, $resource = null)
    {
        return htmlentities($this->i18nraw($key, $resource));
    }
    
    public function i18njs($key, $resource = null)
    {
        return TextHelper::to_js_string($this->i18n($key, $resource));        
    }
    
    public function i18njsraw($key, $resource = null)
    {
        return TextHelper::to_js_string($this->i18nraw($key, $resource));        
    }
    
    public function i18nraw($key, $resource = null)
    {
        if ($resource !== null)
        {
        	// FIXME How to mutualize this code???
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
            return LangLoader::get_message($key, $filename, $module);
        }
        else
        {
        	return $this->messages[$key];
        }
    }
    
    private function get_message($key, $resource)
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
        return LangLoader::get_message($key, $filename, $module);
    }
}
?>