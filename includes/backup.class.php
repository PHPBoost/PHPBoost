<?php
/*##################################################
 *                             backup.class.php
 *                            -------------------
 *   begin                : July 23, 2006
 *   copyright          : (C) 2005 Benoît Sautel / Régis Viarre
 *   email                : ben.popeye@gmail.com / crowkait@phpboost.com
 *
 *   
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 * 
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

//Fonction d'importation/exportation de base de donnée.
class backup
{
	var $tables = array(); //Liste des tables.
	var $save = ''; //Sauvegarde
	var $values = array(); //tableau temporaire
	var $mysql_base = ''; //base sur laquelle on travaille
	var $array_req = ''; //Requête pour l'importation
		
	//On modifie le temps d'exécution maximal si le serveur le permet
	function backup()
	{
		$this->list_table(); //Liste toutes les tables de PHPBoost.
		@set_time_limit(600);
	}
		
	//On crée le tableau qui contient les tables
	function list_table()
	{
		global $sql;
		
		if( $this->tables === array() )
			$this->tables = $sql->sql_list_tables();
	}
	
	//Suppression  des tables
	function drop_tables($table_list = array())
	{
		$selected_tables =  array();
		$all_tables = count($table_list) == 0 ? true : false;
		foreach($this->tables as $id => $properties )
		{
			if( in_array($properties['name'], $table_list) || $all_tables )
				$selected_tables[] = $properties['name'];
		}
		$this->save .= 'DROP TABLE IF EXISTS ' . implode(', ', $selected_tables) . ';' . "\n";
	}
	
	//Création des tables (structure)
	function create_tables($table_list = array())
	{
		global $sql;
		
		$all_tables = count($table_list) == 0 ? true : false;
			
		foreach($this->tables as $id => $properties)
		{
			if( in_array($properties['name'], $table_list) || $all_tables )
			{
				$result = $sql->query_while('SHOW CREATE TABLE ' . $properties['name'], __LINE__, __FILE__);
				while($row = $sql->sql_fetch_row($result))
					$this->save .=  $row[1] . ';' . "\n\n";
				$sql->close($result);
			}				
		}
	}
	
	//Requêtes d'insertion
	function insert_values($tables = array())
	{
		global $sql;
		
		$all_tables = count($tables) == 0 ? true : false;
		
		foreach($this->tables as $id => $table_info)
		{
			if( $all_tables || in_array($table_info['name'], $tables) ) //Table demandée
			{
				$rows_number = $sql->query("SELECT COUNT(*) FROM " . $table_info['name'], __LINE__, __FILE__);
				if( $rows_number > 0 )
				{
					$this->save .= "INSERT INTO " . $table_info['name'] . " (`";
					$this->save .= implode('`, `', $sql->sql_list_fields($table_info['name']));
					$this->save .= "`) VALUES ";
					
					$i = 1;
					$list_fields = $sql->sql_list_fields($table_info['name']);
					$result = $sql->query_while('SELECT * FROM ' . $table_info['name'], __LINE__, __FILE__);			
					while($row = $sql->sql_fetch_row($result))
					{
						if( $i % 10 == 0 ) //Toutes les 10 entrées on reforme une requête
						{
							$this->save .= ";\n";
							$this->save .= "INSERT INTO " . $table_info['name'] . " (";
							$this->save .= implode(', ', $list_fields);
							$this->save .= ") VALUES ";
						}
						elseif( $i > 1 )
							$this->save .= ", ";
						$this->save .= "(";
						foreach( $row as $key => $value )
							$row[$key] = '\'' . str_replace(chr(13), '\r', str_replace(chr(10), '\n', str_replace('\\', '\\\\', str_replace("'", "''", $value)))) . '\'';
						$this->save .= implode(', ', $row) . ")";
						$i++;
					}
					$this->save .= ";\n";
					$sql->close($result);
				}
			}
		}
	}
	
	//Création du fichier
	function export_file($file_path)
	{
		$file = @fopen($file_path, 'w+');	
		fwrite($file, $this->save); //On stocke le tableau dans le fichier de données
		fclose($file);
	}
	
	function optimize_tables($table_array) //Optimisation des tables
	{		
		global $sql;
		
		if( count($table_array) != 0 )
			$sql->query_inject("OPTIMIZE TABLE " . implode(', ', $table_array), __LINE__, __FILE__);
	}
	
	//Réparation des tables
	function repair_tables($table_array)
	{
		global $sql;
		
		if( count($table_array) != 0 )
			$sql->query_inject("REPAIR TABLE " . implode(', ', $table_array), __LINE__, __FILE__);
	}
	
	//Coloration syntaxique du SQL
	function sql_highlight_string($query)
	{
		$query = ' ' . strtolower($query) . ' ';
		$query = preg_replace('`(' . implode('|', array_map('preg_quote', array('*', '=', ',', '!=', '>', '<', '.'))) . ')+`', '<span style="color:#FF00FF;">$1</span>', $query);
		$query = preg_replace('`\`(.*)\``U', '<span style="color:#008000;">$1</span>', $query);	
		$key_words = array('select', 'update', 'delete', 'insert into', 'truncate', 'alter', 'table', 'status', 'set', 'drop', 'from', 'values', 'count', 'distinct', 'having', 'left', 'right', 'join', 'natural', 'outer', 'inner', 'between', 'where', 'group by', 'order by', 'limit', 'or', 'and', 'not', 'in', 'as', 'on', 'all', 'any', 'like', 'concat', 'substring', 'collate', 'collation', 'primary', 'key', 'default', 'null', 'exists', 'status', 'show');
		$query = preg_replace_callback('`\b(' . implode('|', $key_words) . ')+\b`', create_function('$matches','return strtoupper($matches[1]);'), $query);
		$query = preg_replace('`(?<![\r\n\t\f])(' . implode('|', array('SELECT', 'UPDATE', 'INSERT INTO', 'FROM', 'LEFT JOIN', 'RIGHT JOIN', '[^ ]JOIN', 'NATURAL', 'WHERE', 'ORDER BY', 'GROUP BY', 'LIMIT', 'HAVING', 'OUTER', 'CROSS', 'UNION')) . ')+`', "\r\n" . '$1$2', $query);
		$query = preg_replace('`\b(' . implode('|', array_map('strtoupper', $key_words)) . ')+\b`', ' <span style="color:#990099;">$1</span> ', $query);
		$query = preg_replace('`\'(.+)\'`U', '<span style="color:#008000;">\'$1\'</span>', $query);
		$query = preg_replace('`(?<![\'#])\b([0-9]+)\b(?!\')`', '<span style="color:#008080;">$1</span>', $query);
		$query = preg_replace('`(\r\n){2,}`', "\r\n", $query);

		return nl2br($query);
	}
}

?>