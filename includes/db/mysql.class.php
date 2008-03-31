<?php
/*##################################################
 *                                mysql.class.php
 *                            -------------------
 *   begin                : March 13, 2006
 *   copyright          : (C) 2005 Régis Viarre, Loïc Rouchon
 *   email                : crowkait@phpboost.com, horn@phpboost.com
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
		global $sql_host, $sql_login, $sql_pass, $sql_base;
		static $connected = false;
		if( $connect == true && !$connected )
		{
			$this->link = @$this->Sql_connect($sql_host, $sql_login, $sql_pass) or $this->sql_error('', 'Connexion base de donnée impossible!', __LINE__, __FILE__);
			@$this->sql_select_db($sql_base, $this->link) or $this->sql_error('', 'Selection de la base de donnée impossible!', __LINE__, __FILE__);
			$connected = true;			
		}
		return;
	}
	
	//Connexion
	function Sql_connect($sql_host, $sql_login, $sql_pass)
	{
		return mysql_connect($sql_host, $sql_login, $sql_pass);
	}
	
	//Connexion
	function Sql_select_db($sql_base, $link)
	{
		return mysql_select_db($sql_base, $link);
	}

	//Requête simple
	function Query($query, $errline, $errfile) 
	{		
		$this->result = mysql_query($query) or $this->sql_error($query, 'Requête simple invalide', $errline, $errfile);		
		$this->result = mysql_fetch_row($this->result);
		$this->close($this->result); //Déchargement mémoire.	
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
		$this->result = mysql_query('SELECT ' . $field . ' FROM ' . PREFIX . $table . $end_req) or $this->sql_error('SELECT ' . $field . ' FROM ' . PREFIX . $table . '' . $end_req, 'Requête multiple invalide', $error_line, $error_file);
		$this->result = mysql_fetch_assoc($this->result);
		$this->close($this->result); //Déchargement mémoire.
		$this->req++;		
		
		return $this->result;
	}

	//Requete d'injection (insert, update, et requêtes complexes..)
	function Query_inject($query, $errline, $errfile) 
	{
		$resource = mysql_query($query) or $this->sql_error($query, 'Requête inject invalide', $errline, $errfile);
		$this->req++;
		
		return $resource;
	}

	//Requête de boucle.
	function Query_while($query, $errline, $errfile) 
	{
		$this->result = mysql_query($query) or $this->sql_error($query, 'Requête while invalide', $errline, $errfile);
		$this->req++;

		return $this->result;
	}
	
	//Nombre d'entrées dans la table.
	function Count_table($table, $errline, $errfile)
	{ 
		$this->result = mysql_query('SELECT COUNT(*) AS total FROM ' . PREFIX . $table) or $this->sql_error('SELECT COUNT(*) AS total FROM ' . PREFIX . $table, 'Requête count invalide', $errline, $errfile);
		$this->result = mysql_fetch_assoc($this->result);
		$this->close($this->result); //Déchargement mémoire.		
		$this->req++;
		
		return $this->result['total'];
	}

	//Limite des résultats de la requete sql.
	function Sql_limit($start, $end = 0)
	{
		return ' LIMIT ' . $start . ', ' .  $end;
	}
		
    //Concatène des chaines
    //  CONTRAT DE COHERENCE :
    //  - les champ mysql doivent êtres passés sous forme de chaine PHP
    //  - les chaines PHP doivent êtres passés sous forme de chaine PHP
    //      dont le contenu est une chaine PHP délimité par de simple quotes
    //  EXEMPLE :
    //      - champ MySQL : $champMySQL = "id" ou $champMySQL = 'id'
    //      - chaine PHP  : $strPHP = "'ma chaine'" ou $strPHP='\'ma chaine\''
    function Sql_concat()
    {
        $numArgs = func_num_args();
        $concatString = func_get_arg(1);
        for ( $i = 1; $i < $numArgs; $i++ )
            $concatString = 'CONCAT('.$concatString.','.func_get_arg($i).')';
        
        return ' '.$concatString.' ';
    }
    
	//Balayage du retour de la requête sous forme de tableau indexé par le nom des champs.
	function Sql_fetch_assoc($result)
	{	
		return mysql_fetch_assoc($result);
	}
	
	//Balayage du retour de la requête sous forme de tableau indexé numériquement.
	function Sql_fetch_row($result)
	{	
		return mysql_fetch_row($result);
	}
	
	//Lignes affectées lors de requêtes de mise à jour ou d'insertion.
	function Sql_affected_rows($ressource, $query)
	{
		return mysql_affected_rows();
	}
	
	//Nombres de lignes retournées.
	function Sql_num_rows($ressource, $query)
	{
		return mysql_num_rows($ressource);
	}
	
	//Retourne l'id de la dernière insertion
	function Sql_insert_id($query)
	{
		return mysql_insert_id();
	}
	
	//Retourne le nombre d'année entre la date et aujourd'hui.
	function Sql_date_diff($field)
	{
		return '(YEAR(CURRENT_DATE) - YEAR(' . $field . ')) - (RIGHT(CURRENT_DATE, 5) < RIGHT(' . $field . ', 5))';
	}
	
	//Déchargement mémoire.
	function Close($result)
	{
		if( is_resource($result) )
			return mysql_free_result($result);		
	}

	//Fermeture de la connexion mysql ouverte.
	function Sql_close()
	{
		if( $this->link ) // si la connexion est établie
			return mysql_close($this->link); // on ferme la connexion ouverte.
	}
	
	//Liste les champs d'une table.
	function Sql_list_fields($table)
	{
		global $sql_base;
		
		if( !empty($table) )
		{
			$array_fields_name = array();
			$result = $this->query_while("SHOW COLUMNS FROM " . $table . " FROM `" . $sql_base . "`", __LINE__, __FILE__);
			while( $row = mysql_fetch_row($result) ) 
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
		while( $row = mysql_fetch_row($result) )
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
	
	
	## Private Methods ##
	//Gestion des erreurs.
	function sql_error($query, $errstr, $errline = '', $errfile = '') 
	{
		global $Errorh;
		
		//Enregistrement dans le log d'erreur.
		$Errorh->Error_handler($errstr . '<br /><br />' . $query . '<br /><br />' . mysql_error(), E_USER_ERROR, $errline, $errfile);
	}	
	
	
	## Private attributes ##
	var $link; //Lien avec la base de donnée.
	var $result = array(); //Resultat de la requête.
	var $req = 0; //Nombre de requêtes.
}		
?>