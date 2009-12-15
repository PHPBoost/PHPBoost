<?php
/*##################################################
 *                              cache.class.php
 *                            -------------------
 *   begin                : August 28, 2006
 *   copyright            : (C) 2006 Benoît Sautel / Régis Viarre
 *   email                : ben.popeye@phpboost / crowkait@phpboost.com
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

define('RELOAD_CACHE', true);
define('NO_FATAL_ERROR_CACHE', true);

/**
 * @package core
 * @author Benoît Sautel <ben.popeye@phpboost.com>
 * @desc This class is the cache manager of PHPBoost.
 * Its functioning is very particular. Loading a file is equivalent to include the file. The cache file must define some PHP global variables.
 * They will be usable in the execution context of the page.
 * You should read on the PHPBoost website the documentation which explains you how to integrate a cache for you module, it's too much complex to be explained here.
 */
class Cache
{
	/**
	 * @desc Builds a Cache object. Check if the directory in which the cache is written is writable.
	 */
	function Cache()
	{
		if (!is_dir(PATH_TO_ROOT . '/cache') || !is_writable(PATH_TO_ROOT . '/cache'))
		{
			global $Errorh;

			//Enregistrement dans le log d'erreur.
			$Errorh->handler('Cache -> Le dossier /cache doit être inscriptible, donc en CHMOD 777', E_USER_ERROR, __LINE__, __FILE__);
		}
	}

	/**
	 * @desc Loads a file file.
	 * @param string $file Identifier of the cache file (for example the name of your module).
	 * @param bool $reload_cache If the cache file may have been already loaded, RELOAD_CACHE force it to be reloaded, for example if the file
	 * has been updated since the first loading.
	 */
	function load($file, $reload_cache = false)
	{
		global $Errorh, $Sql;

		//On charge le fichier
		$cache_file = PATH_TO_ROOT . '/cache/' . $file . '.php';
		$include = false;
		if ($reload_cache)
		{
			if (!DEBUG)
			{
				$include = @include($cache_file);
			}
			else
			{
				if (file_exists($cache_file))
				{
					$include = include($cache_file);
				}
			}
		}
		else
		{
			if (!DEBUG)
			{
				$include = @include_once($cache_file);
			}
			else
			{
				if (file_exists($cache_file))
				{
					$include = include_once($cache_file);
				}
			}
		}
		if (!$include)
		{
			if (in_array($file, $this->files))
			{
				//Régénération du fichier
				$this->generate_file($file);
				//On inclue une nouvelle fois
				if (!DEBUG)
				{
					$include2 = @include($cache_file);
				}
				else
				{
					$include2 = include($cache_file);
				}
				if (!$include2)
				{
					$Errorh->handler('Cache -> Can\'t generate <strong>' . $file . '</strong>, cache file!', E_USER_ERROR, __LINE__, __FILE__); //Enregistrement dans le log d'erreur.
				}
			}
			else
			{
				//Régénération du fichier du module.
				$this->generate_module_file($file);
				//On inclue une nouvelle fois
				if (!DEBUG)
				{
					$include3 = @include($cache_file);
				}
				else
				{
					$include3 = include($cache_file);
				}
				if (!$include3)
				{
					$Errorh->handler('Cache -> Can\'t generate <strong>' . $file . '</strong>, cache file!', E_USER_ERROR, __LINE__, __FILE__); //Enregistrement dans le log d'erreur.
				}
			}
		}
	}

	/**
	 * @desc Generates a file according to the specified method.
	 * @param string $file The name of the file to generate.
	 */
	function generate_file($file)
	{
		$content = $this->{'_get_' . $file}();
		$this->write($file, $content);
	}

	/**
	 * @desc Generates a module file
	 * @param string $module_name Name of the module for which you want to generate the cache.
	 * @param bool $no_alert_on_error true if you want to display the generation error, false otherwise.
	 */
	function generate_module_file($module_name, $no_alert_on_error = false)
	{
		global $Errorh;


		$modulesLoader = new ModulesDiscoveryService();
		$module = $modulesLoader->get_module($module_name);

		if ((!$module->get_errors() || $module->got_error(ACCES_DENIED)) && $module->has_functionality('get_cache')) //Le module implémente bien la fonction.
		{
			$module_cache = $module->functionality('get_cache');
			$this->write($module_name, $module_cache);
		}
		elseif (!$no_alert_on_error)
		{
			$Errorh->handler('Cache -&gt; Le module <strong>' . $module_name . '</strong> n\'a pas de fonction de cache!', E_USER_ERROR, __LINE__, __FILE__);
		}
	}

