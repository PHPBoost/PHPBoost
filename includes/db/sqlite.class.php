<?php
/*##################################################
 *                           postgresql.class.php
 *                           --------------------
 *   begin                : April 3, 2008
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

define('LOW_PRIORITY', 'LOW_PRIORITY');
define('DB_NO_CONNECT', false);

class Sql
{
    ## Public Methods ##
    function Sql($connect = true) //Constructeur
    {
        global $sql_host;
        static $connected = false;
        if( $connect == true && !$connected )
        {
            $this->link = @$this->Sql_connect($sql_host) or $this->sql_error('', 'Connexion base de donnée impossible!', __LINE__, __FILE__);

            $connected = true;
        }
        return;
    }
    
    //Connexion
    function Sql_connect($filename='')
    {
        $this->link = @sqlite_open('../includes/db/'.$filename.'.sqlite');
        if ( $this->link === false ) // création de la base de données
        {
            @sqlite_factory('../includes/db/'.$filename.'.sqlite');
            $this->link = @sqlite_open('../includes/db/'.$filename.'.sqlite');
        }
        return $this->link;
    }
    
    //Connexion
    function Sql_select_db($sql_base, $link)
    {
        return sqlite_open('../includes/db/'.$sql_base.'.sqlite'); // inexistant en sqlite faire une déconnection, puis une reconnection
    }

    //Requête simple
    function Query($query, $errline, $errfile) 
    {
        $this->last_ressource = sqlite_query($query) or $this->sql_error($query, 'Requête simple invalide', $errline, $errfile);
        $this->result = sqlite_fetch_array($this->last_ressource);
        $this->close($this->last_ressource); //Déchargement mémoire.
        $this->req++;

        return $this->result[0];
    }

    //Requête multiple.
    function Query_array()
    {
        $table = func_get_arg(0);
        $nbr_arg = func_num_args();

        if( func_get_arg(1) !== '*' )
        {
            $nbr_arg_field_end = ($nbr_arg - 4);
            for($i = 1; $i <= $nbr_arg_field_end; $i++)
            {
                if( $i > 1)
                    $field .= ', ' . func_get_arg($i);
                else
                    $field = func_get_arg($i);
            }
            $end_req = ' ' . func_get_arg($nbr_arg - 3);
        }
        else
        {
            $field = '*';
            $end_req = ($nbr_arg > 4) ? ' ' . func_get_arg($nbr_arg - 3) : '';
        }
        
        $error_line = func_get_arg($nbr_arg - 2);
        $error_file = func_get_arg($nbr_arg - 1);
        $this->last_ressource = sqlite_query('SELECT ' . $field . ' FROM ' . PREFIX . $table . $end_req) or $this->sql_error('SELECT ' . $field . ' FROM ' . PREFIX . $table . '' . $end_req, 'Requête multiple invalide', $error_line, $error_file, SQLITE_ASSOC);
        $this->result = sqlite_fetch_array($this->last_ressource, SQLITE_ASSOC);
        $this->close($this->last_ressource); //Déchargement mémoire.
        $this->req++;
        
        return $this->result;
    }

    //Requete d'injection (insert, update, et requêtes complexes..)
    function Query_inject($query, $errline, $errfile) 
    {
        $this->last_ressource = sqlite_query($query) or $this->sql_error($query, 'Requête inject invalide', $errline, $errfile);
        $this->req++;
        
        return $this->last_ressource;
    }

    //Requête de boucle.
    function Query_while($query, $errline, $errfile) 
    {
        $this->result = sqlite_query($query) or $this->sql_error($query, 'Requête while invalide', $errline, $errfile);
        $this->req++;

        return $this->result;
    }
    
    //Nombre d'entrées dans la table.
    function Count_table($table, $errline, $errfile)
    { 
        $this->last_ressource = sqlite_query('SELECT COUNT(*) AS total FROM ' . PREFIX . $table, SQLITE_ASSOC) or $this->sql_error('SELECT COUNT(*) AS total FROM ' . PREFIX . $table, 'Requête count invalide', $errline, $errfile);
        $this->result = sqlite_fetch_array($this->last_ressource, SQLITE_ASSOC);
        $this->close($this->last_ressource); //Déchargement mémoire.
        $this->req++;
        
        return $this->result['total'];
    }

    //Limite des résultats de la requete sql.
    function Sql_limit($offset, $number = 'ALL')
    {
        return ' LIMIT ' . $number . ', ' .  $offset;
    }
        
    //Concatène des chaines
    //  CONTRAT DE COHERENCE :
    //  - les champs SQL doivent êtres passés sous forme de chaine PHP
    //  - les chaines PHP doivent êtres passés sous forme de chaine PHP
    //      dont le contenu est une chaine PHP délimité par de simple quotes
    //  EXEMPLE :
    //      - champ SQL : $champSQL = "id" ou $champSQL = 'id'
    //      - chaine PHP  : $strPHP = "'ma chaine'" ou $strPHP='\'ma chaine\''
    function Sql_concat()
    {
        $nbr_args = func_num_args();
        $concatString = func_get_arg(0);
        for($i = 1; $i < $nbr_args; $i++)
            $concatString = $concatString . '||' . func_get_arg($i);
        
        return ' ' . $concatString . ' ';
    }
    
    //Balayage du retour de la requête sous forme de tableau indexé par le nom des champs.
    function Sql_fetch_assoc($result)
    {
        return sqlite_fetch_array($result, SQLITE_ASSOC);
    }
    
    //Balayage du retour de la requête sous forme de tableau indexé numériquement.
    function Sql_fetch_row($result)
    {
        return sqlite_fetch_array($result, SQLITE_NUM);
    }
    
    //Lignes affectées lors de requêtes de mise à jour ou d'insertion.
    function Sql_affected_rows($ressource, $query)
    {
        return sqlite_changes($this->last_ressource);
    }
    
    //Nombres de lignes retournées.
    function Sql_num_rows($ressource, $query)
    {
        return sqlite_num_rows($ressource);
    }
    
    //Retourne l'id de la dernière insertion
    function Sql_insert_id($query)
    {
        return sqlite_last_insert_rowid($this->last_ressource);
    }
    
    //Retourne le nombre d'année entre la date et aujourd'hui.
    function Sql_date_diff($field)
    {
        return '(YEAR(CURRENT_DATE) - YEAR(' . $field . ')) - (RIGHT(CURRENT_DATE, 5) < RIGHT(' . $field . ', 5))';
    }
    
    //Déchargement mémoire.
    function Close($result)
    {
//         if( is_resource($result) )
//             return sqlite_close($result);
        return true;
    }

    //Fermeture de la connexion postgresql ouverte.
    function Sql_close()
    {
        if( $this->link ) // si la connexion est établie
            return sqlite_close($this->link); // on ferme la connexion ouverte.
    }
    
    //Liste les champs d'une table.
    function Sql_list_fields($table)
    {
        global $sql_base;
        
        if( !empty($table) )
        {
            $array_fields_name = array();
            $result = $this->query_while("SHOW COLUMNS FROM " . $table . " FROM `" . $sql_base . "`", __LINE__, __FILE__);
            while( $row = pg_fetch_row($result) )
                $array_fields_name[] = $row[0];
            return $array_fields_name;
        }
        else 
            return array();
    }
    
    //Liste les tables + infos.
    function Sql_list_tables()
    {
        global $sql_base;
        
        $array_tables = array();
        
        $result = $this->query_while("SHOW TABLE STATUS FROM `" . $sql_base . "` LIKE '" . PREFIX . "%'", __LINE__, __FILE__);
        while( $row = sqlite_fetch_array($result, SQLITE_NUM) )
        {
            $array_tables[] = array(
                'name' => $row[0],
                'engine' => $row[1],
                'rows' => $row[4],
                'data_length' => $row[6],
                'index_lenght' => $row[8],
                'data_free' => $row[9],
                'collation' => $row[14]
            );
        }
        return $array_tables;
    }
        
    //Parsage d'un fichier SQL => exécution des requêtes.
    function Sql_parse($file_path, $tableprefix = '')
    {
        $handle_sql = @fopen($file_path, 'r');
        if( $handle_sql )
        {
            $req = '';
            while( !feof($handle_sql) )
            {
                $sql_line = trim(fgets($handle_sql));
                //Suppression des lignes vides, et des commentaires.
                if( !empty($sql_line) && substr($sql_line, 0, 2) !== '--' )
                {
                    //On vérifie si la ligne est une commande SQL.
                    if( substr($sql_line, -1) == ';' )
                    {
                        if( empty($req) )
                            $req = $sql_line;
                        else
                            $req .= ' ' . $sql_line;
                            
                        if( !empty($tableprefix) )
                            $this->query_inject(str_replace('phpboost_', $tableprefix, $req), __LINE__, __FILE__);
                        else
                            $this->query_inject($req, __LINE__, __FILE__);
                        $req = '';
                    }   
                    else //Concaténation de la requête qui peut être multi ligne.
                        $req .= ' ' . $sql_line;
                }
            }
            @fclose($handle);
        }
    }
    
    //Affichage du nombre de requête sql.
    function Display_sql_request()
    {
        return $this->req;
    }
    
    //Coloration syntaxique du SQL
    function Highlight_query($query)
    {
        $query = ' ' . strtolower($query) . ' ';
        
        //Suppression des espaces en trop.
        $query = preg_replace('`(\s){2,}(\s){2,}`', '$1', $query);
        
        //Ajout d'un retour à la ligne devant les mots clés principaux.
        $query = preg_replace('`\b(' . implode('|', array('select', 'update', 'insert into', 'from', 'left join', 'right join', 'cross join', 'natural join', 'inner join', 'left outer join', 'right outer join', 'full outer join', 'full join', 'drop', 'truncate', 'where', 'order by', 'group by', 'limit', 'having', 'union')) . ')+`', "\r\n" . '$1', $query);
        
        //Coloration des opérateurs.
        $query = preg_replace('`(' . implode('|', array_map('preg_quote', array('*', '=', ',', '!=', '<>', '>', '<', '.', '(', ')'))) . ')+`U', '<span style="color:#FF00FF;">$1</span>', $query);
        
        //Coloration des mots clés.
        $key_words = array('select', 'update', 'delete', 'insert into', 'truncate', 'alter', 'table', 'status', 'set', 'drop', 'from', 'values', 'count', 'distinct', 'having', 'left', 'right', 'join', 'natural', 'outer', 'inner', 'between', 'where', 'group by', 'order by', 'limit', 'union', 'or', 'and', 'not', 'in', 'as', 'on', 'all', 'any', 'like', 'concat', 'substring', 'collate', 'collation', 'primary', 'key', 'default', 'null', 'exists', 'status', 'show');
        $query = preg_replace_callback('`\b(' . implode('|', $key_words) . ')+\b`', create_function('$matches','return \'<span style="color:#990099;">\' . strtoupper($matches[1]) . \'</span>\';'), $query);
        
        //Coloration finale.
        $query = preg_replace('`\'(.+)\'`U', '<span style="color:#008000;">\'$1\'</span>', $query); //Coloration du texte échappé.
        $query = preg_replace('`(?<![\'#])\b([0-9]+)\b(?!\')`', '<span style="color:#008080;">$1</span>', $query); //Coloration des chiffres.
        
        //Suppression des espaces en trop.
        $query = preg_replace('`(\s){2,}(\s){2,}`', '$1', $query);
        
        return nl2br(trim($query));
    }
    
    //Indente une requête SQL.
    function Indent_query($query)
    {
        $query = ' ' . strtolower($query) . ' ';
        
        //Suppression des espaces en trop.
        $query = preg_replace('`(\s){2,}(\s){2,}`', '$1', $query);

        //Ajout d'un retour à la ligne devant les mots clés principaux.
        $query = preg_replace('`\b(' . implode('|', array('select', 'update', 'insert into', 'from', 'left join', 'right join', 'cross join', 'natural join', 'inner join', 'left outer join', 'right outer join', 'full outer join', 'full join', 'drop', 'truncate', 'where', 'order by', 'group by', 'limit', 'having', 'union')) . ')+`', "\r\n" . '$1', $query);
        
        //Case des mots clés.
        $key_words = array('select', 'update', 'delete', 'insert into', 'truncate', 'alter', 'table', 'status', 'set', 'drop', 'from', 'values', 'count', 'distinct', 'having', 'left', 'right', 'join', 'natural', 'outer', 'inner', 'between', 'where', 'group by', 'order by', 'limit', 'union', 'or', 'and', 'not', 'in', 'as', 'on', 'all', 'any', 'like', 'concat', 'substring', 'collate', 'collation', 'primary', 'key', 'default', 'null', 'exists', 'status', 'show');
        $query = preg_replace_callback('`\b(' . implode('|', $key_words) . ')+\b`', create_function('$matches','return strtoupper($matches[1]);'), $query);
        
        //Suppression des espaces en trop.
        $query = preg_replace('`(\s){2,}(\s){2,}`', '$1', $query);
        
        return trim($query);
    }
    
    ## Private Methods ##
    //Gestion des erreurs.
    function sql_error($query, $errstr, $errline = '', $errfile = '') 
    {
        global $Errorh;
        
        //Enregistrement dans le log d'erreur.
        $Errorh->Error_handler($errstr . '<br /><br />' . $query . '<br /><br />' . pg_last_error(), E_USER_ERROR, $errline, $errfile);
    }
    
    ## Private attributes ##
    var $link; //Lien avec la base de donnée.
    var $result = array(); //Resultat de la requête.
    var $req = 0; //Nombre de requêtes.
    var $last_ressource; // dernière ressource
}
?>