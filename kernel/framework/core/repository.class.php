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


class Repository
{
    function Repository($url)
    {
        $this->url = $url;
        $this->xml = simplexml_load_file($this->url);
    }
    
    function check($app)
    {
        $xpath_query = '//app[@id=\'' . $app->get_id() . '\' and @type=\'' .  $app->get_type() . '\' and @language=\'' . $app->get_language() . '\']/version[@num != \'' . $app->get_version() . '\']';
        // ne gère pas le > sur une chaîne, à continuer...
        echo $xpath_query . '<hr />';
        if( $this->xml != null)
            return $this->xml->xpath($xpath_query);
        return 'ERRORORRE';
    }
    
    function get_url() { return $this->url; }
    
    var $url = '';
    var $xml = null;
};

?>