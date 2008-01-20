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

define('MODULE_NOT_AVAILABLE', 1);
define('ACCES_DENIED', 2);
define('MODULE_NOT_YET_IMPLEMENTED', 4);
define('FUNCTIONNALITIE_NOT_YET_IMPLEMENTED', 8);

class Module
{
    //----------------------------------------------------------------- PUBLIC
    //----------------------------------------------------- Méthodes publiques
    function GetInfo (  )
    /**
     *  Renvoie le nom du module, les informations trouvée dans le config.ini
     *  du module ainsi que les fonctionnalités dont dispose le module.
     */
    {
        return Array(   'name' => $this->name,
                        'infos' => $this->infos,
                        'functionnalities' => $this->functionnalities,
                    );
    }
    
    function Functionnalitie ( $functionnalitie, $args = null )
    /**
     *  Teste l'existance de la fonctionnalité et l'appelle le cas échéant.
     *  Si elle n'est pas disponible, le flag
     *  FUNCTIONNALITIE_NOT_YET_IMPLEMENTED de la variable errors est
     *  alors positionné.
     */
    {
        $this->clearFunctionnalitieError ( );
        if ( $this->hasFunctionnalitie( $functionnalitie ) )
        { return $this->$functionnalitie ( $args ); }
        else
        { $this->setError ( FUNCTIONNALITIE_NOT_YET_IMPLEMENTED ); }
    }
    
    function HasFunctionnalitie ( $functionnalitie )
    /**
     *  Teste que la fonctionnalité est bien implémentée
     */
    {
        if ( array_key_exists($functionnalitie, $this->functionnalities) )
        { return (!empty( $this->functionnalities[$functionnalitie] ) && $this->functionnalities[$functionnalitie] != 'false' ? true : false); }
        else
        { return false; }
    }
    
    function GetErrors (  )
    /**
     *  Renvoie un integer contenant des bits d'erreurs.
     */
    {
        return $this->errors;
    }
    
    //---------------------------------------------------------- Constructeurs
    function Module ( $moduleName = 'empty-module', $error = 0  )
    /**
     * Constructeur de la classe Module
     */
    {
        global $CONFIG;
        
        $this->name = $moduleName;
        
        if ($error == 0)
        {   // récupération des infos sur le module à partir du fichier module.ini
            $this->infos = parse_ini_file('../'.$this->name.'/lang/'.$CONFIG['lang'].'/config.ini');
            $this->functionnalities = parse_ini_file('../'.$this->name.'/functionnalities.ini');
        }
        else
        {
            $this->infos = Array ( );
            $this->functionnalities = Array ( );
        }
        
        $this->errors = $error;
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
    function setError ( $error = 0 )
    /**
     *  Ajoute l'erreur rencontré aux erreurs déjà présentes.
     */
    {
        $this->errors = $this->errors|$error;
    }
    
    function clearFunctionnalitieError (  )
    /**
     *  Nettoie le bit d'erreur de la fonctionnalité, pour en tester une autre
     */
    {
        $this->errors = $this->errors &~  FUNCTIONNALITIE_NOT_YET_IMPLEMENTED;
    }
    
    //----------------------------------------------------- Attributs protégés
    var $name;
    var $infos;
    var $functionnalities;
    var $errors;
}

?>