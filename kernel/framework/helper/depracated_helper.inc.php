<?php
/*##################################################
 *                             deprecated_helper.inc.php
 *                            -------------------
 *   begin                : Januar 22, 2010
 *   copyright            : (C) 2010 Régis Viarre
 *   email                : crowkait@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
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

define('TIMEZONE_SITE', 1);
define('TIMEZONE_SYSTEM', 2);
define('TIMEZONE_USER', 3);
	
	/**
 * @deprecated
 * @desc Retrieves an input variable. You can retrieve any parameter of the HTTP request which launched the execution of this page.
 * @param int $var_type The origin of the variable: GET if it's a parameter in the request URL, POST if the variable was in a formulary,
 * COOKIE if the variables come from a cookie and FILES if it's a file.
 * @param string $var_name Name of a HTTP variable you want to retrieve.
 * @param mixed $default_value The value you want the variable you retrieve has if the HTTP parameter doesn't exist.
 * @param string $force_type Type of the variable you want to retrieve. If you don't use this parameter, the returned variable will have the same type as the default value you imposed.
 * When you force the variable type, a cast operation will be made from string (it's a string in the HTTP request) to the type you choosed.
 * The types you can use are numerous:
 * <ul>
 * 	<li>TINTEGER to retrieve an integer value.</li>
 * 	<li>TSTRING to retrieve a string. The HTML code in this string is protected (XSS protection) and the dangerous MySQL characters are escaped. You can use this variable directly in a MySQL query.
 * It you want to use it now without inserting it in a data base, use the stripslashes PHP function.</li>
 * 	<li>TSTRING_UNCHANGE if you want to retrieve the value of a string without any processing (no quotes escaping and no HTML protection).</li>
 * 	<li>TSTRING_PARSE if you want to parse the value you retrieved. The HTML code is protected, it parses with the user parser and the quotes are escaped. Ready to be inserted in a MySQL query !</li>
 * 	<li>TBOOL to retrieve a boolean value.</li>
 * 	<li>TUNSIGNED_INT if you expect an unsigned integer.</li>
 * 	<li>TUNSIGNED_DOUBLE to retrieve an unsigned double value.</li>
 * 	<li>TSTRING_HTML if you don't want to protect the HTML code of the content but you want to escape the quotes.</li>
 * 	<li>TSTRING_AS_RECEIVED if you want to retrieve the string variable as it was in the HTTP request. Be careful, if the magic_quotes are enabled (use the MAGIC_QUOTES constant to know it), the quotes are escaped, otherwise they aren't.</li>
 * 	<li>TARRAY to retrieve an array. The values it contains aren't processed.</li>
 * 	<li>TDOUBLE to retrieve a double value</li>
 * 	<li>TNONE if you want to get the input variable as it has been recieved (the return value will be a string because HTTP parameters are all strings).</li>
 * </ul>
 * @param int $flags You can change the behaviour of this method: USE_DEFAULT_IF_EMPTY will allow you to retrieve the default value even if the parameter exists but its value is empty (to know if the var is empty, we use the empty() PHP function).
 * @return mixed The value of the variable you wanted to retrieve. Its type is either the same as the default value or the type you forced.
 */
