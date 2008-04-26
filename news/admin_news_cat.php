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

require_once('../kernel/admin_begin.php');
load_module_lang('news'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../kernel/admin_header.php');
								
$id = ( !empty($_GET['id'])) ? numeric($_GET['id']) : 0;

//Si c'est confirmé on met à jour!
if( !empty($_POST['valid']) )
{
	$result = $Sql->Query_while("SELECT id
	FROM ".PREFIX."news_cat", __LINE__, __FILE__);
	while( $row = $Sql->Sql_fetch_assoc($result) )
	{
		$cat = !empty($_POST[$row['id'] . 'cat']) ? securit($_POST[$row['id'] . 'cat']) : '';  
		$icon = !empty($_POST[$row['id'] . 'icon']) ? securit($_POST[$row['id'] . 'icon']) : '';  
		$icon_path = !empty($_POST[$row['id'] . 'icon_path']) ? securit($_POST[$row['id'] . 'icon_path']) : '';  
		$contents = !empty($_POST[$row['id'] . 'contents']) ? securit($_POST[$row['id'] . 'contents']) : '';
		
		if( !empty($icon_path) )
			$icon = $icon_path;
		
		if( !empty($cat) )
			$Sql->Query_inject("UPDATE ".PREFIX."news_cat SET name = '" . $cat . "', icon = '" . $icon . "', contents = '" . $contents . "' WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
			
	}
	$Sql->Close($result);
	
	redirect(HOST . SCRIPT);
}
elseif( !empty($_GET['del']) && !empty($id) ) //Suppression de la catégorie.
{
	$Sql->Query_inject("DELETE FROM ".PREFIX."news_cat WHERE id = " . $id, __LINE__, __FILE__);
	$Sql->Query_inject("UPDATE ".PREFIX."news SET idcat = 0 WHERE idcat = " . $id, __LINE__, __FILE__);
		
	redirect(HOST . SCRIPT);
}
//On ajoute la nouvelle catégorie
elseif( !empty($_POST['add']) ) //Ajout de la catégorie.
{
	$cat = !empty($_POST['cat']) ? securit($_POST['cat']) : '';  
	$icon = !empty($_POST['icon']) ? securit($_POST['icon']) : ''; 
	$icon_path = !empty($_POST['icon_path']) ? securit($_POST['icon_path']) : ''; 
	$contents = !empty($_POST['contents']) ? securit($_POST['contents']) : ''; 
		
	if( !empty($icon_path) )
		$icon = $icon_path;
			
	if( !empty($cat) )
	{	
		//On insere le nouveau lien, tout en précisant qu'il s'agit d'un lien ajouté et donc supprimable
		$Sql->Query_inject("INSERT INTO ".PREFIX."news_cat (name, contents, icon) VALUES('" . $cat . "', '" . $contents . "', '" . $icon . "')", __LINE__, __FILE__);
			
		redirect(HOST . SCRIPT); 	
	}
	else
		redirect(HOST . DIR . '/news/admin_news_cat.php?error=incomplete#errorh');
}
//Sinon on rempli le formulaire
else	
{		
	$Template->Set_filenames(array(
		'admin_news_cat'=> 'news/admin_news_cat.tpl'
	));
	  
	//Images disponibles
	$rep = './';
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
	}
	
	$image_list = '<option value="">--</option>';
	foreach($img_array as $key => $img_path)
		$image_list .= '<option value="' . $img_path . '">' . $img_path . '</option>';
	
	$Template->Assign_vars(array(
		'THEME' => $CONFIG['theme'],	
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
	$get_error = !empty($_GET['error']) ? securit($_GET['error']) : '';
	if( $get_error == 'incomplete' )
		$Errorh->Error_handler($LANG['e_incomplete'], E_USER_NOTICE);	
	
	$result = $Sql->Query_while("SELECT a.id, a.name, a.contents, a.icon
	FROM ".PREFIX."news_cat a", __LINE__, __FILE__);
	while( $row = $Sql->Sql_fetch_assoc($result) )
	{
		//On reccourci le lien si il est trop long pour éviter de déformer l'administration.
		$row['name'] = html_entity_decode($row['name']);
		$name = strlen($row['name']) > 45 ? substr($row['name'], 0, 45) . '...' : $row['name'];

		$img_direct_path = (strpos($row['icon'], '/') !== false);
		$image_list = '<option value=""' . ($img_direct_path ? ' selected="selected"' : '') . '>--</option>';
		foreach($img_array as $key => $img_path)
		{	
			$selected = ($img_path == $row['icon']) ? ' selected="selected"' : '';
			$image_list .= '<option value="' . $img_path . '"' . ($img_direct_path ? '' : $selected) . '>' . $img_path . '</option>';
		}
		
		$Template->Assign_block_vars('cat', array(
			'IDCAT' => $row['id'],
			'CAT' => $name,
			'CONTENTS' => $row['contents'],
			'IMG_PATH' => $img_direct_path ? $row['icon'] : '',
			'IMG_ICON' => !empty($row['icon']) ? '<img src="' . $row['icon'] . '" alt="" class="valign_middle" />' : '',		
			'IMG_LIST' => $image_list,
		));
	}
	$Sql->Close($result);
		
	$Template->Pparse('admin_news_cat'); // traitement du modele	
}

require_once('../kernel/admin_footer.php');

?>