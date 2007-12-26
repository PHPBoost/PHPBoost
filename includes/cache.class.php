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
class Cache extends Sql
{
    //Tableau qui contient tous les fichiers supportés dans cette classe
    var $files = array('config', 'modules', 'modules_mini', 'day', 'groups', 'member', 'files', 'com', 'ranks', 'smileys', 'stats');
    
	//On vérifie que le répertoire cache existe et est inscriptible
    function Cache(&$req)
    {
        parent::Sql();    
        $this->req = &$req;    //Récupère la valeure courante des requêtes en mémoire.
        if( !is_dir('../cache') || !is_writable('../cache') )
        {
            global $errorh;
		
			//Enregistrement dans le log d'erreur.
			$errorh->error_handler('Cache -> Le fichier cache doit être inscriptible, donc en CHMOD 777', E_USER_ERROR, __LINE__, __FILE__);
        }
    }
        
    //Fonction de chargement d'un fichier cache
    function load_file($file, $reload_cache = false)
    {
		global $errorh;
		
		//On charge le fichier
		$include = !$reload_cache ? @!include_once('../cache/' . $file . '.php') : @!include('../cache/' . $file . '.php');
		if( $include )
		{
			if( in_array($file, $this->files) )
			{
				//Régénération du fichier
				$this->generate_file($file);
				//On inclue une nouvelle fois				
				if( @!include('../cache/' . $file . '.php') )
				{
					//Enregistrement dans le log d'erreur.
					$errorh->error_handler('Cache -> Impossible de lire le fichier cache, ni de le régénérer!', E_USER_ERROR, __LINE__, __FILE__);
				}
			}
			else
			{
				//Régénération du fichier du module.
				$this->generate_module_file($file);
				//On inclue une nouvelle fois
				if( @!include('../cache/' . $file . '.php') )
				{
					//Enregistrement dans le log d'erreur.
					$errorh->error_handler('Cache -> Impossible de lire le fichier cache, ni de le régénérer!', E_USER_ERROR, __LINE__, __FILE__);
				}
			}
		}
    }
    
    //Génération de tous les fichiers
    function generate_all_files()
    {
        foreach( $this->files as $cache_file )
        {
            $this->generate_file($cache_file);
        }
		
		//Génération de tout les fichiers de cache des modules.
		$this->generate_all_module_files();
    }
	
