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

/**
 * @author Loïc Rouchon <horn@phpboost.com>
 * @desc
 * @package content
 */
class Search
{
    //----------------------------------------------------------------- PUBLIC
    //---------------------------------------------------------- Constructeurs
    
    /**
     * @desc Builds a search object.
     * Query Complexity: 6 + k / 10 database queries. (k represent the number of
     * module without search cache)
     * @param string $search the string to search
     * @param mixed[] $modules Modules in which we gonna search with their search params.
     * This argument is an array which keys are module id's and values are arrays
     * containing the specialized search arguments for a particular module.
     */
    function Search($search = '', $modules = array())
    {
        global $Sql, $User;
        
        $this->errors = 0;
        $this->search = md5($search); // Generating a search id;
        $this->modules = $modules;
        $this->id_search = array();
        $this->cache = array();
        
        $this->id_user = $User->get_attribute('user_id');
        $this->modules_conditions = $this->_get_modules_conditions($this->modules);
                
        
        // Deletes old results from cache
        
        // Here, 3 queries in order to avoid a multi-table delete which is not portable
        // or 2 queries with a NOT IN (a bit long)
        
        // Lists old results to delete
        $reqOldIndex = "SELECT id_search FROM " . PREFIX . "search_index
                        WHERE  last_search_use <= '".(time() - (CACHE_TIME * 60))."'
                            OR times_used >= '".CACHE_TIMES_USED."'";
        
        $nbIdsToDelete = 0;
        $idsToDelete = '';
        $request = $Sql->query_while ($reqOldIndex, __LINE__, __FILE__);
        while ($row = $Sql->fetch_assoc($request))
        {
            if ($nbIdsToDelete > 0)
            {
                $idsToDelete .= ',';
            }
            $idsToDelete .= "'" . $row['id_search'] . "'";
            $nbIdsToDelete++;
        }
        $Sql->query_close($request);
        
        // Deletes old results
        if ($nbIdsToDelete > 0)
        {
            $reqDeleteIdx = "DELETE FROM " . DB_TABLE_SEARCH_INDEX . " WHERE id_search IN (".$idsToDelete.")";
            $reqDeleteRst = "DELETE FROM " . DB_TABLE_SEARCH_RESULTS . " WHERE id_search IN (".$idsToDelete.")";
            
            $Sql->query_inject($reqDeleteIdx, __LINE__, __FILE__);
            $Sql->query_inject($reqDeleteRst, __LINE__, __FILE__);
        }
        
        // Don't compute anything if no text is searched
        // (useful for based-id searches)
        if ($this->search != '')
        {
            // Checks cache results meta-inf
            $reqCache  = "SELECT id_search, module FROM " . DB_TABLE_SEARCH_INDEX . " WHERE ";
            $reqCache .= "search='" . $this->search . "' AND id_user='" . $this->id_user . "'";
            if ($this->modules_conditions != '')
            {
                $reqCache .= " AND " . $this->modules_conditions;
            }
            
            $request = $Sql->query_while ($reqCache, __LINE__, __FILE__);
            while ($row = $Sql->fetch_assoc($request))
            {   // retrieves cache result meta-inf
                array_push($this->cache, $row['module']);
                $this->id_search[$row['module']] = $row['id_search'];
            }
            $Sql->query_close($request);
            
            // Updates cache results meta-inf
            if (count($this->id_search) > 0)
            {
                $reqUpdate  = "UPDATE " . DB_TABLE_SEARCH_INDEX . " SET times_used=times_used+1, last_search_use='" . time() . "' WHERE ";
                $reqUpdate .= "id_search IN (" . implode(',', $this->id_search) . ");";
                $Sql->query_inject($reqUpdate, __LINE__, __FILE__);
            }
            
            // Adds modules missing in cache
            if (count($modules) > count($this->cache))
            {
                $nbReqInsert = 0;
                $reqInsert = '';
                
                foreach ($modules as $module_name => $options)
                {
                    if (!$this->is_in_cache($module_name))
                    {
                        $reqInsert .= "('" . $this->id_user . "','" . $module_name . "','" . $this->search . "','" . md5(implode('|', $options)) . "','" . time() . "', '0'),";
                        // Executes 10 insertions
                        if ($nbReqInsert == 10)
                        {
                            $reqInsert = "INSERT INTO " . DB_TABLE_SEARCH_INDEX . " (id_user, module, search, options, last_search_use, times_used) VALUES " . $reqInsert . "";
                            $Sql->query_inject($reqInsert, __LINE__, __FILE__);
                            $reqInsert = '';
                            $nbReqInsert = 0;
                        }
                        else
                        {
                            $nbReqInsert++;
                        }
                    }
                }
                
                // Executes last insertions queries
                if ($nbReqInsert > 0)
                {
                    $Sql->query_inject("INSERT INTO " . DB_TABLE_SEARCH_INDEX . " (id_user, module, search, options, last_search_use, times_used) VALUES " . substr($reqInsert, 0, strlen($reqInsert) - 1) . "", __LINE__, __FILE__);
                }
                
                // Checks and retrieves cache meta-informations
                $reqCache  = "SELECT id_search, module FROM " . DB_TABLE_SEARCH_INDEX . " WHERE ";
                $reqCache .= "search='" . $this->search . "' AND id_user='" . $this->id_user . "'";
                if ($this->modules_conditions != '')
                {
                    $reqCache .= " AND " . $this->modules_conditions;
                }
                
                $request = $Sql->query_while ($reqCache, __LINE__, __FILE__);
                while ($row = $Sql->fetch_assoc($request))
                {   // Ajout des résultats s'ils font partie de la liste des modules à traiter
                    $this->id_search[$row['module']] = $row['id_search'];
                }
                $Sql->query_close($request);
            }
        }
    }
    
    
    /**
     * @desc Puts results from the search results identified by the $id_search parameter
     * in the $results parameter and returns the number of results.
     * Query complexity: 2 queries.
     * @param string[] &$results the results returned
     * @param int $id_search the search id
     * @param int $nb_lines the number of lines to return
     * @param int $offset the offset from which return results
     * @return int The number of results
     */
    function get_results_by_id(&$results, $id_search = 0, $nb_lines = 0, $offset = 0)
    {
        global $Sql;
        $results = array();
        
        // Building request
        $reqResults = "SELECT module, id_content, title, relevance, link
                        FROM " . DB_TABLE_SEARCH_INDEX . " idx, " . DB_TABLE_SEARCH_RESULTS . " rst
                        WHERE idx.id_search = '" . $id_search . "' AND rst.id_search = '" . $id_search . "'
                        AND id_user = '".$this->id_user."' ORDER BY relevance DESC ";
        if ($nb_lines > 0)
        {
            $reqResults .= $Sql->limit($offset, $nb_lines);
        }
        
        // Retrieves results
        $request = $Sql->query_while ($reqResults, __LINE__, __FILE__);
        while ($result = $Sql->fetch_assoc($request))
        {
            $results[] = $result;
        }
        $nbResults = $Sql->num_rows($request, "SELECT COUNT(*) " . DB_TABLE_SEARCH_RESULTS . " WHERE id_search = ".$id_search);
        $Sql->query_close($request);
        
        return $nbResults;
    }
    
    
    /**
     * @desc Puts results from the search results in the $results parameter and
     * returns the number of results.
     * Query complexity: 1 query.
     * @param string[] &$results the results returned
     * @param string[] &$module_ids the modules ids from which retrieve results
     * @param int $nb_lines the number of lines to return
     * @param int $offset the offset from which return results
     * @return int The number of results
     */
    function get_results(&$results, &$module_ids, $nb_lines = 0, $offset = 0 )
    {
        global $Sql;

        $results = array();
        $num_modules = 0;
        $modules_conditions = '';
        
        // Builds search conditions
        foreach ($module_ids as $module_id)
        {
            // Checks search cache.
            if (in_array($module_id, array_keys($this->id_search)))
            {
                // Search conditions
                if ($num_modules > 0)
                {
                    $modules_conditions .= ", ";
                }
                $modules_conditions .= $this->id_search[$module_id];
                $num_modules++;
            }
        }
        
        // Builds search results retrieval request
        $reqResults  = "SELECT module, id_content, title, relevance, link
                        FROM " . DB_TABLE_SEARCH_INDEX . " idx, " . DB_TABLE_SEARCH_RESULTS . " rst
                        WHERE (idx.id_search = rst.id_search) ";
        if ($modules_conditions != '')
        {
            $reqResults .= " AND rst.id_search  IN (" . $modules_conditions . ")";
        }
        $reqResults .= " ORDER BY relevance DESC ";
        if ( $nb_lines > 0 )
        {
            $reqResults .= $Sql->limit($offset, $nb_lines);
        }
        
        // Executes search
        $request = $Sql->query_while ($reqResults, __LINE__, __FILE__);
        while ($result = $Sql->fetch_assoc($request))
        {
            $results[] = $result;
        }
        $nbResults = $Sql->num_rows($request, __LINE__, __FILE__  );
        
        $Sql->query_close($request);
        
        return $nbResults;
    }
    
    
    /**
     * @desc Inserts search results in the database cache in order to speed up next searches.
     * Query complexity: 1 + k / 10 queries. (k represent the number of results to insert in the database)
     * @param mixed[] &$requestAndResults This parameters is an array with keys that are
     * modules ids and values that could be both a SQL query or a results array.
     */
    function insert_results(&$requestAndResults)
    {
        global $Sql;
        
        $nbReqSEARCH = 0;
        $reqSEARCH = "";
        $results = array();
        
        // Checks results in cache
        foreach ($requestAndResults as $module_name => $request)
        {
            if (!is_array($request))
            {
                if (!$this->is_in_cache($module_name))
                {   // If results are not in cache, adds them
                    if ($nbReqSEARCH > 0)
                    {
                        $reqSEARCH .= " UNION ";
                    }
                    
                    $reqSEARCH .= "(".trim( $request, ' ;' ).")";
                    $nbReqSEARCH++;
                }
            }
            else
            {
                $results += $requestAndResults[$module_name];
            }
        }
        
        $nbResults = count($results);
        // If there is many results to insert
        if (($nbReqSEARCH > 0) || ($nbResults > 0))
        {
            $nbReqInsert = 0;
            $reqInsert = '';
            
            // Group insertions by 10
            for ($nbReqInsert = 0; $nbReqInsert < $nbResults; $nbReqInsert++)
            {
                $row = $results[$nbReqInsert];
                if ($nbReqInsert > 0)
                {
                    $reqInsert .= ',';
                }
                $reqInsert .= " ('".$row['id_search']."','".$row['id_content']."','".addslashes($row['title'])."',";
                $reqInsert .= "'".$row['relevance']."','".$row['link']."')";
            }

            if (!empty($reqSEARCH))
            {   // Inserts results
                $request = $Sql->query_while($reqSEARCH, __LINE__, __FILE__);
                while ($row = $Sql->fetch_assoc($request))
                {
                    if ($nbReqInsert > 0)
                    {
                        $reqInsert .= ',';
                    }
                    $reqInsert .= " ('".$row['id_search']."','".$row['id_content']."','".addslashes($row['title'])."',";
                    $reqInsert .= "'".$row['relevance']."','".$row['link']."')";
                    $nbReqInsert++;
                }
            }
            
            // Executes last insertions
            if ($nbReqInsert > 0)
            {
                $Sql->query_inject("INSERT INTO " . DB_TABLE_SEARCH_RESULTS . " VALUES ".$reqInsert, __LINE__, __FILE__);
            }
        }
    }
    
    
    /**
     * @desc Returns true if the id_search is in cache, else, false.
     * @param int $id_search the search id to check.
     * @return bool true if the id_search is in cache, else, false.
     */
    function is_search_id_in_cache($id_search)
    {
        if (in_array($id_search, $this->id_search))
        {
            return true;
        }

        global $Sql;
        $id = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_SEARCH_INDEX . " WHERE id_search = '" . $id_search . "' AND id_user = '" . $this->id_user . "';", __LINE__, __FILE__);
        if ($id == 1)
        {
            // Search is already in cache, we update it.
            $reqUpdate  = "UPDATE " . DB_TABLE_SEARCH_INDEX . " SET times_used=times_used+1, last_search_use='" . time() . "' WHERE ";
            $reqUpdate .= "id_search = '" . $id_search . "' AND id_user = '" . $this->id_user . "';";
            $Sql->query_inject($reqUpdate, __LINE__, __FILE__);
            
            return true;
        }
        return false;
    }
    
    
    /**
     * @desc Returns true if the module results are in cache, else, false.
     * @param string $module_id the module to check.
     * @return bool true if the module results are in cache, else, false.
     */
    function is_in_cache($module_id)
    {
        return in_array($module_id, $this->cache);
    }
    
    
    /**
     * @desc Returns the list of the modules ids present in the cache
     * @return string[] the list of the modules present in the cache
     */
    function modules_in_cache()
    {
        return array_keys($this->id_search);
    }
    
    /**
     * @desc Returns the search id
     * @return int the search id
     */
    function get_ids()
    {
        return $this->id_search;
    }
    
    
    /**
     * @desc Builds modules search conditions on the meta-information cache
     * @param mixed[string] $modules An array with modules ids keys and values
     * containings modules specifics options.
     * @return string The condition to put in a query WHERE clause
     */
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
            foreach ($modules as $module_id => $options)
            {
                $modules_conditions .= "( module='" . $module_id . "' AND options='" . md5(implode('|', $options)) . "' )";
                
                if ($i < ($nbModules - 1))
                {
                    $modules_conditions .= " OR ";
                }
                else
                {
                    $modules_conditions .= " ) ";
                }
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

