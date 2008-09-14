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

if( defined('PHPBOOST') !== true ) exit;

if( empty($check_update) )
{
    #######Taches de maintenance#######
    $yesterday_timestamp = time() - 86400;
	
    //Pose d'un verrou supplémentaire
    $Sql->Query_inject("INSERT INTO ".PREFIX."stats (stats_year, stats_month, stats_day, nbr, pages, pages_detail) VALUES ('" . gmdate_format('Y', $yesterday_timestamp, TIMEZONE_SYSTEM) . "', '" . gmdate_format('m', $yesterday_timestamp, TIMEZONE_SYSTEM) . "', '" . gmdate_format('d', $yesterday_timestamp, TIMEZONE_SYSTEM) . "', '', '', '')", __LINE__, __FILE__);
    $last_stats = $Sql->Sql_insert_id("SELECT MAX(id) FROM ".PREFIX."stats"); //Récupération de la dernière insertion.
	
    #######Statistiques#######
    $Sql->Query_inject("UPDATE ".PREFIX."stats_referer SET yesterday_visit = today_visit", __LINE__, __FILE__);
    $Sql->Query_inject("UPDATE ".PREFIX."stats_referer SET today_visit = 0, nbr_day = nbr_day + 1", __LINE__, __FILE__);
    $Sql->Query_inject("DELETE FROM ".PREFIX."stats_referer WHERE last_update < '" . (time() - 604800) . "'", __LINE__, __FILE__); //Suppression des entrées non mise à jour depuis 1 semaine.
	
    //Visites et pages vues.
    $pages_displayed = pages_displayed();
    @delete_file(PATH_TO_ROOT . '/cache/pages.txt');

    //Vidage de la table des visites de la journée.
    $total_visit = $Sql->Query("SELECT total FROM ".PREFIX."compteur WHERE id = 1", __LINE__, __FILE__);
    $Sql->Query_inject("DELETE FROM ".PREFIX."compteur WHERE id <> 1", __LINE__, __FILE__);
    $Sql->Query_inject("UPDATE ".PREFIX."compteur SET time = '" . gmdate_format('Y-m-d', time(), TIMEZONE_SYSTEM) . "', total = 1 WHERE id = 1", __LINE__, __FILE__); 	//Remet le compteur à 1.  
    $Sql->Query_inject("INSERT INTO ".PREFIX."compteur (ip, time, total) VALUES('" . USER_IP . "', '" . gmdate_format('Y-m-d', time(), TIMEZONE_SYSTEM) . "', '0')", __LINE__, __FILE__); //Insère l'utilisateur qui a déclanché les requêtes de changement de jour.

    //Mise à jour des stats.
    $Sql->Query_inject("UPDATE ".PREFIX."stats SET nbr = '" . $total_visit . "', pages = '" . array_sum($pages_displayed) . "', pages_detail = '" . addslashes(serialize($pages_displayed)) . "' WHERE id = '" . $last_stats . "'", __LINE__, __FILE__);

	//Suppression des sessions périmées
	$Session->session_garbage_collector();

	//Suppression des images du cache des formules mathématiques, supprimé chaque semaine.
	$rep = PATH_TO_ROOT . '/images/maths/';
	$dh = @opendir($rep);
	$week = 3600*24*7;
	while( !is_bool($fichier = readdir($dh)) )
	{	
		if( preg_match('`\.png$`', $fichier) )
		{
			if( (time() - filemtime($rep . $fichier)) > $week ) //Une semaine avant péremption
				@unlink($rep . $fichier);
		}
	}	
	@closedir($dh); //On ferme le dossier
    
    //Check for availables updates
    require_once(PATH_TO_ROOT . '/kernel/framework/core/updates.class.php');
    $updates = new Updates();
    
	//Parcours des modules afin d'executer les actions journalières.
	require_once(PATH_TO_ROOT . '/kernel/framework/modules/modules.class.php');
	$modulesLoader = new Modules();
	$modules = $modulesLoader->get_available_modules('on_changeday');
	foreach($modules as $module)
	{
		if( $MODULES[strtolower($module->id)]['activ'] == '1' ) //Module activé
			$module->functionnality('on_changeday');
	}

	//Suppression des membres ayant dépassé le délai d'unactivation max, si non activation par admin.
	$CONFIG_MEMBER['delay_unactiv_max'] = ($CONFIG_MEMBER['delay_unactiv_max'] * 3600 * 24); //On passe en secondes.
	if( !empty($CONFIG_MEMBER['delay_unactiv_max']) && $CONFIG_MEMBER['activ_mbr'] != 2 )
		$Sql->Query_inject("DELETE FROM ".PREFIX."member WHERE timestamp < '" . (time() - $CONFIG_MEMBER['delay_unactiv_max']) . "' AND user_aprob = 0", __LINE__, __FILE__);
    
    $rep = PATH_TO_ROOT . '/cache/';
    $dh = @opendir($rep);
    while( !is_bool($fichier = readdir($dh)) )
    {
        if( preg_match('`\.png$`', $fichier) )
            @unlink($rep . $fichier);
    }
    @closedir($dh); //On ferme le dossier
    
	//Vidage des entrées des inscriptions
	if( $CONFIG_MEMBER['verif_code'] == '1' )
		$Sql->Query_inject("DELETE FROM ".PREFIX."verif_code WHERE timestamp < '" . (time() - (3600 * 24)) . "'", __LINE__, __FILE__);

    //Optimisations des tables
    $array_tables = $Sql->Sql_list_tables();
    foreach($array_tables as $key => $table)
        $Sql->Query_inject("OPTIMIZE TABLE ".PREFIX . $table, __LINE__, __FILE__);
}
?>