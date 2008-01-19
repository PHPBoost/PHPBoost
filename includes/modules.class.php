<?php

/*##################################################
 *                              modules.class.php
 *                            -------------------
 *   begin                : January 15, 2008
 *   copyright          : (C) 2008 Rouchon Loïc
 *   email                : xhorn37@yahoo.fr
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

define('LIST_FUNCTIONNALITIES','Search,LatestAdds,LatestModifications,MadeBy');


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
    function Search ( $modules )
    /**
     *  Effectue une recherche dans le(s) module(s) sélectionné(s)
     */
    {
        $this->verifyModulesFunctionnalities ( 'Search', array_keys($modules) );
        $modulesNames = array_keys($modules);
        $results = Array();
        for ($i = 0; $i < count($modules); $i++)
        {
            // Instanciation de l'objet $module
            $module = $this->GetModule($modulesNames[$i]);
            $results[$modulesNames[$i]] = $module->Search($modules[$i]);
        }
        return $results;
    }

    function GetAvailablesModulesList ( $functionnalitie )
    {
        return $this->functionnalities[$functionnalitie];
    }

    function GetModule($moduleName)
    {
        if ( ($module = object()) !== false )
        { return $module; }
        else
        { return false; }
    }

    //---------------------------------------------------------- Constructeurs
    function Modules (  )
    // Constructeur de la classe Modules
    {
        //$listAvailablesModules = parse_ini_file('availablesModules.ini', TRUE);
        $listAvailablesModules = Array();
        foreach (explode(',',LIST_FUNCTIONNALITIES) as $functionnalitie)
        {
            $availablesModules = Array();
            foreach ($listAvailablesModules as $module)
            {
                if ($module[$functionnalitie])
                { array_push($availablesModules, $module); }
            }
            $this->functionnalities[$functionnalitie] = $availablesModules;
        }
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
    function verifyModulesFunctionnalities ( $functionnalitie, $listMods )
    {
        foreach ($listMods as $mod)
        {
            if ( !in_array($mod, $this->functionnalities[$functionnalitie]) )
            { unset($modules[$mod]); }
        }
    }

    //----------------------------------------------------- Attributs protégés
    var $functionnalities = Array();
}


function test()
{
    $Mods = new Modules();
    $Mods->functionnalities['Search'] = Array('wiki', 'forum', 'news');
    $Mods->functionnalities['LatestAdds'] = Array('wiki', 'forum', 'news');
    $Mods->functionnalities['LatestModifications'] = Array('wiki');
    
    echo '<br /><pre>';
    print_r($Mods->functionnalities);
    print_r($Mods->GetAvailablesModulesList('Search'));
    echo '</pre>';
}

?>