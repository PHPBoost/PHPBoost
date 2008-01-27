<?php

/*##################################################
 *                              module.class.php
 *                            -------------------
 *   begin                : January 15, 2008
 *   copyright            : (C) 2008 Rouchon Loic
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

define('MODULE_NOT_AVAILABLE', 1);
define('ACCES_DENIED', 2);
define('MODULE_NOT_YET_IMPLEMENTED', 4);
define('FUNCTIONNALITIE_NOT_IMPLEMENTED', 8);

class ModuleInterface
{
    //----------------------------------------------------------------- PUBLIC
    //----------------------------------------------------- Méthodes publiques
    function GetInfo (  )
    /**
     *  Renvoie le nom du module, les informations trouvées dans le config.ini
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
     *  FUNCTIONNALITIE_NOT_IMPLEMENTED de la variable errors est
     *  alors positionné.
     */
    {
        $this->clearFunctionnalitieError ( );
        if ( $this->hasFunctionnalitie( $functionnalitie ) )
        { return $this->$functionnalitie ( $args ); }
        else
        { $this->setError ( FUNCTIONNALITIE_NOT_IMPLEMENTED ); }
    }
    
    function HasFunctionnalitie ( $functionnalitie )
    /**
     *  Teste que la fonctionnalité est bien implémentée
     */
    {
        return in_array($functionnalitie, $this->functionnalities);
    }
    
    function GetErrors (  )
    /**
     *  Renvoie un integer contenant des bits d'erreurs.
     */
    {
        return $this->errors;
    }
    
    //---------------------------------------------------------- Constructeurs
    function ModuleInterface ( $moduleName = '', $error = 0  )
    /**
     * Constructeur de la classe Module
     */
    {
        global $CONFIG;
        
        $this->name = $moduleName;
        
        $this->infos = Array ( );
        $this->functionnalities = Array ( );
        if ($error == 0)
        {
           // récupération des infos sur le module é partir du fichier module.ini
           $iniFile = '../'.$this->name.'/lang/'.$CONFIG['lang'].'/config.ini';
           if ( file_exists($iniFile) )
           { $this->infos = @parse_ini_file($iniFile); }
            
            // Récupération des méthodes du module
            $methods = get_class_methods( ucfirst($moduleName).'Interface' );
            // Méthode de la classe générique ModuleInterface
            $moduleMethods = get_class_methods('ModuleInterface');
            
            // Enlévement de toutes les méthodes auxquelles le developpeur n'a pas accés
            for ($i = 0; $i < count($methods); $i++)
            {
                // Si la méthode est une méthode générique de la classe ModuleInterface
                //  Ou si c'est le constructeur de l'interface de son module
                //  Ou si c'est une méthode privé de l'interface de son module,
                // Alors ce n'est pas une fonctionnalité.
                if ( in_array($methods[$i], $moduleMethods) or ( $methods[$i] == ucfirst($moduleName).'Interface' ) )
                { array_splice($methods, $i); }
            }
            $this->functionnalities = $methods;
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
     *  Bref, utilisation é vos risques et périls !!!
     *  
     */
    //----------------------------------------------------- Méthodes protégées
    function setError ( $error = 0 )
    /**
     *  Ajoute l'erreur rencontré aux erreurs déjé présentes.
     */
    {
        $this->errors |= $error;
    }
    
    function clearFunctionnalitieError (  )
    /**
     *  Nettoie le bit d'erreur de la fonctionnalité, pour en tester une autre
     */
    {
        $this->errors = $this->errors &~  FUNCTIONNALITIE_NOT_IMPLEMENTED;
    }
    
    //----------------------------------------------------- Attributs protégés
    var $name;
    var $infos;
    var $functionnalities;
    var $errors;
}

?>