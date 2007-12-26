<?php
/*##################################################
 *                                changeday.php
 *                            -------------------
 *   begin                : April 10, 2005
 *   copyright          : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
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

if( defined('PHP_BOOST') !== true ) exit;

//Vérification pour empêcher une double mise à jour.		
$compteur = $sql->query_array('compteur', $sql->sql_year('time') . ' AS Y', $sql->sql_month('time') . ' AS M', $sql->sql_day('time') . ' AS D', 'total', "WHERE id = 1", __LINE__, __FILE__);
if( $compteur['D'] != date('j') )
{
	#######Taches de maintenance#######
	//Suppression des sessions périmées
	$session->session_garbage_collector();

	//Suppression des images du cache des formules mathématiques, supprimé chaque semaine.
	$rep = '../images/maths/';
	$dh = @opendir($rep);
	$week = 3600*24*7;
	while ( !is_bool($fichier = readdir($dh)) )
	{	
		if( preg_match('`\.png$`', $fichier) )
		{
			if( (time() - filemtime($rep . $fichier)) > $week ) //Une semaine avant péremption
				@unlink($rep . $fichier);
		}
	}	
	@closedir($dh); //On ferme le dossier

	//Parcours des modules afin d'executer les actions journalières.
	$array_changeday = array();
	$result = $sql->query_while("SELECT name 
	FROM ".PREFIX."modules
	WHERE activ = 1", __LINE__, __FILE__);
	while( $row = $sql->sql_fetch_assoc($result) )
	{	
		if( is_file('../' . $row['name'] . '/' . $row['name'] . '_changeday.php') ) //On regarde si le module a des actions journalières à executer.
			$array_changeday[$row['name']] = '../' . $row['name'] . '/' . $row['name'] . '_changeday.php';
	}
	$sql->close($result);
	foreach($array_changeday as $name => $include)
		@include_once($include);

	//Suppression des membres ayant dépassé le délai d'unactivation max, si non activation par admin.
	$CONFIG_MEMBER['delay_unactiv_max'] = ($CONFIG_MEMBER['delay_unactiv_max'] * 3600 * 24); //On passe en secondes.
	if( !empty($CONFIG_MEMBER['delay_unactiv_max']) && $CONFIG_MEMBER['activ_mbr'] != 2 )
		$sql->query_inject("DELETE FROM ".PREFIX."member WHERE timestamp < '" . (time() - $CONFIG_MEMBER['delay_unactiv_max']) . "' AND user_aprob = 0", __LINE__, __FILE__);

	//Vidage des entrées des inscriptions
	if( $CONFIG_MEMBER['verif_code'] == '1' )
		$sql->query_inject("DELETE FROM ".PREFIX."verif_code WHERE timestamp < '" . (time() - (3600 * 24)) . "'", __LINE__, __FILE__);
			
	//Vidage de la table des visites de la journée.
	$sql->query_inject("DELETE FROM ".PREFIX."compteur WHERE id != 1", __LINE__, __FILE__);
	$sql->query_inject("UPDATE ".PREFIX."compteur SET time = " . $sql->sql_now() . ", total = 1 WHERE id = 1", __LINE__, __FILE__); //Remet le compteur à 1.	
	$sql->query_inject("INSERT INTO ".PREFIX."compteur (ip, time, total) VALUES('" . USER_IP . "', " . $sql->sql_now() . ", '0')", __LINE__, __FILE__); //Insère l'utilisateur qui a déclanché les requêtes de changement de jour.

	//Optimisations des tables
	$array_tables = $sql->sql_list_tables();
	foreach($array_tables as $key => $table)
		$sql->query_inject("OPTIMIZE TABLE ".PREFIX . $table, __LINE__, __FILE__);
		
	#######Statistiques#######	
	$rep = '../cache/';	
	$dh = @opendir($rep);
	while ( !is_bool($fichier = readdir($dh)) )
	{	
		if( preg_match('`\.png$`', $fichier) )
			@unlink($rep . $fichier);
	}	
	@closedir($dh); //On ferme le dossier
	$sql->query_inject("INSERT INTO ".PREFIX."stats (stats_year, stats_month, stats_day, nbr) VALUES ('" . $compteur['Y'] . "', '" . $compteur['M'] . "', '" . $compteur['D'] . "', '" . $compteur['total'] . "')", __LINE__, __FILE__);

	//Inscription du nouveau jour dans le fichier en cache.
	$cache->generate_file('day');
}
?>