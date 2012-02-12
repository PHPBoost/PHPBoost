<?php
/*##################################################
 *                               admin_news_cat.php
 *                            -------------------
 *   begin                : January 24, 2007
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
load_module_lang('news'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$id = retrieve(GET, 'id', 0);

//Si c'est confirmé on met à jour!
if (!empty($_POST['valid']))
{
	$result = $Sql->query_while("SELECT id
	FROM " . PREFIX . "news_cat", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$cat = retrieve(POST, $row['id'] . 'cat', '');  
		$icon = retrieve(POST, $row['id'] . 'icon', '');  
		$icon_path = retrieve(POST, $row['id'] . 'icon_path', '');  
		$contents = retrieve(POST, $row['id'] . 'contents', '');
		
		if (!empty($icon_path))
			$icon = $icon_path;
		
		if (!empty($cat))
			$Sql->query_inject("UPDATE " . PREFIX . "news_cat SET name = '" . $cat . "', icon = '" . $icon . "', contents = '" . $contents . "' WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
			
	}
	$Sql->query_close($result);
	
	redirect(HOST . SCRIPT);
}
elseif (!empty($_GET['del']) && !empty($id)) //Suppression de la catégorie.
{
	$Session->csrf_get_protect(); //Protection csrf
	
	$Sql->query_inject("DELETE FROM " . PREFIX . "news_cat WHERE id = " . $id, __LINE__, __FILE__);
	$Sql->query_inject("UPDATE " . PREFIX . "news SET idcat = 0 WHERE idcat = " . $id, __LINE__, __FILE__);
		
	redirect(HOST . SCRIPT);
}
//On ajoute la nouvelle catégorie
elseif (!empty($_POST['add'])) //Ajout de la catégorie.
{
	$cat = retrieve(POST, 'cat', '');  
	$icon = retrieve(POST, 'icon', ''); 
	$icon_path = retrieve(POST, 'icon_path', ''); 
	$contents = retrieve(POST, 'contents', ''); 
	
	if (!empty($icon_path))
		$icon = $icon_path;
	
	if (!empty($cat))
	{
		//On insere le nouveau lien, tout en précisant qu'il s'agit d'un lien ajouté et donc supprimable
		$Sql->query_inject("INSERT INTO " . PREFIX . "news_cat (name, contents, icon) VALUES('" . $cat . "', '" . $contents . "', '" . $icon . "')", __LINE__, __FILE__);
		
		redirect(HOST . SCRIPT); 	
	}
	else
		redirect(HOST . DIR . '/news/admin_news_cat.php?error=incomplete#errorh');
}
//Sinon on rempli le formulaire
else	
{		
	$Template->set_filenames(array(
		'admin_news_cat'=> 'news/admin_news_cat.tpl'
	));
	
	//Images disponibles
	import('io/filesystem/folder');
	$img_array = array();
	$image_list = '<option value="">--</option>';
	$image_folder_path = new Folder('./');
	foreach ($image_folder_path->get_files('`\.(png|jpg|bmp|gif|jpeg|tiff)$`i') as $images)
	{
		$image = $images->get_name();
		$img_array[] = $image;
		$image_list .= '<option value="' . $image . '">' . $image . '</option>';
	}

	$Template->assign_vars(array(
		'THEME' => get_utheme(),	
		'IMG_LIST' => $image_list,
		'L_DEL_ENTRY' => $LANG['del_entry'],
		'L_NEWS_MANAGEMENT' => $LANG['news_management'],
		'L_ADD_NEWS' => $LANG['add_news'],
		'L_CONFIG_NEWS' => $LANG['configuration_news'],
		'L_CAT_NEWS' => $LANG['category_news'],
		'L_ADD_CAT' => $LANG['cat_add'],
		'L_NAME' => $LANG['name'],
		'L_ICON' => $LANG['icon_cat'],
		'L_OR_DIRECT_PATH' => $LANG['or_direct_path'],
		'L_DESC' => $LANG['description'],
		'L_DELETE' => $LANG['delete'],
		'L_ADD' => $LANG['add'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset']
	));
	
	//Gestion erreur.
	$get_error = retrieve(GET, 'error', '');
	if ($get_error == 'incomplete')
		$Errorh->handler($LANG['e_incomplete'], E_USER_NOTICE);	
	
	$result = $Sql->query_while("SELECT a.id, a.name, a.contents, a.icon
	FROM " . PREFIX . "news_cat a", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		//On raccourci le lien si il est trop long pour éviter de déformer l'administration.
		$row['name'] = html_entity_decode($row['name']);
		$name = strlen($row['name']) > 45 ? substr($row['name'], 0, 45) . '...' : $row['name'];
		$name = htmlspecialchars($name);

		$img_direct_path = (strpos($row['icon'], '/') !== false);
		$image_list = '<option value=""' . ($img_direct_path ? ' selected="selected"' : '') . '>--</option>';
		foreach ($img_array as $key => $img_path)
		{	
			$selected = ($img_path == $row['icon']) ? ' selected="selected"' : '';
			$image_list .= '<option value="' . $img_path . '"' . ($img_direct_path ? '' : $selected) . '>' . $img_path . '</option>';
		}
		
		$Template->assign_block_vars('cat', array(
			'IDCAT' => $row['id'],
			'CAT' => $name,
			'CONTENTS' => $row['contents'],
			'IMG_PATH' => $img_direct_path ? $row['icon'] : '',
			'IMG_ICON' => !empty($row['icon']) ? '<img src="' . $row['icon'] . '" alt="" class="valign_middle" />' : '',		
			'IMG_LIST' => $image_list,
		));
	}
	$Sql->query_close($result);
		
	$Template->pparse('admin_news_cat'); // traitement du modele	
}

require_once('../admin/admin_footer.php');

?>