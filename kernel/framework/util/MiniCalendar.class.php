<?php
/**
 * This class enables you to retrieve easily a date entered by a user.
 * If the user isn't in the same timezone as the server, the hour will be automatically recomputed.
 * @package     Util
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 2.0 - 2008 06 03
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
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
	 * Builds a calendar which will be displayable.
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
	 * Sets the date at which will be initialized the calendar.
	 * @param Date $date Date
	 */
	public function set_date($date)
	{
		$this->date = $date;
	}

	/**
	 * Sets the CSS properties of the element.
	 * You can use it if you want to customize the mini calendar, but the best solution is to redefine the template in your module.
	 * The template used is framework/mini_calendar.tpl.
	 * @param string $style The CSS properties
	 */
	public function set_style($style)
	{
		$this->style = $style;
	}

	/**
	 * Returns the date
	 * @return Date the date
	 */
	public function get_date()
	{
		return $this->date;
	}

	/**
	 * Displays the mini calendar. You must call the display method in the same order as the calendars are displayed, because it requires a javascript code loading.
	 * @return string The code to write in the HTML page.
	 */
	public function display()
	{
		//On crée le code selon le template
		$template = new FileTemplate('framework/util/mini_calendar.tpl');
		$template->add_lang(LangLoader::get_all_langs());

		$template->put_all(array(
			'C_INCLUDE_JS' => !self::$js_inclusion_already_done,

			'DEFAULT_DATE'    => !empty($this->date) ? $this->date->format(Date::FORMAT_ISO_DAY_MONTH_YEAR) : '',
			'CALENDAR_ID'     => $this->html_id,
			'CALENDAR_NUMBER' => (string)$this->num_instance,
			'DAY'             => !empty($this->date) ? $this->date->get_day()   : '',
			'MONTH'           => !empty($this->date) ? $this->date->get_month() : '',
			'YEAR'            => !empty($this->date) ? $this->date->get_year()  : '',
			'CALENDAR_STYLE'  => $this->style,
		));

		self::$js_inclusion_already_done = true;

		return $template->render();
	}

	/**
	 * Retrieves a date entered in a mini calendar.
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
