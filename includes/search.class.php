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
    //----------------------------------------------------- M�thodes publiques
    function InsertResults ( &$requests )
    /**
     *  Enregistre les r�sultats de la recherche dans la base des r�sultats
     *  si ils n'y sont pas d�j�
     *  Nb requ�tes : 1
     */
    {
        $nbReqSEARCH = 0;
        $reqSEARCH = '';
        
        $nbReqInsert = 0;
        $reqInsert = '';
        
        // V�rification de la pr�sence des r�sultats dans le cache
        foreach ( $requests as $id_module => $request )
        {
            if ( !$this->IsInCache ( $id_module ) )
            {   // Si les r�sultats ne sont pas dans le cache.
                // Ajout des r�sultats dans le cache
                if ( $nbReqSEARCH > 0 )
                { $reqSEARCH .= ' UNION '; }
                else
                { $nbReqSEARCH++; }
                $reqSEARCH .= trim( $request, ' ;' );
            }
        }
        
        $reqSEARCH = "INSERT ( ".$reqSEARCH." ) INTO ".PREFIX."search_results;";
        $this->sql->query_inject($reqSEARCH, __LINE__, __FILE__ );
        
        // Au cas ou le insert select into ne soit pas portable.
//         $reqInsert = '';
//         $request = $this->sql->query_while( $reqSEARCH, __LINE__, __FILE__ );
//         while( $result = $this->sql->sql_fetch_assoc($request) )
//         {
//             $reqInsert .= " ('".$this->id_search[$id_module]."','".$id_module."','".$result['id_content']."',";
//             $reqInsert .= "'".$result['relevance']."','".$result['id_content']."'), ";
//             
//             // Ex�cution de 10 requ�tes d'insertions
//             if ( $nbReqInsert == 10 )
//             {
//                 $this-sql->query_insert("INSERT INTO ".PREFIX."search_results VALUES ( ".$reqInsert." )", __LINE__, __FILE__);
//                 $reqInsert = '';
//                 $nbReqInsert = 0;
//             }
//             else { $nbReqInsert++; }
//         }
//         
//         // Ex�cution des derni�res requ�tes d'insertions
//         if ( $nbReqInsert > 1 )
//         { $sql->query_inject("INSERT INTO ".PREFIX."search_results VALUES ( ".$reqInsert." )", __LINE__, __FILE__); }
    }
    
    function GetResults (  &$results, &$id_modules, $offset = 0, $nbLines = NB_LINES)
    /**
     *  Renvoie le nombre de r�sultats de la recherche
     *  et mets les r�sultats dans le tableau &results
     *  Nb requ�tes : 1, 2 si le SGBD ne supporte pas 'sql->sql_num_rows'
     */
    {
        $results = Array ( );
        $numModules = 0;
        $modulesConditions = '';
        
        // Construction des conditions de recherche
        foreach ( $id_modules as $id_module )
        {
            // Teste l'existence de la recherche dans la base sinon signale l'erreur
            if ( in_array ( $id_module, array_keys ( $this->search ) ) )
            {
                // Conditions de la recherche
                if ( $numModules > 0 )
                { $modulesConditions .= " OR"; }
                $modulesConditions .= " ( id_search='".$this->id_search[$id_module]."' ";
                $modulesConditions .= " AND id_module='".$id_module."' ) ";
                $numModules++;
            }
        }
        
        // R�cup�ration des $nbLines r�sultats � partir de l'$offset
        $reqResults  = "SELECT id_module, id_content, relevance, link FROM ".PREFIX."search_results WHERE ";
        $reqResults .= $modulesConditions." ORDER BY relevance DESC ".$this->sql->sql_limit($offset, $nbLines);
        
        // Ex�cution de la requ�te
        $request = $this->sql->query_while( $reqResults, __LINE__, __FILE__ );
        while( $result = $this->sql->sql_fetch_assoc($request) )
        {   // Ajout des r�sultats
            array_push($results, $result);
        }
        // R�cup�ration du nombre de r�sultats correspondant � la recherche
        $reqNbResults  = "SELECT COUNT(*) ".PREFIX."search_results ".$modulesConditions;
        $nbResults = $this->sql->sql_num_rows( $request, $reqNbResults );
        
        //On lib�re la m�moire
        $this->sql->close($request);
        
        return $nbResults;
    }
    
    function ModulesInCache ( )
    /**
     *  Renvoie la liste des modules pr�sent dans le cache
     *  Nb requ�tes : 0
     */
    {
        return array_keys ( $this->id_search );
    }
    
    function IsInCache ( $id_module )
    /**
     *  Renvoie true si les r�sultats du module sont dans le cache
     *  Nb requ�tes : 0
     */
    {
        return in_array ( $id_module, array_keys ( $this->id_search ) );
    }
    
    //---------------------------------------------------------- Constructeurs
    
    function Search ( $search = '', $modules = Array ( ) )
    /**
     *  Constructeur de la classe Search
     *  Nb requ�tes : 4 + k / 10
     *  avec k nombre de module n'ayant pas de cache de recherche
     */
    {
        $this->errors = 0;
        $this->search = $search;
        $this->modules = $modules;
        $this->id_search = Array ( );

        global $session;
        $this->id_user = $session->data['user_id'];
        
        global $sql;
        $this->sql = $sql;
        $this->modulesConditions = $this->getModulesConditions ( &$modules );

        // D�lestage
        $reqDelete  = "DELETE FROM ".PREFIX."search_index WHERE ";
        $reqDelete .= "last_search_use < '".($time() - (CACHE_TIME * 60))."' OR times_used > '".CACHE_TIMES_USED."' ";
        
        // V�rifications des r�sultats dans le cache.
        $reqCache  = "SELECT id_search, id_module FROM".PREFIX."search_index WHERE ";
        $reqCache .= "search='".$search."' AND id_user='".$this->id_user."' AND ".$this->modulesConditions;
        
        $request = $this->sql->query_while( $reqCache, __LINE__, __FILE__ );
        while( $row = $this->sql->sql_fetch_assoc($request) )
        {   // Ajout des r�sultats s'ils font partie de la liste des modules � traiter
            $this->id_search[$row[1]] = $row[0];
        }
        $this->sql->close($request);
        
        // Mise � jours des r�sultats du cache
        if ( count ( $this->id_search ) > 0 )
        {
            $reqUpdate  = "UPDATE ".PREFIX."search_index SET times_used=(times_used + 1), last_search_use='".$time()."' WHERE ";
            $reqUpdate .= "id_search IN ( ".implode($this->id_search)." ) AND user_id='".$this->id_user."';";
            $this->sql->query_insert($reqDelete.$reqUpdate, __LINE__, __FILE__);
        }
        
        $nbReqInsert = 0;
        $reqInsert = '';
        foreach ( $requests as $id_module => $request )
        {
            if ( !$this->IsInCache ( $id_module ) )
            {
                $reqInsert .= "('".$this->id_search[$id_module]."','".$id_module."','".$this->id_user."','".$result['id_content']."',";
                $reqInsert .= "'".$result['relevance']."','".time()."', '0'), ";
                
                // Ex�cution de 10 requ�tes d'insertions
                if ( $nbReqInsert == 10 )
                {
                    $reqInsert = "INSERT INTO ".PREFIX."search_index VALUES ( ".$reqInsert." )";
                    $this->sql->query_insert($reqInsert, __LINE__, __FILE__);
                    $reqInsert = '';
                    $nbReqInsert = 0;
                }
                else { $nbReqInsert++; }
            }
        }
        
        // Ex�cution des derni�res requ�tes d'insertions
        if ( $nbReqInsert > 1 )
        { $sql->query_inject("INSERT INTO ".PREFIX."search_index VALUES ( ".$reqInsert." )", __LINE__, __FILE__); }
        
        // R�cup�ration des r�sultats et de leurs id dans le cache.
        
        // Pourquoi faire �� plut�t que de r�cup�rer id_search pour chaque
        // insertion dans l'index du cache.
        // parce que cela donne au total pour le contructeur une complexit�
        // en requ�te de :
        // 1 (delete) + 1 (recup id) + 1 (update timestamp) + k / 10 (nb non dans le cache) + 1 (recup id) = 4 + k/10
        // au lieu de :
        // 1 (delete) + 1 (recup id) + 1 (update timestamp) + k (nb non dans le cache) = 3 + k
        // cela permet donc de grouper les insertions dans l'index du cache.
        
        $reqCache  = "SELECT id_search, id_module FROM".PREFIX."search_index ";
        $reqCache .= "WHERE search='".$search."' AND ".$this->modulesConditions;
        
        $request = $this->sql->query_while( $reqCache, __LINE__, __FILE__ );
        while( $row = $this->sql->sql_fetch_assoc($request) )
        {   // Ajout des r�sultats s'il fait partie de la liste des modules � traiter
            $this->id_search[$row[1]] = $row[0];
        }
        $this->sql->close($request);
    }
    
    //------------------------------------------------------------------ PRIVE
    /**
     *  Pour des raisons de compatibilit� avec PHP 4, les mots-cl�s private,
     *  protected et public ne sont pas utilis�.
     *  
     *  L'appel aux m�thodes et/ou attributs PRIVE/PROTEGE est donc possible.
     *  Cependant il est strictement d�conseill�, car cette partie du code
     *  est suceptible de changer sans avertissement et donc vos modules ne
     *  fonctionnerai plus.
     *  
     *  Bref, utilisation � vos risques et p�rils !!!
     *  
     */
    
    //----------------------------------------------------- M�thodes prot�g�es
    function getModulesConditions ( &$modules )
    /**
     *  G�n�re les conditions de la clause WHERE pour limiter les requ�tes
     *  aux seuls modules avec les bonnes options de recherches concern�s.
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
    
    //----------------------------------------------------- Attributs prot�g�s
    var $id_search;
    var $search;
    var $modules;
    var $modulesConditions;
    var $id_user;
    var $sql;
    var $errors;

}

?>

