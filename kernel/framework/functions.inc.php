<?php
/*##################################################
 *                             functions.inc.php
 *                            -------------------
 *   begin                : June 13, 2005
 *   copyright            : (C) 2005 Régis Viarre, Loïc Rouchon
 *   email                : crowkait@phpboost.com, horn@phpboost.com
 *
 *   Function 2.0.0
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

define('HTML_NO_PROTECT', false);
define('HTML_PROTECT', true);
//Automatique : échappe seulement si le serveur n'échappe pas automatiquement
define('ADDSLASHES_AUTO', 0);
//Force l'échappement des caractères critique
define('ADDSLASHES_FORCE', 1);
//Aucun échappement
define('ADDSLASHES_NONE', 2);
define('MAGIC_QUOTES_DISABLED', false);
define('NO_UPDATE_PAGES', true);
define('NO_FATAL_ERROR', false);
define('NO_EDITOR_UNPARSE', false);
define('TIMEZONE_SITE', 1);
define('TIMEZONE_SYSTEM', 2);
define('TIMEZONE_USER', 3);

/**
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
			return strprotect($var); //Chaine protégée.
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
			return strparse($var); //Chaine parsée.
		case TBOOL:
			return (bool)$var;
		case TUNSIGNED_INT:
			$var = (int)$var;
			return $var > 0 ? $var : max(0, $default_value);
		case TUNSIGNED_DOUBLE:
			$var = (double)$var;
			return $var > 0.0 ? $var : max(0.0, $default_value);
		case TSTRING_HTML:
			return strprotect($var, HTML_NO_PROTECT); //Chaine non protégée pour l'html.
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
 * @desc Protects an input variable. Never trust user input!
 * @param string $var Variable to protect.
 * @param bool $html_protect HTML_PROTECT if you don't accept the HTML code (it will be transformed
 *  by the corresponding HTML entities and won't be considerer by the web browsers). HTML_UNPROTECT if you want to let them.
 * @param int $addslashes If you want to escape the quotes in the string, use ADDSLASHES_FORCE, if you don't want, use the ADDSLASHES_NONE constant.
 * If you want to escape them only if they have not been escaped automatically by the magic quotes option, use the ADDSLASHES_AUTO constant.
 * @return string The protected string.
 */
function strprotect($var, $html_protect = HTML_PROTECT, $addslashes = ADDSLASHES_AUTO)
{
	$var = trim((string)$var);

	//Protection contre les balises html.
	if ($html_protect)
	{
		$var = htmlspecialchars($var);
		//While we aren't in UTF8 encoding, we have to use HTML entities to display some special chars, we accept them.
		$var = preg_replace('`&amp;((?:#[0-9]{2,5})|(?:[a-z0-9]{2,8}));`i', "&$1;", $var);
	}

	switch ($addslashes)
	{
		case ADDSLASHES_FORCE:
			//On force l'échappement de caractères
			$var = addslashes($var);
			break;
		case ADDSLASHES_NONE:
			//On ne touche pas la chaîne
			$var = stripslashes($var);
			break;
			//Mode automatique
		case ADDSLASHES_AUTO:
		default:
			//On échappe les ' si la fonction magic_quotes_gpc() n'est pas activée sur le serveur.
			if (!MAGIC_QUOTES)
			{
				$var = addslashes($var);
			}
	}

	return $var;
}

/**
 * @desc Converts a string to a numeric value.
 * @param string $var The value you want to convert.
 * @param string $type 'int' if you want to convert to an integer value, 'float' if you want a floating value.
 * @return mixed The integer or floating value (according to the type you chose).
 */
function numeric($var, $type = 'int')
{
	if (is_numeric($var)) //Retourne un nombre
	{
		if ($type === 'float')
		{
			return (float)$var; //Nbr virgule flottante.
		}
		else
		{
			return (int)$var; //Nombre entier
		}
	}
	else
	{
		return 0;
	}
}

/**
 * @desc Returns the current user's theme.
 * @return string The theme identifier (name of its folder).
 */
function get_utheme()
{
	$user = AppContext::get_user();
	return $user->get_attribute('user_theme');
}

