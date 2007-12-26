<?php
/*##################################################
 *                            display_stats.php
 *                            -------------------
 *   begin                : August 26, 2007
 *   copyright          : (C) 2007 Viarre Régis
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

$get_brw = !empty($_GET['browsers']) ? true : false;
$get_os = !empty($_GET['os']) ? true : false;
$get_lang = !empty($_GET['lang']) ? true : false;
$get_bot = !empty($_GET['bot']) ? true : false;
$get_theme = !empty($_GET['theme']) ? true : false;
$get_sex = !empty($_GET['sex']) ? true : false;

include_once('../includes/begin.php');

if( $get_brw || $get_os || $get_lang || $get_bot || $get_sex || $get_theme )
{
	include_once('../lang/' . $CONFIG['lang'] . '/stats.php');
	
	//Navigateurs.
	if( $get_brw )
	{
		//On lit le fichier
		$file = @fopen('../cache/browsers.txt', 'r');
		$browsers_serial = @fgets($file);
		$array_browsers = !empty($browsers_serial) ? unserialize($browsers_serial) : array();
		$array_stats = array();
		$percent_other = 0;
		foreach($array_browsers as $name => $value)
		{
			if( isset($stats_array_browsers[$name]) && $name != 'other' )
				$array_stats[$stats_array_browsers[$name][0]] = $value;
			else
				$percent_other += $value;
		}
		if( $percent_other > 0 )
			$array_stats[$stats_array_browsers['other'][0]] = $percent_other;
			
		@fclose($file);
		$img_name = 'browsers';
	}
	elseif( $get_os )
	{
		//On lit le fichier
		$file = @fopen('../cache/os.txt', 'r');
		$os_serial = @fgets($file);
		$array_os = !empty($os_serial) ? unserialize($os_serial) : array();
		$array_stats = array();
		$percent_other = 0;
		foreach($array_os as $name => $value)
		{
			if( isset($stats_array_os[$name]) && $name != 'other' )
				$array_stats[$stats_array_os[$name][0]] = $value;
			else
				$percent_other += $value;
		}
		if( $percent_other > 0 )
			$array_stats[$stats_array_os['other'][0]] = $percent_other;
		@fclose($file);
		$img_name = 'os';
	}	
	elseif( $get_lang )
	{
		//On lit le fichier
		$file = @fopen('../cache/lang.txt', 'r');
		$lang_serial = @fgets($file);
		$array_lang = !empty($lang_serial) ? unserialize($lang_serial) : array();
		$array_stats = array();
		$percent_other = 0;
		foreach($array_lang as $name => $value)
		{
			foreach($stats_array_lang as $regex => $array_country)
			{
				if( preg_match('`' . $regex . '`', $name) )
				{	
					if( $name != 'other' )
						$array_stats[$array_country[0]] = $value;
					else
						$percent_other += $value;
					break;
				}
			}
		}
		if( $percent_other > 0 )
			$array_stats[$stats_array_lang['other'][0]] = $percent_other;

		@fclose($file);
		$img_name = 'lang';
	}
	elseif( $get_theme )
	{
		include_once('../includes/begin.php');
		define('TITLE', '');
		include_once('../includes/header_no_display.php');
		
		$array_stats = array();
		$result = $sql->query_while("SELECT at.theme, COUNT(m.user_theme) AS compt
		FROM ".PREFIX."themes AS at
		LEFT JOIN ".PREFIX."member AS m ON m.user_theme = at.theme
		GROUP BY at.theme", __LINE__, __FILE__);
		while($row = $sql->sql_fetch_assoc($result))
		{
			$name = isset($info_theme['name']) ? $info_theme['name'] : $row['theme'];
			$array_stats[$name] = $row['compt'];
		}	
		$sql->close($result);
		$img_name = 'theme';
	}
	elseif( $get_sex )
	{
		include_once('../includes/begin.php');
		define('TITLE', '');
		include_once('../includes/header_no_display.php');
		
		$array_stats = array();
		$result = $sql->query_while("SELECT count(user_sex) as compt, user_sex
		FROM ".PREFIX."member
		GROUP BY user_sex
		ORDER BY compt", __LINE__, __FILE__);
		while($row = $sql->sql_fetch_assoc($result))
		{
			switch($row['user_sex'])
			{
				case 0:
				$name = $LANG['unknow'];
				break;
				case 1:
				$name = $LANG['male'];
				break;
				case 2:
				$name = $LANG['female'];
				break;
			}
			$array_stats[$name] = $row['compt'];
		}	
		$sql->close($result);
		$img_name = 'sex';
	}
	elseif( $get_bot )
	{
		//On lit le fichier
		$file = @fopen('../cache/robots.txt', 'r');
		$robot_serial = @fgets($file);	
		$array_robot = !empty($robot_serial) ? unserialize($robot_serial) : array('other' => 0);
		$array_stats = array();
		if( is_array($array_robot) )
		{
			foreach($array_robot as $key => $value)
			{
				$array_info = explode('/', $value);			
				if( isset($array_info[0]) && isset($array_info[1]) )
					$array_stats[$array_info[0]] = $array_info[1];
			}
		}
		@fclose($file);
		$img_name = 'bot';
	}
	
	if( count($array_stats) == 0 )
		$array_stats = array('other' => 0);

	include_once('../includes/stats.class.php');
	$stats = new Stats();
		
	$stats->load_statsdata($array_stats, 'ellipse');
	//Tracé de l'ellipse.
	$stats->draw_ellipse(210, 100, '../cache/' . $img_name . '.png');
}

?>