function retrieve($var_type, $var_name, $default_value, $force_type = NULL, $flags = 0)
{
	$var = null;
	switch ($var_type)
	{
		case GET:
			if (isset($_GET[$var_name]))
			{
				$var = $_GET[$var_name];
			}
			break;
		case POST:
			if (isset($_POST[$var_name]))
			{
				$var = $_POST[$var_name];
			}
			break;
		case REQUEST:
			if (isset($_REQUEST[$var_name]))
			{
				$var = $_REQUEST[$var_name];
			}
			break;
		case COOKIE:
			if (isset($_COOKIE[$var_name]))
			{
				$var = $_COOKIE[$var_name];
			}
			break;
		case FILES:
			if (isset($_FILES[$var_name]))
			{
				$var = $_FILES[$var_name];
			}
			break;
		default:
			break;
	}

	//If $var is not set or an empty value is retrieved with the USE_DEFAULT_IF_EMPTY flag, we return the default value
	if ($var === null || (($flags & USE_DEFAULT_IF_EMPTY != 0) && empty($var)))
	{
		return $default_value;
	}

	$force_type = !isset($force_type) ? gettype($default_value) : $force_type;
	switch ($force_type)
	{
		case TINTEGER:
			return (int)$var;
		case TSTRING:
			return TextHelper::strprotect($var); //Chaine protégée.
		case TSTRING_UNCHANGE:
			if (MAGIC_QUOTES)
			{
				$var = trim(stripslashes($var));
			}
			else
			{
				$var = trim($var);
			}
			return (string)$var; //Chaine non protégée.
		case TSTRING_PARSE:
			return FormatingHelper::strparse($var); //Chaine parsée.
		case TBOOL:
			return (bool)$var;
		case TUNSIGNED_INT:
			$var = (int)$var;
			return $var > 0 ? $var : max(0, $default_value);
		case TUNSIGNED_DOUBLE:
			$var = (double)$var;
			return $var > 0.0 ? $var : max(0.0, $default_value);
		case TSTRING_HTML:
			return TextHelper::strprotect($var, HTML_NO_PROTECT); //Chaine non protégée pour l'html.
		case TSTRING_AS_RECEIVED:
			return (string)$var;
		case TARRAY:
			return (array)$var;
		case TDOUBLE:
			return (double)$var;
		case TNONE:
			return $var;
		default:
			return $default_value;
	}
}

/**
 * @deprecated
 * @desc Returns the config field of a module configuration file.
 * In fact, this field contains the default module configuration in which we can find some " characters. To solve the problem,
 * this field is considered as a comment and when we want to retrieve its value, we have to call this method which returns its value.
 * @param string $dir_path Path in which is the file (stop just before the lang folder)
 * @param string $require_dir Lang folder in which must be the file
 * @param string $ini_name Config file name
 * @return string The config field value.
 */
function get_ini_config($dir_path, $require_dir, $ini_name = 'config.ini')
{
	// TODO remove this function
	$dir = find_require_dir($dir_path, $require_dir, false);

	$module_config_file = new File($dir_path . $dir . '/desc.ini');
	$module_config_text = $module_config_file->read();

	//Maintenant qu'on a le contenu du fichier, on tente d'extraire la dernière ligne qui est commentée car sa syntaxe est incorrecte
	$result = array();

	//Si on détecte le bon motif, on le renvoie
	if (preg_match('`;config="(.*)"\s*$`s', $module_config_text, $result))
	{
		return str_replace('\n', "\r\n", $result[1]);
	}
	//Sinon, on renvoie une chaîne vide
	else
	{
		return '';
	}
}

/**
 * @deprecated
 * @desc Checks if a string could match an email address form.
 * @param string $mail String to check
 * @return bool true if the form is correct, false otherwhise.
 * @see Mail::check_validity
 */
function check_mail($mail)
{
	return Mail::check_validity($mail);
}

/**
 * @deprecated
 * @desc Formats a date according to a specific form.
 * @param string $format Formatting name (date_format, date_format_tiny, date_format_short, date_format_long)
 * @param int $timestamp Time to format (UNIX timestamp)
 * @param int $timezone_system Time zone (1 for the site time zone, 2 for the server time zone and 0 for the user timezone)
 * @return string The formatted date
 * @see Date::format()
 */