/**
 * @desc Returns the current user's language.
 * @return string The lang identifier (name of its folder).
 */
function get_ulang()
{
	$user = AppContext::get_user();
	return $user->get_attribute('user_lang');
}

/**
 * @desc Inserts a carriage return every $lenght characters. It's equivalent to wordwrap PHP function but it can deal with the HTML entities.
 * An entity is coded on several characters and the wordwrap function counts several characters for an entity whereas it represents only one character.
 * @param string $str The string to wrap.
 * @param int $lenght The number of characters you want in a line.
 * @param string $cut_char The character to insert every $lenght characters. The default value is '<br />', the HTML carriage return tag.
 * @param bool $cut True if you accept that a word would be broken apart, false if you want to cut only on a blank character.
 * @return string The wrapped HTML string.
 */
function wordwrap_html(&$str, $lenght, $cut_char = '<br />', $cut = true)
{
	$str = wordwrap(html_entity_decode($str), $lenght, $cut_char, $cut);
	return str_replace('&lt;br /&gt;', '<br />', htmlspecialchars($str, ENT_NOQUOTES));
}

/**
 * @desc Cuts a string containing some HTML code which contains some HTML entities. The substr PHP function considers a HTML entity as several characters.
 * This function allows you to consider them as only one character.
 * @param string $str The string you want to cut.
 * @param int $start  If start  is non-negative, the returned string will start at the start 'th position in string , counting from zero. For instance, in the string 'abcdef', the character at position 0 is 'a', the character at position 2 is 'c', and so forth.
 * If start is negative, the returned string will start at the start 'th character from the end of string .
 * If string is less than or equal to start characters long, FALSE will be returned.
 * @param int $end If length is given and is positive, the string returned will contain at most length  characters beginning from start  (depending on the length of string ).
 * @return string The sub string.
 */
function substr_html(&$str, $start, $end = '')
{
	if ($end == '')
	{
		return htmlspecialchars(substr(html_entity_decode($str), $start), ENT_NOQUOTES);
	}
	else
	{
		return htmlspecialchars(substr(html_entity_decode($str), $start, $end), ENT_NOQUOTES);
	}
}

/**
 * @desc Returns the HTML code of the user editor. It uses the ContentFormattingFactory class, it allows you to write less code lines.
 * @param string $field The name of the HTTP parameter which you will retrieve the value entered by the user.
 * @param string[] $forbidden_tags The list of the tags you don't want to appear in the editor.
 * @return The HTML code of the editor that you can directly display in a template.
 */
function display_editor($field = 'contents', $forbidden_tags = array())
{
	$content_editor = new ContentFormattingFactory();
	$editor = $content_editor->get_editor();
	if (!empty($forbidden_tags) && is_array($forbidden_tags))
	{
		$editor->set_forbidden_tags($forbidden_tags);
	}
	$editor->set_identifier($field);

	return $editor->display();
}

/**
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
	import('content/Comments');
	$comments = new Comments($script, $idprov, $vars, $module_folder);

	return $comments->display();
}

/**
 * @desc Loads a module lang file. It will load alone the file corresponding to the user lang, but if it doesn't exist, another lang will be choosen.
 * An error will be displayed on the page and the script execution will be stopped if no lang file is found for this module.
 * @param string $module_name The identifier of the module for which you want to load the lang file.
 * @param string Path of the folder in which is the file. This path mustn't finish by the / character.
 */
function load_module_lang($module_name, $path = PATH_TO_ROOT)
{
	global $LANG;

	$file = $path . '/' . $module_name . '/lang/' . get_ulang() . '/' . $module_name . '_' . get_ulang() . '.php';
	if (!DEBUG) {
		$result = @include_once($file);
	}
	else
	{
		$result = include_once($file);
	}

	if (!$result)
	{
		$lang = find_require_dir(PATH_TO_ROOT . '/' . $module_name . '/lang/', get_ulang(), NO_FATAL_ERROR);
		$file2 = PATH_TO_ROOT . '/' . $module_name . '/lang/' . $lang . '/' . $module_name . '_' . $lang . '.php';

		if (!DEBUG)
		{
			$result2 = @include_once($file2);
		}
		else
		{
			$result2 = include_once($file2);
		}

		if (!$result2)
		{
			global $Errorh;

			//Déclenchement d'une erreur fatale.
			$Errorh->handler(sprintf('Unable to load lang file \'%s\'!', PATH_TO_ROOT . '/' . $module_name . '/lang/' . $lang . '/' . $module_name . '_' . $lang . '.php'), E_USER_ERROR, __LINE__, __FILE__);
			exit;
		}
	}
}