	/**
	 * @desc Regenerates all the cache files managed by the PHPBoost cache manager. This method needs a lot of resource, call it only when you are sure you need it.
	 */
	function generate_all_files()
	{
		foreach ($this->files as $cache_file)
		{
			$this->generate_file($cache_file);
		}

		//Génération de tout les fichiers de cache des modules.
		$this->generate_all_modules();
	}

	/**
	 * @desc Generates all the module cache files.
	 */
	function generate_all_modules()
	{
		global $MODULES;


		$modulesLoader = new ModulesDiscoveryService();
		$modules = $modulesLoader->get_available_modules('get_cache');
		foreach ($modules as $module)
		{
			if ($MODULES[strtolower($module->get_id())]['activ'] == '1') //Module activé
			{
				$this->write(strtolower($module->get_id()), $module->functionality('get_cache'));
			}
		}
	}

	/**
	 * @desc Deletes a cache file.
	 * @param string $file Name of the file to delete.
	 * @return true if the file could be deleted, false otherwise.
	 */
	function delete_file($file)
	{
		if (@file_exists(PATH_TO_ROOT . '/cache/' . $file . '.php'))
		{
			return @unlink(PATH_TO_ROOT . '/cache/' . $file . '.php');
		}
		else
		{
			return false;
		}
	}

	/**
	 * @desc Writes a cache file.
	 * @param string $module_name Name of the file to write
	 * @param string $cache_string Content of the file to write
	 */
	function write($module_name, $cache_string)
	{
		$file_path = PATH_TO_ROOT . '/cache/' . $module_name . '.php';


		$cache_file = new File($file_path, File::WRITE);

		//Suppression du fichier (si il existe)
		$cache_file->delete();

		//Ouverture du fichier
		$cache_file->open();

		//Verrouillage du fichier (comme un mutex si une autre tâche travaille actuellement dessus)
		$cache_file->lock();

		//Ecriture de son contenu
		$cache_file->write("<?php\n" . $cache_string . "\n?>");

		//Déverrouillage du fichier (on relâche le mutex)
		$cache_file->unlock();

		//Fermeture du fichier
		$cache_file->close();

		//On lui met les autorisations nécessaires de façon à pouvoir par la suite le lire (4) et le supprimer (2), soit 4 + 2 
		$cache_file->change_chmod(0666);

		//Il est l'heure de vérifier si la génération a fonctionné.
		if (!file_exists($file_path) && filesize($file_path) == 0)
		{
			$Errorh->handler('Cache -> La génération du fichier de cache <strong>' . $file . '</strong> a échoué!', E_USER_ERROR, __LINE__, __FILE__);
		}
	}

	## Private Methods ##
	########## Fonctions de génération des fichiers un à un ##########
	/**
	* @desc Method which is called to generate the modules file cache.
	* @return The content of the modules file cache.
	*/
	function _get_modules()
	{
		global $Sql;

		$code = 'global $MODULES;' . "\n";
		$code .= '$MODULES = array();' . "\n\n";
		$result = $Sql->query_while("SELECT name, auth, activ
		FROM " . PREFIX . "modules
		ORDER BY name", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			$code .= '$MODULES[\'' . $row['name'] . '\'] = array(' . "\n"
			. "'name' => " . var_export($row['name'], true) . ',' . "\n"
			. "'activ' => " . var_export($row['activ'], true) . ',' . "\n"
			. "'auth' => " . var_export(unserialize($row['auth']), true) . ',' . "\n"
			. ");\n";
		}
		$Sql->query_close($result);

		return $code;
	}

	/**
	 * @desc Method which is called to generate the menus file cache.
	 * @return The content of the menus file cache.
	 */
	function _get_menus()
	{

		return MenuService::generate_cache(true);
	}

