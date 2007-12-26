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

include_once('../includes/begin.php'); 
include_once('../web/lang/' . $CONFIG['lang'] . '/web_' . $CONFIG['lang'] . '.php'); //Chargement de la langue du module.

$get_note =  !empty($_GET['note']) ? numeric($_GET['note']) : 0;
$idweb = !empty($_GET['id']) ? numeric($_GET['id']) : 0;
$idcat = !empty($_GET['cat']) ? numeric($_GET['cat']) : 0;

$cache->load_file('web'); //$CAT_WEB et $CONFIG_WEB en global.

$CAT_WEB[$idcat]['name'] = !empty($CAT_WEB[$idcat]['name']) ? $CAT_WEB[$idcat]['name'] : '';
$web['title'] = '';
if( !empty($idweb) && !empty($idcat) )
{ 
	$web = $sql->query_array('web' , '*', "WHERE aprob = 1 AND id = '" . $idweb . "' AND idcat = '" . $idcat . "'", __LINE__, __FILE__);
	define('TITLE', $LANG['title_web'] . ' - ' . addslashes($web['title']));
}
elseif( !empty($idcat) )
	define('TITLE', $LANG['title_web'] . ' - ' . addslashes($CAT_WEB[$idcat]['name']));
else
	define('TITLE', $LANG['title_web']);
	
$l_com_note = !empty($get_note) ? $LANG['note'] : (!empty($_GET['i']) ? $LANG['com'] : '');
$speed_bar = array(
	$LANG['title_web'] => transid('web.php'),
	$CAT_WEB[$idcat]['name'] => empty($idweb) ? '' : transid('web.php?cat=' . $idcat, 'web-' . $idcat . '.php'),
	$web['title'] => (!empty($get_note) || !empty($_GET['i'])) ? transid('web.php?cat=' . $idcat . '&amp;id=' . $idweb, 'web-' . $idcat . '-' . $idweb . '.php') : '',
	$l_com_note => ''
);
include_once('../includes/header.php'); 

if( !$groups->check_auth($SECURE_MODULE['web'], ACCESS_MODULE) )
{
	$errorh->error_handler('e_auth', E_USER_REDIRECT); 
	exit;
}