/**
 * @desc Loads a menu lang file. It will load alone the file corresponding to the user lang, but if it doesn't exist, another lang will be choosen.
 * An error will be displayed on the page and the script execution will be stopped if no lang file is found for this menu.
 * @param string $menu_name The identifier of the menu for which you want to load the lang file.
 */
function load_menu_lang($menu_name)
{
	load_module_lang($menu_name, PATH_TO_ROOT . '/menus');
}

/**
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
	if (!DEBUG)
	{
		$result = @parse_ini_file($file);
	}
	elseif(file_exists($file))
	{
		$result = parse_ini_file($file);
	}
	else
	{
		$result = FALSE;
	}
	return $result;
}

/**
 * @desc Parses a table written in a special syntax which is user-friendly and can be inserted in a ini file (PHP serialized arrays cannot be inserted because they contain the " character).
 * The syntax is very easy, it really looks like the PHP array declaration: key => value, key2 => value2
 * You can nest some elements: key => (key1 => value1, key2 => value2), key2 => value2
 * @param string $links_format Serialized array
 * @return string[] The unserialized array.
 */
function parse_ini_array($links_format)
{
	$links_format = preg_replace('` ?=> ?`', '=', $links_format);
	$links_format = preg_replace(' ?, ?', ',', $links_format) . ' ';
	list($key, $value, $open, $cursor, $check_value, $admin_links) = array('', '', '', 0, false, array());
	$string_length = strlen($links_format);
	while ($cursor < $string_length) //Parcours linéaire.
	{
		$char = substr($links_format, $cursor, 1);
		if (!$check_value) //On récupère la clé.
		{
			if ($char != '=')
			{
				$key .= $char;
			}
			else
			{
				$check_value =  true;
			}
		}
		else //On récupère la valeur associé à la clé, une fois celle-ci récupérée.
		{
			if ($char == '(') //On marque l'ouverture de la parenthèse.
			{
				$open = $key;
			}

			if ($char != ',' && $char != '(' && $char != ')' && ($cursor+1) < $string_length) //Si ce n'est pas un caractère délimiteur, on la fin => on concatène.
			{
				$value .= $char;
			}
			else
			{
				if (!empty($open) && !empty($value)) //On insère dans la clé marqué précédemment à l'ouveture de la parenthèse.
				{
					$admin_links[$open][$key] = $value;
				}
				else
				{
					$admin_links[$key] = $value; //Ajout simple.
				}
				list($key, $value, $check_value) = array('', '', false);
			}
			if ($char == ')')
			{
				$open = ''; //On supprime le marqueur.
				$cursor++; //On avance le curseur pour faire sauter la virugle après la parenthèse.
			}
		}
		$cursor++;
	}
	return $admin_links;
}

/**
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
	$dir = find_require_dir($dir_path, $require_dir, false);
	import('io/filesystem/File');

	$module_config_file = new File($dir_path . $dir . '/config.ini', READ);
	$module_config_file->open();
	$module_config_text = $module_config_file->get_contents();

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

//Cherche un dossier s'il n'est pas trouvé, on parcourt le dossier passé en argument à la recherche du premier dossier.
/**
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
	//Si le dossier de langue n'existe pas on prend le suivant exisant.
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
		global $Errorh;

		//Déclenchement d'une erreur fatale.
		$Errorh->handler(sprintf('Unable to load required directory \'%s\'!', $dir_path . $require_dir), E_USER_ERROR, __LINE__, __FILE__);
		exit;
	}
}

/**
 * @desc Retrieves the identifier (name of the folder) of the module which is currently executed.
 * @return string The module identifier.
 */
