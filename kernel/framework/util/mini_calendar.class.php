<?php
/*##################################################
 *                                mini_calendar.class.php
 *                            -------------------
 *   begin                : June 3rd, 2008
 *   copyright            : (C) 2008 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
 *
 *   Mini_calendar 1.0
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

import('util/date');

/**
 * @desc This class enables you to retrieve easily a date entered by a user.
 * If the user isn't in the same timezone as the server, the hour will be automatically recomputed.
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 * @package util
 */
class MiniCalendar
{
    /**
	 * @var int The number of calendars created in that page (used to know if we have to load the javascript code)
	 */
	private $num_instance = 0;
	/**
	 * @var string The CSS properties of the calendar
	 */
	private $style = '';
	/**
	 * @var string The calendar name
	 */
	private $form_name = '';
	/**
	 * @var Date The date it displays
	 */
	private $date;
	
    private static $num_instances = 0;
    
    private static $js_inclusion_already_done = false;
    
	/**
	 * @desc Builds a calendar which will be displayable.
	 * @param string $form_name Name of the mini calendar in the HTML code (you will retrieve the data in that field).
	 * This name must be a HTML identificator.
	 */
	public function MiniCalendar($form_name)
	{
		$this->form_name = $form_name;
		$this->num_instance = ++self::$num_instances;
		$this->date = new Date(DATE_NOW);
	}
	
	/**
	 * @desc Sets the date at which will be initialized the calendar.
	 * @param Date $date Date
	 */
	public function set_date($date)
	{
		$this->date = $date;
	}
	
	/**
	 * @desc Sets the CSS properties of the element. 
	 * You can use it if you want to customize the mini calendar, but the best solution is to redefine the template in your module.
	 * The template used is framework/mini_calendar.tpl.
	 * @param string $style The CSS properties
	 */
	public function set_style($style)
	{
		$this->style = $style;
	}
	
	/**
	 * @desc Returns the date
	 * @return Date the date
	 */
	public function get_date()
	{
		return $this->date;
	}
	
	/**
	 * @desc Displays the mini calendar. You must call the display method in the same order as the calendars are displayed, because it requires a javascript code loading.
	 * @return string The code to write in the HTML page.
	 */
	public function display()
	{
		global $CONFIG;
		
		//On crée le code selon le template
		$template = new Template('framework/mini_calendar.tpl');
		
		$template->assign_vars(array(
			'DEFAULT_DATE' => $this->date->format(DATE_FORMAT_SHORT),
			'CALENDAR_ID' => 'calendar_' . $this->num_instance,
			'CALENDAR_NUMBER' => (string)$this->num_instance,
			'DAY' => $this->date->get_day(),
			'MONTH' => $this->date->get_month(),
			'YEAR' => $this->date->get_year(),
			'FORM_NAME' => $this->form_name,
			'CALENDAR_STYLE' => $this->style,
			'C_INCLUDE_JS' => !self::$js_inclusion_already_done
		));
		
		self::$js_inclusion_already_done = true;
		
		return $template->parse(TEMPLATE_STRING_MODE);
	}
	
	/**
	 * @static
	 * @desc Retrieves a date entered in a mini calendar.
	 * @param string $calendar_name Name of the calendar (HTML identifier).
	 * @return Date The date of the calendar.
	 */
	public function retrieve_date($calendar_name)
	{
		global $LANG;
		return new Date(DATE_FROM_STRING, TIMEZONE_AUTO, retrieve(REQUEST, $calendar_name, '', TSTRING_UNCHANGE), $LANG['date_format_short']);
	}
}

?>