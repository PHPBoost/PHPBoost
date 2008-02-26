<?php

/*##################################################
 *                              search.class.php
 *                            -------------------
 *   begin                : February 1, 2008
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

define('NB_LINES', 10);
define('CACHE_TIME', 30);
define('CACHE_TIMES_USED', 10);

class Search
{
    //----------------------------------------------------------------- PUBLIC
    //----------------------------------------------------- Méthodes publiques
    function InsertResults ( &$requests )
    /**
     *  Enregistre les résultats de la recherche dans la base des résultats
     *  si ils n'y sont pas déjà
     *  Nb requêtes : 1
     */
    {
        global $Sql;
		
		$nbReqSEARCH = 0;
        $reqSEARCH = '';
        
        $nbReqInsert = 0;
        $reqInsert = '';
        
        // Vérification de la présence des résultats dans le cache
        foreach ( $requests as $id_module => $request )
        {
            if ( !$this->IsInCache ( $id_module ) )
            {   // Si les résultats ne sont pas dans le cache.
                // Ajout des résultats dans le cache
                if ( $nbReqSEARCH > 0 )
                { $reqSEARCH .= ' UNION '; }
                else
                { $nbReqSEARCH++; }
                $reqSEARCH .= trim( $request, ' ;' );
            }
        }
        // Vérification de la présence de la requête
        if ( $reqSEARCH != "" )
        {
            $reqSEARCH = "INSERT ( ".$reqSEARCH." ) INTO ".PREFIX."search_results;";
            $Sql->Query_inject($reqSEARCH, __LINE__, __FILE__ );
        }
        
        // Au cas ou le insert select into ne soit pas portable.
//         $reqInsert = '';
//         $request = $Sql->Query_while( $reqSEARCH, __LINE__, __FILE__ );
//         while( $result = $Sql->Sql_fetch_assoc($request) )
//         {
//             $reqInsert .= " ('".$this->id_search[$id_module]."','".$id_module."','".$result['id_content']."',";
//             $reqInsert .= "'".$result['relevance']."','".$result['id_content']."'), ";
//             
//             // Exécution de 10 requêtes d'insertions
//             if ( $nbReqInsert == 10 )
//             {
//                 $Sql->Query_insert("INSERT INTO ".PREFIX."search_results VALUES ( ".$reqInsert." )", __LINE__, __FILE__);
//                 $reqInsert = '';
//                 $nbReqInsert = 0;
//             }
//             else { $nbReqInsert++; }
//         }
//         
//         // Exécution des derniéres requêtes d'insertions
//         if ( $nbReqInsert > 1 )
//         { $Sql->Query_inject("INSERT INTO ".PREFIX."search_results VALUES ( ".$reqInsert." )", __LINE__, __FILE__); }
    }
    
    function GetResults ( &$results, &$id_modules, $offset = 0, $nbLines = NB_LINES)
    /**
     *  Renvoie le nombre de résultats de la recherche
     *  et mets les résultats dans le tableau &results
     *  Nb requêtes : 1, 2 si le SGBD ne supporte pas 'sql->Sql_num_rows'
     */
    {
        global $Sql;

        $results = Array ( );
        $numModules = 0;
        $modulesConditions = '';
        
        // Construction des conditions de recherche
        foreach ( $id_modules as $id_module )
        {
            // Teste l'existence de la recherche dans la base sinon signale l'erreur
            if ( in_array($id_module, array_keys($this->id_search)) )
            {
                // Conditions de la recherche
                if ( $numModules > 0 )
                { $modulesConditions .= " OR"; }
                $modulesConditions .= " ( id_search='".$this->id_search[$id_module]."' ";
                $modulesConditions .= " AND id_module='".$id_module."' ) ";
                $numModules++;
            }
        }
        
        // Récupération des $nbLines résultats é partir de l'$offset
        $reqResults  = "SELECT id_module, id_module_content, relevance, link FROM ".PREFIX."search_results ";
        if ( $modulesConditions != "" )
            $reqResults .= " WHERE ".$modulesConditions;
        $reqResults .= $modulesConditions." ORDER BY relevance DESC ".$Sql->Sql_limit($offset, $nbLines);
        
        // Exécution de la requête
        $request = $Sql->Query_while( $reqResults, __LINE__, __FILE__ );
        while( $result = $Sql->Sql_fetch_assoc($request) )
        {   // Ajout des résultats
            array_push($results, $result);
        }
        // Récupération du nombre de résultats correspondant é la recherche
        $reqNbResults  = "SELECT COUNT(*) ".PREFIX."search_results ".$modulesConditions;
        $nbResults = $Sql->Sql_num_rows( $request, $reqNbResults );
        
        //On libére la mémoire
        $Sql->Close($request);
        
        return $nbResults;
    }
    
    function ModulesInCache ( )
    /**
     *  Renvoie la liste des modules présent dans le cache
     *  Nb requêtes : 0
     */
    {
        return array_keys ( $this->id_search );
    }
    
    function IsInCache ( $id_module )
    /**
     *  Renvoie true si les résultats du module sont dans le cache
     *  Nb requêtes : 0
     */
    {
        return in_array ( $id_module, array_keys ( $this->id_search ) );
    }
    
    //---------------------------------------------------------- Constructeurs
    
    function Search ( $search = '', $modules = Array ( ), $options = '' )
    /**
     *  Constructeur de la classe Search
     *  Nb requêtes : 4 + k / 10
     *  avec k nombre de module n'ayant pas de cache de recherche
     */
    {
        global $Sql, $Member;
		
        $this->errors = 0;
        $this->search = $search;
        $this->modules = $modules;
        $this->id_search = Array ( );

        $this->id_user = $Member->Get_attribute('user_id');
        $this->modulesConditions = $this->getModulesConditions($modules);

        // Délestage
        $reqDelete  = "DELETE FROM ".PREFIX."search_index WHERE ";
        $reqDelete .= "last_search_use < '".(time() - (CACHE_TIME * 60))."' OR times_used > '".CACHE_TIMES_USED."' ";
        
        // Vérifications des résultats dans le cache.
        $reqCache  = "SELECT id_search, id_module FROM ".PREFIX."search_index WHERE ";
        $reqCache .= "search='".$search."' AND id_user='".$this->id_user."' AND ".$this->modulesConditions;
        
        $request = $Sql->Query_while( $reqCache, __LINE__, __FILE__ );
        while( $row = $Sql->Sql_fetch_assoc($request) )
        {   // Ajout des résultats s'ils font partie de la liste des modules à traiter
            $this->id_search[$row[1]] = $row[0];
        }
        $Sql->Close($request);
        
        // Mise à jours des résultats du cache
        if ( count ( $this->id_search ) > 0 )
        {
            $reqUpdate  = "UPDATE ".PREFIX."search_index SET times_used=(times_used + 1), last_search_use='".$time()."' WHERE ";
            $reqUpdate .= "id_search IN ( ".implode($this->id_search)." ) AND user_id='".$this->id_user."';";
            $Sql->Query_insert($reqDelete.$reqUpdate, __LINE__, __FILE__);
        }
        
        $nbReqInsert = 0;
        $reqInsert = '';
        // Pour chaque module n'étant pas dans le cache
        foreach ( $modules as $id_module )
        {
            if ( !$this->IsInCache ( $id_module ) )
            {
                $reqInsert .= "('','".$id_module."','".$this->id_user."','".$search."','".$options."','".time()."', '0'),";
                
                // Exécution de 10 requêtes d'insertions
                if ( $nbReqInsert == 10 )
                {
                    $reqInsert = "INSERT INTO ".PREFIX."search_index VALUES ".$reqInsert."";
                    $Sql->Query_insert($reqInsert, __LINE__, __FILE__);
                    $reqInsert = '';
                    $nbReqInsert = 0;
                }
                else { $nbReqInsert++; }
            }
        }
        
        // Exécution des derniéres requêtes d'insertions
        if ( $nbReqInsert > 1 )
        { $Sql->Query_inject("INSERT INTO ".PREFIX."search_index VALUES ".substr($reqInsert, 0, strlen($reqInsert) - 1)."", __LINE__, __FILE__); }
        
        // Récupération des résultats et de leurs id dans le cache.
        
        // Pourquoi faire éé plutét que de récupérer id_search pour chaque
        // insertion dans l'index du cache.
        // parce que cela donne au total pour le contructeur une complexité
        // en requête de :
        // 1 (delete) + 1 (recup id) + 1 (update timestamp) + k / 10 (nb non dans le cache) + 1 (recup id) = 4 + k/10
        // au lieu de :
        // 1 (delete) + 1 (recup id) + 1 (update timestamp) + k (nb non dans le cache) = 3 + k
        // cela permet donc de grouper les insertions dans l'index du cache.
        
        $reqCache  = "SELECT id_search, id_module FROM ".PREFIX."search_index ";
        $reqCache .= "WHERE search='".$search."' AND ".$this->modulesConditions;
        
        $request = $Sql->Query_while( $reqCache, __LINE__, __FILE__ );
        while( $row = $Sql->Sql_fetch_assoc($request) )
        {   // Ajout des résultats s'il fait partie de la liste des modules é traiter
            $this->id_search[$row[1]] = $row[0];
        }
        $Sql->Close($request);
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
     *  Bref, utilisation et vos risques et périls !!!
     *  
     */
    
    //----------------------------------------------------- Méthodes protégées
    function getModulesConditions ( &$modules )
    /**
     *  Génère les conditions de la clause WHERE pour limiter les requêtes
     *  aux seuls modules avec les bonnes options de recherches concernés.
     */
    {
        $nbModules = count( $modules );
        
        $modulesConditions = '';
        
        if ( $nbModules > 0 )
        {
            $modulesConditions .= " ( ";
            $i = 0;
            foreach ( $modules as $id_module => $options )
            {
                $modulesConditions .= "( id_module='".$id_module."'";
                $modulesConditions .= " AND options='".$options."'";
                $modulesConditions .= " )";
                
                if ( $i < ( $nbModules - 1 ) )
                { $modulesConditions .= " OR " ; }
                else
                { $modulesConditions .= " ) "; }
                $i++;
            }
        }
        
        return $modulesConditions;
    }
    
    //----------------------------------------------------- Attributs protégés
    var $id_search;
    var $search;
    var $modules;
    var $modulesConditions;
    var $id_user;
    var $errors;

}

?>

