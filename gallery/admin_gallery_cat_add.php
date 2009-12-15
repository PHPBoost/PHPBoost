<?php
/*##################################################
 *                               admin_gallery_cat_add.php
 *                            -------------------
 *   begin                : August  01, 2007
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

require_once('../admin/admin_begin.php');
load_module_lang('gallery'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$idcat = !empty($_GET['idcat']) ? numeric($_GET['idcat']) : 0;

define('READ_CAT_GALLERY', 0x01);
define('WRITE_CAT_GALLERY', 0x02);
define('EDIT_CAT_GALLERY', 0x04);

//Si c'est confirmé on execute
if (!empty($_POST['add'])) //Nouvelle galerie/catégorie.
{
	$Cache->load('gallery');
	
	$parent_category = !empty($_POST['category']) ? numeric($_POST['category']) : 0;
	$name = !empty($_POST['name']) ? strprotect($_POST['name']) : '';
	$contents = !empty($_POST['desc']) ? strprotect($_POST['desc']) : '';
	$aprob = isset($_POST['aprob']) ? numeric($_POST['aprob']) : 0;   
	$status = isset($_POST['status']) ? numeric($_POST['status']) : 0;   
		
	//Génération du tableau des droits.
	$array_auth_all = Authorizations::build_auth_array_from_form(READ_CAT_GALLERY, WRITE_CAT_GALLERY, EDIT_CAT_GALLERY);
	
	if (!empty($name))
	{	
		if (isset($CAT_GALLERY[$parent_category])) //Insertion sous galerie de niveau x.
		{
			//Galerie parente de la galerie cible.
			$list_parent_cats = '';
			$result = $Sql->query_while("SELECT id
			FROM " . PREFIX . "gallery_cats 
			WHERE id_left <= '" . $CAT_GALLERY[$parent_category]['id_left'] . "' AND id_right >= '" . $CAT_GALLERY[$parent_category]['id_right'] . "'", __LINE__, __FILE__);
			while ($row = $Sql->fetch_assoc($result))
			{
				$list_parent_cats .= $row['id'] . ', ';
			}
			$Sql->query_close($result);
			$list_parent_cats = trim($list_parent_cats, ', ');
				
			if (empty($list_parent_cats))
				$clause_parent = "id = '" . $parent_category . "'";
			else
				$clause_parent = "id IN (" . $list_parent_cats . ")";
				
			$id_left = $CAT_GALLERY[$parent_category]['id_right'];
			$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET id_right = id_right + 2 WHERE " . $clause_parent, __LINE__, __FILE__);
			$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET id_right = id_right + 2, id_left = id_left + 2 WHERE id_left > '" . $id_left . "'", __LINE__, __FILE__);
			$level = $CAT_GALLERY[$parent_category]['level'] + 1;
			
		}
		else //Insertion galerie niveau 0.
		{
			$id_left = $Sql->query("SELECT MAX(id_right) FROM " . PREFIX . "gallery_cats", __LINE__, __FILE__);
			$id_left++;
			$level = 0;
		}
			
		$Sql->query_inject("INSERT INTO " . PREFIX . "gallery_cats (id_left, id_right, level, name, contents, nbr_pics_aprob, nbr_pics_unaprob, status, aprob, auth) VALUES('" . $id_left . "', '" . ($id_left + 1) . "', '" . $level . "', '" . $name . "', '" . $contents . "', 0, 0, '" . $status . "', '" . $aprob . "', '" . strprotect(serialize($array_auth_all), HTML_NO_PROTECT) . "')", __LINE__, __FILE__);	

		###### Regénération du cache #######
		$Cache->Generate_module_file('gallery');
			
		redirect('/gallery/admin_gallery_cat.php');	
	}	
	else
		redirect('/gallery/admin_gallery_cat_add.php?error=incomplete#errorh');
}
else	
{		
	$Template->set_filenames(array(
		'admin_gallery_cat_add'=> 'gallery/admin_gallery_cat_add.tpl'
	));
			
	//Listing des catégories disponibles, sauf celle qui va être supprimée.			
	$galleries = '<option value="0" checked="checked">' . $LANG['root'] . '</option>';
	$result = $Sql->query_while("SELECT id, name, level
	FROM " . PREFIX . "gallery_cats 
	ORDER BY id_left", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{	
		$margin = ($row['level'] > 0) ? str_repeat('--------', $row['level']) : '--';
		$galleries .= '<option value="' . $row['id'] . '">' . $margin . ' ' . $row['name'] . '</option>';
	}
	$Sql->query_close($result);
	
	//Gestion erreur.
	$get_error = !empty($_GET['error']) ? trim($_GET['error']) : '';
	if ($get_error == 'incomplete')
		$Errorh->handler($LANG['e_incomplete'], E_USER_NOTICE);	
		
	$Template->assign_vars(array(
		'THEME' => get_utheme(),
		'CATEGORIES' => $galleries,
		'AUTH_READ' => Authorizations::generate_select(READ_CAT_GALLERY, array(), array(-1 => true, 0 => true, 1 => true, 2 => true)),
		'AUTH_WRITE' => Authorizations::generate_select(WRITE_CAT_GALLERY, array(), array(1 => true, 2 => true)),
		'AUTH_EDIT' => Authorizations::generate_select(EDIT_CAT_GALLERY, array(), array(2 => true)),
		'L_REQUIRE_TITLE' => $LANG['require_title'],
		'L_GALLERY_MANAGEMENT' => $LANG['gallery_management'], 
		'L_GALLERY_PICS_ADD' => $LANG['gallery_pics_add'], 
		'L_GALLERY_CAT_MANAGEMENT' => $LANG['gallery_cats_management'], 
		'L_GALLERY_CAT_ADD' => $LANG['gallery_cats_add'],
		'L_GALLERY_CONFIG' => $LANG['gallery_config'],
		'L_REQUIRE' => $LANG['require'],
		'L_APROB' => $LANG['aprob'],
		'L_STATUS' => $LANG['status'],
		'L_RANK' => $LANG['rank'],
		'L_DELETE' => $LANG['delete'],
		'L_PARENT_CATEGORY' => $LANG['parent_category'],
		'L_NAME' => $LANG['name'],
		'L_DESC' => $LANG['description'],
		'L_RESET' => $LANG['reset'],
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_LOCK' => $LANG['gallery_lock'],
		'L_UNLOCK' => $LANG['gallery_unlock'],
		'L_GUEST' => $LANG['guest'],
		'L_USER' => $LANG['member'],
		'L_MODO' => $LANG['modo'],
		'L_ADMIN' => $LANG['admin'],
		'L_ADD' => $LANG['add'],
		'L_AUTH_READ' => $LANG['auth_read'],
		'L_AUTH_WRITE' => $LANG['auth_upload'],
		'L_AUTH_EDIT' => $LANG['auth_edit']
	));
	
	$Template->pparse('admin_gallery_cat_add'); // traitement du modele	
}

require_once('../admin/admin_footer.php');

?>