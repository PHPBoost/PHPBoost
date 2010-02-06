<?php
/*##################################################
 *                              modules.class.php
 *                            -------------------
 *   begin                : January 15, 2008
 *   copyright            : (C) 2008 Rouchon Loïc
 *   email                : loic.rouchon@phpboost.com
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



/**
 * @author Loïc Rouchon <loic.rouchon@phpboost.com>
 * @desc This class is a ModuleInterface factory providing some services like
 * mass operations (on several modules at the same time) or identifications
 * methods to get all modules that provide a given functionality
 * @package modules
 */
class ModulesDiscoveryService
{
	private $loaded_modules;
	private $available_modules;

	/**
	 * @desc Builds a new ModuleInterface factory
	 */
	public function __construct()
	{
		$this->loaded_modules = array();
		$this->availables_modules = array();
		foreach (ModulesManager::get_installed_modules_map() as $module_id => $module)
		{
			if ($module->is_activated())
			{
				$this->availables_modules[] = $module_id;
			}
		}
		$this->availables_modules[] = 'kernel';
		if (ClassLoader::is_class_registered_and_valid('CLIInstallCommand'))
		{
			$this->availables_modules[] = 'install';
		}
	}


	/**
	 * @desc Call the method call functionality on each speficied modules
	 * @param string $functionality The method name to call on ModuleInterfaces
	 * @param mixed[string] $modules The modules arguments in an array which keys
	 * are modules ids and values specifics arguments for those modules.
	 * @return mixed[string] The results of the functionality method on all
	 * modules. This array has keys that are the modules ids and the associated
	 * value is the return value for this particular module.
	 */
	public function functionality($functionality, $modules)
	{
		$results = array();
		foreach ($modules as $module_id => $args)
		{
			// Instanciate the ModuleInterface
			$module = $this->get_module($module_id);
			// Clear any pre-existing functionality error flag
			$module->clear_functionality_error();
			if ($module->has_functionality($functionality) == true)
			{
				$results[$module_id] = $module->functionality($functionality, $args);
			}
		}
		return $results;
	}


	/**
	 * @desc Returns a list with all the modules in it, even with those that have
	 * no ModuleInterface.
	 * Useful to do generic operations on modules.
	 * @return ModuleInterface[] the ModuleInterface list.
	 */
	public function get_all_modules()
	{
		return $this->get_available_modules();
	}


	/**
	 * @desc Returns the ModuleInterface list.
	 * @param string $functionality the functionality name. By default, returns
	 * all availables modules interfaces.
	 * @param ModuleInterface[] $modulesList If specified, only keep modules
	 * interfaces having the requested functionality. Else, search in all
	 * availables modules interfaces.
	 * @return ModuleInterface[] the ModuleInterface list.
	 */
	public function get_available_modules($functionality = 'none', $modules_list = array())
	{
		$modules = array();
		if ($modules_list === array())
		{
			//            foreach (ModulesManager::get_installed_modules_ids_list() as $extension_provider_id)
			foreach ($this->availables_modules as $extension_provider_id)
			{
				$module = $this->get_module($extension_provider_id);
				if (!$module->got_error() && ($functionality === 'none' || $module->has_functionality($functionality)))
				{
					$modules[$module->get_id()] = $module;
				}
			}
		}
		else
		{
			foreach ($modules_list as $module)
			{
				if (!$module->got_error() && $module->has_functionality($functionality))
				{
					$modules[$module->get_id()] = $module;
				}
			}
		}
		return $modules;
	}


	/**
	 * @desc Returns the ModuleInterface of the module which id is $module_id.
	 * @param string $module_id The module id.
	 * @return ModuleInterface The corresponding ModuleInterface.
	 */
	public function get_module($module_id = '')
	{
		$classname = ucfirst($module_id) . 'Interface';
		$module = null;
		if (ClassLoader::is_class_registered($classname))
		{   // The Interface exists

			if (isset($this->loaded_modules[$module_id]))
			{
				return $this->loaded_modules[$module_id];
			}
            $module = new $classname();

			//			if (in_array($module_id, $this->availables_modules))
			//			{   // The interface is available
			//				$user = AppContext::get_user();
			//				if (!ModulesManager::get_module($module_id)->check_auth())
			//				{   // ACCESS DENIED
			//					$module->set_error(ACCES_DENIED);
			//				}
			//			}
			//			else
			//			{   // NOT AVAILABLE
			//				$module->set_error(MODULE_NOT_AVAILABLE);
			//			}
		}
		else
		{   // NOT IMPLEMENTED
			$module = new ModuleInterface($module_id, MODULE_NOT_YET_IMPLEMENTED);
		}

		$this->loaded_modules[$module_id] = $module;
		return $this->loaded_modules[$module_id];
	}
}

?>