if( !empty($idweb) && !empty($CAT_WEB[$idcat]['name']) && !empty($idcat) ) //Contenu du lien.
{
	$template->set_filenames(array('web' => '../templates/' . $CONFIG['theme'] . '/web/web.tpl'));
	
	if( !$session->check_auth($session->data, $CAT_WEB[$idcat]['secure']) )
	{
		$errorh->error_handler('e_auth', E_USER_REDIRECT); 
		exit;
	}
	if( empty($web['id']) )
	{
		$errorh->error_handler('e_unexist_link_web', E_USER_REDIRECT); 
		exit;
	}	
	if( $session->data['level'] === 2 )
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

	$template->assign_vars(array(
		'JAVA' => $java,
		'EDIT' => $edit,
		'DEL' => $del
	));
		
	//Notation
	if( $session->data['user_id'] !== -1 ) //Utilisateur connecté
		$link_note = '<a class="com" style="font-size:10px;" href="web' . transid('.php?note=' . $web['id'] . '&amp;id=' . $idweb . '&amp;cat=' . $idcat, '-' . $idcat . '-' . $idweb . '-0-0-' . $web['id'] . '.php?note=' . $web['id']) . '#note" title="' . $LANG['note'] . '">' . $LANG['note'] . '</a>';
	else
		$link_note = $LANG['note'];
	
	$note = ($web['nbrnote'] > 0 ) ? $web['note'] : '<em>' . $LANG['no_note'] . '</em>';

	//Commentaires
	$link_pop = "<a class=\"com\" href=\"#\" onclick=\"popup('" . HOST . DIR . transid("/includes/com.php?i=" . $idweb . "web") . "', 'web');\">";
	$link_current = '<a class="com" href="' . HOST . DIR . '/web/web' . transid('.php?cat=' . $idcat . '&amp;id=' . $idweb . '&amp;i=0', '-' . $idcat . '-' . $idweb . '.php?i=0') . '#web">';	
	$link = ($CONFIG['com_popup'] == '0') ? $link_current : $link_pop;
	
	$com_true = ($web['nbr_com'] > 1) ? $LANG['com_s'] : $LANG['com'];
	$com_false = $LANG['post_com'] . '</a>';
	$l_com = !empty($web['nbr_com']) ? $com_true . '</a>: ' . $web['nbr_com'] : $com_false;
	
	$template->assign_block_vars('web', array(
		'MODULE_DATA_PATH' => $template->module_data_path('web'),
		'IDWEB' => $web['id'],		
		'NAME' => $web['title'],
		'CONTENTS' => $web['contents'],
		'URL' => $web['url'],
		'CAT' => $CAT_WEB[$idcat]['name'],
		'DATE' => date($LANG['date_format'], $web['timestamp']),
		'COMPT' => $web['compt'],
		'THEME' => $CONFIG['theme'],
		'LANG' => $CONFIG['lang'],
		'COM' => $link . $l_com,
		'NOTE' => $note,
		'U_WEB_CAT' => transid('.php?cat=' . $idcat, '-' . $idcat . '.php'),
		'L_NOTE' => $link_note,
		'L_DESC' => $LANG['description'],
		'L_CAT' => $LANG['category'],
		'L_DATE' => $LANG['date'],
		'L_TIMES' => $LANG['n_time'],
		'L_VIEWS' => $LANG['views']
	));

	//Affichage et gestion de la notation
	if( !empty($get_note) && !empty($CAT_WEB[$idcat]['name']) )
	{
		$template->assign_vars(array(
			'L_ACTUAL_NOTE' => $LANG['actual_note'],
			'L_VOTE' => $LANG['vote'],
			'L_NOTE' => $LANG['note']
		));
				
		if( $session->check_auth($session->data, 0) ) //Utilisateur connecté.
		{
			if( !empty($_POST['valid_note']) )
			{
				$note = numeric($_POST['note']);
				
				//Echelle de notation.
				$check_note = ( ($note >= 0) && ($note <= $CONFIG_WEB['note_max']) ) ? true : false;				
				$users_note = $sql->query("SELECT users_note FROM ".PREFIX."web WHERE idcat = '" . $idcat . "' AND id = '" . $get_note . "'", __LINE__, __FILE__);
				
				$array_users_note = explode('/', $users_note);
				if( !in_array($session->data['user_id'], $array_users_note) && !empty($session->data['user_id']) && ($check_note === true) )
				{
					$row_note = $sql->query_array('web', 'users_note', 'nbrnote', 'note', "WHERE id = '" . $get_note . "'", __LINE__, __FILE__);
					$note = ( ($row_note['note'] * $row_note['nbrnote']) + $note ) / ($row_note['nbrnote'] + 1);
					
					$row_note['nbrnote']++;
					
					$users_note = !empty($row_note['users_note']) ? $row_note['users_note'] . '/' . $session->data['user_id'] : $session->data['user_id']; //On ajoute l'id de l'utilisateur.
					
					$sql->query_inject("UPDATE ".PREFIX."web SET note = '" . $note . "', nbrnote = '" . $row_note['nbrnote'] . "', 
					users_note = '" . $users_note . "' WHERE id = '" . $get_note . "' AND idcat = '" . $idcat . "'", __LINE__, __FILE__);
					
					//Success.
					header('location:' . HOST . DIR . '/web/web' . transid('.php?cat=' . $idcat . '&id=' . $get_note, '-' . $idcat . '-' . $get_note . '.php', '&'));
					exit;
				}
				else
				{
					header('location:' . HOST . DIR . '/web/web' . transid('.php?cat=' . $idcat . '&id=' . $get_note, '-' . $idcat . '-' . $get_note . '.php', '&'));
					exit;
				}			
			}
			elseif( !empty($session->data['user_id']) )
			{
				$row = $sql->query_array('web', 'users_note', 'nbrnote', 'note', "WHERE idcat = '" . $idcat . "' AND id = '" . $get_note . "'", __LINE__, __FILE__);
				
				$array_users_note = explode('/', $row['users_note']);
				$select = '';
				if( in_array($session->data['user_id'], $array_users_note) ) //Déjà voté
					$select .= '<option value="-1">' . $LANG['already_vote'] . '</option>';
				else 
				{
					//Génération de l'échelle de notation.
					for( $i = -1; $i <= $CONFIG_WEB['note_max']; $i++)
					{
						if( $i == -1 )
							$select = '<option value="-1">' . $LANG['note'] . '</option>';
						else
							$select .= '<option value="' . $i . '">' . $i . '</option>';
					}
				}
				
				$template->assign_block_vars('note', array(
					'NOTE' => ($row['nbrnote'] > 0) ? $row['note'] : '<em>' . $LANG['no_note'] . '</em>',
					'SELECT' => $select,
					'U_WEB_ACTION_NOTE' => transid('.php?note=' . $get_note . '&amp;id=' . $get_note . '&amp;cat=' . $idcat, '-' . $idcat . '-' . $get_note . '.php?note=' . $get_note)
				));
			}	
			else
			{
				$errorh->error_handler('e_auth', E_USER_REDIRECT); 
				exit;
			}
		}
		else 
		{
			$errorh->error_handler('e_auth', E_USER_REDIRECT); 
			exit;
		}
	}
	
	//Affichage commentaires.
	if( isset($_GET['i']) )
	{
		$_com_vars = 'web.php?cat=' . $idcat . '&amp;id=' . $idweb . '&amp;i=%d';
		$_com_vars_e = 'web.php?cat=' . $idcat . '&id=' . $idweb . '&i=1';
		$_com_vars_r = 'web-' . $idcat . '-' . $idweb . '.php?i=%d%s';
		$_com_idprov = $idweb;
		$_com_script = 'web';
		include_once('../includes/com.php');
		$template->assign_var_from_handle('HANDLE_COM', 'com');
	}	

	$template->pparse('web');
}
elseif( !empty($idcat) && empty($idweb) ) //Catégories.
{
	$template->set_filenames(array('web' => '../templates/' . $CONFIG['theme'] . '/web/web.tpl'));
	
	if( !$session->check_auth($session->data, $CAT_WEB[$idcat]['secure']) )
	{
		$errorh->error_handler('e_auth', E_USER_REDIRECT); 
		exit;
	}
	
	$nbr_web = $sql->query("SELECT COUNT(*) as compt 
	FROM ".PREFIX."web 
	WHERE aprob = 1 AND idcat = '" . $idcat . "'", __LINE__, __FILE__);
	
	$template->assign_block_vars('link', array(
		'CAT_NAME' => $CAT_WEB[$idcat]['name'],		
		'NO_CAT' => ($nbr_web == 0) ? $LANG['none_link'] : ''
	));	
	
	$template->assign_vars(array(
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
	
	$get_sort = !empty($_GET['sort']) ? trim($_GET['sort']) : '';	
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
	
	$get_mode = !empty($_GET['mode']) ? trim($_GET['mode']) : '';	
	$mode = ($get_mode == 'asc' || $get_mode == 'desc') ? strtoupper(trim($_GET['mode'])) : 'DESC';	
	$unget = (!empty($get_sort) && !empty($mode)) ? '?sort=' . $get_sort . '&amp;mode=' . $get_mode : '';

	//On crée une pagination si le nombre de lien est trop important.
	include_once('../includes/pagination.class.php'); 
	$pagination = new Pagination();
		
	$template->assign_vars(array(
		'PAGINATION' => $pagination->show_pagin('web' . transid('.php' . (!empty($unget) ? $unget . '&amp;' : '?') . 'cat=' . $idcat . '&amp;p=%d', '-' . $idcat . '-0-%d.php' . (!empty($unget) ? '?' . $unget : '')), $nbr_web, 'p', $CONFIG_WEB['nbr_web_max'], 3)
	));

	$result = $sql->query_while("SELECT id, title, timestamp, compt, note, nbrnote, nbr_com
	FROM ".PREFIX."web
	WHERE aprob = 1 AND idcat = '" . $idcat . "'
	ORDER BY " . $sort . " " . $mode . 
	$sql->sql_limit($pagination->first_msg($CONFIG_WEB['nbr_web_max'], 'p'), $CONFIG_WEB['nbr_web_max']), __LINE__, __FILE__);
	while( $row = $sql->sql_fetch_assoc($result) )
	{
		//On reccourci le lien si il est trop long.
		$row['title'] = (strlen($row['title']) > 45 ) ? substr(html_entity_decode($row['title']), 0, 45) . '...' : $row['title'];
		
		//Commentaires
		$link_pop = "<a class=\"com\" href=\"#\" onclick=\"popup('" . HOST . DIR . transid("/includes/com.php?i=" . $row['id'] . "web") . "', 'web');\">";
		$link_current = '<a class="com" href="' . HOST . DIR . '/web/web' . transid('.php?cat=' . $idcat . '&amp;id=' . $row['id'] . '&amp;i=0', '-' . $idcat . '-' . $row['id'] . '.php?i=0') . '#web">';	
		$link = ($CONFIG['com_popup'] == '0') ? $link_current : $link_pop;
	
	
		$template->assign_block_vars('link.web', array(			
			'NAME' => $row['title'],
			'CAT' => $CAT_WEB[$idcat]['name'],
			'DATE' => date($LANG['date_format'], $row['timestamp']),
			'COMPT' => $row['compt'],
			'NOTE' => ($row['nbrnote'] > 0) ? $row['note'] . '/' . $CONFIG_WEB['note_max'] : '<em>' . $LANG['no_note'] . '</em>',
			'COM' => $link . $row['nbr_com'] . '</a>',
			'U_WEB_LINK' => transid('.php?cat=' . $idcat . '&amp;id=' . $row['id'], '-' .  $idcat . '-' . $row['id'] . '.php')
		));
	}
	$sql->close($result);
	
	$template->pparse('web');
}
else
{
	$template->set_filenames(array('web' => '../templates/' . $CONFIG['theme'] . '/web/web.tpl'));
	
	$total_link = $sql->query("SELECT COUNT(*) FROM ".PREFIX."web_cat AS wc
	LEFT JOIN ".PREFIX."web AS w ON w.idcat = wc.id
	WHERE w.aprob = 1 AND wc.aprob = 1 AND wc.secure <= '" . $session->data['level'] . "'", __LINE__, __FILE__);
	$total_cat = $sql->query("SELECT COUNT(*) as compt FROM ".PREFIX."web_cat WHERE aprob = 1 AND secure <= '" . $session->data['level'] . "'", __LINE__, __FILE__);
	
	$edit = '';
	if( $session->data['level'] === 2 )
		$edit = '&nbsp;&nbsp;<a href="admin_web_cat.php' .  SID . '" title=""><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/edit.png" class="valign_middle" /></a>';

	//On crée une pagination si le nombre de catégories est trop important.
	include_once('../includes/pagination.class.php'); 
	$pagination = new Pagination();

	$CONFIG_WEB['nbr_column'] = ($total_cat > $CONFIG_WEB['nbr_column']) ? $CONFIG_WEB['nbr_column'] : $total_cat;
	$CONFIG_WEB['nbr_column'] = !empty($CONFIG_WEB['nbr_column']) ? $CONFIG_WEB['nbr_column'] : 1;
	
	$template->assign_block_vars('cat', array(
		'PAGINATION' => $pagination->show_pagin('web' . transid('.php?p=%d', '-0-0-%d.php'), $total_cat, 'p', $CONFIG_WEB['nbr_cat_max'], 3),
		'EDIT' => $edit,
		'TOTAL_FILE' => $total_link,
		'L_CATEGORIES' => $LANG['categories'],
		'L_PROPOSE_LINK' => $LANG['propose_link'],
		'L_HOW_LINK' => $LANG['how_link'],
		'U_WEB_ADD' => transid('.php?web=true')
	));
	
	//Catégorie disponibles	
	$column_width = floor(100/$CONFIG_WEB['nbr_column']);
	$result = $sql->query_while(
	"SELECT aw.id, aw.name, aw.contents, aw.icon, COUNT(w.id) as count
	FROM ".PREFIX."web_cat AS aw
	LEFT JOIN ".PREFIX."web AS w ON w.idcat = aw.id AND w.aprob = 1
	WHERE aw.aprob = 1 AND aw.secure <= '" . $session->data['level'] . "'
	GROUP BY aw.id
	ORDER BY aw.class
	" . $sql->sql_limit($pagination->first_msg($CONFIG_WEB['nbr_cat_max'], 'p'), $CONFIG_WEB['nbr_cat_max']), __LINE__, __FILE__);
	while( $row = $sql->sql_fetch_assoc($result) )
	{
		$template->assign_block_vars('cat.web', array(
			'WIDTH' => $column_width,
			'TOTAL' => $row['count'],
			'CAT' => $row['name'],
			'CONTENTS' => $row['contents'],	
			'U_IMG_CAT' => !empty($row['icon']) ? '<a href="../web/web' . transid('.php?cat=' . $row['id'], '-' . $row['id'] . '.php') . '"><img src="' . $row['icon'] . '" alt="" /></a><br />' : '',
			'U_WEB_CAT' => transid('.php?cat=' . $row['id'], '-' . $row['id'] . '.php')
		));
	}
	$sql->close($result);
	
	$template->pparse('web');
}
			
include('../includes/footer.php'); 

?>