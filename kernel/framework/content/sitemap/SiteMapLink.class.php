<?php
/*##################################################
 *                           sitemaplink.class.php
 *                            -------------------
 *   begin                : June 16 th 2008
 *   copyright            : (C) 2008 Sautel Benoit
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





/**
 * @package content
 * @subpackage sitemap
 * @author Benoît Sautel <ben.popeye@phpboost.com>
 * @desc This class represents a link of a site map.
 */
class SiteMapLink extends SiteMapElement
{
    /**
     * @desc Builds a SiteMapLink object
     * @param string $name Name of the target page
     * @param Url $link link
     * @param string $change_freq Frequency taken into the following enum: SITE_MAP_FREQ_ALWAYS, SITE_MAP_FREQ_HOURLY,
     * SITE_MAP_FREQ_DAILY, SITE_MAP_FREQ_WEEKLY, SITE_MAP_FREQ_MONTHLY, SITE_MAP_FREQ_YEARLY, SITE_MAP_FREQ_NEVER,
     * SITE_MAP_FREQ_DEFAULT
     * @param string $priority Priority taken into the following enum: SITE_MAP_PRIORITY_MAX, SITE_MAP_PRIORITY_HIGH,
     * SITE_MAP_PRIORITY_AVERAGE, SITE_MAP_PRIORITY_LOW, SITE_MAP_PRIORITY_MIN
     * @param Date $last_modification_date Last modification date of the target page
     */
    function SitemapLink($name = '', $link = null, $change_freq = SITE_MAP_FREQ_MONTHLY, $priority = SITE_MAP_PRIORITY_AVERAGE, $last_modification_date = null)
    {
        $this->name = $name;
        $this->set_link($link);
        $this->set_change_freq($change_freq);
        $this->set_priority($priority);
        $this->set_last_modification_date($last_modification_date);
    }
    
    /**
     * @desc Returns the name of the target page 
     * @return string name
     */
    function get_name()
    {
        return $this->name;
    }

    /**
     * @desc Returns the URL of the link
     * @return Url The URL of the link
     */
    function get_link()
    {
        return $this->link;
    }

    /**
     * @desc Gets the change frequency (how often the target page is actualized)
     * @return string Frequency taken into the following enum: SITE_MAP_FREQ_ALWAYS, SITE_MAP_FREQ_HOURLY,
     * SITE_MAP_FREQ_DAILY, SITE_MAP_FREQ_WEEKLY, SITE_MAP_FREQ_MONTHLY, SITE_MAP_FREQ_YEARLY, SITE_MAP_FREQ_NEVER,
     * SITE_MAP_FREQ_DEFAULT
     */
    function get_change_freq()
    {
        return $this->change_freq;
    }

    /**
     * @desc Gets the priority of the link
     * @return string Priority taken into the following enum: SITE_MAP_PRIORITY_MAX, SITE_MAP_PRIORITY_HIGH,
     * SITE_MAP_PRIORITY_AVERAGE, SITE_MAP_PRIORITY_LOW, SITE_MAP_PRIORITY_MIN
     */
    function get_priority()
    {
        return $this->priority;
    }

    /**
     * @desc Returns the last modification date of the target page
     * @return Date the last modification date
     */
    function get_last_modification_date()
    {
        return $this->last_modification_date;
    }

    /**
     * @desc Returns the URL of the link 
     * @return string the URL
     */
    function get_url()
    {
        if (is_object($this->link))
        {
            return $this->link->absolute();
        }
        else
        {
            return '';
        }
    }

    /**
     * @desc Sets the name of the element
     * @param string $name name of the element
     */
    function set_name($name)
    {
        $this->name = $name;
    }

    /**
     * @desc Sets the URL of the link
     * @param Url $link URL
     */
    function set_link($link)
    {
        if (is_object($link))
        {
            $this->link = $link;
        }
        else if (is_string($link))
        {
            $this->link = new Url($link);
        }
    }

