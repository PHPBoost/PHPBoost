<?php
/*##################################################
 *                               web.php
 *                            -------------------
 *   begin                : July 28, 2005
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
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

require_once('../kernel/begin.php'); 
require_once('../web/web_begin.php'); 
require_once('../kernel/header.php'); 

if( !empty($idweb) && !empty($CAT_WEB[$idcat]['name']) && !empty($idcat) ) //Contenu du lien.
{
	$Template->Set_filenames(array('web'=> 'web/web.tpl'));
	
	if( !$Member->Check_level($CAT_WEB[$idcat]['secure']) )
		$Errorh->Error_handler('e_auth', E_USER_REDIRECT); 
	if( empty($web['id']) )
		$Errorh->Error_handler('e_unexist_link_web', E_USER_REDIRECT);
		
	if( $Member->Check_level(ADMIN_LEVEL) )
	{
		$java = "<script language='JavaScript' type='text/javascript'>
		<!--
		function Confirm() {
		return confirm('" . $LANG['delete_link'] . "');
		}
		-->
		</script>";
		
		$edit = '&nbsp;&nbsp;<a href="../web/admin_web' . transid('.php?id=' . $web['id']) . '" title="' . $LANG['edit'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/edit.png" class="valign_middle" /></a>';
		$del = '&nbsp;&nbsp;<a href="../web/admin_web.php?delete=1&amp;id=' . $web['id'] . '" title="' . $LANG['delete'] . '" onClick="javascript:return Confirm();"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/delete.png" class="valign_middle" /></a>';
	}
	else
	{
		$edit = '';
		$del = '';
		$java = '';
	}

	$Template->Assign_vars(array(
		'JAVA' => $java,
		'EDIT' => $edit,
		'DEL' => $del
	));
		
	$Template->Assign_vars(array(
		'C_DISPLAY_WEB' => true,
		'MODULE_DATA_PATH' => $Template->Module_data_path('web'),
		'IDWEB' => $web['id'],		
		'NAME' => $web['title'],
		'CONTENTS' => $web['contents'],
		'URL' => $web['url'],
		'CAT' => $CAT_WEB[$idcat]['name'],
		'DATE' => gmdate_format('date_format_short', $web['timestamp']),
		'COMPT' => $web['compt'],
		'THEME' => $CONFIG['theme'],
		'LANG' => $CONFIG['lang'],
		'COM' => com_display_link($web['nbr_com'], '../web/web' . transid('.php?cat=' . $idcat . '&amp;id=' . $idweb . '&amp;i=0', '-' . $idcat . '-' . $idweb . '.php?i=0'), $idweb, 'web'),
		'U_WEB_CAT' => transid('.php?cat=' . $idcat, '-' . $idcat . '.php'),
		'L_DESC' => $LANG['description'],
		'L_CAT' => $LANG['category'],
		'L_DATE' => $LANG['date'],
		'L_TIMES' => $LANG['n_time'],
		'L_VIEWS' => $LANG['views']
	));
	
	//Affichage notation.
	include_once('../kernel/framework/note.class.php'); 
	$Note = new Note('web', $idweb, transid('web.php?cat=' . $idcat . '&amp;id=' . $idweb, 'web-' . $idcat . '-' . $idweb . '.php'), $CONFIG_WEB['note_max'], '', NOTE_DISPLAY_NOTE);
	include_once('../kernel/framework/note.php');
	
	//Affichage commentaires.
	if( isset($_GET['i']) )
	{
		include_once('../kernel/framework/content/comments.class.php'); 
		$Comments = new Comments('web', $idweb, transid('web.php?cat=' . $idcat . '&amp;id=' . $idweb . '&amp;i=%s', 'web-' . $idcat . '-' . $idweb . '.php?i=%s'));
		include_once('../kernel/com.php');
	}	

	$Template->Pparse('web');
}
elseif( !empty($idcat) && empty($idweb) ) //Catégories.
{
	$Template->Set_filenames(array('web'=> 'web/web.tpl'));
	
	if( !$Member->Check_level($CAT_WEB[$idcat]['secure']) )
		$Errorh->Error_handler('e_auth', E_USER_REDIRECT); 
	
	$nbr_web = $Sql->Query("SELECT COUNT(*) as compt 
	FROM ".PREFIX."web 
	WHERE aprob = 1 AND idcat = '" . $idcat . "'", __LINE__, __FILE__);
	
	$Template->Assign_vars(array(
		'C_WEB_LINK' => true,
		'CAT_NAME' => $CAT_WEB[$idcat]['name'],		
		'NO_CAT' => ($nbr_web == 0) ? $LANG['none_link'] : '',
		'MAX_NOTE' => $CONFIG_WEB['note_max'],
		'L_LINK' => $LANG['link'],
		'L_DATE' => $LANG['date'],
		'L_VIEW' => $LANG['views'],
		'L_NOTE' => $LANG['note'],
		'L_COM' => $LANG['com'],
		'U_WEB_ALPHA_TOP' => transid('.php?sort=alpha&amp;mode=desc&amp;cat=' . $idcat, '-' . $idcat . '.php?sort=alpha&amp;mode=desc'),
		'U_WEB_ALPHA_BOTTOM' => transid('.php?sort=alpha&amp;mode=asc&amp;cat=' . $idcat, '-' . $idcat . '.php?sort=alpha&amp;mode=asc'),
		'U_WEB_DATE_TOP' => transid('.php?sort=date&amp;mode=desc&amp;cat=' . $idcat, '-' . $idcat . '.php?sort=date&amp;mode=desc'),
		'U_WEB_DATE_BOTTOM' => transid('.php?sort=date&amp;mode=asc&amp;cat=' . $idcat, '-' . $idcat . '.php?sort=date&amp;mode=asc'),
		'U_WEB_VIEW_TOP' => transid('.php?sort=view&amp;mode=desc&amp;cat=' . $idcat, '-' . $idcat . '.php?sort=view&amp;mode=desc'),
		'U_WEB_VIEW_BOTTOM' => transid('.php?sort=view&amp;mode=asc&amp;cat=' . $idcat, '-' . $idcat . '.php?sort=view&amp;mode=asc'),
		'U_WEB_NOTE_TOP' => transid('.php?sort=note&amp;mode=desc&amp;cat=' . $idcat, '-' . $idcat . '.php?sort=note&amp;mode=desc'),
		'U_WEB_NOTE_BOTTOM' => transid('.php?sort=note&amp;mode=asc&amp;cat=' . $idcat, '-' . $idcat . '.php?sort=note&amp;mode=asc'),
		'U_WEB_COM_TOP' => transid('.php?sort=com&amp;mode=desc&amp;cat=' . $idcat, '-' . $idcat . '.php?sort=com&amp;mode=desc'),
		'U_WEB_COM_BOTTOM' => transid('.php?sort=com&amp;mode=asc&amp;cat=' . $idcat, '-' . $idcat . '.php?sort=com&amp;mode=asc')
	));		
	
	$get_sort = retrieve(GET, 'sort', '');	
	switch($get_sort)
	{
		case 'alpha' : 
		$sort = 'title';
		break;		
		case 'date' : 
		$sort = 'timestamp';
		break;		
		case 'view' : 
		$sort = 'compt';
		break;		
		case 'note' :
		$sort = 'note/' . $CONFIG_WEB['note_max'];
		break;		
		case 'com' :
		$sort = 'nbr_com';
		break;	
		default :
		$sort = 'timestamp';
	}
	
	$get_mode = retrieve(GET, 'mode', '');	
	$mode = ($get_mode == 'asc') ? 'ASC' : 'DESC';	
	$unget = (!empty($get_sort) && !empty($mode)) ? '?sort=' . $get_sort . '&amp;mode=' . $get_mode : '';

	//On crée une pagination si le nombre de lien est trop important.
	include_once('../kernel/framework/pagination.class.php'); 
	$Pagination = new Pagination();
		
	$Template->Assign_vars(array(
		'PAGINATION' => $Pagination->Display_pagination('web' . transid('.php' . (!empty($unget) ? $unget . '&amp;' : '?') . 'cat=' . $idcat . '&amp;p=%d', '-' . $idcat . '-0-%d.php' . (!empty($unget) ? '?' . $unget : '')), $nbr_web, 'p', $CONFIG_WEB['nbr_web_max'], 3)
	));

	include_once('../kernel/framework/note.class.php');
	$Note = new Note(null, null, null, null, '', NOTE_NO_CONSTRUCT);
	$result = $Sql->Query_while("SELECT id, title, timestamp, compt, note, nbrnote, nbr_com
	FROM ".PREFIX."web
	WHERE aprob = 1 AND idcat = '" . $idcat . "'
	ORDER BY " . $sort . " " . $mode . 
	$Sql->Sql_limit($Pagination->First_msg($CONFIG_WEB['nbr_web_max'], 'p'), $CONFIG_WEB['nbr_web_max']), __LINE__, __FILE__);
	while( $row = $Sql->Sql_fetch_assoc($result) )
	{
		//On reccourci le lien si il est trop long.
		$row['title'] = (strlen($row['title']) > 45 ) ? substr(html_entity_decode($row['title']), 0, 45) . '...' : $row['title'];
		
		$Template->Assign_block_vars('web', array(			
			'NAME' => $row['title'],
			'CAT' => $CAT_WEB[$idcat]['name'],
			'DATE' => gmdate_format('date_format_short', $row['timestamp']),
			'COMPT' => $row['compt'],
			'NOTE' => ($row['nbrnote'] > 0) ? $Note->Display_note($row['note'], $CONFIG_WEB['note_max']) : '<em>' . $LANG['no_note'] . '</em>',
			'COM' => $row['nbr_com'],
			'U_WEB_LINK' => transid('.php?cat=' . $idcat . '&amp;id=' . $row['id'], '-' .  $idcat . '-' . $row['id'] . '.php')
		));
	}
	$Sql->Close($result);
	
	$Template->Pparse('web');
}
else
{
	$Template->Set_filenames(array('web'=> 'web/web.tpl'));
	
	$total_link = $Sql->Query("SELECT COUNT(*) FROM ".PREFIX."web_cat wc
	LEFT JOIN ".PREFIX."web w ON w.idcat = wc.id
	WHERE w.aprob = 1 AND wc.aprob = 1 AND wc.secure <= '" . $Member->Get_attribute('level') . "'", __LINE__, __FILE__);
	$total_cat = $Sql->Query("SELECT COUNT(*) as compt FROM ".PREFIX."web_cat WHERE aprob = 1 AND secure <= '" . $Member->Get_attribute('level') . "'", __LINE__, __FILE__);
	
	$edit = '';
	if( $Member->Check_level(ADMIN_LEVEL) )
		$edit = '&nbsp;&nbsp;<a href="admin_web_cat.php' .  SID . '" title=""><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/edit.png" class="valign_middle" /></a>';

	//On crée une pagination si le nombre de catégories est trop important.
	include_once('../kernel/framework/pagination.class.php'); 
	$Pagination = new Pagination();

	$CONFIG_WEB['nbr_column'] = ($total_cat > $CONFIG_WEB['nbr_column']) ? $CONFIG_WEB['nbr_column'] : $total_cat;
	$CONFIG_WEB['nbr_column'] = !empty($CONFIG_WEB['nbr_column']) ? $CONFIG_WEB['nbr_column'] : 1;
	
	$Template->Assign_vars(array(
		'C_WEB_CAT' => true,
		'PAGINATION' => $Pagination->Display_pagination('web' . transid('.php?p=%d', '-0-0-%d.php'), $total_cat, 'p', $CONFIG_WEB['nbr_cat_max'], 3),
		'EDIT' => $edit,
		'TOTAL_FILE' => $total_link,
		'L_CATEGORIES' => $LANG['categories'],
		'L_PROPOSE_LINK' => $LANG['propose_link'],
		'L_HOW_LINK' => $LANG['how_link'],
		'U_WEB_ADD' => transid('.php?web=true')
	));
	
	//Catégorie disponibles	
	$column_width = floor(100/$CONFIG_WEB['nbr_column']);
	$result = $Sql->Query_while(
	"SELECT aw.id, aw.name, aw.contents, aw.icon, COUNT(w.id) as count
	FROM ".PREFIX."web_cat aw
	LEFT JOIN ".PREFIX."web w ON w.idcat = aw.id AND w.aprob = 1
	WHERE aw.aprob = 1 AND aw.secure <= '" . $Member->Get_attribute('level') . "'
	GROUP BY aw.id
	ORDER BY aw.class
	" . $Sql->Sql_limit($Pagination->First_msg($CONFIG_WEB['nbr_cat_max'], 'p'), $CONFIG_WEB['nbr_cat_max']), __LINE__, __FILE__);
	while( $row = $Sql->Sql_fetch_assoc($result) )
	{
		$Template->Assign_block_vars('cat_list', array(
			'WIDTH' => $column_width,
			'TOTAL' => $row['count'],
			'CAT' => $row['name'],
			'CONTENTS' => $row['contents'],	
			'U_IMG_CAT' => !empty($row['icon']) ? '<a href="../web/web' . transid('.php?cat=' . $row['id'], '-' . $row['id'] . '.php') . '"><img src="' . $row['icon'] . '" alt="" /></a><br />' : '',
			'U_WEB_CAT' => transid('.php?cat=' . $row['id'], '-' . $row['id'] . '.php')
		));
	}
	$Sql->Close($result);
	
	$Template->Pparse('web');
}
			
require_once('../kernel/footer.php'); 

?>