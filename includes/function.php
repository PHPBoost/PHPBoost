<?php
/*##################################################
 *                                function.php
 *                            -------------------
 *   begin                : June 13, 2005
 *   copyright          : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
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
define('MAGIC_QUOTES_UNACTIV', false);
define('NO_UPDATE_PAGES', true);
define('NO_FATAL_ERROR', false);
define('NO_EDITOR_UNPARSE', false);
define('TIMEZONE_SITE', 1);
define('TIMEZONE_SYSTEM', 2);

//Passe à la moulinette les entrées (chaînes) utilisateurs.
function securit($var, $html_protect = true)
{
	$var = trim($var);
	
	//Protection contre les balises html.
	if( $html_protect ) 
		$var = strip_tags(htmlspecialchars($var));
	
	//On échappe les ' si la fonction magic_quotes_gpc() n'est pas activée sur le serveur.
	if( MAGIC_QUOTES == false )
		$var = addslashes($var);

	return (string) $var;
}

//Vérifie les entrées numeriques.
function numeric($var, $type = 'int')
{
	if( is_numeric($var) ) //Retourne un nombre
	{
		if( $type === 'float')
			return (float)$var; //Nbr virgule flottante.
		else
			return (int)$var; //Nombre entier
	}
	else
		return 0;
}

//Si register_globals activé, suppression des variables globales qui trainent. Fonction emprunté à phpBB3
function securit_register_globals()
{	
	$not_unset = array(
		'GLOBALS'	=> true,
		'_GET'		=> true,
		'_POST'		=> true,
		'_COOKIE'	=> true,
		'_REQUEST'	=> true,
		'_SERVER'	=> true,
		'_SESSION'	=> true,
		'_ENV'		=> true,
		'_FILES'	=> true
	);

	// Merge all into one extremely huge array; unset this later
	$input = array_merge(
		array_keys($_GET),
		array_keys($_POST),
		array_keys($_COOKIE),
		array_keys($_SERVER),
		array_keys($_ENV),
		array_keys($_FILES)
	);

	foreach($input as $varname)
	{
		if( isset($not_unset[$varname]) )
		{
			// Hacking attempt. No point in continuing unless it's a COOKIE
			if( $varname !== 'GLOBALS' || isset($_GET['GLOBALS']) || isset($_POST['GLOBALS']) || isset($_SERVER['GLOBALS']) || isset($_SESSION['GLOBALS']) || isset($_ENV['GLOBALS']) || isset($_FILES['GLOBALS']) )
				exit;
			else
			{
				$cookie = &$_COOKIE;
				while( isset($cookie['GLOBALS']) )
				{
					foreach($cookie['GLOBALS'] as $registered_var => $value)
					{
						if( !isset($not_unset[$registered_var]) )
							unset($GLOBALS[$registered_var]);
					}
					$cookie = &$cookie['GLOBALS'];
				}
			}
		}
		unset($GLOBALS[$varname]);
	}
	unset($input);
}

//Securisation nom d'utilisateur.
function clean_user($var)
{
	$var = substr($var, 0, 25);
	$var = trim(htmlspecialchars($var));
	$var = strip_tags($var);	
	
	//On échappe les '  et " si la fonction magic_quotes_gpc() n'est pas activée sur le serveur.
	if( MAGIC_QUOTES == false )
		$var = addslashes($var);

	return (string) $var;
}

//Découpage avec retour à la ligne, d'une chaîne, en prenant compte les entités html.
function wordwrap_html($str, $lenght, $cut_char = '<br />', $boolean = true)
{
	$str = wordwrap(html_entity_decode($str), $lenght, $cut_char, $boolean);
	return str_replace('&lt;br /&gt;', '<br />', strip_tags(htmlentities($str)));	
}

//Découpe d'une chaîne, en prenant compte les entités html.
function substr_html($str, $start, $end = '')
{
	if( $end == '' )
		return htmlentities(substr(html_entity_decode($str), $start));
	else
		return htmlentities(substr(html_entity_decode($str), $start, $end));
}

//Chercher le dossier langue d'un module, s'il n'est pas trouvé on retourne la première langue.
function load_module_lang($module_name, $lang)
{
	global $LANG;
	if( !@include_once('../' . $module_name . '/lang/' . $lang . '/' . $module_name . '_' . $lang . '.php') )
	{	
		$lang = find_require_dir('../' . $module_name . '/lang/', $lang, NO_FATAL_ERROR);
		if( !@include_once('../' . $module_name . '/lang/' . $lang . '/' . $module_name . '_' . $lang . '.php') )
		{
			global $Errorh;
			
			//Déclenchement d'une erreur fatale.
			$Errorh->Error_handler(sprintf('Unable to load lang file \'%s\'!', '../' . $module_name . '/lang/' . $lang . '/' . $module_name . '_' . $lang . '.php'), E_USER_ERROR, __LINE__, __FILE__); 
			exit;
		}
	}
}

//Cherche un dossier s'il n'est pas trouvé, on parcourt le dossier passé en argument à la recherche du premier dossier.
function find_require_dir($dir_path, $require_dir, $fatal_error = true)
{
	//Si le dossier de langue n'existe pas on prend le suivant exisant.
	if( !file_exists($dir_path . $require_dir) )
	{
		if( is_dir($dir_path) ) //Si le dossier existe
		{		
			$dh = @opendir($dir_path);
			while( !is_bool($dir = @readdir($dh)) )
			{	
				if( !preg_match('`\.`', $dir) )
					return $dir;
			}
			@closedir($dh);
		}	
	}
	else
		return $require_dir;
		
	if( $fatal_error )
	{
		global $Errorh;
	
		//Déclenchement d'une erreur fatale.
		$Errorh->Error_handler(sprintf('Unable to load required directory \'%s\'!', $dir_path . $require_dir), E_USER_ERROR, __LINE__, __FILE__); 
		exit;
	}
}

//Transforme des données de la base de données en données utilisable pour la génération du cache.
function sql_to_cache($str)
{
	return str_replace('\'', '\\\'', str_replace('\\', '\\\\', $str));
}

//Redirection.
function redirect($url)
{
	global $Sql;
	
	if( !empty($Sql) && is_object($Sql) ) //Coupure de la connexion mysql.
		$Sql->Sql_close();
		
	header('Location:' . $url);
	exit;
}

//Récupération de la page de démarrage du site.
function get_start_page()
{
	global $CONFIG;
	
	$start_page = (substr($CONFIG['start_page'], 0, 1) == '/') ? transid(HOST . DIR . $CONFIG['start_page']) : $CONFIG['start_page'];
	return $start_page;
}

//Récupération de la page d'installation.
function get_install_page()
{
	$server_name = !empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST');
	$server_path = !empty($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : getenv('PHP_SELF');
	if( !$server_path )
		$server_path = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : getenv('REQUEST_URI');
	$install_path = trim(dirname($server_path)) . '/install/install.php';
	
	//Suppression du dossier courant, et trim du chemin de l'installateur.
	return 'http://' . $server_name . preg_replace('`(.*)/[a-z]+/(install/install\.php)(.*)`i', '$1/$2', $install_path);
}

//Charge le parseur.
function parse($contents, $forbidden_tags = array(), $html_protect = true, $magic_quotes_activ = true)
{
	global $LANG, $Member;
	
	include_once('../includes/parse.class.php');
	$Parse = new Parse($Member->Get_attribute('user_editor'));

	return $Parse->parse_content($contents, $forbidden_tags, $html_protect, $magic_quotes_activ);
}

//Charge l'unparseur.
function unparse($contents, $editor_unparse = true)
{
	global $LANG, $Member;
	
	include_once('../includes/parse.class.php');
	$parse = new Parse($Member->Get_attribute('user_editor'));	
	
	return $parse->unparse_content($contents, $editor_unparse);
}

//Vérifie que le message ne contient pas plus de x liens
function check_nbr_links($contents, $max_nbr)
{
	if( $max_nbr == -1 )
		return true;
		
	$nbr_link = preg_match_all('`(?:ftp|https?)://`', $contents, $array);
	if( $nbr_link !== false && $nbr_link > $max_nbr )
		return false;
	
	return true;
}
	
//Coloration syntaxique suivant le langage, tracé des lignes si demandé.
function highlight_code($contents, $language, $line_number) 
{
	if( $language != '' )
	{
		include_once('../includes/geshi/geshi.php');
		$Geshi =& new GeSHi($contents, $language);
		
		if( $line_number ) //Affichage des numéros de lignes.
			$Geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);

		$contents = $Geshi->parse_code();
	}
	else
	{
		$highlight = highlight_string($contents, true);
		$font_replace = str_replace(array('<font ', '</font>'), array('<span ', '</span>'), $highlight);
		$contents = preg_replace('`color="(.*?)"`', 'style="color: \\1"', $font_replace);
	}
	
	return $contents ;
} 

//Fonction appliquée aux balises [code] temps réel.
function callback_highlight_code($matches)
{
	global $LANG;

	$line_number = !empty($matches[2]) ? true : false;
	$display_info_code = !empty($matches[3]) ? false : true;

	$contents = str_replace('<br />', '', $matches[4]);
	$contents = unparse($contents, false);
	$contents = html_entity_decode($contents);
	$contents = highlight_code($contents, $matches[1], $line_number);
	if( $display_info_code )
		$contents = '<span class="text_code">' . $LANG['code'] . (!empty($matches[1]) ? ' ' . strtoupper($matches[1]) : '') . ' :</span><div class="code">'. $contents .'</div>';
	else
		$contents = '<div class="code" style="margin-top:3px;">'. $contents .'</div>';
		
	return $contents;
}

//Fonction appliquée aux balises [math] temps réel, formules matématiques.
function math_code($matches)
{
	$matches[1] = str_replace('<br />', '', $matches[1]);
	$matches = mathfilter(html_entity_decode($matches[1]), 12);

	return $matches;
}

//Parse temps réel => détection des balisses [code]  et remplacement, coloration si contient du code php.
function second_parse($contents)
{
	global $LANG;
	
	//Balise code.
	if( strpos($contents, '[code') !== false )
		$contents = preg_replace_callback('`\[code(?:=([a-z0-9-]+))?(?:,(0|1)(,0)?)?\](.+)\[/code\]`isU', 'callback_highlight_code', $contents);
	
	//Balise latex.
	if( strpos($contents, '[math]') !== false )
		$contents = preg_replace_callback('`\[math\](.+)\[/math\]`isU', 'math_code', $contents);

	return $contents;
}
	
//Transmet le session_id et le user_id à traver l'url pour les connexions sans cookies. Permet le support de l'url rewritting!
function transid($url, $mod_rewrite = '', $esperluette = '&amp;')
{
	global $CONFIG;
	global $Session;
	
	if( $Session->session_mod == 0 )
	{	
		if( $CONFIG['rewrite'] == 1 && !empty($mod_rewrite) ) //Activation du mod rewrite => cookies activés.
			return $mod_rewrite;	
		else
			return $url;
	}	
	elseif( $Session->session_mod == 1 )
		return $url . ((strpos($url, '?') === false) ? '?' : $esperluette) . 'sid=' . $Session->session['session_id'] . $esperluette . 'suid=' . $Session->session['user_id'];	
}

//Nettoie l'url de tous les caractères spéciaux, accents, etc....
function url_encode_rewrite($string)
{
	$string = strtolower(html_entity_decode($string));

	$chars_special = array(' ', 'é', 'è', 'ê', 'à', 'â', 'ù', 'ü', 'û', 'ï', 'î', 'ô', 'ç');
	$chars_replace = array('-', 'e', 'e', 'e', 'a', 'a', 'u', 'u', 'u', 'i', 'i', 'o', 'c');
	$string = str_replace($chars_special, $chars_replace, $string);

	$string = preg_replace('`([^a-z0-9]|[\s])`', '-', $string);
	$string = preg_replace('`[-]{2,}`', '-', $string);
	$string = trim($string, ' -');
	
	return $string;
}

//Formate la date au format GMT, suivant la configuration du fuseau horaire du serveur.
function gmdate_format($format, $timestamp = false, $timezone_system = 0)
{
	global $Member, $CONFIG, $LANG;
	
	if( strpos($format, 'date_format') !== false ) //Inutile de tout tester si ce n'est pas un formatage prédéfini.
	{
		switch($format)
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
	
	if( $timestamp === false )
		$timestamp = time();
	
	$serveur_hour = number_round(date('Z')/3600, 0); //Décallage du serveur par rapport au méridien de greenwitch.
	if( $timezone_system == 1 ) //Timestamp du site, non dépendant de l'utilisateur.
		$timezone = $CONFIG['timezone'] - $serveur_hour;
	elseif( $timezone_system == 2 ) //Timestamp du serveur, non dépendant de l'utilisateur et du fuseau par défaut du site.
		$timezone = 0;
	else //Timestamp utilisateur dépendant de la localisation de l'utilisateur par rapport à serveur.
		$timezone = $Member->Get_attribute('user_timezone') - $serveur_hour;

	if( $timezone != 0 )
		$timestamp += $timezone * 3600;
	
	return date($format, $timestamp);
}
		
//Converti une chaîne au format $LANG['date_format'] (ex:d/m/y) en timestamp, si la date saisie est valide sinon retourne 0.
function strtotimestamp($str, $date_format)
{
	global $CONFIG, $Member;
	
	list($month, $day, $year) = array(0, 0, 0);
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
	if( checkdate($month, $day, $year) )
		$timestamp = @mktime(0, 0, 1, $month, $day, $year);
	else
		$timestamp = time();
		
	$serveur_hour = number_round(date('Z')/3600, 0); //Décallage du serveur par rapport au méridien de greenwitch.
	$timezone = $Member->Get_attribute('user_timezone') - $serveur_hour;
	if( $timezone != 0 )
		$timestamp -= $timezone * 3600;	
		
	return ($timestamp > 0) ? $timestamp : time();
}

//Converti une chaîne au format $LANG['date_format'] (ex:DD/MM/YYYY) en type DATE, si la date saisie est valide sinon retourne 0000-00-00.
function strtodate($str, $date_format)
{
	list($month, $day, $year) = array(0, 0, 0);
	$array_date = explode('/', $str);
	$array_format_date = explode('/', $date_format);
	for($i = 0; $i < 3; $i++)
	{
		switch($array_format_date[$i])
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
	if( checkdate($month, $day, $year) )
		$date = $year . '-' . $month . '-' . $day;
	else
		$date = '0000-00-00';
		
	return $date;
}

//Suppression d'un fichier avec gestion des erreurs.
function delete_file($file)
{
	global $LANG;
	
	if( function_exists('unlink') )
		if( file_exists($file) )
			return @unlink($file); //On supprime le fichier.
	else
		return false;
}

//Fonction récursive de suppression de dossier.
function delete_directory($dir_path, $path)
{
	$dir = dir($path);
	if( !is_object($dir) ) 
		return false;
		
	while( $file = $dir->read() ) 
	{
		if( $file != '.' && $file != '..' )
		{			
			$path_file = $path . '/' . $file;
			if( is_file($path_file) )
				if( !@unlink($path_file) )
					return false;
			elseif( is_dir($path_file) )
			{	
				delete_directory($dir_path, $path_file);
				if( !@rmdir($path_file) )
					return false;
			}
		}
	}
	
	//Fermeture du dossier et suppression de celui-ci.
	if( !$file )
	{	
		$dir->close();
		if( @rmdir($dir_path) )
			return true;
	}	
	return false;
}

//Compte le nombre de page vues.
function pages_displayed($no_update = false)
{
	$file = @fopen('../cache/pages.txt', 'r+');
	if( $file )
	{
		$hour = gmdate_format('G');
		$data = unserialize(@fgets($file, 4096)); //Renvoi la première ligne du fichier (le array précédement crée).
		if( !$no_update )
		{
			if( isset($data[$hour]) ) //Robo repasse.
				$data[$hour]++; //Nbr de vue.
			else
				$data[$hour] = 1;
		}
		
		@rewind($file);
		@fwrite($file, serialize($data)); //On stock le tableau dans le fichier de données
		@fclose($file);
	}
	else
	{
		$file = @fopen('../cache/pages.txt', 'w+'); //Si le fichier n'existe pas on le crée avec droit d'écriture et lecture.
		$data = array();
		@fwrite($file, serialize($data)); //On insère un tableau vide.
		@fclose($file);
	}
	
	return $data;
}

//Arrondi nbr au nbr de décimal voulu
function number_round($number, $dec)
{
	return trim(number_format($number, $dec, '.', ''));
}
function arrondi($number, $dec) //Alias.
{
	return trim(number_format($number, $dec, '.', ''));
}

//Remplacement de la fonction file_get_contents.
function file_get_contents_emulate($filename, $incpath = false, $resource_context = null)
{
	if( false === $fh = fopen($filename, 'rb', $incpath) ) 
	{
		user_error('file_get_contents_emulate() failed to open stream: No such file or directory', E_USER_WARNING);
		return false;
	}

	clearstatcache();
	if( $fsize = @filesize($filename) ) 
		$data = fread($fh, $fsize);
	else 
	{
		$data = '';
		while( !feof($fh) )
			$data .= fread($fh, 8192);
	}
	fclose($fh);
	return $data;
}

//Emulation de la fonction PHP5 html_entity_decode().		
if( !function_exists('html_entity_decode') ) 
{
	function html_entity_decode($string, $quote_style = ENT_COMPAT, $charset = null)
	{
		if( !is_int($quote_style) ) 
		{
			user_error('html_entity_decode() expects parameter 2 to be long, ' .
			gettype($quote_style) . ' given', E_USER_WARNING);
			return;
		}

		$trans_tbl = array_flip(get_html_translation_table(HTML_ENTITIES));

		// Add single quote to translation table;
		$trans_tbl['&#039;'] = '\'';

		// Not translating double quotes
		if( $quote_style & ENT_NOQUOTES ) 
			unset($trans_tbl['&quot;']); // Remove double quote from translation table
		return strtr($string, $trans_tbl);
	}
}

//Emulation de la fonction PHP5 htmlspecialchars_decode().	
if( !function_exists('htmlspecialchars_decode') ) 
{
    function htmlspecialchars_decode($string, $quote_style = null)
    {
        // Sanity check
        if( !is_scalar($string) ) 
		{
            user_error('htmlspecialchars_decode() expects parameter 1 to be string, ' . gettype($string) . ' given', E_USER_WARNING);
            return;
        }

        if( !is_int($quote_style) && $quote_style !== null ) 
		{
            user_error('htmlspecialchars_decode() expects parameter 2 to be integer, ' . gettype($quote_style) . ' given', E_USER_WARNING);
            return;
        }

        // Init
        $from = array('&amp;', '&lt;', '&gt;');
        $to = array('&', '<', '>');
        
        // The function does not behave as documented
        // This matches the actual behaviour of the function
        if( $quote_style & ENT_COMPAT || $quote_style & ENT_QUOTES ) 
		{
            $from[] = '&quot;';
            $to[] = '"';
            
            $from[] = '&#039;';
            $to[] = "'";
        }

        return str_replace($from, $to, $string);
    }
}

//Emulation de la fonction PHP5 array_combine
if( !function_exists('array_combine') ) 
{
    function array_combine($keys, $values)
    {
        if( !is_array($keys) ) 
		{
            user_error('array_combine() expects parameter 1 to be array, ' .
                gettype($keys) . ' given', E_USER_WARNING);
            return;
        }

        if( !is_array($values) ) 
		{
            user_error('array_combine() expects parameter 2 to be array, ' .
                gettype($values) . ' given', E_USER_WARNING);
            return;
        }

        $key_count = count($keys);
        $value_count = count($values);
        if( $key_count !== $value_count ) {
            user_error('array_combine() Both parameters should have equal number of elements', E_USER_WARNING);
            return false;
        }

        if( $key_count === 0 || $value_count === 0 ) 
		{
            user_error('array_combine() Both parameters should have number of elements at least 0', E_USER_WARNING);
            return false;
        }

        $keys = array_values($keys);
        $values  = array_values($values);

        $combined = array();
        for ($i = 0; $i < $key_count; $i++) 
		{
            $combined[$keys[$i]] = $values[$i];
        }

        return $combined;
    }
}

?>
