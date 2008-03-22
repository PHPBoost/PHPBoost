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
if ( @include_once('../search/search_cache.php') )
{
    $SEARCH_CONFIG = Load_search_cache();
    define('CACHE_TIME', $SEARCH_CONFIG['cache_time']);
    define('CACHE_TIMES_USED', $SEARCH_CONFIG['max_use']);
}
else
{
    define('CACHE_TIME', 30);
    define('CACHE_TIMES_USED', 5);
}

class Search
{
    //----------------------------------------------------------------- PUBLIC
    //---------------------------------------------------------- Constructeurs
    
    function Search($search = '', $modules = array())
    /**
     *  Constructeur de la classe Search
     *  Nb requêtes : 6 + k / 10
     *  avec k nombre de module n'ayant pas de cache de recherche
     */
    {
        global $Sql, $Member;
        
        $this->errors = 0;
        $this->search = $search;
        $this->modules = $modules;
        $this->id_search = array();
        $this->cache = array();
        
        $this->id_user = $Member->Get_attribute('user_id');
        $this->modulesConditions = $this->getModulesConditions($this->modules);
        
        // Si on demande une recherche directe par id, on ne calcule pas de résultats
        if( $search != '' )
        {
            // Suppression des vieux résultats du cache
            
            // Liste des résultats à supprimer
            $reqOldIndex = "SELECT `id_search` FROM ".PREFIX."search_index
                            WHERE  `last_search_use` <= '".(time() - (CACHE_TIME * 60))."'
                                OR `times_used` >= '".CACHE_TIMES_USED."'";
            
            $nbIdsToDelete = 0;
            $idsToDelete = '';
            $request = $Sql->Query_while($reqOldIndex, __LINE__, __FILE__);
            while($row = $Sql->Sql_fetch_assoc($request))
            {
                if ( $nbIdsToDelete > 0 )
                    $idsToDelete .= ',';
                $idsToDelete .= "'".$row['id_search']."'";
                $nbIdsToDelete++;
            }
            $Sql->Close($request);
            
            // Si il y a des résultats à supprimer, on les supprime
            if ( $nbIdsToDelete > 0 )
            {
                $reqDeleteIdx = "DELETE FROM ".PREFIX."search_index WHERE `id_search` IN (".$idsToDelete.")";
                $reqDeleteRst = "DELETE FROM ".PREFIX."search_results WHERE `id_search` IN (".$idsToDelete.")";
                
                $Sql->Query_inject($reqDeleteIdx, __LINE__, __FILE__);
                $Sql->Query_inject($reqDeleteRst, __LINE__, __FILE__);
            }
            
            // Vérifications des résultats dans le cache.
            $reqCache  = "SELECT `id_search`, `module` FROM ".PREFIX."search_index WHERE ";
            $reqCache .= "`search`='".$search."' AND `id_user`='".$this->id_user."'";
            if( $this->modulesConditions != '' )
                $reqCache .= " AND ".$this->modulesConditions;
            
            $request = $Sql->Query_while($reqCache, __LINE__, __FILE__);
            while($row = $Sql->Sql_fetch_assoc($request))
            {   // Récupération du cache
                array_push($this->cache, $row['module']);
                $this->id_search[$row['module']] = $row['id_search'];
            }
            $Sql->Close($request);
            
            // Mise à jours des résultats du cache
            if( count($this->id_search) > 0 )
            {
                $reqUpdate  = "UPDATE ".PREFIX."search_index SET times_used=times_used+1, `last_search_use`='".time()."' WHERE ";
                $reqUpdate .= "`id_search` IN (".implode(',', $this->id_search).");";
                $Sql->Query_inject($reqUpdate, __LINE__, __FILE__);
            }
            
            // Si tous les modules ne sont pas en cache
            if ( count($modules) > count($this->cache) )
            {
                $nbReqInsert = 0;
                $reqInsert = '';
                // Pour chaque module n'étant pas dans le cache
                foreach($modules as $moduleName => $options)
                {
                    if( !$this->IsInCache($moduleName) )
                    {
                        $reqInsert .= "('".$this->id_user."','".$moduleName."','".$search."','".$options."','".time()."', '0'),";
                        // Exécution de 10 requêtes d'insertions
                        if( $nbReqInsert == 10 )
                        {
                            $reqInsert = "INSERT INTO ".PREFIX."search_index (`id_user`, `module`, `search`, `options`, `last_search_use`, `times_used`) VALUES ".$reqInsert."";
                            $Sql->Query_insert($reqInsert, __LINE__, __FILE__);
                            $reqInsert = '';
                            $nbReqInsert = 0;
                        }
                        else { $nbReqInsert++; }
                    }
                }
                
                // Exécution des derniéres requêtes d'insertions
                if( $nbReqInsert > 0 )
                    $Sql->Query_inject("INSERT INTO ".PREFIX."search_index (`id_user`, `module`, `search`, `options`, `last_search_use`, `times_used`) VALUES ".substr($reqInsert, 0, strlen($reqInsert) - 1)."", __LINE__, __FILE__);
                
                // Récupération des résultats et de leurs id dans le cache.
                
                // Pourquoi faire çà plutôt que de récupérer id_search pour chaque
                // insertion dans l'index du cache.
                // parce que cela donne au total pour le contructeur une complexité
                // en requête de :
                // 1 (delete) + 1 (recup id) + 1 (update timestamp) + k / 10 (nb non dans le cache) + 1 (recup id) = 4 + k/10
                // au lieu de :
                // 1 (delete) + 1 (recup id) + 1 (update timestamp) + k (nb non dans le cache) = 3 + k
                // cela permet donc de grouper les insertions dans l'index du cache.
                
                // Vérifications des résultats dans le cache.
                $reqCache  = "SELECT id_search, module FROM ".PREFIX."search_index WHERE ";
                $reqCache .= "search='".$search."' AND id_user='".$this->id_user."'";
                if( $this->modulesConditions != '' )
                    $reqCache .= " AND ".$this->modulesConditions;
                
                $request = $Sql->Query_while( $reqCache, __LINE__, __FILE__ );
                while($row = $Sql->Sql_fetch_assoc($request))
                {   // Ajout des résultats s'ils font partie de la liste des modules à traiter
                    $this->id_search[$row['module']] = $row['id_search'];
                }
                $Sql->Close($request);
            }
        }
    }
    
