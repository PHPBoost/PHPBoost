<?php
/**
 * This class represents a link of a site map.
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 10 24
 * @since       PHPBoost 2.0 - 2008 06 16
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class SitemapLink extends SitemapElement
{
	/**
	 * @var string Name of the SitemapElement
	 */
	private $name = '';
	/**
	 * @var Url Url of the link
	 */
	private $link = null;
	/**
	 * @var string Actualization frequency of the target page, must be a member of the following enum:
	 * Sitemap::FREQ_ALWAYS, Sitemap::FREQ_HOURLY, Sitemap::FREQ_DAILY, Sitemap::FREQ_WEEKLY,
	 * Sitemap::FREQ_MONTHLY, Sitemap::FREQ_YEARLY, Sitemap::FREQ_NEVER, Sitemap::FREQ_DEFAULT
	 */
	private $change_freq = Sitemap::FREQ_DEFAULT;
	/**
	 *
	 * @var Date Last modification date of the target page
	 */
	private $last_modification_date = null;
	/**
	 * @var string Priority of the target page, must be a member of the following enum: Sitemap::PRIORITY_MAX,
	 *  Sitemap::PRIORITY_HIGH, Sitemap::PRIORITY_AVERAGE, Sitemap::PRIORITY_LOW, Sitemap::PRIORITY_MIN
	 */
	private $priority = Sitemap::PRIORITY_AVERAGE;

	/**
	 * @var mixed[] list of the accepted change freq values
	 */
	private static $change_freq_list = array(Sitemap::FREQ_ALWAYS, Sitemap::FREQ_HOURLY, Sitemap::FREQ_DAILY, Sitemap::FREQ_WEEKLY, Sitemap::FREQ_MONTHLY, Sitemap::FREQ_YEARLY, Sitemap::FREQ_NEVER, Sitemap::FREQ_DEFAULT);
	/**
	 * @var mixed[] list of the accepted priority values
	 */
	private static $priority_list = array(Sitemap::PRIORITY_MAX, Sitemap::PRIORITY_HIGH, Sitemap::PRIORITY_AVERAGE, Sitemap::PRIORITY_LOW, Sitemap::PRIORITY_MIN);

	/**
	 * @desc Builds a SitemapLink object
	 * @param string $name Name of the target page
	 * @param Url $link link
	 * @param string $change_freq Frequency taken into the following enum: Sitemap::FREQ_ALWAYS, Sitemap::FREQ_HOURLY,
	 * Sitemap::FREQ_DAILY, Sitemap::FREQ_WEEKLY, Sitemap::FREQ_MONTHLY, Sitemap::FREQ_YEARLY, Sitemap::FREQ_NEVER,
	 * Sitemap::FREQ_DEFAULT
	 * @param string $priority Priority taken into the following enum: Sitemap::PRIORITY_MAX, Sitemap::PRIORITY_HIGH,
	 * Sitemap::PRIORITY_AVERAGE, Sitemap::PRIORITY_LOW, Sitemap::PRIORITY_MIN
	 * @param Date $last_modification_date Last modification date of the target page
	 */
	public function __construct($name = '', Url $link = null, $change_freq = Sitemap::FREQ_MONTHLY, $priority = Sitemap::PRIORITY_AVERAGE, Date $last_modification_date = null)
	{
		$this->set_name($name);
		$this->set_link($link);
		$this->set_change_freq($change_freq);
		$this->set_priority($priority);
		if ($last_modification_date !== null)
		{
			$this->set_last_modification_date($last_modification_date);
		}
	}

	/**
	 * @desc Returns the name of the target page
	 * @return string name
	 */
	public function get_name()
	{
		return $this->name;
	}

	/**
	 * @desc Returns the URL of the link
	 * @return Url The URL of the link
	 */
	public function get_link()
	{
		return $this->link;
	}

	/**
	 * @desc Gets the change frequency (how often the target page is actualized)
	 * @return string Frequency taken into the following enum: Sitemap::FREQ_ALWAYS, Sitemap::FREQ_HOURLY,
	 * Sitemap::FREQ_DAILY, Sitemap::FREQ_WEEKLY, Sitemap::FREQ_MONTHLY, Sitemap::FREQ_YEARLY, Sitemap::FREQ_NEVER,
	 * Sitemap::FREQ_DEFAULT
	 */
	public function get_change_freq()
	{
		return $this->change_freq;
	}

	/**
	 * @desc Gets the priority of the link
	 * @return string Priority taken into the following enum: Sitemap::PRIORITY_MAX, Sitemap::PRIORITY_HIGH,
	 * Sitemap::PRIORITY_AVERAGE, Sitemap::PRIORITY_LOW, Sitemap::PRIORITY_MIN
	 */
	public function get_priority()
	{
		return $this->priority;
	}

	/**
	 * @desc Returns the last modification date of the target page
	 * @return Date the last modification date
	 */
	public function get_last_modification_date()
	{
		return $this->last_modification_date;
	}

	/**
	 * @desc Returns the URL of the link
	 * @return string the URL
	 */
	public function get_url()
	{
		if ($this->link !== null)
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
	public function set_name($name)
	{
		$this->name = $name;
	}

	/**
	 * @desc Sets the URL of the link
	 * @param Url $link URL
	 */
	public function set_link(Url $link)
	{
		$this->link = $link;
	}

	/**
	 * @desc Sets the change frequency
	 * @param string $change_freq Frequency taken into the following enum: Sitemap::FREQ_ALWAYS, Sitemap::FREQ_HOURLY,
	 * Sitemap::FREQ_DAILY, Sitemap::FREQ_WEEKLY, Sitemap::FREQ_MONTHLY, Sitemap::FREQ_YEARLY, Sitemap::FREQ_NEVER,
	 * Sitemap::FREQ_DEFAULT
	 */
	public function set_change_freq($change_freq)
	{
		//If the given frequency is correct
		if (in_array($change_freq, self::$change_freq_list))
		{
			$this->change_freq = $change_freq;
		}
		else
		{
			$this->change_freq = Sitemap::FREQ_DEFAULT;
		}
	}

	/**
	 * @desc Sets the priority of the link
	 * @param string $priority Priority taken into the following enum: Sitemap::PRIORITY_MAX, Sitemap::PRIORITY_HIGH,
	 * Sitemap::PRIORITY_AVERAGE, Sitemap::PRIORITY_LOW, Sitemap::PRIORITY_MIN
	 */
	public function set_priority($priority)
	{
		if (in_array($priority, self::$priority_list))
		{
			$this->priority = $priority;
		}
		else
		{
			$this->priority = Sitemap::PRIORITY_AVERAGE;
		}
	}

	/**
	 * @desc Sets the last modification date of the target page
	 * @param Date $date date
	 */
	public function set_last_modification_date(Date $last_modification_date)
	{
		$this->last_modification_date = $last_modification_date;
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
	 * @param SitemapExportConfig $export_config Export configuration
	 * @return Template the exported link
	 */
	public function export(SitemapExportConfig  $export_config)
	{
		$display_date = $this->last_modification_date !== null;

		//We get the stream in which we are going to write
		$template = $export_config->get_link_stream();

		$template->put_all(array(
			'LOC' => $this->get_url(),
			'TEXT' => TextHelper::htmlspecialchars($this->name, ENT_QUOTES),
			'C_DISPLAY_DATE' => $display_date,
			'DATE' => $display_date ? $this->last_modification_date->to_date() : '',
			'ACTUALIZATION_FREQUENCY' => $this->change_freq,
			'PRIORITY' => $this->priority,
            'C_LINK' => true
		));

		return $template;
	}
}
?>
