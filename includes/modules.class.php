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


require_once ( '../includes/module_interface.class.php' );

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
    //----------------------------------------------------------------- PUBLIC
    //----------------------------------------------------- Méthodes publiques
    function Functionnality ( $functionnality, $modules )
    /**
     *  Vérifie les fonctionnalités des modules et appelle la méthode
     *  du/des module(s) sélectionné(s) avec les bons arguments.
     */
    {
        $results = Array ( );
        foreach( $modules as $moduleName => $args )
        {
            // Instanciation de l'objet $module
            $module = $this->GetModule ( $moduleName );
            // Si le module à déjà été appelé et a déjà eu une erreur,
            // On nettoie le bit d'erreur correspondant.
            $Module->clearFunctionnalityError ( );
            if ( $Module->hasFunctionnality ( $functionnality ) == true )
            { $results[$moduleName] = $Module->Functionnality ( $functionnality, $args ); }
        }
        return $results;
    }

    function GetAvailablesModules ( $functionnality, $modulesList = Array ( ) )
    /**
     *  Renvoie la liste des modules disposant de la fonctionnalité demandée.
     *  Si $modulesList est spécifié, alors on ne recherche que le sous ensemble de celui-ci
     */
    {
        $modules = Array (  );
        
        if ( $modulesList === Array ( ) )
        {
            global $SECURE_MODULE;
            foreach( array_keys($SECURE_MODULE) as $moduleName )
            {
                $module = $this->GetModule ( $moduleName );
                if ( $module->GetErrors ( ) == 0 && $module->HasFunctionnality ( $functionnality ) )
                {
                    array_push( $modules, $module );
                }
            }
        }
        else
        {
            foreach( $modulesList as $module )
            {
                if ( $module->GetErrors == 0 && $module->HasFunctionnality ( $functionnality ) )
                {
                    array_push( $modules, $module );
                }
            }
        }
        return $modules;
    }

    function GetModule ( $moduleName = '' )
    /**
     *  Instancie et renvoie le module demandé.
     */
    {
        if ( !isset( $this->loadedModules[$moduleName] ) )
        {
            if ( in_array( $moduleName, $this->availablesModules ) )
            {
                global $Member, $SECURE_MODULE;
                if ( $Member->Check_auth ( $SECURE_MODULE[$moduleName], 1 ) )
                {
                    if ( @include_once ( '../'.$moduleName.'/'.$moduleName.'_interface.class.php' ) )
                    {
                        $moduleConstructor = ucfirst ( $moduleName.'Interface' );
                        $Module = new $moduleConstructor ( );
                    }
                    else
                    { $Module = new ModuleInterface ( $moduleName, MODULE_NOT_YET_IMPLEMENTED ); }
                }
                else
                { $Module = new ModuleInterface ( $moduleName, ACCES_DENIED ); }
            }
            else
            { $Module = new ModuleInterface ( $moduleName, MODULE_NOT_AVAILABLE ); }
            $this->loadedModules[$moduleName] = $Module;
        }
        return $this->loadedModules[$moduleName];
    }

    //---------------------------------------------------------- Constructeurs
    function Modules (  )
    /**
     *  Constructeur de la classe Modules
     */
    {
        global $SECURE_MODULE;
        
        $this->loadedModules = Array (  );
        $this->availablesModules = array_keys ( $SECURE_MODULE );
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