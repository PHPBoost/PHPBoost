<?php
/*##################################################
 *                               admin_xmlhttprequest.php
 *                            -------------------
 *   begin                : August 12, 2007
 *   copyright            : (C) 2007 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
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
	$Cache->load('gallery');
	AppContext::get_session()->csrf_get_protect(); //Protection csrf
	
	$move = !empty($_GET['move']) ? trim($_GET['move']) : 0;
	$id = !empty($_GET['id']) ? NumberHelper::numeric($_GET['id']) : 0;
	$get_parent_up = !empty($_GET['g_up']) ? NumberHelper::numeric($_GET['g_up']) : 0;
	$get_parent_down = !empty($_GET['g_down']) ? NumberHelper::numeric($_GET['g_down']) : 0;

	//Récupération de la catégorie d'échange.
	if (!empty($get_parent_up))
	{
		$switch_id_cat = PersistenceContext::get_querier()->get_column_value(PREFIX . "gallery_cats", 'id', 'WHERE :id_left - id_right = 1', array('id_left' => $CAT_GALLERY[$get_parent_up]['id_left']));
		if (!empty($switch_id_cat))
			echo $switch_id_cat;
		else
		{
			//Galeries parentes de la galerie à supprimer.
			$list_parent_cats = array();
			$result = PersistenceContext::get_querier()->select("SELECT id 
			FROM " . PREFIX . "gallery_cats 
			WHERE id_left < :id_left AND id_right > :id_right", array(
				'id_left' => $CAT_GALLERY[$get_parent_up]['id_left'],
				'id_right' => $CAT_GALLERY[$get_parent_up]['id_right']
			));
			while ($row = $result->fetch())
			{
				$list_parent_cats[] = $row['id'];
			}
			$result->dispose();
			
			if (!empty($list_parent_cats))
			{
				//Changement de catégorie.
				$change_cat = PersistenceContext::get_querier()->get_column_value(PREFIX . "gallery_cats", 'id', 'WHERE id_left < :id_left AND level = :level AND id NOT IN :ids_list', array('id_left' => $CAT_GALLERY[$get_parent_up]['id_left'], 'level' => ($CAT_GALLERY[$get_parent_up]['level'] - 1), 'ids_list' => $list_parent_cats));
				
				if (isset($CAT_GALLERY[$change_cat]))
				{
					$switch_id_cat = PersistenceContext::get_querier()->get_column_value(PREFIX . "gallery_cats", 'id', 'WHERE id_left > :id_right', array('id_right' => $CAT_GALLERY[$change_cat]['id_right']));
				}
				if (!empty($switch_id_cat))
					echo 's' . $switch_id_cat;
			}
		}	
	}
	elseif (!empty($get_parent_down))
	{
		$switch_id_cat = PersistenceContext::get_querier()->get_column_value(PREFIX . "gallery_cats", 'id', 'WHERE id_left - :id_right = 1', array('id_right' => $CAT_GALLERY[$get_parent_up]['id_right']));
		if (!empty($switch_id_cat))
			echo $switch_id_cat;
		else
		{
			$change_cat = PersistenceContext::get_querier()->get_column_value(PREFIX . "gallery_cats", 'id', 'WHERE id_left > :id_left AND level = :level', array('id_left' => $CAT_GALLERY[$get_parent_up]['id_left'], 'level' => ($CAT_GALLERY[$get_parent_down]['level'] - 1)));
			
			if (isset($CAT_GALLERY[$change_cat]))
			{
				$switch_id_cat = PersistenceContext::get_querier()->get_column_value(PREFIX . "gallery_cats", 'id', 'WHERE id_left < :id_right', array('id_right' => $CAT_GALLERY[$change_cat]['id_right']));
			}
			if (!empty($switch_id_cat))
				echo 's' . $switch_id_cat;
		}	
	}

	//Déplacement.
	if (!empty($move) && !empty($id))
	{
		//Si la catégorie existe, et déplacement possible
		if (array_key_exists($id, $CAT_GALLERY))
		{
			//Galeries parentes de la galerie à déplacer.
			$list_parent_cats = array();
			$result = PersistenceContext::get_querier()->select("SELECT id 
			FROM " . PREFIX . "gallery_cats 
			WHERE id_left < :id_left AND id_right > :id_right", array(
				'id_left' => $CAT_GALLERY[$id]['id_left'],
				'id_right' => $CAT_GALLERY[$id]['id_right']
			));
			while ($row = $result->fetch())
			{
				$list_parent_cats[] = $row['id'];
			}
			$result->dispose();
			
			$to = 0;
			if ($move == 'up')
			{
				//Même catégorie
				$switch_id_cat = PersistenceContext::get_querier()->get_column_value(PREFIX . "gallery_cats", 'id', 'WHERE :id_left - id_right = 1', array('id_left' => $CAT_GALLERY[$get_parent_up]['id_left']));
				if (!empty($switch_id_cat))
				{
					//On monte la catégorie à déplacer, on lui assigne des id négatifs pour assurer l'unicité.
					PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "gallery_cats SET id_left = - id_left + :diff, id_right = - id_right + :diff WHERE id_left BETWEEN :id_left AND :id_right", array('diff' => ($CAT_GALLERY[$switch_id_cat]['id_right'] - $CAT_GALLERY[$switch_id_cat]['id_left'] + 1), 'id_left' => $CAT_GALLERY[$id]['id_left'], 'id_right' => $CAT_GALLERY[$id]['id_right']));
					//On descend la catégorie cible.
					PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "gallery_cats SET id_left = id_left + :diff, id_right = id_right + :diff WHERE id_left BETWEEN :id_left AND :id_right", array('diff' => ($CAT_GALLERY[$id]['id_right'] - $CAT_GALLERY[$id]['id_left'] + 1), 'id_left' => $CAT_GALLERY[$switch_id_cat]['id_left'], 'id_right' => $CAT_GALLERY[$switch_id_cat]['id_right']));
					
					//On rétablit les valeurs absolues.
					PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "gallery_cats SET id_left = - id_left WHERE id_left < 0");
					PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "gallery_cats SET id_right = - id_right WHERE id_right < 0");
					
					$Cache->Generate_module_file('gallery');
				}
				elseif (!empty($list_parent_cats) )
				{
					//Changement de catégorie.
					$to = PersistenceContext::get_querier()->select_single_row_query("SELECT id FROM " . PREFIX . "gallery_cats
					WHERE id_left < :id_left AND level = :level AND
					id NOT IN :ids_list
					ORDER BY id_left DESC", array(
						'id_left' => $CAT_GALLERY[$id]['id_left'],
						'level' => ($CAT_GALLERY[$id]['level'] - 1),
						'ids_list' => $list_parent_cats
					));
				}
			}
			elseif ($move == 'down')
			{
				//Doit-on changer de catégorie parente ou non ?
				$switch_id_cat = PersistenceContext::get_querier()->get_column_value(PREFIX . "gallery_cats", 'id', 'WHERE id_left - :id_right = 1', array('id_right' => $CAT_GALLERY[$get_parent_up]['id_right']));
				if (!empty($switch_id_cat))
				{
					//On monte la catégorie à déplacer, on lui assigne des id négatifs pour assurer l'unicité.
					PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "gallery_cats SET id_left = - id_left - :diff, id_right = - id_right - :diff WHERE id_left BETWEEN :id_left AND :id_right", array('diff' => ($CAT_GALLERY[$switch_id_cat]['id_right'] - $CAT_GALLERY[$switch_id_cat]['id_left'] + 1), 'id_left' => $CAT_GALLERY[$id]['id_left'], 'id_right' => $CAT_GALLERY[$id]['id_right']));
					//On descend la catégorie cible.
					PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "gallery_cats SET id_left = id_left - :diff, id_right = id_right - :diff WHERE id_left BETWEEN :id_left AND :id_right", array('diff' => ($CAT_GALLERY[$id]['id_right'] - $CAT_GALLERY[$id]['id_left'] + 1), 'id_left' => $CAT_GALLERY[$switch_id_cat]['id_left'], 'id_right' => $CAT_GALLERY[$switch_id_cat]['id_right']));
					
					//On rétablit les valeurs absolues.
					PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "gallery_cats SET id_left = - id_left WHERE id_left < 0");
					PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "gallery_cats SET id_right = - id_right WHERE id_right < 0");
					
					$Cache->Generate_module_file('gallery');
				}
				elseif (!empty($list_parent_cats) )
				{
					//Changement de catégorie.
					$to = PersistenceContext::get_querier()->select_single_row_query("SELECT id FROM " . PREFIX . "gallery_cats
					WHERE id_left > :id_left AND level = :level
					ORDER BY id_left DESC", array(
						'id_left' => $CAT_GALLERY[$id]['id_left'],
						'level' => ($CAT_GALLERY[$id]['level'] - 1)
					));
				}
			}

			if (!empty($to)) //Changement de catégorie possible?
			{
				//On vérifie si la catégorie contient des sous galeries.
				$nbr_cat = (($CAT_GALLERY[$id]['id_right'] - $CAT_GALLERY[$id]['id_left'] - 1) / 2) + 1;
			
				//Sous galeries de la galerie à déplacer.
				$list_cats = array();
				$result = PersistenceContext::get_querier()->select("SELECT id
				FROM " . PREFIX . "gallery_cats 
				WHERE id_left BETWEEN :id_left AND :id_right
				ORDER BY id_left", array(
					'id_left' => $CAT_GALLERY[$id]['id_left'],
					'id_right' => $CAT_GALLERY[$id]['id_right']
				));
				while ($row = $result->fetch())
				{
					$list_cats[] = $row['id'];
				}
				$result->dispose();
				
				//Récupération du nombre d'images de la galerie.
				$nbr_pics_aprob = PersistenceContext::get_querier()->get_column_value(PREFIX . "gallery_cats", 'nbr_pics_aprob', 'WHERE id = :id', array('id' => $id));
				$nbr_pics_unaprob = PersistenceContext::get_querier()->get_column_value(PREFIX . "gallery_cats", 'nbr_pics_unaprob', 'WHERE id = :id', array('id' => $id));
				
				//Galeries parentes de la galerie cible.
				$list_parent_cats_to = array();
				$result = PersistenceContext::get_querier()->select("SELECT id, level 
				FROM " . PREFIX . "gallery_cats 
				WHERE id_left <= :id_left AND id_right >= :id_right", array(
					'id_left' => $CAT_GALLERY[$to]['id_left'],
					'id_right' => $CAT_GALLERY[$to]['id_right'],
				));
				while ($row = $result->fetch())
				{
					$list_parent_cats_to[] = $row['id'];
				}
				$result->dispose();
				
				########## Suppression ##########
				//On supprime virtuellement (changement de signe des bornes) les enfants.
				PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "gallery_cats SET id_left = - id_left, id_right = - id_right WHERE id " . (empty($list_cats) ? '= : id' : 'IN :ids_list'), array('id' => $id, 'ids_list' => $list_cats));
				//On modifie les bornes droites des parents.
				if (!empty($list_parent_cats))
				{
					PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "gallery_cats SET id_right = id_right - :new_number_cats, nbr_pics_aprob = nbr_pics_aprob - :number_pics_aprob, nbr_pics_unaprob = nbr_pics_unaprob - :number_pics_unaprob WHERE id IN :ids_list", array('new_number_cats' => ($nbr_cat*2), 'number_pics_aprob' => $nbr_pics_aprob, 'number_pics_unaprob' => $nbr_pics_unaprob, 'ids_list' => $list_parent_cats));
				}
				
				//On réduit la taille de l'arbre du nombre de galeries supprimées à partir de la position de celui-ci.
				PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "gallery_cats SET id_left = id_left - :new_number_cats, id_right = id_right - :new_number_cats WHERE id_left > :id_right", array('new_number_cats' => ($nbr_cat*2), 'id_right' => $CAT_GALLERY[$id]['id_right']));
				
				########## Ajout ##########
				//On modifie les bornes droites des parents de la cible.
				PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "gallery_cats SET id_right = id_right + :new_number_cats, nbr_pics_aprob = nbr_pics_aprob + :number_pics_aprob, nbr_pics_unaprob = nbr_pics_unaprob + :number_pics_unaprob WHERE id " . (empty($list_parent_cats_to) ? '= : id' : 'IN :ids_list'), array('new_number_cats' => ($nbr_cat*2), 'number_pics_aprob' => $nbr_pics_aprob, 'number_pics_unaprob' => $nbr_pics_unaprob, 'id' => $to, 'ids_list' => $list_parent_cats_to));
				
				//On augmente la taille de l'arbre du nombre de galeries supprimées à partir de la position de la galerie cible.
				if ($CAT_GALLERY[$id]['id_left'] > $CAT_GALLERY[$to]['id_left'] ) //Direction galerie source -> galerie cible.
					$limit = $CAT_GALLERY[$to]['id_right'];
				else
					$limit = $CAT_GALLERY[$to]['id_right'] - ($nbr_cat*2);
				
				PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "gallery_cats SET id_left = id_left + :new_number_cats, id_right + id_right - :new_number_cats WHERE id_left > :id_right", array('new_number_cats' => ($nbr_cat*2), 'id_right' => $limit));
				$end = $limit + ($nbr_cat*2) - 1;
				
				//On replace les galeries supprimées virtuellement.
				$z = 0;
				for ($i = $limit; $i <= $end; $i = $i + 2)
				{
					$id_left = $limit + ($CAT_GALLERY[$list_cats[$z]]['id_left'] - $CAT_GALLERY[$id]['id_left']);
					$id_right = $end - ($CAT_GALLERY[$id]['id_right'] - $CAT_GALLERY[$list_cats[$z]]['id_right']);
					PersistenceContext::get_querier()->update(PREFIX . "gallery_cats", array('id_left' => $id_left, 'id_right' => $id_right), 'WHERE id = :id', array('id' => $list_cats[$z]));
					$z++;
				}
				
				$Cache->Generate_module_file('gallery');
			}
			
			//Génération de la liste des catégories en cache.
			$list_cats_js = '';
			$array_js = '';	
			$i = 0;
			$result = PersistenceContext::get_querier()->select("SELECT id, id_left, id_right
			FROM " . PREFIX . "gallery_cats 
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