function get_module_name()
{
	$path = str_replace(DIR, '', SCRIPT);
	$path = trim($path, '/');
	$module_name = explode('/', $path);

	return $module_name[0];
}

/**
 * @desc Returns the full path's file from the root installation.
 * @param string $errfile the filepath
 */
function get_root_path_from_file($errfile)
{
	$local_path = str_replace('/kernel/framework/functions.inc.php', '', str_replace('\\', '/', __FILE__));
	return ltrim(str_replace($local_path, '', str_replace('\\', '/', $errfile)), '/');
}

/**
 * @desc Redirects the user to the URL and stops purely the script execution (database deconnexion...).
 * @param string $url URL at which you want to redirect the user.
 */
function redirect($url)
{
	global $CONFIG;

	if (!empty($CONFIG) && is_array($CONFIG))
	{
		import('util/Url');
		$url = new Url($url);
		$url = $url->absolute();
	}
	header('Location:' . $url);
	exit;
}

/**
 * @desc Displays a confirmation message during a defined delay and then redirects the user.
 * @param string $url_error Url at which you want to redirect him.
 * @param string $l_error The message to show him
 * @param int $delay_redirect Number of seconds after which you want him to be redirected.
 */
function redirect_confirm($url_error, $l_error, $delay_redirect = 3)
{
	global $LANG;

	$template = new Template('framework/confirm.tpl');

	$template->assign_vars(array(
		'URL_ERROR' => !empty($url_error) ? $url_error : get_start_page(),
		'DELAY_REDIRECT' => $delay_redirect,
		'L_ERROR' => $l_error,
		'L_REDIRECT' => $LANG['redirect']
	));

	$template->parse();
}

/**
 * @desc Retrieves the site start page.
 * @return The absolute start page URL.
 */
function get_start_page()
{
	global $CONFIG;

	$start_page = (substr($CONFIG['start_page'], 0, 1) == '/') ? url(HOST . DIR . $CONFIG['start_page']) : $CONFIG['start_page'];
	return $start_page;
}

/**
 * @desc Checks if a string contains less than a defined number of links (used to prevent SPAM).
 * @param string $contents String in which you want to count the number of links
 * @param int $max_nbr Maximum number of links accepted.
 * @return bool true if there are no too much links, false otherwise.
 */
function check_nbr_links($contents, $max_nbr)
{
	if ($max_nbr == -1)
	{
		return true;
	}

	$nbr_link = preg_match_all('`(?:ftp|https?)://`', $contents, $array);
	if ($nbr_link !== false && $nbr_link > $max_nbr)
	{
		return false;
	}

	return true;
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
	import('io/Mail');
	return Mail::check_validity($mail);
}

/**
 * @desc Parses a string with several default parameters. This methods exists to lighten the number of lines written.
 * @param string $content Content to parse
 * @param string[] $forbidden_tags List of the forbidden formatting tags
 * @param bool $addslashes if true, the parsed string will be escaped.
 * @return string The parsed string.
 * @see ContentParser
 */
function strparse(&$content, $forbidden_tags = array(), $addslashes = true)
{
	//On utilise le gestionnaire de contenu
	$content_manager = new ContentFormattingFactory();
	//On lui demande le parser adéquat
	$parser = $content_manager->get_parser();

	//On assigne le contenu à interpréter. Il supprime les antislashes d'échappement seulement si ils ont été ajoutés par magic_quotes
	$parser->set_content($content, MAGIC_QUOTES);

	//Si il y a des balises interdites, on lui signale
	if (!empty($forbidden_tags))
	{
		$parser->set_forbidden_tags($forbidden_tags);
	}
	//Au travail maintenant !
	$parser->parse();

	//Renvoie le résultat. Echappe par défaut les caractères critiques afin d'être envoyé en base de données
	return $parser->get_content($addslashes);
}

/**
 * @desc Unparses a string with several default parameters. This methods exists to lighten the number of lines written.
 * @param string $content Content to unparse
 * @param string[] $forbidden_tags List of the forbidden formatting tags
 * @param bool $addslashes if true, the unparsed string will be escaped.
 * @return string The unparsed string.
 * @see ContentUnparser
 */
