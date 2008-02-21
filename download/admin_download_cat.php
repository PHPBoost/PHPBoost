<?php
/*##################################################
 *                               admin_download_cat.php
 *                            -------------------
 *   begin                : July 15, 2005
 *   copyright          : (C) 2005 Viarre Régis
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

require_once('../includes/admin_begin.php');
load_module_lang('download'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../includes/admin_header.php');

$id = !empty($_GET['id']) ? numeric($_GET['id']) : '' ;
$top = !empty($_GET['top']) ? numeric($_GET['top']) : '' ;
$bottom = !empty($_GET['bot']) ? numeric($_GET['bot']) : '' ;
$del = isset($_GET['del']) ?  true : false;

//Si c'est confirmé on met à jour!
if( !empty($_POST['valid']) )
{
	$result = $Sql->Query_while("SELECT id
	FROM ".PREFIX."download_cat
	ORDER BY class", __LINE__, __FILE__);
	while( $row = $Sql->Sql_fetch_assoc($result) )
	{
		$cat = !empty($_POST[$row['id'] . 'cat']) ? securit($_POST[$row['id'] . 'cat']) : '';  
		$contents = !empty($_POST[$row['id'] . 'contents']) ? securit($_POST[$row['id'] . 'contents']) : '';
		$icon = !empty($_POST[$row['id'] . 'icon']) ? securit($_POST[$row['id'] . 'icon']) : ''; 
		$icon_path = !empty($_POST[$row['id'] . 'icon_path']) ? securit($_POST[$row['id'] . 'icon_path']) : ''; 
		$aprob = isset($_POST[$row['id'] . 'aprob']) ? numeric($_POST[$row['id'] . 'aprob']) : '0';
		$secure = isset($_POST[$row['id'] . 'secure']) ? numeric($_POST[$row['id'] . 'secure']) : '-1';
		
		if( !empty($icon_path) )
			$icon = $icon_path;
			
		if( !empty($cat) )
			$Sql->Query_inject("UPDATE ".PREFIX."download_cat SET name = '" . $cat . "', contents = '" . $contents . "', icon = '" . $icon . "', aprob = '" . $aprob . "', secure = '" . $secure . "' WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
	}
	$Sql->Close($result);
	
	//Régénération du cache des catégories.
	$Cache->Generate_module_file('download');
	
	redirect(HOST . SCRIPT);
}
elseif( empty($top) && empty($bottom) && $del && !empty($id) ) //Suppression du lien.
{
	$Sql->Query_inject("DELETE FROM ".PREFIX."download_cat WHERE id = '" . $id . "'", __LINE__, __FILE__);	
	$Sql->Query_inject("UPDATE ".PREFIX."download SET idcat = '' WHERE idcat = '" . $id . "'", __LINE__, __FILE__);
	
	//Régénération du cache des catégories.
	$Cache->Generate_module_file('download');
	
	redirect(HOST . SCRIPT);
}
elseif( (!empty($top) || !empty($bottom)) && !empty($id) ) //Monter/descendre.
{
	if( !empty($top) )
	{	
		$idmoins = ($top - 1);
		
		$Sql->Query_inject("UPDATE ".PREFIX."download_cat SET class = 0 WHERE class = '" . $top . "'", __LINE__, __FILE__);
		$Sql->Query_inject("UPDATE ".PREFIX."download_cat SET class = '" . $top . "' WHERE class = '" . $idmoins . "'", __LINE__, __FILE__);
		$Sql->Query_inject("UPDATE ".PREFIX."download_cat SET class = '" . $idmoins . "' WHERE class = 0", __LINE__, __FILE__);
		
		//Régénération du cache des catégories.
		$Cache->Generate_module_file('download');
		
		redirect(HOST . SCRIPT . '#d' . $id);
	}
	elseif( !empty($bottom) )
	{
		$idplus = ($bottom + 1);
		
		$Sql->Query_inject("UPDATE ".PREFIX."download_cat SET class = 0 WHERE class = '" . $bottom . "'", __LINE__, __FILE__);
		$Sql->Query_inject("UPDATE ".PREFIX."download_cat SET class = '" . $bottom . "' WHERE class = '" . $idplus . "'", __LINE__, __FILE__);
		$Sql->Query_inject("UPDATE ".PREFIX."download_cat SET class = '" . $idplus . "' WHERE class = 0", __LINE__, __FILE__);
		
		//Régénération du cache des catégories.
		$Cache->Generate_module_file('download');
		
		redirect(HOST . SCRIPT . '#d' . $id);
	}
}
//On ajoute la nouvelle catégorie
elseif( !empty($_POST['add']) ) //Ajout du lien.
{
	$cat = !empty($_POST['cat']) ? securit($_POST['cat']) : '';  
	$contents = !empty($_POST['contents']) ? securit($_POST['contents']) : '';
	$icon = !empty($_POST['icon']) ? securit($_POST['icon']) : ''; 
	$icon_path = !empty($_POST['icon_path']) ? securit($_POST['icon_path']) : ''; 
	$aprob = isset($_POST['aprob']) ? numeric($_POST['aprob']) : '0';
	$secure = isset($_POST['secure']) ? numeric($_POST['secure']) : '-1';
		
	if( !empty($icon_path) )
		$icon = $icon_path;
		
	if( !empty($cat) )
	{	
		$order = $Sql->Query("SELECT MAX(class) FROM ".PREFIX."download_cat", __LINE__, __FILE__);
		$order++;
		
		//On insere le nouveau lien, tout en précisant qu'il s'agit d'un lien ajouté et donc supprimable
		$Sql->Query_inject("INSERT INTO ".PREFIX."download_cat (class,name,contents,icon,aprob,secure) VALUES('" . $order . "', '" . $cat . "', '" . $contents . "', '" . $icon . "', '". $aprob . "', '" . $secure . "')", __LINE__, __FILE__);	
	
		//Régénération du cache des catégories.
		$Cache->Generate_module_file('download');
	
		redirect(HOST . SCRIPT); 
	}
	else
		redirect(HOST . DIR . '/download/admin_download_cat.php?error=incomplete#errorh');
}
//Sinon on rempli le formulaire
else	
{		
	$Template->Set_filenames(array(
		'admin_download_cat' => '../templates/' . $CONFIG['theme'] . '/download/admin_download_cat.tpl'
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
		'L_DOWNLOAD_ADD' => $LANG['download_add'],
		'L_DOWNLOAD_MANAGEMENT' => $LANG['download_management'],
		'L_DOWNLOAD_CAT' => $LANG['cat_management'],
		'L_DOWNLOAD_CONFIG' => $LANG['download_config'],
		'L_LISTE' => $LANG['liste'],
		'L_ADD_CAT' => $LANG['cat_add'],
		'L_NAME' => $LANG['name'],
		'L_DESC' => $LANG['description'],
		'L_ICON' => $LANG['icon_cat'],
		'L_OR_DIRECT_PATH' => $LANG['or_direct_path'],
		'L_STATUS' => $LANG['status'],
		'L_POSITION' => $LANG['position'],
		'L_DELETE' => $LANG['delete'],
		'L_ACTIVATION' => $LANG['activation'],
		'L_ACTIV' => $LANG['activ'],
		'L_UNACTIV' => $LANG['unactiv'],
		'L_ADD' => $LANG['add'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset'],
		'L_RANK' => $LANG['rank'],
		'L_GUEST' => $LANG['guest'],
		'L_MEMBER' => $LANG['member'],
		'L_MODO' => $LANG['modo'],
		'L_ADMIN' => $LANG['admin'],
	));
	
	//Gestion erreur.
	$get_error = !empty($_GET['error']) ? securit($_GET['error']) : '';
	if( $get_error == 'incomplete' )
		$Errorh->Error_handler($LANG['e_incomplete'], E_USER_NOTICE);
		
	$min_cat = $Sql->Query("SELECT MIN(class) FROM ".PREFIX."download_cat", __LINE__, __FILE__);
	$max_cat = $Sql->Query("SELECT MAX(class) FROM ".PREFIX."download_cat", __LINE__, __FILE__);	
		
	$result = $Sql->Query_while("SELECT id, name, class, contents, icon, aprob, secure
	FROM ".PREFIX."download_cat
	ORDER BY class", __LINE__, __FILE__);
	while( $row = $Sql->Sql_fetch_assoc($result) )
	{
		//On reccourci le lien si il est trop long pour éviter de déformer l'administration.
		$row['name'] = html_entity_decode($row['name']);
		$name = strlen($row['name']) > 45 ? substr($row['name'], 0, 45) . '...' : $row['name'];

		//Activation des catégories.
		$enabled = $row['aprob'] == '1' ? 'checked="checked"' : '';	
		$disabled = $row['aprob'] == '0' ? 'checked="checked"' : '';				
		
		//Si on atteint le premier ou le dernier id on affiche pas le lien inaproprié.
		$top_link = $min_cat != $row['class'] ? '<a href="admin_download_cat.php?top=' . $row['class'] . '&amp;id=' . $row['id'] . '" title="">
		<img src="../templates/' . $CONFIG['theme'] . '/images/admin/up.png" alt="" title="" /></a>' : '';
		$bottom_link = $max_cat != $row['class'] ? '<a href="admin_download_cat.php?bot=' . $row['class'] . '&amp;id=' . $row['id'] . '" title="">
		<img src="../templates/' . $CONFIG['theme'] . '/images/admin/down.png" alt="" title="" /></a>' : '';
		
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
			'TOP' => $top_link,
			'BOTTOM' => $bottom_link,			
			'ACTIV_ENABLED' => $enabled,
			'ACTIV_DISABLED' => $disabled
		));	
		
		//Rang d'autorisation.
		for($i= -1; $i <= 2; $i++)
		{
			switch ($i) 
			{	
				case -1:
					$rank = $LANG['guest'];
				break;
				
				case 0:
					$rank = $LANG['member'];
				break;
				
				case 1: 
					$rank = $LANG['modo'];
				break;
		
				case 2:
					$rank = $LANG['admin'];
				break;	
				
				default: -1;
			} 

			$selected = ( $row['secure'] == $i ) ? 'selected="selected"' : '' ;

			$Template->Assign_block_vars('cat.select_auth', array(
				'RANK' => '<option value="' . $i . '" ' . $selected . '>' . $rank . '</option>'
			));
		}
	}
	$Sql->Close($result);
		
	$Template->Pparse('admin_download_cat'); // traitement du modele	
}

require_once('../includes/admin_footer.php');

?>