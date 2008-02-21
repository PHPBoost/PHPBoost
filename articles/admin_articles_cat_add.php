<?php
/*##################################################
 *                               admin_articles_cat_add.php
 *                            -------------------
 *   begin                : August 27, 2007
 *   copyright          : (C) 2007 Viarre Régis
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

require_once('../includes/admin_begin.php');
load_module_lang('articles'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../includes/admin_header.php');

$idcat = !empty($_GET['idcat']) ? numeric($_GET['idcat']) : 0;

//Si c'est confirmé on execute
if( !empty($_POST['add']) ) //Nouvelle articles/catégorie.
{
	$Cache->Load_file('articles');
	
	$parent_category = !empty($_POST['category']) ? numeric($_POST['category']) : 0;
	$name = !empty($_POST['name']) ? securit($_POST['name']) : '';
	$contents = !empty($_POST['desc']) ? securit($_POST['desc']) : '';
	$icon = !empty($_POST['icon']) ? securit($_POST['icon']) : ''; 
	$aprob = isset($_POST['aprob']) ? numeric($_POST['aprob']) : 0;    
	$auth_read = isset($_POST['groups_authr']) ? $_POST['groups_authr'] : ''; 
		
	//Génération du tableau des droits.
	$array_auth_all = $Group->Return_array_auth($auth_read);
			
	if( !empty($name) )
	{	
		if( isset($CAT_ARTICLES[$parent_category]) ) //Insertion sous articles de niveau x.
		{
			//Articles parente de la articles cible.
			$list_parent_cats = '';
			$result = $Sql->Query_while("SELECT id
			FROM ".PREFIX."articles_cats 
			WHERE id_left <= '" . $CAT_ARTICLES[$parent_category]['id_left'] . "' AND id_right >= '" . $CAT_ARTICLES[$parent_category]['id_right'] . "'", __LINE__, __FILE__);
			while( $row = $Sql->Sql_fetch_assoc($result) )
			{
				$list_parent_cats .= $row['id'] . ', ';
			}
			$Sql->Close($result);
			$list_parent_cats = trim($list_parent_cats, ', ');
				
			if( empty($list_parent_cats) )
				$clause_parent = "id = '" . $parent_category . "'";
			else
				$clause_parent = "id IN (" . $list_parent_cats . ")";
				
			$id_left = $CAT_ARTICLES[$parent_category]['id_right'];
			$Sql->Query_inject("UPDATE ".PREFIX."articles_cats SET id_right = id_right + 2 WHERE " . $clause_parent, __LINE__, __FILE__);
			$Sql->Query_inject("UPDATE ".PREFIX."articles_cats SET id_right = id_right + 2, id_left = id_left + 2 WHERE id_left > '" . $id_left . "'", __LINE__, __FILE__);
			$level = $CAT_ARTICLES[$parent_category]['level'] + 1;
			
		}
		else //Insertion articles niveau 0.
		{
			$id_left = $Sql->Query("SELECT MAX(id_right) FROM ".PREFIX."articles_cats", __LINE__, __FILE__);
			$id_left++;
			$level = 0;
		}
			
		$Sql->Query_inject("INSERT INTO ".PREFIX."articles_cats (id_left, id_right, level, name, contents, nbr_articles_visible, nbr_articles_unvisible, icon, aprob, auth) VALUES('" . $id_left . "', '" . ($id_left + 1) . "', '" . $level . "', '" . $name . "', '" . $contents . "', 0, 0, '" . $icon . "', '" . $aprob . "', '" . securit(serialize($array_auth_all), HTML_NO_PROTECT) . "')", __LINE__, __FILE__);	

		###### Regénération du cache #######
		$Cache->Generate_module_file('articles');
			
		redirect(HOST . DIR . '/articles/admin_articles_cat.php');	
	}	
	else
		redirect(HOST . DIR . '/articles/admin_articles_cat_add.php?error=incomplete#errorh');
}
else	
{		
	$Template->Set_filenames(array(
		'admin_articles_cat_add' => '../templates/' . $CONFIG['theme'] . '/articles/admin_articles_cat_add.tpl'
	));
			
	//Listing des catégories disponibles		
	$galleries = '<option value="0" checked="checked">' . $LANG['root'] . '</option>';
	$result = $Sql->Query_while("SELECT id, name, level
	FROM ".PREFIX."articles_cats 
	ORDER BY id_left", __LINE__, __FILE__);
	while( $row = $Sql->Sql_fetch_assoc($result) )
	{	
		$margin = ($row['level'] > 0) ? str_repeat('--------', $row['level']) : '--';
		$galleries .= '<option value="' . $row['id'] . '">' . $margin . ' ' . $row['name'] . '</option>';
	}
	$Sql->Close($result);
	
	//Images disponibles
	$rep = './';
	$image_list = '';
	if( is_dir($rep) ) //Si le dossier existe
	{
		$img_array = array();
		$dh = @opendir( $rep);
		while( ! is_bool($lang = @readdir($dh)) )
		{	
			if( preg_match('`\.(gif|png|jpg|jpeg|tiff)`i', $lang) )
				$img_array[] = $lang; //On crée un tableau, avec les different fichiers.				
		}	
		@closedir($dh); //On ferme le dossier

		foreach($img_array as $key => $img_path)
			$image_list .= '<option value="' . $img_path . '">' . $img_path . '</option>';
	}
	
	//Gestion erreur.
	$get_error = !empty($_GET['error']) ? $_GET['error'] : '';
	if( $get_error == 'incomplete' )
		$Errorh->Error_handler($LANG['e_incomplete'], E_USER_NOTICE);	
		
	$array_groups = $Group->Create_groups_array(); //Création du tableau des groupes.
	
	$Template->Assign_vars(array(
		'THEME' => $CONFIG['theme'],
		'MODULE_DATA_PATH' => $Template->Module_data_path('articles'),
		'NBR_GROUP' => count($array_groups),
		'CATEGORIES' => $galleries,
		'AUTH_READ' => $Group->Generate_select_groups('r', array(), -1, array(-1 => true, 0 => true, 1 => true, 2 => true)),
		'IMG_LIST' => $image_list,
		'L_REQUIRE_TITLE' => $LANG['require_title'],
		'L_ARTICLES_MANAGEMENT' => $LANG['articles_management'],
		'L_ARTICLES_ADD' => $LANG['articles_add'],
		'L_ARTICLES_CAT' => $LANG['cat_management'],
		'L_ARTICLES_CONFIG' => $LANG['articles_config'],
		'L_ARTICLES_CAT_ADD' => $LANG['articles_cats_add'],
		'L_REQUIRE' => $LANG['require'],
		'L_APROB' => $LANG['aprob'],
		'L_ICON' => $LANG['icon_cat'],
		'L_ICON_EXPLAIN' => $LANG['icon_cat_explain'],
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
		'L_MEMBER' => $LANG['member'],
		'L_MODO' => $LANG['modo'],
		'L_ADMIN' => $LANG['admin'],
		'L_ADD' => $LANG['add'],
		'L_AUTH_READ' => $LANG['auth_read'],
		'L_EXPLAIN_SELECT_MULTIPLE' => $LANG['explain_select_multiple'],
		'L_SELECT_ALL' => $LANG['select_all'],
		'L_SELECT_NONE' => $LANG['select_none']
	));
	
	$Template->Pparse('admin_articles_cat_add'); // traitement du modele	
}

require_once('../includes/admin_footer.php');

?>