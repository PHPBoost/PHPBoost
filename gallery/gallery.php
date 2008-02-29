<?php
/*##################################################
 *                               gallery.php
 *                            -------------------
 *   begin                : August 12, 2005
 *   copyright          : (C) 2005 Viarre Régis
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

require_once('../includes/begin.php'); 
require_once('../gallery/gallery_begin.php');
require_once('../includes/header.php');

$g_idpics = !empty($_GET['id']) ? numeric($_GET['id']) : 0;
$g_del = !empty($_GET['del']) ? numeric($_GET['del']) : 0;
$g_note =  !empty($_GET['note']) ? numeric($_GET['note']) : 0;
$g_add = !empty($_GET['add']) ? true : false;
$g_page = !empty($_GET['p']) ? numeric($_GET['p']) : 1;
$g_views = !empty($_GET['views']) ? true : false;
$g_notes = !empty($_GET['notes']) ? true : false;
$g_sort =  !empty($_GET['sort']) ? 'sort=' . securit($_GET['sort']) : '';
//Récupération du mode d'ordonnement.
if( preg_match('`([a-z]+)_([a-z]+)`', $g_sort, $array_match) )
{	
	$g_type = $array_match[1];
	$g_mode = $array_match[2];	
}
else
	list($g_type, $g_mode) = array('date', 'asc');


include_once('../gallery/gallery.class.php');
$Gallery = new Gallery;

if( !empty($g_del) ) //Suppression d'une image.
{
	$Gallery->Del_pics($g_del);
	
	//Régénération du cache des photos aléatoires.
	$Cache->Generate_module_file('gallery');
	
	redirect(HOST . DIR . '/gallery/gallery' . transid('.php?cat=' . $g_idcat, '-' . $g_idcat . '.php', '&'));
}
elseif( !empty($g_idpics) && isset($_GET['move']) ) //Déplacement d'une image.
{
	$move = !empty($_GET['move']) ? numeric($_GET['move']) : 0;
	$Gallery->Move_pics($g_idpics, $move);
	
	//Régénération du cache des photos aléatoires.
	$Cache->Generate_module_file('gallery');
					
	redirect(HOST . DIR . '/gallery/gallery' . transid('.php?cat=' . $move, '-' . $move . '.php', '&'));
}
elseif( isset($_FILES['gallery']) ) //Upload
{ 
	if( !empty($g_idcat) )
	{
		if( !isset($CAT_GALLERY[$g_idcat]) || $CAT_GALLERY[$g_idcat]['aprob'] == 0 ) 
			redirect(HOST . DIR . '/gallery/gallery' . transid('.php?error=unexist_cat', '', '&'));
	}
	else //Racine.
		$CAT_GALLERY[0]['auth'] = $CONFIG_GALLERY['auth_root'];
		
	//Niveau d'autorisation de la catégorie, accès en écriture.
	if( !$Member->Check_auth($CAT_GALLERY[$g_idcat]['auth'], READ_CAT_GALLERY) && !$Member->Check_auth($CAT_GALLERY[$g_idcat]['auth'], WRITE_CAT_GALLERY) )
		$Errorh->Error_handler('e_auth', E_USER_REDIRECT); 

	//Niveau d'autorisation de la catégorie, accès en écriture.
	if( !$Gallery->auth_upload_pics($Member->Get_attribute('user_id'), $Member->Get_attribute('level')) )
		redirect(HOST . DIR . '/gallery/gallery' . transid('.php?add=1&cat=' . $g_idcat . '&error=upload_limit', '-' . $g_idcat . '.php?add=1&error=upload_limit', '&') . '#errorh');
	
	$dir = 'pics/';
	include_once('../includes/upload.class.php');
	$Upload = new Upload($dir);
	
	$idpic = 0;
	$idcat_post = !empty($_POST['cat']) ? numeric($_POST['cat']) : 0;
	$name_post = !empty($_POST['name']) ? securit($_POST['name']) : '';

	if( is_writable($dir) )
	{
		if( $_FILES['gallery']['size'] > 0 )
		{
			$Upload->Upload_file('gallery', '`([a-z0-9])+\.(jpg|gif|png)+`i', UNIQ_NAME, $CONFIG_GALLERY['weight_max']);
			if( !empty($Upload->error) ) //Erreur, on arrête ici
				redirect(HOST . DIR . '/gallery/gallery' . transid('.php?add=1&cat=' . $g_idcat . '&error=' . $Upload->error, '-' . $g_idcat . '.php?add=1&error=' . $Upload->error, '&') . '#errorh');
			else
			{
				$path = $dir . $Upload->filename['gallery'];
				$error = $Upload->Validate_img($path, $CONFIG_GALLERY['width_max'], $CONFIG_GALLERY['height_max'], DELETE_ON_ERROR);
				if( !empty($error) ) //Erreur, on arrête ici
					redirect(HOST . DIR . '/gallery/gallery' . transid('.php?add=1&cat=' . $g_idcat . '&error=' . $error, '-' . $g_idcat . '.php?add=1&error=' . $error, '&') . '#errorh');
				else
				{					
					//Enregistrement de l'image dans la bdd.
					$Gallery->Resize_pics($path);		
					if( !empty($Gallery->error) )
						redirect(HOST . DIR . '/gallery/gallery' . transid('.php?add=1&cat=' . $g_idcat . '&error=' . $Upload->error, '-' . $g_idcat . '.php?add=1&error=' . $Upload->error, '&') . '#errorh');
					
					$idpic = $Gallery->Add_pics($idcat_post, $name_post, $Upload->filename['gallery'], $Member->Get_attribute('user_id'));
					if( !empty($Gallery->error) )
						redirect(HOST . DIR . '/gallery/gallery' . transid('.php?add=1&cat=' . $g_idcat . '&error=' . $Upload->error, '-' . $g_idcat . '.php?add=1&error=' . $Upload->error, '&') . '#errorh');
					
					//Régénération du cache des photos aléatoires.
					$Cache->Generate_module_file('gallery');
				}				
			}
		}
	}
	
	redirect(HOST . DIR . '/gallery/gallery' . transid('.php?add=1&cat=' . $idcat_post . '&id=' . $idpic, '-' . $idcat_post . '.php?add=1&id=' . $idpic, '&'));
}
elseif( $g_add )
{
	$Template->Set_filenames(array(
		'gallery_add' => '../templates/' . $CONFIG['theme'] . '/gallery/gallery_add.tpl'
	));
	
	if( !empty($g_idcat) )
	{
		if( !isset($CAT_GALLERY[$g_idcat]) || $CAT_GALLERY[$g_idcat]['aprob'] == 0 ) 
			redirect(HOST . DIR . '/gallery/gallery' . transid('.php?error=unexist_cat', '', '&'));

		$cat_links = '';
		foreach($CAT_GALLERY as $id => $array_info_cat)
		{
			if( $id > 0 )
			{
				if( $CAT_GALLERY[$g_idcat]['id_left'] >= $array_info_cat['id_left'] && $CAT_GALLERY[$g_idcat]['id_right'] <= $array_info_cat['id_right'] && $array_info_cat['level'] <= $CAT_GALLERY[$g_idcat]['level'] )
					$cat_links .= ' <a href="gallery' . transid('.php?cat=' . $id, '-' . $id . '.php') . '">' . $array_info_cat['name'] . '</a> &raquo;';
			}
		}
	}
	else //Racine.
	{
		$cat_links = '';
		$CAT_GALLERY[0]['auth'] = $CONFIG_GALLERY['auth_root'];
		$CAT_GALLERY[0]['aprob'] = 1;
		$CAT_GALLERY[0]['name'] = $LANG['root'];
	}
	
	//Niveau d'autorisation de la catégorie, accès en écriture.
	if( !$Member->Check_auth($CAT_GALLERY[$g_idcat]['auth'], READ_CAT_GALLERY) && !$Member->Check_auth($CAT_GALLERY[$g_idcat]['auth'], WRITE_CAT_GALLERY) )
		$Errorh->Error_handler('e_auth', E_USER_REDIRECT); 
	
	$auth_cats = '<option value="0">' . $LANG['root'] . '</option>';
	foreach($CAT_GALLERY as $idcat => $key)
	{
		if( $idcat != 0  && $CAT_GALLERY[$idcat]['aprob'] == 1 )
		{		
			if( $Member->Check_auth($CAT_GALLERY[$idcat]['auth'], READ_CAT_GALLERY) && $Member->Check_auth($CAT_GALLERY[$idcat]['auth'], WRITE_CAT_GALLERY) )
			{	
				$margin = ($CAT_GALLERY[$idcat]['level'] > 0) ? str_repeat('--------', $CAT_GALLERY[$idcat]['level']) : '--';
				$selected = ($idcat == $g_idcat) ? ' selected="selected"' : '';
				$auth_cats .= '<option value="' . $idcat . '"' . $selected . '>' . $margin . ' ' . $CAT_GALLERY[$idcat]['name'] . '</option>';
			}
		}
	}
	
	//Gestion erreur.
	$get_error = !empty($_GET['error']) ? trim($_GET['error']) : '';
	$array_error = array('e_upload_invalid_format', 'e_upload_max_weight', 'e_upload_max_dimension', 'e_upload_error', 'e_upload_failed_unwritable', 'e_upload_already_exist', 'e_unlink_disabled', 'e_unsupported_format', 'e_unabled_create_pics', 'e_error_resize', 'e_no_graphic_support', 'e_unabled_incrust_logo', 'delete_thumbnails', 'upload_limit');
	if( in_array($get_error, $array_error) )
		$Errorh->Error_handler($LANG[$get_error], E_USER_WARNING);
	elseif( $get_error == 'unexist_cat' )
		$Errorh->Error_handler($LANG['e_unexist_cat'], E_USER_NOTICE);	
		
	$module_data_path = $Template->Module_data_path('gallery');
	$path_pics = $Sql->Query("SELECT path FROM ".PREFIX."gallery WHERE id = '" . $g_idpics . "'", __LINE__, __FILE__);
	
	//Aficchage de la photo uploadée.
	if( !empty($g_idpics) )
	{	
		$imageup = $Sql->Query_array("gallery", "idcat", "name", "path", "WHERE id = '" . $g_idpics . "'", __LINE__, __FILE__);
		$Template->Assign_block_vars('image_up', array(
			'NAME' => $imageup['name'],
			'IMG' => '<a href="gallery.php?cat=' . $imageup['idcat'] . '&amp;id=' . $g_idpics . '#pics_max"><img src="pics/' . $imageup['path'] . '" alt="" /></a>',
			'L_SUCCESS_UPLOAD' => $LANG['success_upload_img'],
			'U_CAT' => '<a href="gallery.php?cat=' . $imageup['idcat'] . '">' . $CAT_GALLERY[$imageup['idcat']]['name'] . '</a>'
		));
	}

	//Affichage du quota d'image uploadée.
	$quota = isset($CAT_GALLERY[$g_idcat]['auth']['r-1']) ? ($CAT_GALLERY[$g_idcat]['auth']['r-1'] != '3') : true;
	if( $quota )
	{
		switch( $Member->Get_attribute('level') )
		{
			case 2:
			$l_pics_quota = $LANG['illimited'];
			break;
			case 1:
			$l_pics_quota = $CONFIG_GALLERY['limit_modo'];
			break;
			default:
			$l_pics_quota = $CONFIG_GALLERY['limit_member'];
		}
		$nbr_upload_pics = $Gallery->Get_nbr_upload_pics($Member->Get_attribute('user_id'));
		
		$Template->Assign_block_vars('image_quota', array(
			'L_IMAGE_QUOTA' => sprintf($LANG['image_quota'], $nbr_upload_pics, $l_pics_quota)
		));	
	}
	
	$Template->Assign_vars(array(
		'SID' => SID,
		'THEME' => $CONFIG['theme'],
		'LANG' => $CONFIG['lang'],
		'CAT_ID' => $g_idcat,
		'GALLERY' => !empty($g_idcat) ? $CAT_GALLERY[$g_idcat]['name'] : $LANG['gallery'],
		'CATEGORIES' => $auth_cats,
		'WIDTH_MAX' => $CONFIG_GALLERY['width_max'],
		'HEIGHT_MAX' => $CONFIG_GALLERY['height_max'],
		'WEIGHT_MAX' => $CONFIG_GALLERY['weight_max'],
		'ADD_PICS' => $Member->Check_auth($CAT_GALLERY[$g_idcat]['auth'], WRITE_CAT_GALLERY) ? '<a href="gallery' . transid('.php?add=1&amp;cat=' . $g_idcat) . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/add.png" alt="" class="valign_middle" /></a>' : '',
		'IMG_FORMAT' => 'JPG, PNG, GIF',
		'L_IMG_FORMAT' => $LANG['img_format'],
		'L_WIDTH_MAX' => $LANG['width_max'],
		'L_HEIGHT_MAX' => $LANG['height_max'],
		'L_WEIGHT_MAX' => $LANG['weight_max'],
		'L_ADD_IMG' => $LANG['add_pic'],
		'L_GALLERY' => $LANG['gallery'],
		'L_GALLERY_INDEX' => $LANG['gallery_index'],
		'L_CATEGORIES' => $LANG['categories'],
		'L_NAME' => $LANG['name'],
		'L_UNIT_PX' => $LANG['unit_pixels'],
		'L_UNIT_KO' => $LANG['unit_kilobytes'],
		'L_UPLOAD' => $LANG['upload_img'],
		'U_GALLERY_CAT_LINKS' => $cat_links,
		'U_GALLERY_ACTION_ADD' => transid('.php?add=1&amp;cat=' . $g_idcat),
		'U_INDEX' => transid('.php')
	));
		
	$Template->Pparse('gallery_add'); 
}
else
{
	$Template->Set_filenames(array(
		'gallery' => '../templates/' . $CONFIG['theme'] . '/gallery/gallery.tpl'
	));
	
	if( !empty($g_idcat) )
	{
		if( !isset($CAT_GALLERY[$g_idcat]) || $CAT_GALLERY[$g_idcat]['aprob'] == 0 ) 
			redirect(HOST . DIR . '/gallery/gallery' . transid('.php?error=unexist_cat', '', '&'));

		$cat_links = '';
		foreach($CAT_GALLERY as $id => $array_info_cat)
		{
			if( $id > 0 )
			{	
				if( $CAT_GALLERY[$g_idcat]['id_left'] >= $array_info_cat['id_left'] && $CAT_GALLERY[$g_idcat]['id_right'] <= $array_info_cat['id_right'] && $array_info_cat['level'] <= $CAT_GALLERY[$g_idcat]['level'] )
					$cat_links .= ' <a href="gallery' . transid('.php?cat=' . $id, '-' . $id . '.php') . '">' . $array_info_cat['name'] . '</a> &raquo;';
			}
		}
		$clause_cat = " WHERE gc.id_left > '" . $CAT_GALLERY[$g_idcat]['id_left'] . "' AND gc.id_right < '" . $CAT_GALLERY[$g_idcat]['id_right'] . "' AND gc.level = '" . ($CAT_GALLERY[$g_idcat]['level'] + 1) . "' AND gc.aprob = 1";
	}
	else //Racine.
	{
		$cat_links = '';
		$clause_cat = " WHERE gc.level = '0' AND gc.aprob = 1";
		$CAT_GALLERY[0]['auth'] = $CONFIG_GALLERY['auth_root'];
		$CAT_GALLERY[0]['aprob'] = 1;
		$CAT_GALLERY[0]['name'] = $LANG['root'];
		$CAT_GALLERY[0]['level'] = -1;
	}
	
	//Niveau d'autorisation de la catégorie
	if( !$Member->Check_auth($CAT_GALLERY[$g_idcat]['auth'], READ_CAT_GALLERY) )
		$Errorh->Error_handler('e_auth', E_USER_REDIRECT); 
	
	$nbr_pics = $Sql->Query("SELECT COUNT(*) FROM ".PREFIX."gallery WHERE idcat = '" . $g_idcat . "' AND aprob = 1", __LINE__, __FILE__);
	$total_cat = $Sql->Query("SELECT COUNT(*) FROM ".PREFIX."gallery_cats gc " . $clause_cat, __LINE__, __FILE__);

	//Gestion erreur.
	$get_error = !empty($_GET['error']) ? trim($_GET['error']) : '';
	if( $get_error == 'unexist_cat' )
		$Errorh->Error_handler($LANG['e_unexist_cat'], E_USER_NOTICE);	
		
	//On crée une pagination si le nombre de catégories est trop important.
	include_once('../includes/pagination.class.php'); 
	$Pagination = new Pagination();

	//Colonnes des catégories.
	$nbr_column_cats = ($total_cat > $CONFIG_GALLERY['nbr_column']) ? $CONFIG_GALLERY['nbr_column'] : $total_cat;
	$nbr_column_cats = !empty($nbr_column_cats) ? $nbr_column_cats : 1;
	$column_width_cats = floor(100/$nbr_column_cats);
	
	//Colonnes des images.
	$nbr_column_pics = ($nbr_pics > $CONFIG_GALLERY['nbr_column']) ? $CONFIG_GALLERY['nbr_column'] : $nbr_pics;
	$nbr_column_pics = !empty($nbr_column_pics) ? $nbr_column_pics : 1;
	$column_width_pics = floor(100/$nbr_column_pics);
	
	$is_admin = $Member->Check_level(ADMIN_LEVEL) ? true : false;
	$is_modo = ($Member->Check_auth($CAT_GALLERY[$g_idcat]['auth'], EDIT_CAT_GALLERY)) ? true : false;
	
	$module_data_path = $Template->Module_data_path('gallery');
	$rewrite_title = url_encode_rewrite($CAT_GALLERY[$g_idcat]['name']);
	
	//Ordonnement.
	$array_order = array('name' => $LANG['name'], 'date' => $LANG['date'], 'views' => $LANG['views'], 'notes' => $LANG['notes'], 'com' => $LANG['com_s']);
	foreach($array_order as $type => $name)
	{
		$Template->Assign_block_vars('order', array(
			'ORDER_BY' => '<a href="gallery' . transid('.php?sort=' . $type . '_desc&amp;cat=' . $g_idcat, '-' . $g_idcat . '+' . $rewrite_title . '.php?sort=' . $type . '_desc') . '" style="background-image:url(' . $module_data_path . '/images/' . $type . '.png);">' . $name . '</a>'
		));
	}
	
	$Template->Assign_vars(array(
		'ARRAY_JS' => '',
		'NBR_PICS' => 0,
		'MAX_START' => 0,
		'START_THUMB' => 0,
		'END_THUMB' => 0,
		'SID' => SID,
		'THEME' => $CONFIG['theme'],
		'LANG' => $CONFIG['lang'],
		'PAGINATION' => $Pagination->Display_pagination('gallery' . transid('.php?p=%d&amp;cat=' . $g_idcat . '&amp;id=' . $g_idpics . '&amp;' . $g_sort, '-' . $g_idcat . '-' . $g_idpics . '-%d.php?&' . $g_sort), $total_cat, 'p', $CONFIG_GALLERY['nbr_pics_max'], 3),	
		'COLUMN_WIDTH_CAT' => $column_width_cats,
		'COLUMN_WIDTH_PICS' => $column_width_pics,
		'COLSPAN' => $CONFIG_GALLERY['nbr_column'],
		'NOTE_MAX' => $CONFIG_GALLERY['note_max'],
		'CAT_ID' => $g_idcat,
		'GALLERY' => !empty($g_idcat) ? $CAT_GALLERY[$g_idcat]['name'] : $LANG['gallery'],
		'HEIGHT_MAX' => ($CONFIG_GALLERY['height'] + 6),
		'WIDTH_MAX' => $column_width_pics,
		'MODULE_DATA_PATH' => $module_data_path,
		'ADD_PICS' => $Member->Check_auth($CAT_GALLERY[$g_idcat]['auth'], WRITE_CAT_GALLERY) ? '<a href="gallery' . transid('.php?add=1&amp;cat=' . $g_idcat) . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/add.png" alt="" class="valign_middle" /></a>' : '',
		'L_CONFIRM_DEL_FILE' => $LANG['confim_del_file'],
		'L_FILE_FORBIDDEN_CHARS' => $LANG['file_forbidden_chars'],
		'L_TOTAL_IMG' => sprintf($LANG['total_img_cat'], $nbr_pics),		
		'L_ADD_IMG' => $LANG['add_pic'],
		'L_GALLERY' => $LANG['gallery'],
		'L_CATEGORIES' => ($CAT_GALLERY[$g_idcat]['level'] >= 0) ? $LANG['sub_album'] : $LANG['album'],	
		'L_NAME' => $LANG['name'],
		'L_EDIT' => $LANG['edit'],
		'L_MOVETO' => $LANG['moveto'],
		'L_DELETE' => $LANG['delete'],
		'L_SUBMIT' => $LANG['submit'],
		'L_ALREADY_VOTED' => $LANG['already_vote'],
		'L_ORDER_BY' => $LANG['orderby'] . (isset($LANG[$g_type]) ? ' ' . strtolower($LANG[$g_type]) : ''),
		'L_DIRECTION' => $LANG['direction'],
		'L_DISPLAY' => $LANG['display'],		
		'L_VOTES' => $LANG['votes'],
		'L_VOTE' => $LANG['vote'],
		'U_INDEX' => transid('.php'),
		'U_GALLERY_CAT_LINKS' => $cat_links,
		'U_BEST_VIEWS' => '<a class="com" href="gallery' . transid('.php?views=1&amp;cat=' . $g_idcat, '-' . $g_idcat . '.php?views=1') . '" style="background-image:url(' . $module_data_path . '/images/views.png);">' . $LANG['best_views'] . '</a>',
		'U_BEST_NOTES' => '<a class="com" href="gallery' . transid('.php?notes=1&amp;cat=' . $g_idcat, '-' . $g_idcat . '.php?notes=1') . '" style="background-image:url(' . $module_data_path . '/images/notes.png);">' . $LANG['best_notes'] . '</a>',
		'U_ASC' => '<a class="com" href="gallery' . transid('.php?cat=' . $g_idcat . '&amp;sort=' . $g_type . '_' . 'asc', '-' . $g_idcat . '.php?sort=' . $g_type . '_' . 'asc') . '" style="background-image:url(' . $module_data_path . '/images/up.png);">' . $LANG['asc'] . '</a>',
		'U_DESC' => '<a class="com" href="gallery' . transid('.php?cat=' . $g_idcat . '&amp;sort=' . $g_type . '_' . 'desc', '-' . $g_idcat . '.php?sort=' . $g_type . '_' . 'desc') . '" style="background-image:url(' . $module_data_path . '/images/down.png);">' . $LANG['desc'] . '</a>',
	));
	
	//Catégories non autorisées.
	$unauth_cats_sql = array();
	$unauth_cats = array();
	foreach($CAT_GALLERY as $idcat => $key)
	{
		if( $idcat > 0 && $CAT_GALLERY[$idcat]['aprob'] == 1 )
		{
			if( !$Member->Check_auth($CAT_GALLERY[$idcat]['auth'], READ_CAT_GALLERY) )
			{
				$clause_level = !empty($g_idcat) ? ($CAT_GALLERY[$idcat]['level'] == ($CAT_GALLERY[$g_idcat]['level'] + 1)) : ($CAT_GALLERY[$idcat]['level'] == 0);
				if( $clause_level )
					$unauth_cats_sql[] = $idcat;
				$unauth_cats[] = $idcat;
			}
		}
	}
	$clause_unauth_cats = (count($unauth_cats_sql) > 0) ? " AND gc.id NOT IN (" . implode(', ', $unauth_cats_sql) . ")" : '';

	##### Catégorie disponibles #####	
	if( $total_cat > 0 && count($unauth_cats_sql) < $total_cat )
	{
		$Template->Assign_block_vars('cat', array(			
			'EDIT' => $is_admin ? '<a href="admin_gallery_cat.php"><img class="valign_middle" src="../templates/' . $CONFIG['theme'] .  '/images/' . $CONFIG['lang'] . '/edit.png" alt="" /></a>' : ''
		));	
			
		$i = 0;	
		$result = $Sql->Query_while("SELECT gc.id, gc.name, gc.contents, gc.status, (gc.nbr_pics_aprob + gc.nbr_pics_unaprob) AS nbr_pics, gc.nbr_pics_unaprob, g.path 
		FROM ".PREFIX."gallery_cats gc
		LEFT JOIN ".PREFIX."gallery g ON g.idcat = gc.id AND g.aprob = 1
		" . $clause_cat . $clause_unauth_cats . "
		GROUP BY gc.id
		ORDER BY gc.id_left
		" . $Sql->Sql_limit($Pagination->First_msg($CONFIG_GALLERY['nbr_pics_max'], 'p'), $CONFIG_GALLERY['nbr_pics_max']), __LINE__, __FILE__);
		while( $row = $Sql->Sql_fetch_assoc($result) )
		{
			//On genère le tableau pour $CONFIG_GALLERY['nbr_column'] colonnes
			$multiple_x = $i / $nbr_column_cats;
			$tr_start = is_int($multiple_x) ? '<tr>' : '';
			$i++;	
			$multiple_x = $i / $nbr_column_cats;
			$tr_end = is_int($multiple_x) ? '</tr>' : '';

			//Si la miniature n'existe pas (cache vidé) on regénère la miniature à partir de l'image en taille réelle.
			if( !file_exists('pics/thumbnails/' . $row['path']) )
				$Gallery->Resize_pics('pics/' . $row['path']); //Redimensionnement + création miniature

			$Template->Assign_block_vars('cat.list', array(
				'IDCAT' => $row['id'],
				'CAT' => $row['name'],
				'DESC' => $row['contents'],
				'IMG' => !empty($row['path']) ? '<img src="pics/thumbnails/' . $row['path'] . '" alt="" />' : '',
				'EDIT' => $is_admin ? '<a href="admin_gallery_cat.php?id=' . $row['id'] . '"><img class="valign_middle" src="../templates/' . $CONFIG['theme'] .  '/images/' . $CONFIG['lang'] . '/edit.png" alt="" /></a>' : '',
				'TR_START' => $tr_start,
				'TR_END' => $tr_end,			
				'LOCK' => ($row['status'] == 0) ? '<img class="valign_middle" src="../templates/' . $CONFIG['theme'] . '/images/readonly.png" alt="" title="' . $LANG['gallery_lock'] . '" />' : '',
				'L_NBR_PICS' => sprintf($LANG['nbr_pics_info'], $row['nbr_pics']),
				'U_CAT' => transid('.php?cat=' . $row['id'], '-' . $row['id'] . '+' . url_encode_rewrite($row['name']) . '.php')
			));
		}
		$Sql->Close($result);	
		
		//Création des cellules du tableau si besoin est.
		while( !is_int($i/$nbr_column_cats) )
		{		
			$i++;
			$Template->Assign_block_vars('cat.end_td', array(
				'TD_END' => '<td class="row2" style="width:' . $column_width_cats . '%">&nbsp;</td>',
				'TR_END' => (is_int($i/$nbr_column_cats)) ? '</tr>' : ''			
			));	
		}
	}	
	
	##### Affichage des photos #####	
	if( $nbr_pics > 0 )
	{
		switch($g_type)
		{
			case 'name' : 
			$sort_type = 'g.name';
			break;		
			case 'date' : 
			$sort_type = 'g.timestamp';
			break;		
			case 'views' : 
			$sort_type = 'g.views';
			break;		
			case 'notes' :
			$sort_type = 'g.note';
			break;	
			case 'com' :
			$sort_type = 'g.nbr_com';
			break;			
			default :
			$sort_type = 'g.timestamp';
		}	
		switch($g_mode)
		{
			case 'desc' : 
			$sort_mode = 'DESC';
			break;		
			case 'asc' : 
			$sort_mode = 'ASC';
			break;		
			default:
			$sort_mode = 'ASC';
		}	
		$g_sql_sort = ' ORDER BY ' . $sort_type . ' ' . $sort_mode;	
		if( $g_views )
			$g_sql_sort = ' ORDER BY g.views DESC';	
		elseif( $g_notes )
			$g_sql_sort = ' ORDER BY g.note DESC';	
	
		$Template->Assign_block_vars('pics', array(
			'EDIT' => $is_admin ? '<a href="admin_gallery_cat.php' . (!empty($g_idcat) ? '?id=' . $g_idcat : '') . '"><img class="valign_middle" src="../templates/' . $CONFIG['theme'] .  '/images/' . $CONFIG['lang'] . '/edit.png" alt="" /></a>' : ''
		));	
				
		//Liste des catégories.	
		$array_cat_list = array(0 => '<option value="0" %s>' . $LANG['root'] . '</option>');
		$result = $Sql->Query_while("SELECT id, level, name 
		FROM ".PREFIX."gallery_cats
		WHERE aprob = 1
		ORDER BY id_left", __LINE__, __FILE__);
		while( $row = $Sql->Sql_fetch_assoc($result) )
		{
			if( !in_array($row['id'], $unauth_cats) )
			{
				$margin = ($row['level'] > 0) ? str_repeat('--------', $row['level']) : '--';
				$array_cat_list[$row['id']] = $Member->Check_auth($CAT_GALLERY[$row['id']]['auth'], EDIT_CAT_GALLERY) ? '<option value="' . $row['id'] . '" %s>' . $margin . ' ' . $row['name'] . '</option>' : '';
			}
		}
		$Sql->Close($result);
		
		//Affichage d'une photo demandé.
		if( !empty($g_idpics) )
		{
			$result = $Sql->Query_while("SELECT g.id, g.idcat, g.name, g.user_id, g.views, g.width, g.height, g.weight, g.timestamp, g.note, g.nbrnote, g.nbr_com, g.aprob, m.login
			FROM ".PREFIX."gallery g
			LEFT JOIN ".PREFIX."member m ON m.user_id = g.user_id		
			WHERE g.idcat = '" . $g_idcat . "' AND g.id = '" . $g_idpics . "' AND g.aprob = 1
			" . $g_sql_sort . "
			" . $Sql->Sql_limit(0, 1), __LINE__, __FILE__);
			$info_pics = $Sql->Sql_fetch_assoc($result);			
			if( !empty($info_pics['id']) )
			{
				//Commentaires
				$link_pop = "<a class=\"com\" href=\"#\" onclick=\"popup('" . HOST . DIR . transid("/includes/com.php?i=" . $info_pics['id'] . "gallery") . "', 'gallery');\">";
				$link_current = '<a class="com" href="' . HOST . DIR . '/gallery/gallery' . transid('.php?cat=' . $info_pics['idcat'] . '&amp;id=' . $info_pics['id'] . '&amp;i=0&amp;sort=' . $g_sort, '-' . $info_pics['idcat'] . '-' . $info_pics['id'] . '.php?i=0&amp;sort=' . $g_sort) . '#gallery">';	
				$link = ($CONFIG['com_popup'] == '0') ? $link_current : $link_pop;
				
				$l_com = ($info_pics['nbr_com'] > 1) ? $LANG['com_s'] : $LANG['com'];
				
				$com_true = $l_com . ' (' . $info_pics['nbr_com'] . ')</a>';
				$com_false = $LANG['post_com'] . '</a>';
				$com = (!empty($info_pics['nbr_com'])) ? $com_true : $com_false;
							
				//Affichage miniatures.		
				$id_previous = 0;
				$id_next = 0;
				$nbr_pics_display_before = floor(($nbr_column_pics - 1)/2); //Nombres de photos de chaque côté de la miniature de la photo affichée.
				$nbr_pics_display_after = ($nbr_column_pics - 1) - floor($nbr_pics_display_before);				
				list($i, $reach_pics_pos, $pos_pics, $thumbnails_before, $thumbnails_after, $start_thumbnails, $end_thumbnails) = array(0, false, 0, 0, 0, $nbr_pics_display_before, $nbr_pics_display_after);
				$array_pics = array();
				$array_js = 'var array_pics = new Array();';
				$result = $Sql->Query_while("SELECT g.id, g.idcat, g.path
				FROM ".PREFIX."gallery g
				LEFT JOIN ".PREFIX."member m ON m.user_id = g.user_id		
				WHERE g.idcat = '" . $g_idcat . "' AND g.aprob = 1
				" . $g_sql_sort, __LINE__, __FILE__);
				while( $row = $Sql->Sql_fetch_assoc($result) )
				{
					//Si la miniature n'existe pas (cache vidé) on regénère la miniature à partir de l'image en taille réelle.
					if( !file_exists('pics/thumbnails/' . $row['path']) )
						$Gallery->Resize_pics('pics/' . $row['path']); //Redimensionnement + création miniature

					//Affichage de la liste des miniatures sous l'image.
					$array_pics[] = '<td class="row2" style="text-align:center;height:' . ($CONFIG_GALLERY['height'] + 16) . 'px"><span id="thumb' . $i . '"><a href="gallery' . transid('.php?cat=' . $row['idcat'] . '&amp;id=' . $row['id'] . '&amp;sort=' . $g_sort, '-' . $row['idcat'] . '-' . $row['id'] . '.php?sort=' . $g_sort) . '#pics_max' . '"><img src="pics/thumbnails/' . $row['path'] . '" alt="" / ></a></span></td>';
					
					if( $row['id'] == $g_idpics )
					{	
						$reach_pics_pos = true;
						$pos_pics = $i;						
					}
					else
					{	
						
						if( !$reach_pics_pos )
						{
							$thumbnails_before++;
							$id_previous = $row['id'];
						}
						else
						{	
							$thumbnails_after++;
							if( empty($id_next) )
								$id_next = $row['id'];
						}
					}	
					
					$array_js .= 'array_pics[' . $i . '] = new Array();' . "\n"; 
					$array_js .= 'array_pics[' . $i . '][\'link\'] = \'' . transid('.php?cat=' . $row['idcat'] . '&amp;id=' . $row['id'], '-' . $row['idcat'] . '-' . $row['id'] . '.php') . '#pics_max' . "';\n";
					$array_js .= 'array_pics[' . $i . '][\'path\'] = \'' . $row['path'] . "';\n";
					$i++;
				}
				$Sql->Close($result);
								
				if( $thumbnails_before < $nbr_pics_display_before )	
					$end_thumbnails += $nbr_pics_display_before - $thumbnails_before;				
				if( $thumbnails_after < $nbr_pics_display_after )
					$start_thumbnails += $nbr_pics_display_after - $thumbnails_after;	
					
				$Template->Assign_vars(array(
					'ARRAY_JS' => $array_js,
					'NBR_PICS' => ($i - 1),
					'MAX_START' => ($i - 1) - $nbr_column_pics,
					'START_THUMB' => (($pos_pics - $start_thumbnails) > 0) ? ($pos_pics - $start_thumbnails) : 0,
					'END_THUMB' => ($pos_pics + $end_thumbnails),					
					'L_INFORMATIONS' => $LANG['informations'],
					'L_NAME' => $LANG['name'],
					'L_POSTOR' => $LANG['postor'],					
					'L_VIEWS' => $LANG['views'],
					'L_ADD_ON' => $LANG['add_on'],
					'L_DIMENSION' => $LANG['dimension'],
					'L_SIZE' => $LANG['size'],
					'L_NOTE' => $LANG['note'],
					'L_COM' => $LANG['com'],
					'L_EDIT' => $LANG['edit'],
					'L_APROB_IMG' => ($info_pics['aprob'] == 1) ? $LANG['unaprob'] : $LANG['aprob'],
					'L_THUMBNAILS' => $LANG['thumbnails']		
				));	

				//Liste des catégories.
				$cat_list = '';
				foreach($array_cat_list as $key_cat => $option_value)
					$cat_list .= ($key_cat == $info_pics['idcat']) ? sprintf($option_value, 'selected="selected"') : sprintf($option_value, '');
				
				$activ_note = ($CONFIG_GALLERY['activ_note'] == 1 && $Member->Check_level(MEMBER_LEVEL) );
				if( $activ_note )
				{
					$info_pics['note'] = round($info_pics['note'] / 0.25) * 0.25;
					$img_note = '<script type="text/javascript">
					<!--
					array_note[' . $info_pics['id'] . '] = \'' . $info_pics['note'] . '\';
					-->
					</script>
					<div style="width:' . ($CONFIG_GALLERY['note_max']*16) . 'px;" onmouseout="out_div(' . $info_pics['id'] . ', array_note[' . $info_pics['id'] . '])" onmouseover="over_div()">';
					for($i = 1; $i <= $CONFIG_GALLERY['note_max']; $i++)
					{
						$star_img = 'stars.png';
						if( $info_pics['note'] < $i )
						{							
							$decimal = $i - $info_pics['note'];
							if( $decimal >= 1 )
								$star_img = 'stars0.png';
							elseif( $decimal >= 0.75 )
								$star_img = 'stars1.png';
							elseif( $decimal >= 0.50 )
								$star_img = 'stars2.png';
							else
								$star_img = 'stars3.png';
						}	
						
						$img_note .= '<a href="javascript:send_note(' . $info_pics['id'] . ', ' . $info_pics['idcat'] . ', ' . $i . ')" onmouseover="select_stars(' . $info_pics['id'] . ', ' . $i . ');"><img src="../templates/'. $CONFIG['theme'] . '/images/' . $star_img . '" alt="" id="' . $info_pics['id'] . '_stars' . $i . '" /></a>';
					}
					$img_note .= '</div>'; 
					$img_note .= ($CONFIG_GALLERY['display_nbrnote']) ? '<span id="' . $info_pics['id'] . '_note" class="text_small">(' . $info_pics['nbrnote'] . ' ' . (($info_pics['nbrnote'] > 1) ? $LANG['votes'] : $LANG['vote']) . ')</span>' : '';
				}			
				
				//Affichage de l'image et de ses informations.
				$Template->Assign_block_vars('pics.pics_max', array(
					'ID' => $info_pics['id'],
					'IMG' => '<img src="show_pics' . transid('.php?id=' . $g_idpics . '&amp;cat=' . $g_idcat) . '" alt="" />',
					'NAME' => '<span id="fi_' . $info_pics['id'] . '">' . $info_pics['name'] . '</span> <span id="fi' . $info_pics['id'] . '"></span>',
					'POSTOR' => '<a class="com" href="../member/member' . transid('.php?id=' . $info_pics['user_id'], '-' . $info_pics['user_id'] . '.php') . '">' . $info_pics['login'] . '</a>',
					'DATE' => gmdate_format('date_format_short', $info_pics['timestamp']),
					'VIEWS' => ($info_pics['views'] + 1),
					'DIMENSION' => $info_pics['width'] . ' x ' . $info_pics['height'],
					'SIZE' => number_round($info_pics['weight']/1024, 1),
					'NOTE' => $activ_note ? '<br />' . $img_note : '',
					'COM' => $link . $com,
					'COLSPAN' => ($CONFIG_GALLERY['nbr_column'] + 2),	
					'CAT' => $cat_list,	
					'U_PREVIOUS' => ($pos_pics > 0) ? '<a href="gallery' . transid('.php?cat=' . $g_idcat . '&amp;id=' . $id_previous, '-' . $g_idcat . '-' . $id_previous . '.php') . '#pics_max"><img src="../templates/' . $CONFIG['theme'] . '/images/left.png" alt="" class="valign_middle" /></a> <a href="gallery' . transid('.php?cat=' . $g_idcat . '&amp;id=' . $id_previous, '-' . $g_idcat . '-' . $id_previous . '.php') . '#pics_max">' . $LANG['previous'] . '</a>' : '',
					'U_NEXT' => ($pos_pics < ($i - 1)) ? '<a href="gallery' . transid('.php?cat=' . $g_idcat . '&amp;id=' . $id_next, '-' . $g_idcat . '-' . $id_next . '.php') . '#pics_max">' . $LANG['next'] . '</a> <a href="gallery' . transid('.php?cat=' . $g_idcat . '&amp;id=' . $id_next, '-' . $g_idcat . '-' . $id_next . '.php') . '#pics_max"><img src="../templates/' . $CONFIG['theme'] . '/images/right.png" alt="" class="valign_middle" /></a>' : '',
					'U_LEFT_THUMBNAILS' => (($pos_pics - $start_thumbnails) > 0) ? '<span id="display_left"><a href="javascript:display_thumbnails(\'left\')"><img src="../templates/' . $CONFIG['theme'] . '/images/left.png" class="valign_middle" alt="" /></a></span>' : '<span id="display_left"></span>',
					'U_RIGHT_THUMBNAILS' => (($pos_pics - $start_thumbnails) <= ($i - 1) - $nbr_column_pics) ? '<span id="display_right"><a href="javascript:display_thumbnails(\'right\')"><img src="../templates/' . $CONFIG['theme'] . '/images/right.png" class="valign_middle" alt="" /></a></span>' : '<span id="display_right"></span>'
				));				

				//Modo?
				if( $is_modo )
				{
					$Template->Assign_block_vars('pics.pics_max.modo', array(
						'RENAME' => addslashes($info_pics['name']),
						'RENAME_CUT' => addslashes($info_pics['name']),		
						'IMG_APROB' => $CONFIG['lang'] . '/' . (($info_pics['aprob'] == 1) ? 'unaprob.png' : 'aprob.png'),
						'U_DEL' => transid('php?del=' . $info_pics['id'] . '&amp;cat=' . $g_idcat, '-' . $g_idcat . '.php?del=' . $info_pics['id']),
						'U_MOVE' => transid('.php?id=' . $info_pics['id'] . '&amp;move=\' + this.options[this.selectedIndex].value', '-0-' . $info_pics['id'] . '.php?move=\' + this.options[this.selectedIndex].value')
					));			
				}
				
				//Affichage de la liste des miniatures sous l'image.
				$i = 0;
				foreach($array_pics as $pics)
				{
					if( $i >= ($pos_pics - $start_thumbnails) && $i <= ($pos_pics + $end_thumbnails) )
					{
						$Template->Assign_block_vars('pics.pics_max.list_preview_pics', array(
							'PICS' => $pics
						));
					}
					$i++;
				}

				//Commentaires
				if( isset($_GET['i']) )
				{
					$_com_vars = 'gallery.php?cat=' . $g_idcat . '&amp;id=' . $g_idpics . '&amp;i=%d';
					$_com_vars_e = 'gallery.php?cat=' . $g_idcat . '&id=' . $g_idpics . '&i=1';
					$_com_vars_r = 'gallery-' . $g_idcat . '-' . $g_idpics . '.php?i=%d%s';
					$_com_idprov = $g_idpics;
					$_com_script = 'gallery';
					include_once('../includes/com.php');
				}
			}
		}
		else
		{
			//On crée une pagination si le nombre de photos est trop important.
			include_once('../includes/pagination.class.php'); 
			$Pagination = new Pagination();
			
			$Template->Assign_vars(array(
				'PAGINATION_PICS' => $Pagination->Display_pagination('gallery' . transid('.php?pp=%d&amp;cat=' . $g_idcat, '-' . $g_idcat . '+' . $rewrite_title . '.php?pp=%d'), $nbr_pics, 'pp', $CONFIG_GALLERY['nbr_pics_max'], 3),
				'L_EDIT' => $LANG['edit']
			));
			
			$is_connected = $Member->Check_level(MEMBER_LEVEL);
			$j = 0;
			$result = $Sql->Query_while("SELECT g.id, g.idcat, g.name, g.path, g.timestamp, g.aprob, g.width, g.height, g.user_id, g.views, g.note, g.nbrnote, g.nbr_com, g.aprob, m.login
			FROM ".PREFIX."gallery g
			LEFT JOIN ".PREFIX."member m ON m.user_id = g.user_id		
			WHERE g.idcat = '" . $g_idcat . "' AND g.aprob = 1
			" . $g_sql_sort . "
			" . $Sql->Sql_limit($Pagination->First_msg($CONFIG_GALLERY['nbr_pics_max'], 'pp'), $CONFIG_GALLERY['nbr_pics_max']), __LINE__, __FILE__);
			while( $row = $Sql->Sql_fetch_assoc($result) )
			{
				//Si la miniature n'existe pas (cache vidé) on regénère la miniature à partir de l'image en taille réelle.
				if( !file_exists('pics/thumbnails/' . $row['path']) )
					$Gallery->Resize_pics('pics/' . $row['path']); //Redimensionnement + création miniature
				
				//On genère le tableau pour x colonnes
				$tr_start = is_int($j / $nbr_column_pics) ? '<tr>' : '';
				$j++;	
				$tr_end = is_int($j / $nbr_column_pics) ? '</tr>' : '';

				//Affichage de l'image en grand.
				if( $CONFIG_GALLERY['display_pics'] == 3 ) //Ouverture en popup plein écran.
					$display_link = '<a class="com" href="' . HOST . DIR . '/gallery/show_pics' . transid('.php?id=' . $row['id'] . '&amp;cat=' . $row['idcat']) . '" rel="lightbox[1]" title="' . $row['name'] . '">';
				elseif( $CONFIG_GALLERY['display_pics'] == 2 ) //Ouverture en popup simple.
					$display_link = '<a class="com" href="javascript:display_pics_popup(\'' . HOST . DIR . '/gallery/show_pics' . transid('.php?id=' . $row['id'] . '&amp;cat=' . $row['idcat']) . '\', \'' . $row['width'] . '\', \'' . $row['height'] . '\')">';
				elseif( $CONFIG_GALLERY['display_pics'] == 1 ) //Ouverture en agrandissement simple.
					$display_link = '<a class="com" href="javascript:display_pics(' . $row['id'] . ', \'' . HOST . DIR . '/gallery/show_pics' . transid('.php?id=' . $row['id'] . '&amp;cat=' . $row['idcat']) . '\', 0)">';
				else //Ouverture nouvelle page.
					$display_link = '<a class="com" href="' . transid('gallery.php?cat=' . $row['idcat'] . '&amp;id=' . $row['id'], 'gallery-' . $row['idcat'] . '-' . $row['id'] . '.php') . '#pics_max">';
				
				//Liste des catégories.
				$cat_list = '';
				foreach($array_cat_list as $key_cat => $option_value)
					$cat_list .= ($key_cat == $row['idcat']) ? sprintf($option_value, 'selected="selected"') : sprintf($option_value, '');
	
				//Commentaires
				$link_pop = "<a class=\"com\" href=\"#\" onclick=\"popup('" . HOST . DIR . transid("/includes/com.php?i=" . $row['id'] . "gallery") . "', 'gallery');\">";
				$link_current = '<a class="com" href="' . HOST . DIR . '/gallery/gallery' . transid('.php?cat=' . $row['idcat'] . '&amp;id=' . $row['id'] . '&amp;i=0', '-' . $row['idcat'] . '-' . $row['id'] . '.php?i=0') . '#gallery">';	
				$link = ($CONFIG['com_popup'] == '0') ? $link_current : $link_pop;
				
				$l_com = ($row['nbr_com'] > 1) ? $LANG['com_s'] : $LANG['com'];
				
				$com_true = $l_com . ' (' . $row['nbr_com'] . ')</a>';
				$com_false = $LANG['post_com'] . '</a>';
				$com = (!empty($row['nbr_com'])) ? $com_true : $com_false;	

				$activ_note = ($CONFIG_GALLERY['activ_note'] == 1 && $is_connected );
				if( $activ_note )
				{
					$row['note'] = round($row['note'] / 0.25) * 0.25;
					$img_note = '<script type="text/javascript">
					<!--
					array_note[' . $row['id'] . '] = \'' . $row['note'] . '\';
					-->
					</script>
					<div style="width:' . ($CONFIG_GALLERY['note_max']*16) . 'px;margin:auto;" onmouseout="out_div(' . $row['id'] . ', array_note[' . $row['id'] . '])" onmouseover="over_div()">';
					for($i = 1; $i <= $CONFIG_GALLERY['note_max']; $i++)
					{
						$star_img = 'stars.png';
						if( $row['note'] < $i )
						{							
							$decimal = $i - $row['note'];
							if( $decimal >= 1 )
								$star_img = 'stars0.png';
							elseif( $decimal >= 0.75 )
								$star_img = 'stars1.png';
							elseif( $decimal >= 0.50 )
								$star_img = 'stars2.png';
							else
								$star_img = 'stars3.png';
						}	
						
						$img_note .= '<a href="javascript:send_note(' . $row['id'] . ', ' . $row['idcat'] . ', ' . $i . ')" onmouseover="select_stars(' . $row['id'] . ', ' . $i . ');"><img src="../templates/'. $CONFIG['theme'] . '/images/' . $star_img . '" alt="" id="' . $row['id'] . '_stars' . $i . '" /></a>';
					}
					$img_note .= '</div>'; 
					$img_note .= ($CONFIG_GALLERY['display_nbrnote']) ? '<span id="' . $row['id'] . '_note" class="text_small">(' . $row['nbrnote'] . ' ' . (($row['nbrnote'] > 1) ? $LANG['votes'] : $LANG['vote']) . ')</span>' : '';
				}
				
				$Template->Assign_block_vars('pics.list', array(
					'ID' => $row['id'],
					'APROB' => $row['aprob'],
					'IMG' => '<img src="pics/thumbnails/' . $row['path'] . '" alt="' . $row['name'] . '" />',
					'PATH' => $row['path'],
					'NAME' => ($CONFIG_GALLERY['activ_title'] == 1) ? $display_link . '<span id="fi_' . $row['id'] . '">' . wordwrap_html($row['name'], 22, ' ') . '</span></a> <span id="fi' . $row['id'] . '"></span>' : '<span id="fi_' . $row['id'] . '"></span></a> <span id="fi' . $row['id'] . '"></span>',	
					'POSTOR' => ($CONFIG_GALLERY['activ_user'] == 1) ? '<br />' . $LANG['by'] . (!empty($row['login']) ? ' <a class="com" href="../member/member' . transid('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '">' . $row['login'] . '</a>' : ' ' . $LANG['guest']) : '',
					'VIEWS' => ($CONFIG_GALLERY['activ_view'] == 1) ? '<br />' . $row['views'] . ' ' . ($row['views'] > 1 ? $LANG['views'] : $LANG['view']) : '',
					'COM' => ($CONFIG_GALLERY['activ_com'] == 1) ? '<br />' . $link . $com  : '',
					'NOTE' => $activ_note ? '<br />' . $img_note : '',
					'TR_START' => $tr_start,
					'TR_END' => $tr_end,
					'CAT' => $cat_list,
					'L_APROB_IMG' => ($row['aprob'] == 1) ? $LANG['unaprob'] : $LANG['aprob'],
					'U_DISPLAY' => $display_link
				));	
				
				if( $is_modo )
				{
					$Template->Assign_block_vars('pics.list.modo', array(
						'RENAME' => addslashes($row['name']),
						'RENAME_CUT' => addslashes($row['name']),		
						'IMG_APROB' => $CONFIG['lang'] . '/' . (($row['aprob'] == 1) ? 'unaprob.png' : 'aprob.png'),
						'U_DEL' => transid('.php?del=' . $row['id'] . '&amp;cat=' . $g_idcat, '-' . $g_idcat . '.php?del=' . $row['id']),
						'U_MOVE' => transid('.php?id=' . $row['id'] . '&amp;move=\' + this.options[this.selectedIndex].value', '-0-' . $row['id'] . '.php?move=\' + this.options[this.selectedIndex].value')
					));			
				}
			}
			$Sql->Close($result);
			
			//Création des cellules du tableau si besoin est.
			while( !is_int($j/$nbr_column_pics) )
			{		
				$j++;
				$Template->Assign_block_vars('pics.end_td_pics', array(
					'TD_END' => '<td class="row2" style="padding:0;padding-top:2px;width:' . $column_width_pics . '%">&nbsp;</td>',
					'TR_END' => (is_int($j/$nbr_column_pics)) ? '</tr>' : ''			
				));	
			}
		}
	}	

	$Template->Pparse('gallery'); 
}

require_once('../includes/footer.php');

?>