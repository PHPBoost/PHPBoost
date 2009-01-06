<?php

/*##################################################
 *                              search.class.php
 *                            -------------------
 *   begin                : February 1, 2008
 *   copyright            : (C) 2008 Loïc Rouchon
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

global $CONFIG;

define('CACHE_TIME', $CONFIG['search_cache_time']);
define('CACHE_TIMES_USED', $CONFIG['search_max_use']);

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
        global $Sql, $User;
        
        $this->errors = 0;
        $this->search = md5($search); // Identifiant de la chaîne recherchée;
        $this->modules = $modules;
        $this->id_search = array();
        $this->cache = array();
        
        $this->id_user = $User->get_attribute('user_id');
        $this->modules_conditions = $this->_get_modules_conditions($this->modules);
                
        // Suppression des vieux résultats du cache
        // Ici 3 requêtes pour éviter un delete multi-table non portable ou 2 requête avec un NOT IN long en exécution
        // Liste des résultats à supprimer
        $reqOldIndex = "SELECT id_search FROM " . PREFIX . "search_index
                        WHERE  last_search_use <= '".(time() - (CACHE_TIME * 60))."'
                            OR times_used >= '".CACHE_TIMES_USED."'";
        
        $nbIdsToDelete = 0;
        $idsToDelete = '';
        $request = $Sql->query_while ($reqOldIndex, __LINE__, __FILE__);
        while ($row = $Sql->fetch_assoc($request))
        {
            if ( $nbIdsToDelete > 0 )
                $idsToDelete .= ',';
            $idsToDelete .= "'".$row['id_search']."'";
            $nbIdsToDelete++;
        }
        $Sql->query_close($request);
        
        // Si il y a des résultats à supprimer, on les supprime
        if ( $nbIdsToDelete > 0 )
        {
            $reqDeleteIdx = "DELETE FROM " . DB_TABLE_SEARCH_INDEX . " WHERE id_search IN (".$idsToDelete.")";
            $reqDeleteRst = "DELETE FROM " . DB_TABLE_SEARCH_RESULTS . " WHERE id_search IN (".$idsToDelete.")";
            
            $Sql->query_inject($reqDeleteIdx, __LINE__, __FILE__);
            $Sql->query_inject($reqDeleteRst, __LINE__, __FILE__);
        }
        
        // Si on demande une recherche directe par id, on ne calcule pas de résultats
        if ($this->search != '')
        {
            // Vérifications des résultats dans le cache.
            $reqCache  = "SELECT id_search, module FROM " . DB_TABLE_SEARCH_INDEX . " WHERE ";
            $reqCache .= "search='" . $this->search . "' AND id_user='" . $this->id_user . "'";
            if ($this->modules_conditions != '')
                $reqCache .= " AND " . $this->modules_conditions;
            
            $request = $Sql->query_while ($reqCache, __LINE__, __FILE__);
            while ($row = $Sql->fetch_assoc($request))
            {   // Récupération du cache
                array_push($this->cache, $row['module']);
                $this->id_search[$row['module']] = $row['id_search'];
            }
            $Sql->query_close($request);
            
            // Mise à jours des résultats du cache
            if (count($this->id_search) > 0)
            {
                $reqUpdate  = "UPDATE " . DB_TABLE_SEARCH_INDEX . " SET times_used=times_used+1, last_search_use='" . time() . "' WHERE ";
                $reqUpdate .= "id_search IN (" . implode(',', $this->id_search) . ");";
                $Sql->query_inject($reqUpdate, __LINE__, __FILE__);
            }
            
            // Si tous les modules ne sont pas en cache
            if ( count($modules) > count($this->cache) )
            {
                $nbReqInsert = 0;
                $reqInsert = '';
                // Pour chaque module n'étant pas dans le cache
                foreach ($modules as $module_name => $options)
                {
                    if (!$this->is_in_cache($module_name))
                    {
                        $reqInsert .= "('" . $this->id_user . "','" . $module_name . "','" . $this->search . "','" . md5(implode('|', $options)) . "','" . time() . "', '0'),";
                        // Exécution de 10 requêtes d'insertions
                        if ($nbReqInsert == 10)
                        {
                            $reqInsert = "INSERT INTO " . DB_TABLE_SEARCH_INDEX . " (id_user, module, search, options, last_search_use, times_used) VALUES " . $reqInsert . "";
                            $Sql->Query_insert($reqInsert, __LINE__, __FILE__);
                            $reqInsert = '';
                            $nbReqInsert = 0;
                        }
                        else { $nbReqInsert++; }
                    }
                }
                
                // Exécution des derniéres requêtes d'insertions
                if ($nbReqInsert > 0)
                    $Sql->query_inject("INSERT INTO " . DB_TABLE_SEARCH_INDEX . " (id_user, module, search, options, last_search_use, times_used) VALUES " . substr($reqInsert, 0, strlen($reqInsert) - 1) . "", __LINE__, __FILE__);
                
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
                $reqCache  = "SELECT id_search, module FROM " . DB_TABLE_SEARCH_INDEX . " WHERE ";
                $reqCache .= "search='" . $this->search . "' AND id_user='" . $this->id_user . "'";
                if ($this->modules_conditions != '')
                    $reqCache .= " AND " . $this->modules_conditions;
                
                $request = $Sql->query_while ($reqCache, __LINE__, __FILE__);
                while ($row = $Sql->fetch_assoc($request))
                {   // Ajout des résultats s'ils font partie de la liste des modules à traiter
                    $this->id_search[$row['module']] = $row['id_search'];
                }
                $Sql->query_close($request);
            }
        }
    }
    
    //----------------------------------------------------- Méthodes publiques
    function get_results_by_id(&$results, $id_search = 0, $nb_lines = 0, $offset = 0)
    /**
     *  Renvoie les résultats de la recherche d'id <idSearch>
     *  Nb requêtes : 2
     */
    {
        global $Sql;
        $results = array();
        
        // Récupération des $nb_lines résultats à partir de l'$offset
        $reqResults = "SELECT module, id_content, title, relevance, link
                        FROM " . DB_TABLE_SEARCH_INDEX . " idx, " . DB_TABLE_SEARCH_RESULTS . " rst
                        WHERE idx.id_search = '" . $id_search . "' AND rst.id_search = '" . $id_search . "'
                        AND id_user = '".$this->id_user."' ORDER BY relevance DESC ";
        if ( $nb_lines > 0 )
            $reqResults .= $Sql->limit($offset, $nb_lines);
        
        // Exécution de la requête
        $request = $Sql->query_while ($reqResults, __LINE__, __FILE__);
        while ($result = $Sql->fetch_assoc($request))
        {   // Ajout des résultats
            $results[] = $result;
        }
        // Récupération du nombre de résultats correspondant à la recherche
        $reqNbResults  = "SELECT COUNT(*) " . DB_TABLE_SEARCH_RESULTS . " WHERE id_search = ".$id_search;
        $nbResults = $Sql->num_rows( $request, $reqNbResults );
        
        //On libére la mémoire
        $Sql->query_close($request);
        
        return $nbResults;
    }
    
    function get_results(&$results, &$module_names, $nb_lines = 0, $offset = 0 )
    /**
     *  Renvoie le nombre de résultats de la recherche
     *  et mets les résultats dans le tableau $results
     *  Nb requêtes : 1, 2 si le SGBD ne supporte pas 'sql->Sql_num_rows'
     */
    {
        global $Sql;

        $results = array( );
        $num_modules = 0;
        $modules_conditions = '';
        
        // Construction des conditions de recherche
        foreach ($module_names as $module_name)
        {
            // Teste l'existence de la recherche dans la base sinon signale l'erreur
            if (in_array($module_name, array_keys($this->id_search)))
            {
                // Conditions de la recherche
                if ($num_modules > 0)
                    $modules_conditions .= ", ";
                $modules_conditions .= $this->id_search[$module_name];
                $num_modules++;
            }
        }
        
        // Récupération des $nb_lines résultats à partir de l'$offset
        $reqResults  = "SELECT module, id_content, title, relevance, link
                        FROM " . DB_TABLE_SEARCH_INDEX . " idx, " . DB_TABLE_SEARCH_RESULTS . " rst
                        WHERE (idx.id_search = rst.id_search) ";
        if ($modules_conditions != '')
            $reqResults .= " AND rst.id_search  IN (" . $modules_conditions . ")";
        $reqResults .= " ORDER BY relevance DESC ";
        if ( $nb_lines > 0 )
            $reqResults .= $Sql->limit($offset, $nb_lines);
        
        // Exécution de la requête
        $request = $Sql->query_while ($reqResults, __LINE__, __FILE__);
        while ($result = $Sql->fetch_assoc($request))
        {   // Ajout des résultats
            array_push($results, $result);
        }
        
        // Récupération du nombre de résultats correspondant à la recherche
        $reqNbResults  = "SELECT COUNT(*) FROM " . DB_TABLE_SEARCH_RESULTS . " WHERE id_search IN ( ".$modules_conditions." )";
        if ( $modules_conditions > 0 )
            $nbResults = $Sql->query($reqNbResults, __LINE__, __FILE__  );
        else
            $nbResults = 0;
        
        //On libére la mémoire
        $Sql->query_close($request);
        
        return $nbResults;
    }
    
    function insert_results(&$requestAndResults)
    /**
     *  Enregistre les résultats de la recherche dans la base des résultats
     *  si ils n'y sont pas déjà
     *  Nb requêtes : 1 + k / 10
     */
    {
        global $Sql;
        
        $nbReqSEARCH = 0;
        $reqSEARCH = "";
        $results = array();
        
        // Vérification de la présence des résultats dans le cache
        foreach ($requestAndResults as $module_name => $request)
        {
            if ( !is_array($request) )
            {
                if (!$this->is_in_cache($module_name))
                {   // Si les résultats ne sont pas dans le cache.
                    // Ajout des résultats dans le cache
                    if ($nbReqSEARCH > 0)
                        $reqSEARCH .= " UNION ";
                    
                    $reqSEARCH .= "(".trim( $request, ' ;' ).")";
                    $nbReqSEARCH++;
                }
            }
            else $results += $requestAndResults[$module_name];
        }
        
        $nbResults = count($results);
        // Dans le cas ou il y a des résultats à enregistrer
        if ( ($nbReqSEARCH > 0) || ($nbResults > 0) )
        {
            $nbReqInsert = 0;
            $reqInsert = '';
            
            for ( $nbReqInsert = 0; $nbReqInsert < $nbResults; $nbReqInsert++ )
            {
                $row = $results[$nbReqInsert];
                if ($nbReqInsert > 0)
                    $reqInsert .= ',';
                $reqInsert .= " ('".$row['id_search']."','".$row['id_content']."','".addslashes($row['title'])."',";
                $reqInsert .= "'".$row['relevance']."','".$row['link']."')";
            }

            if ( !empty($reqSEARCH) )
            {
                $request = $Sql->query_while($reqSEARCH, __LINE__, __FILE__);
                while ($row = $Sql->fetch_assoc($request))
                {
                    if ($nbReqInsert > 0)
                        $reqInsert .= ',';
                    $reqInsert .= " ('".$row['id_search']."','".$row['id_content']."','".addslashes($row['title'])."',";
                    $reqInsert .= "'".$row['relevance']."','".$row['link']."')";
                    $nbReqInsert++;
                }
            }
            
            // Exécution des derniéres requêtes d'insertions
            if ($nbReqInsert > 0)
                $Sql->query_inject("INSERT INTO " . DB_TABLE_SEARCH_RESULTS . " VALUES ".$reqInsert, __LINE__, __FILE__);
        }
    }
    
    function is_search_id_in_cache($id_search)
    /**
     *  Renvoie <true> si la recherche est en cache et <false> sinon.
     *  Nb requêtes : 2
     */
    {
        if (in_array($id_search, $this->id_search))
        {
            return true;
        }

        global $Sql;
        $id = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_SEARCH_INDEX . " WHERE id_search = '".$id_search."' AND id_user = '".$this->id_user."';", __LINE__, __FILE__);
        if ($id == 1)
        {
            // la recherche est déjà, en cache, on la met à jour.
            $reqUpdate  = "UPDATE " . DB_TABLE_SEARCH_INDEX . " SET times_used=times_used+1, last_search_use='".time()."' WHERE ";
            $reqUpdate .= "id_search = '".$id_search."' AND id_user = '".$this->id_user."';";
            $Sql->query_inject($reqUpdate, __LINE__, __FILE__);
            
            return true;
        }
        return false;
    }
    
    function is_in_cache($module_name)
    /**
     *  Renvoie true si les résultats du module sont dans le cache
     *  Nb requêtes : 0
     */
    {
        return in_array($module_name, $this->cache);
    }
    
    function modules_in_cache()
    /**
     *  Renvoie la liste des modules présent dans le cache
     *  Nb requêtes : 0
     */
    {
        return array_keys($this->id_search);
    }
    
    function get_ids()
    /**
     *  Renvoie l'id de la recherche
     */
    {
        return $this->id_search;
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
    function _get_modules_conditions(&$modules)
    /**
     *  Génère les conditions de la clause WHERE pour limiter les requêtes
     *  aux seuls modules avec les bonnes options de recherches concernés.
     */
    {
        $nbModules = count($modules);
        $modules_conditions = '';
        if ($nbModules > 0)
        {
            $modules_conditions .= " ( ";
            $i = 0;
            foreach ($modules as $module_name => $options)
            {
                $modules_conditions .= "( module='" . $module_name . "' AND options='" . md5(implode('|', $options)) . "' )";
                
                if ($i < ($nbModules - 1))
                    $modules_conditions .= " OR ";
                else
                    $modules_conditions .= " ) ";
                $i++;
            }
        }
        
        return $modules_conditions;
    }
    
    //----------------------------------------------------- Attributs protégés
    var $id_search;
    var $search;
    var $modules;
    var $modules_conditions;
    var $id_user;
    var $errors;

}

?>