    //----------------------------------------------------- Méthodes publiques
    function IsSearchIdInCache($idSearch)
    /**
     *  Renvoie <true> si la recherche est en cache et <false> sinon.
     *  Nb requêtes : 2
     */
    {
        global $Sql;
        $id = $Sql->Query("SELECT COUNT(*) FROM ".PREFIX."search_index WHERE id_search = ".$idSearch, __LINE__, __FILE__);
        if( $id == 1 )
        {
            // la recherche est déjà, en cache, on la met à jour.
            $reqUpdate  = "UPDATE ".PREFIX."search_index SET times_used=times_used+1, `last_search_use`='".time()."' WHERE ";
            $reqUpdate .= "`id_search` = ".$idSearch;
            $Sql->Query_inject($reqUpdate, __LINE__, __FILE__);
            return true;
        }
        else return false;
    }
    
    function GetResultsById( &$results, $idSearch = 0, $offset = 0, $nbLines = 0 )
    /**
     *  Renvoie les résultats de la recherche d'id <idSearch>
     *  Nb requêtes : 5 + k / 10
     *  avec k nombre de module n'ayant pas de cache de recherche
     */
    {
        global $Sql;
        $results = array();
        
        // Récupération des $nbLines résultats à partir de l'$offset
        $reqResults = "SELECT module, id_content, title, relevance, link
                        FROM ".PREFIX."search_index idx, ".PREFIX."search_results rst
                        WHERE idx.id_search = ".$idSearch." AND rst.id_search = ".$idSearch."
                        ORDER BY relevance DESC ";
        if ( $nbLines > 0 )
            $reqResults .= $Sql->Sql_limit($offset, $nbLines);
        
        // Exécution de la requête
        $request = $Sql->Query_while( $reqResults, __LINE__, __FILE__ );
        while( $result = $Sql->Sql_fetch_assoc($request) )
        {   // Ajout des résultats
            array_push($results, $result);
        }
        // Récupération du nombre de résultats correspondant à la recherche
        $reqNbResults  = "SELECT COUNT(*) ".PREFIX."search_results WHERE id_search = ".$idSearch;
        $nbResults = $Sql->Sql_num_rows( $request, $reqNbResults );
        
        //On libére la mémoire
        $Sql->Close($request);
        
        return $nbResults;
    }
    
