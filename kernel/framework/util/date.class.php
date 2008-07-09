<?php
/*##################################################
 *                                date.class.php
 *                            -------------------
 *   begin                : June 1st, 2008
 *   copyright          : (C) 2008 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
 *
 *   Date 1.0
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

define('DATE_TIMESTAMP', 0);
define('DATE_NOW', 1);
define('DATE_YEAR_MONTH_DAY', 2);
define('DATE_YEAR_MONTH_DAY_HOUR_MINUTE_SECOND', 3);
define('DATE_FROM_STRING', 4);
define('DATE_FORMAT_TINY', 1);
define('DATE_FORMAT_SHORT', 2);
define('DATE_FORMAT', 3);
define('DATE_FORMAT_LONG', 4);
define('DATE_RFC822_F', 5);
define('DATE_RFC3339_F', 6);

define('DATE_RFC822_FORMAT', 'D, d M Y H:i:s O');
define('DATE_RFC3339_FORMAT', 'Y-m-d\TH:i:s');

define('TIMEZONE_AUTO', TIMEZONE_USER);

class Date
{
	// Constructeur selon différents formats
	function Date()
	{
		global $CONFIG;
		
		//Nombre d'arguments
		$num_args = func_num_args();
		
		if( $num_args == 0 )
			$format = DATE_NOW;
		else
			$format = func_get_arg(0);
		
		if( $format != DATE_NOW )
		{
			// Fuseau horaire
			if( func_get_arg(1) !== false )
				$referencial_timezone = func_get_arg(1);
			else
				$referencial_timezone = TIMEZONE_USER;
			
			$time_difference = $this->compute_serveur_user_difference($referencial_timezone);
		}
		
		switch($format)
		{
			case DATE_NOW:
				$this->timestamp = time();
				break;
				
			// Année mois jour
			case DATE_YEAR_MONTH_DAY:
				if( $num_args >= 5 )
				{
					$year = func_get_arg(3);
					$month = func_get_arg(4);
					$day = func_get_arg(2);
					$this->timestamp = mktime(0, 0, 0, $year, $month, $day) - $time_difference * 3600;
				}
				break;
				
			// Année mois jour heure minute seconde
			case DATE_YEAR_MONTH_DAY_HOUR_MINUTE_SECOND:
				if( $num_args >= 7 )
				{
					$hour = func_get_arg(5);
					$minute = func_get_arg(6);
					$seconds = func_get_arg(7);
					$month = func_get_arg(3);
					$day = func_get_arg(4);
					$year = func_get_arg(2);
					$this->timestamp = mktime($hour, $minute, $seconds, $month, $day, $year) - $time_difference * 3600;
				}
				break;
				
			case DATE_TIMESTAMP:
				$this->timestamp = func_get_arg(2) - $time_difference * 3600;
				break;
				
			case DATE_FROM_STRING:
				list($month, $day, $year) = array(0, 0, 0);
				$str = func_get_arg(2);
				$date_format = func_get_arg(3);
			    $array_timestamp = explode('/', $str);
			    $array_date = explode('/', $date_format);
			    for($i = 0; $i < 3; $i++)
			    {
			        switch($array_date[$i])
			        {
			            case 'd':
			            $day = (isset($array_timestamp[$i])) ? numeric($array_timestamp[$i]) : 0;
			            break;
						
			            case 'm':
			            $month = (isset($array_timestamp[$i])) ? numeric($array_timestamp[$i]) : 0;
			            break;
						
			            case 'y':
			            $year = (isset($array_timestamp[$i])) ? numeric($array_timestamp[$i]) : 0;
			            break;
			        }
			    }

			    //Vérification du format de la date.
			    if( $this->Check_date($month, $day, $year) )
			        $this->timestamp = @mktime(0, 0, 1, $month, $day, $year) - $time_difference * 3600;
				else
			        $this->timestamp = time();
				break;
				
			default:
				$this->timestamp = 0;
		}
	}
	
	// Fonction qui retourne la date formatée
	function format($format, $referencial_timezone = TIMEZONE_USER)
	{
		global $LANG, $CONFIG;
		
		$timestamp = $this->timestamp - $this->compute_serveur_user_difference($referencial_timezone) * 3600;
		
		switch($format)
		{
			case DATE_FORMAT_TINY:
				return date($LANG['date_format_tiny'], $timestamp);
				break;
				
			case DATE_FORMAT_SHORT:
				return date($LANG['date_format_short'], $timestamp);
				break;
			case DATE_FORMAT:
				return date($LANG['date_format'], $timestamp);
				break;
				
			case DATE_FORMAT_LONG:
				return date($LANG['date_format_long'], $timestamp);
				break;
				
			case DATE_TIMESTAMP:
				return $timestamp;
				break;
                
            case DATE_RFC822_F:
                return date(DATE_RFC822_FORMAT, $timestamp);
                break;
                
            case DATE_RFC3339_F:
                return date(DATE_RFC3339_FORMAT, $timestamp) . ($CONFIG['timezone'] < 0 ? '-' : '+') . sprintf('%02d:00',$CONFIG['timezone']);
                break;
			 
			default:
				return '';
		}
	}
	
	//Renvoie le timestamp
	function Get_timestamp()
	{
		return $this->timestamp - $this->compute_serveur_user_difference(TIMEZONE_USER) * 3600;
	}
	
	// Renvoie l'année
	function Get_year()
	{
		return date('Y', $this->timestamp - $this->compute_serveur_user_difference(TIMEZONE_USER) * 3600);
	}

	// Renvoie le mois
	function Get_month()
	{
		return date('m', $this->timestamp - $this->compute_serveur_user_difference(TIMEZONE_USER) * 3600);
	}
	
	//Renvoie le jour
	function Get_day()
	{
		return date('d', $this->timestamp - $this->compute_serveur_user_difference(TIMEZONE_USER) * 3600);
	}
	
	//Renvoie l'heure
	function Get_hours()
	{
		return date('H', $this->timestamp - $this->compute_serveur_user_difference(TIMEZONE_USER) * 3600);
	}
	
	//Renvoie les minutes
	function Get_minutes()
	{
		return date('i', $this->timestamp - $this->compute_serveur_user_difference(TIMEZONE_USER) * 3600);
	}
	
	//Renvoie les secondes
	function Get_seconds()
	{
		return date('s', $this->timestamp - $this->compute_serveur_user_difference(TIMEZONE_USER) * 3600);
	}
	
	//Renvoie une chaine au format YYYY-mm-dd
	function To_date()
	{
		return date('Y-m-d', $this->timestamp);
	}
	
	# This should be static#
	//Function which determines wether a date is correct
	function Check_date($month, $day, $year)
	{
		return checkdate($month, $day, $year);
	}
	
	## Private ##
	//Renvoie le nombre d'heures de décalage par rapport à un certain référentiel
	#This should be static#
	function compute_serveur_user_difference($referencial_timezone = 0)
	{
		global $CONFIG, $Member;
		
		// Décallage du serveur par rapport au méridien de greenwitch et à l'heure d'été
	    $server_hour = number_round(date('Z')/3600, 0) - date('I');
	    
		switch($referencial_timezone)
		{
			// Référentiel : heure du site
			case TIMEZONE_SITE:
				$timezone = $CONFIG['timezone'] - $server_hour;
				break;
				
			//Référentiel : heure du serveur
			case TIMEZONE_SYSTEM:
				$timezone = 0;
				break;
				
			case TIMEZONE_USER:
				$timezone = $Member->Get_attribute('user_timezone') - $server_hour;
				break;
			
			default:
				$timezone = 0;
		}
	    return $timezone;
	}
	
	// Timestamp correspondant à la date
	var $timestamp = 0;
}


?>