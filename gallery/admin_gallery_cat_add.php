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

$idcat = !empty($_GET['idcat']) ? NumberHelper::numeric($_GET['idcat']) : 0;

//Si c'est confirmé on execute
if (!empty($_POST['add'])) //Nouvelle galerie/catégorie.
{
	$Cache->load('gallery');
	
	$parent_category = !empty($_POST['category']) ? NumberHelper::numeric($_POST['category']) : 0;
	$name = !empty($_POST['name']) ? TextHelper::strprotect($_POST['name']) : '';
	$contents = !empty($_POST['desc']) ? TextHelper::strprotect($_POST['desc']) : '';
	$aprob = isset($_POST['aprob']) ? NumberHelper::numeric($_POST['aprob']) : 0;   
	$status = isset($_POST['status']) ? NumberHelper::numeric($_POST['status']) : 0;   
		
	//Génération du tableau des droits.
	$array_auth_all = Authorizations::build_auth_array_from_form(GalleryAuthorizationsService::READ_AUTHORIZATIONS, GalleryAuthorizationsService::WRITE_AUTHORIZATIONS, GalleryAuthorizationsService::MODERATION_AUTHORIZATIONS);
	
	if (!empty($name))
	{	
		if (!empty($parent_category) && isset($CAT_GALLERY[$parent_category])) //Insertion sous galerie de niveau x.
		{
			//Galerie parente de la galerie cible.
			$list_parent_cats = '';
			$result = PersistenceContext::get_querier()->select("SELECT id
			FROM " . PREFIX . "gallery_cats 
			WHERE id_left <= :id_left AND id_right >= :id_right", array(
				'id_left' => $CAT_GALLERY[$parent_category]['id_left'],
				'id_right' => $CAT_GALLERY[$parent_category]['id_right']
			));
			while ($row = $result->fetch())
			{
				$list_parent_cats .= $row['id'] . ', ';
			}
			$result->dispose();
			$list_parent_cats = trim($list_parent_cats, ', ');
				
			if (empty($list_parent_cats))
				$clause_parent = $parent_category;
			else
				$clause_parent = $list_parent_cats;
				
			$id_left = $CAT_GALLERY[$parent_category]['id_right'];
			PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "gallery_cats SET id_right = id_right + 2 WHERE id " . (empty($list_parent_cats) ? "=" : "IN") . " :id_cat", array('id_cat' => $clause_parent));
			PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "gallery_cats SET id_right = id_right + 2, id_left = id_left + 2 WHERE id_left > :id_left", array('id_left' => $id_left));
			$level = $CAT_GALLERY[$parent_category]['level'] + 1;
			
		}
		else //Insertion galerie niveau 0.
		{
			$id_left = PersistenceContext::get_querier()->get_column_value(PREFIX . "gallery_cats", 'MAX(id_right)', '');
			$id_left++;
			$level = 0;
		}
			
		PersistenceContext::get_querier()->insert(PREFIX . "gallery_cats", array('id_left' => $id_left, 'id_right' => ($id_left + 1), 'level' => $level, 'name' => $name, 'contents' => $contents, 'nbr_pics_aprob' => 0, 'nbr_pics_unaprob' => 0, 'status' => $status, 'aprob' => $aprob, 'auth' => TextHelper::strprotect(serialize($array_auth_all), TextHelper::HTML_NO_PROTECT)));

		###### Regénération du cache #######
		$Cache->Generate_module_file('gallery');
			
		AppContext::get_response()->redirect('/gallery/admin_gallery_cat.php');	
	}	
	else
		AppContext::get_response()->redirect('/gallery/admin_gallery_cat_add.php?error=incomplete#message_helper');
}
else	
{		
	$tpl = new FileTemplate('gallery/admin_gallery_cat_add.tpl');
			
	//Listing des catégories disponibles, sauf celle qui va être supprimée.			
	$galleries = '<option value="0" checked="checked">' . $LANG['root'] . '</option>';
	$result = PersistenceContext::get_querier()->select("SELECT id, name, level
	FROM " . PREFIX . "gallery_cats 
	ORDER BY id_left");
	while ($row = $result->fetch())
	{	
		$margin = ($row['level'] > 0) ? str_repeat('--------', $row['level']) : '--';
		$galleries .= '<option value="' . $row['id'] . '">' . $margin . ' ' . $row['name'] . '</option>';
	}
	$result->dispose();
	
	//Gestion erreur.
	$get_error = !empty($_GET['error']) ? trim($_GET['error']) : '';
	if ($get_error == 'incomplete')
		$tpl->put('message_helper', MessageHelper::display($LANG['e_incomplete'], MessageHelper::NOTICE));	
		
	$tpl->put_all(array(
		'CATEGORIES' => $galleries,
		'AUTH_READ' => Authorizations::generate_select(GalleryAuthorizationsService::READ_AUTHORIZATIONS, array(), array(-1 => true, 0 => true, 1 => true, 2 => true)),
		'AUTH_WRITE' => Authorizations::generate_select(GalleryAuthorizationsService::WRITE_AUTHORIZATIONS, array(), array(1 => true, 2 => true)),
		'AUTH_EDIT' => Authorizations::generate_select(GalleryAuthorizationsService::MODERATION_AUTHORIZATIONS, array(), array(2 => true)),
		'L_REQUIRE_NAME' => $LANG['require_name'],
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
	
	$tpl->display();
}

require_once('../admin/admin_footer.php');

?>