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
		
	if ($User->check_level(User::ADMIN_LEVEL))
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
		'COM' => '<a href="'. PATH_TO_ROOT .'/web/web' . url('.php?cat=' . $idcat . '&amp;id=' . $idweb . '&amp;com=0', '-' . $idcat . '-' . $idweb . '.php?com=0') .'">'. CommentsService::get_number_and_lang_comments('web', $web['id']) . '</a>',
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
		$comments_topic = new WebCommentsTopic();
		$comments_topic->set_id_in_module($idweb);
		$comments_topic->set_url(new Url('/web/web.php?cat='. $idcat .'&id=' . $web['id'] . '&com=0'));
		$tpl->put_all(array(
			'COMMENTS' => $comments_topic->display()->render()
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
		'C_IS_ADMIN' => $User->check_level(User::ADMIN_LEVEL),
		'CAT_NAME' => $CAT_WEB[$idcat]['name'],		
		'NO_CAT' => ($nbr_web == 0) ? $LANG['none_link'] : '',
		'MAX_NOTE' => $web_config->get_note_max(),
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
		'PAGINATION' => $Pagination->display('web' . url('.php' . (!empty($unget) ? $unget . '&amp;' : '?') . 'cat=' . $idcat . '&amp;p=%d', '-' . $idcat . '-0-%d.php' . (!empty($unget) ? '?' . $unget : '')), $nbr_web, 'p', $web_config->get_max_nbr_weblinks(), 3)
	));

	$result = $Sql->query_while("SELECT w.id, w.title, w.timestamp, w.compt, notes.average_notes
	FROM " . PREFIX . "web w
	LEFT JOIN " . DB_TABLE_AVERAGE_NOTES . " notes ON w.id = notes.id_in_module AND notes.module_name = 'web'
	WHERE aprob = 1 AND idcat = '" . $idcat . "'
	ORDER BY " . $sort . " " . $mode . 
	$Sql->limit($Pagination->get_first_msg($web_config->get_max_nbr_weblinks(), 'p'), $web_config->get_max_nbr_weblinks()), __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$notation->set_id_in_module($row['id']);
		
		//On reccourci le lien si il est trop long.
		$row['title'] = (strlen($row['title']) > 45 ) ? substr(html_entity_decode($row['title']), 0, 45) . '...' : $row['title'];
		
		$tpl->assign_block_vars('web', array(			
			'NAME' => $row['title'],
			'CAT' => $CAT_WEB[$idcat]['name'],
			'DATE' => gmdate_format('date_format_short', $row['timestamp']),
			'COMPT' => $row['compt'],
			'NOTE' => NotationService::display_static_image($notation, $row['average_notes']),
			'COM' => CommentsService::get_number_comments('web', $row['id']),
			'U_WEB_LINK' => url('.php?cat=' . $idcat . '&amp;id=' . $row['id'], '-' .  $idcat . '-' . $row['id'] . '.php')
		));
	}
	$Sql->query_close($result);
}
else
{
	$modulesLoader = AppContext::get_extension_provider_service();
	$module = $modulesLoader->get_provider('web');
	if ($module->has_extension_point(HomePageExtensionPoint::EXTENSION_POINT))
	{
		echo $module->get_extension_point(HomePageExtensionPoint::EXTENSION_POINT)->get_home_page()->get_view()->display();
	}
}
$tpl->display();

require_once('../kernel/footer.php'); 

?>