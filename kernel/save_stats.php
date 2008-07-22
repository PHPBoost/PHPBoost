<?php
/*##################################################
 *                              save_stats.php
 *                            -------------------
 *   begin                : January 31, 2006
 *   copyright          : (C) 2006 Viarre Régis
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

if( defined('PHPBOOST') !== true )
	exit;
	
include_once(PATH_TO_ROOT . '/lang/' . $CONFIG['lang'] . '/stats.php');
$referer = !empty($_SERVER['HTTP_REFERER']) ? parse_url($_SERVER['HTTP_REFERER']) : '';
if( !empty($referer) )
{
	########### Détection des mots clés ###########
	$is_search_engine = false;
	$search_engine = '';
	$array_search = array('google', 'search.live', 'search.msn', 'yahoo', 'exalead', 'altavista', 'lycos', 'ke.voila', 'recherche.aol');
	foreach($array_search as $search_engine_check)
	{	
		if( strpos($referer['host'], $search_engine_check) !== false )
		{
			$is_search_engine = true;
			$search_engine = $search_engine_check;
			break;
		}
	}	

	if( $is_search_engine )
	{
		$query = !empty($referer['query']) ? $referer['query'] : '';
		$keyword = strprotect(strtolower(str_replace('+', ' ', preg_replace('`(?:.*)(?:q|p|query|rdata)=([^&]+)(?:.*)`i', '$1', $query))));
		
		$check_search_engine = $Sql->Query("SELECT COUNT(*) FROM ".PREFIX."stats_referer WHERE url = '" . $search_engine . "' AND relative_url = '" . $keyword . "'", __LINE__, __FILE__);
		if( !empty($keyword) )
		{
			if( !empty($check_search_engine) )
				$Sql->Query_inject("UPDATE ".PREFIX."stats_referer SET total_visit = total_visit + 1, today_visit = today_visit + 1, last_update = '" . time() . "' WHERE url = '" . $search_engine . "' AND relative_url = '" . $keyword . "'", __LINE__, __FILE__);			
			else
				$Sql->Query_inject("INSERT INTO ".PREFIX."stats_referer (url, relative_url, total_visit, today_visit, yesterday_visit, nbr_day, last_update, type) VALUES ('" . $search_engine . "', '" . $keyword . "', 1, 1, 1, 1, '" . time() . "', 1)", __LINE__, __FILE__);
		}
	}
	elseif( !empty($referer['host']) )
	{
		########### Détection du site de provenance ###########
		$url = strprotect($referer['scheme'] . '://' . $referer['host']);
		if( strpos($url, HOST) === false )
		{				
			$relative_url = strprotect(((substr($referer['path'], 0, 1) == '/') ? $referer['path'] : ('/' . $referer['path'])) . (!empty($referer['query']) ? '?' . $referer['query'] : '') . (!empty($referer['fragment']) ? '#' . $referer['fragment'] : ''));
			
			$check_url = $Sql->Query("SELECT COUNT(*) FROM ".PREFIX."stats_referer WHERE url = '" . $url . "' AND relative_url = '" . $relative_url . "'", __LINE__, __FILE__);
			if( !empty($check_url) )
				$Sql->Query_inject("UPDATE ".PREFIX."stats_referer SET total_visit = total_visit + 1, today_visit = today_visit + 1, last_update = '" . time() . "' WHERE url = '" . $url . "' AND relative_url = '" . $relative_url . "'", __LINE__, __FILE__);			
			else
				$Sql->Query_inject("INSERT INTO ".PREFIX."stats_referer (url, relative_url, total_visit, today_visit, yesterday_visit, nbr_day, last_update, type) VALUES ('" . $url . "', '" . $relative_url . "', 1, 1, 1, 1, '" . time() . "', 0)", __LINE__, __FILE__);
		}
	}
}

//Inclusion une fois par jour et par visiteur.
if( $_include_once ) //Variable provenant de sessions.class.php
{
	$_SERVER['HTTP_USER_AGENT'] = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
	
	//Suppression des images de statistiques en cache.
	$array_stats_img = array('browsers.png', 'os.png', 'lang.png');
	foreach($array_stats_img as $key => $value)
		@unlink(PATH_TO_ROOT . '/cache/' . $value);
		
	//Enregistrement	
	function write_stats($file_path, $stats_item)
	{
		$file_path = PATH_TO_ROOT . '/cache/' . $file_path . '.txt';
		if( !file_exists($file_path) ) 
		{
			$file = @fopen($file_path, 'w+');
			@fwrite($file, serialize(array()));
			@fclose($file);
		}
		if( is_file($file_path) && is_writable($file_path) )
		{		
			$line = file($file_path);
			$stats_array = unserialize($line[0]);
			if( isset($stats_array[strtolower($stats_item)]) )
				$stats_array[strtolower($stats_item)]++;
			else
				$stats_array[strtolower($stats_item)] = 1;
			
			$file = @fopen($file_path, 'r+');	
			fwrite($file, serialize($stats_array));
			fclose($file);
		}
	}
	
	########### Détection des navigateurs ###########
	$array_browser = array(
		'opera' => 'opera',
		'firefox' => 'firefox',
		'MSIE|internet explorer' => 'internetexplorer',
		'safari' => 'safari',
		'konqueror' => 'konqueror',
		'netscape' => 'netscape',
		'seamonkey' => 'SeaMonkey',
		'mozilla firebird' => 'Mozilla firebird',
		'mozilla' => 'mozilla', 
		'aol' => 'aol',
		'lynx' => 'lynx',
		'camino' => 'Camino',
		'links' => 'Links',
		'galeon' => 'Galeaon',
		'phoenix' => 'Phoenix',
		'chimera' => 'Chimera',
		'k-meleon' => 'K-meleon',
		'icab' => 'Icab',
		'ncsa mosaic'=> 'Ncsa mosaic',
		'amaya'	=> 'Amaya',
		'omniweb' => 'Omniweb',
		'hotjava' => 'Hotjava',
		'browsex' => 'Browsex',
		'amigavoyager'=> 'Amigavoyager',
		'amiga-aweb'=> 'Amiga-aweb',
		'ibrowse' => 'Ibrowse'
	);
	$browser = 'other';
	foreach($array_browser as $regex => $name)
	{
		if( preg_match('`' . $regex . '`i', $_SERVER['HTTP_USER_AGENT']) )
		{
			$browser = $name;
			break;
		}
	}		
	write_stats('browsers', $browser);
	
	########### Détection des systèmes d'exploitation ###########
	$array_os = array(
		'windows NT 6.0|Vista' => 'windowsvista',
		'Windows NT 5.1|Windows XP' => 'windowsxp',
		'Linux' => 'linux',
		'Macintosh|Mac|PPC|PowerPC' => 'macintosh',
		'Windows NT 5.2|Windows Server 2003' => 'windowsserver2003',
		'Windows NT 5.0|Windows 2000' => 'windows2000',
		'WinNT|Windows NT|Windows NT 4.0'  => 'windowsnt',
		'Windows 98|Win98' => 'windows98',
		'Win 9x 4.90|Windows Me' => 'windowsme',
		'Win95|Win32|Windows 95' => 'windows95',
		'Solaris|SunOS' => 'sunos',
		'Nintendo Wii' => 'wii',
		'PlayStation Portable' => 'psp',
		'PLAYSTATION 3' => 'playstation3',
		'FreeBSD' => 'freebsd',
		'AIX' => 'aix',
		'IRIX' => 'irix',
		'HP-UX' => 'hp-ux', 
		'os2|OS/2' => 'os2',
		'NetBSD' => 'netbsd'
	);
	$os = 'other';
	foreach($array_os as $regex => $name)
	{
		if( preg_match('`' . $regex . '`i', $_SERVER['HTTP_USER_AGENT']) )
		{
			$os = $name;
			break;
		}
	}		
	write_stats('os', $os);
	
	########### Détection de la langue utilisateur ###########
	if( !empty($_SERVER['HTTP_ACCEPT_LANGUAGE']) )
	{
		$user_lang = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
		$favorite_lang = strtolower($user_lang[0]);	
		if( strpos($favorite_lang, '-') !== false )		
			$favorite_lang = preg_replace('`[a-z]{2}\-([a-z]{2})`i', '$1', $favorite_lang);			
		$lang = str_replace(array('cs', 'sv', 'fa', 'ja', 'ko', 'he', 'da'), array('cz', 'se', 'ir', 'jp', 'kr', 'il', 'dk'), $favorite_lang);
		
		$lang = 'other';
		if( isset($stats_array_lang[$favorite_lang]) )
			$lang = $favorite_lang;
			
		write_stats('lang', $lang);
	}
}

?>