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
//Base non trouvée et impossible à créer
define('DB_CONFIG_ERROR_DATABASE_NOT_FOUND', 2);
//Une installation avec ce préfixe existe déjà
define('DB_CONFIG_ERROR_TABLES_ALREADY_EXIST', 3);

//Function which returns a result code
function check_database_config($host, $login, $password, $database_name, $tables_prefix)
{
	require_once('../kernel/framework/db/mysql.class.php');
	require_once('../kernel/framework/core/errors.class.php');
	
	//Lancement de la classe d'erreur (nécessaire pour lancer la gestion de base de données)
	$Errorh = new Errors;
	$Sql = new Sql(false);
	
	//Tentative de connexion à la base de données
	switch($Sql->Sql_connect($host, $login, $password, $database_name, ERRORS_MANAGEMENT_BY_RETURN))
	{
		//La connexion a échoué, l'hôte ou les identifiants sont erronés
		case CONNECTION_FAILED:
			return DB_CONFIG_ERROR_CONNECTION_TO_DBMS;
		//La base de données n'existe pas
		case UNEXISTING_DATABASE:
			//Tentative de création de la base de données
			$Sql->create_database();
	}
}

?>