function gmdate_format($format, $timestamp = false, $timezone_system = 0)
{
	global $User, $CONFIG, $LANG;

	if (strpos($format, 'date_format') !== false) //Inutile de tout tester si ce n'est pas un formatage prédéfini.
	{
		switch ($format)
		{
			case 'date_format':
				$format = $LANG['date_format'];
				break;
			case 'date_format_tiny':
				$format = $LANG['date_format_tiny'];
				break;
			case 'date_format_short':
				$format = $LANG['date_format_short'];

				break;
			case 'date_format_long':
				$format = $LANG['date_format_long'];
				break;
		}
	}

	if ($timestamp === false)
	{
		$timestamp = time();
	}

	//TODO S'occuper de ce problème de timezone
	date_default_timezone_set('Europe/Paris');
	// Décallage du serveur par rapport au méridien de greenwitch et à l'heure d'été
	$serveur_hour = number_round(date('Z')/3600, 0) - date('I');

	if ($timezone_system == 1) //Timestamp du site, non dépendant de l'utilisateur.
	{
		$timezone = $CONFIG['timezone'] - $serveur_hour;
	}
	elseif ($timezone_system == 2) //Timestamp du serveur, non dépendant de l'utilisateur et du fuseau par défaut du site.
	{
		$timezone = 0;
	}
	else //Timestamp utilisateur dépendant de la localisation de l'utilisateur par rapport à serveur.
	{
		$timezone = AppContext::get_user()->get_attribute('user_timezone') - $serveur_hour;
	}

	if ($timezone != 0)
	{
		$timestamp += $timezone * 3600;
	}

	if ($timestamp <= 0)
	{
		return '';
	}

	return date($format, $timestamp);
}

/**
 * @deprecated
 * @desc Parses a formatted date
 * @param string $str String to parse
 * @param string $date_format Formatting pattern (d for day, m for month and y for year, for instance m/d/y)
 * @return int The timestamp corresponding to the parsed date or 0 if it couldn't be parsed.
 * @see Date::Date()
 */
function strtotimestamp($str, $date_format)
{
	global $CONFIG, $User;

	list($month, $day, $year) = array(0, 0, 0);
	$array_timestamp = explode('/', $str);
	$array_date = explode('/', $date_format);
	for ($i = 0; $i < 3; $i++)
	{
		switch ($array_date[$i])
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
	if (checkdate($month, $day, $year))
	{
		$timestamp = @mktime(0, 0, 1, $month, $day, $year);
	}
	else
	{
		$timestamp = time();
	}

	$serveur_hour = number_round(date('Z')/3600, 0) - date('I'); //Décallage du serveur par rapport au méridien de greenwitch.
	$timezone = $User->get_attribute('user_timezone') - $serveur_hour;
	if ($timezone != 0)
	{
		$timestamp -= $timezone * 3600;
	}

	return ($timestamp > 0) ? $timestamp : time();
}

//Convertit une chaîne au format $LANG['date_format'] (ex:DD/MM/YYYY) en type DATE, si la date saisie est valide sinon retourne 0000-00-00.
/**
 * @deprecated
 * @desc Converts a formatted date to the SQL date format.
 * @param string $str Formatted date
 * @param string $date_format Formatting pattern (DD for the day, MM for the month and YYYY for the year separated only by / characters).
 * @return string The formatted date
 */
function strtodate($str, $date_format)
{
	list($month, $day, $year) = array(0, 0, 0);
	$array_date = explode('/', $str);
	$array_format = explode('/', $date_format);
	for ($i = 0; $i < 3; $i++)
	{
		switch ($array_format[$i])
		{
			case 'DD':
				$day = (isset($array_date[$i])) ? numeric($array_date[$i]) : 0;
				break;
			case 'MM':
				$month = (isset($array_date[$i])) ? numeric($array_date[$i]) : 0;
				break;
			case 'YYYY':
				$year = (isset($array_date[$i])) ? numeric($array_date[$i]) : 0;
				break;
		}
	}

	//Vérification du format de la date.
	if (checkdate($month, $day, $year))
	{
		$date = $year . '-' . $month . '-' . $day;
	}
	else
	{
		$date = '0000-00-00';
	}

	return $date;
}

/**
 * @deprecated
 * @desc Deletes a file
 * @param string $file Path of the file to delete
 * @return bool true if the file could be deleted, false if an error occured.
 */
function delete_file($filepath)
{
	$file = new File($filepath);
	try
	{
		$file->delete();
		return true;
	}
	catch (IOException $exception)
	{
		return false;
	}
}

?>