function unparse(&$content)
{
	$content_manager = new ContentFormattingFactory();
	$parser = $content_manager->get_unparser();
	$parser->set_content($content, PARSER_DO_NOT_STRIP_SLASHES);
	$parser->parse();

	return $parser->get_content(DO_NOT_ADD_SLASHES);
}

/**
 * @desc Second parses a string with several default parameters. This methods exists to lighten the number of lines written.
 * @param string $content Content to second parse
 * @param string[] $forbidden_tags List of the forbidden formatting tags
 * @param bool $addslashes if true, the second parsed string will be escaped.
 * @return string The second parsed string.
 * @see ContentSecondParser
 */
function second_parse(&$content)
{
	$content_manager = new ContentFormattingFactory();

	$parser = $content_manager->get_second_parser();
	$parser->set_content($content, PARSER_DO_NOT_STRIP_SLASHES);
	$parser->parse();

	return $parser->get_content(DO_NOT_ADD_SLASHES);
}

/**
 * @desc Second parses relative urls to absolute urls.
 * @param string $url Url to second parse
 * @return string The second parsed url.
 * @see Url
 */
function second_parse_url(&$url)
{
	import('util/Url');
	$Url = new Url($url);
	return $Url->absolute();
}

/**
 * @desc Adds the session ID to an URL if the user doesn't accepts cookies.
 * This functions allows you to generate an URL according to the site configuration concerning the URL rewriting.
 * @param string $url URL if the URL rewriting is disabled
 * @param string $mod_rewrite URL if the URL rewriting is enabled
 * @param string $ampersand In a redirection you mustn't put the & HTML entity (&amp;). In this case set that parameter to &.
 * @return string The URL to use.
 */
function url($url, $mod_rewrite = '', $ampersand = '&amp;')
{
	global $CONFIG, $Session;

	if (!is_object($Session))
	{
		$session_mod = 0;
	}
	else
	{
		$session_mod = $Session->supports_cookies();
	}

	if ($session_mod == 0)
	{
		if ($CONFIG['rewrite'] == 1 && !empty($mod_rewrite)) //Activation du mod rewrite => cookies activés.
		{
			return $mod_rewrite;
		}
		else
		{
			return $url;
		}
	}
	elseif ($session_mod == 1)
	{
		return $url . ((strpos($url, '?') === false) ? '?' : $ampersand) . 'sid=' . $Session->data['session_id'] . $ampersand . 'suid=' . $Session->data['user_id'];
	}
}

/**
 * @desc Prepares a string for it to be used in an URL (with only a-z, 0-9 and - characters).
 * @param string $string String to encode.
 * @return string The encoded string.
 */
