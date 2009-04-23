<?php
/*##################################################
 *                               admin_articles_cat.php
 *                            -------------------
 *   begin                : August 27, 2007
 *   copyright          : (C) 2007 Viarre Régis
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

require_once('../admin/admin_begin.php');
load_module_lang('articles'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');
		
$id = retrieve(GET, 'id', 0);
$del = retrieve(GET, 'del', 0);
$move = retrieve(GET, 'move', '', TSTRING_UNCHANGE);
$root = retrieve(GET, 'root', 0);

define('READ_CAT_ARTICLES', 0x01);
define('WRITE_CAT_ARTICLES', 0x02);
define('EDIT_CAT_ARTICLES', 0x04);

//Si c'est confirmé on execute
if (!empty($_POST['valid']) && !empty($id))
{
	$Cache->load('articles');
	
	$to = retrieve(POST, 'category', 0);
	$name = retrieve(POST, 'name', '');
	$contents = retrieve(POST, 'desc', '');
	$icon = retrieve(POST, 'icon', '');
	$icon_path = retrieve(POST, 'icon_path', '');
	$aprob = retrieve(POST, 'aprob', 1);

	//Génération du tableau des droits.
	$array_auth_all = Authorizations::build_auth_array_from_form(READ_CAT_ARTICLES);
	
	if (!empty($name))
	{
		$icon = !empty($icon) ? $icon : $icon_path;
		$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET name = '" . $name . "', contents = '"  . $contents . "', aprob = '" . $aprob . "', icon = '" . $icon . "', auth = '" . addslashes(serialize($array_auth_all)) . "' WHERE id = '" . $id . "'", __LINE__, __FILE__);

		//Empêche le déplacement dans une catégorie fille.
		$to = $Sql->query("SELECT id FROM " . PREFIX . "articles_cats WHERE id = '" . $to . "' AND id_left NOT BETWEEN '" . $CAT_ARTICLES[$id]['id_left'] . "' AND '" . $CAT_ARTICLES[$id]['id_right'] . "'", __LINE__, __FILE__);
		 
		//Catégorie parente changée?
		$change_cat = !empty($to) ? !($CAT_ARTICLES[$to]['id_left'] < $CAT_ARTICLES[$id]['id_left'] && $CAT_ARTICLES[$to]['id_right'] > $CAT_ARTICLES[$id]['id_right'] && ($CAT_ARTICLES[$id]['level'] - 1) == $CAT_ARTICLES[$to]['level']) : $CAT_ARTICLES[$id]['level'] > 0;
		if ($change_cat)
		{
			//On vérifie si l'articles contient des sous catégories.
			$nbr_cat = (($CAT_ARTICLES[$id]['id_right'] - $CAT_ARTICLES[$id]['id_left'] - 1) / 2) + 1;
		
			//Sous catégories de l'article à supprimer.
			$list_cats = '';
			$result = $Sql->query_while("SELECT id
			FROM " . PREFIX . "articles_cats
			WHERE id_left BETWEEN '" . $CAT_ARTICLES[$id]['id_left'] . "' AND '" . $CAT_ARTICLES[$id]['id_right'] . "'
			ORDER BY id_left", __LINE__, __FILE__);
			
			while ($row = $Sql->fetch_assoc($result))
				$list_cats .= $row['id'] . ', ';
			
			$Sql->query_close($result);
			$list_cats = trim($list_cats, ', ');
			
			//Catégories parentes de l'article à supprimer.
			$list_parent_cats = '';
			$result = $Sql->query_while("SELECT id
			FROM " . PREFIX . "articles_cats
			WHERE id_left < '" . $CAT_ARTICLES[$id]['id_left'] . "' AND id_right > '" . $CAT_ARTICLES[$id]['id_right'] . "'", __LINE__, __FILE__);
			
			while ($row = $Sql->fetch_assoc($result))
				$list_parent_cats .= $row['id'] . ', ';
				
			$Sql->query_close($result);
			$list_parent_cats = trim($list_parent_cats, ', ');
			
			//Précaution pour éviter erreur fatale, cas impossible si cohérence de l'arbre respectée.
			if (empty($list_cats))
				redirect(HOST . SCRIPT);
			
			//Catégories parentes de l'article cible.
			if (!empty($to))
			{
				$list_parent_cats_to = '';
				$result = $Sql->query_while("SELECT id
				FROM " . PREFIX . "articles_cats
				WHERE id_left <= '" . $CAT_ARTICLES[$to]['id_left'] . "' AND id_right >= '" . $CAT_ARTICLES[$to]['id_right'] . "'", __LINE__, __FILE__);
				while ($row = $Sql->fetch_assoc($result))
					$list_parent_cats_to .= $row['id'] . ', ';
				
				$Sql->query_close($result);
				$list_parent_cats_to = trim($list_parent_cats_to, ', ');
						
				if (empty($list_parent_cats_to))
					$clause_parent_cats_to = " id = '" . $to . "'";
				else
					$clause_parent_cats_to = " id IN (" . $list_parent_cats_to . ")";
			}

			########## Suppression ##########
			//On supprime virtuellement (changement de signe des bornes) les enfants.
			$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_left = - id_left, id_right = - id_right WHERE id IN (" . $list_cats . ")", __LINE__, __FILE__);
			
			//Récupération du nombre d'articles de l'article.
			$nbr_articles_visible = $Sql->query("SELECT nbr_articles_visible FROM " . PREFIX . "articles_cats WHERE id = '" . $id . "'", __LINE__, __FILE__);
			$nbr_articles_unvisible = $Sql->query("SELECT nbr_articles_unvisible FROM " . PREFIX . "articles_cats WHERE id = '" . $id . "'", __LINE__, __FILE__);
			
			//On modifie les bornes droites des parents et le nbr d'articles.
			if (!empty($list_parent_cats))
			{
				$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_right = id_right - '" . ( $nbr_cat*2) . "', nbr_articles_visible = nbr_articles_visible - " . numeric($nbr_articles_visible) . ", nbr_articles_unvisible = nbr_articles_unvisible - " . numeric($nbr_articles_unvisible) . " WHERE id IN (" . $list_parent_cats . ")", __LINE__, __FILE__);
			}
			
			//On réduit la taille de l'arbre du nombre de articles supprimées à partir de la position de celui-ci.
			$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_left = id_left - '" . ($nbr_cat*2) . "', id_right = id_right - '" . ($nbr_cat*2) . "' WHERE id_left > '" . $CAT_ARTICLES[$id]['id_right'] . "'", __LINE__, __FILE__);

			########## Ajout ##########
			if (!empty($to)) //Galerie cible différent de la racine.
			{
				//On modifie les bornes droites et le nbr d'articles des parents de la cible.
				$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_right = id_right + '" . ($nbr_cat*2) . "', nbr_articles_visible = nbr_articles_visible + " . numeric($nbr_articles_visible) . ", nbr_articles_unvisible = nbr_articles_unvisible + " . numeric($nbr_articles_unvisible) . " WHERE " . $clause_parent_cats_to, __LINE__, __FILE__);

				//On augmente la taille de l'arbre du nombre de articles supprimées à partir de la position de l'article cible.
				$array_parents_cats = explode(', ', $list_parent_cats);
				if ($CAT_ARTICLES[$id]['id_left'] > $CAT_ARTICLES[$to]['id_left'] && !in_array($to, $array_parents_cats) ) //Direction forum source -> forum cible, et source non incluse dans la cible.
				{
					$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_left = id_left + '" . ($nbr_cat*2) . "', id_right = id_right + '" . ($nbr_cat*2) . "' WHERE id_left > '" . $CAT_ARTICLES[$to]['id_right'] . "'", __LINE__, __FILE__);
					$limit = $CAT_ARTICLES[$to]['id_right'];
					$end = $limit + ($nbr_cat*2) - 1;
				}
				else
				{
					$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_left = id_left + '" . ($nbr_cat*2) . "', id_right = id_right + '" . ($nbr_cat*2) . "' WHERE id_left > '" . ($CAT_ARTICLES[$to]['id_right'] - ($nbr_cat*2)) . "'", __LINE__, __FILE__);
					$limit = $CAT_ARTICLES[$to]['id_right'] - ($nbr_cat*2);
					$end = $limit + ($nbr_cat*2) - 1;
				}
				//On replace les articles supprimées virtuellement.
				$array_sub_cats = explode(', ', $list_cats);
				$z = 0;
				for ($i = $limit; $i <= $end; $i = $i + 2)
				{
					$id_left = $limit + ($CAT_ARTICLES[$array_sub_cats[$z]]['id_left'] - $CAT_ARTICLES[$id]['id_left']);
					$id_right = $end - ($CAT_ARTICLES[$id]['id_right'] - $CAT_ARTICLES[$array_sub_cats[$z]]['id_right']);
					$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_left = '" . $id_left . "', id_right = '" . $id_right . "' WHERE id = '" . $array_sub_cats[$z] . "'", __LINE__, __FILE__);
					$z++;
				}
					
				//On met à jour la nouvelle article.
				$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET level = level - '" . (($CAT_ARTICLES[$id]['level'] - $CAT_ARTICLES[$to]['level']) - 1) . "' WHERE id IN (" . $list_cats . ")", __LINE__, __FILE__);
			}
			else //Racine
			{
				$max_id = $Sql->query("SELECT MAX(id_right) FROM " . PREFIX . "articles_cats", __LINE__, __FILE__);
				//On replace les articles supprimées virtuellement.
				$array_sub_cats = explode(', ', $list_cats);
				$z = 0;
				$limit = $max_id + 1;
				$end = $limit + ($nbr_cat*2) - 1;
				for ($i = $limit; $i <= $end; $i = $i + 2)
				{
					$id_left = $limit + ($CAT_ARTICLES[$array_sub_cats[$z]]['id_left'] - $CAT_ARTICLES[$id]['id_left']);
					$id_right = $end - ($CAT_ARTICLES[$id]['id_right'] - $CAT_ARTICLES[$array_sub_cats[$z]]['id_right']);
					$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_left = '" . $id_left . "', id_right = '" . $id_right . "' WHERE id = '" . $array_sub_cats[$z] . "'", __LINE__, __FILE__);
					$z++;
				}
				$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET level = level - '" . ($CAT_ARTICLES[$id]['level'] - $CAT_ARTICLES[$to]['level']) . "' WHERE id IN (" . $list_cats . ")", __LINE__, __FILE__);
			}
		}
		
		$Cache->Generate_module_file('articles');
	}
	else
		redirect(HOST . DIR . '/articles/admin_articles_cat.php?id=' . $id . '&error=incomplete');
    
    import('content/syndication/feed');
    Feed::clear_cache('articles');
    
	redirect(HOST . DIR . '/articles/admin_articles_cat.php');
}
elseif (!empty($_POST['valid_root'])) //Modification des autorisations de la racine.
{
	$Cache->load('articles');
	
	$array_auth_all = Authorizations::build_auth_array_from_form(READ_CAT_ARTICLES);
	$CONFIG_ARTICLES['auth_root'] = serialize($array_auth_all);
	
	$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($CONFIG_ARTICLES)) . "' WHERE name = 'articles'", __LINE__, __FILE__);
	$Cache->Generate_module_file('articles');
	
    import('content/syndication/feed');
    Feed::clear_cache('articles');
    
	redirect(HOST . DIR . '/articles/admin_articles_cat.php');
}
elseif (!empty($del)) //Suppression de l'articles/sous-catégorie.
{
	$Session->csrf_get_protect(); //Protection csrf
	
	$Cache->load('articles');
	
	$confirm_delete = false;
	
	$idcat = $Sql->query("SELECT id FROM " . PREFIX . "articles_cats WHERE id = '" . $del . "'", __LINE__, __FILE__);
	if (!empty($idcat) && isset($CAT_ARTICLES[$idcat]))
	{
		//On vérifie si l'articles contient des sous catégories.
		$nbr_sub_cat = (($CAT_ARTICLES[$idcat]['id_right'] - $CAT_ARTICLES[$idcat]['id_left'] - 1) / 2);
		//On vérifie si l'articles ne contient pas d'articles.
		$check_articles = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "articles WHERE idcat = '" . $idcat . "'", __LINE__, __FILE__);
		
		if ($check_articles == 0 && $nbr_sub_cat == 0) //Si vide on supprime simplement, l'articles.
		{
			$confirm_delete = true;
		}
		else //Sinon on propose de déplacer les images existantes dans une autre article.
		{
			if (empty($_POST['del_cat']))
			{
				$Template->set_filenames(array(
					'admin_articles_cat_del'=> 'articles/admin_articles_cat_del.tpl'
				));

				if ($check_articles > 0) //Conserve les images.
				{
					//Listing des catégories disponibles, sauf celle qui va être supprimée.
					$subcats = '<option value="0">' . $LANG['root'] . '</option>';
					$result = $Sql->query_while("SELECT id, name, level
					FROM " . PREFIX . "articles_cats
					WHERE id_left NOT BETWEEN '" . $CAT_ARTICLES[$idcat]['id_left'] . "' AND '" . $CAT_ARTICLES[$idcat]['id_right'] . "'
					ORDER BY id_left", __LINE__, __FILE__);
					while ($row = $Sql->fetch_assoc($result))
					{
						$margin = ($row['level'] > 0) ? str_repeat('--------', $row['level']) : '--';
						$subcats .= '<option value="' . $row['id'] . '">' . $margin . ' ' . $row['name'] . '</option>';
					}
					$Sql->query_close($result);
					
					$Template->assign_block_vars('articles', array(
						'CATEGORIES' => $subcats,
						'L_KEEP' => $LANG['keep_articles'],
						'L_MOVE_TOPICS' => $LANG['move_articles_to'],
						'L_EXPLAIN_CAT' => sprintf($LANG['error_warning'], sprintf((($check_articles > 1) ? $LANG['explain_articles'] : $LANG['explain_article']), $check_articles), '', '')
					));
				}
				if ($nbr_sub_cat > 0) //Converse uniquement les sous-articles.
				{
					//Listing des catégories disponibles, sauf celle qui va être supprimée.
					$subcats = '<option value="0">' . $LANG['root'] . '</option>';
					$result = $Sql->query_while("SELECT id, name, level
					FROM " . PREFIX . "articles_cats
					WHERE id_left NOT BETWEEN '" . $CAT_ARTICLES[$idcat]['id_left'] . "' AND '" . $CAT_ARTICLES[$idcat]['id_right'] . "'
					ORDER BY id_left", __LINE__, __FILE__);
					while ($row = $Sql->fetch_assoc($result))
					{
						$margin = ($row['level'] > 0) ? str_repeat('--------', $row['level']) : '--';
						$subcats .= '<option value="' . $row['id'] . '">' . $margin . ' ' . $row['name'] . '</option>';
					}
					$Sql->query_close($result);
					
					$Template->assign_block_vars('subcats', array(
						'CATEGORIES' => $subcats,
						'L_KEEP' => $LANG['keep_subcat'],
						'L_MOVE_CATEGORIES' => $LANG['move_subcat_to'],
						'L_EXPLAIN_CAT' => sprintf($LANG['error_warning'], sprintf((($nbr_sub_cat > 1) ? $LANG['explain_subcat'] : $LANG['explain_subcat']), $nbr_sub_cat), '', '')
					));
				}
		
				$articles_name = $Sql->query("SELECT name FROM " . PREFIX . "articles_cats WHERE id = '" . $idcat . "'", __LINE__, __FILE__);
				$Template->assign_vars(array(
					'IDCAT' => $idcat,
					'ARTICLES_NAME' => $articles_name,
					'L_REQUIRE_SUBCAT' => $LANG['require_subcat'],
					'L_ARTICLES_MANAGEMENT' => $LANG['articles_management'],
					'L_ARTICLES_ADD' => $LANG['articles_add'],
					'L_ARTICLES_CAT' => $LANG['cat_management'],
					'L_ARTICLES_CONFIG' => $LANG['articles_config'],
					'L_ARTICLES_CAT_ADD' => $LANG['articles_cats_add'],
					'L_CAT_TARGET' => $LANG['cat_target'],
					'L_DEL_ALL' => $LANG['del_all'],
					'L_DEL_ARTICLES_CONTENTS' => sprintf($LANG['del_articles_contents'], $articles_name),
					'L_SUBMIT' => $LANG['submit'],
				));
				
				$Template->pparse('admin_articles_cat_del'); //Traitement du modele
			}
			else //Traitements.
			{
				if (!empty($_POST['del_conf']))
				{
					$confirm_delete = true;
				}
				else
				{
					//Déplacement de sous catégories.
					$f_to = retrieve(POST, 'f_to', 0);
					$f_to = $Sql->query("SELECT id FROM " . PREFIX . "articles_cats WHERE id = '" . $f_to . "' AND id_left NOT BETWEEN '" . $CAT_ARTICLES[$idcat]['id_left'] . "' AND '" . $CAT_ARTICLES[$idcat]['id_right'] . "'", __LINE__, __FILE__);
					
					//Déplacement d'articles
					$t_to = !empty($_POST['t_to']) ? numeric($_POST['t_to']) : 0;
					$t_to = $Sql->query("SELECT id FROM " . PREFIX . "articles_cats WHERE id = '" . $t_to . "' AND id <> '" . $idcat . "'", __LINE__, __FILE__);
					
					####Déplacement des articles dans la catégorie sélectionnée.####
					//Catégories parentes de l'article à supprimer.
					$list_parent_cats = '';
					$result = $Sql->query_while("SELECT id
					FROM " . PREFIX . "articles_cats
					WHERE id_left < '" . $CAT_ARTICLES[$idcat]['id_left'] . "' AND id_right > '" . $CAT_ARTICLES[$idcat]['id_right'] . "'", __LINE__, __FILE__);
					while ($row = $Sql->fetch_assoc($result))
						$list_parent_cats .= $row['id'] . ', ';
					
					$Sql->query_close($result);
					$list_parent_cats = trim($list_parent_cats, ', ');
					
					//On va chercher la somme du nombre d'articles
					$nbr_articles_visible = $Sql->query("SELECT nbr_articles_visible FROM " . PREFIX . "articles_cats WHERE id = '" . $idcat . "'", __LINE__, __FILE__);
					$nbr_articles_visible = !empty($nbr_articles_visible) ? $nbr_articles_visible : 0;
					$nbr_articles_unvisible = $Sql->query("SELECT nbr_articles_unvisible FROM " . PREFIX . "articles_cats WHERE id = '" . $idcat . "'", __LINE__, __FILE__);
					$nbr_articles_unvisible = !empty($nbr_articles_unvisible) ? $nbr_articles_unvisible : 0;
					
					//On déplace les images dans la nouvelle article.
					$Sql->query_inject("UPDATE " . PREFIX . "articles SET idcat = '" . $t_to . "' WHERE idcat = '" . $idcat . "'", __LINE__, __FILE__);

					//On met à jour la nouvelle article.
					$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET nbr_articles_visible = nbr_articles_visible + " . numeric($nbr_articles_visible) . ", nbr_articles_unvisible = nbr_articles_unvisible + " . numeric($nbr_articles_unvisible) . " WHERE id = '" . $t_to . "'", __LINE__, __FILE__);
					
					//On modifie les bornes droites des parents et le nbr d'articles.
					if (!empty($list_parent_cats))
					{
						$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET nbr_articles_visible = nbr_articles_visible - " . numeric($nbr_articles_visible) . ", nbr_articles_unvisible = nbr_articles_unvisible - " . numeric($nbr_articles_unvisible) . " WHERE id IN (" . $list_parent_cats . ")", __LINE__, __FILE__);
					}
					
					//Présence de sous-cat => déplacement de celles-ci.
					if ($nbr_sub_cat > 0)
					{
						//Sous catégories de la catégorie à supprimer.
						$list_sub_cats = '';
						$result = $Sql->query_while("SELECT id
						FROM " . PREFIX . "articles_cats
						WHERE id_left BETWEEN '" . $CAT_ARTICLES[$idcat]['id_left'] . "' AND '" . $CAT_ARTICLES[$idcat]['id_right'] . "' AND id != '" . $idcat . "' ORDER BY id_left", __LINE__, __FILE__);
						while ($row = $Sql->fetch_assoc($result))
							$list_sub_cats .= $row['id'] . ', ';
						$Sql->query_close($result);
						$list_sub_cats = trim($list_sub_cats, ', ');
						
						//Catégories parentes de la catégorie à supprimer.
						$list_parent_cats = '';
						$result = $Sql->query_while("SELECT id
						FROM " . PREFIX . "articles_cats
						WHERE id_left < '" . $CAT_ARTICLES[$idcat]['id_left'] . "' AND id_right > '" . $CAT_ARTICLES[$idcat]['id_right'] . "'", __LINE__, __FILE__);
						while ($row = $Sql->fetch_assoc($result))
							$list_parent_cats .= $row['id'] . ', ';
						$Sql->query_close($result);
						$list_parent_cats = trim($list_parent_cats, ', ');
						
						//Précaution pour éviter erreur fatale, cas impossible si cohérence de l'arbre respectée.
						if (empty($list_sub_cats))
							redirect(HOST . SCRIPT);

						//Catégories parentes de l'article cible.
						if (!empty($f_to))
						{
							$list_parent_cats_to = '';
							$result = $Sql->query_while("SELECT id
							FROM " . PREFIX . "articles_cats
							WHERE id_left <= '" . $CAT_ARTICLES[$f_to]['id_left'] . "' AND id_right >= '" . $CAT_ARTICLES[$f_to]['id_right'] . "'", __LINE__, __FILE__);
							while ($row = $Sql->fetch_assoc($result))
								$list_parent_cats_to .= $row['id'] . ', ';
							
							$Sql->query_close($result);
							$list_parent_cats_to = trim($list_parent_cats_to, ', ');
						
							if (empty($list_parent_cats_to))
								$clause_parent_cats_to = " id = '" . $f_to . "'";
							else
								$clause_parent_cats_to = " id IN (" . $list_parent_cats_to . ")";
						}
							
						########## Suppression ##########
						//On supprime l'ancienne catégorie.
						$Sql->query_inject("DELETE FROM " . PREFIX . "articles_cats WHERE id = '" . $idcat . "'", __LINE__, __FILE__);
						
						//On supprime virtuellement (changement de signe des bornes) les enfants.
						$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_left = - id_left, id_right = - id_right WHERE id IN (" . $list_sub_cats . ")", __LINE__, __FILE__);
						
						//Récupération du nombre d'articles de l'article.
						$nbr_articles_visible = $Sql->query("SELECT nbr_articles_visible FROM " . PREFIX . "articles_cats WHERE id = '" . $idcat . "'", __LINE__, __FILE__);
						$nbr_articles_unvisible = $Sql->query("SELECT nbr_articles_unvisible FROM " . PREFIX . "articles_cats WHERE id = '" . $idcat . "'", __LINE__, __FILE__);
			
						//On modifie les bornes droites des parents et le nbr d'articles.
						if (!empty($list_parent_cats))
						{
							$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_right = id_right - '" . (2 + $nbr_sub_cat*2) . "', nbr_articles_visible = nbr_articles_visible - " . numeric($nbr_articles_visible) . ", nbr_articles_unvisible = nbr_articles_unvisible - " . numeric($nbr_articles_unvisible) . " WHERE id IN (" . $list_parent_cats . ")", __LINE__, __FILE__);
						}
						
						//On réduit la taille de l'arbre du nombre de article supprimées à partir de la position de celui-ci.
						$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_left = id_left - '" . (2 + $nbr_sub_cat*2) . "', id_right = id_right - '" . (2 + $nbr_sub_cat*2) . "' WHERE id_left > '" . $CAT_ARTICLES[$idcat]['id_right'] . "'", __LINE__, __FILE__);
					
						########## Ajout ##########
						if (!empty($f_to)) //Galerie cible différent de la racine.
						{
							//On modifie les bornes droites et le nbr d'articles des parents de la cible.
							$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_right = id_right + '" . ($nbr_sub_cat*2) . "', nbr_articles_visible = nbr_articles_visible + " . numeric($nbr_articles_visible) . ", nbr_articles_unvisible = nbr_articles_unvisible + " . numeric($nbr_articles_unvisible) . " WHERE " . $clause_parent_cats_to, __LINE__, __FILE__);
							
							//On augmente la taille de l'arbre du nombre de article supprimées à partir de la position de l'article cible.
							$array_parents_cats = explode(', ', $list_parent_cats);
							if ($CAT_ARTICLES[$idcat]['id_left'] > $CAT_ARTICLES[$f_to]['id_left'] && !in_array($f_to, $array_parents_cats) ) //Direction forum source -> forum cible, et source non incluse dans la cible.
							{
								$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_left = id_left + '" . ($nbr_sub_cat*2) . "', id_right = id_right + '" . ($nbr_sub_cat*2) . "' WHERE id_left > '" . $CAT_ARTICLES[$f_to]['id_right'] . "'", __LINE__, __FILE__);
								$limit = $CAT_ARTICLES[$f_to]['id_right'];
								$end = $limit + ($nbr_sub_cat*2) - 1;
							}
							else
							{
								$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_left = id_left + '" . ($nbr_sub_cat*2) . "', id_right = id_right + '" . ($nbr_sub_cat*2) . "' WHERE id_left > '" . ($CAT_ARTICLES[$f_to]['id_right'] - (2 + $nbr_sub_cat*2)) . "'", __LINE__, __FILE__);
								$limit = $CAT_ARTICLES[$f_to]['id_right'] - (2 + $nbr_sub_cat*2);
								$end = $limit + ($nbr_sub_cat*2) - 1;
							}
							
							//On replace les articles supprimées virtuellement.
							$array_sub_cats = explode(', ', $list_sub_cats);
							$z = 0;
							for ($i = $limit; $i <= $end; $i = $i + 2)
							{
								$id_left = $limit + ($CAT_ARTICLES[$array_sub_cats[$z]]['id_left'] - $CAT_ARTICLES[$idcat]['id_left']) - 1;
								$id_right = $end - ($CAT_ARTICLES[$idcat]['id_right'] - $CAT_ARTICLES[$array_sub_cats[$z]]['id_right']) + 1;
								$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_left = '" . $id_left . "', id_right = '" . $id_right . "' WHERE id = '" . $array_sub_cats[$z] . "'", __LINE__, __FILE__);
								$z++;
							}

							//On met à jour le nouveau article.
							$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET level = level - '" . ($CAT_ARTICLES[$idcat]['level'] - $CAT_ARTICLES[$f_to]['level']) . "' WHERE id IN (" . $list_sub_cats . ")", __LINE__, __FILE__);
						}
						else //Racine
						{
							$max_id = $Sql->query("SELECT MAX(id_right) FROM " . PREFIX . "articles_cats", __LINE__, __FILE__);
							//On replace les articles supprimées virtuellement.
							$array_sub_cats = explode(', ', $list_sub_cats);
							$z = 0;
							$limit = $max_id + 1;
							$end = $limit + ($nbr_sub_cat*2) - 1;
							for ($i = $limit; $i <= $end; $i = $i + 2)
							{
								$id_left = $limit + ($CAT_ARTICLES[$array_sub_cats[$z]]['id_left'] - $CAT_ARTICLES[$idcat]['id_left']) - 1;
								$id_right = $end - ($CAT_ARTICLES[$idcat]['id_right'] - $CAT_ARTICLES[$array_sub_cats[$z]]['id_right']) + 1;
								$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_left = '" . $id_left . "', id_right = '" . $id_right . "' WHERE id = '" . $array_sub_cats[$z] . "'", __LINE__, __FILE__);
								$z++;
							}
							$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET level = level - '" . ($CAT_ARTICLES[$idcat]['level'] - $CAT_ARTICLES[$f_to]['level'] + 1) . "' WHERE id IN (" . $list_sub_cats . ")", __LINE__, __FILE__);
						}
					}
					else //On rétabli l'arbre intervallaire.
					{
						$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_right = id_right - 2 WHERE id_left < '" . $CAT_ARTICLES[$idcat]['id_left'] . "' AND id_right > '" . $CAT_ARTICLES[$idcat]['id_right'] . "'", __LINE__, __FILE__);
						$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_left = id_left - 2, id_right = id_right - 2 WHERE id_left > '" . $CAT_ARTICLES[$idcat]['id_right'] . "'", __LINE__, __FILE__);
					}
					
					$Cache->Generate_module_file('articles');
					
					redirect(HOST . SCRIPT);
				}
			}
		}

		if ($confirm_delete) //Confirmation de suppression, on supprime dans la bdd.
		{
			//Catégories parentes de l'article à supprimer.
			$list_parent_cats = '';
			$result = $Sql->query_while("SELECT id
			FROM " . PREFIX . "articles_cats
			WHERE id_left < '" . $CAT_ARTICLES[$idcat]['id_left'] . "' AND id_right > '" . $CAT_ARTICLES[$idcat]['id_right'] . "'", __LINE__, __FILE__);
			while ($row = $Sql->fetch_assoc($result))
				$list_parent_cats .= $row['id'] . ', ';
			
			$Sql->query_close($result);
			$list_parent_cats = trim($list_parent_cats, ', ');
			
			$nbr_del = $CAT_ARTICLES[$idcat]['id_right'] - $CAT_ARTICLES[$idcat]['id_left'] + 1;
			if (!empty($list_parent_cats))
			{
				//Récupération du nombre d'articles de l'article.
				$nbr_articles_visible = $Sql->query("SELECT nbr_articles_visible FROM " . PREFIX . "articles_cats WHERE id = '" . $idcat . "'", __LINE__, __FILE__);
				$nbr_articles_unvisible = $Sql->query("SELECT nbr_articles_unvisible FROM " . PREFIX . "articles_cats WHERE id = '" . $idcat . "'", __LINE__, __FILE__);
				
				$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_right = id_right - '" . $nbr_del . "', nbr_articles_visible = nbr_articles_visible - '" . numeric($nbr_articles_visible) . "', nbr_articles_unvisible = nbr_articles_unvisible - '" . numeric($nbr_articles_unvisible) . "' WHERE id IN (" . $list_parent_cats . ")", __LINE__, __FILE__);
			}
			
			$Sql->query_inject("DELETE FROM " . PREFIX . "articles_cats WHERE id_left BETWEEN '" . $CAT_ARTICLES[$idcat]['id_left'] . "' AND '" . $CAT_ARTICLES[$idcat]['id_right'] . "'", __LINE__, __FILE__);
			$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_left = id_left - '" . $nbr_del . "', id_right = id_right - '" . $nbr_del . "' WHERE id_left > '" . $CAT_ARTICLES[$idcat]['id_right'] . "'", __LINE__, __FILE__);
			$Sql->query_inject("DELETE FROM " . PREFIX . "articles WHERE idcat = '" . $idcat . "'", __LINE__, __FILE__);
			
			###### Regénération du cache #######
			$Cache->Generate_module_file('articles');
			
			redirect(HOST . DIR . '/articles/admin_articles_cat.php');
		}
        import('content/syndication/feed');
        Feed::clear_cache('articles');
    }
	else
		redirect(HOST . DIR . '/articles/admin_articles_cat.php');
}
elseif (!empty($id) && !empty($move)) //Monter/descendre.
{
	$Session->csrf_get_protect(); //Protection csrf
	
	$Cache->load('articles');
	
	//Catégorie existe?
	if (!isset($CAT_ARTICLES[$id]))
		redirect(HOST . DIR . '/articles/admin_articles_cat.php');
    
	//Catégories parentes de l'article à déplacer.
	$list_parent_cats = '';
	$result = $Sql->query_while("SELECT id
	FROM " . PREFIX . "articles_cats
	WHERE id_left < '" . $CAT_ARTICLES[$id]['id_left'] . "' AND id_right > '" . $CAT_ARTICLES[$id]['id_right'] . "'", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
		$list_parent_cats .= $row['id'] . ', ';
	
	$Sql->query_close($result);
	$list_parent_cats = trim($list_parent_cats, ', ');
	
	$to = 0;
	if ($move == 'up')
	{
		//Même catégorie
		$switch_id_cat = $Sql->query("SELECT id FROM " . PREFIX . "articles_cats
		WHERE '" . $CAT_ARTICLES[$id]['id_left'] . "' - id_right = 1", __LINE__, __FILE__);
		if (!empty($switch_id_cat))
		{
			//On monte l'articles à déplacer, on lui assigne des id négatifs pour assurer l'unicité.
			$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_left = - id_left + '" . ($CAT_ARTICLES[$switch_id_cat]['id_right'] - $CAT_ARTICLES[$switch_id_cat]['id_left'] + 1) . "', id_right = - id_right + '" . ($CAT_ARTICLES[$switch_id_cat]['id_right'] - $CAT_ARTICLES[$switch_id_cat]['id_left'] + 1) . "' WHERE id_left BETWEEN '" . $CAT_ARTICLES[$id]['id_left'] . "' AND '" . $CAT_ARTICLES[$id]['id_right'] . "'", __LINE__, __FILE__);
			//On descend l'articles cible.
			$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_left = id_left + '" . ($CAT_ARTICLES[$id]['id_right'] - $CAT_ARTICLES[$id]['id_left'] + 1) . "', id_right = id_right + '" . ($CAT_ARTICLES[$id]['id_right'] - $CAT_ARTICLES[$id]['id_left'] + 1) . "' WHERE id_left BETWEEN '" . $CAT_ARTICLES[$switch_id_cat]['id_left'] . "' AND '" . $CAT_ARTICLES[$switch_id_cat]['id_right'] . "'", __LINE__, __FILE__);
			
			//On rétablit les valeurs absolues.
			$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_left = - id_left WHERE id_left < 0", __LINE__, __FILE__);
			$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_right = - id_right WHERE id_right < 0", __LINE__, __FILE__);
			
			$Cache->Generate_module_file('articles');
		}
		elseif (!empty($list_parent_cats) )
		{
			//Changement de catégorie.
			$to = $Sql->query("SELECT id FROM " . PREFIX . "articles_cats
			WHERE id_left < '" . $CAT_ARTICLES[$id]['id_left'] . "' AND level = '" . ($CAT_ARTICLES[$id]['level'] - 1) . "' AND
			id NOT IN (" . $list_parent_cats . ")
			ORDER BY id_left DESC" .
			$Sql->limit(0, 1), __LINE__, __FILE__);
		}
	}
	elseif ($move == 'down')
	{
		//Doit-on changer de catégorie parente ou non ?
		$switch_id_cat = $Sql->query("SELECT id FROM " . PREFIX . "articles_cats
		WHERE id_left - '" . $CAT_ARTICLES[$id]['id_right'] . "' = 1", __LINE__, __FILE__);
		if (!empty($switch_id_cat))
		{
			//On monte l'articles à déplacer, on lui assigne des id négatifs pour assurer l'unicité.
			$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_left = - id_left - '" . ($CAT_ARTICLES[$switch_id_cat]['id_right'] - $CAT_ARTICLES[$switch_id_cat]['id_left'] + 1) . "', id_right = - id_right - '" . ($CAT_ARTICLES[$switch_id_cat]['id_right'] - $CAT_ARTICLES[$switch_id_cat]['id_left'] + 1) . "' WHERE id_left BETWEEN '" . $CAT_ARTICLES[$id]['id_left'] . "' AND '" . $CAT_ARTICLES[$id]['id_right'] . "'", __LINE__, __FILE__);
			//On descend l'articles cible.
			$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_left = id_left - '" . ($CAT_ARTICLES[$id]['id_right'] - $CAT_ARTICLES[$id]['id_left'] + 1) . "', id_right = id_right - '" . ($CAT_ARTICLES[$id]['id_right'] - $CAT_ARTICLES[$id]['id_left'] + 1) . "' WHERE id_left BETWEEN '" . $CAT_ARTICLES[$switch_id_cat]['id_left'] . "' AND '" . $CAT_ARTICLES[$switch_id_cat]['id_right'] . "'", __LINE__, __FILE__);
			
			//On rétablit les valeurs absolues.
			$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_left = - id_left WHERE id_left < 0", __LINE__, __FILE__);
			$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_right = - id_right WHERE id_right < 0", __LINE__, __FILE__);
			
			$Cache->Generate_module_file('articles');
		}
		elseif (!empty($list_parent_cats) )
		{
			//Changement de catégorie.
			$to = $Sql->query("SELECT id FROM " . PREFIX . "articles_cats
			WHERE id_left > '" . $CAT_ARTICLES[$id]['id_left'] . "' AND level = '" . ($CAT_ARTICLES[$id]['level'] - 1) . "'
			ORDER BY id_left" .
			$Sql->limit(0, 1), __LINE__, __FILE__);
		}
	}

	if (!empty($to)) //Changement de catégorie possible?
	{
		//On vérifie si l'articles contient des sous catégories.
		$nbr_cat = (($CAT_ARTICLES[$id]['id_right'] - $CAT_ARTICLES[$id]['id_left'] - 1) / 2) + 1;
	
		//Sous catégories de l'article à déplacer.
		$list_cats = '';
		$result = $Sql->query_while("SELECT id
		FROM " . PREFIX . "articles_cats
		WHERE id_left BETWEEN '" . $CAT_ARTICLES[$id]['id_left'] . "' AND '" . $CAT_ARTICLES[$id]['id_right'] . "'
		ORDER BY id_left", __LINE__, __FILE__);
		
		while ($row = $Sql->fetch_assoc($result))
			$list_cats .= $row['id'] . ', ';
		
		$Sql->query_close($result);
		$list_cats = trim($list_cats, ', ');
	
		if (empty($list_cats))
			$clause_cats = " id = '" . $id . "'";
		else
			$clause_cats = " id IN (" . $list_cats . ")";
			
		//Récupération du nombre d'articles de l'article.
		$nbr_articles_visible = $Sql->query("SELECT nbr_articles_visible FROM " . PREFIX . "articles_cats WHERE id = '" . $id . "'", __LINE__, __FILE__);
		$nbr_articles_unvisible = $Sql->query("SELECT nbr_articles_unvisible FROM " . PREFIX . "articles_cats WHERE id = '" . $id . "'", __LINE__, __FILE__);
		
		//Catégories parentes de l'article cible.
		$list_parent_cats_to = '';
		$result = $Sql->query_while("SELECT id, level
		FROM " . PREFIX . "articles_cats
		WHERE id_left <= '" . $CAT_ARTICLES[$to]['id_left'] . "' AND id_right >= '" . $CAT_ARTICLES[$to]['id_right'] . "'", __LINE__, __FILE__);
		
		while ($row = $Sql->fetch_assoc($result))
			$list_parent_cats_to .= $row['id'] . ', ';
		
		$Sql->query_close($result);
		$list_parent_cats_to = trim($list_parent_cats_to, ', ');
	
		if (empty($list_parent_cats_to))
			$clause_parent_cats_to = " id = '" . $to . "'";
		else
			$clause_parent_cats_to = " id IN (" . $list_parent_cats_to . ")";
			
		########## Suppression ##########
		//On supprime virtuellement (changement de signe des bornes) les enfants.
		$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_left = - id_left, id_right = - id_right WHERE " . $clause_cats, __LINE__, __FILE__);
		//On modifie les bornes droites des parents.
		if (!empty($list_parent_cats))
		{
			$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_right = id_right - '" . ( $nbr_cat*2) . "', nbr_articles_visible = nbr_articles_visible - '" . $nbr_articles_visible . "', nbr_articles_unvisible = nbr_articles_unvisible - '" . $nbr_articles_unvisible . "' WHERE id IN (" . $list_parent_cats . ")", __LINE__, __FILE__);
		}
		
		//On réduit la taille de l'arbre du nombre de articles supprimées à partir de la position de celui-ci.
		$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_left = id_left - '" . ($nbr_cat*2) . "', id_right = id_right - '" . ($nbr_cat*2) . "' WHERE id_left > '" . $CAT_ARTICLES[$id]['id_right'] . "'", __LINE__, __FILE__);

		########## Ajout ##########
		//On modifie les bornes droites des parents de la cible.
		$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_right = id_right + '" . ($nbr_cat*2) . "', nbr_articles_visible = nbr_articles_visible + '" . $nbr_articles_visible . "', nbr_articles_unvisible = nbr_articles_unvisible + '" . $nbr_articles_unvisible . "' WHERE " . $clause_parent_cats_to, __LINE__, __FILE__);

		//On augmente la taille de l'arbre du nombre de articles supprimées à partir de la position de l'article cible.
		if ($CAT_ARTICLES[$id]['id_left'] > $CAT_ARTICLES[$to]['id_left'] ) //Direction article source -> article cible.
		{
			$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_left = id_left + '" . ($nbr_cat*2) . "', id_right = id_right + '" . ($nbr_cat*2) . "' WHERE id_left > '" . $CAT_ARTICLES[$to]['id_right'] . "'", __LINE__, __FILE__);
			$limit = $CAT_ARTICLES[$to]['id_right'];
			$end = $limit + ($nbr_cat*2) - 1;
		}
		else
		{
			$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_left = id_left + '" . ($nbr_cat*2) . "', id_right = id_right + '" . ($nbr_cat*2) . "' WHERE id_left > '" . ($CAT_ARTICLES[$to]['id_right'] - ($nbr_cat*2)) . "'", __LINE__, __FILE__);
			$limit = $CAT_ARTICLES[$to]['id_right'] - ($nbr_cat*2);
			$end = $limit + ($nbr_cat*2) - 1;
		}

		//On replace les articles supprimées virtuellement.
		$array_sub_cats = explode(', ', $list_cats);
		$z = 0;
		for ($i = $limit; $i <= $end; $i = $i + 2)
		{
			$id_left = $limit + ($CAT_ARTICLES[$array_sub_cats[$z]]['id_left'] - $CAT_ARTICLES[$id]['id_left']);
			$id_right = $end - ($CAT_ARTICLES[$id]['id_right'] - $CAT_ARTICLES[$array_sub_cats[$z]]['id_right']);
			$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET id_left = '" . $id_left . "', id_right = '" . $id_right . "' WHERE id = '" . $array_sub_cats[$z] . "'", __LINE__, __FILE__);
			$z++;
		}
		
		$Cache->Generate_module_file('articles');
	}
		
	redirect(HOST . SCRIPT);
}
elseif (!empty($id)) //Edition des catégories.
{
	$Cache->load('articles');
	
	$Template->set_filenames(array(
		'admin_articles_cat_edit'=> 'articles/admin_articles_cat_edit.tpl'
	));
	
	$articles_info = $Sql->query_array(PREFIX . "articles_cats", "id_left", "id_right", "level", "name", "contents", "icon", "aprob", "auth", "WHERE id = '" . $id . "'", __LINE__, __FILE__);
	
	if (!isset($CAT_ARTICLES[$id]))
		redirect(HOST . DIR . '/articles/admin_articles_cat.php?error=unexist_cat');
	
	//Listing des catégories disponibles.
	$articles = '<option value="0">' . $LANG['root'] . '</option>';
	$result = $Sql->query_while("SELECT id, id_left, id_right, name, level
	FROM " . PREFIX . "articles_cats
	WHERE id_left NOT BETWEEN '" . $CAT_ARTICLES[$id]['id_left'] . "' AND '" . $CAT_ARTICLES[$id]['id_right'] . "'
	ORDER BY id_left", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$margin = ($row['level'] > 0) ? str_repeat('--------', $row['level']) : '--';
		$selected = ($row['id_left'] < $articles_info['id_left'] && $row['id_right'] > $articles_info['id_right'] && ($articles_info['level'] - 1) == $row['level'] ) ? ' selected="selected"' : '';
		$articles .= '<option value="' . $row['id'] . '"' . $selected . '>' . $margin . ' ' . $row['name'] . '</option>';
	}
	$Sql->query_close($result);

	//Images disponibles
	$img_direct_path = (strpos($articles_info['icon'], '/') !== false);
	$image_list = '<option value=""' . ($img_direct_path ? ' selected="selected"' : '') . '>--</option>';
	import('io/filesystem/folder');
	$image_list = '<option value="" id="img_default_select">--</option>';
	$image_folder_path = new Folder('./');
	foreach ($image_folder_path->get_files('`\.(png|jpg|bmp|gif|jpeg|tiff)$`i') as $images)
	{
		$image = $images->get_name();
		$selected = $image == $articles_info['icon'] ? ' selected="selected"' : '';
		$image_list .= '<option value="' . $image . '"' . ($img_direct_path ? '' : $selected) . '>' . $image . '</option>';
	}
	
	//Gestion erreur.
	$get_error = !empty($_GET['error']) ? $_GET['error'] : '';
	if ($get_error == 'incomplete')
		$Errorh->handler($LANG['e_incomplete'], E_USER_NOTICE);
	
	$array_auth = !empty($articles_info['auth']) ? unserialize($articles_info['auth']) : array(); //Récupération des tableaux des autorisations et des groupes.
		
	$Template->assign_vars(array(
		'THEME' => get_utheme(),
		'MODULE_DATA_PATH' => $Template->get_module_data_path('articles'),
		'ID' => $id,
		'CATEGORIES' => $articles,
		'NAME' => $articles_info['name'],
		'DESC' => $articles_info['contents'],
		'CHECKED_APROB' => ($articles_info['aprob'] == 1) ? 'checked="checked"' : '',
		'UNCHECKED_APROB' => ($articles_info['aprob'] == 0) ? 'checked="checked"' : '',
		'IMG_PATH' => $img_direct_path ? $articles_info['icon'] : '',
		'IMG_ICON' => !empty($articles_info['icon']) ? '<img src="' . $articles_info['icon'] . '" alt="" class="valign_middle" />' : '',
		'IMG_LIST' => $image_list,
		'AUTH_READ' => Authorizations::generate_select(READ_CAT_ARTICLES, $array_auth),
		'L_REQUIRE_TITLE' => $LANG['require_title'],
		'L_ARTICLES_MANAGEMENT' => $LANG['articles_management'],
		'L_ARTICLES_ADD' => $LANG['articles_add'],
		'L_ARTICLES_CAT' => $LANG['cat_management'],
		'L_ARTICLES_CONFIG' => $LANG['articles_config'],
		'L_ARTICLES_CAT_ADD' => $LANG['articles_cats_add'],
		'L_EDIT_CAT' => $LANG['cat_edit'],
		'L_REQUIRE' => $LANG['require'],
		'L_APROB' => $LANG['aprob'],
		'L_ICON' => $LANG['icon_cat'],
		'L_OR_DIRECT_PATH' => $LANG['or_direct_path'],
		'L_RANK' => $LANG['rank'],
		'L_DELETE' => $LANG['delete'],
		'L_PARENT_CATEGORY' => $LANG['parent_category'],
		'L_NAME' => $LANG['name'],
		'L_DESC' => $LANG['description'],
		'L_RESET' => $LANG['reset'],
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_LOCK' => $LANG['lock'],
		'L_UNLOCK' => $LANG['unlock'],
		'L_GUEST' => $LANG['guest'],
		'L_USER' => $LANG['member'],
		'L_MODO' => $LANG['modo'],
		'L_ADMIN' => $LANG['admin'],
		'L_UPDATE' => $LANG['update'],
		'L_AUTH_READ' => $LANG['auth_read']
	));
	
	$Template->pparse('admin_articles_cat_edit'); // traitement du modele
}
elseif (!empty($root)) //Edition de la racine.
{
	$Cache->load('articles');
	
	$Template->set_filenames(array(
		'admin_articles_cat_edit2'=> 'articles/admin_articles_cat_edit2.tpl'
	));
			
	//Gestion erreur.
	$get_error = !empty($_GET['error']) ? $_GET['error'] : '';
	if ($get_error == 'incomplete')
		$Errorh->handler($LANG['e_incomplete'], E_USER_NOTICE);
	
	$array_auth = isset($CONFIG_ARTICLES['auth_root']) ? $CONFIG_ARTICLES['auth_root'] : array(); //Récupération des tableaux des autorisations et des groupes.
	$Template->assign_vars(array(
		'THEME' => get_utheme(),
		'MODULE_DATA_PATH' => $Template->get_module_data_path('articles'),
		'AUTH_READ' => Authorizations::generate_select(READ_CAT_ARTICLES, $array_auth),
		'L_ROOT' => $LANG['root'],
		'L_ARTICLES_MANAGEMENT' => $LANG['articles_management'],
		'L_ARTICLES_ADD' => $LANG['articles_add'],
		'L_ARTICLES_CAT' => $LANG['cat_management'],
		'L_ARTICLES_CONFIG' => $LANG['articles_config'],
		'L_ARTICLES_CAT_ADD' => $LANG['articles_cats_add'],
		'L_EDIT_CAT' => $LANG['cat_edit'],
		'L_REQUIRE' => $LANG['require'],
		'L_RESET' => $LANG['reset'],
		'L_GUEST' => $LANG['guest'],
		'L_USER' => $LANG['member'],
		'L_MODO' => $LANG['modo'],
		'L_ADMIN' => $LANG['admin'],
		'L_UPDATE' => $LANG['update'],
		'L_AUTH_READ' => $LANG['auth_read'],
		'L_EXPLAIN_SELECT_MULTIPLE' => $LANG['explain_select_multiple'],
		'L_SELECT_ALL' => $LANG['select_all'],
		'L_SELECT_NONE' => $LANG['select_none']
	));
	
	$Template->pparse('admin_articles_cat_edit2'); // traitement du modele
}
else
{
	$Template->set_filenames(array(
		'admin_articles_cat'=> 'articles/admin_articles_cat.tpl'
	));
	
	//Gestion erreur.
	$get_error = retrieve(GET, 'error', '');
	if ($get_error == 'unexist_cat')
		$Errorh->handler($LANG['e_unexist_cat'], E_USER_NOTICE);
		
	$Template->assign_vars(array(
		'THEME' => get_utheme(),
		'MODULE_DATA_PATH' => $Template->get_module_data_path('articles'),
		'L_CONFIRM_DEL' => $LANG['del_entry'],
		'L_REQUIRE_TITLE' => $LANG['require_title'],
		'L_ARTICLES_MANAGEMENT' => $LANG['articles_management'],
		'L_ARTICLES_ADD' => $LANG['articles_add'],
		'L_ARTICLES_CAT' => $LANG['cat_management'],
		'L_ARTICLES_CONFIG' => $LANG['articles_config'],
		'L_ARTICLES_CAT_ADD' => $LANG['articles_cats_add'],
		'L_DELETE' => $LANG['delete'],
		'L_ROOT' => $LANG['root'],
		'L_NAME' => $LANG['name'],
		'L_DESC' => $LANG['description'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset'],
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_LOCK' => $LANG['lock'],
		'L_UNLOCK' => $LANG['unlock'],
		'L_GUEST' => $LANG['guest'],
		'L_USER' => $LANG['member'],
		'L_MODO' => $LANG['modo'],
		'L_ADMIN' => $LANG['admin'],
		'L_ADD' => $LANG['add'],
		'L_AUTH_READ' => $LANG['auth_read'],
		'L_AUTH_WRITE' => $LANG['auth_write'],
		'L_AUTH_EDIT' => $LANG['auth_edit'],
		'L_EXPLAIN_SELECT_MULTIPLE' => $LANG['explain_select_multiple'],
		'L_SELECT_ALL' => $LANG['select_all'],
		'L_SELECT_NONE' => $LANG['select_none']
	));

	$max_cat = $Sql->query("SELECT MAX(id_left) FROM " . PREFIX . "articles_cats", __LINE__, __FILE__);
	$list_cats_js = '';
	$array_js = '';
	$i = 0;
	$result = $Sql->query_while("SELECT id, id_left, id_right, level, name, contents
	FROM " . PREFIX . "articles_cats
	ORDER BY id_left", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		//On assigne les variables pour le POST en précisant l'idurl.
		$Template->assign_block_vars('list', array(
			'I' => $i,
			'ID' => $row['id'],
			'NAME' => (strlen($row['name']) > 60) ? (substr($row['name'], 0, 60) . '...') : $row['name'],
			'INDENT' => ($row['level'] + 1) * 75, //Indentation des sous catégories.
			'U_ARTICLES_VARS' => url('.php?cat=' . $row['id'], '-' . $row['id'] . '+' . url_encode_rewrite($row['name']) . '.php')
		));
		
		$list_cats_js .= $row['id'] . ', ';
		
		$array_js .= 'array_cats[' . $row['id'] . '] = new Array();' . "\n";
		$array_js .= 'array_cats[' . $row['id'] . '][\'id\'] = ' . $row['id'] . ";\n";
		$array_js .= 'array_cats[' . $row['id'] . '][\'id_left\'] = ' . $row['id_left'] . ";\n";
		$array_js .= 'array_cats[' . $row['id'] . '][\'id_right\'] = ' . $row['id_right'] . ";\n";
		$array_js .= 'array_cats[' . $row['id'] . '][\'i\'] = ' . $i . ";\n";
		$i++;
	}
	$Sql->query_close($result);
	
	$Template->assign_vars(array(
		'LIST_CATS' => trim($list_cats_js, ', '),
		'ARRAY_JS' => $array_js,
		'ID_END' => ($i - 1)
	));

	$Template->pparse('admin_articles_cat'); // traitement du modele
}
	
require_once('../admin/admin_footer.php');

?>