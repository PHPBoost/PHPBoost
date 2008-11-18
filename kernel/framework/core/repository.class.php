<?php
/*##################################################
 *                           repository.class.php
 *                            -------------------
 *   begin                : August 17 2008
 *   copyright            : (C) 2008 Loïc Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
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

import('core/application');

class Repository
{
    function Repository($url)
    {
        $this->url = $url;
        $this->xml = @simplexml_load_file($this->url);
        if ($this->xml == false)
            $this->xml = null;
    }
    
    function check($app)
    {
        global $CONFIG;
        $xpath_query = '//app[@id=\'' . $app->get_id() . '\' and @type=\'' .  $app->get_type() . '\']/version[@language=\'' . $app->get_language() . '\']';
        // can't compare strings with XPath, so we check the version number with PHP.
        if ($this->xml != null)
        {
            $newerVersions = array();
            $versions = $this->xml->xpath($xpath_query);
            $nbVersions = $versions != false ? count($versions) : 0;
            // Retrieves all the available updates for the current application
            for ($i = 0; $i < $nbVersions; $i++)
            {
                $attributes = $versions[$i]->attributes();
                $version = $attributes['num'];
                if ($version > $app->get_version())
                {
                    $compatibility = $versions[$i]->xpath('compatibility');
                    if (!empty($compatibility) && is_array($compatibility) && count($compatibility) > 0)
                    {
                        $compatibility_attributes = $compatibility[0]->attributes();
                        $version_min = (string) (!empty($compatibility_attributes['min']) ? $compatibility_attributes['min'] : '0');
                        $version_max = (string) (!empty($compatibility_attributes['max']) ? $compatibility_attributes['max'] : $version_min);
                        
                        if ($CONFIG['version'] >= $version_min && $CONFIG['version'] <= $version_max && $version_max >= $version_min)
                            $newerVersions[(string) $version] = $i;
                    }
                }
            }
            
            // Keep only the first applyable update
            $firstNewVersion = count($newerVersions) > 0 ? min(array_keys($newerVersions)) : '';
            if (!empty($firstNewVersion))
            {
                $app->load($versions[$newerVersions[$firstNewVersion]]);
                return $app;
            }
        }
        return null;
    }
    
    function get_url() { return $this->url; }
    
    var $url = '';
    var $xml = null;
};

?>