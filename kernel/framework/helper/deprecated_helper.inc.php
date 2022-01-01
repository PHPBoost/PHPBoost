<?php
/**
 * @package     Helper
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 25
 * @since       PHPBoost 3.0 - 2010 01 22
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

### Variable types ###
define('GET', 		1);
define('POST', 		2);
define('REQUEST', 	3);
define('COOKIE', 	4);
define('FILES', 	5);

define('TBOOL', 			'boolean');
define('TINTEGER', 			'integer');
define('TDOUBLE', 			'double');
define('TFLOAT', 			'double');
define('TSTRING', 			'string');
define('TSTRING_PARSE', 	'string_parse');
define('TSTRING_UNCHANGE', 	'string_unsecure');
define('TSTRING_HTML', 		'string_html');
define('TSTRING_AS_RECEIVED', 'string_unchanged');
define('TARRAY', 			'array');
define('TUNSIGNED_INT', 	'uint');
define('TUNSIGNED_DOUBLE', 	'udouble');
define('TUNSIGNED_FLOAT', 	'udouble');
define('TNONE', 			'none');

define('USE_DEFAULT_IF_EMPTY', 1);

/**
 * @deprecated
 * Retrieves an input variable. You can retrieve any parameter of the HTTP request which launched the execution of this page.
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
 * 	<li>TSTRING_AS_RECEIVED if you want to retrieve the string variable as it was in the HTTP request. </li>
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
	$request = AppContext::get_request();

	switch ($var_type)
	{
		case GET:
			if ($request->has_getparameter($var_name))
			{
				$var = $request->get_getvalue($var_name);
			}
			break;
		case POST:
			if ($request->has_postparameter($var_name))
			{
				$var = $request->get_postvalue($var_name);
			}
			break;
		case REQUEST:
			if ($request->has_parameter($var_name))
			{
				$var = $request->get_value($var_name);
			}
			break;
		case COOKIE:
			if ($request->has_cookieparameter($var_name))
			{
				$var = $request->get_cookie($var_name);
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
			return trim((string)$var); //Chaine non protégée.
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
 * Adds the session ID to an URL if the user doesn't accepts cookies.
 * This functions allows you to generate an URL according to the site configuration concerning the URL rewriting.
 * @param string $url URL if the URL rewriting is disabled
 * @param string $mod_rewrite URL if the URL rewriting is enabled
 * @param string $ampersand In a redirection you mustn't put the & HTML entity (&amp;). In this case set that parameter to &.
 * @return string The URL to use.
 */
function url($url, $mod_rewrite = '', $ampersand = '&amp;')
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
 * Loads a module lang file. It will load alone the file corresponding to the user lang, but if it doesn't exist, another lang will be choosen.
 * An error will be displayed on the page and the script execution will be stopped if no lang file is found for this module.
 * @param string $module_name The identifier of the module for which you want to load the lang file.
 * @param string Path of the folder in which is the file. This path mustn't finish by the / character.
 * @depre
 */
function load_module_lang($module_name, $path = PATH_TO_ROOT)
{
	global $LANG;

	$user_locale = AppContext::get_current_user()->get_locale();

	$module_lang_file = $path . '/lang/' . $user_locale . '/modules/' . $module_name . '/' . $module_name . '_' . $user_locale . '.php';
	if (file_exists($module_lang_file))
	{
		$result = include_once $module_lang_file;
	}
	else
	{
		$file = $path . '/' . $module_name . '/lang/' . $user_locale . '/' . $module_name . '_' . $user_locale . '.php';
		$result = include_once $file;
	}

	if (!$result)
	{
		$lang = find_require_dir(PATH_TO_ROOT . '/' . $module_name . '/lang/', $user_locale, false);
		$file2 = PATH_TO_ROOT . '/' . $module_name . '/lang/' . $lang . '/' . $module_name . '_' . $lang . '.php';
		$result2 = include_once $file2;

		if (!$result2)
		{
			$error_message = sprintf('Unable to load lang file \'%s\'!', PATH_TO_ROOT . '/' . $module_name . '/lang/' . $lang . '/' . $module_name . '_' . $lang . '.php');

			$controller = new UserErrorController(LangLoader::get_message('warning.error', 'warning-lang'),
                $error_message, UserErrorController::FATAL);
            DispatchManager::redirect($controller);
		}
	}
}

/**
 * @deprecated
 * Loads a configuration file. You choose a bases path, and you specify a folder name in which you file should be found, if it doesn't exist, it will take a file in another folder.
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
	if (file_exists($file))
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
 * Finds a folder according to the user language. You find the file in a folder in which there is one folder per lang.
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
				if (TextHelper::strpos($dir, '.') === false  )
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

        $controller = new UserErrorController(LangLoader::get_message('warning.error', 'warning-lang'),
            $error_message, UserErrorController::FATAL);
        DispatchManager::redirect($controller);
	}
}
?>
