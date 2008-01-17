<?php

/*##################################################
 *                              module.class.php
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

define('NOT_YET_IMPLEMENTED',-1);

class Module
{
    //----------------------------------------------------------------- PUBLIC
    //----------------------------------------------------- Méthodes publiques
    function Search (  )
    {
        return NOT_YET_IMPLEMENTED;
    }
    
    function LatestAdds (  )
    {
        return NOT_YET_IMPLEMENTED;
    }
    
    function LatestModifications (  )
    {
        return NOT_YET_IMPLEMENTED;
    }
    
    function MadeBy (  )
    {
        return NOT_YET_IMPLEMENTED;
    }
    
    
    //---------------------------------------------------------- Constructeurs
    function Module ( $moduleName = 'NaS'  )
    // Constructeur de la classe Module
    {
        $this->name = $moduleName;
    }
    
    //------------------------------------------------------------------ PRIVE
    
    //----------------------------------------------------- Méthodes protégées
    
    
    //----------------------------------------------------- Attributs protégés
    var $name;
}


function test()
{
    $Mod = new Module('wiki');
    $Mod->Search();
    $Mod->LatestAdds();
    $Mod->LatestModifications();
    $Mod->MadeBy();
}

?>