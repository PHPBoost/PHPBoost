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
			if (MAGIC_QUOTES)
			{
				$var = stripslashes($var);
			}
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
			return TextHelper::strprotect($var, TextHelper::HTML_NO_PROTECT); //Chaine non protégée pour l'html.
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
 * @desc Adds the session ID to an URL if the user doesn't accepts cookies.
 * This functions allows you to generate an URL according to the site configuration concerning the URL rewriting.
 * @param string $url URL if the URL rewriting is disabled
 * @param string $mod_rewrite URL if the URL rewriting is enabled
 * @param string $ampersand In a redirection you mustn't put the & HTML entity (&amp;). In this case set that parameter to &.
 * @return string The URL to use.
 */
function url($url, $mod_rewrite = '')
{

	if (ServerEnvironmentConfig::load()->is_url_rewriting_enabled() && !empty($mod_rewrite)) //Activation du mod rewrite => cookies activés.
	{
		return $mod_rewrite;
	}
	else
	{
		return $url;
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
 * @see MailService::is_mail_valid
 */
function check_mail($mail)
{
	return AppContext::get_mail_service()->is_mail_valid($mail);
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
	global $User, $LANG;

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

	// Décallage du serveur par rapport au méridien de greenwitch et à l'heure d'été
	$serveur_hour = NumberHelper::round(date('Z')/3600, 0) - date('I');

	if ($timezone_system == 1) //Timestamp du site, non dépendant de l'utilisateur.
	{
		$timezone = GeneralConfig::load()->get_site_timezone() - $serveur_hour;
	}
	elseif ($timezone_system == 2) //Timestamp du serveur, non dépendant de l'utilisateur et du fuseau par défaut du site.
	{
		$timezone = 0;
	}
	else //Timestamp utilisateur dépendant de la localisation de l'utilisateur par rapport à serveur.
	{
		$timezone = AppContext::get_user()->get_timezone() - $serveur_hour;
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
	global $User;

	list($month, $day, $year) = array(0, 0, 0);
	$array_timestamp = explode('/', $str);
	$array_date = explode('/', $date_format);
	for ($i = 0; $i < 3; $i++)
	{
		switch ($array_date[$i])
		{
			case 'd':
				$day = (isset($array_timestamp[$i])) ? NumberHelper::numeric($array_timestamp[$i]) : 0;
				break;
			case 'm':
				$month = (isset($array_timestamp[$i])) ? NumberHelper::numeric($array_timestamp[$i]) : 0;
				break;
			case 'y':
				$year = (isset($array_timestamp[$i])) ? NumberHelper::numeric($array_timestamp[$i]) : 0;
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

	$serveur_hour = NumberHelper::round(date('Z')/3600, 0) - date('I'); //Décallage du serveur par rapport au méridien de greenwitch.
	$timezone = $User->get_timezone('user_timezone') - $serveur_hour;
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
				$day = (isset($array_date[$i])) ? NumberHelper::numeric($array_date[$i]) : 0;
				break;
			case 'MM':
				$month = (isset($array_date[$i])) ? NumberHelper::numeric($array_date[$i]) : 0;
				break;
			case 'YYYY':
				$year = (isset($array_date[$i])) ? NumberHelper::numeric($array_date[$i]) : 0;
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

/**
 * @deprecated
 * @desc Displays a confirmation message during a defined delay and then redirects the user.
 * @param string $url_error Url at which you want to redirect him.
 * @param string $l_error The message to show him
 * @param int $delay_redirect Number of seconds after which you want him to be redirected.
 */
function redirect_confirm($url_error, $l_error, $delay_redirect = 3)
{
	global $LANG;

	$template = new FileTemplate('framework/confirm.tpl');

	$template->put_all(array(
		'URL_ERROR' => !empty($url_error) ? $url_error : Environment::get_home_page(),
		'DELAY_REDIRECT' => $delay_redirect,
		'L_ERROR' => $l_error,
		'L_REDIRECT' => $LANG['redirect']
	));

	$template->display();
}

/**
 * @deprecated
 * @desc Returns the current user's theme.
 * @return string The theme identifier (name of its folder).
 */
function get_utheme()
{
	$user = AppContext::get_user();
	return $user->get_theme();
}

/**
 * @deprecated
 * @desc Returns the current user's language.
 * @return string The lang identifier (name of its folder).
 */
function get_ulang()
{
	$user = AppContext::get_user();
	return $user->get_locale();
}

/**
 * @deprecated
 * @desc Returns the HTML code of the user editor. It uses the ContentFormattingFactory class, it allows you to write less code lines.
 * @param string $field The name of the HTTP parameter which you will retrieve the value entered by the user.
 * @param string[] $forbidden_tags The list of the tags you don't want to appear in the editor.
 * @return The HTML code of the editor that you can directly display in a template.
 */
function display_editor($field = 'contents', $forbidden_tags = array())
{
	$editor = AppContext::get_content_formatting_service()->get_default_editor();
	if (!empty($forbidden_tags) && is_array($forbidden_tags))
	{
		$editor->set_forbidden_tags($forbidden_tags);
	}
	$editor->set_identifier($field);

	return $editor->display();
}

/**
 * @deprecated
 * @desc Returns the HTML code of the comments manager.
 * @param string $script
 * @param int $idprov The data base id of the item for which you want to display the commenting interface.
 * @param string $vars The URL of the curent page (the comments API will always redirect the user to the current page). You just have to add a 'com' HTTP parameter
 * for which the value must be %s (it will be used by the comments API).
 * @param string $module_folder The identifier of your module (the name of its folder).
 * @return The HTML code of the commenting interface that you can directly display in a template.
 */
function display_comments($script, $idprov, $vars, $module_folder = '')
{
	$comments = new Comments($script, $idprov, $vars, $module_folder);

	return $comments->display();
}

/**
 * @deprecated
 * @desc Loads a module lang file. It will load alone the file corresponding to the user lang, but if it doesn't exist, another lang will be choosen.
 * An error will be displayed on the page and the script execution will be stopped if no lang file is found for this module.
 * @param string $module_name The identifier of the module for which you want to load the lang file.
 * @param string Path of the folder in which is the file. This path mustn't finish by the / character.
 * @depre
 */
function load_module_lang($module_name, $path = PATH_TO_ROOT)
{
	global $LANG;

	$file = $path . '/' . $module_name . '/lang/' . get_ulang() . '/' . $module_name . '_' . get_ulang() . '.php';
	$result = include_once $file;

	if (!$result)
	{
		$lang = find_require_dir(PATH_TO_ROOT . '/' . $module_name . '/lang/', get_ulang(), false);
		$file2 = PATH_TO_ROOT . '/' . $module_name . '/lang/' . $lang . '/' . $module_name . '_' . $lang . '.php';
		$result2 = include_once $file2;

		if (!$result2)
		{
			$error_message = sprintf('Unable to load lang file \'%s\'!', PATH_TO_ROOT . '/' . $module_name . '/lang/' . $lang . '/' . $module_name . '_' . $lang . '.php');

			$controller = new UserErrorController(LangLoader::get_message('error', 'errors'),
                $error_message, UserErrorController::FATAL);
            DispatchManager::redirect($controller);
		}
	}
}

/**
 * @deprecated
 * @desc Loads a menu lang file. It will load alone the file corresponding to the user lang, but if it doesn't exist, another lang will be choosen.
 * An error will be displayed on the page and the script execution will be stopped if no lang file is found for this menu.
 * @param string $menu_name The identifier of the menu for which you want to load the lang file.
 */
function load_menu_lang($menu_name)
{
	load_module_lang($menu_name, PATH_TO_ROOT . '/menus');
}

/**
 * @deprecated
 * @desc Loads a configuration file. You choose a bases path, and you specify a folder name in which you file should be found, if it doesn't exist, it will take a file in another folder.
 * It's very interesting when you want to
 * @param string $dir_path Path of the file (relative from this page).
 * @param string $require_dir The name of the folder in which the configuration file should be. This folder must be in the bases file ($dir_path). If this directory doesn't exist, another will be read.
 * @param string $ini_name The name of the configuration file you want to know.
 * @return string[] The configuration values contained in the file $dir_path/$require_dir/$ini_name.
 */
function load_ini_file($dir_path, $require_dir, $ini_name = 'config.ini')
{
	$dir = find_require_dir($dir_path, $require_dir, false);
	$file = $dir_path . $dir . '/' . $ini_name;
	if(file_exists($file))
	{
		$result = parse_ini_file($file);
	}
	else
	{
		$result = false;
	}
	return $result;
}

//Cherche un dossier s'il n'est pas trouvé, on parcourt le dossier passé en argument à la recherche du premier dossier.
/**
 * @deprecated
 * @desc Finds a folder according to the user language. You find the file in a folder in which there is one folder per lang.
 * If it doesn't exist, you want to choose the file in another language.
 * This function returns the path of an existing file (if the required lang exists, it will be it, otherwise it will be one of the existing files).
 * @param string $dir_path Path of the folder in which you want to search
 * @param string $require_dir Default folder
 * @param string $fatal_error true if you want to throw a fatal error if no file could be found, false otherwise.
 * @return string The path of the folder you search.
 */
function find_require_dir($dir_path, $require_dir, $fatal_error = true)
{
	//Si le dossier n'existe pas on prend le suivant exisant.
	if (!@file_exists($dir_path . $require_dir))
	{
		if (@is_dir($dir_path) && $dh = @opendir($dir_path)) //Si le dossier existe et qu'on a les permissions suffisantes
		{
			while (!is_bool($dir = readdir($dh)))
			{
				if (strpos($dir, '.') === false  )
				{
					closedir($dh);
					return $dir;
				}
			}
			closedir($dh);
		}
	}
	else
	{
		return $require_dir;
	}

	if ($fatal_error)
	{
		$error_message = sprintf('Unable to load required directory \'%s\'!', $dir_path . $require_dir);

        $controller = new UserErrorController(LangLoader::get_message('error', 'errors'),
            $error_message, UserErrorController::FATAL);
        DispatchManager::redirect($controller);
	}
}

?>
