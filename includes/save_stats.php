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

if( defined('PHP_BOOST') !== true )
	exit;
	
//Si ce n'est pas un bot (deuxième vérification), après celle par ip.
if( !preg_match('`(w3c|http:\/\/|bot|spider|Gigabot|gigablast.com)+`isU', $_SERVER['HTTP_USER_AGENT']) )
{
	//Suppression des images de statistiques en cache.
	$array_stats_img = array('browser.png', 'os.png', 'lang.png');
	foreach($array_stats_img as $key => $value)
		@unlink('../cache/' . $value);
	
	include_once('../lang/' . $CONFIG['lang'] . '/stats.php');
		
	//Enregistrement	
	function write_stats($file_path, $stats_item)
	{
		$file_path = '../cache/' . $file_path . '.txt';
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
			$stats_array[strtolower($stats_item)]++;
			
			$file = @fopen($file_path, 'r+');	
			fwrite($file, serialize($stats_array));
			fclose($file);
		}
	}
	
	########### Détection des navigateurs ###########
	$array_browser = array(
		'opera' => 'opera',
		'firefox' => 'firefox',
		'MSIE|internet explorer|' => 'internetexplorer',
		'safari' => 'safari',
		'konqueror' => 'konqueror',
		'lynx' => 'lynx',
		'netscape' => 'netscape',
		'mozilla' => 'mozilla'
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
		'Macintosh|Mac|PPC' => 'macintosh',
		'Windows NT 5.2|Windows Server 2003' => 'windowsserver2003',
		'Windows NT 5.0|Windows 2000' => 'windows2000',
		'WinNT|Windows NT'  => 'windowsnt',
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