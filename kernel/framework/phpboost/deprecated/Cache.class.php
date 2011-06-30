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
 * @package {@package}
 * @author Benoît Sautel <ben.popeye@phpboost.com>
 * @desc This class is the cache manager of PHPBoost.
 * Its functioning is very particular. Loading a file is equivalent to include the file. The cache file must define some PHP global variables.
 * They will be usable in the execution context of the page.
 * You should read on the PHPBoost website the documentation which explains you how to integrate a cache for you module, it's too much complex to be explained here.
 */
class Cache
{
	private static $sql;
	
	public static function __static()
	{
		self::$sql = PersistenceContext::get_sql();
	}
	
	/**
	 * @desc Builds a Cache object. Check if the directory in which the cache is written is writable.
	 */
	function Cache()
	{
		if (!is_dir(PATH_TO_ROOT . '/cache') || !is_writable(PATH_TO_ROOT . '/cache'))
		{
			//Enregistrement dans le log d'erreur.
			$controller = new UserErrorController(LangLoader::get_message('error', 'errors'), 
                'Cache -> Le dossier /cache doit être inscriptible, donc en CHMOD 777', UserErrorController::FATAL);
            DispatchManager::redirect($controller);
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
		//On charge le fichier
		$cache_file = PATH_TO_ROOT . '/cache/' . $file . '.php';
		$include = false;
		if ($reload_cache)
		{
			if (file_exists($cache_file))
			{
				$include = include($cache_file);
			}
		}
		else
		{
			if (file_exists($cache_file))
			{
				$include = include_once($cache_file);
			}
		}
		if (!$include)
		{
			if (in_array($file, $this->files))
			{
				//Régénération du fichier
				$this->generate_file($file);
				//On inclue une nouvelle fois
				$include2 = include($cache_file);
				if (!$include2)
				{
					$controller = new UserErrorController(LangLoader::get_message('error', 'errors'), 
                        'Cache -> Can\'t generate <strong>' . $file . '</strong>, cache file!', UserErrorController::FATAL);
                    DispatchManager::redirect($controller);
				}
			}
			else
			{
				//Régénération du fichier du module.
				$this->generate_module_file($file);
				//On inclue une nouvelle fois
				$include3 = include($cache_file);
				if (!$include3)
				{
					$controller = new UserErrorController(LangLoader::get_message('error', 'errors'), 
                         'Cache -> Can\'t generate <strong>' . $file . '</strong>, cache file!', UserErrorController::FATAL);
                    DispatchManager::redirect($controller);
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
		$modules_loader = AppContext::get_extension_provider_service();
		$module = $modules_loader->get_provider($module_name);

		if ($module->has_extension_point('get_cache')) //Le module implémente bien la fonction.
		{
			$module_cache = $module->get_extension_point('get_cache');
			$this->write($module_name, $module_cache);
		}
		elseif (!$no_alert_on_error)
		{
			$controller = new UserErrorController(LangLoader::get_message('error', 'errors'), 
                'Cache -&gt; Le module <strong>' . $module_name . '</strong> n\'a pas de fonction de cache!', UserErrorController::FATAL);
            DispatchManager::redirect($controller);
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


		$modulesLoader = AppContext::get_extension_provider_service();
		$modules = $modulesLoader->get_providers('get_cache');
		foreach ($modules as $module)
		{
			if ($MODULES[strtolower($module->get_id())]['activ'] == '1') //Module activé
			{
				$this->write(strtolower($module->get_id()), $module->get_extension_point('get_cache'));
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


		$cache_file = new File($file_path);

		//Suppression du fichier (si il existe)
		$cache_file->delete();

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
			$controller = new UserErrorController(LangLoader::get_message('error', 'errors'), 
                'Cache -> Can\'t generate <strong>' . $file . '</strong>, cache file!', UserErrorController::FATAL);
            DispatchManager::redirect($controller);
		}
	}

	## Private Methods ##
	########## Fonctions de génération des fichiers un à un ##########

	/**
	 * @desc Method which is called to generate the menus file cache.
	 * @return The content of the menus file cache.
	 */
	function _get_menus()
	{
		return MenuService::generate_cache(true);
	}
	
	## Private Attributes ##
	/**
	* @static
	* @var string[] List of all the cache files of the kernel.
	*/
	var $files = array('menus');
}

?>