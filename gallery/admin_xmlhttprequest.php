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

define('NO_SESSION_LOCATION', true); //Permet de ne pas mettre jour la page dans la session.
require_once('../kernel/begin.php');
require_once('../kernel/header_no_display.php');

if ($User->check_level(ADMIN_LEVEL)) //Admin
{	
	$Cache->load('gallery');
	$Session->csrf_get_protect(); //Protection csrf
	
	$move = !empty($_GET['move']) ? trim($_GET['move']) : 0;
	$id = !empty($_GET['id']) ? numeric($_GET['id']) : 0;
	$get_parent_up = !empty($_GET['g_up']) ? numeric($_GET['g_up']) : 0;
	$get_parent_down = !empty($_GET['g_down']) ? numeric($_GET['g_down']) : 0;

	//Récupération de la catégorie d'échange.
	if (!empty($get_parent_up))
	{
		$switch_id_cat = $Sql->query("SELECT id FROM " . PREFIX . "gallery_cats WHERE '" . $CAT_GALLERY[$get_parent_up]['id_left'] . "' - id_right = 1", __LINE__, __FILE__);
		if (!empty($switch_id_cat))
			echo $switch_id_cat;
		else
		{	
			//Galeries parentes de la galerie à supprimer.
			$list_parent_cats = '';
			$result = $Sql->query_while("SELECT id 
			FROM " . PREFIX . "gallery_cats 
			WHERE id_left < '" . $CAT_GALLERY[$get_parent_up]['id_left'] . "' AND id_right > '" . $CAT_GALLERY[$get_parent_up]['id_right'] . "'", __LINE__, __FILE__);
			while ($row = $Sql->fetch_assoc($result))
			{
				$list_parent_cats .= $row['id'] . ', ';
			}
			$Sql->query_close($result);
			$list_parent_cats = trim($list_parent_cats, ', ');
			
			if (!empty($list_parent_cats))
			{
				//Changement de catégorie.
				$change_cat = $Sql->query("SELECT id FROM " . PREFIX . "gallery_cats
				WHERE id_left < '" . $CAT_GALLERY[$get_parent_up]['id_left'] . "' AND level = '" . ($CAT_GALLERY[$get_parent_up]['level'] - 1) . "' AND
				id NOT IN (" . $list_parent_cats . ")
				ORDER BY id_left DESC" . 
				$Sql->limit(0, 1), __LINE__, __FILE__);
				if (isset($CAT_GALLERY[$change_cat]))
				{	
					$switch_id_cat = $Sql->query("SELECT id FROM " . PREFIX . "gallery_cats 
					WHERE id_left > '" . $CAT_GALLERY[$change_cat]['id_right'] . "'
					ORDER BY id_left" . 
					$Sql->limit(0, 1), __LINE__, __FILE__);
				}
				if (!empty($switch_id_cat))
					echo 's' . $switch_id_cat;
			}
		}	
	}
	elseif (!empty($get_parent_down))
	{
		$switch_id_cat = $Sql->query("SELECT id FROM " . PREFIX . "gallery_cats WHERE id_left - '" . $CAT_GALLERY[$get_parent_down]['id_right'] . "' = 1", __LINE__, __FILE__);
		if (!empty($switch_id_cat))
			echo $switch_id_cat;
		else
		{	
			$change_cat = $Sql->query("SELECT id FROM " . PREFIX . "gallery_cats
			WHERE id_left > '" . $CAT_GALLERY[$get_parent_down]['id_left'] . "' AND level = '" . ($CAT_GALLERY[$get_parent_down]['level'] - 1) . "'
			ORDER BY id_left" . 
			$Sql->limit(0, 1), __LINE__, __FILE__);
			if (isset($CAT_GALLERY[$change_cat]))
			{	
				$switch_id_cat = $Sql->query("SELECT id FROM " . PREFIX . "gallery_cats 
				WHERE id_left < '" . $CAT_GALLERY[$change_cat]['id_right'] . "'
				ORDER BY id_left DESC" . 
				$Sql->limit(0, 1), __LINE__, __FILE__);
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
			$list_parent_cats = '';
			$result = $Sql->query_while("SELECT id 
			FROM " . PREFIX . "gallery_cats 
			WHERE id_left < '" . $CAT_GALLERY[$id]['id_left'] . "' AND id_right > '" . $CAT_GALLERY[$id]['id_right'] . "'", __LINE__, __FILE__);
			while ($row = $Sql->fetch_assoc($result))
			{
				$list_parent_cats .= $row['id'] . ', ';
			}
			$Sql->query_close($result);
			$list_parent_cats = trim($list_parent_cats, ', ');
			
			$to = 0;
			if ($move == 'up')
			{	
				//Même catégorie
				$switch_id_cat = $Sql->query("SELECT id FROM " . PREFIX . "gallery_cats
				WHERE '" . $CAT_GALLERY[$id]['id_left'] . "' - id_right = 1", __LINE__, __FILE__);		
				if (!empty($switch_id_cat))
				{
					//On monte la catégorie à déplacer, on lui assigne des id négatifs pour assurer l'unicité.
					$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET id_left = - id_left + '" . ($CAT_GALLERY[$switch_id_cat]['id_right'] - $CAT_GALLERY[$switch_id_cat]['id_left'] + 1) . "', id_right = - id_right + '" . ($CAT_GALLERY[$switch_id_cat]['id_right'] - $CAT_GALLERY[$switch_id_cat]['id_left'] + 1) . "' WHERE id_left BETWEEN '" . $CAT_GALLERY[$id]['id_left'] . "' AND '" . $CAT_GALLERY[$id]['id_right'] . "'", __LINE__, __FILE__);
					//On descend la catégorie cible.
					$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET id_left = id_left + '" . ($CAT_GALLERY[$id]['id_right'] - $CAT_GALLERY[$id]['id_left'] + 1) . "', id_right = id_right + '" . ($CAT_GALLERY[$id]['id_right'] - $CAT_GALLERY[$id]['id_left'] + 1) . "' WHERE id_left BETWEEN '" . $CAT_GALLERY[$switch_id_cat]['id_left'] . "' AND '" . $CAT_GALLERY[$switch_id_cat]['id_right'] . "'", __LINE__, __FILE__);
					
					//On rétablit les valeurs absolues.
					$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET id_left = - id_left WHERE id_left < 0", __LINE__, __FILE__);
					$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET id_right = - id_right WHERE id_right < 0", __LINE__, __FILE__);	
					
					$Cache->Generate_module_file('gallery');
				}		
				elseif (!empty($list_parent_cats) )
				{
					//Changement de catégorie.
					$to = $Sql->query("SELECT id FROM " . PREFIX . "gallery_cats
					WHERE id_left < '" . $CAT_GALLERY[$id]['id_left'] . "' AND level = '" . ($CAT_GALLERY[$id]['level'] - 1) . "' AND
					id NOT IN (" . $list_parent_cats . ")
					ORDER BY id_left DESC" . 
					$Sql->limit(0, 1), __LINE__, __FILE__);
				}
			}
			elseif ($move == 'down')
			{
				//Doit-on changer de catégorie parente ou non ?
				$switch_id_cat = $Sql->query("SELECT id FROM " . PREFIX . "gallery_cats
				WHERE id_left - '" . $CAT_GALLERY[$id]['id_right'] . "' = 1", __LINE__, __FILE__);
				if (!empty($switch_id_cat))
				{
					//On monte la catégorie à déplacer, on lui assigne des id négatifs pour assurer l'unicité.
					$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET id_left = - id_left - '" . ($CAT_GALLERY[$switch_id_cat]['id_right'] - $CAT_GALLERY[$switch_id_cat]['id_left'] + 1) . "', id_right = - id_right - '" . ($CAT_GALLERY[$switch_id_cat]['id_right'] - $CAT_GALLERY[$switch_id_cat]['id_left'] + 1) . "' WHERE id_left BETWEEN '" . $CAT_GALLERY[$id]['id_left'] . "' AND '" . $CAT_GALLERY[$id]['id_right'] . "'", __LINE__, __FILE__);
					//On descend la catégorie cible.
					$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET id_left = id_left - '" . ($CAT_GALLERY[$id]['id_right'] - $CAT_GALLERY[$id]['id_left'] + 1) . "', id_right = id_right - '" . ($CAT_GALLERY[$id]['id_right'] - $CAT_GALLERY[$id]['id_left'] + 1) . "' WHERE id_left BETWEEN '" . $CAT_GALLERY[$switch_id_cat]['id_left'] . "' AND '" . $CAT_GALLERY[$switch_id_cat]['id_right'] . "'", __LINE__, __FILE__);
					
					//On rétablit les valeurs absolues.
					$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET id_left = - id_left WHERE id_left < 0", __LINE__, __FILE__);
					$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET id_right = - id_right WHERE id_right < 0", __LINE__, __FILE__);
					
					$Cache->Generate_module_file('gallery');
				}
				elseif (!empty($list_parent_cats) )
				{
					//Changement de catégorie.
					$to = $Sql->query("SELECT id FROM " . PREFIX . "gallery_cats
					WHERE id_left > '" . $CAT_GALLERY[$id]['id_left'] . "' AND level = '" . ($CAT_GALLERY[$id]['level'] - 1) . "'
					ORDER BY id_left" . 
					$Sql->limit(0, 1), __LINE__, __FILE__);
				}
			}

			if (!empty($to)) //Changement de catégorie possible?
			{
				//On vérifie si la catégorie contient des sous galeries.
				$nbr_cat = (($CAT_GALLERY[$id]['id_right'] - $CAT_GALLERY[$id]['id_left'] - 1) / 2) + 1;
			
				//Sous galeries de la galerie à déplacer.
				$list_cats = '';
				$result = $Sql->query_while("SELECT id
				FROM " . PREFIX . "gallery_cats 
				WHERE id_left BETWEEN '" . $CAT_GALLERY[$id]['id_left'] . "' AND '" . $CAT_GALLERY[$id]['id_right'] . "'
				ORDER BY id_left", __LINE__, __FILE__);
				while ($row = $Sql->fetch_assoc($result))
				{
					$list_cats .= $row['id'] . ', ';
				}
				$Sql->query_close($result);
				$list_cats = trim($list_cats, ', ');
			
				if (empty($list_cats))
					$clause_cats = " id = '" . $id . "'";
				else
					$clause_cats = " id IN (" . $list_cats . ")";
					
				//Récupération du nombre d'images de la galerie.
				$nbr_pics_aprob = $Sql->query("SELECT nbr_pics_aprob FROM " . PREFIX . "gallery_cats WHERE id = '" . $id . "'", __LINE__, __FILE__);
				$nbr_pics_unaprob = $Sql->query("SELECT nbr_pics_unaprob FROM " . PREFIX . "gallery_cats WHERE id = '" . $id . "'", __LINE__, __FILE__);
				
				//Galeries parentes de la galerie cible.
				$list_parent_cats_to = '';
				$result = $Sql->query_while("SELECT id, level 
				FROM " . PREFIX . "gallery_cats 
				WHERE id_left <= '" . $CAT_GALLERY[$to]['id_left'] . "' AND id_right >= '" . $CAT_GALLERY[$to]['id_right'] . "'", __LINE__, __FILE__);
				while ($row = $Sql->fetch_assoc($result))
				{
					$list_parent_cats_to .= $row['id'] . ', ';
				}
				$Sql->query_close($result);
				$list_parent_cats_to = trim($list_parent_cats_to, ', ');
			
				if (empty($list_parent_cats_to))
					$clause_parent_cats_to = " id = '" . $to . "'";
				else
					$clause_parent_cats_to = " id IN (" . $list_parent_cats_to . ")";
					
				########## Suppression ##########
				//On supprime virtuellement (changement de signe des bornes) les enfants.
				$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET id_left = - id_left, id_right = - id_right WHERE " . $clause_cats, __LINE__, __FILE__);					
				//On modifie les bornes droites des parents.
				if (!empty($list_parent_cats))
				{
					$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET id_right = id_right - '" . ( $nbr_cat*2) . "', nbr_pics_aprob = nbr_pics_aprob - '" . $nbr_pics_aprob . "', nbr_pics_unaprob = nbr_pics_unaprob - '" . $nbr_pics_unaprob . "' WHERE id IN (" . $list_parent_cats . ")", __LINE__, __FILE__);
				}
				
				//On réduit la taille de l'arbre du nombre de galeries supprimées à partir de la position de celui-ci.
				$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET id_left = id_left - '" . ($nbr_cat*2) . "', id_right = id_right - '" . ($nbr_cat*2) . "' WHERE id_left > '" . $CAT_GALLERY[$id]['id_right'] . "'", __LINE__, __FILE__);

				########## Ajout ##########
				//On modifie les bornes droites des parents de la cible.
				$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET id_right = id_right + '" . ($nbr_cat*2) . "', nbr_pics_aprob = nbr_pics_aprob + '" . $nbr_pics_aprob . "', nbr_pics_unaprob = nbr_pics_unaprob + '" . $nbr_pics_unaprob . "' WHERE " . $clause_parent_cats_to, __LINE__, __FILE__);

				//On augmente la taille de l'arbre du nombre de galeries supprimées à partir de la position de la galerie cible.
				if ($CAT_GALLERY[$id]['id_left'] > $CAT_GALLERY[$to]['id_left'] ) //Direction galerie source -> galerie cible.
				{	
					$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET id_left = id_left + '" . ($nbr_cat*2) . "', id_right = id_right + '" . ($nbr_cat*2) . "' WHERE id_left > '" . $CAT_GALLERY[$to]['id_right'] . "'", __LINE__, __FILE__);						
					$limit = $CAT_GALLERY[$to]['id_right'];
					$end = $limit + ($nbr_cat*2) - 1;
				}
				else
				{	
					$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET id_left = id_left + '" . ($nbr_cat*2) . "', id_right = id_right + '" . ($nbr_cat*2) . "' WHERE id_left > '" . ($CAT_GALLERY[$to]['id_right'] - ($nbr_cat*2)) . "'", __LINE__, __FILE__);
					$limit = $CAT_GALLERY[$to]['id_right'] - ($nbr_cat*2);
					$end = $limit + ($nbr_cat*2) - 1;						
				}	

				//On replace les galeries supprimées virtuellement.
				$array_sub_cats = explode(', ', $list_cats);
				$z = 0;
				for ($i = $limit; $i <= $end; $i = $i + 2)
				{
					$id_left = $limit + ($CAT_GALLERY[$array_sub_cats[$z]]['id_left'] - $CAT_GALLERY[$id]['id_left']);
					$id_right = $end - ($CAT_GALLERY[$id]['id_right'] - $CAT_GALLERY[$array_sub_cats[$z]]['id_right']);
					$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET id_left = '" . $id_left . "', id_right = '" . $id_right . "' WHERE id = '" . $array_sub_cats[$z] . "'", __LINE__, __FILE__);
					$z++;
				}
				
				$Cache->Generate_module_file('gallery');
			}
			
			//Génération de la liste des catégories en cache.
			$list_cats_js = '';
			$array_js = '';	
			$i = 0;
			$result = $Sql->query_while("SELECT id, id_left, id_right
			FROM " . PREFIX . "gallery_cats 
			ORDER BY id_left", __LINE__, __FILE__);
			while ($row = $Sql->fetch_assoc($result))
			{
				$list_cats_js .= $row['id'] . ', ';		
				$array_js .= 'array_cats[' . $row['id'] . '][\'id\'] = ' . $row['id'] . ";";
				$array_js .= 'array_cats[' . $row['id'] . '][\'id_left\'] = ' . $row['id_left'] . ";";
				$array_js .= 'array_cats[' . $row['id'] . '][\'id_right\'] = ' . $row['id_right'] . ";";
				$array_js .= 'array_cats[' . $row['id'] . '][\'i\'] = ' . $i . ";";
				$i++;
			}
			$Sql->query_close($result);
			echo 'list_cats = new Array(' . trim($list_cats_js, ', ') . ');' . $array_js;
		}	
	}
}

?>