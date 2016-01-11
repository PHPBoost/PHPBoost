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



/**
 * @desc This class enables you to retrieve easily a date entered by a user.
 * If the user isn't in the same timezone as the server, the hour will be automatically recomputed.
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 * @package {@package}
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
	 * @var string The calendar id
	 */
	private $html_id = '';
	
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
	public function __construct($html_id, Date $date = null)
	{
		$this->html_id = $html_id;
		$this->num_instance = ++self::$num_instances;

		$this->set_date($date);
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
		//On crée le code selon le template
		$template = new FileTemplate('framework/util/mini_calendar.tpl');

		$template->put_all(array(
			'DEFAULT_DATE' => !empty($this->date) ? $this->date->format(Date::FORMAT_ISO_DAY_MONTH_YEAR) : '',
			'CALENDAR_ID' => $this->html_id,
			'CALENDAR_NUMBER' => (string)$this->num_instance,
			'DAY' => !empty($this->date) ? $this->date->get_day() : '',
			'MONTH' => !empty($this->date) ? $this->date->get_month() : '',
			'YEAR' => !empty($this->date) ? $this->date->get_year() : '',
			'CALENDAR_STYLE' => $this->style,
			'C_INCLUDE_JS' => !self::$js_inclusion_already_done
		));

		self::$js_inclusion_already_done = true;

		return $template->render();
	}

	/**
	 * @desc Retrieves a date entered in a mini calendar.
	 * @param string $calendar_name Name of the calendar (HTML identifier).
	 * @return Date The date of the calendar.
	 */
	public static function retrieve_date($calendar_name)
	{
		$value = retrieve(REQUEST, $calendar_name, '', TSTRING_UNCHANGE);
		return preg_match('`^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$`', $value) > 0 ? new Date($value) : null;
	}
}
?>