    /**
     * @desc Sets the change frequency
     * @param string $change_freq Frequency taken into the following enum: SITE_MAP_FREQ_ALWAYS, SITE_MAP_FREQ_HOURLY,
     * SITE_MAP_FREQ_DAILY, SITE_MAP_FREQ_WEEKLY, SITE_MAP_FREQ_MONTHLY, SITE_MAP_FREQ_YEARLY, SITE_MAP_FREQ_NEVER,
     * SITE_MAP_FREQ_DEFAULT
     */
    function set_change_freq($change_freq)
    {
        //If the given frequency is correct
        if (in_array($change_freq, array(SITE_MAP_FREQ_ALWAYS, SITE_MAP_FREQ_HOURLY, SITE_MAP_FREQ_DAILY, SITE_MAP_FREQ_WEEKLY, SITE_MAP_FREQ_MONTHLY, SITE_MAP_FREQ_YEARLY, SITE_MAP_FREQ_NEVER, SITE_MAP_FREQ_DEFAULT)))
        {
            $this->change_freq = $change_freq;
        }
        else
        {
            $this->change_freq = SITE_MAP_FREQ_DEFAULT;
        }
    }

    /**
     * @desc Sets the priority of the link
     * @param string $priority Priority taken into the following enum: SITE_MAP_PRIORITY_MAX, SITE_MAP_PRIORITY_HIGH,
     * SITE_MAP_PRIORITY_AVERAGE, SITE_MAP_PRIORITY_LOW, SITE_MAP_PRIORITY_MIN
     */
    function set_priority($priority)
    {
        if (in_array($priority, array(SITE_MAP_PRIORITY_MAX, SITE_MAP_PRIORITY_HIGH, SITE_MAP_PRIORITY_AVERAGE, SITE_MAP_PRIORITY_LOW, SITE_MAP_PRIORITY_MIN)))
        {
            $this->priority = $priority;
        }
        else
        {
            $this->priority = SITE_MAP_PRIORITY_AVERAGE;
        }
    }

    /**
     * @desc Sets the last modification date of the target page
     * @param Date $date date
     */
    function set_last_modification_date($last_modification_date)
    {
        if (is_object($last_modification_date))
        {
            $this->last_modification_date = $last_modification_date;
        }
    }

    /**
     * @desc Exports the section according to the given configuration. You will use the following template variables:
     * <ul>
     * 	<li>LOC containing the URL of the link</li>
     * 	<li>TEXT containing the name of the target page</li>
     * 	<li>C_DISPLAY_DATE indicating if the date is not empty</li>
     * 	<li>DATE containing the date of the last modification of the target page, formatted for the sitemap.xml file</li>
     * 	<li>ACTUALIZATION_FREQUENCY corresponding to the code needed in the sitemap.xml file</li>
     * 	<li>PRIORITY corresponding to the code needed in the sitemap.xml file to indicate the priority of the target page.</li>
     * 	<li>C_LINK indicating that we are displaying a link (useful if you want to use a signe template export configuration)</li>
     * </ul>
     * @param SiteMapExportConfig $export_config Export configuration
     * @return string the exported link
     */
    function export(&$export_config)
    {
        $display_date = is_object($this->last_modification_date);

        //We get the stream in which we are going to write
        $template = $export_config->get_link_stream();

        $template->assign_vars(array(
			'LOC' => $this->get_url(),
			'TEXT' => htmlspecialchars($this->name, ENT_QUOTES),
			'C_DISPLAY_DATE' => $display_date,
			'DATE' => $display_date ? $this->last_modification_date->to_date() : '',
			'ACTUALIZATION_FREQUENCY' => $this->change_freq,
			'PRIORITY' => $this->priority,
            'C_LINK' => true
        ));

        return $template->parse(Template::TEMPLATE_PARSER_STRING);
    }

    ## Private elements ##
    /**
    * @var string Name of the SiteMapElement
    */
    var $name = '';
    /**
     * @var Url Url of the link
     */
    var $link;
    /**
     * @var string Actualization frequency of the target page, must be a member of the following enum:
     * SITE_MAP_FREQ_ALWAYS, SITE_MAP_FREQ_HOURLY, SITE_MAP_FREQ_DAILY, SITE_MAP_FREQ_WEEKLY,
     * SITE_MAP_FREQ_MONTHLY, SITE_MAP_FREQ_YEARLY, SITE_MAP_FREQ_NEVER, SITE_MAP_FREQ_DEFAULT
     */
    var $change_freq = SITE_MAP_FREQ_DEFAULT;
    /**
     *
     * @var Date Last modification date of the target page
     */
    var $last_modification_date;
    /**
     * @var string Priority of the target page, must be a member of the following enum: SITE_MAP_PRIORITY_MAX,
     *  SITE_MAP_PRIORITY_HIGH, SITE_MAP_PRIORITY_AVERAGE, SITE_MAP_PRIORITY_LOW, SITE_MAP_PRIORITY_MIN
     */
    var $priority = SITE_MAP_PRIORITY_AVERAGE;
}

?>