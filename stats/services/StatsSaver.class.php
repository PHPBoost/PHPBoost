<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 03 16
 * @since       PHPBoost 2.0 - 2008 08 23
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

class StatsSaver
{
    /**
	 * @desc Compute Stats of Site Referers
	 */
	public static function compute_referer()
	{
		$referer = parse_url(AppContext::get_request()->get_url_referrer());
		if (!empty($referer))
		{
			########### Détection des mots clés ###########
			$is_search_engine = false;
			$search_engine = $query_param = '';
			if (!empty($referer['host']))
			{
				$engines = array(
					'dmoz'		=> 'q',
					'aol'		=> 'q',
					'ask'		=> 'q',
					'google'	=> 'q',
					'bing'		=> 'q',
					'hotbot'	=> 'q',
					'teoma'		=> 'q',
					'exalead'	=> 'q',
					'yahoo'		=> 'p',
					'lycos'		=> 'query',
					'kanoodle'	=> 'query',
					'voila'		=> 'kw',
					'baidu'		=> 'wd',
					'yandex'	=> 'text'
				);

				foreach ($engines as $engine => $param)
				{
					if (TextHelper::strpos($referer['host'], $engine) !== false)
					{
						$is_search_engine = true;
						$search_engine = $engine;
						$query_param = $param;
						break;
					}
				}
			}

			if ($is_search_engine)
			{
				$query = !empty($referer['query']) ? $referer['query'] . '&' : '';

				if (TextHelper::strpos($query, $query_param . '=') !==  false)
				{
					$pattern = '/' . $query_param . '=(.*?)&/si';
					preg_match($pattern, $query, $matches);
					$keyword = TextHelper::strprotect(utf8_decode(urldecode(TextHelper::strtolower($matches[1]))));

					$check_search_engine = PersistenceContext::get_querier()->count(StatsSetup::$stats_referer_table, 'WHERE url = :url AND relative_url = :keyword', array('url' => $search_engine, 'keyword' => $keyword));
					if (!empty($keyword))
					{
						if (!empty($check_search_engine))
							PersistenceContext::get_querier()->inject("UPDATE " . StatsSetup::$stats_referer_table . " SET total_visit = total_visit + 1, today_visit = today_visit + 1, last_update = '" . time() . "' WHERE url = '" . $search_engine . "' AND relative_url = '" . $keyword . "'");
						else
							PersistenceContext::get_querier()->insert(StatsSetup::$stats_referer_table, array('url' => $search_engine, 'relative_url' => $keyword, 'total_visit' => 1, 'today_visit' => 1, 'yesterday_visit' => 0, 'nbr_day' => 1, 'last_update' => time(), 'type' => 1));
					}
				}
			}
			elseif (!empty($referer['host']))
			{
				$referer['scheme'] = !empty($referer['scheme']) ? $referer['scheme'] : 'http';
				########### Détection du site de provenance ###########
				$url = addslashes($referer['scheme'] . '://' . $referer['host']);
				if (TextHelper::strpos($url, HOST) === false)
				{
					$referer['path'] = !empty($referer['path']) ? $referer['path'] : '';
					$relative_url = addslashes(((TextHelper::substr($referer['path'], 0, 1) == '/') ? $referer['path'] : ('/' . $referer['path'])) . (!empty($referer['query']) ? '?' . $referer['query'] : '') . (!empty($referer['fragment']) ? '#' . $referer['fragment'] : ''));

					$check_url = PersistenceContext::get_querier()->count(StatsSetup::$stats_referer_table, 'WHERE url = :url AND relative_url = :relative_url', array('url' => $url, 'relative_url' => $relative_url));
					if (!empty($check_url))
						PersistenceContext::get_querier()->inject("UPDATE " . StatsSetup::$stats_referer_table . " SET total_visit = total_visit + 1, today_visit = today_visit + 1, last_update = '" . time() . "' WHERE url = '" . $url . "' AND relative_url = '" . $relative_url . "'");
					else
						PersistenceContext::get_querier()->insert(StatsSetup::$stats_referer_table, array('url' => $url, 'relative_url' => $relative_url, 'total_visit' => 1, 'today_visit' => 1, 'yesterday_visit' => 0, 'nbr_day' => 1, 'last_update' => time(), 'type' => 0));
				}
			}
		}
	}

