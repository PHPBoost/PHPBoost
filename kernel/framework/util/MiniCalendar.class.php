<?php
/**
 * This class enables you to retrieve easily a date entered by a user.
 * If the user isn't in the same timezone as the server, the hour will be automatically recomputed.
 * @package     Util
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2025 11 27
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
    private int $num_instance = 0;

    /**
     * @var string The CSS properties of the calendar
     */
    private string $style = '';

    /**
     * @var string The calendar id
     */
    private string $html_id = '';

    /**
     * @var Date|null The date it displays
     */
    private ?Date $date = null;

    /**
     * @var int The number of instances created
     */
    private static int $num_instances = 0;

    /**
     * @var bool Whether the JavaScript inclusion has already been done
     */
    private static bool $js_inclusion_already_done = false;

    /**
     * Builds a calendar which will be displayable.
     *
     * @param string $html_id Name of the mini calendar in the HTML code (you will retrieve the data in that field).
     * This name must be a HTML identifier.
     * @param Date|null $date The date to set for the calendar
     */
    public function __construct(string $html_id, ?Date $date = null)
    {
        $this->html_id = $html_id;
        $this->num_instance = ++self::$num_instances;

        $this->set_date($date);
    }

    /**
     * Sets the date at which the calendar will be initialized.
     *
     * @param Date|null $date The date to set
     */
    public function set_date(?Date $date): void
    {
        $this->date = $date;
    }

    /**
     * Sets the CSS properties of the element.
     * You can use it if you want to customize the mini calendar, but the best solution is to redefine the template in your module.
     * The template used is framework/mini_calendar.tpl.
     *
     * @param string $style The CSS properties
     */
    public function set_style(string $style): void
    {
        $this->style = $style;
    }

    /**
     * Returns the date.
     *
     * @return Date|null The date
     */
    public function get_date(): ?Date
    {
        return $this->date;
    }

    /**
     * Displays the mini calendar. You must call the display method in the same order as the calendars are displayed,
     * because it requires a JavaScript code loading.
     *
     * @return string The code to write in the HTML page
     */
    public function display(): string
    {
        $template = new FileTemplate('framework/util/mini_calendar.tpl');
        $template->add_lang(LangLoader::get_all_langs());

        $template->put_all([
            'C_INCLUDE_JS'     => !self::$js_inclusion_already_done,
            'DEFAULT_DATE'     => $this->date ? $this->date->format(Date::FORMAT_ISO_DAY_MONTH_YEAR) : '',
            'CALENDAR_ID'      => $this->html_id,
            'CALENDAR_NUMBER'  => (string)$this->num_instance,
            'DAY'              => $this->date ? $this->date->get_day()   : '',
            'MONTH'            => $this->date ? $this->date->get_month() : '',
            'YEAR'             => $this->date ? $this->date->get_year()  : '',
            'CALENDAR_STYLE'   => $this->style,
        ]);

        self::$js_inclusion_already_done = true;

        return $template->render();
    }

    /**
     * Retrieves a date entered in a mini calendar.
     *
     * @param string $calendar_name Name of the calendar (HTML identifier)
     * @return Date|null The date of the calendar, or null if invalid
     */
    public static function retrieve_date(string $calendar_name): ?Date
    {
        $request = AppContext::get_request();
        $value = $request->get_value($calendar_name, '', TSTRING_UNCHANGE);
        return (preg_match('`^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$`', $value) > 0) ? new Date($value) : null;
    }
}
?>
