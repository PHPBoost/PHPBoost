<?php
/*##################################################
 *                            site_map.class.php
 *                            -------------------
 *   begin                : February 3rd 2009 
 *   copyright            : (C) 2009 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
 *
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

//Imports of every class of this package
import('content/sitemap/site_map_link');
import('content/sitemap/site_map_section');
import('content/sitemap/site_map_export_config');

//Usefull ?
define('SITE_MAP_AUTH_GUEST', false);
define('SITE_MAP_AUTH_USER', true);

//Actualization frequencies
define('SITEMAP_FREQ_ALWAYS', 'always');
define('SITEMAP_FREQ_HOURLY', 'hourly');
define('SITEMAP_FREQ_DAILY', 'daily');
define('SITEMAP_FREQ_WEEKLY', 'weekly');
define('SITEMAP_FREQ_MONTHLY', 'monthly');
define('SITEMAP_FREQ_YEARLY', 'yearly');
define('SITEMAP_FREQ_NEVER', 'never');
define('SITEMAP_FREQ_DEFAULT', SITEMAP_FREQ_MONTHLY);

//Link priority
define('SITEMAP_PRIORITY_MAX', '1');
define('SITEMAP_PRIORITY_HIGH', '0.75');
define('SITEMAP_PRIORITY_AVERAGE', '0.5');
define('SITEMAP_PRIORITY_LOW', '0.25');
define('SITEMAP_PRIORITY_MIN', '0');

class SiteMap
{
    /**
     * @desc Builds a SiteMap object with its elements 
     * @param $elements SiteMapElement[] List of the elements it contains
     */
    function SiteMap($elements)
    {
        if (is_array($elements))
        {
            $this->elements = $elements;
        }
    }
    
    /**
     * @desc Adds an element to the elements list of the SiteMap 
     * @param $element SiteMapElement The element to add
     */
    function add($element)
    {
        $this->elements[] = $element;
    }
    
	/**
	 * @desc Exports a SiteMap 
	 * @param $export_config SiteMapExportConfig Export configuration
	 * @return string The exported code of the SiteMap
	 */
	function export(&$export_config)
	{
		//We get the stream in which we are going to write
		$template = $export_config->get_site_map_stream();
		
		//Let's export all the element it contains
		foreach ($this->elements as $element)
		{
			$template->assign_block_vars('children', array(
				'CHILD_CODE' => $element->export($export_config)
				));
		}
		return $template->parse(TEMPLATE_STRING_MODE);
	}
	
	## Private elements ##
    /**
     * @var SiteMapElement[] Elements contained by the site map  
     */
    var $elements;
}

?>
