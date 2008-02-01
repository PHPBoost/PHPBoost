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

define('NB_LINES', 10);
define('CACHE_TIME', 30);
define('CONNEXION_NOT_OPEN', 1);
define('SEARCH_DEPRECATED', 2);
define('MODULE_DOES_NOT_EXISTS', 4);

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
		
		// Nettoyage de la bases des recherche
		$reqUpdate  = "DELETE FROM ".PREFIX."search_index ";
		$reqUpdate .= "WHERE id_search=".$this->id_search." AND last_search_use < ".($time() - (CACHE_TIME*60));
		$sql->query_inject($reqUpdate, __LINE__, __FILE__);
		
		// Vérification de la présence des résultats dans le cache
		// 	si oui
		//		utilise le id_search trouve pour les resultats
		//	sinon
        // 		Recherche et stockage dans la table des résultats et stockage id_search
		
		// Update du timestamp de la recherche
		$this->updateTimeStamp(); 
    }
    
    function GetResults ( $id_modules = Array (), $offset = 0, $nbLines = NB_LINES)
    /**
            *  Renvoie les résultats de la recherche
            */
    {
		
		// Update du timestamp de la recherche
		$this->updateTimeStamp(); 
	
		// Teste l'existence de la recherche dans la base sinon stoppe
		$reqNbSearch  = "SELECT COUNT(*) ".PREFIX."search_index WHERE id_search=".$this->id_search;
		if ( $sql->query( $reqNbSearch, __LINE__, __FILE__ ) > 0 )
		{
			$this->setError ( SEARCH_DEPRECATED );
			return Array (0, Array ( ) );
		}
		
	 
		$results = Array ( );
		$moduleConditions = "";
		
		// Choix de la recherche
		$moduleConditions .= " WHERE id_search=".$this->id_search;
		
		// Choix du/des modules sur lesquels rechercher
		$nbModules = count($id_modules);
		if ( $nbModules > 0 )
		{ $moduleConditions .= " AND (" ; }
        
		for ( $i = 0; $i < $nbModules; $i++ )
        {
            $moduleConditions .= "id_module=".$id_modules[$i];
			if ( $i < ( $nbModules - 1 ) )
			{ $moduleConditions .= " OR " ; }
        }
		$moduleConditions .= ") ";
		
		// Récupération du nombre de résultats correspondant à la recherche
		$reqNbResults  = "SELECT COUNT(*) ".PREFIX."search_results".$moduleConditions.
		$nbResults = $sql->query( $reqNbResults, __LINE__, __FILE__ );
		
		// Récupération des $nbLines résultats à partir de l'$offset
		$reqResults  = "SELECT id_module, id_content, pertinence, link FROM ".PREFIX."search_results";
		$reqResults .= $moduleConditions." ".$this->sql->sql_limit(0, 10);
		
		$request = $this->sql->query_while( $reqResults, __LINE__, __FILE__ );
		while( $result = $this->sql->sql_fetch_assoc($request) )
		{
			array_push($results, $result)
		}
		$this->sql->close($request); //On libère la mémoire  
		
		return Array ( $nbResults, $results );
    }
    
    
    function GetErrors (  )
    /**
            *  Renvoie un integer contenant des bits d'erreurs.
            */
    {
        return $this->errors;
    }
    
	function isDeprecated (  )
    /**
            *  Renvoie un integer contenant des bits d'erreurs.
            */
    {
        return (($this->errors && SEARCH_DEPRECATED) == SEARCH_DEPRECATED);
    }
	
    //---------------------------------------------------------- Constructeurs
    
    function Search ( $sql )
    /**
            *  Constructeur de la classe Search
            */
    {
		$this->errors = 0;
		$this->id_search = 0;
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
	function updateTimeStamp ( )
	{
		$reqUpdate  = "UPDATE ".PREFIX."search_index SET last_search_use = '".$time()."' ";
		$reqUpdate .= "WHERE id_search=".$this->id_search;
		$sql->query_inject($reqUpdate, __LINE__, __FILE__);
	}
	
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
    var $id_search;
    var $sql;
    var $errors;
}

?>