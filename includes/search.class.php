<?php

/*##################################################
 *                              search.class.php
 *                            -------------------
 *   begin                : February 1, 2008
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

defien('NB_LINES', 10);
define('MODULE_DOES_NOT_EXISTS', 1);

class Search
{
    //----------------------------------------------------------------- PUBLIC
    //----------------------------------------------------- Méthodes publiques
    function Execute ( $search, $args )
    /**
            *  Exécute la recherche demandée
            */
    {
		$this->resetError(MODULE_DOES_NOT_EXISTS);
		// Vérification de la présence des résultats dans le cache
		// 	si oui
		//		update du timestamp
		//	sinon
        // 		Recherche et stockage dans la table des résultats
    }
    
    function GetResults ( $id_modules = Array (), $offset = 0; $nbLines = NB_LINES)
    /**
            *  Teste que la fonctionnalité est bien implémentée
            */
    {
		$moduleConditions = '';
		
		$nbModules = count($id_modules);
		if ( $nbModules > 0 )
		{ $moduleConditions .= ' WHERE ';
        
		for ( $i = 0; $i < $nbModules; $i++ )
        {
            $moduleConditions .= ' id_module='.$id_modules[$i].' ';
			
			if ( $i < ($nbModules - 1) )
			{ $moduleConditions .= 'AND '; }
        }
		
		$results = Array ( );
		
		$req = 'SELECT id_module, id_content FROM '.PREFIX.'search_results ';
		$req .= $this->sql->sql_limit(0, 10);
		
		$request = $this->sql->query_while( $req , __LINE__, __FILE__ );
		while( $result = $this->sql->sql_fetch_assoc($request) )
		{
			array_push($results, $result)
		}
		$this->sql->close($request); //On libère la mémoire  
		
		return Array ( $nbResults, $results);
    }
    
    
    function GetErrors (  )
    /**
            *  Renvoie un integer contenant des bits d'erreurs.
            */
    {
        return $this->errors;
    }
    
    //---------------------------------------------------------- Constructeurs
    
    function Search ( $sql )
    /**
            *  Constructeur de la classe Search
            */
    {
		$this->errors = 0;
        $this->sql = $sql;
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
    
    function resetError ( $error )
    /**
             *  Nettoie le bit d'erreur de l'erreur correspondante
             */
    {
        $this->errors = $this->errors &~  $error;
    }
    
    //----------------------------------------------------- Attributs protégés
    var $sql;
    var $errors;
}

?>