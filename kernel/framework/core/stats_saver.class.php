<?php
/*##################################################
 *                                stats_saver.class.php
 *                            -------------------
 *   begin                : July 23, 2008
 *   copyright          : (C) 2008 Viarre Régis
 *   email                : crowkait@phpboost.com
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

global $CONFIG;
include_once(PATH_TO_ROOT . '/lang/' . $CONFIG['lang'] . '/stats.php');

/**
 * @author Viarre Régis crowkait@phpboost.com
  * @desc 
  * @package core
  */
class StatsSaver
{
	## Public Methods ##
    /**
	 * @desc Compute Stats of Site Referers
	 */
	function compute_referer()
	{
		global $Sql;
		
		$referer = !empty($_SERVER['HTTP_REFERER']) ? parse_url($_SERVER['HTTP_REFERER']) : '';
		if (!empty($referer))
		{
			########### Détection des mots clés ###########
			$is_search_engine = false;
			$search_engine = '';
			if (!empty($referer['host']))
			{
				$array_search = array('google', 'search.live', 'search.msn', 'yahoo', 'exalead', 'altavista', 'lycos', 'ke.voila', 'recherche.aol');
				foreach ($array_search as $search_engine_check)
				{	
					if (strpos($referer['host'], $search_engine_check) !== false)
					{
						$is_search_engine = true;
						$search_engine = $search_engine_check;
						break;
					}
				}	
			}

			if ($is_search_engine)
			{
				$query = !empty($referer['query']) ? $referer['query'] : '';
				$keyword = strtolower(preg_replace('`(?:.*)(?:q|p|query|rdata)=([^&]+)(?:.*)`i', '$1', $query));
				$keyword = addslashes(str_replace('+', ' ', urldecode($keyword)));
				
				$check_search_engine = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_STATS_REFERER . " WHERE url = '" . $search_engine . "' AND relative_url = '" . $keyword . "'", __LINE__, __FILE__);
				if (!empty($keyword))
				{
					if (!empty($check_search_engine))
						$Sql->query_inject("UPDATE " . DB_TABLE_STATS_REFERER . " SET total_visit = total_visit + 1, today_visit = today_visit + 1, last_update = '" . time() . "' WHERE url = '" . $search_engine . "' AND relative_url = '" . $keyword . "'", __LINE__, __FILE__);			
					else
						$Sql->query_inject("INSERT INTO " . DB_TABLE_STATS_REFERER . " (url, relative_url, total_visit, today_visit, yesterday_visit, nbr_day, last_update, type) VALUES ('" . $search_engine . "', '" . $keyword . "', 1, 1, 1, 1, '" . time() . "', 1)", __LINE__, __FILE__);
				}
			}
			elseif (!empty($referer['host']))
			{
				$referer['scheme'] = !empty($referer['scheme']) ? $referer['scheme'] : 'http';
				########### Détection du site de provenance ###########
				$url = addslashes($referer['scheme'] . '://' . $referer['host']);
				if (strpos($url, HOST) === false)
				{				
					$referer['path'] = !empty($referer['path']) ? $referer['path'] : '';
					$relative_url = addslashes(((substr($referer['path'], 0, 1) == '/') ? $referer['path'] : ('/' . $referer['path'])) . (!empty($referer['query']) ? '?' . $referer['query'] : '') . (!empty($referer['fragment']) ? '#' . $referer['fragment'] : ''));
					
					$check_url = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_STATS_REFERER . " WHERE url = '" . $url . "' AND relative_url = '" . $relative_url . "'", __LINE__, __FILE__);
					if (!empty($check_url))
						$Sql->query_inject("UPDATE " . DB_TABLE_STATS_REFERER . " SET total_visit = total_visit + 1, today_visit = today_visit + 1, last_update = '" . time() . "' WHERE url = '" . $url . "' AND relative_url = '" . $relative_url . "'", __LINE__, __FILE__);			
					else
						$Sql->query_inject("INSERT INTO " . DB_TABLE_STATS_REFERER . " (url, relative_url, total_visit, today_visit, yesterday_visit, nbr_day, last_update, type) VALUES ('" . $url . "', '" . $relative_url . "', 1, 1, 1, 1, '" . time() . "', 0)", __LINE__, __FILE__);
				}
			}
		}
	}
	
    /**
	 * @desc Compute Stats of Site Users
	 */
	function compute_users()
	{
		global $stats_array_lang;
		
		//Inclusion une fois par jour et par visiteur.
		$_SERVER['HTTP_USER_AGENT'] = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
		
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'libwww') !== false) //Evite les bots.
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
			'chrome' => 'chrome', 
			'safari' => 'safari',
			'konqueror' => 'konqueror',
			'netscape' => 'netscape',
			'seamonkey' => 'seamonkey',
			'mozilla firebird' => 'mozilla firebird',
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
			'amaya'	=> 'amaya',
			'omniweb' => 'omniweb',
			'hotjava' => 'hotjava',
			'browsex' => 'browsex',
			'amigavoyager'=> 'amigavoyager',
			'amiga-aweb'=> 'amiga-aweb',
			'ibrowse' => 'ibrowse',
			'samsung|sony|nokia' => 'phone'
		);
		if (!empty($_SERVER['HTTP_USER_AGENT']) ) //On ignore si user agent vide.
		{
			$browser = 'other';
			foreach ($array_browser as $regex => $name)
			{
				if (preg_match('`' . $regex . '`i', $_SERVER['HTTP_USER_AGENT']))
				{
					$browser = $name;
					break;
				}
			}
			StatsSaver::_write_stats('browsers', $browser);
		}
		
		########### Détection des systèmes d'exploitation ###########
		$array_os = array(
			'windows nt 6.1|seven' => 'windowsseven',
			'windows nt 6.0|vista' => 'windowsvista',
			'windows nt 5.1|windows xp' => 'windowsxp',
			'linux|x11' => 'linux',
			'macintosh|mac|ppc|powerpc' => 'macintosh',
			'windows nt 5.2|windows server 2003' => 'windowsserver2003',
			'windows nt 5.0|windows 2000' => 'windows2000',
			'winnt|windows nt|windows nt 4.0'  => 'windowsnt',
			'windows 98|win98' => 'windows98',
			'win 9x 4.90|windows me' => 'windowsme',
			'win95|win32|windows 95|windows 3.1' => 'windows95',
			'windows ce' => 'windowsce',
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
			'samsung|sony|nokia' => 'phone'
		);
		if (!empty($_SERVER['HTTP_USER_AGENT']) ) //On ignore si user agent vide.
		{
			$os = 'other';
			foreach ($array_os as $regex => $name)
			{
				if (preg_match('`' . $regex . '`i', $_SERVER['HTTP_USER_AGENT']))
				{
					$os = $name;
					break;
				}
			}		
			StatsSaver::_write_stats('os', $os);
		}		
		
		########### Détection de la langue utilisateur ###########
		if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE']))
		{
			$user_lang = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
			$favorite_lang = !empty($user_lang[0]) ? strtolower($user_lang[0]) : '';
			if (strpos($favorite_lang, '-') !== false)		
				$favorite_lang = preg_replace('`[a-z]{2}\-([a-z]{2})`i', '$1', $favorite_lang);			
			$lang = str_replace(array('en', 'cs', 'sv', 'fa', 'ja', 'ko', 'he', 'da'), array('uk', 'cz', 'se', 'ir', 'jp', 'kr', 'il', 'dk'), $favorite_lang);
			
			if (!empty($lang)) //On ignore ceux qui n'ont pas renseigné le champs.
			{
				$wlang = 'other';
				if (isset($stats_array_lang[$lang]))
					$wlang = $lang;
				elseif (isset($stats_array_lang[substr($lang, 0, 2)]))
					$wlang = substr($lang, 0, 2);

				StatsSaver::_write_stats('lang', $wlang);
			}
		}
	}

	## Private Methods ##
    /**
	 * @desc Save stats to file
	 */
	function _write_stats($file_path, $stats_item)
	{
		$file_path = PATH_TO_ROOT . '/cache/' . $file_path . '.txt';
		if (!file_exists($file_path)) 
		{
			$file = @fopen($file_path, 'w+');
			@fwrite($file, serialize(array()));
			@fclose($file);
		}
		if (is_file($file_path) && is_writable($file_path))
		{		
			$line = file($file_path);
			$stats_array = unserialize($line[0]);
			if (isset($stats_array[strtolower($stats_item)]))
				$stats_array[strtolower($stats_item)]++;
			else
				$stats_array[strtolower($stats_item)] = 1;
			
			$file = @fopen($file_path, 'r+');	
			fwrite($file, serialize($stats_array));
			fclose($file);
		}
	}
	
	
	## Private Attribute ##
}

?>