<?php
/*##################################################
 *                             cache.class.php
 *                            -------------------
 *   begin                : August 28, 2006
 *   copyright          : (C) 2006 Benoît Sautel / Régis Viarre
 *   email                : ben.popeye@phpboost / crowkait@phpboost.com
 *
 *   
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 * 
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

define('RELOAD_CACHE', true);

//Fonction d'importation/exportation de base de donnée.
class Cache
{
    ## Public Methods ##
	//On vérifie que le répertoire cache existe et est inscriptible
    function Cache()
    {
        if( !is_dir('../cache') || !is_writable('../cache') )
        {
            global $Errorh;
		
			//Enregistrement dans le log d'erreur.
			$Errorh->Error_handler('Cache -> Le fichier cache doit être inscriptible, donc en CHMOD 777', E_USER_ERROR, __LINE__, __FILE__);
        }
    }
        
    //Fonction de chargement d'un fichier cache
    function Load_file($file, $reload_cache = false)
    {
		global $Errorh, $Sql;
		
		//On charge le fichier
		$include = !$reload_cache ? !include_once('../cache/' . $file . '.php') : !include('../cache/' . $file . '.php');
		if( $include )
		{
			if( in_array($file, $this->files) )
			{
				//Régénération du fichier
				$this->generate_file($file);
				//On inclue une nouvelle fois				
				if( !include('../cache/' . $file . '.php') )
				{
					//Enregistrement dans le log d'erreur.
					$Errorh->Error_handler('Cache -> Impossible de lire le fichier cache, ni de le régénérer!', E_USER_ERROR, __LINE__, __FILE__);
				}
			}
			else
			{
				//Régénération du fichier du module.
				$this->generate_module_file($file);
				//On inclue une nouvelle fois
				if( !include('../cache/' . $file . '.php') )
				{
					//Enregistrement dans le log d'erreur.
					$Errorh->Error_handler('Cache -> Impossible de lire le fichier cache, ni de le régénérer!', E_USER_ERROR, __LINE__, __FILE__);
				}
			}
		}
    }
    
    //Génération de tous les fichiers
    function Generate_all_files()
    {
        foreach( $this->files as $cache_file )
            $this->generate_file($cache_file);
		
		//Génération de tout les fichiers de cache des modules.
		$this->generate_all_module_files();
    }
	
    //Fonction d'enregistrement du fichier
    function Generate_file($file)
    {
		global $Errorh;
		
		$content = $this->{'generate_file_' . $file}();
		$file_path = '../cache/' . $file . '.php';
		@delete_file($file_path); //Supprime le fichier
		if( $handle = @fopen($file_path, 'wb') ) //On crée le fichier avec droit d'écriture et lecture.
		{
			@flock($handle, LOCK_EX);
			@fwrite($handle, "<?php\n" . $content . "\n?>");
			@flock($handle, LOCK_UN);
			@fclose;
			
			@chmod($file_path, 0666);
		}
		//Il est l'heure de vérifier si la génération a fonctionnée.
		if( !file_exists($file_path) && filesize($file_path) == '0' )
			$Errorh->Error_handler('Cache -> La génération du fichier de cache ' . $file . ' a échoué!', E_USER_ERROR, __LINE__, __FILE__);
    }
    
	//Fonction d'enregistrement du fichier d'un module.
    function Generate_module_file($file)
    {
		global $CONFIG, $Errorh;
		
		$root = '../';
		$dir = $file;
		//On vérifie que le fichier de configuration est présent.
		if( file_exists($root . $dir . '/lang/' . $CONFIG['lang'] . '/config.ini') && file_exists($root . $dir . '/' . $dir . '_cache.php') )
		{
			$config = @parse_ini_file($root . $dir . '/lang/' . $CONFIG['lang'] . '/config.ini');
			//On récupère l'information sur le cache, si le cache est activé, on va chercher les fonctions de régénération de cache.
			if( !empty($config['cache']) && $config['cache'] )
			{
				include_once($root . $dir . '/' . $dir . '_cache.php');
				$content = call_user_func('generate_module_file_' . $dir);
				$file_path = '../cache/' . $file . '.php';
				@delete_file($file_path); //Supprime le fichier
				if( $handle = @fopen($file_path, 'wb') ) //On crée le fichier avec droit d'écriture et lecture.
				{
					@flock($handle, LOCK_EX);
					@fwrite($handle, "<?php\n" . $content . "\n?>");
					@flock($handle, LOCK_UN);
					@fclose;
					
					@chmod($file_path, 0666);
				}
		
				//Il est l'heure de vérifier si la génération a fonctionnée.
				if( !file_exists($file_path) && filesize($file_path) == '0' )
					$Errorh->Error_handler('Cache -> La génération du fichier de cache ' . $file . ' a échoué!', E_USER_ERROR, __LINE__, __FILE__);
			}
		}	
		else
		{
			//Enregistrement dans le log d'erreur.
			$Errorh->Error_handler('Cache -> Impossible de lire le fichier cache, ni de le régénérer!', E_USER_ERROR, __LINE__, __FILE__);
		}
    }
	
	//Génération du fichier htaccess
	function Generate_htaccess()
	{
		global $CONFIG, $Sql;
		
		if( $CONFIG['rewrite'] )
		{
			$htaccess_rules = 'Options +FollowSymlinks' . "\n" . 'RewriteEngine on' . "\n";
			$result = $Sql->Query_while("SELECT name
			FROM ".PREFIX."modules
			WHERE activ = 1", __LINE__, __FILE__);
			while( $row = $Sql->Sql_fetch_assoc($result) )
			{
				//Récupération des infos de config.
				$get_info_modules = @parse_ini_file('../' . $row['name'] . '/lang/' . $CONFIG['lang'] . '/config.ini');
				if( !empty($get_info_modules['url_rewrite']) )
					$htaccess_rules .= str_replace('\n', "\n", str_replace('DIR', DIR, $get_info_modules['url_rewrite'])) . "\n\n";
			}
			$htaccess_rules .= 
			'# Core #' . 
			"\n" . 'RewriteRule ^(.*)member/member-([0-9]+)-?([0-9]*)\.php$ ' . DIR . '/member/member.php?id=$2&p=$3 [L,QSA]' . 
			"\n" . 'RewriteRule ^(.*)member/pm-?([0-9]+)-?([0-9]{0,})-?([0-9]{0,})-?([0-9]{0,})-?([a-z_]{0,})\.php$ ' . DIR . '/member/pm.php?pm=$2&id=$3&p=$4&quote=$5 [L,QSA]';	
			
			//Page d'erreur.
			$htaccess_rules .= "\n\n" . '# Error page #' . "\n" . 'ErrorDocument 404 ' . get_start_page();						

			//Protection de la bande passante, interdiction d'accès aux fichiers du répertoire upload depuis un autre serveur.
			global $CONFIG_FILES;
			$this->load_file('files');
			if( $CONFIG_FILES['bandwidth_protect'] )
			{
				$htaccess_rules .= "\n\n# Bandwith protection #\nRewriteCond %{HTTP_REFERER} !^$\nRewriteCond %{HTTP_REFERER} !^" . HOST . "\nReWriteRule .*upload/.*$ - [F]";
			}	

			//Ecriture du fichier .htaccess
			$file_path = '../.htaccess';
			@delete_file($file_path); //Supprime le fichier.
			$handle = @fopen($file_path, 'w+'); //On crée le fichier avec droit d'écriture et lecture.
			@fwrite($handle, $htaccess_rules);
			@fclose($handle);
		}
		else
		{
			$htaccess_rules = 'ErrorDocument 404 ' . get_start_page();

			//Ecriture du fichier .htaccess
			$file_path = '../.htaccess';
			@delete_file($file_path); //Supprime le fichier.
			$handle = @fopen($file_path, 'w+'); //On crée le fichier avec droit d'écriture et lecture.
			@fwrite($handle, $htaccess_rules);
			@fclose($handle);
		}
	}
	
	
	## Private Methods ##
	//Parcours les dossiers, à la recherche de fichiers de configuration en vue de regénérer le cache des modules.
	function generate_all_module_files()
	{
		global $CONFIG, $Sql;
		
		$result = $Sql->Query_while("SELECT name 
		FROM ".PREFIX."modules
		WHERE activ = 1", __LINE__, __FILE__);
		while( $row = $Sql->Sql_fetch_assoc($result) )
		{
			$config = @parse_ini_file('../' . $row['name'] . '/lang/' . $CONFIG['lang'] . '/config.ini');
			//On récupère l'information sur le cache, si le cache est activé, on va chercher les fonctions de régénération de cache.
			if( !empty($config['cache']) && $config['cache'] )
			{
				//génération du cache.
				@include_once('../' . $row['name'] . '/' . $row['name'] . '_cache.php');
				$this->Generate_module_file($row['name']);
			}
		}
		$Sql->Close($result);
	}
	
    ########## Fonctions de génération des fichiers un à un ##########
	//Gestions des modules installalés, configuration des autorisations.
	function generate_file_modules()
	{
		global $Sql;
		
		$code = 'global $SECURE_MODULE;' . "\r\n";
		$result = $Sql->Query_while("SELECT name, auth
		FROM ".PREFIX."modules
		WHERE activ = 1
		ORDER BY name", __LINE__, __FILE__);
		while( $row = $Sql->Sql_fetch_assoc($result) )
			$code .= '$SECURE_MODULE[\'' . $row['name'] . '\'] = ' . var_export(unserialize($row['auth']), true) . ';' . "\r\n";
		$Sql->Close($result);

		return $code;
	}
	
	//Placements et autorisations des modules minis.
	function generate_file_modules_mini()
	{
		global $Sql;
		
		$modules_mini = array();
		$result = $Sql->Query_while("SELECT name, code, contents, location, secure, added, use_tpl
		FROM ".PREFIX."modules_mini 
		WHERE activ = 1
		ORDER BY location, class", __LINE__, __FILE__);
		while( $row = $Sql->Sql_fetch_assoc($result) )
		{
			$modules_mini[$row['location']][] = array('name' => $row['name'], 'code' => $row['code'], 'contents' => $row['contents'], 'secure' => $row['secure'], 'added' => $row['added'], 'use_tpl' => $row['use_tpl']);
		}
		$Sql->Close($result);

		$code = '';
		$array_seek = array('subheader', 'left', 'right', 'topcentral', 'bottomcentral');
		foreach($array_seek as $location)
		{
			$code .= 'if( isset($MODULES_MINI[\'' . $location . '\']) && $MODULES_MINI[\'' . $location . '\'] ){' . "\n";
			foreach($modules_mini[$location] as $location_key => $info)
			{
				if( $info['added'] == '0' )
					$code .= 'if( $Member->Get_attribute(\'level\') >= ' . $info['secure'] . ' ){' . $info['code'] . '}' . "\n";
				else
				{
					if( $info['use_tpl'] == '0' )
						$code .= 'echo ' . var_export($info['contents'], true) . ';' . "\n";
					else
					{
						switch($location)
						{
							case 'left':
							case 'right':
							$code .= 'if( $Member->Get_attribute(\'level\') >= ' . $info['secure'] . ' ){' . 
							"\$Template->Set_filenames(array('modules_mini' => '../templates/' . \$CONFIG['theme'] . '/modules_mini.tpl'));\$Template->Assign_vars(array('MODULE_MINI_NAME' => " . var_export($info['name'], true) . ", 'MODULE_MINI_CONTENTS' => " . var_export($info['contents'], true) . "));\$Template->Pparse('modules_mini');" . '}' . "\n";
							break;
							case 'subheader':
							case 'topcentral':
							case 'bottomcentral':
							$code .= 'if( $Member->Get_attribute(\'level\') >= ' . $info['secure'] . ' ){' . 
							"\$Template->Set_filenames(array('modules_mini_horizontal' => '../templates/' . \$CONFIG['theme'] . '/modules_mini_horizontal.tpl'));\$Template->Assign_vars(array('MODULE_MINI_NAME' => " . var_export($info['name'], true) . ", 'MODULE_MINI_CONTENTS' => " . var_export($info['contents'], true) . "));\$Template->Pparse('modules_mini_horizontal');" . '}' . "\n";
							break;					
						}						
					}
				}
			}
			$code .= '}' . "\n";
		}
		
		return $code;
	}
	
	//Configuration du site
	function generate_file_config()
	{
		global $Sql;
		
		$config = 'global $CONFIG;' . "\n";
	
		//Récupération du tableau linéarisé dans la bdd.
		$CONFIG = unserialize((string)$Sql->Query("SELECT value FROM ".PREFIX."configs WHERE name = 'config'", __LINE__, __FILE__));
		foreach($CONFIG as $key => $value)
			$config .= '$CONFIG[\'' . $key . '\'] = ' . var_export($value, true) . ";\n";

		return $config;
	}
	
	//Day
	function generate_file_day()
	{
		return 'global $_record_day;' . "\n" . '$_record_day = ' . gmdate_format('j', time(), TIMEZONE_SITE) . ';';
	}
	
	//Groupes
	function generate_file_groups()
	{
		global $Sql;
		
		$code = 'global $_array_groups_auth;' . "\n" . '$_array_groups_auth = array(' . "\n";
		$result = $Sql->Query_while("SELECT id, name, img, auth
		FROM ".PREFIX."group	
		ORDER BY id", __LINE__, __FILE__);
		while( $row = $Sql->Sql_fetch_assoc($result) )
		{
			$code .= $row['id'] . ' => array(\'name\' => ' . var_export($row['name'], true) . ', \'img\' => ' . var_export($row['img'], true) . ', \'auth\' => ' . var_export(unserialize($row['auth']), true) . '),' . "\n";
		}			
		$Sql->Close($result);
		$code .= ');';
		
		return $code;
	}
	
	//Debug.
	function generate_file_debug()
	{
		global $Sql;
		
		//Récupération du tableau linéarisé dans la bdd.
		$CONFIG = unserialize((string)$Sql->Query("SELECT value FROM ".PREFIX."configs WHERE name = 'config'", __LINE__, __FILE__));
		
		//Url rewriting.
		if( function_exists('apache_get_modules') )
		{	
			$get_rewrite = apache_get_modules();
			$check_rewrite = (!empty($get_rewrite[5])) ? '1' : '0';
		}
		else
			$check_rewrite = '?';
		
		//Variables serveur.
		$server_path = !empty($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : getenv('PHP_SELF');
		if( !$server_path )
			$server_path = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : getenv('REQUEST_URI');
		$server_path = trim(str_replace('/admin', '', dirname($server_path)));
		$server_name = 'http://' . (!empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST'));

		//Informations pour le débuggage.
		$array_debug[] = 'PHPBoost output debug file';
		$array_debug[] = '-------------HOST-------------';
		$array_debug[] = 'PHP [' . phpversion() . ']';
		$array_debug[] = 'GD [' . (@extension_loaded('gd') ? 1 : 0) . ']';
		$array_debug[] = 'Mod Rewrite [' . $check_rewrite . ']';
		$array_debug[] = 'Register globals [' . ((@ini_get('register_globals') == '1' || strtolower(@ini_get('register_globals')) == 'on') ? 1 : 0) . ']';
		$array_debug[] = 'Server name [' . $server_name . ']';
		$array_debug[] = 'Server path [' . $server_path . ']';
		$array_debug[] = '-------------CONFIG-------------';
		$array_debug[] = 'Version [' . $CONFIG['version'] . ']';
		$array_debug[] = 'Server name [' . $CONFIG['server_name'] . ']';
		$array_debug[] = 'Server path [' . $CONFIG['server_path'] . ']';
		$array_debug[] = 'Theme [' . $CONFIG['theme'] . ']';
		$array_debug[] = 'Lang [' . $CONFIG['lang'] . ']';
		$array_debug[] = 'Editor [' . $CONFIG['editor'] . ']';
		$array_debug[] = 'Start page [' . $CONFIG['start_page'] . ']';
		$array_debug[] = 'Rewrite [' . $CONFIG['rewrite'] . ']';
		$array_debug[] = 'Gz [' . $CONFIG['ob_gzhandler'] . ']';
		$array_debug[] = 'Cookie [' . $CONFIG['site_cookie'] . ']';
		$array_debug[] = 'Site session [' . $CONFIG['site_session'] . ']';
		$array_debug[] = 'Site session invit [' . $CONFIG['site_session_invit'] . ']';
		$array_debug[] = '-------------CHMOD-------------';
		$array_debug[] = 'includes/auth/ [' . (is_writable('../includes/auth/') ? 1 : 0) . ']';
		$array_debug[] = 'includes/ [' . (is_writable('../includes/') ? 1 : 0) . ']';
		$array_debug[] = 'cache/ [' . (is_writable('../cache/') ? 1 : 0) . ']';
		$array_debug[] = 'upload/ [' . (is_writable('../upload/') ? 1 : 0) . ']';
		$array_debug[] = '/ [' . (is_writable('../') ? 1 : 0) . ']';
		
		$debug = '$array_debug = ' . var_export($array_debug, true) . ';' . "\n";
		$debug .= 'echo \'<pre>\'; print_r($array_debug); echo \'</pre>\';';
		
		return $debug;
	}
	
	//Configuration des membres
	function generate_file_member()
	{
		global $Sql;
		
		$config_member = 'global $CONFIG_MEMBER;' . "\n";
	
		//Récupération du tableau linéarisé dans la bdd.
		$CONFIG_MEMBER = unserialize((string)$Sql->Query("SELECT value FROM ".PREFIX."configs WHERE name = 'member'", __LINE__, __FILE__));
		foreach($CONFIG_MEMBER as $key => $value)
			$config_member .= '$CONFIG_MEMBER[\'' . $key . '\'] = ' . var_export($value, true) . ';' . "\n";

		return $config_member;
	}

	//Rangs
	function generate_file_ranks()
	{
		global $Sql;
		
		$stock_array_ranks = '$_array_rank = array(';	
		$result = $Sql->Query_while("SELECT name, msg, icon
		FROM ".PREFIX."ranks 
		ORDER BY msg DESC", __LINE__, __FILE__);
		while( $row = $Sql->Sql_fetch_assoc($result) )
		{
			$stock_array_ranks .= "\n" . var_export($row['msg'], true) . ' => array(' . var_export($row['name'], true) . ', ' . var_export($row['icon'], true) . '),';
		}	
		$Sql->Close($result);
		
		$stock_array_ranks = trim($stock_array_ranks, ',');
		$stock_array_ranks .= ');';	
		return	'global $_array_rank;' . "\n" . $stock_array_ranks;	
	}
	
	//Commentaires.
	function generate_file_files()
	{
		global $Sql;
		
		$config_files = 'global $CONFIG_FILES;' . "\n";
			
		//Récupération du tableau linéarisé dans la bdd.
		$CONFIG_FILES = unserialize((string)$Sql->Query("SELECT value FROM ".PREFIX."configs WHERE name = 'files'", __LINE__, __FILE__));
		$CONFIG_FILES = is_array($CONFIG_FILES) ? $CONFIG_FILES : array();
		foreach($CONFIG_FILES as $key => $value)
		{	
			if( $key == 'auth_files' )
				$config_files .= '$CONFIG_FILES[\'auth_files\'] = ' . var_export(unserialize($value), true) . ';' . "\n";
			else
				$config_files .= '$CONFIG_FILES[\'' . $key . '\'] = ' . var_export($value, true) . ';' . "\n";		
		}
		return $config_files;
	}
	
	//Commentaires.
	function generate_file_com()
	{
		global $Sql;
		
		$com_config = 'global $CONFIG_COM;' . "\n";
			
		//Récupération du tableau linéarisé dans la bdd.
		$CONFIG_COM = unserialize((string)$Sql->Query("SELECT value FROM ".PREFIX."configs WHERE name = 'com'", __LINE__, __FILE__));
		$CONFIG_COM = is_array($CONFIG_COM) ? $CONFIG_COM : array();
		foreach($CONFIG_COM as $key => $value)
		{
			if( $key == 'forbidden_tags' )
				$com_config .= '$CONFIG_COM[\'forbidden_tags\'] = ' . var_export(unserialize($value), true) . ';' . "\n";
			else
				$com_config .= '$CONFIG_COM[\'' . $key . '\'] = ' . var_export($value, true) . ';' . "\n";		
		}		
		return $com_config;
	}
	
	//Smileys
	function generate_file_smileys()
	{
		global $Sql;
		
		$i = 0;
		$stock_smiley_code = '$_array_smiley_code = array(';
		$result = $Sql->Query_while("SELECT code_smiley, url_smiley 
		FROM ".PREFIX."smileys", __LINE__, __FILE__);
		while( $row = $Sql->Sql_fetch_assoc($result) )
		{
			$coma = ($i != 0) ? ',' : '';
			$stock_smiley_code .=  $coma . "\n" . '' . var_export($row['code_smiley'], true) . ' => ' . var_export($row['url_smiley'], true);
			$i++;
		}
		$Sql->Close($result);
		$stock_smiley_code .= "\n" . ');';
		
		return 'global $_array_smiley_code;' . "\n" . $stock_smiley_code;
	}
	
	//Statistiques
	function generate_file_stats()
	{
		global $Sql;
		
		$code = 'global $nbr_members, $last_member_login, $last_member_id;' . "\n";
		$nbr_members = $Sql->Query("SELECT COUNT(*) FROM ".PREFIX."member", __LINE__, __FILE__);
		$last_member = $Sql->Query_array('member', 'user_id', 'login', "ORDER BY timestamp DESC " . $Sql->Sql_limit(0, 1), __LINE__, __FILE__);

		$code .= '$nbr_members = ' . var_export($nbr_members, true) . ';' . "\n";
		$code .= '$last_member_login = ' . var_export($last_member['login'], true) . ';' . "\n";
		$code .= '$last_member_id = ' . var_export($last_member['user_id'], true). ';' . "\n";
		
		$array_stats_img = array('browser.png', 'os.png', 'lang.png', 'theme.png', 'sex.png');
		foreach($array_stats_img as $key => $value)
			@unlink('../cache/' . $value);
		
		return $code;
	}
	
	## Private Attributes ##
	//Tableau qui contient tous les fichiers supportés dans cette classe
    var $files = array('config', 'modules', 'modules_mini', 'day', 'groups', 'debug', 'member', 'files', 'com', 'ranks', 'smileys', 'stats');
}

?>