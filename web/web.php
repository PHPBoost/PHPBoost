<?php
/*##################################################
 *                               web.php
 *                            -------------------
 *   begin                : July 28, 2005
 *   copyright            : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
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
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

require_once('../kernel/begin.php'); 
require_once('../web/web_begin.php'); 
require_once('../kernel/header.php'); 

$tpl = new FileTemplate('web/web.tpl');

$notation = new Notation();
$notation->set_module_name('web');
$notation->set_id_in_module($idweb);
$notation->set_notation_scale($CONFIG_WEB['note_max']);

$comments = new Comments();
$comments->set_module_name('web');
$comments->set_id_in_module($idweb);

if (!empty($idweb) && !empty($CAT_WEB[$idcat]['name']) && !empty($idcat)) //Contenu du lien.
{
	if (!$User->check_level($CAT_WEB[$idcat]['secure']))
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	} 
	
	if (empty($web['id']))
	{
		$controller = new UserErrorController(LangLoader::get_message('error', 'errors'), 
            $LANG['e_unexist_link_web']);
        DispatchManager::redirect($controller);
	}
		
	if ($User->check_level(ADMIN_LEVEL))
	{
		$java = "<script language='JavaScript' type='text/javascript'>
		<!--
		function Confirm() {
		return confirm('" . $LANG['delete_link'] . "');
		}
		-->
		</script>";
		
		$edit = '&nbsp;&nbsp;<a href="../web/admin_web' . url('.php?id=' . $web['id']) . '" title="' . $LANG['edit'] . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/edit.png" class="valign_middle" /></a>';
		$del = '&nbsp;&nbsp;<a href="../web/admin_web.php?delete=1&amp;id=' . $web['id'] . '&amp;token=' . $Session->get_token() . '" title="' . $LANG['delete'] . '" onclick="javascript:return Confirm();"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/delete.png" class="valign_middle" /></a>';
	}
	else
	{
		$edit = '';
		$del = '';
		$java = '';
	}

	$tpl->put_all(array(
		'JAVA' => $java,
		'EDIT' => $edit,
		'DEL' => $del
	));
	
	
	$tpl->put_all(array(
		'C_DISPLAY_WEB' => true,
		'IDWEB' => $web['id'],		
		'NAME' => $web['title'],
		'CONTENTS' => FormatingHelper::second_parse($web['contents']),
		'URL' => $web['url'],
		'CAT' => $CAT_WEB[$idcat]['name'],
		'DATE' => gmdate_format('date_format_short', $web['timestamp']),
		'COMPT' => $web['compt'],
		'THEME' => get_utheme(),
		'LANG' => get_ulang(),
		'COM' => '<a href="'. PATH_TO_ROOT .'/web/web' . url('.php?cat=' . $idcat . '&amp;id=' . $idweb . '&amp;com=0', '-' . $idcat . '-' . $idweb . '.php?com=0') .'">'. CommentsService::get_number_and_lang_comments($comments) . '</a>',
		'KERNEL_NOTATION' => NotationService::display_active_image($notation),
		'U_WEB_CAT' => url('.php?cat=' . $idcat, '-' . $idcat . '.php'),
		'L_DESC' => $LANG['description'],
		'L_CAT' => $LANG['category'],
		'L_DATE' => $LANG['date'],
		'L_TIMES' => $LANG['n_time'],
		'L_VIEWS' => $LANG['views']
	));
	
	//Affichage commentaires.
	if (isset($_GET['com']))
	{
		$tpl->put_all(array(
			'COMMENTS' => CommentsService::display($comments)->render()
		));
	}	
}
elseif (!empty($idcat) && empty($idweb)) //Catégories.
{
	if (!$User->check_level($CAT_WEB[$idcat]['secure']))
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	} 
	
	$nbr_web = $Sql->query("SELECT COUNT(*) as compt 
	FROM " . PREFIX . "web 
	WHERE aprob = 1 AND idcat = '" . $idcat . "'", __LINE__, __FILE__);
	
	$tpl->put_all(array(
		'C_WEB_LINK' => true,
		'C_IS_ADMIN' => $User->check_level(ADMIN_LEVEL),
		'CAT_NAME' => $CAT_WEB[$idcat]['name'],		
		'NO_CAT' => ($nbr_web == 0) ? $LANG['none_link'] : '',
		'MAX_NOTE' => $CONFIG_WEB['note_max'],
		'L_LINK' => $LANG['link'],
		'L_DATE' => $LANG['date'],
		'L_VIEW' => $LANG['views'],
		'L_NOTE' => $LANG['note'],
		'L_COM' => $LANG['com'],
		'U_WEB_ALPHA_TOP' => url('.php?sort=alpha&amp;mode=desc&amp;cat=' . $idcat, '-' . $idcat . '.php?sort=alpha&amp;mode=desc'),
		'U_WEB_ALPHA_BOTTOM' => url('.php?sort=alpha&amp;mode=asc&amp;cat=' . $idcat, '-' . $idcat . '.php?sort=alpha&amp;mode=asc'),
		'U_WEB_DATE_TOP' => url('.php?sort=date&amp;mode=desc&amp;cat=' . $idcat, '-' . $idcat . '.php?sort=date&amp;mode=desc'),
		'U_WEB_DATE_BOTTOM' => url('.php?sort=date&amp;mode=asc&amp;cat=' . $idcat, '-' . $idcat . '.php?sort=date&amp;mode=asc'),
		'U_WEB_VIEW_TOP' => url('.php?sort=view&amp;mode=desc&amp;cat=' . $idcat, '-' . $idcat . '.php?sort=view&amp;mode=desc'),
		'U_WEB_VIEW_BOTTOM' => url('.php?sort=view&amp;mode=asc&amp;cat=' . $idcat, '-' . $idcat . '.php?sort=view&amp;mode=asc'),
		'U_WEB_NOTE_TOP' => url('.php?sort=note&amp;mode=desc&amp;cat=' . $idcat, '-' . $idcat . '.php?sort=note&amp;mode=desc'),
		'U_WEB_NOTE_BOTTOM' => url('.php?sort=note&amp;mode=asc&amp;cat=' . $idcat, '-' . $idcat . '.php?sort=note&amp;mode=asc'),
		'U_WEB_COM_TOP' => url('.php?sort=com&amp;mode=desc&amp;cat=' . $idcat, '-' . $idcat . '.php?sort=com&amp;mode=desc'),
		'U_WEB_COM_BOTTOM' => url('.php?sort=com&amp;mode=asc&amp;cat=' . $idcat, '-' . $idcat . '.php?sort=com&amp;mode=asc')
	));		
	
	$get_sort = retrieve(GET, 'sort', '');	
	switch ($get_sort)
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
		$sort = 'average_notes';
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
	 
	$Pagination = new DeprecatedPagination();
		
	$tpl->put_all(array(
		'PAGINATION' => $Pagination->display('web' . url('.php' . (!empty($unget) ? $unget . '&amp;' : '?') . 'cat=' . $idcat . '&amp;p=%d', '-' . $idcat . '-0-%d.php' . (!empty($unget) ? '?' . $unget : '')), $nbr_web, 'p', $CONFIG_WEB['nbr_web_max'], 3)
	));

	$result = $Sql->query_while("SELECT w.id, w.title, w.timestamp, w.compt, notes.average_notes
	FROM " . PREFIX . "web w
	LEFT JOIN " . DB_TABLE_AVERAGE_NOTES . " notes ON w.id = notes.id_in_module
	WHERE aprob = 1 AND idcat = '" . $idcat . "'
	ORDER BY " . $sort . " " . $mode . 
	$Sql->limit($Pagination->get_first_msg($CONFIG_WEB['nbr_web_max'], 'p'), $CONFIG_WEB['nbr_web_max']), __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$notation->set_id_in_module($row['id']);
		$comments->set_id_in_module($row['id']);
		
		//On reccourci le lien si il est trop long.
		$row['title'] = (strlen($row['title']) > 45 ) ? substr(html_entity_decode($row['title']), 0, 45) . '...' : $row['title'];
		
		$tpl->assign_block_vars('web', array(			
			'NAME' => $row['title'],
			'CAT' => $CAT_WEB[$idcat]['name'],
			'DATE' => gmdate_format('date_format_short', $row['timestamp']),
			'COMPT' => $row['compt'],
			'NOTE' => NotationService::display_static_image($notation),
			'COM' => CommentsService::get_number_comments($comments),
			'U_WEB_LINK' => url('.php?cat=' . $idcat . '&amp;id=' . $row['id'], '-' .  $idcat . '-' . $row['id'] . '.php')
		));
	}
	$Sql->query_close($result);
}
else
{
	$total_link = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "web_cat wc
	LEFT JOIN " . PREFIX . "web w ON w.idcat = wc.id
	WHERE w.aprob = 1 AND wc.aprob = 1 AND wc.secure <= '" . $User->get_attribute('level') . "'", __LINE__, __FILE__);
	$total_cat = $Sql->query("SELECT COUNT(*) as compt FROM " . PREFIX . "web_cat WHERE aprob = 1 AND secure <= '" . $User->get_attribute('level') . "'", __LINE__, __FILE__);
	
	//On crée une pagination si le nombre de catégories est trop important.
	 
	$Pagination = new DeprecatedPagination();

	$CONFIG_WEB['nbr_column'] = ($total_cat > $CONFIG_WEB['nbr_column']) ? $CONFIG_WEB['nbr_column'] : $total_cat;
	$CONFIG_WEB['nbr_column'] = !empty($CONFIG_WEB['nbr_column']) ? $CONFIG_WEB['nbr_column'] : 1;
	
	$tpl->put_all(array(
		'C_WEB_CAT' => true,
		'C_IS_ADMIN' => $User->check_level(ADMIN_LEVEL),
		'PAGINATION' => $Pagination->display('web' . url('.php?p=%d', '-0-0-%d.php'), $total_cat, 'p', $CONFIG_WEB['nbr_cat_max'], 3),
		'TOTAL_FILE' => $total_link,
		'L_CATEGORIES' => $LANG['categories'],
		'L_PROPOSE_LINK' => $LANG['propose_link'],
		'L_HOW_LINK' => $LANG['how_link'],
		'U_WEB_ADD' => url('.php?web=true')
	));
	
	//Catégorie disponibles	
	$column_width = floor(100/$CONFIG_WEB['nbr_column']);
	$result = $Sql->query_while(
	"SELECT aw.id, aw.name, aw.contents, aw.icon, COUNT(w.id) as count
	FROM " . PREFIX . "web_cat aw
	LEFT JOIN " . PREFIX . "web w ON w.idcat = aw.id AND w.aprob = 1
	WHERE aw.aprob = 1 AND aw.secure <= '" . $User->get_attribute('level') . "'
	GROUP BY aw.id
	ORDER BY aw.class
	" . $Sql->limit($Pagination->get_first_msg($CONFIG_WEB['nbr_cat_max'], 'p'), $CONFIG_WEB['nbr_cat_max']), __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$tpl->assign_block_vars('cat_list', array(
			'WIDTH' => $column_width,
			'TOTAL' => $row['count'],
			'CAT' => $row['name'],
			'CONTENTS' => $row['contents'],	
			'U_IMG_CAT' => !empty($row['icon']) ? '<a href="../web/web' . url('.php?cat=' . $row['id'], '-' . $row['id'] . '.php') . '"><img src="' . $row['icon'] . '" alt="" /></a><br />' : '',
			'U_WEB_CAT' => url('.php?cat=' . $row['id'], '-' . $row['id'] . '.php')
		));
	}
	$Sql->query_close($result);
}
$tpl->display();

require_once('../kernel/footer.php'); 

?>