function url_encode_rewrite($string)
{
	$string = strtolower(html_entity_decode($string));
	$string = strtr($string, ' éèêàâùüûïîôç', '-eeeaauuuiioc');
	$string = preg_replace('`([^a-z0-9]|[\s])`', '-', $string);
	$string = preg_replace('`[-]{2,}`', '-', $string);
	$string = trim($string, ' -');

	return $string;
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
function delete_file($file)
{
	global $LANG;

	if (function_exists('unlink'))
	{
		if (file_exists($file))
		{
			return @unlink($file); //On supprime le fichier.
		}
	}
	else
	{
		return false;
	}
}

/**
 * @desc This function is called by the kernel on each displayed page to count the number of pages seen at each hour.
 * @param bool $no_update True if you just want to read the number of pages viewed, false if you want to increment it.
 * @return int[] Map associating the hour to the number of seen pages. For instance 14 => 56 means that at between 14:00 and 15:00 56 pages were generated.
 */
function pages_displayed($no_update = false)
{
	$data = array();
	if ($file = @fopen(PATH_TO_ROOT . '/cache/pages.txt', 'r+'))
	{
		$hour = gmdate_format('G');
		$data = unserialize(fgets($file, 4096)); //Renvoie la première ligne du fichier (le array précédement crée).
		if (!$no_update)
		{
			if (isset($data[$hour])) //Robo repasse.
			{
				$data[$hour]++; //Nbr de vue.
			}
			else
			{
				$data[$hour] = 1;
			}
		}

		rewind($file);
		fwrite($file, serialize($data)); //On stocke le tableau dans le fichier de données
		fclose($file);
	}
	else if ($file = @fopen(PATH_TO_ROOT . '/cache/pages.txt', 'w+')) //Si le fichier n'existe pas on le crée avec droit d'écriture et lecture.
	{
		$data = array();
		fwrite($file, serialize($data)); //On insère un tableau vide.
		fclose($file);
	}

	return $data;
}

/**
 * @desc Rounds a number
 * @param mixed $number Number to round
 * @param int $dec The number of decimal points
 * @return string The rounded number.
 */
function number_round($number, $dec)
{
	return trim(number_format($number, $dec, '.', ''));
}

/**
 * @desc Emulates the PHP file_get_contents_emulate.
 * @param string $filename File to read.
 * @param $incpath See the PHP documentation
 * @param $resource_context See the PHP documentation
 * @return string The file contents.
 */
function file_get_contents_emulate($filename, $incpath = false, $resource_context = null)
{
	if (false === ($fh = @fopen($filename, 'rb', $incpath)))
	{
		user_error('file_get_contents_emulate() failed to open stream: No such file or directory', E_USER_WARNING);
		return false;
	}

	clearstatcache();
	if ($fsize = @filesize($filename))
	{
		$data = fread($fh, $fsize);
	}
	else
	{
		$data = '';
		while (!feof($fh))
		{
			$data .= fread($fh, 8192);
		}
	}
	fclose($fh);
	return $data;
}

/**
 * @desc Return a SHA256 hash of the $str string [with a salt]
 * @param string $str the string to hash
 * @param mixed $salt If true, add the default salt : md5($str)
 * if a string, use this string as the salt
 * if false, do not use any salt
 * @return string a SHA256 hash of the $str string [with a salt]
 */
function strhash($str, $salt = true)
{

	if ($salt === true)
	{   // Default salt
		$str = md5($str) . $str;
	}
	elseif ($salt !== false)
	{   // Specific salt
		$str = $salt . $str;
	}

	if (phpversion() >= '5.1.2' && @extension_loaded('pecl'))
	{   // PHP5 Primitive
		return hash('sha256', $str);
	}
	else
	{   // With PHP4
		import('lib/SHA256');
		return SHA256::hash($str);
	}
}

/**
 * @desc Returns a unique identifier (useful for example to generate some javascript ids)
 * @return int Id
 */
function get_uid()
{
	static $uid = 1764;
	return $uid++;
}

define('CLASS_IMPORT', '.class.php');
define('INTERFACE_IMPORT', '.int.php');
define('INC_IMPORT', '.inc.php');
define('LIB_IMPORT', '.lib.php');
define('PHP_IMPORT', '.php');
define('PLAIN_IMPORT', '');

/**
 * @desc Imports a class or a lib from the framework or from the root
 * @param string $path Path of the file to load without .class.php or .inc.php extension (for instance util/date)
 * if this path begins with "/", it won't be searched from the kernel framework but from
 * PHPBoost root
 * @param string $import_type the import type. Default is CLASS_IMPORT,
 * but you could also import a library by using LIB_IMPORT (file whose extension is .inc.php)
 * or INC_IMPORT to include a .inc.php file (for example the current file, functions.inc.php).
 */
function import($path, $import_type = CLASS_IMPORT)
{
	if (substr($path, 0, 1) !== 0)
	{
		$path = '/kernel/framework/' . $path;
	}
	require_once PATH_TO_ROOT . $path . $import_type;
}

/**
 * @desc Requires a file
 * @param string $file the file to require with an absolute path from the website root
 * @param bool $once if false use require instead of require_once
 */
function req($file, $once = true)
{
	$file = '/' . ltrim($file, '/');
	if ($once)
	{
		if (!DEBUG)
		{
			@require_once PATH_TO_ROOT . $file ;
		}
		else
		{
			require_once PATH_TO_ROOT . $file ;
		}
	}
	else
	{
		if (!DEBUG)
		{
			@require PATH_TO_ROOT . $file ;
		}
		else
		{
			require PATH_TO_ROOT . $file ;
		}
	}
}


/**
 * @desc Includes a file
 * @param string $file the file to include with an absolute path from the website root
 * @param bool $once if false use include instead of include_once
 * @return bool true if the file have been included with success else, false
 */
function inc($file, $once = true)
{
	$file = '/' . ltrim($file, '/');
	if ($once)
	{
		if (!DEBUG)
		{
			return (@include_once(PATH_TO_ROOT . $file)) !== false;
		}
		else
		{
			return (include_once(PATH_TO_ROOT . $file)) !== false;
		}
	}
	else
	{
		if (!DEBUG)
		{
			return (@include(PATH_TO_ROOT . $file)) !== false;
		}
		else
		{
			return (include(PATH_TO_ROOT . $file)) !== false;
		}
	}
}


/**
 * @desc Tells if an object is an instance of a class
 * @param object $object the object to check its type
 * @param string $classname the classname you want to compare with
 * @return bool true if the $object is an instance of the $classname class
 */
function of_class(&$object, $classname)
{
	if (!is_object($object))
	{
		return false;
	}

	return strtolower(get_class($object)) == strtolower($classname) ||
	is_subclass_of(strtolower(get_class($object)), strtolower($classname));
}

/**
 * @desc Returns true if the object $object implements the interface named $interface_name
 * @param object $object the object
 * @param string $interface_name the interface
 * @return boolean true if the object $object implements the interface named $interface_name
 */
function implements_interface($class, $interface_name)
{
	return in_array($interface_name, class_implements($class));
}

/**
 * @Exports a variable to be used in a javascript script.
 * @param string $string A PHP string to convert to a JS one
 * @return string The js equivalent string
 */
function to_js_string($string)
{
	return '\'' . str_replace(array("\r\n", "\r", "\n"), array('\n', '\n', '\n'),
	addcslashes($string, '\'')) . '\'';
}

/**
 * @desc Returns the sub-regex with its multiplicity option
 * @param string $sub_regex the sub-regex on which add the multiplicity
 * @param int $occurence REGEX_MULTIPLICITY_OPTION
 * @return string the subregex with its multiplicity option
 * @see REGEX_MULTIPLICITY_OPTIONNAL
 * @see REGEX_MULTIPLICITY_NEEDED
 * @see REGEX_MULTIPLICITY_AT_LEAST_ONE
 * @see REGEX_MULTIPLICITY_ALL
 * @see REGEX_MULTIPLICITY_NOT_USED
 */
function set_subregex_multiplicity($sub_regex, $multiplicity_option)
{
	switch ($multiplicity_option)
	{
		case REGEX_MULTIPLICITY_OPTIONNAL:
			// Optionnal
			return '(?:' . $sub_regex . ')?';
		case REGEX_MULTIPLICITY_REQUIRED:
			// Required
			return $sub_regex;
		case REGEX_MULTIPLICITY_AT_LEAST_ONE:
			// Optionnal
			return '(?:' . $sub_regex . ')+';
		case REGEX_MULTIPLICITY_ALL:
			// Optionnal
			return '(?:' . $sub_regex . ')*';
		case  REGEX_MULTIPLICITY_NOT_USED:
		default:
			// Not present
			return '';
	}
}

function check_for_maintain_redirect()
{
	global $CONFIG, $User;

	if ($CONFIG['maintain'] == -1 || $CONFIG['maintain'] > time())
	{
		if (!$User->check_level(ADMIN_LEVEL) && !$User->check_auth($CONFIG['maintain_auth'], AUTH_MAINTAIN)) //Non admin et utilisateurs autorisés.
		{
			if (SCRIPT !== (DIR . '/member/maintain.php')) //Evite de créer une boucle infine.
			{
				redirect('/member/maintain.php');
			}
		}
	}
}

/**
 * @desc returns the file path from the phpboost root directory
 * @param string $path the path to clean. <code>$path</code> must be
 * an absolute file path for the file system
 * @return string the file path from the phpboost root directory
 */
function get_free_phpboost_root_directory_path($path)
{
	return str_replace('\\', '/', substr($path, strlen(PATH_TO_ROOT)));
}

?>