    /**
	 * @desc Compute Stats of Site Users
	 */
	public static function compute_users()
	{
		//Inclusion une fois par jour et par visiteur.
		$_SERVER['HTTP_USER_AGENT'] = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';

		if (TextHelper::strpos($_SERVER['HTTP_USER_AGENT'], 'libwww') !== false) //Evite les bots.
			return;

		//Suppression des images de statistiques en cache.
		$array_stats_img = array('browsers.png', 'os.png', 'lang.png');
		foreach ($array_stats_img as $key => $value)
			@unlink(PATH_TO_ROOT . '/cache/' . $value);

		########### Détection des navigateurs ###########
		$array_browser = array(
			'opera' => 'opera',
			'firefox' => 'firefox',
			'msie|internet explorer' => 'internetexplorer',
			'edge' => 'edge',
			'chrome' => 'chrome',
			'safari' => 'safari',
			'konqueror' => 'konqueror',
			'netscape' => 'netscape',
			'seamonkey' => 'seamonkey',
			'mozilla' => 'mozilla',
			'aol' => 'aol',
			'lynx' => 'lynx',
			'camino' => 'camino',
			'links' => 'links',
			'galeon' => 'galeaon',
			'phoenix' => 'phoenix',
			'chimera' => 'chimera',
			'k-meleon' => 'k-meleon',
			'icab' => 'icab',
			'ncsa mosaic'=> 'ncsa mosaic',
			'amaya' => 'amaya',
			'omniweb' => 'omniweb',
			'hotjava' => 'hotjava',
			'browsex' => 'browsex',
			'amigavoyager'=> 'amigavoyager',
			'amiga-aweb'=> 'amiga-aweb',
			'ibrowse' => 'ibrowse',
			'samsung|sony|nokia|blackberry|android|ipod|iphone|opera mini|palm|iemobile|smartphone|symbian' => 'phone'
		);
		if (!empty($_SERVER['HTTP_USER_AGENT']) ) //On ignore si user agent vide.
		{
			$browser = 'other';
			foreach ($array_browser as $regex => $name)
			{
				if (preg_match('`' . $regex . '`iu', $_SERVER['HTTP_USER_AGENT']))
				{
					$browser = $name;
					break;
				}
			}
			self::write_stats('browsers', $browser);
		}

		########### Détection des systèmes d'exploitation ###########
		$array_os = array(
			'android' => 'android',
			'iphone|ipad' => 'ios',
			'windows nt 10.0' => 'windows10',
			'windows nt 6.3' => 'windows8.1',
			'windows nt 6.2' => 'windows8',
			'windows nt 6.1|seven' => 'windowsseven',
			'windows nt 6.0|vista' => 'windowsvista',
			'windows nt 5.2|windows server 2003' => 'windowsserver2003',
			'windows nt 5.1|windows xp' => 'windowsxp',
			'windows nt 5.0|windows 2000' => 'windowsold',
			'winnt|windows nt|windows nt 4.0'  => 'windowsold',
			'windows 98|win98' => 'windowsold',
			'win 9x 4.90|windows me' => 'windowsold',
			'win95|win32|windows 95|windows 3.1' => 'windowsold',
			'windows ce' => 'windowsold',
			'windows phone' => 'windowsphone',
			'linux|x11' => 'linux',
			'macintosh|mac|ppc|powerpc' => 'macintosh',
			'solaris|sunos' => 'sunos',
			'nintendo wii' => 'wii',
			'playstation portable' => 'psp',
			'playstation 3' => 'playstation3',
			'freebsd' => 'freebsd',
			'aix' => 'aix',
			'irix' => 'irix',
			'hp-ux' => 'hp-ux',
			'os2|os/2' => 'os2',
			'netbsd' => 'netbsd',
			'samsung|sony|nokia|blackberry|ipod|opera mini|palm|iemobile|smartphone|symbian' => 'phone'
		);
		if (!empty($_SERVER['HTTP_USER_AGENT']) ) //On ignore si user agent vide.
		{
			$os = 'other';
			foreach ($array_os as $regex => $name)
			{
				if (preg_match('`' . $regex . '`iu', $_SERVER['HTTP_USER_AGENT']))
				{
					$os = $name;
					break;
				}
			}
			self::write_stats('os', $os);
		}

		########### Détection de la langue utilisateur ###########
		if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE']))
		{
			$user_lang = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
			$favorite_lang = !empty($user_lang[0]) ? TextHelper::strtolower($user_lang[0]) : '';
			if (TextHelper::strpos($favorite_lang, '-') !== false)
				$favorite_lang = preg_replace('`[a-z]{2}\-([a-z]{2})`iu', '$1', $favorite_lang);
			$lang = str_replace(array('en', 'cs', 'sv', 'fa', 'ja', 'ko', 'he', 'da', 'gb'), array('uk', 'cz', 'se', 'ir', 'jp', 'kr', 'il', 'dk', 'uk'), $favorite_lang);
			$lang = TextHelper::substr($lang, 0, 2);

			if (!empty($lang)) //On ignore ceux qui n'ont pas renseigné le champs.
			{
				$wlang = 'other';
				if (Countries::is_available($lang))
				{
					$wlang = $lang;
				}
				self::write_stats('lang', $wlang);
			}
		}
	}

	/**
	 * @static
	 */
	public static function register_bot()
	{
		$current_robot = Robots::get_current_robot_name();
		if ($current_robot!== null)
		{
			self::write_stats('robots', $current_robot, false);
		}
	}

	/**
	 * @desc This function is called by the kernel on each displayed page to count the number of pages seen at each hour.
	 */
	public static function update_pages_displayed()
	{
		self::write_stats('pages', Date::to_format(Date::DATE_NOW, 'G'));
	}

	/**
	 * @desc Retrieve stats from file
	 * @param string $file_path The path to the stats file.
	 */
	public static function retrieve_stats($file_path)
	{
		$file = @fopen(PATH_TO_ROOT . '/stats/cache/' . $file_path . '.txt', 'r');
		$stats_array = @fgets($file);
		$stats_array = !empty($stats_array) ? TextHelper::unserialize($stats_array) : array();
		@fclose($file);

		return $stats_array;
	}

    /**
	 * @desc Save stats to file
	 */
	private static function write_stats($file_path, $stats_item, $strtolower = true)
	{
		$file_path = PATH_TO_ROOT . '/stats/cache/' . $file_path . '.txt';
		if (!file_exists($file_path))
		{
			$file = @fopen($file_path, 'w+');
			@fwrite($file, TextHelper::serialize(array()));
			@fclose($file);
		}
		if (is_file($file_path) && is_writable($file_path))
		{
			$line = file($file_path);
			if (isset($line[0]))
			{
				$stats_array = TextHelper::unserialize($line[0]);
				if (isset($stats_array[$strtolower ? TextHelper::strtolower($stats_item) : $stats_item]))
					$stats_array[$strtolower ? TextHelper::strtolower($stats_item) : $stats_item]++;
				else
					$stats_array[$strtolower ? TextHelper::strtolower($stats_item) : $stats_item] = 1;

				$file = @fopen($file_path, 'r+');
				fwrite($file, TextHelper::serialize($stats_array));
				fclose($file);
			}
		}
	}
}
?>
