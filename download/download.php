<?php
/*##################################################
 *                               download.php
 *                            -------------------
 *   begin                : July 27, 2005
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
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

require_once('../includes/begin.php'); 
require_once('../download/download_begin.php');
require_once('../includes/header.php'); 

$page = !empty($_GET['p']) ? numeric($_GET['p']) : 1;
$category_id = !empty($_GET['cat']) ? numeric($_GET['cat']) : 0;
$file_id = !empty($_GET['id']) ? numeric($_GET['id']) : 0;

if( $file_id > 0 ) //Contenu
{
	$Template->Set_filenames(array('download' => '../templates/' . $CONFIG['theme'] . '/download/download.tpl'));
	
	if( !$Member->Check_level($CAT_DOWNLOAD[$idcat]['secure']) )
		$Errorh->Error_handler('e_auth', E_USER_REDIRECT); 
	if( empty($download['id']) )
		$Errorh->Error_handler('e_unexist_file_download', E_USER_REDIRECT); 
	
	if( $Member->Check_level(ADMIN_LEVEL) )
	{
		$java = '<script type="text/javascript">
		<!--
		function Confirm() {
		return confirm("' . $LANG['alert_delete_file'] . '");
		}
		-->
		</script>';
		
		$edit = '&nbsp;&nbsp;<a href="../download/admin_download' . transid('.php?id=' . $download['id']) . '" title="' . $LANG['edit'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/edit.png" class="valign_middle" /></a>';
		$del = '&nbsp;&nbsp;<a href="../download/admin_download.php?delete=1&amp;id=' . $download['id'] . '" title="' . $LANG['delete'] . '" onClick="javascript:return Confirm();"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/delete.png" class="valign_middle" /></a>';
		
		$Template->Assign_vars(array(
			'JAVA' => $java,
			'EDIT' => $edit,
			'DEL' => $del		
		));
	}	
	
	//On encode l'url pour un éventuel rewriting, c'est une opération assez gourmande
	$rewrited_title = ($CONFIG['rewrite'] == 1) ? url_encode_rewrite($download['title']) : '';
	$rewrited_cat_title = ($CONFIG['rewrite'] == 1) ? url_encode_rewrite($CAT_DOWNLOAD[$idcat]['name']) : '';
	
	//Notation
	$link_note = $LANG['note'];
	if( $Member->Get_attribute('user_id') !== -1 ) //Utilisateur connecté
		$link_note = '<script type="text/javascript"><!-- 
		document.write("' . $LANG['note'] . '"); 
		--></script> <noscript><a class="small_link" href="download' . transid('.php?note=' . $download['id'] . '&amp;id=' . $idurl . '&amp;cat=' . $idcat, '-' . $idcat . '-' . $idurl . '-0-0-' . $download['id'] . '+' . $rewrited_title . '.php?note=' . $download['id']) . '#note" title="' . $LANG['note'] . '">' . $LANG['note'] . '</a></noscript>';
		
	$download['nbrnote'] = round($download['nbrnote'] / 0.25) * 0.25;
	$img_note = '<script type="text/javascript">
	<!--
	array_note[' . $download['id'] . '] = \'' . $download['nbrnote'] . '\';
	-->
	</script>
	<div style="width:' . ($CONFIG_DOWNLOAD['note_max']*16 + 100) . 'px;" class="text_small" onmouseout="out_div(' . $download['id'] . ', array_note[' . $download['id'] . '])" onmouseover="over_div()">' . $link_note . ' : ';
	for($i = 1; $i <= $CONFIG_DOWNLOAD['note_max']; $i++)
	{
		$star_img = 'stars.png';
		if( $download['nbrnote'] < $i )
		{							
			$decimal = $i - $download['nbrnote'];
			if( $decimal >= 1 )
				$star_img = 'stars0.png';
			elseif( $decimal >= 0.75 )
				$star_img = 'stars1.png';
			elseif( $decimal >= 0.50 )
				$star_img = 'stars2.png';
			else
				$star_img = 'stars3.png';
		}			
		$img_note .= '<a href="javascript:send_note(' . $download['id'] . ', ' . $idcat . ', ' . $i . ')" onmouseover="select_stars(' . $download['id'] . ', ' . $i . ');"><img src="../templates/'. $CONFIG['theme'] . '/images/' . $star_img . '" alt="" class="valign_middle" id="' . $download['id'] . '_stars' . $i . '" /></a>';
	}
	$img_note .= ' <span id="download_note">(' . $download['nbrnote'] . ' ' . (($download['nbrnote'] > 1) ? $LANG['votes'] : $LANG['vote']) . ')</span></div>';

	//Commentaires
	$link_pop = "<a class=\"com\" href=\"#\" onclick=\"popup('" . HOST . DIR . transid("/includes/com.php?i=" . $idurl . "download") . "', 'download');\">";
	$link_current = '<a class="com" href="' . HOST . DIR . '/download/download' . transid('.php?cat=' . $idcat . '&amp;id=' . $idurl . '&amp;i=0', '-' . $idcat . '-' . $idurl . '.php?i=0') . '#download">';	
	$link = ($CONFIG['com_popup'] == '0') ? $link_current : $link_pop;
	
	$com_true = ($download['nbr_com'] > 1) ? $LANG['com_s'] : $LANG['com'];
	$com_false = $LANG['post_com'] . '</a>';
	$l_com = !empty($download['nbr_com']) ? $com_true . ' (' . $download['nbr_com'] . ')</a>' : $com_false;
	
	$Template->Assign_vars(array(
		'C_DISPLAY_DOWNLOAD' => true,
		'MODULE_DATA_PATH' => $Template->Module_data_path('download'),
		'IDURL' => $download['id'],		
		'NAME' => $download['title'],
		'CONTENTS' => $download['contents'],
		'URL' => $download['url'],
		'CAT' => $CAT_DOWNLOAD[$idcat]['name'],
		'DATE' => gmdate_format('date_format_short', $download['timestamp']),
		'SIZE' => ($download['size'] >= 1) ? $download['size'] . ' ' . $LANG['unit_megabytes'] : ($download['size']*1024) . ' ' . $LANG['unit_kilobytes'],
		'COMPT' => $download['compt'],
		'THEME' => $CONFIG['theme'],
		'COM' => $link . $l_com,
		'NOTE_MAX' => $CONFIG_DOWNLOAD['note_max'],
		'NOTE' => $img_note,
		'LANG' => $CONFIG['lang'],
		'U_DOWNLOAD_CAT' => transid('.php?cat=' . $idcat, '-' . $idcat . '+' . $rewrited_cat_title . '.php'),
		'L_DESC' => $LANG['description'],
		'L_CAT' => $LANG['category'],
		'L_DATE' => $LANG['date'],
		'L_SIZE' => $LANG['size'],
		'L_TIMES' => $LANG['n_time'],
		'L_DOWNLOAD' => $LANG['download'],
		'L_ALREADY_VOTED' => $LANG['already_vote']
	));

	//Affichage et gestion de la notation
	if( !empty($get_note) && !empty($CAT_DOWNLOAD[$idcat]['name']) )
	{
		$Template->Assign_vars(array(
			'L_ACTUAL_NOTE' => $LANG['actual_note'],
			'L_VOTE' => $LANG['vote_action'],
			'L_NOTE' => $LANG['note']
		));
				
		if( $Member->Check_level(MEMBER_LEVEL) ) //Utilisateur connecté.
		{
			if( !empty($_POST['valid_note']) )
			{
				$note = numeric($_POST['note']);
				
				//Echelle de notation.
				$check_note = ( ($note >= 0) && ($note <= $CONFIG_DOWNLOAD['note_max']) ) ? true : false;				
				$users_note = $Sql->Query("SELECT users_note FROM ".PREFIX."download WHERE idcat = '" . $idcat . "' AND id = '" . $get_note . "'", __LINE__, __FILE__);
				
				$array_users_note = explode('/', $users_note);
				if( !in_array($Member->Get_attribute('user_id'), $array_users_note) && $Member->Get_attribute('user_id') != '' && ($check_note === true) )
				{
					$row_note = $Sql->Query_array('download', 'users_note', 'nbrnote', 'note', "WHERE id = '" . $get_note . "'", __LINE__, __FILE__);
					$note = ( ($row_note['note'] * $row_note['nbrnote']) + $note ) / ($row_note['nbrnote'] + 1);
					
					$row_note['nbrnote']++;
					
					$users_note = !empty($row_note['users_note']) ? $row_note['users_note'] . '/' . $Member->Get_attribute('user_id') : $Member->Get_attribute('user_id'); //On ajoute l'id de l'utilisateur.
					
					$Sql->Query_inject("UPDATE ".PREFIX."download SET note = '" . $note . "', nbrnote = '" . $row_note['nbrnote'] . "', 
					users_note = '" . $users_note . "' WHERE id = '" . $get_note . "' AND idcat = '" . $idcat . "'", __LINE__, __FILE__);
					
					//Success.
					redirect(HOST . DIR . '/download/download' . transid('.php?cat=' . $idcat . '&id=' . $get_note, '-' . $idcat . '-' . $get_note . '.php', '&'));
				}
				else
					redirect(HOST . DIR . '/download/download' . transid('.php?cat=' . $idcat . '&id=' . $get_note, '-' . $idcat . '-' . $get_note . '.php', '&'));
			}
			else
			{
				$row = $Sql->Query_array('download', 'users_note', 'nbrnote', 'note', "WHERE idcat = '" . $idcat . "' AND id = '" . $get_note . "'", __LINE__, __FILE__);
				
				$array_users_note = explode('/', $row['users_note']);
				$select = '';
				if( in_array($Member->Get_attribute('user_id'), $array_users_note) ) //Déjà voté
					$select .= '<option value="-1">' . $LANG['already_vote'] . '</option>';
				else 
				{
					//Génération de l'échelle de notation.
					for( $i = -1; $i <= $CONFIG_DOWNLOAD['note_max']; $i++)
					{
						if( $i == -1 )
							$select = '<option value="-1">' . $LANG['note'] . '</option>';
						else
							$select .= '<option value="' . $i . '">' . $i . '</option>';
					}
				}
				
				$Template->Assign_vars(array(
					'C_DISPLAY_DOWNLOAD_NOTE' => true,
					'NOTE' => ($row['nbrnote'] > 0) ? $row['note'] : '<em>' . $LANG['no_note'] . '</em>',
					'SELECT' => $select,
					'U_DOWNLOAD_ACTION_NOTE' => transid('.php?note=' . $get_note . '&amp;id=' . $get_note . '&amp;cat=' . $idcat, '-' . $idcat . '-' . $get_note . '.php?note=' . $get_note)
				));
			}
		}
		else 
			$Errorh->Error_handler('e_auth', E_USER_REDIRECT); 
	}	
	
	//Affichage commentaires.
	if( isset($_GET['i']) )
	{
		include_once('../includes/com.class.php'); 
		$Comments = new Comments('download', $idurl, transid('download.php?cat=' . $idcat . '&amp;id=' . $idurl . '&amp;i=%s', 'category-' . $idcat . '-' . $idurl . '.php?i=%s'));
		include_once('../includes/com.php');
	}	

	$Template->Pparse('download');
}
else
{
	$Template->Set_filenames(array('download' => '../templates/' . $CONFIG['theme'] . '/download/download.tpl'));
	
	$Template->Assign_vars(array(
		'C_ADMIN' => $Member->Check_level(ADMIN_LEVEL),
		'U_ADMIN_CAT' => $category_id > 0 ? transid('admin_download_cat.php?edit=' . $category_id) : transid('admin_download_cat.php'),
		'C_DOWNLOAD_CAT' => true,
		'TITLE' => sprintf($DOWNLOAD_LANG['title_download'] . ($category_id > 0 ? ' - ' . $DOWNLOAD_CATS[$category_id]['name'] : ''))
	));
	
	//let's check if there are some subcategories
	$num_subcats = 0;
	foreach( $DOWNLOAD_CATS as $id => $value )
	{
		if( $id != 0 && $value['id_parent'] == $category_id )
			$num_subcats ++;
	}

	//listing of subcategories
	if( $num_subcats > 0 )
	{
		$Template->Assign_vars(array(
			'C_SUB_CATS' => true
		));	
		
		$i = 1;
		
		foreach( $DOWNLOAD_CATS as $id => $value )
		{
			//List of children categories
			if( $id != 0 && $value['visible'] && $value['id_parent'] == $category_id && (empty($value['auth']) || $Member->Check_auth($value['auth'], AUTH_READ)) )
			{
				if ( $i % $CONFIG_DOWNLOAD['nbr_column'] == 1 )
					$Template->Assign_block_vars('row', array());
				$Template->Assign_block_vars('row.list_cats', array(
					'ID' => $id,
					'NAME' => $value['name'],
					'WIDTH' => floor(100 / (float)$CONFIG_DOWNLOAD['nbr_column']),
					'SRC' => $value['icon'],
					'IMG_NAME' => addslashes($value['name']),
					'NUM_FILES' => sprintf(((int)$value['num_files'] > 1 ? $DOWNLOAD_LANG['num_files_plural'] : $DOWNLOAD_LANG['num_files_singular']), (int)$value['num_files']),
					'U_CAT' => transid('faq.php?id=' . $id, 'category-' . $id . '+' . url_encode_rewrite($value['name']) . '.php'),
					'U_ADMIN_CAT' => transid('admin_download_cat.php?edit=' . $id)
				));
				
				if( !empty($value['icon']) )
					$Template->Assign_vars(array(
						'C_CAT_IMG' => true
					));
					
				$i++;
			}
		}
	}
	
	//Contenu de la catégorie	
	$nbr_files = (int)$Sql->Query("SELECT COUNT(*) FROM ".PREFIX."download WHERE visible = 1 AND idcat = '" . $idcat . "'", __LINE__, __FILE__);
	
	if( $nbr_files > 0 )
	{
		$rewrited_title = url_encode_rewrite($CAT_DOWNLOAD[$idcat]['name']);
		
		$Template->Assign_vars(array(
			'CAT_NAME' => $CAT_DOWNLOAD[$idcat]['name'],
			'L_FILE' => $DOWNLOAD_LANG['file'],
			'L_SIZE' => $LANG['size'],
			'L_DATE' => $LANG['date'],
			'L_DOWNLOAD' => $DOWNLOAD_LANG['download'],
			'L_NOTE' => $LANG['note'],
			'L_COM' => $LANG['com'],
			'L_FILES_IN_THIS_CATEGORY' => $DOWNLOAD_LANG['files_in_category'],
			'U_DOWNLOAD_ALPHA_TOP' => transid('.php?sort=alpha&amp;mode=desc&amp;cat=' . $idcat, '-' . $idcat . '+' . $rewrited_title . '.php?sort=alpha&amp;mode=desc'),
			'U_DOWNLOAD_ALPHA_BOTTOM' => transid('.php?sort=alpha&amp;mode=asc&amp;cat=' . $idcat, '-' . $idcat . '+' . $rewrited_title . '.php?alpha&amp;mode=asc'),
			'U_DOWNLOAD_SIZE_TOP' => transid('.php?sort=size&amp;mode=desc&amp;cat=' . $idcat, '-' . $idcat . '+' . $rewrited_title . '.php?sort=size&amp;mode=desc'),
			'U_DOWNLOAD_SIZE_BOTTOM' => transid('.php?sort=size&amp;mode=asc&amp;cat=' . $idcat, '-' . $idcat . '+' . $rewrited_title . '.php?sort=size&amp;mode=asc'),
			'U_DOWNLOAD_DATE_TOP' => transid('.php?sort=date&amp;mode=desc&amp;cat=' . $idcat, '-' . $idcat . '+' . $rewrited_title . '.php?sort=date&amp;mode=desc'),
			'U_DOWNLOAD_DATE_BOTTOM' => transid('.php?sort=date&amp;mode=asc&amp;cat=' . $idcat, '-' . $idcat . '+' . $rewrited_title . '.php?sort=date&amp;mode=asc'),
			'U_DOWNLOAD_VIEW_TOP' => transid('.php?sort=view&amp;mode=desc&amp;cat=' . $idcat, '-' . $idcat . '+' . $rewrited_title . '.php?sort=view&amp;mode=desc'),
			'U_DOWNLOAD_VIEW_BOTTOM' => transid('.php?sort=view&amp;mode=asc&amp;cat=' . $idcat, '-' . $idcat . '+' . $rewrited_title . '.php?sort=view&amp;mode=asc'),
			'U_DOWNLOAD_NOTE_TOP' => transid('.php?sort=note&amp;mode=desc&amp;cat=' . $idcat, '-' . $idcat . '+' . $rewrited_title . '.php?sort=note&amp;mode=desc'),
			'U_DOWNLOAD_NOTE_BOTTOM' => transid('.php?sort=note&amp;mode=asc&amp;cat=' . $idcat, '-' . $idcat . '+' . $rewrited_title . '.php?sort=note&amp;mode=asc'),
			'U_DOWNLOAD_COM_TOP' => transid('.php?sort=com&amp;mode=desc&amp;cat=' . $idcat, '-' . $idcat . '+' . $rewrited_title . '.php?sort=com&amp;mode=desc'),
			'U_DOWNLOAD_COM_BOTTOM' => transid('.php?sort=com&amp;mode=asc&amp;cat=' . $idcat, '-' . $idcat . '+' . $rewrited_title . '.php?sort=com&amp;mode=asc')
		));		
		
		$get_sort = !empty($_GET['sort']) ? trim($_GET['sort']) : '';	
		switch($get_sort)
		{
			case 'alpha' : 
			$sort = 'title';
			break;	
			case 'size' : 
			$sort = 'size';
			break;			
			case 'date' : 
			$sort = 'timestamp';
			break;		
			case 'view' : 
			$sort = 'compt';
			break;		
			case 'note' :
			$sort = 'note/' . $CONFIG_DOWNLOAD['note_max'];
			break;
			case 'com' :
			$sort = 'nbr_com';
			break;		
			default :
			$sort = 'timestamp';
		}
		
		$get_mode = !empty($_GET['mode']) ? trim($_GET['mode']) : '';
		$mode = ($get_mode == 'asc' || $get_mode == 'desc') ? strtoupper(trim($_GET['mode'])) : 'DESC';	
		$unget = (!empty($get_sort) && !empty($mode)) ? '?sort=' . $get_sort . '&amp;mode=' . $get_mode : '';
			
		//On crée une pagination si le nombre de fichiers est trop important.
		include_once('../includes/pagination.class.php'); 
		$Pagination = new Pagination();
			
		$Template->Assign_vars(array(
			'PAGINATION' => $Pagination->Display_pagination('download' . transid('.php' . (!empty($unget) ? $unget . '&amp;' : '?') . 'cat=' . $idcat . '&amp;p=%d', '-' . $idcat . '-0-%d+' . $rewrited_title . '.php' . $unget), $nbr_files, 'p', $CONFIG_DOWNLOAD['nbr_file_max'], 3),
			'C_FILES' => true
		));

		$result = $Sql->Query_while("SELECT id, title, timestamp, size, count, note, nbrnote, nbr_com, image, short_contents
		FROM ".PREFIX."download
		WHERE visible = 1 AND idcat = '" . $idcat . "'
		ORDER BY " . $sort . " " . $mode . 
		$Sql->Sql_limit($Pagination->First_msg($CONFIG_DOWNLOAD['nbr_file_max'], 'p'), $CONFIG_DOWNLOAD['nbr_file_max']), __LINE__, __FILE__);
		while( $row = $Sql->Sql_fetch_assoc($result) )
		{
			$Template->Assign_block_vars('file', array(			
				'NAME' => $row['title'],
				'C_DESCRIPTION' => !empty($row['short_contents']),
				'DESCRIPTION' => second_parse($row['short_contents']),
				'DATE' => sprintf($DOWNLOAD_LANG['add_on_date'], gmdate_format('date_format_short', $row['timestamp'])),
				'COUNT_DL' => sprintf($DOWNLOAD_LANG['downloaded_n_times'], $row['count']),
				'NOTE' => ($row['nbrnote'] > 0) ? $row['note'] . '/' . $CONFIG_DOWNLOAD['note_max'] : $LANG['no_note'],
				'SIZE' => ($row['size'] >= 1) ? number_round($row['size'], 1) . ' ' . $LANG['unit_megabytes'] : (number_round($row['size'], 1)*1024) . ' ' . $LANG['unit_kilobytes'],
				'COMS' => (int)$row['nbr_com'] > 1 ? sprintf($DOWNLOAD_LANG['num_coms'], $row['com']) : sprintf($DOWNLOAD_LANG['num_com'], $row['nbr_com']),
				'C_IMG' => !empty($row['image']),
				'IMG' => $row['image'],
				'U_DOWNLOAD_LINK' => transid('download/admin_download.php?cat=' . $idcat . '&amp;id=' . $row['id'], 'download-' . $row['id'] . '+' . url_encode_rewrite($row['title']) . '.php')
			));
		}
		$Sql->Close($result);
	}
	else
	{
		$Template->Assign_vars(array(
			'L_NO_FILE_THIS_CATEGORY' => $DOWNLOAD_LANG['none_download'],
			'C_NO_FILE' => true
		));
	}
		
	$Template->Pparse('download');
}
	
require_once('../includes/footer.php'); 

?>