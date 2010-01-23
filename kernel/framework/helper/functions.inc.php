<?php
/*##################################################
 *                             functions.inc.php
 *                            -------------------
 *   begin                : June 13, 2005
 *   copyright            : (C) 2005 Régis Viarre, Loïc Rouchon
 *   email                : crowkait@phpboost.com, loic.rouchon@phpboost.com
 *
 *   Function 2.0.0
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
 * @desc Returns the HTML code of the user editor. It uses the ContentFormattingFactory class, it allows you to write less code lines.
 * @param string $field The name of the HTTP parameter which you will retrieve the value entered by the user.
 * @param string[] $forbidden_tags The list of the tags you don't want to appear in the editor.
 * @return The HTML code of the editor that you can directly display in a template.
 */
function display_editor($field = 'contents', $forbidden_tags = array())
{
	$editor = ContentFormattingMetaFactory::get_default_editor();
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
	if (!Debug::is_debug_mode_enabled())
	{
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

		if (!Debug::is_debug_mode_enabled())
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
	if (!Debug::is_debug_mode_enabled())
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
		global $Errorh;

		//Déclenchement d'une erreur fatale.
		$Errorh->handler(sprintf('Unable to load required directory \'%s\'!', $dir_path . $require_dir), E_USER_ERROR, __LINE__, __FILE__);
		exit;
	}
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
		if (!($url instanceof Url))
		{
			$url = new Url($url);
		}
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
		'URL_ERROR' => !empty($url_error) ? $url_error : get_home_page(),
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
function get_home_page()
{
	global $CONFIG;

	$start_page = (substr($CONFIG['start_page'], 0, 1) == '/') ? url(HOST . DIR . $CONFIG['start_page']) : $CONFIG['start_page'];
	return $start_page;
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
		user_error('file_get_contents_emulate(\'' . $filename . '\')' .
			'failed to open stream: No such file or directory', E_USER_WARNING);
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

	if (function_exists('hash'))
	{   // PHP5 Primitive
		return hash('sha256', $str);
	}
	else
	{   // With PHP4
		echo 'PHP4';

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
	if (substr($path, 0, 1) !== '/')
	{
		$path = '/kernel/framework/' . $path;
	}
	if (!@include_once(PATH_TO_ROOT . $path . $import_type))
	{
		Debug::fatal(new Exception('Can\'t load file ' . PATH_TO_ROOT . $path . $import_type));
	}
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
		if (!Debug::is_debug_mode_enabled())
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
		if (!Debug::is_debug_mode_enabled())
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
		if (!Debug::is_debug_mode_enabled())
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
		if (!Debug::is_debug_mode_enabled())
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
function of_class($object, $classname)
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

?>