	/**
	 * @desc Method which is called to generate the site configuration file cache.
	 * @return The content of the site configuration file cache.
	 */
	function _get_config()
	{
		global $Sql;

		$config = 'global $CONFIG;' . "\n" . '$CONFIG = array();' . "\n";
		//Récupération du tableau linéarisé dans la bdd
		$CONFIG = unserialize((string) AppContext::get_sql()->query(
			"SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name='config'"));
		foreach ($CONFIG as $key => $value)
		{
			$config .= '$CONFIG[\'' . $key . '\'] = ' . var_export($value, true) . ";\n";
		}

		return $config;
	}

	/**
	 * @desc Method which is called to generate the debug file cache (it cannot be in the configuration file because it must be loaded before the PHPBoost environment).
	 * @return The content of the debug file cache.
	 */
	function _get_debug()
	{
		$this->load('config');
		global $CONFIG;

		$debug_mode = empty($CONFIG['debug_mode']) ? 0 : (int)$CONFIG['debug_mode'];
		return 'global $DEBUG;' . "\n" . '$DEBUG[\'debug_mode\'] = ' . $debug_mode . ';';
	}

	/**
	 * @desc Method which is called to generate the themes file cache.
	 * @return The content of the themes file cache.
	 */
	function _get_themes()
	{
		global $Sql;

		$code = 'global $THEME_CONFIG;' . "\n";
		$result = $Sql->query_while("SELECT theme, left_column, right_column, secure
		FROM " . DB_TABLE_THEMES . "
		WHERE activ = 1", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			$code .= '$THEME_CONFIG[\'' . addslashes($row['theme']) . '\'][\'left_column\'] = ' . var_export((bool)$row['left_column'], true) . ';' . "\n";
			$code .= '$THEME_CONFIG[\'' . addslashes($row['theme']) . '\'][\'right_column\'] = ' . var_export((bool)$row['right_column'], true) . ';' . "\n";
			$code .= '$THEME_CONFIG[\'' . addslashes($row['theme']) . '\'][\'secure\'] = ' . var_export($row['secure'], true) . ';' . "\n\n";
		}
		$Sql->query_close($result);

		return $code . '$THEME_CONFIG[\'default\'][\'left_column\'] = true;' . "\n" . '$THEME_CONFIG[\'default\'][\'right_column\'] = true;' . "\n" . '$THEME_CONFIG[\'default\'][\'secure\'] = \'-1\'';
	}

	/**
	 * @desc Method which is called to generate the langs file cache.
	 * @return The content of the langs file cache.
	 */
	function _get_langs()
	{
		global $Sql;

		$code = 'global $LANGS_CONFIG;' . "\n";
		$result = $Sql->query_while("SELECT lang, secure
		FROM " . PREFIX . "lang
		WHERE activ = 1", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			$code .= '$LANGS_CONFIG[\'' . addslashes($row['lang']) . '\'][\'secure\'] = ' . var_export($row['secure'], true) . ';' . "\n\n";
		}
		$Sql->query_close($result);

		return $code;
	}

	/**
	 * @desc Method which is called to generate the members file cache.
	 * @return The content of the members file cache.
	 */
	function _get_member()
	{
		global $Sql;

		$config_member = 'global $ADMINISTRATOR_ALERTS;' . "\n";

		$config_member .= "\n" . '$ADMINISTRATOR_ALERTS = ' . var_export(AdministratorAlertService::compute_number_unread_alerts(), true) . ';';

		return $config_member;
	}

	/**
	 * @desc Method which is called to generate the ranks file cache.
	 * @return The content of the ranks file cache.
	 */
	function _get_ranks()
	{
		global $Sql;

		$stock_array_ranks = '$_array_rank = array(';
		$result = $Sql->query_while("SELECT name, msg, icon
		FROM " . PREFIX . "ranks
		ORDER BY msg DESC", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			$stock_array_ranks .= "\n" . var_export($row['msg'], true) . ' => array(' . var_export($row['name'], true) . ', ' . var_export($row['icon'], true) . '),';
		}
		$Sql->query_close($result);

		$stock_array_ranks = trim($stock_array_ranks, ',');
		$stock_array_ranks .= ');';
		return	'global $_array_rank;' . "\n" . $stock_array_ranks;
	}

