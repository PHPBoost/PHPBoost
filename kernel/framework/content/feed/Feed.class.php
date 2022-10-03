<?php
/**
 * This class could be used to export feeds
 * <div classs="message-helper bgc notice">Do not use this class, but one of its children like RSS or ATOM</div>
 * @package     Content
 * @subpackage  Feed
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 10 03
 * @since       PHPBoost 2.0 - 2008 04 21
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

define('FEEDS_PATH', PATH_TO_ROOT . '/cache/syndication/');
define('ERROR_GETTING_CACHE', 'Error regenerating and / or retrieving the syndication cache of the %s (%s)');

class Feed
{
	const DEFAULT_FEED_NAME = 'master';

	/**
	 * @var int Module ID
	 */
	private $module_id = '';
	/**
	 *
	 * @var int ID cat
	 */
	private $id_cat = 0;
	/**
	 *
	 * @var string Feed Name
	 */
	private $name = '';
	/**
	 *
	 * @var string The feed as a string
	 */
	private $str = '';
	/**
	 *
	 * @var string The feed Template to use
	 */
	protected $view = null;
	/**
	 *
	 * @var string The data structure
	 */
	private $data = null;

	/**
	 * Builds a new feed object
	 * @param string $module_id its module_id
	 * @param string $name the feeds name / type. default is DEFAULT_FEED_NAME
	 * @param int $id_cat the feed category id
	 */
	public function __construct($module_id, $name = self::DEFAULT_FEED_NAME, $id_cat = 0)
	{
		$this->module_id = $module_id;
		$this->name = $name;
		$this->id_cat = $id_cat;
	}

	/**
	 * Loads a FeedData element
	 * @param FeedData $data the element to load
	 */
	public function load_data($data) { $this->data = $data; }
	/**
	 * Loads a feed by its url
	 * @param string $url the feed url
	 */
	public function load_file($url) { }

	/**
	 * Exports the feed as a string parsed by the <$view> template
	 * @param mixed $template If false, uses de default tpl. If an associative array,
	 * uses the default tpl but assigns it the array vars first.
	 * It could also be a Template object
	 * @param int $number the number of item to display
	 * @param int $begin_at the first item to display
	 * @return string The exported feed
	 */
	public function export($template = false, $number = 10, $begin_at = 0)
	{
		if ($template === false)
		{    // A specific template is used
			$view = clone $this->tpl;
		}
		else
		{
			$view = clone $template;
		}

		if (!empty($this->data))
		{
			$view->put_all(array_merge(
				Date::get_array_tpl_vars($this->data->get_date(), 'date'),
				array(
				'THIS_YEAR'    => date('Y'),
				'TITLE'        => $this->data->get_title(),
				'RAW_TITLE'    => TextHelper::htmlspecialchars($this->data->get_title()),
				'HOST'         => $this->data->get_host(),
				'DESC'         => ContentSecondParser::export_html_text($this->data->get_desc()),
				'RAW_DESC'     => TextHelper::htmlspecialchars($this->data->get_desc()),
				'LANG'         => $this->data->get_lang(),

				'U_LINK' => $this->data->get_link(),
			)));

			$items = $this->data->subitems($number, $begin_at);
			foreach ($items as $item)
			{
				$enclosure = $item->get_enclosure();
				$view->assign_block_vars('item', array_merge(
					Date::get_array_tpl_vars($item->get_date(), 'date'),
					array(
					'C_ENCLOSURE' => $enclosure !== null,
					'C_IMG'       => ($item->get_image_url() != ''),

					'TITLE'            => $item->get_title(),
					'RAW_TITLE'        => TextHelper::htmlspecialchars($item->get_title()),
					'DESC'             => ContentSecondParser::export_html_text($item->get_desc()),
					'RAW_DESC'         => TextHelper::htmlspecialchars($item->get_desc()),
					'ENCLOSURE_LENGHT' => $enclosure !== null ? $enclosure->get_lenght() : '',
					'ENCLOSURE_TYPE'   => $enclosure !== null ? $enclosure->get_type() : '',
					'ENCLOSURE_URL'    => $enclosure !== null ? $enclosure->get_url() : '',

					'U_LINK' => $item->get_link(),
					'U_GUID' => $item->get_guid(),
					'U_IMG'  => $item->get_image_url(),
				)));
			}
		}

		return $view->render();
	}

	/**
	 * Loads the feed data in cache and export it
	 * @return string the exported feed
	 */
	public function read()
	{
		if ($this->is_in_cache())
		{
			$include = include($this->get_cache_file_name());
			if ($include && isset($__feed_object) && !empty($__feed_object))
			{
				$this->data = $__feed_object;
				return $this->export();
			}
		}
		return '';
	}

	/**
	 * Send the feed data in the cache
	 */
	public function cache()
	{
		self::update_cache($this->module_id, $this->name, $this->data, $this->id_cat);
	}

	/**
	 * Returns true if the feed data are in the cache
	 * @return bool true if the feed data are in the cache
	 */
	public function is_in_cache()
	{
		return file_exists($this->get_cache_file_name());
	}

	/**
	 * Returns the feed data cache filename
	 * @return string the feed data cache filename
	 */
	public function get_cache_file_name()
	{
		return FEEDS_PATH . $this->module_id . '_' . $this->name . '_' . $this->id_cat . '.php';
	}

	/**
	 * Clear the cache of the specified module_id.
	 * @param mixed $module_id the module module_id or false. If false,
	 * Clear all feeds data from the cache
	 * @static
	 */
	public static function clear_cache($module_id = false)
	{
		$folder = new Folder(FEEDS_PATH);
		$files = null;
		if ($module_id !== false)
		{   // Clear only this module cache
			$files = $folder->get_files('`' . $module_id . '_.*`');
			foreach ($files as $file)
			{
				$file->delete();
			}
		}
		else
		{   // Clear the whole cache
			AppContext::get_cache_service()->clear_syndication_cache();
		}
	}


	/**
	 * Update the cache of the $module_id, $name, $idcat feed with $data
	 * @param string $module_id the module id
	 * @param string $name the feed name / type
	 * @param &FeedData $data the data to put in the cache
	 * @param int $idcat the feed data category
	 * @static
	 */
	private static function update_cache($module_id, $name, $data, $idcat = 0)
	{
		if ($data instanceof FeedData)
		{
			$file = new File(FEEDS_PATH . $module_id . '_' . $name . '_' . $idcat . '.php');
			$file->write('<?php $__feed_object = TextHelper::unserialize(' . var_export($data->serialize(), true) . '); ?>');
			$file->close();
			return true;
		}
		return false;
	}

	/**
	 * Export a feed
	 * @param string $module_id the module id
	 * @param string $name the feed name / type
	 * @param int $idcat the feed data category
	 * @param mixed $view If false, uses de default tpl. If an associative array,
	 * uses the default tpl but assigns it the array vars first.
	 * It could also be a Template object
	 * @param int $number the number of item to display
	 * @param int $begin_at the first item to display
	 * @return string The exported feed
	 * @static
	 */
	public static function get_parsed($module_id, $name = self::DEFAULT_FEED_NAME, $idcat = 0, $template = false, $number = 10, $begin_at = 0)
	{
		if (!($template instanceof Template))
		{
			$template = new FileTemplate('framework/content/syndication/feed.tpl');
			if (gettype($template) == 'array')
			{
				$template->put_all($template);
			}
		}

		$feed_data_cache_file_exists = true;
		// Get the cache content or recreate it if not existing
		$feed_data_cache_file = FEEDS_PATH . $module_id . '_' . $name . '_' . $idcat . '.php';
		if (!file_exists($feed_data_cache_file))
		{
			$extension_provider_service = AppContext::get_extension_provider_service();
			$provider = $extension_provider_service->get_provider($module_id);

			if (!$provider->has_extension_point(FeedProvider::EXTENSION_POINT))
			{   // If the module is not installed or doesn't have the get_feed_data_struct
				// functionality we break
				return '';
			}
			$feed_provider = $provider->get_extension_point(FeedProvider::EXTENSION_POINT);
			$data = $feed_provider->get_feed_data_struct($idcat);
			$feed_data_cache_file_exists = self::update_cache($module_id, $name, $data, $idcat);
		}

		if ($feed_data_cache_file_exists)
		{
			include $feed_data_cache_file;
			if (isset($__feed_object) && !empty($__feed_object))
			{
				$feed = new Feed($module_id, $name);
				$feed->load_data($__feed_object);
				return $feed->export($template, $number, $begin_at);
			}
			return '';
		}
		else
		{
			MessageHelper::display(sprintf(ERROR_GETTING_CACHE, $module_id, $idcat), MessageHelper::WARNING);
			return '';
		}
	}

	/**
	 * @static
	 * Generates the code which shows all the feeds formats.
	 * @param string $feed_url Feed URL
	 * @return string The HTML code to display.
	 */
	public static function get_feed_menu($module_id, $id_cat = 0)
	{
		$feed_menu = new FileTemplate('framework/content/syndication/menu.tpl');

		$feed_menu->put_all(array(
			'U_FEED_RSS' => SyndicationUrlBuilder::rss($module_id, $id_cat)->absolute(),
			'U_FEED_ATOM' => SyndicationUrlBuilder::atom($module_id, $id_cat)->absolute()
		));

		return $feed_menu->render();
	}
}
?>
