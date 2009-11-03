<?php
/*##################################################
 *                                functions.php
 *                            -------------------
 *   begin                : September 29, 2008
 *   copyright            : (C) 2008 	Sautel Benoit
 *   email                : ben.popeye@phpboost.com
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

//Constants used in the function above
//Aucune erreur
define('DB_CONFIG_SUCCESS', 0);
//Hôte introuvable ou login/mot de passe incorrect(s)
define('DB_CONFIG_ERROR_CONNECTION_TO_DBMS', 1);
//Base non trouvée mais créée
define('DB_CONFIG_ERROR_DATABASE_NOT_FOUND_BUT_CREATED', 2);
//Base non trouvée et impossible à créer
define('DB_CONFIG_ERROR_DATABASE_NOT_FOUND_AND_COULDNOT_BE_CREATED', 3);
//Une installation avec ce préfixe existe déjà
define('DB_CONFIG_ERROR_TABLES_ALREADY_EXIST', 4);
//Erreur inconnue
define('DB_UNKNOW_ERROR', -1);

//Function which returns a result code
function check_database_config(&$host, &$login, &$password, &$database_name, $tables_prefix)
{
	
	

	//Lancement de la classe d'erreur (nécessaire pour lancer la gestion de base de données)
	$Errorh = new Errors;

	$database_name = Sql::clean_database_name($database_name);

	
	$db_connection = new MySQLDBConnection($host, $login, $password);
	try
	{
		$db_connection->connect();	
	}
	catch (DBConnectionException $ex)
	{
		return DB_CONFIG_ERROR_CONNECTION_TO_DBMS;
	}
	
	try
	{
		$db_connection->select_database($database_name);
	}
	catch (UnexistingDatabaseException $ex)
	{
		$Sql = new Sql($db_connection, $database_name);
		
		//Tentative de création de la base de données
		$database_name = $Sql->create_database($database_name);

		//On regarde si elle a pu être traitée
		$databases_list = $Sql->list_databases();

		$db_connection->disconnect();

		if (in_array($database_name, $databases_list))
		{
			return DB_CONFIG_ERROR_DATABASE_NOT_FOUND_BUT_CREATED;
		}
		else
		{
			return DB_CONFIG_ERROR_DATABASE_NOT_FOUND_AND_COULDNOT_BE_CREATED;
		}
	}
	
	$Sql = new Sql($db_connection, $database_name);
	define('PREFIX', $tables_prefix);
	$tables_list = $Sql->list_tables();

	//We close the database connection
	$db_connection->disconnect();

	//Is PHPBoost already installed in this database?
	if (!empty($tables_list[$tables_prefix . 'member']) || !empty($tables_list[$tables_prefix . 'configs']))
	{
		return DB_CONFIG_ERROR_TABLES_ALREADY_EXIST;
	}
	else
	{
		return DB_CONFIG_SUCCESS;
	}
}

function load_db_connection()
{
	global $Sql, $Errorh;

	
    
	$Errorh = new Errors;
    return $Sql;
}

?>