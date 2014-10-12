<?php
/*##################################################
 *                               admin_forum.class.php
 *                            -------------------
 *   begin                : December 20, 2007
 *   copyright            : (C) 2007 Viarre R�gis
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
	//Déplacement vers le haut/bas de la cat�gorie.
	function move_updown_cat($id, $move)
	{
		global $CAT_FORUM, $Cache;
		
		$list_parent_cats = $this->get_parent_list($idcat); //R�cup�re la liste des parents de la cat�gorie.
		
		$to = 0;
		if ($move == 'up')
		{
			//Même catégorie
			$switch_id_cat = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_cats", 'id', 'WHERE :id_left - id_right = 1', array('id_left' => $CAT_FORUM[$id]['id_left']));
			if (!empty($switch_id_cat))
			{
				//On monte la cat�gorie � d�placer, on lui assigne des id n�gatifs pour assurer l'unicit�.
				PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET id_left = - id_left + '" . ($CAT_FORUM[$switch_id_cat]['id_right'] - $CAT_FORUM[$switch_id_cat]['id_left'] + 1) . "', id_right = - id_right + '" . ($CAT_FORUM[$switch_id_cat]['id_right'] - $CAT_FORUM[$switch_id_cat]['id_left'] + 1) . "' WHERE id_left BETWEEN '" . $CAT_FORUM[$id]['id_left'] . "' AND '" . $CAT_FORUM[$id]['id_right'] . "'");
				//On descend la cat�gorie cible.
				PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET id_left = id_left + '" . ($CAT_FORUM[$id]['id_right'] - $CAT_FORUM[$id]['id_left'] + 1) . "', id_right = id_right + '" . ($CAT_FORUM[$id]['id_right'] - $CAT_FORUM[$id]['id_left'] + 1) . "' WHERE id_left BETWEEN '" . $CAT_FORUM[$switch_id_cat]['id_left'] . "' AND '" . $CAT_FORUM[$switch_id_cat]['id_right'] . "'");
				
				//On r�tablit les valeurs absolues.
				PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET id_left = - id_left WHERE id_left < 0");
				PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET id_right = - id_right WHERE id_right < 0");	
				
				$Cache->Generate_module_file('forum');
			}
			elseif (!empty($list_parent_cats) )
			{
				//Changement de catégorie.
				$to = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_cats", 'id', 'WHERE id_left < :id_left AND level = :level AND id NOT IN :ids_list ORDER BY id_left DESC', array('id_left' => $CAT_FORUM[$id]['id_left'], 'level' => ($CAT_FORUM[$id]['level'] - 1), 'ids_list' => $list_parent_cats));
			}
		}
		elseif ($move == 'down')
		{
			//Doit-on changer de catégorie parente ou non ?
			$switch_id_cat = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_cats", 'id', 'WHERE id_left - :id_right = 1', array('id_right' => $CAT_FORUM[$id]['id_right']));
			if (!empty($switch_id_cat))
			{
				//On monte la catégorie à déplacer, on lui assigne des id négatifs pour assurer l'unicité.
				PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET id_left = - id_left - '" . ($CAT_FORUM[$switch_id_cat]['id_right'] - $CAT_FORUM[$switch_id_cat]['id_left'] + 1) . "', id_right = - id_right - '" . ($CAT_FORUM[$switch_id_cat]['id_right'] - $CAT_FORUM[$switch_id_cat]['id_left'] + 1) . "' WHERE id_left BETWEEN '" . $CAT_FORUM[$id]['id_left'] . "' AND '" . $CAT_FORUM[$id]['id_right'] . "'");
				//On descend la cat�gorie cible.
				PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET id_left = id_left - '" . ($CAT_FORUM[$id]['id_right'] - $CAT_FORUM[$id]['id_left'] + 1) . "', id_right = id_right - '" . ($CAT_FORUM[$id]['id_right'] - $CAT_FORUM[$id]['id_left'] + 1) . "' WHERE id_left BETWEEN '" . $CAT_FORUM[$switch_id_cat]['id_left'] . "' AND '" . $CAT_FORUM[$switch_id_cat]['id_right'] . "'");
				
				//On r�tablit les valeurs absolues.
				PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET id_left = - id_left WHERE id_left < 0");
				PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET id_right = - id_right WHERE id_right < 0");
				
				$Cache->Generate_module_file('forum');
			}
			elseif (!empty($list_parent_cats) )
			{
				//Changement de catégorie.
				$to = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_cats", 'id', 'WHERE id_left > :id_left AND level = :level ORDER BY id_left DESC', array('id_left' => $CAT_FORUM[$id]['id_left'], 'level' => ($CAT_FORUM[$id]['level'] - 1)));
			}
		}

		if (!empty($to)) //Changement de catégorie possible?
		{
			//On vérifie si la cat�gorie contient des sous forums.
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
			PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET id_left = - id_left, id_right = - id_right WHERE id IN (" . $list_cats . ")");								
			//On modifie les bornes droites des parents.
			if (!empty($list_parent_cats))
				PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET id_right = id_right - '" . ( $nbr_cat*2) . "' WHERE id IN (" . $list_parent_cats . ")");
			
			//On r�duit la taille de l'arbre du nombre de forum supprim� � partir de la position de celui-ci.
			PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET id_left = id_left - '" . ($nbr_cat*2) . "', id_right = id_right - '" . ($nbr_cat*2) . "' WHERE id_left > '" . $CAT_FORUM[$id]['id_right'] . "'");

			########## Ajout ##########
			//On modifie les bornes droites des parents de la cible.
			PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET id_right = id_right + '" . ($nbr_cat*2) . "' WHERE " . $clause_parent_cats_to);

			//On augmente la taille de l'arbre du nombre de forum supprim� � partir de la position du forum cible.
			$array_parents_cats = explode(', ', $list_parent_cats);
			if ($CAT_FORUM[$id]['id_left'] > $CAT_FORUM[$to]['id_left'] && !in_array($f_to, $array_parents_cats) ) //Direction forum source -> forum cible, et source non incluse dans la cible.
			{	
				PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET id_left = id_left + '" . ($nbr_cat*2) . "', id_right = id_right + '" . ($nbr_cat*2) . "' WHERE id_left > '" . $CAT_FORUM[$to]['id_right'] . "'");						
				$limit = $CAT_FORUM[$to]['id_right'];
				$end = $limit + ($nbr_cat*2) - 1;
			}
			else
			{	
				PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET id_left = id_left + '" . ($nbr_cat*2) . "', id_right = id_right + '" . ($nbr_cat*2) . "' WHERE id_left > '" . ($CAT_FORUM[$to]['id_right'] - ($nbr_cat*2)) . "'");
				$limit = $CAT_FORUM[$to]['id_right'] - ($nbr_cat*2);
				$end = $limit + ($nbr_cat*2) - 1;						
			}	

			//On replace les forums supprim�s virtuellement.
			$array_sub_cats = explode(', ', $list_cats);
			$z = 0;
			for ($i = $limit; $i <= $end; $i = $i + 2)
			{
				$id_left = $limit + ($CAT_FORUM[$array_sub_cats[$z]]['id_left'] - $CAT_FORUM[$id]['id_left']);
				$id_right = $end - ($CAT_FORUM[$id]['id_right'] - $CAT_FORUM[$array_sub_cats[$z]]['id_right']);
				PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET id_left = '" . $id_left . "', id_right = '" . $id_right . "' WHERE id = '" . $array_sub_cats[$z] . "'");
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
		global $CAT_FORUM, $Cache;
		
		//On vérifie si la catégorie contient des sous forums.
		$nbr_sub_cat = (($CAT_FORUM[$idcat]['id_right'] - $CAT_FORUM[$idcat]['id_left'] - 1) / 2);
		
		if ($confirm_delete) //Confirmation de suppression, on supprime dans la bdd et on rétabli l'arbre intervallaire.
		{
			$first_parent = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_cats", 'id', 'WHERE id_left < :id_left AND id_right > :id_right ORDER BY id_left DESC', array('id_left' => $CAT_FORUM[$idcat]['id_left'], 'id_right' => $CAT_FORUM[$idcat]['id_right']));
			$list_parent_cats = $this->get_parent_list($idcat); //Récupère la liste des parents de la catégorie.
			
			$nbr_del = $CAT_FORUM[$idcat]['id_right'] - $CAT_FORUM[$idcat]['id_left'] + 1;
			if (!empty($list_parent_cats))
				PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET id_right = id_right - '" . $nbr_del . "' WHERE id IN (" . $list_parent_cats . ")");

			PersistenceContext::get_querier()->delete(PREFIX . 'forum_cats', 'WHERE id_left BETWEEN :id_left AND :id_right', array('id_left' => $CAT_FORUM[$idcat]['id_left'], 'id_right' => $CAT_FORUM[$idcat]['id_right']));
			
			PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET id_left = id_left - '" . $nbr_del . "', id_right = id_right - '" . $nbr_del . "' WHERE id_left > '" . $CAT_FORUM[$idcat]['id_right'] . "'");
			
			PersistenceContext::get_querier()->delete(PREFIX . 'forum_msg', 'WHERE idtopic IN (
			SELECT id FROM ' . PREFIX . 'forum_topics WHERE idcat =:id)', array('id' => $idcat));
			
			PersistenceContext::get_querier()->delete(PREFIX . 'forum_topics', 'WHERE idcat=:id', array('id' => $idcat));
	
			$Cache->Generate_module_file('forum'); //Reg�n�ration du cache
			$Cache->load('forum', RELOAD_CACHE); //Rechargement du cache
			
			$this->update_last_topic_id($first_parent); //On met à jour l'id du dernier topic.
		}
		else //Déplacement.
		{
			//Déplacement de sous forums.
			$f_to = retrieve(POST, 'f_to', 0);
			$f_to = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_cats", 'id', 'WHERE id = :id AND id_left NOT BETWEEN :id_left AND :id_right', array('id' => $f_to, 'id_left' => $CAT_FORUM[$idcat]['id_left'], 'id_right' => $CAT_FORUM[$idcat]['id_right']));
			
			//Déplacement de topics.
			$t_to = retrieve(POST, 't_to', 0);
			$t_to = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_cats", 'id', 'WHERE id = :id AND id <> :idcat', array('id' => $t_to, 'idcat' => $idcat));
			
			//Déplacement des topics dans la catégorie sélectionnée.
			if (!empty($t_to))
			{
				//On va chercher la somme du nombre de messages dans la table topics
				$nbr_msg = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_topics", 'SUM(nbr_msg)', 'WHERE idcat = :idcat', array('idcat' => $idcat));
				$nbr_msg = !empty($nbr_msg) ? $nbr_msg : 0;
				//Nombre de topics.
				$nbr_topic = PersistenceContext::get_querier()->count(PREFIX . 'forum_topics', 'WHERE idcat=:idcat', array('idcat' => $idcat));
				$nbr_topic = !empty($nbr_topic) ? $nbr_topic : 0;
				
				//On deplace les topics dans le nouveau forum.
				PersistenceContext::get_querier()->update(PREFIX . 'forum_topics', array('idcat' => $t_to), 'WHERE idcat=:id', array('id' => $idcat));

				//On met a jour le nouveau forum.
				PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET nbr_msg = nbr_msg + " . $nbr_msg . ", nbr_topic = nbr_topic + " . $nbr_topic . " WHERE id=:id", array('id' => $t_to));
				
				//On supprime l'ancien forum.
				PersistenceContext::get_querier()->delete(PREFIX . 'forum_cats', 'WHERE id=:id', array('id' => $idcat));
			}
			
			//Pr�sence de sous-forums => deplacement de ceux-ci.
			if ($nbr_sub_cat > 0)
			{
				$list_sub_cats = $this->get_child_list($idcat, FORUM_CAT_NO_INCLUDED); //Sous forums du forum.
				$list_parent_cats = $this->get_parent_list($idcat);  //Forums parent du forum.
				$list_parent_cats_to = $this->get_parent_list($f_to, FORUM_CAT_INCLUDED);  //Forums parents du forum cible.
				
				//Pr�caution pour eviter erreur fatale, cas impossible si coherence de l'arbre respecte.
				if (empty($list_sub_cats))
					return false;
					
				########## Suppression ##########
				//On supprime l'ancien forum.
				PersistenceContext::get_querier()->delete(PREFIX . 'forum_cats', 'WHERE id=:id', array('id' => $idcat));
				
				//On supprime virtuellement (changement de signe des bornes) les enfants.
				PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET id_left = - id_left, id_right = - id_right WHERE id IN (" . $list_sub_cats . ")");					
				
				//On modifie les bornes droites des parents.
				if (!empty($list_parent_cats))
					PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET id_right = id_right - '" . (2 + $nbr_sub_cat*2) . "' WHERE id IN (" . $list_parent_cats . ")");
				
				//On r�duit la taille de l'arbre du nombre de forum supprim� � partir de la position de celui-ci.
				PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET id_left = id_left - '" . (2 + $nbr_sub_cat*2) . "', id_right = id_right - '" . (2 + $nbr_sub_cat*2) . "' WHERE id_left > '" . $CAT_FORUM[$idcat]['id_right'] . "'");
			
				########## Ajout ##########
				if (!empty($f_to)) //Forum cible diff�rent de la racine.
				{
					if (empty($list_parent_cats_to))
						$clause_parent_cats_to = " id = '" . $f_to . "'";
					else
						$clause_parent_cats_to = " id IN (" . $list_parent_cats_to . ")";
					
					//On modifie les bornes droites des parents de la cible.
					PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET id_right = id_right + '" . ($nbr_sub_cat*2) . "' WHERE " . $clause_parent_cats_to);
					
					//On augmente la taille de l'arbre du nombre de forum supprim� � partir de la position du forum cible.
					$array_parents_cats = explode(', ', $list_parent_cats);
					if ($CAT_FORUM[$idcat]['id_left'] > $CAT_FORUM[$f_to]['id_left'] && !in_array($f_to, $array_parents_cats)) //Direction forum source -> forum cible, et source non incluse dans la cible.
					{	
						PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET id_left = id_left + '" . ($nbr_sub_cat*2) . "', id_right = id_right + '" . ($nbr_sub_cat*2) . "' WHERE id_left > '" . $CAT_FORUM[$f_to]['id_right'] . "'");
						$limit = $CAT_FORUM[$f_to]['id_right'];
						$end = $limit + ($nbr_sub_cat*2) - 1;
					}
					else
					{	
						PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET id_left = id_left + '" . ($nbr_sub_cat*2) . "', id_right = id_right + '" . ($nbr_sub_cat*2) . "' WHERE id_left > '" . ($CAT_FORUM[$f_to]['id_right'] - (2 + $nbr_sub_cat*2)) . "'");
						$limit = $CAT_FORUM[$f_to]['id_right'] - (2 + $nbr_sub_cat*2);
						$end = $limit + ($nbr_sub_cat*2) - 1;
					}
					
					//On replace les forums supprim�s virtuellement.
					$array_sub_cats = explode(', ', $list_sub_cats);
					$z = 0;
					for ($i = $limit; $i <= $end; $i = $i + 2)
					{
						$id_left = $limit + ($CAT_FORUM[$array_sub_cats[$z]]['id_left'] - $CAT_FORUM[$idcat]['id_left']) - 1;
						$id_right = $end - ($CAT_FORUM[$idcat]['id_right'] - $CAT_FORUM[$array_sub_cats[$z]]['id_right']) + 1;
						PersistenceContext::get_querier()->update(PREFIX . 'forum_cats', array('id_left' => $id_left, 'id_right' => $id_right), 'WHERE id=:id', array('id' => $array_sub_cats[$z]));
						$z++;
					}

					//On met à jour le nouveau forum.
					PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET level = level - '" . ($CAT_FORUM[$idcat]['level'] - $CAT_FORUM[$f_to]['level']) . "' WHERE id IN (" . $list_sub_cats . ")");
				}
				else //Racine
				{
					$max_id = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_cats", 'MAX(id_right)', '');
					//On replace les forums supprimés virtuellement.
					$array_sub_cats = explode(', ', $list_sub_cats);
					$z = 0;
					$limit = $max_id + 1;
					$end = $limit + ($nbr_sub_cat*2) - 1;	
					for ($i = $limit; $i <= $end; $i = $i + 2)
					{
						$id_left = $limit + ($CAT_FORUM[$array_sub_cats[$z]]['id_left'] - $CAT_FORUM[$idcat]['id_left']) - 1;
						$id_right = $end - ($CAT_FORUM[$idcat]['id_right'] - $CAT_FORUM[$array_sub_cats[$z]]['id_right']) + 1;
						PersistenceContext::get_querier()->update(PREFIX . 'forum_cats', array('id_left' => $id_left, 'id_right' => $id_right), 'WHERE id=:id', array('id' => $array_sub_cats[$z]));
						$z++;
					}		
					PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET level = level - '" . ($CAT_FORUM[$idcat]['level'] - $CAT_FORUM[$f_to]['level'] + 1) . "' WHERE id IN (" . $list_sub_cats . ")");
				}
			}
			else //On rétabli l'arbre intervallaire.
			{
				PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET id_right = id_right - 2 WHERE id_left < '" . $CAT_FORUM[$idcat]['id_left'] . "' AND id_right > '" . $CAT_FORUM[$idcat]['id_right'] . "'");
				PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET id_left = id_left - 2, id_right = id_right - 2 WHERE id_left > '" . $CAT_FORUM[$idcat]['id_right'] . "'");
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
		global $CAT_FORUM, $Cache;
		
		//On vérifie si la catégorie contient des sous forums.
		$nbr_cat = (($CAT_FORUM[$id]['id_right'] - $CAT_FORUM[$id]['id_left'] - 1) / 2) + 1;
		
		$list_cats = $this->get_child_list($id); //Sous forums du forum à supprimer..
		$list_parent_cats = $this->get_parent_list($id);  //Forums parent du forum à supprimer.
		
		//Préaution pour éviter erreur fatale, cas impossible si cohérence de l'arbre respectée.
		if (empty($list_cats))
			AppContext::get_response()->redirect(HOST . REWRITED_SCRIPT);

		########## Suppression ##########
		//On supprime virtuellement (changement de signe des bornes) les enfants.
		PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET id_left = - id_left, id_right = - id_right WHERE id IN (" . $list_cats . ")");	
		//On modifie les bornes droites des parents.
		if (!empty($list_parent_cats))
			PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET id_right = id_right - '" . ( $nbr_cat*2) . "' WHERE id IN (" . $list_parent_cats . ")");
		
		//On r�duit la taille de l'arbre du nombre de forum supprim� � partir de la position de celui-ci.
		PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET id_left = id_left - '" . ($nbr_cat*2) . "', id_right = id_right - '" . ($nbr_cat*2) . "' WHERE id_left > '" . $CAT_FORUM[$id]['id_right'] . "'");
		
		########## Ajout ##########
		if (!empty($to)) //Forum cible diff�rent de la racine.
		{
			//On augmente la taille de l'arbre du nombre de forum supprim�s � partir de la position du forum cible.
			$array_parents_cats = explode(', ', $list_parent_cats);
			if ($CAT_FORUM[$id]['id_left'] > $CAT_FORUM[$to]['id_left'] && !in_array($to, $array_parents_cats)) //Direction forum source -> forum cible, et source non incluse dans la cible.
			{	
				PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET id_left = id_left + '" . ($nbr_cat*2) . "', id_right = id_right + '" . ($nbr_cat*2) . "' WHERE id_left > '" . $CAT_FORUM[$to]['id_right'] . "'");
				$limit = $CAT_FORUM[$to]['id_right'];
				$end = $limit + ($nbr_cat*2) - 1;
			}
			else
			{	
				PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET id_left = id_left + '" . ($nbr_cat*2) . "', id_right = id_right + '" . ($nbr_cat*2) . "' WHERE id_left > '" . ($CAT_FORUM[$to]['id_right'] - ($nbr_cat*2)) . "'");
				$limit = $CAT_FORUM[$to]['id_right'] - ($nbr_cat*2);
				$end = $limit + ($nbr_cat*2) - 1;
			}	
			
			//On replace les forums supprim�s virtuellement.
			$array_sub_cats = explode(', ', $list_cats);
			$z = 0;
			for ($i = $limit; $i <= $end; $i = $i + 2)
			{
				$id_left = $limit + ($CAT_FORUM[$array_sub_cats[$z]]['id_left'] - $CAT_FORUM[$id]['id_left']);
				$id_right = $end - ($CAT_FORUM[$id]['id_right'] - $CAT_FORUM[$array_sub_cats[$z]]['id_right']);
				PersistenceContext::get_querier()->update(PREFIX . 'forum_cats', array('id_left' => $id_left, 'id_right' => $id_right), 'WHERE id=:id', array('id' => $array_sub_cats[$z]));
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
			PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET id_right = id_right + '" . ($nbr_cat*2) . "' WHERE " . $clause_parent_cats_to);
			
			//On met � jour le nouveau forum.
			PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET level = level - '" . (($CAT_FORUM[$id]['level'] - $CAT_FORUM[$to]['level']) - 1) . "' WHERE id IN (" . $list_cats . ")");
			
			$Cache->Generate_module_file('forum');
			$Cache->load('forum', RELOAD_CACHE); //Rechargement du cache
			
			$this->update_last_topic_id($id); //On met � jour l'id du dernier topic.
			$this->update_last_topic_id($to); //On met � jour l'id du dernier topic.
			
			return true;
		}
		else //Racine
		{
			$max_id = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_cats", 'MAX(id_right)', '');
			//On replace les forums supprim�s virtuellement.
			$array_sub_cats = explode(', ', $list_cats);
			$z = 0;
			$limit = $max_id + 1;
			$end = $limit + ($nbr_cat*2) - 1;	
			for ($i = $limit; $i <= $end; $i = $i + 2)
			{
				$id_left = $limit + ($CAT_FORUM[$array_sub_cats[$z]]['id_left'] - $CAT_FORUM[$id]['id_left']);
				$id_right = $end - ($CAT_FORUM[$id]['id_right'] - $CAT_FORUM[$array_sub_cats[$z]]['id_right']);
				PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET id_left = '" . $id_left . "', id_right = '" . $id_right . "' WHERE id = '" . $array_sub_cats[$z] . "'");
				$z++;
			}		
			PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET level = level - '" . ($CAT_FORUM[$id]['level'] - $CAT_FORUM[$to]['level']) . "' WHERE id IN (" . $list_cats . ")");		
		}
		
		$Cache->Generate_module_file('forum');
		return true;
	}
	
	//Recupere la liste des parents d'une categorie.
	function get_parent_list($idcat, $cat_include = false)
	{
		global $CAT_FORUM;
		
		$list_parent_cats = array();
		$result = PersistenceContext::get_querier()->select("SELECT id
		FROM " . PREFIX . "forum_cats 
		" . ($cat_include ? "WHERE id_left <= :id_left AND id_right >= :id_right" : "WHERE id_left < :id_left AND id_right > :id_right"), array(
			'id_left' => $CAT_FORUM[$idcat]['id_left'],
			'id_right' => $CAT_FORUM[$idcat]['id_right']
		));
		while ($row = $result->fetch())
			$list_parent_cats[] = $row['id'];
		
		$result->dispose();

		return $list_parent_cats;
	}
	
	//Récupère les enfants d'une catégorie
	function get_child_list($id, $cat_include = true)
	{
		global $CAT_FORUM;
		
		$list_cats = array();
		$result = PersistenceContext::get_querier()->select("SELECT id
		FROM " . PREFIX . "forum_cats 
		WHERE id_left BETWEEN :id_left AND :id_right" . ($cat_include ? " AND id != :id" : ''), array(
			'id_left' => $CAT_FORUM[$id]['id_left'],
			'id_right' => $CAT_FORUM[$id]['id_right'],
			'id' => $id
		));
		
		while ($row = $result->fetch())
			$list_cats[] = $row['id'];
		
		$result->dispose();
		
		return $list_cats;
	}
	
	//Met � jour chaque cat�gories quelque soit le niveau de profondeur de la cat�gorie source. Cas le plus favorable et courant seulement 3 requ�tes.
	function update_last_topic_id($idcat)
	{
		global $CAT_FORUM;
		
		$clause = "idcat = '" . $idcat . "'";
		if (($CAT_FORUM[$idcat]['id_right'] - $CAT_FORUM[$idcat]['id_left']) > 1) //Sous forums pr�sents.
		{
			//Sous forums du forum à mettre à jour.
			$list_cats = '';
			$result = PersistenceContext::get_querier()->select("SELECT id
			FROM " . PREFIX . "forum_cats 
			WHERE id_left BETWEEN :id_left AND :id_right
			ORDER BY id_left", array(
				'id_left' => $CAT_FORUM[$idcat]['id_left'],
				'id_right' => $CAT_FORUM[$idcat]['id_right']
			));
			
			while ($row = $result->fetch())
				$list_cats[] = $row['id'];
			
			$result->dispose();

			$clause = !empty($list_cats) ? "idcat IN (" . implode(', ', $list_cats) . ")" : "1";
		}
		
		//Récupération du timestamp du dernier message de la catégorie.
		$last_timestamp = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_topics", 'MAX(last_timestamp)', 'WHERE' . $clause);
		$last_topic_id = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_topics", 'id', 'WHERE last_timestamp = :timestamp', array('timestamp' => $last_timestamp));
		if (!empty($last_topic_id))
			PersistenceContext::get_querier()->update(PREFIX . 'forum_cats', array('last_topic_id' => $last_topic_id), 'WHERE id=:id', array('id' => $idcat));
		
		if ($CAT_FORUM[$idcat]['level'] > 1) //Appel recursif si sous-forum.
		{
			//Recherche de l'id du forum parent.
			$idcat_parent = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_cats", 'id', 'WHERE id_left < :id_left AND id_right > :id_right AND level = :level', array('id_left' => $CAT_FORUM[$idcat]['id_left'], 'id_right' => $CAT_FORUM[$idcat]['id_right'], 'level' => ($CAT_FORUM[$idcat]['level'] - 1)));
			$this->update_last_topic_id($idcat_parent); //Appel recursif.
		}
	}
}

?>