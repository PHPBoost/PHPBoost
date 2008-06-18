<?php
/*##################################################
 *                                sitemaplink.class.php
 *                            -------------------
 *   begin                : June 16 th 2008
 *   copyright          : (C) 2008 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
 *
 *   Sitemap_link
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

define('SITEMAP_FREQ_ALWAYS', 'always');
define('SITEMAP_FREQ_HOURLY', 'hourly');
define('SITEMAP_FREQ_DAILY', 'daily');
define('SITEMAP_FREQ_WEEKLY', 'weekly');
define('SITEMAP_FREQ_MONTHLY', 'monthly');
define('SITEMAP_FREQ_YEARLY', 'yearly');
define('SITEMAP_FREQ_NEVER', 'never');
define('SITEMAP_FREQ_DEFAULT', SITEMAP_FREQ_MONTHLY);

define('SITEMAP_PRIORITY_MAX', '1');
define('SITEMAP_PRIORITY_HIGH', '0.75');
define('SITEMAP_PRIORITY_AVERAGE', '0.5');
define('SITEMAP_PRIORITY_LOW', '0.25');
define('SITEMAP_PRIORITY_MIN', '0');

include_once(PATH_TO_ROOT . '/kernel/framework/util/date.class.php');

//Should implement an interface in PHP 5

class Sitemap_link
{
	##  Public methods  ##
	function Sitemap_link($text = '', $link = '', $change_freq = SITEMAP_FREQ_MONTHLY, $priority = SITEMAP_PRIORITY_AVERAGE, $last_modification_date = NULL)
	{
		$this->text = $text;
		$this->link = $link;
		$this->Set_change_freq($change_freq);
		$this->Set_priority($priority);
		if( is_object($last_modification_date) )
			$this->last_modification_date = $last_modification_date;
	}
	
	//Text getter
	function Get_text()
	{
		return $this->text;
	}
	
	//Text setter
	function Set_text($text)
	{
		$this->text = $text;
	}
	
	//Link getter
	function Get_link()
	{
		return $this->link;
	}
	
	//Link setter
	function Set_link($link)
	{
		$this->link = $link;
	}
	
	//Actualization frequency getter
	function Get_change_freq()
	{
		return $this->change_freq;
	}
	
	//Function which changes the actualization frequency
	function Set_change_freq($change_freq)
	{
		if( in_array($change_freq, array(SITEMAP_FREQ_ALWAYS, SITEMAP_FREQ_HOURLY, SITEMAP_FREQ_DAILY, SITEMAP_FREQ_WEEKLY, SITEMAP_FREQ_MONTHLY, SITEMAP_FREQ_YEARLY, SITEMAP_FREQ_NEVER, SITEMAP_FREQ_DEFAULT)) )
			$this->change_freq = $change_freq;
		else
			$this->change_freq = SITEMAP_FREQ_DEFAULT;
	}
	
	//Priority getter
	function Get_priority()
	{
		return $this->priority;
	}
	
	//Priority setter
	function Set_priority($priority)
	{
		if( in_array($priority, array(SITEMAP_PRIORITY_MAX, SITEMAP_PRIORITY_HIGH, SITEMAP_PRIORITY_AVERAGE, SITEMAP_PRIORITY_LOW, SITEMAP_PRIORITY_MIN)) )
			$this->priority = $priority;
		else
			$this->priority = SITEMAP_PRIORITY_AVERAGE;
	}
	
	//Date getter
	function Get_date()
	{
		return $this->last_modification_date;
	}
	
	//Date setter
	function Set_date($date)
	{
		$this->last_modification_date = $date;
	}
	
	//Method which exports the link into the stream $template
	function Export(&$export_config)
	{
		$display_date = is_object($this->last_modification_date);
		
		//We get the stream in which we are going to write
		$template = $export_config->Get_link_stream();
		
		$template->Assign_vars(array(
			'LOC' => htmlspecialchars($this->link, ENT_QUOTES),
			'TEXT' => htmlspecialchars($this->text, ENT_QUOTES),
			'C_DISPLAY_DATE' => $display_date,
			'DATE' => $display_date ? $this->last_modification_date->To_date() : '',
			'ACTUALIZATION_FREQUENCY' => $this->change_freq,
			'PRIORITY' => $this->priority,
			));
		
		return $template->Tparse(TEMPLATE_STRING_MODE);
	}
	
	## Private elements ##
	//Text which will be displayed in the HTML interface of the sitemap
	var $text;
	//Link of the target
	var $link;
	//Actualization frequency of the link
	var $change_freq = SITEMAP_FREQ_DEFAULT;
	//Last modification date
	var $last_modification_date;
	//Priority of the link
	var $priority = SITEMAP_PRIORITY_AVERAGE;
}

?>