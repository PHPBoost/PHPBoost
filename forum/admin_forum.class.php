<?php
/*##################################################
 *                               admin_forum.class.php
 *                            -------------------
 *   begin                : December 20, 2007
 *   copyright            : (C) 2007 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *
###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
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

define('FORUM_CAT_INCLUDED', true);
define('FORUM_CAT_NO_INCLUDED', false);

class Admin_forum
{	
	//Constructeur
	function Admin_forum() 
	{
	}
	
	//Déplacement vers le haut/bas de la catégorie.
	function move_updown_cat($id, $move)
	{
		global $Sql, $CAT_FORUM, $Cache;
		
		$list_parent_cats = $this->get_parent_list($idcat); //Récupère la liste des parents de la catégorie.
		
		$to = 0;
		if ($move == 'up')
		{	
			//Même catégorie
			$switch_id_cat = $Sql->query("SELECT id FROM " . PREFIX . "forum_cats
			WHERE '" . $CAT_FORUM[$id]['id_left'] . "' - id_right = 1", __LINE__, __FILE__);		
			if (!empty($switch_id_cat))
			{
				//On monte la catégorie à déplacer, on lui assigne des id négatifs pour assurer l'unicité.
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = - id_left + '" . ($CAT_FORUM[$switch_id_cat]['id_right'] - $CAT_FORUM[$switch_id_cat]['id_left'] + 1) . "', id_right = - id_right + '" . ($CAT_FORUM[$switch_id_cat]['id_right'] - $CAT_FORUM[$switch_id_cat]['id_left'] + 1) . "' WHERE id_left BETWEEN '" . $CAT_FORUM[$id]['id_left'] . "' AND '" . $CAT_FORUM[$id]['id_right'] . "'", __LINE__, __FILE__);
				//On descend la catégorie cible.
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = id_left + '" . ($CAT_FORUM[$id]['id_right'] - $CAT_FORUM[$id]['id_left'] + 1) . "', id_right = id_right + '" . ($CAT_FORUM[$id]['id_right'] - $CAT_FORUM[$id]['id_left'] + 1) . "' WHERE id_left BETWEEN '" . $CAT_FORUM[$switch_id_cat]['id_left'] . "' AND '" . $CAT_FORUM[$switch_id_cat]['id_right'] . "'", __LINE__, __FILE__);
				
				//On rétablit les valeurs absolues.
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = - id_left WHERE id_left < 0", __LINE__, __FILE__);
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_right = - id_right WHERE id_right < 0", __LINE__, __FILE__);	
				
				$Cache->Generate_module_file('forum');
			}		
			elseif (!empty($list_parent_cats) )
			{
				//Changement de catégorie.
				$to = $Sql->query("SELECT id FROM " . PREFIX . "forum_cats
				WHERE id_left < '" . $CAT_FORUM[$id]['id_left'] . "' AND level = '" . ($CAT_FORUM[$id]['level'] - 1) . "' AND
				id NOT IN (" . $list_parent_cats . ")
				ORDER BY id_left DESC" . 
				$Sql->limit(0, 1), __LINE__, __FILE__);
			}
		}
		elseif ($move == 'down')
		{
			//Doit-on changer de catégorie parente ou non ?
			$switch_id_cat = $Sql->query("SELECT id FROM " . PREFIX . "forum_cats
			WHERE id_left - '" . $CAT_FORUM[$id]['id_right'] . "' = 1", __LINE__, __FILE__);
			if (!empty($switch_id_cat))
			{
				//On monte la catégorie à déplacer, on lui assigne des id négatifs pour assurer l'unicité.
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = - id_left - '" . ($CAT_FORUM[$switch_id_cat]['id_right'] - $CAT_FORUM[$switch_id_cat]['id_left'] + 1) . "', id_right = - id_right - '" . ($CAT_FORUM[$switch_id_cat]['id_right'] - $CAT_FORUM[$switch_id_cat]['id_left'] + 1) . "' WHERE id_left BETWEEN '" . $CAT_FORUM[$id]['id_left'] . "' AND '" . $CAT_FORUM[$id]['id_right'] . "'", __LINE__, __FILE__);
				//On descend la catégorie cible.
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = id_left - '" . ($CAT_FORUM[$id]['id_right'] - $CAT_FORUM[$id]['id_left'] + 1) . "', id_right = id_right - '" . ($CAT_FORUM[$id]['id_right'] - $CAT_FORUM[$id]['id_left'] + 1) . "' WHERE id_left BETWEEN '" . $CAT_FORUM[$switch_id_cat]['id_left'] . "' AND '" . $CAT_FORUM[$switch_id_cat]['id_right'] . "'", __LINE__, __FILE__);
				
				//On rétablit les valeurs absolues.
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = - id_left WHERE id_left < 0", __LINE__, __FILE__);
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_right = - id_right WHERE id_right < 0", __LINE__, __FILE__);
				
				$Cache->Generate_module_file('forum');
			}
			elseif (!empty($list_parent_cats) )
			{
				//Changement de catégorie.
				$to = $Sql->query("SELECT id FROM " . PREFIX . "forum_cats
				WHERE id_left > '" . $CAT_FORUM[$id]['id_left'] . "' AND level = '" . ($CAT_FORUM[$id]['level'] - 1) . "'
				ORDER BY id_left" . 
				$Sql->limit(0, 1), __LINE__, __FILE__);
				
			}
		}

		if (!empty($to)) //Changement de catégorie possible?
		{
			//On vérifie si la catégorie contient des sous forums.
			$nbr_cat = (($CAT_FORUM[$id]['id_right'] - $CAT_FORUM[$id]['id_left'] - 1) / 2) + 1;
			$list_cats = $this->get_child_list($id); //Sous forums du forum à déplacer.
	
			//Précaution pour éviter erreur fatale, cas impossible si cohérence de l'arbre respectée.
			if (empty($list_cats))
				return false;
						
			## Dernier topic des enfants du forum à supprimer ##
			$list_parent_cats_to = $this->get_parent_list($to, FORUM_CAT_INCLUDED); //Forums parent du forum cible.
			if (empty($list_parent_cats_to))
				$clause_parent_cats_to = " id = '" . $to . "'";
			else
				$clause_parent_cats_to = " id IN (" . $list_parent_cats_to . ")";
				
			########## Suppression ##########
			//On supprime virtuellement (changement de signe des bornes) les enfants.
			$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = - id_left, id_right = - id_right WHERE id IN (" . $list_cats . ")", __LINE__, __FILE__);								
			//On modifie les bornes droites des parents.
			if (!empty($list_parent_cats))
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_right = id_right - '" . ( $nbr_cat*2) . "' WHERE id IN (" . $list_parent_cats . ")", __LINE__, __FILE__);
			
			//On réduit la taille de l'arbre du nombre de forum supprimé à partir de la position de celui-ci.
			$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = id_left - '" . ($nbr_cat*2) . "', id_right = id_right - '" . ($nbr_cat*2) . "' WHERE id_left > '" . $CAT_FORUM[$id]['id_right'] . "'", __LINE__, __FILE__);

			########## Ajout ##########
			//On modifie les bornes droites des parents de la cible.
			$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_right = id_right + '" . ($nbr_cat*2) . "' WHERE " . $clause_parent_cats_to, __LINE__, __FILE__);

			//On augmente la taille de l'arbre du nombre de forum supprimé à partir de la position du forum cible.
			$array_parents_cats = explode(', ', $list_parent_cats);
			if ($CAT_FORUM[$id]['id_left'] > $CAT_FORUM[$to]['id_left'] && !in_array($f_to, $array_parents_cats) ) //Direction forum source -> forum cible, et source non incluse dans la cible.
			{	
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = id_left + '" . ($nbr_cat*2) . "', id_right = id_right + '" . ($nbr_cat*2) . "' WHERE id_left > '" . $CAT_FORUM[$to]['id_right'] . "'", __LINE__, __FILE__);						
				$limit = $CAT_FORUM[$to]['id_right'];
				$end = $limit + ($nbr_cat*2) - 1;
			}
			else
			{	
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = id_left + '" . ($nbr_cat*2) . "', id_right = id_right + '" . ($nbr_cat*2) . "' WHERE id_left > '" . ($CAT_FORUM[$to]['id_right'] - ($nbr_cat*2)) . "'", __LINE__, __FILE__);
				$limit = $CAT_FORUM[$to]['id_right'] - ($nbr_cat*2);
				$end = $limit + ($nbr_cat*2) - 1;						
			}	

			//On replace les forums supprimés virtuellement.
			$array_sub_cats = explode(', ', $list_cats);
			$z = 0;
			for ($i = $limit; $i <= $end; $i = $i + 2)
			{
				$id_left = $limit + ($CAT_FORUM[$array_sub_cats[$z]]['id_left'] - $CAT_FORUM[$id]['id_left']);
				$id_right = $end - ($CAT_FORUM[$id]['id_right'] - $CAT_FORUM[$array_sub_cats[$z]]['id_right']);
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = '" . $id_left . "', id_right = '" . $id_right . "' WHERE id = '" . $array_sub_cats[$z] . "'", __LINE__, __FILE__);
				$z++;
			}
					
			$Cache->Generate_module_file('forum'); //Régénération du cache.
			
			$this->update_last_topic_id($id); //On met à jour l'id du dernier topic.
			$this->update_last_topic_id($to); //On met à jour l'id du dernier topic.
		}
		
		return true;
	}
	
	//Suppression d'une catégorie.
	function del_cat($idcat, $confirm_delete)
	{
		global $Sql, $CAT_FORUM, $Cache;
		
		//On vérifie si la catégorie contient des sous forums.
		$nbr_sub_cat = (($CAT_FORUM[$idcat]['id_right'] - $CAT_FORUM[$idcat]['id_left'] - 1) / 2);
		
		if ($confirm_delete) //Confirmation de suppression, on supprime dans la bdd et on rétabli l'arbre intervallaire.
		{
			$first_parent = $Sql->query("SELECT id FROM " . PREFIX . "forum_cats WHERE id_left < '" . $CAT_FORUM[$idcat]['id_left'] . "' AND id_right > " . $CAT_FORUM[$idcat]['id_right'] . " ORDER BY id_left DESC " . $Sql->limit(0, 1), __LINE__, __FILE__);
			$list_parent_cats = $this->get_parent_list($idcat); //Récupère la liste des parents de la catégorie.
			
			$nbr_del = $CAT_FORUM[$idcat]['id_right'] - $CAT_FORUM[$idcat]['id_left'] + 1;
			if (!empty($list_parent_cats))
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_right = id_right - '" . $nbr_del . "' WHERE id IN (" . $list_parent_cats . ")", __LINE__, __FILE__);
			
			$Sql->query_inject("DELETE FROM " . PREFIX . "forum_cats WHERE id_left BETWEEN '" . $CAT_FORUM[$idcat]['id_left'] . "' AND '" . $CAT_FORUM[$idcat]['id_right'] . "'", __LINE__, __FILE__);	
			$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = id_left - '" . $nbr_del . "', id_right = id_right - '" . $nbr_del . "' WHERE id_left > '" . $CAT_FORUM[$idcat]['id_right'] . "'", __LINE__, __FILE__);
			
			$Sql->query_inject("DELETE FROM " . PREFIX . "forum_msg WHERE idtopic IN (
			SELECT id FROM " . PREFIX . "forum_topics WHERE idcat = '" . $idcat . "')", __LINE__, __FILE__); //On supprime les messages de tout les sujets.
			
			$Sql->query_inject("DELETE FROM " . PREFIX . "forum_topics WHERE idcat = '" . $idcat . "'", __LINE__, __FILE__); //On supprime les topics de la catégorie.
				
			$Cache->Generate_module_file('forum'); //Regénération du cache
			$Cache->load('forum', RELOAD_CACHE); //Rechargement du cache
			
			$this->update_last_topic_id($first_parent); //On met à jour l'id du dernier topic.
		}
		else //Déplacement.
		{
			//Déplacement de sous forums.
			$f_to = retrieve(POST, 'f_to', 0);
			$f_to = $Sql->query("SELECT id FROM " . PREFIX . "forum_cats WHERE id = '" . $f_to . "' AND id_left NOT BETWEEN '" . $CAT_FORUM[$idcat]['id_left'] . "' AND '" . $CAT_FORUM[$idcat]['id_right'] . "'", __LINE__, __FILE__);
			
			//Déplacement de topics.
			$t_to = retrieve(POST, 't_to', 0);
			$t_to = $Sql->query("SELECT id FROM " . PREFIX . "forum_cats WHERE id = '" . $t_to . "' AND id <> '" . $idcat . "'", __LINE__, __FILE__);
			
			//Déplacement des topics dans la catégorie sélectionnée.
			if (!empty($t_to))
			{
				//On va chercher la somme du nombre de messages dans la table topics
				$nbr_msg = $Sql->query("SELECT SUM(nbr_msg) FROM " . PREFIX . "forum_topics WHERE idcat = '" . $idcat . "'", __LINE__, __FILE__);
				$nbr_msg = !empty($nbr_msg) ? $nbr_msg : 0;
				//Nombre de topics.
				$nbr_topic = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "forum_topics WHERE idcat = '" . $idcat . "'", __LINE__, __FILE__); 
				$nbr_topic = !empty($nbr_topic) ? $nbr_topic : 0;
				
				//On déplace les topics dans le nouveau forum.
				$Sql->query_inject("UPDATE " . PREFIX . "forum_topics SET idcat = '" . $t_to . "' WHERE idcat = '" . $idcat . "'", __LINE__, __FILE__);

				//On met à jour le nouveau forum.
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET nbr_msg = nbr_msg + " . $nbr_msg . ", nbr_topic = nbr_topic + " . $nbr_topic . " WHERE id = '" . $t_to . "'", __LINE__, __FILE__);
				
				//On supprime l'ancien forum.
				$Sql->query_inject("DELETE FROM " . PREFIX . "forum_cats WHERE id = '" . $idcat . "'", __LINE__, __FILE__);
			}
			
			//Présence de sous-forums => déplacement de ceux-ci.
			if ($nbr_sub_cat > 0)
			{
				$list_sub_cats = $this->get_child_list($idcat, FORUM_CAT_NO_INCLUDED); //Sous forums du forum.
				$list_parent_cats = $this->get_parent_list($idcat);  //Forums parent du forum.
				$list_parent_cats_to = $this->get_parent_list($f_to, FORUM_CAT_INCLUDED);  //Forums parents du forum cible.
				
				//Précaution pour éviter erreur fatale, cas impossible si cohérence de l'arbre respectée.
				if (empty($list_sub_cats))
					return false;
					
				########## Suppression ##########
				//On supprime l'ancien forum.
				$Sql->query_inject("DELETE FROM " . PREFIX . "forum_cats WHERE id = '" . $idcat . "'", __LINE__, __FILE__);
				
				//On supprime virtuellement (changement de signe des bornes) les enfants.
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = - id_left, id_right = - id_right WHERE id IN (" . $list_sub_cats . ")", __LINE__, __FILE__);					
				
				//On modifie les bornes droites des parents.
				if (!empty($list_parent_cats))
					$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_right = id_right - '" . (2 + $nbr_sub_cat*2) . "' WHERE id IN (" . $list_parent_cats . ")", __LINE__, __FILE__);
				
				//On réduit la taille de l'arbre du nombre de forum supprimé à partir de la position de celui-ci.
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = id_left - '" . (2 + $nbr_sub_cat*2) . "', id_right = id_right - '" . (2 + $nbr_sub_cat*2) . "' WHERE id_left > '" . $CAT_FORUM[$idcat]['id_right'] . "'", __LINE__, __FILE__);
			
				########## Ajout ##########
				if (!empty($f_to)) //Forum cible différent de la racine.
				{
					if (empty($list_parent_cats_to))
						$clause_parent_cats_to = " id = '" . $f_to . "'";
					else
						$clause_parent_cats_to = " id IN (" . $list_parent_cats_to . ")";
					
					//On modifie les bornes droites des parents de la cible.
					$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_right = id_right + '" . ($nbr_sub_cat*2) . "' WHERE " . $clause_parent_cats_to, __LINE__, __FILE__);
					
					//On augmente la taille de l'arbre du nombre de forum supprimé à partir de la position du forum cible.
					$array_parents_cats = explode(', ', $list_parent_cats);
					if ($CAT_FORUM[$idcat]['id_left'] > $CAT_FORUM[$f_to]['id_left'] && !in_array($f_to, $array_parents_cats)) //Direction forum source -> forum cible, et source non incluse dans la cible.
					{	
						$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = id_left + '" . ($nbr_sub_cat*2) . "', id_right = id_right + '" . ($nbr_sub_cat*2) . "' WHERE id_left > '" . $CAT_FORUM[$f_to]['id_right'] . "'", __LINE__, __FILE__);						
						$limit = $CAT_FORUM[$f_to]['id_right'];
						$end = $limit + ($nbr_sub_cat*2) - 1;
					}
					else
					{	
						$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = id_left + '" . ($nbr_sub_cat*2) . "', id_right = id_right + '" . ($nbr_sub_cat*2) . "' WHERE id_left > '" . ($CAT_FORUM[$f_to]['id_right'] - (2 + $nbr_sub_cat*2)) . "'", __LINE__, __FILE__);
						$limit = $CAT_FORUM[$f_to]['id_right'] - (2 + $nbr_sub_cat*2);
						$end = $limit + ($nbr_sub_cat*2) - 1;						
					}
					
					//On replace les forums supprimés virtuellement.
					$array_sub_cats = explode(', ', $list_sub_cats);
					$z = 0;
					for ($i = $limit; $i <= $end; $i = $i + 2)
					{
						$id_left = $limit + ($CAT_FORUM[$array_sub_cats[$z]]['id_left'] - $CAT_FORUM[$idcat]['id_left']) - 1;
						$id_right = $end - ($CAT_FORUM[$idcat]['id_right'] - $CAT_FORUM[$array_sub_cats[$z]]['id_right']) + 1;
						$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = '" . $id_left . "', id_right = '" . $id_right . "' WHERE id = '" . $array_sub_cats[$z] . "'", __LINE__, __FILE__);
						$z++;
					}								

					//On met à jour le nouveau forum.
					$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET level = level - '" . ($CAT_FORUM[$idcat]['level'] - $CAT_FORUM[$f_to]['level']) . "' WHERE id IN (" . $list_sub_cats . ")", __LINE__, __FILE__);
				}
				else //Racine
				{
					$max_id = $Sql->query("SELECT MAX(id_right) FROM " . PREFIX . "forum_cats", __LINE__, __FILE__);
					//On replace les forums supprimés virtuellement.
					$array_sub_cats = explode(', ', $list_sub_cats);
					$z = 0;
					$limit = $max_id + 1;
					$end = $limit + ($nbr_sub_cat*2) - 1;	
					for ($i = $limit; $i <= $end; $i = $i + 2)
					{
						$id_left = $limit + ($CAT_FORUM[$array_sub_cats[$z]]['id_left'] - $CAT_FORUM[$idcat]['id_left']) - 1;
						$id_right = $end - ($CAT_FORUM[$idcat]['id_right'] - $CAT_FORUM[$array_sub_cats[$z]]['id_right']) + 1;
						$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = '" . $id_left . "', id_right = '" . $id_right . "' WHERE id = '" . $array_sub_cats[$z] . "'", __LINE__, __FILE__);
						$z++;
					}		
					$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET level = level - '" . ($CAT_FORUM[$idcat]['level'] - $CAT_FORUM[$f_to]['level'] + 1) . "' WHERE id IN (" . $list_sub_cats . ")", __LINE__, __FILE__);
				}
			}
			else //On rétabli l'arbre intervallaire.
			{
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_right = id_right - 2 WHERE id_left < '" . $CAT_FORUM[$idcat]['id_left'] . "' AND id_right > '" . $CAT_FORUM[$idcat]['id_right'] . "'", __LINE__, __FILE__);
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = id_left - 2, id_right = id_right - 2 WHERE id_left > '" . $CAT_FORUM[$idcat]['id_right'] . "'", __LINE__, __FILE__);
			}
			
			$Cache->Generate_module_file('forum'); //Regénération du cache
			$Cache->load('forum', RELOAD_CACHE); //Rechargement du cache
			
			$this->update_last_topic_id($idcat); //On met à jour l'id du dernier topic.
			$this->update_last_topic_id($f_to); //On met à jour l'id du dernier topic.
			$this->update_last_topic_id($t_to); //On met à jour l'id du dernier topic.
		}
		
		return true;
	}
	
	//Déplacement d'une catégorie.
	function move_cat($id, $to)
	{
		global $Sql, $CAT_FORUM, $Cache;
		
		//On vérifie si la catégorie contient des sous forums.
		$nbr_cat = (($CAT_FORUM[$id]['id_right'] - $CAT_FORUM[$id]['id_left'] - 1) / 2) + 1;
		
		$list_cats = $this->get_child_list($id); //Sous forums du forum à supprimer..
		$list_parent_cats = $this->get_parent_list($id);  //Forums parent du forum à supprimer.
		
		//Précaution pour éviter erreur fatale, cas impossible si cohérence de l'arbre respectée.
		if (empty($list_cats))
			redirect(HOST . SCRIPT);

		########## Suppression ##########
		//On supprime virtuellement (changement de signe des bornes) les enfants.
		$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = - id_left, id_right = - id_right WHERE id IN (" . $list_cats . ")", __LINE__, __FILE__);	
		//On modifie les bornes droites des parents.
		if (!empty($list_parent_cats))
			$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_right = id_right - '" . ( $nbr_cat*2) . "' WHERE id IN (" . $list_parent_cats . ")", __LINE__, __FILE__);
		
		//On réduit la taille de l'arbre du nombre de forum supprimé à partir de la position de celui-ci.
		$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = id_left - '" . ($nbr_cat*2) . "', id_right = id_right - '" . ($nbr_cat*2) . "' WHERE id_left > '" . $CAT_FORUM[$id]['id_right'] . "'", __LINE__, __FILE__);
		
		########## Ajout ##########
		if (!empty($to)) //Forum cible différent de la racine.
		{
			//On augmente la taille de l'arbre du nombre de forum supprimés à partir de la position du forum cible.
			$array_parents_cats = explode(', ', $list_parent_cats);
			if ($CAT_FORUM[$id]['id_left'] > $CAT_FORUM[$to]['id_left'] && !in_array($to, $array_parents_cats)) //Direction forum source -> forum cible, et source non incluse dans la cible.
			{	
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = id_left + '" . ($nbr_cat*2) . "', id_right = id_right + '" . ($nbr_cat*2) . "' WHERE id_left > '" . $CAT_FORUM[$to]['id_right'] . "'", __LINE__, __FILE__);						
				$limit = $CAT_FORUM[$to]['id_right'];
				$end = $limit + ($nbr_cat*2) - 1;
			}
			else
			{	
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = id_left + '" . ($nbr_cat*2) . "', id_right = id_right + '" . ($nbr_cat*2) . "' WHERE id_left > '" . ($CAT_FORUM[$to]['id_right'] - ($nbr_cat*2)) . "'", __LINE__, __FILE__);
				$limit = $CAT_FORUM[$to]['id_right'] - ($nbr_cat*2);
				$end = $limit + ($nbr_cat*2) - 1;
			}	
			
			//On replace les forums supprimés virtuellement.
			$array_sub_cats = explode(', ', $list_cats);
			$z = 0;
			for ($i = $limit; $i <= $end; $i = $i + 2)
			{
				$id_left = $limit + ($CAT_FORUM[$array_sub_cats[$z]]['id_left'] - $CAT_FORUM[$id]['id_left']);
				$id_right = $end - ($CAT_FORUM[$id]['id_right'] - $CAT_FORUM[$array_sub_cats[$z]]['id_right']);
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = '" . $id_left . "', id_right = '" . $id_right . "' WHERE id = '" . $array_sub_cats[$z] . "'", __LINE__, __FILE__);
				$z++;
			}
			
			//On recharge le cache
			$Cache->Generate_module_file('forum');
			$Cache->load('forum', RELOAD_CACHE); //Rechargement du cache
			
			$list_parent_cats_to = $this->get_parent_list($to, FORUM_CAT_INCLUDED); //Forums parents du forum cible.
			if (empty($list_parent_cats_to))
				$clause_parent_cats_to = " id = '" . $to . "'";
			else
				$clause_parent_cats_to = " id IN (" . $list_parent_cats_to . ")";
			
			//On modifie les bornes droites des parents de la cible.
			$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_right = id_right + '" . ($nbr_cat*2) . "' WHERE " . $clause_parent_cats_to, __LINE__, __FILE__);
			
			//On met à jour le nouveau forum.
			$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET level = level - '" . (($CAT_FORUM[$id]['level'] - $CAT_FORUM[$to]['level']) - 1) . "' WHERE id IN (" . $list_cats . ")", __LINE__, __FILE__);
			
			$Cache->Generate_module_file('forum');
			$Cache->load('forum', RELOAD_CACHE); //Rechargement du cache
			
			$this->update_last_topic_id($id); //On met à jour l'id du dernier topic.
			$this->update_last_topic_id($to); //On met à jour l'id du dernier topic.
			
			return true;
		}
		else //Racine
		{
			$max_id = $Sql->query("SELECT MAX(id_right) FROM " . PREFIX . "forum_cats", __LINE__, __FILE__);
			//On replace les forums supprimés virtuellement.
			$array_sub_cats = explode(', ', $list_cats);
			$z = 0;
			$limit = $max_id + 1;
			$end = $limit + ($nbr_cat*2) - 1;	
			for ($i = $limit; $i <= $end; $i = $i + 2)
			{
				$id_left = $limit + ($CAT_FORUM[$array_sub_cats[$z]]['id_left'] - $CAT_FORUM[$id]['id_left']);
				$id_right = $end - ($CAT_FORUM[$id]['id_right'] - $CAT_FORUM[$array_sub_cats[$z]]['id_right']);
				$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_left = '" . $id_left . "', id_right = '" . $id_right . "' WHERE id = '" . $array_sub_cats[$z] . "'", __LINE__, __FILE__);
				$z++;
			}		
			$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET level = level - '" . ($CAT_FORUM[$id]['level'] - $CAT_FORUM[$to]['level']) . "' WHERE id IN (" . $list_cats . ")", __LINE__, __FILE__);		
		}
		
		$Cache->Generate_module_file('forum');
		return true;
	}
	
	//Récupère la liste des parents d'une catégorie.
	function get_parent_list($idcat, $cat_include = false)
	{
		global $Sql, $CAT_FORUM;
		
		$clause = $cat_include ? "WHERE id_left <= '" . $CAT_FORUM[$idcat]['id_left'] . "' AND id_right >= '" . $CAT_FORUM[$idcat]['id_right'] . "'" : "WHERE id_left < '" . $CAT_FORUM[$idcat]['id_left'] . "' AND id_right > '" . $CAT_FORUM[$idcat]['id_right'] . "'";
		
		$list_parent_cats = '';
		$result = $Sql->query_while("SELECT id
		FROM " . PREFIX . "forum_cats 
		" . $clause, __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
			$list_parent_cats .= $row['id'] . ', ';
		
		$Sql->query_close($result);
		$list_parent_cats = trim($list_parent_cats, ', ');

		return $list_parent_cats;
	}
	
	//Récupère les enfants d'une catégorie
	function get_child_list($id, $cat_include = true)
	{
		global $Sql, $CAT_FORUM;
		
		$clause = $cat_include ? "WHERE id_left BETWEEN '" . $CAT_FORUM[$id]['id_left'] . "' AND '" . $CAT_FORUM[$id]['id_right'] . "'" : "WHERE id_left BETWEEN '" . $CAT_FORUM[$id]['id_left'] . "' AND '" . $CAT_FORUM[$id]['id_right'] . "' AND id != '" . $id . "'";
		
		$list_cats = '';
		$result = $Sql->query_while("SELECT id
		FROM " . PREFIX . "forum_cats 
		" . $clause . "
		ORDER BY id_left", __LINE__, __FILE__);
		
		while ($row = $Sql->fetch_assoc($result))
			$list_cats .= $row['id'] . ', ';
		
		$Sql->query_close($result);
		$list_cats = trim($list_cats, ', ');
		
		return $list_cats;
	}
	
	//Met à jour chaque catégories quelque soit le niveau de profondeur de la catégorie source. Cas le plus favorable et courant seulement 3 requêtes.
	function update_last_topic_id($idcat)
	{
		global $Sql, $CAT_FORUM;
		
		$clause = "idcat = '" . $idcat . "'";
		if (($CAT_FORUM[$idcat]['id_right'] - $CAT_FORUM[$idcat]['id_left']) > 1) //Sous forums présents.
		{
			//Sous forums du forum à mettre à jour.
			$list_cats = '';
			$result = $Sql->query_while("SELECT id
			FROM " . PREFIX . "forum_cats 
			WHERE id_left BETWEEN '" . $CAT_FORUM[$idcat]['id_left'] . "' AND '" . $CAT_FORUM[$idcat]['id_right'] . "'
			ORDER BY id_left", __LINE__, __FILE__);
			
			while ($row = $Sql->fetch_assoc($result))
				$list_cats .= $row['id'] . ', ';
			
			$Sql->query_close($result);

			$clause = !empty($list_cats) ? "idcat IN (" . trim($list_cats, ', ') . ")" : "1";
		}
		
		//Récupération du timestamp du dernier message de la catégorie.		
		$last_timestamp = $Sql->query("SELECT MAX(last_timestamp) FROM " . PREFIX . "forum_topics WHERE " . $clause, __LINE__, __FILE__);
		$last_topic_id = $Sql->query("SELECT id FROM " . PREFIX . "forum_topics WHERE last_timestamp = '" . $last_timestamp . "'", __LINE__, __FILE__);
		if (!empty($last_topic_id))
			$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET last_topic_id = '" . $last_topic_id . "' WHERE id = '" . $idcat . "'", __LINE__, __FILE__);
		
		if ($CAT_FORUM[$idcat]['level'] > 1) //Appel recursif si sous-forum.
		{	
			//Recherche de l'id du forum parent.
			$idcat_parent = $Sql->query("SELECT id 
			FROM " . PREFIX . "forum_cats 
			WHERE id_left < '" . $CAT_FORUM[$idcat]['id_left'] . "' AND id_right > '" . $CAT_FORUM[$idcat]['id_right'] . "' AND level = '" .  ($CAT_FORUM[$idcat]['level'] - 1) . "'", __LINE__, __FILE__);

			$this->update_last_topic_id($idcat_parent); //Appel recursif.
		}
	}
}

?>