	//Parcours les dossiers, à la recherche de fichiers de configuration en vue de regénérer le cache des modules.
	function generate_all_module_files()
	{
		global $CONFIG;
		
		$result = parent::query_while("SELECT name 
		FROM ".PREFIX."modules
		WHERE activ = 1", __LINE__, __FILE__);
		while( $row = parent::sql_fetch_assoc($result) )
		{
			$config = @parse_ini_file('../' . $row['name'] . '/lang/' . $CONFIG['lang'] . '/config.ini');
			//On récupère l'information sur le cache, si le cache est activé, on va chercher les fonctions de régénération de cache.
			if( !empty($config['cache']) && $config['cache'] )
			{
				//génération du cache.
				@include_once('../' . $row['name'] . '/' . $row['name'] . '_cache.php');
				$this->generate_module_file($row['name']);
			}
		}
		parent::close($result);
	}
	
    //Fonction d'enregistrement du fichier
    function generate_file($file)
    {
		global $errorh;
		
		$content = $this->{'generate_file_' . $file}();
		$file_path = '../cache/' . $file . '.php';
		@delete_file($file_path); //Supprime le fichier
		$handle = @fopen($file_path, 'w+'); //On crée le fichier avec droit d'écriture et lecture.
		@fwrite($handle, "<?php\n" . $content . "\n?>");
		@fclose($handle);
		
		//Il est l'heure de vérifier si la génération a fonctionnée, tentative d'appel recursif.
		if( !is_file($file_path) && filesize($file_path) == '0' )
			$errorh->error_handler('Cache -> La génération du fichier de cache ' . $file . ' a échoué!', E_USER_ERROR, __LINE__, __FILE__);
    }
    
	//Fonction d'enregistrement du fichier d'un module.
    function generate_module_file($file)
    {
		global $CONFIG;
		global $errorh;
		
		$root = '../';
		$dir = $file;
		//On vérifie que le fichier de configuration est présent.
		if( is_file($root . $dir . '/lang/' . $CONFIG['lang'] . '/config.ini') && is_file($root . $dir . '/' . $dir . '_cache.php') )
		{
			$config = @parse_ini_file($root . $dir . '/lang/' . $CONFIG['lang'] . '/config.ini');
			//On récupère l'information sur le cache, si le cache est activé, on va chercher les fonctions de régénération de cache.
			if( !empty($config['cache']) && $config['cache'] )
			{
				include_once($root . $dir . '/' . $dir . '_cache.php');
				$content = call_user_func('generate_module_file_' . $dir);
				$file_path = '../cache/' . $file . '.php';
				@delete_file($file_path); //Supprime le fichier
				$handle = @fopen($file_path, 'w+'); //On crée le fichier avec droit d'écriture et lecture.
				@fwrite($handle, "<?php\n" . $content . "\n?>");
				@fclose($handle);
				
				//Il est l'heure de vérifier si la génération a fonctionnée, tentative d'appel recursif.
				if( !is_file($file_path) && filesize($file_path) == '0' )
					$errorh->error_handler('Cache -> La génération du fichier de cache ' . $file . ' a échoué!', E_USER_ERROR, __LINE__, __FILE__);
			}
		}	
		else
		{
			//Enregistrement dans le log d'erreur.
			$errorh->error_handler('Cache -> Impossible de lire le fichier cache, ni de le régénérer!', E_USER_ERROR, __LINE__, __FILE__);
		}
    }
	
	//Génération du fichier htaccess
	function generate_htaccess()
	{
		global $CONFIG;
		
		if( $CONFIG['rewrite'] )
		{
			$htaccess_rules = 'Options +FollowSymlinks' . "\n" . 'RewriteEngine on' . "\n";
			$result = parent::query_while("SELECT name
			FROM ".PREFIX."modules
			WHERE activ = 1", __LINE__, __FILE__);
			while( $row = parent::sql_fetch_assoc($result) )
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
	
	
	
    ########## Fonctions de génération des fichiers un à un ##########
			
	//Gestions des modules installalés, configuration des autorisations.
	function generate_file_modules()
	{
		$code = 'global $SECURE_MODULE;' . "\r\n";
		$result = parent::query_while("SELECT name, auth
		FROM ".PREFIX."modules
		WHERE activ = 1
		ORDER BY name", __LINE__, __FILE__);
		while( $row = parent::sql_fetch_assoc($result) )
			$code .= '$SECURE_MODULE[\'' . $row['name'] . '\'] = ' . var_export(unserialize($row['auth']), true) . ';' . "\r\n";
		parent::close($result);

		return $code;
	}
	
	//Placements et autorisations des modules minis.
	function generate_file_modules_mini()
	{
		$code = '';
		$result = parent::query_while("SELECT name, code, contents, side, secure, added
		FROM ".PREFIX."modules_mini 
		WHERE activ = 1
		ORDER BY side, class", __LINE__, __FILE__);
		while( $row = parent::sql_fetch_assoc($result) )
		{
			if( $row['added'] == '0' )
			{
				if( $row['side'] == '0' )
					$code .= 'if( $BLOCK_top && $session->data[\'level\'] >= ' . $row['secure'] . ' ){' . $row['code'] . '}' . "\n";
				else
					$code .= 'if( $BLOCK_bottom && $session->data[\'level\'] >= ' . $row['secure'] . ' ){' . $row['code'] . '}' . "\n";
			}
			else
			{
				if( $row['side'] == '0' )
					$code .= 'if( $BLOCK_top && $session->data[\'level\'] >= ' . $row['secure'] . ' ){' . 
					"\$template->set_filenames(array('modules_mini' => '../templates/' . \$CONFIG['theme'] . '/modules_mini.tpl'));\$template->assign_vars(array('MODULE_MINI_NAME' => " . var_export($row['name'], true) . ", 'MODULE_MINI_CONTENTS' => " . var_export($row['contents'], true) . "));\$template->pparse('modules_mini');" . '}' . "\n";
				else
					$code .= 'if( $BLOCK_bottom && $session->data[\'level\'] >= ' . $row['secure'] . ' ){' . 
					"\$template->set_filenames(array('modules_mini' => '../templates/' . \$CONFIG['theme'] . '/modules_mini.tpl'));\$template->assign_vars(array('MODULE_MINI_NAME' => " . var_export($row['name'], true) . ", 'MODULE_MINI_CONTENTS' => " . var_export($row['contents'], true) . "));\$template->pparse('modules_mini');" . '}' . "\n";
			}
		}
		parent::close($result);

		return $code;
	}
	
	//Configuration du site
	function generate_file_config()
	{
		$config = 'global $CONFIG;' . "\n";
	
		//Récupération du tableau linéarisé dans la bdd.
		$CONFIG = unserialize(parent::query("SELECT value FROM ".PREFIX."configs WHERE name = 'config'", __LINE__, __FILE__));
		foreach($CONFIG as $key => $value)
			$config .= '$CONFIG[\'' . $key . '\'] = ' . var_export($value, true) . ";\n";

		return $config;
	}
	
	//Day
	function generate_file_day()
	{
		return 'global $_record_day;' . "\n" . '$_record_day = ' . date('j') . ';';
	}
	
	//Groupes
	function generate_file_groups()
	{
		$code = 'global $_array_groups_auth;' . "\n" . '$_array_groups_auth = array(' . "\n";
		$result = parent::query_while("SELECT id, name, img, auth
		FROM ".PREFIX."group	
		ORDER BY id", __LINE__, __FILE__);
		while( $row = parent::sql_fetch_assoc($result) )
		{
			$code .= $row['id'] . ' => array(' . var_export($row['name'], true) . ', ' . var_export($row['img'], true) . ', ' . $row['auth'] . '),' . "\n";
		}			
		parent::close($result);
		$code .= ');';
		
		return $code;
	}
	
	//Configuration des membres
	function generate_file_member()
	{
		$config_member = 'global $CONFIG_MEMBER;' . "\n";
	
		//Récupération du tableau linéarisé dans la bdd.
		$CONFIG_MEMBER = unserialize(parent::query("SELECT value FROM ".PREFIX."configs WHERE name = 'member'", __LINE__, __FILE__));
		foreach($CONFIG_MEMBER as $key => $value)
			$config_member .= '$CONFIG_MEMBER[\'' . $key . '\'] = ' . var_export($value, true) . ';' . "\n";

		return $config_member;
	}

	//Rangs
	function generate_file_ranks()
	{
		$stock_array_ranks = '$_array_rank = array(';	
		$result = parent::query_while("SELECT name, msg, icon
		FROM ".PREFIX."ranks 
		ORDER BY msg DESC", __LINE__, __FILE__);
		while( $row = parent::sql_fetch_assoc($result) )
		{
			$stock_array_ranks .= "\n" . var_export($row['msg'], true) . ' => array(' . var_export($row['name'], true) . ', ' . var_export($row['icon'], true) . '),';
		}	
		parent::close($result);
		
		$stock_array_ranks = trim($stock_array_ranks, ',');
		$stock_array_ranks .= ');';	
		return	'global $_array_rank;' . "\n" . $stock_array_ranks;	
	}
	
	//Commentaires.
	function generate_file_files()
	{
		$config_files = 'global $CONFIG_FILES;' . "\n";
			
		//Récupération du tableau linéarisé dans la bdd.
		$CONFIG_FILES = unserialize(parent::query("SELECT value FROM ".PREFIX."configs WHERE name = 'files'", __LINE__, __FILE__));
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
		$com_config = 'global $CONFIG_COM;' . "\n";
			
		//Récupération du tableau linéarisé dans la bdd.
		$CONFIG_COM = unserialize(parent::query("SELECT value FROM ".PREFIX."configs WHERE name = 'com'", __LINE__, __FILE__));
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
		$i = 0;
		$stock_smiley_code = '$_array_smiley_code = array(';
		$result = parent::query_while("SELECT code_smiley, url_smiley 
		FROM ".PREFIX."smileys", __LINE__, __FILE__);
		while( $row = parent::sql_fetch_assoc($result) )
		{
			$coma = ($i != 0) ? ',' : '';
			$stock_smiley_code .=  $coma . "\n" . '' . var_export($row['code_smiley'], true) . ' => ' . var_export($row['url_smiley'], true);
			$i++;
		}
		parent::close($result);
		$stock_smiley_code .= "\n" . ');';
		
		return 'global $_array_smiley_code;' . "\n" . $stock_smiley_code;
	}
	
	//Statistiques
	function generate_file_stats()
	{
		global $sql;
		
		$code = 'global $nbr_members, $last_member_login, $last_member_id;' . "\n";
		$nbr_members = $sql->query("SELECT COUNT(*) FROM ".PREFIX."member", __LINE__, __FILE__);
		$last_member = $sql->query_array('member', 'user_id', 'login', "ORDER BY timestamp DESC " . $sql->sql_limit(0, 1), __LINE__, __FILE__);

		$code .= '$nbr_members = ' . var_export($nbr_members, true) . ';' . "\n";
		$code .= '$last_member_login = ' . var_export($last_member['login'], true) . ';' . "\n";
		$code .= '$last_member_id = ' . var_export($last_member['user_id'], true). ';' . "\n";
		
		$array_stats_img = array('browser.png', 'os.png', 'lang.png', 'theme.png', 'sex.png');
		foreach($array_stats_img as $key => $value)
			@unlink('../cache/' . $value);
		
		return $code;
	}
}

?>