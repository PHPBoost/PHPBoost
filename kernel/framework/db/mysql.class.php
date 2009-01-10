<?php
/*##################################################
 *                              mysql.class.php
 *                            -------------------
 *   begin                : March 13, 2006
 *   copyright            : (C) 2005 Régis Viarre, Loïc Rouchon
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
define('ERRORS_MANAGEMENT_BY_RETURN', false);
define('EXPLICIT_ERRORS_MANAGEMENT', true);

//Errors
define('CONNECTION_FAILED', 1);
define('UNEXISTING_DATABASE', 2);
define('CONNECTED_TO_DATABASE', 3);

define('DBTYPE', 'mysql');

class Sql
{
	## Public Methods ##
	//Constructeur de la classe Sql. Il prend en paramètre les paramètres de connexion à la base de données. Par défaut la classe Sql gère de façon autonome les erreurs de connexion, mais on peut demander à les gérer manuellement
	function Sql() { }
	
	//Connexion
	function connect($sql_host, $sql_login, $sql_pass, $sql_base, $errors_management = EXPLICIT_ERRORS_MANAGEMENT)
	{
		//Identification sur le serveur
		if ($this->link = @mysql_connect($sql_host, $sql_login, $sql_pass))
		{
			//Sélection de la base de données
			if (@mysql_select_db($sql_base, $this->link))
			{
				$this->connected = true;
				$this->sql_base = $sql_base;
				return CONNECTED_TO_DATABASE;
			}
			else
			{
				//Traitement des erreurs
				if ($errors_management)
					$this->_error('', 'Can \'t select database!', __LINE__, __FILE__);
				else
					return UNEXISTING_DATABASE;
			}
		}
		//La connexion a échoué
		else
		{
			if ($errors_management)
				$this->_error('', 'Can\'t connect to database!', __LINE__, __FILE__);
			else
				return CONNECTION_FAILED;
		}
	}
	
	//Autoconnexion (lecture du fichier de configuration)
	function auto_connect()
	{
		//Lecture du fichier de configuration.
		@require_once(PATH_TO_ROOT . '/kernel/db/config.php');
		
		//Si PHPBoost n'est pas installé, redirection manuelle car chemin non connu.
		if (!defined('PHPBOOST_INSTALLED'))
		{
		    import('util/unusual_functions', LIB_IMPORT);
		    redirect(get_server_url_page('install/install.php'));
		}

		//Connexion à la base de données
		$result =  $this->connect($sql_host, $sql_login, $sql_pass, $sql_base);
		$this->sql_base = $sql_base;
		
		return $result;
	}

	//Requête simple
	function query($query, $errline, $errfile)
	{
		$ressource = mysql_query($query, $this->link) or $this->_error($query, 'Invalid SQL request', $errline, $errfile);
		$result = mysql_fetch_row($ressource);
		$this->query_close($ressource); //Déchargement mémoire.
		$this->req++;

		return $result[0];
	}

	//Requête multiple.
	function query_array()
	{
		$table = func_get_arg(0);
		$nbr_arg = func_num_args();

		if (func_get_arg(1) !== '*')
		{
			$nbr_arg_field_end = ($nbr_arg - 4);
			for ($i = 1; $i <= $nbr_arg_field_end; $i++)
			{
				if ($i > 1)
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
		$ressource = mysql_query('SELECT ' . $field . ' FROM ' . $table . $end_req, $this->link) or $this->_error('SELECT ' . $field . ' FROM ' . $table . '' . $end_req, 'Invalid SQL request', $error_line, $error_file);
		$result = mysql_fetch_assoc($ressource);
		
		//Fermeture de la ressource
		$this->query_close($ressource);
		$this->req++;
		
		return $result;
	}

	//Requete d'injection (insert, update, et requêtes complexes..)
	function query_inject($query, $errline, $errfile)
	{
		$resource = mysql_query($query, $this->link) or $this->_error($query, 'Invalid inject request', $errline, $errfile);
		$this->req++;
		
		return $resource;
	}

	//Requête de boucle.
	function query_while ($query, $errline, $errfile)
	{
		$result = mysql_query($query, $this->link) or $this->_error($query, 'invalid while request', $errline, $errfile);
		$this->req++;

		return $result;
	}
	
	//Nombre d'entrées dans la table.
	function count_table($table, $errline, $errfile)
	{
		$ressource = mysql_query('SELECT COUNT(*) AS total FROM ' . PREFIX . $table, $this->link) or $this->_error('SELECT COUNT(*) AS total FROM ' . PREFIX . $table, 'Invalid count request', $errline, $errfile);
		$result = mysql_fetch_assoc($ressource);
		$this->query_close($result); //Déchargement mémoire.
		$this->req++;
		
		return $result['total'];
	}

	//Limite des résultats de la requete sql.
	function limit($start, $end = 0)
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
    function Concat()
    {
        $nbr_args = func_num_args();
        $concatString = func_get_arg(0);
        for ($i = 1; $i < $nbr_args; $i++)
            $concatString = 'CONCAT(' . $concatString . ',' . func_get_arg($i) . ')';
        
        return ' ' . $concatString . ' ';
    }
    
	//Balayage du retour de la requête sous forme de tableau indexé par le nom des champs.
	function fetch_assoc($result)
	{
		return mysql_fetch_assoc($result);
	}
	
	//Balayage du retour de la requête sous forme de tableau indexé numériquement.
	function fetch_row($result)
	{
		return mysql_fetch_row($result);
	}
	
	//Lignes affectées lors de requêtes de mise à jour ou d'insertion.
	function affected_rows($ressource, $query)
	{
		return mysql_affected_rows();
	}
	
	//Nombres de lignes retournées.
	function num_rows($ressource, $query)
	{
		return mysql_num_rows($ressource);
	}
	
	//Retourne l'id de la dernière insertion
	function insert_id($query)
	{
		return mysql_insert_id();
	}
	
	//Retourne le nombre d'année entre la date et aujourd'hui.
	function date_diff($field)
	{
		return '(YEAR(CURRENT_DATE) - YEAR(' . $field . ')) - (RIGHT(CURRENT_DATE, 5) < RIGHT(' . $field . ', 5))';
	}
	
	//Déchargement mémoire.
	function query_close($result)
	{
		if (is_resource($result))
			return mysql_free_result($result);
	}

	//Fermeture de la connexion mysql ouverte.
	function close()
	{
		if ($this->connected) // si la connexion est établie
		{
			$this->connected = false;
			return mysql_close($this->link); // on ferme la connexion ouverte.
		}
	}
	
	//Liste les champs d'une table.
	function list_fields($table)
	{
		if (!empty($table))
		{
			$array_fields_name = array();
			$result = $this->query_while ("SHOW COLUMNS FROM " . $table . " FROM `" . $this->sql_base . "`", __LINE__, __FILE__);
			while ($row = mysql_fetch_row($result))
				$array_fields_name[] = $row[0];
			return $array_fields_name;
		}
		else
			return array();
	}
	
	//Liste les tables + infos.
	function list_tables()
	{
		$array_tables = array();
		
		$result = $this->query_while ("SHOW TABLE STATUS FROM `" . $this->sql_base . "` LIKE '" . PREFIX . "%'", __LINE__, __FILE__);
		while ($row = mysql_fetch_row($result))
		{
			$array_tables[$row[0]] = array(
				'name' => $row[0],
				'engine' => $row[1],
				'row_format' => $row[3],
				'rows' => $row[4],
				'data_length' => $row[6],
				'index_lenght' => $row[8],
				'data_free' => $row[9],
				'collation' => $row[14],
				'auto_increment' => $row[10],
				'create_time' => $row[11],
				'update_time' => $row[12]
			);
		}
		return $array_tables;
	}
		
	//Parsage d'un fichier SQL => exécution des requêtes.
	function parse($file_path, $tableprefix = '')
	{
		$handle_sql = @fopen($file_path, 'r');
		if ($handle_sql)
		{
			$req = '';
			while (!feof($handle_sql))
			{
				$sql_line = trim(fgets($handle_sql));
				//Suppression des lignes vides, et des commentaires.
				if (!empty($sql_line) && substr($sql_line, 0, 2) !== '--')
				{
					//On vérifie si la ligne est une commande SQL.
					if (substr($sql_line, -1) == ';')
					{
						if (empty($req))
							$req = $sql_line;
						else
							$req .= ' ' . $sql_line;
							
						if (!empty($tableprefix))
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
	function display_request()
	{
		return $this->req;
	}
	
	//Coloration syntaxique du SQL
	function highlight_query($query)
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
	function indent_query($query)
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
	
	//Version du SGBD
	function get_dbms_version()
	{
		return 'MySQL ' . mysql_get_server_info();
	}
	
	//Listage des base de données présentes sur le SGBD courant
	function list_databases()
	{
		$db_list = mysql_list_dbs($this->link);
		
		$result = array();
		
		while ($row = mysql_fetch_assoc($db_list))
			$result[] = $row['Database'];
		
		return $result;
	}

	//Création d'une base de données
	function create_database($db_name)
	{
        return mysql_query( "CREATE DATABASE " . str_replace('-', '_', url_encode_rewrite($db_name)));
	}

	//Requête query + fetch_array
	function query_fetch($query, $errline, $errfile, $type = MYSQL_BOTH)
	{
		$ressource = mysql_query($query, $this->link) or $this->_error($query, 'Invalid SQL request', $errline, $errfile);
		$result = $this->fetch_array($ressource, $type);
		$this->query_close($ressource);
		$this->req++;

		return $result;
	}
	
	//Balayage du retour de la requête sous forme de tableau indexé, associatif ou les deux
	function fetch_array($result, $type=MYSQL_BOTH)
	{
		return mysql_fetch_array($result, $type);
	}
	
	## Private Methods ##
	//Gestion des erreurs.
	function _error($query, $errstr, $errline = '', $errfile = '')
	{
		global $Errorh;
		
		//Enregistrement dans le log d'erreur.
        $too_many_connections = strpos($errstr, 'already has more than \'max_user_connections\' active connections') > 0;
		$Errorh->handler($errstr . '<br /><br />' . $query . '<br /><br />' . mysql_error(), E_USER_ERROR, $errline, $errfile, false, !$too_many_connections);
        redirect(PATH_TO_ROOT . '/member/toomanyconnections.php');
	}
	
	
	## Private attributes ##
	var $link; //Lien avec la base de donnée.
	var $req = 0; //Nombre de requêtes.
	var $connected = false;
	var $sql_base = '';
}
?>