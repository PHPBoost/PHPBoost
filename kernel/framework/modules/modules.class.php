<?php

/*##################################################
 *                              modules.class.php
 *                            -------------------
 *   begin                : January 15, 2008
 *   copyright            : (C) 2008 Rouchon Loïc
 *   email                : horn@phpboost.com
 *
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

require_once(PATH_TO_ROOT . '/kernel/framework/modules/module_interface.class.php');

/**
 *  Les arguments de fonction nommé "$modules" sont assez particulier.
 *
 *  Il s'agit d'un tableau avec comme clés le nom des modules et comme
 *  arguments un tableau d'arguments correspondant à la liste des arguments
 *  nécessaire pour la méthode de ce module particulier.
 *
 *  Par exemple, la recherche sur le forum peut nécessiter plus d'option
 *  qu'une recherche sur le wiki.
 *
 */

class Modules
{
    //---------------------------------------------------------- Constructeurs
    function Modules()
    /**
     *  Constructeur de la classe Modules
     */
    {
        global $MODULES;
        
        $this->loaded_modules = array();
        $this->availables_modules = array_keys($MODULES);
    }
    
    //----------------------------------------------------------------- PUBLIC
    //----------------------------------------------------- Méthodes publiques
    function functionnality($functionnality, $modules)
    /**
     *  Vérifie les fonctionnalités des modules et appelle la méthode
     *  du/des module(s) sélectionné(s) avec les bons arguments.
     */
    {
        $results = array();
        foreach($modules as $moduleName => $args)
        {
            // Instanciation de l'objet $module
            $module = $this->get_module($moduleName);
            // Si le module à déjà été appelé et a déjà eu une erreur,
            // On nettoie le bit d'erreur correspondant.
            $module->clear_functionnality_error();
            if( $module->has_functionnality($functionnality) == true )
                $results[$moduleName] = $module->functionnality($functionnality, $args);
        }
        return $results;
    }

    function get_available_modules($functionnality='get_id', $modulesList = array())
    /**
     *  Renvoie la liste des modules disposant de la fonctionnalité demandée.
     *  Si $modulesList est spécifié, alors on ne recherche que le sous ensemble de celui-ci 
     */
    {
        $modules = array();
        if( $modulesList === array() )
        {
            global $MODULES;
            
            foreach(array_keys($MODULES) as $moduleId)
            {
			   $module = $this->get_module($moduleId);
                if( !$module->got_error() && $module->has_functionnality($functionnality) )
                    array_push($modules, $module);
            }
        }
        else
        {
            foreach($modulesList as $module)
            {
                if( !$module->got_error() && $module->has_functionnality($functionnality) )
                    array_push($modules, $module);
            }
        }
        return $modules;
    }

    function get_module($moduleId = '')
    /**
     *  Instancie et renvoie le module demandé.
     */
    {
		if( !isset($this->loaded_modules[$moduleId]) )
        {
            if( in_array($moduleId, $this->availables_modules) )
            {
                global $User, $MODULES;
                
                if( $User->check_auth($MODULES[$moduleId]['auth'], ACCESS_MODULE) )
                {
                    if( @include_once(PATH_TO_ROOT . '/'.$moduleId.'/'.$moduleId.'_interface.class.php') )
                    {
                        $module_constructor = ucfirst($moduleId.'Interface');
                        $Module = new $module_constructor();
                    }
                    else $Module = new ModuleInterface($moduleId, MODULE_NOT_YET_IMPLEMENTED);
                }
                else 
					$Module = new ModuleInterface($moduleId, ACCES_DENIED);
            }
            else 
				$Module = new ModuleInterface($moduleId, MODULE_NOT_AVAILABLE);
				
            $this->loaded_modules[$moduleId] = $Module;
        }
        return $this->loaded_modules[$moduleId];
    }

    //------------------------------------------------------------------ PRIVE
    /**
     *  Pour des raisons de compatibilité avec PHP 4, les mots-clés private,
     *  protected et public ne sont pas utilisé.
     *
     *  L'appel aux méthodes et/ou attributs PRIVE/PROTEGE est donc possible.
     *  Cependant il est strictement déconseillé, car cette partie du code
     *  est suceptible de changer sans avertissement et donc vos modules ne
     *  fonctionnerai plus.
     *
     *  Bref, utilisation à vos risques et périls !!!
     *
     */
    //----------------------------------------------------- Méthodes protégées

    //----------------------------------------------------- Attributs protégés
    var $loadedModules;
    var $availablesModules;
}

?>