    function InsertResults(&$requests)
    /**
     *  Enregistre les résultats de la recherche dans la base des résultats
     *  si ils n'y sont pas déjà
     *  Nb requêtes : 1
     */
    {
        global $Sql;
		
		$nbReqSEARCH = 0;
        $reqSEARCH = "";
        
        // Vérification de la présence des résultats dans le cache
        foreach($requests as $moduleName => $request)
        {
            if( !$this->IsInCache($moduleName) )
            {   // Si les résultats ne sont pas dans le cache.
                // Ajout des résultats dans le cache
                if( $nbReqSEARCH > 0 )
                    $reqSEARCH .= " UNION ";
                
                $reqSEARCH .= "(".trim( $request, ' ;' ).")";
                $nbReqSEARCH++;
            }
        }
        
        // Dans le cas ou il y a des recherches à faire
        if( $nbReqSEARCH > 0 )
        {
            $nbReqInsert = 0;
            $reqInsert = '';
            $request = $Sql->Query_while( $reqSEARCH, __LINE__, __FILE__ );
            while( $row = $Sql->Sql_fetch_assoc($request) )
            {
                if( $nbReqInsert > 0 )
                    $reqInsert .= ',';
                $reqInsert .= " ('".$row['id_search']."','".$row['id_content']."','".addslashes($row['title'])."',";
                $reqInsert .= "'".$row['relevance']."','".$row['link']."')";
                
                // Exécution de 10 requêtes d'insertions
                if( $nbReqInsert == 10 )
                {
                    $Sql->Query_inject("INSERT INTO ".PREFIX."search_results VALUES ".$reqInsert, __LINE__, __FILE__);
                    $reqInsert = '';
                    $nbReqInsert = 0;
                }
                else { $nbReqInsert++; }
            }
            
            // Exécution des derniéres requêtes d'insertions
            if( $nbReqInsert > 0 )
            {
                $Sql->Query_inject("INSERT INTO ".PREFIX."search_results VALUES ".$reqInsert, __LINE__, __FILE__);
            }
        }
    }
    
    function GetResults(&$results, &$moduleNames, $nbLines = 0, $offset = 0 )
    /**
     *  Renvoie le nombre de résultats de la recherche
     *  et mets les résultats dans le tableau &results
     *  Nb requêtes : 1, 2 si le SGBD ne supporte pas 'sql->Sql_num_rows'
     */
    {
        global $Sql;

        $results = array( );
        $numModules = 0;
        $modulesConditions = '';
        
        // Construction des conditions de recherche
        foreach($moduleNames as $moduleName)
        {
            // Teste l'existence de la recherche dans la base sinon signale l'erreur
            if( in_array($moduleName, array_keys($this->id_search)) )
            {
                // Conditions de la recherche
                if( $numModules > 0 )
                    $modulesConditions .= ", ";
                $modulesConditions .= $this->id_search[$moduleName];
                $numModules++;
            }
        }
        
        // Récupération des $nbLines résultats à partir de l'$offset
        $reqResults  = "SELECT module, id_content, title, relevance, link
                        FROM ".PREFIX."search_index idx, ".PREFIX."search_results rst
                        WHERE (idx.id_search = rst.id_search) ";
        if( $modulesConditions != '' )
            $reqResults .= " AND rst.id_search  IN (".$modulesConditions.")";
        $reqResults .= " ORDER BY relevance DESC ";
        if ( $nbLines > 0 )
            $reqResults .= $Sql->Sql_limit($offset, $nbLines);
        
        // Exécution de la requête
        $request = $Sql->Query_while($reqResults, __LINE__, __FILE__);
        while( $result = $Sql->Sql_fetch_assoc($request) )
        {   // Ajout des résultats
            array_push($results, $result);
        }
        
        // Récupération du nombre de résultats correspondant à la recherche
        $reqNbResults  = "SELECT COUNT(*) FROM ".PREFIX."search_results WHERE id_search IN ( ".$modulesConditions." )";
        if ( $modulesConditions > 0 )
            $nbResults = $Sql->Query($reqNbResults, __LINE__, __FILE__  );
        else
            $nbResults = 0;
        
        //On libére la mémoire
        $Sql->Close($request);
        
        return $nbResults;
    }
    
    function ModulesInCache()
    /**
     *  Renvoie la liste des modules présent dans le cache
     *  Nb requêtes : 0
     */
    {
        return array_keys($this->id_search);
    }
    
    function IsInCache($moduleName)
    /**
     *  Renvoie true si les résultats du module sont dans le cache
     *  Nb requêtes : 0
     */
    {
        return in_array($moduleName, $this->cache);
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
    function getModulesConditions(&$modules)
    /**
     *  Génère les conditions de la clause WHERE pour limiter les requêtes
     *  aux seuls modules avec les bonnes options de recherches concernés.
     */
    {
        $nbModules = count($modules);
        $modulesConditions = '';
        if( $nbModules > 0 )
        {
            $modulesConditions .= " ( ";
            $i = 0;
            foreach($modules as $moduleName => $options)
            {
                $modulesConditions .= "( module='".$moduleName."'";
                $modulesConditions .= " AND options='".$options."'";
                $modulesConditions .= " )";
                
                if( $i < ($nbModules - 1) )
                    $modulesConditions .= " OR ";
                else
                    $modulesConditions .= " ) ";
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