	/**
	 * @desc Method which is called to generate the uploads file cache.
	 * @return The content of the uploads file cache.
	 */
	function _get_uploads()
	{
		global $Sql;

		$config_uploads = 'global $CONFIG_UPLOADS;' . "\n";
			
		//Récupération du tableau linéarisé dans la bdd
		$CONFIG_UPLOADS = unserialize((string)$Sql->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'uploads'", __LINE__, __FILE__));
		$CONFIG_UPLOADS = is_array($CONFIG_UPLOADS) ? $CONFIG_UPLOADS : array();
		foreach ($CONFIG_UPLOADS as $key => $value)
		{
			if ($key == 'auth_files')
			{
				$config_uploads .= '$CONFIG_UPLOADS[\'auth_files\'] = ' . var_export(unserialize($value), true) . ';' . "\n";
			}
			else
			{
				$config_uploads .= '$CONFIG_UPLOADS[\'' . $key . '\'] = ' . var_export($value, true) . ';' . "\n";
			}
		}
		return $config_uploads;
	}

	/**
	 * @desc Method which is called to generate the comments file cache.
	 * @return The content of the comments file cache.
	 */
	function _get_com()
	{
		global $Sql;

		$com_config = 'global $CONFIG_COM;' . "\n";
			
		//Récupération du tableau linéarisé dans la bdd
		$CONFIG_COM = unserialize((string)$Sql->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'com'", __LINE__, __FILE__));
		$CONFIG_COM = is_array($CONFIG_COM) ? $CONFIG_COM : array();
		foreach ($CONFIG_COM as $key => $value)
		{
			$com_config .= '$CONFIG_COM[\'' . $key . '\'] = ' . var_export($value, true) . ';' . "\n";
		}

		return $com_config;
	}

	//Smileys
	function _get_smileys()
	{
		global $Sql;

		$i = 0;
		$stock_smiley_code = '$_array_smiley_code = array(';
		$result = $Sql->query_while("SELECT code_smiley, url_smiley
		FROM " . PREFIX . "smileys", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			$comma = ($i != 0) ? ',' : '';
			$stock_smiley_code .=  $comma . "\n" . '' . var_export($row['code_smiley'], true) . ' => ' . var_export($row['url_smiley'], true);
			$i++;
		}
		$Sql->query_close($result);
		$stock_smiley_code .= "\n" . ');';

		return 'global $_array_smiley_code;' . "\n" . $stock_smiley_code;
	}

	/**
	 * @desc Method which is called to generate the statistics file cache.
	 * @return The content of the statistics file cache.
	 */
	function _get_stats()
	{
		global $Sql;

		$code = 'global $nbr_members, $last_member_login, $last_member_id;' . "\n";
		$nbr_members = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_MEMBER . " WHERE user_aprob = 1", __LINE__, __FILE__);
		$last_member = $Sql->query_array(DB_TABLE_MEMBER, 'user_id', 'login', "WHERE user_aprob = 1 ORDER BY timestamp DESC " . $Sql->limit(0, 1), __LINE__, __FILE__);

		$code .= '$nbr_members = ' . var_export($nbr_members, true) . ';' . "\n";
		$code .= '$last_member_login = ' . var_export($last_member['login'], true) . ';' . "\n";
		$code .= '$last_member_id = ' . var_export($last_member['user_id'], true). ';' . "\n";

		$array_stats_img = array('browser.png', 'os.png', 'lang.png', 'theme.png', 'sex.png');
		foreach ($array_stats_img as $key => $value)
		{
			@unlink(PATH_TO_ROOT . '/cache/' . $value);
		}

		return $code;
	}

	## Private Attributes ##
	/**
	* @static
	* @var string[] List of all the cache files of the kernel.
	*/
	var $files = array('config', 'debug', 'modules', 'menus', 'themes', 'langs', 'member', 'uploads', 'com', 'ranks', 'smileys', 'stats');
}

?>