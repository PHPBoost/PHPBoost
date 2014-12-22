<?php
/*##################################################
 *                                admin_xmlhttprequest.php
 *                            -------------------
 *   begin                : August 15, 2007
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

require_once('../kernel/begin.php');
AppContext::get_session()->no_session_location(); //Permet de ne pas mettre jour la page dans la session.
require_once('../kernel/header_no_display.php');

if (AppContext::get_current_user()->check_level(User::ADMIN_LEVEL)) //Admin
{
	AppContext::get_session()->csrf_get_protect(); //Protection csrf
	
	$Cache->load('forum');

	$move = retrieve(GET, 'move', '', TSTRING_UNCHANGE);
	$id = retrieve(GET, 'id', 0);
	$get_parent_up = retrieve(GET, 'g_up', 0);
	$get_parent_down = retrieve(GET, 'g_down', 0);

	//Récupération de la catégorie d'échange.
	if (!empty($get_parent_up))
	{
		$switch_id_cat = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_cats", 'id', 'WHERE :id_left - id_right = 1', array('id_left' => $CAT_FORUM[$get_parent_up]['id_left']));
		if (!empty($switch_id_cat))
			echo $switch_id_cat;
		else
		{
			//Forums parent du forum à supprimer.
			$list_parent_cats = array();
			$result = PersistenceContext::get_querier()->select("SELECT id 
			FROM " . PREFIX . "forum_cats 
			WHERE id_left < :id_left AND id_right > :id_right", array(
				'id_left' => $CAT_FORUM[$get_parent_up]['id_left'],
				'id_right' => $CAT_FORUM[$get_parent_up]['id_right'],
			));
			
			while ($row = $result->fetch())
				$list_parent_cats[] = $row['id'];
			
			$result->dispose();
			
			if (!empty($list_parent_cats))
			{
				//Changement de catégorie.
				$change_cat = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_cats", 'id', 'WHERE id_left < :id_left AND level = :level AND id NOT IN :ids_list ORDER BY id_left DESC', array('id_left' => $CAT_FORUM[$get_parent_up]['id_left'], 'level' => ($CAT_FORUM[$get_parent_up]['level'] - 1), 'ids_list' => $list_parent_cats));
				if (isset($CAT_FORUM[$change_cat]))
				{
					$switch_id_cat = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_cats", 'id', 'WHERE id_left > :id_right ORDER BY id_left DESC', array('id_right' => $CAT_FORUM[$change_cat]['id_right']));
				}
				if (!empty($switch_id_cat))
					echo 's' . $switch_id_cat;
			}
		}
	}
	elseif (!empty($get_parent_down))
	{
		$switch_id_cat = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_cats", 'id', 'WHERE id_left - :id_right = 1', array('id_right' => $CAT_FORUM[$get_parent_down]['id_right']));
		if (!empty($switch_id_cat))
			echo $switch_id_cat;
		else
		{
			$change_cat = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_cats", 'id', 'WHERE id_left > :id_left AND level = :level ORDER BY id_left DESC', array('id_left' => $CAT_FORUM[$get_parent_down]['id_left'], 'level' => ($CAT_FORUM[$get_parent_down]['level'] - 1)));
			if (isset($CAT_FORUM[$change_cat]))
			{
				$switch_id_cat = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_cats", 'id', 'WHERE id_left < :id_right ORDER BY id_left DESC', array('id_right' => $CAT_FORUM[$change_cat]['id_right']));
			}
			if (!empty($switch_id_cat))
				echo 's' . $switch_id_cat;
		}	
	}

	//Déplacement.
	if (!empty($move) && !empty($id))
	{
		//Si la catégorie existe, et déplacement possible
		if (array_key_exists($id, $CAT_FORUM))
		{
			//Forums parent du forum à supprimer.
			$list_parent_cats = array();
			$result = PersistenceContext::get_querier()->select("SELECT id 
			FROM " . PREFIX . "forum_cats 
			WHERE id_left < :id_left AND id_right > :id_right", array(
				'id_left' => $CAT_FORUM[$id]['id_left'],
				'id_right' => $CAT_FORUM[$id]['id_right']
			));
			
			while ($row = $result->fetch())
				$list_parent_cats[] = $row['id'];
			
			$result->dispose();
			
			$to = 0;
			if ($move == 'up')
			{
				//Même catégorie
				$switch_id_cat = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_cats", 'id', 'WHERE :id_left - id_right = 1', array('id_left' => $CAT_FORUM[$id]['id_left']));
				if (!empty($switch_id_cat))
				{
					//On monte la catégorie à déplacer, on lui assigne des id négatifs pour assurer l'unicité.
					PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET id_left = - id_left + '" . ($CAT_FORUM[$switch_id_cat]['id_right'] - $CAT_FORUM[$switch_id_cat]['id_left'] + 1) . "', id_right = - id_right + '" . ($CAT_FORUM[$switch_id_cat]['id_right'] - $CAT_FORUM[$switch_id_cat]['id_left'] + 1) . "' WHERE id_left BETWEEN '" . $CAT_FORUM[$id]['id_left'] . "' AND '" . $CAT_FORUM[$id]['id_right'] . "'");
					//On descend la catégorie cible.
					PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET id_left = id_left + '" . ($CAT_FORUM[$id]['id_right'] - $CAT_FORUM[$id]['id_left'] + 1) . "', id_right = id_right + '" . ($CAT_FORUM[$id]['id_right'] - $CAT_FORUM[$id]['id_left'] + 1) . "' WHERE id_left BETWEEN '" . $CAT_FORUM[$switch_id_cat]['id_left'] . "' AND '" . $CAT_FORUM[$switch_id_cat]['id_right'] . "'");
					
					//On rétablit les valeurs absolues.
					PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET id_left = - id_left WHERE id_left < 0");
					PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET id_right = - id_right WHERE id_right < 0");
					
					$Cache->Generate_module_file('forum');
				}
				elseif (!empty($list_parent_cats))
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
					//On descend la catégorie cible.
					PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET id_left = id_left - '" . ($CAT_FORUM[$id]['id_right'] - $CAT_FORUM[$id]['id_left'] + 1) . "', id_right = id_right - '" . ($CAT_FORUM[$id]['id_right'] - $CAT_FORUM[$id]['id_left'] + 1) . "' WHERE id_left BETWEEN '" . $CAT_FORUM[$switch_id_cat]['id_left'] . "' AND '" . $CAT_FORUM[$switch_id_cat]['id_right'] . "'");
					
					//On rétablit les valeurs absolues.
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
				//On vérifie si la catégorie contient des sous forums.
				$nbr_cat = (($CAT_FORUM[$id]['id_right'] - $CAT_FORUM[$id]['id_left'] - 1) / 2) + 1;
			
				//Sous forums du forum à supprimer.
				$list_cats = array();
				$result = PersistenceContext::get_querier()->select("SELECT id 
				FROM " . PREFIX . "forum_cats 
				WHERE id_left BETWEEN :id_left AND :id_right", array(
					'id_left' => $CAT_FORUM[$id]['id_left'],
					'id_right' => $CAT_FORUM[$id]['id_right']
				));
				
				while ($row = $result->fetch())
					$list_cats = $row['id'];
				
				$result->dispose();
			
				//Précaution pour éviter erreur fatale, cas impossible si cohérence de l'arbre respectée.
				if (empty($list_cats))
					AppContext::get_response()->redirect(HOST . REWRITED_SCRIPT);
					
				//Dernier topic des parents du forum à supprimer.
				if (!empty($list_parent_cats))
				{
					$max_timestamp_parent = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_topics", 'MAX(last_timestamp)', 'WHERE idcat IN :ids_list', array('ids_list' => $list_parent_cats));
					$max_topic_id_parent = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_topics", 'id', 'WHERE last_timestamp = :timestamp', array('timestamp' => $max_timestamp_parent));
				}
				
				## Dernier topic des enfants du forum à supprimer ##
				//Forums parents du forum cible.
				$list_parent_cats_to = array();
				$result = PersistenceContext::get_querier()->select("SELECT id 
				FROM " . PREFIX . "forum_cats 
				WHERE id_left <= :id_left AND id_right >= :id_right", array(
					'id_left' => $CAT_FORUM[$to]['id_left'],
					'id_right' => $CAT_FORUM[$to]['id_right']
				));
				
				while ($row = $result->fetch())
					$list_parent_cats_to[] = $row['id'];
				
				$result->dispose();
				
				//Récupération de l'id de dernier topic.
				$max_timestamp = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_topics", 'MAX(last_timestamp)', 'WHERE idcat IN :ids_list', array('ids_list' => $list_cats));
				if (empty($list_parent_cats_to))
					$max_timestamp_to = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_topics", 'MAX(last_timestamp)', 'WHERE idcat = :id', array('id' => $to));
				else
					$max_timestamp_to = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_topics", 'MAX(last_timestamp)', 'WHERE idcat IN :ids_list', array('ids_list' => $list_parent_cats_to));
				
				$max_topic_id = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_topics", 'id', 'WHERE last_timestamp = :timestamp', array('timestamp' => max($max_timestamp, $max_timestamp_to)));
				
				########## Suppression ##########
				//On supprime virtuellement (changement de signe des bornes) les enfants.
				PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET id_left = - id_left, id_right = - id_right WHERE id IN (" . implode(', ', $list_cats) . ")");
				
				//On modifie les bornes droites et le last_topic_id des parents.
				if (!empty($list_parent_cats))
				{
					PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET last_topic_id = '" . NumberHelper::numeric($max_topic_id_parent) . "', id_right = id_right - '" . ( $nbr_cat*2) . "' WHERE id IN (" . implode(', ', $list_parent_cats) . ")");
				}
				
				//On réduit la taille de l'arbre du nombre de forum supprimé à partir de la position de celui-ci.
				PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET id_left = id_left - '" . ($nbr_cat*2) . "', id_right = id_right - '" . ($nbr_cat*2) . "' WHERE id_left > '" . $CAT_FORUM[$id]['id_right'] . "'");

				########## Ajout ##########
				//On modifie les bornes droites des parents de la cible.
				PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_cats SET id_right = id_right + '" . ($nbr_cat*2) . "' WHERE id " . (empty($list_parent_cats_to) ? "= '" . $to . "'" : "IN (" . implode(', ', $list_parent_cats_to) . ")"));

				//On augmente la taille de l'arbre du nombre de forum supprimé à partir de la position du forum cible.
				if ($CAT_FORUM[$id]['id_left'] > $CAT_FORUM[$to]['id_left'] ) //Direction forum source -> forum cible.
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

				//On replace les forums supprimés virtuellement.
				$array_sub_cats = explode(', ', $list_cats);
				$z = 0;
				for ($i = $limit; $i <= $end; $i = $i + 2)
				{
					$id_left = $limit + ($CAT_FORUM[$array_sub_cats[$z]]['id_left'] - $CAT_FORUM[$id]['id_left']);
					$id_right = $end - ($CAT_FORUM[$id]['id_right'] - $CAT_FORUM[$array_sub_cats[$z]]['id_right']);
					PersistenceContext::get_querier()->update(PREFIX . "forum_cats", array('id_left' => $id_left, 'id_right' => $id_right), 'WHERE id = :id', array('id' => $array_sub_cats[$z]));
					$z++;
				}
				
				//On met à jour le nouveau forum.
				PersistenceContext::get_querier()->update(PREFIX . "forum_cats", array('last_topic_id' => NumberHelper::numeric($max_topic_id)), 'WHERE id ' . (empty($list_parent_cats_to) ? "= :id" : "IN :ids_list"), array('id' => $to, 'ids_list' => $list_parent_cats_to));
				
				$Cache->Generate_module_file('forum');
			}
			
			//Génération de la liste des catégories en cache.
			$list_cats_js = '';
			$array_js = '';
			$i = 0;
			$result = PersistenceContext::get_querier()->select("SELECT id, id_left, id_right
			FROM " . PREFIX . "forum_cats 
			ORDER BY id_left");
			while ($row = $result->fetch())
			{
				$list_cats_js .= $row['id'] . ', ';
				$array_js .= 'array_cats[' . $row['id'] . '][\'id\'] = ' . $row['id'] . ";";
				$array_js .= 'array_cats[' . $row['id'] . '][\'id_left\'] = ' . $row['id_left'] . ";";
				$array_js .= 'array_cats[' . $row['id'] . '][\'id_right\'] = ' . $row['id_right'] . ";";
				$array_js .= 'array_cats[' . $row['id'] . '][\'i\'] = ' . $i . ";";
				$i++;
			}
			$result->dispose();
			echo 'list_cats = new Array(' . trim($list_cats_js, ', ') . ');' . $array_js;
		}	
	}
}

?>