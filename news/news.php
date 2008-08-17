<?php
/*##################################################
 *                                news.php
 *                            -------------------
 *   begin              : June 20, 2005
 *   copyright          : (C) 2005 Viarre Régis
 *   email              : crowkait@phpboost.com
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
require_once('../news/news_begin.php');
require_once('../kernel/header.php');

$idnews = retrieve(GET, 'id', 0);	
$idcat = retrieve(GET, 'cat', 0);
$show_archive = retrieve(GET, 'arch', false);

$is_admin = $Member->Check_level(ADMIN_LEVEL);
if( empty($idnews) && empty($idcat) ) // Accueil du module de news
{
	$tpl_news = new Template('news/news.tpl');

	if( $CONFIG_NEWS['activ_edito'] == 1 ) //Affichage de l'édito
	{
		$tpl_news->Assign_vars( array(
			'C_NEWS_EDITO' => true,
			'CONTENTS' => second_parse($CONFIG_NEWS['edito']),
			'TITLE' => $CONFIG_NEWS['edito_title'],
			'EDIT' => $is_admin ? '<a href="../news/admin_news_config.php" title="' . $LANG['edit'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/edit.png" class="valign_middle" /></a>&nbsp;' : ''
		));
	}	

	//On crée une pagination (si activé) si le nombre de news est trop important.
	include_once('../kernel/framework/util/pagination.class.php'); 
	$Pagination = new Pagination();
		
	//Pagination activée, sinon affichage lien vers les archives.
	if( $CONFIG_NEWS['activ_pagin'] == '1' )
	{
		$show_pagin = $Pagination->Display_pagination('news' . transid('.php?p=%d', '-0-0-%d.php'), $CONFIG_NEWS['nbr_news'], 'p', $CONFIG_NEWS['pagination_news'], 3);
		$first_msg = $Pagination->First_msg($CONFIG_NEWS['pagination_news'], 'p'); 
	}
	elseif( $show_archive ) //Pagination des archives.
	{
		$show_pagin = $Pagination->Display_pagination('news' . transid('.php?arch=1&amp;p=%d', '-0-0-%d.php?arch=1'), $CONFIG_NEWS['nbr_news'] - $CONFIG_NEWS['pagination_news'], 'p', $CONFIG_NEWS['pagination_arch'], 3);
		$first_msg = $CONFIG_NEWS['pagination_news'] + $Pagination->First_msg($CONFIG_NEWS['pagination_arch'], 'p'); 
		$CONFIG_NEWS['pagination_news'] = $CONFIG_NEWS['pagination_arch'];
	}
	else //Affichage du lien vers les archives.
	{
		$show_pagin = (($CONFIG_NEWS['nbr_news'] > $CONFIG_NEWS['pagination_news']) && ($CONFIG_NEWS['nbr_news'] != 0)) ? '<a href="news.php?arch=1" title="' . $LANG['display_archive'] . '">' . $LANG['display_archive'] . '</a>' : '';
		$first_msg = 0;
	}
		
	$tpl_news->Assign_vars(array(
	    'L_SYNDICATION' => $LANG['syndication'],
		'PAGINATION' => $show_pagin,
		'L_ALERT_DELETE_NEWS' => $LANG['alert_delete_news'],
		'L_LAST_NEWS' => !$show_archive ? $LANG['last_news'] : $LANG['archive'],
        'PATH_TO_ROOT' => PATH_TO_ROOT,
        'THEME' => $CONFIG['theme'],
	    'FEED_MENU' => get_feed_menu(FEED_URL)
	));
	
	//Si les news en block sont activées on recupère la page.
	if( $CONFIG_NEWS['type'] == 1 && !$show_archive )
	{		
		$tpl_news->Assign_vars(array(
			'C_NEWS_BLOCK' => true
		));
		
		$column = ($CONFIG_NEWS['nbr_column'] > 1) ? true : false;
		if( $column )
		{
			$i = 0;
			$CONFIG_NEWS['nbr_column'] = ceil($CONFIG_NEWS['pagination_news']/$CONFIG_NEWS['nbr_column']);
			$CONFIG_NEWS['nbr_column'] = !empty($CONFIG_NEWS['nbr_column']) ? $CONFIG_NEWS['nbr_column'] : 1;
			$column_width = floor(100/$CONFIG_NEWS['nbr_column']);	
			
			$tpl_news->Assign_vars(array(
				'START_TABLE_NEWS' => '<table style="margin:auto;width:98%"><tr><td style="vertical-align:top;width:' . $column_width . '%">',
				'END_TABLE_NEWS' => '</td></tr></table>'
			));	
		}
		else
			$new_row = '';
		
		$z = 0;
		list($admin, $del) = array('', ''); 			
		$result = $Sql->Query_while("SELECT n.contents, n.extend_contents, n.title, n.id, n.timestamp, n.user_id, n.img, n.alt, n.nbr_com, nc.id AS idcat, nc.icon, m.login
		FROM ".PREFIX."news n
		LEFT JOIN ".PREFIX."news_cat nc ON nc.id = n.idcat
		LEFT JOIN ".PREFIX."member m ON m.user_id = n.user_id		
		WHERE '" . time() . "' >= n.start AND ('" . time() . "' <= n.end OR n.end = 0) AND n.visible = 1
		ORDER BY n.timestamp DESC 
		" . $Sql->Sql_limit($first_msg, $CONFIG_NEWS['pagination_news']), __LINE__, __FILE__);
		while($row = $Sql->Sql_fetch_assoc($result) )
		{
			if( $is_admin )
			{
				$admin = '&nbsp;&nbsp;<a href="../news/admin_news.php?id=' . $row['id'] . '" title="' . $LANG['edit'] . '"><img class="valign_middle" src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/edit.png" /></a>';
				$del = '&nbsp;&nbsp;<a href="../news/admin_news.php?delete=1&amp;id=' . $row['id'] . '" title="' . $LANG['delete'] . '" onClick="javascript:return Confirm();"><img class="valign_middle" src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/delete.png" /></a>';
			}
			
			//Séparation des news en colonnes si activé.
			if( $column )
			{	
				$new_row = (($i%$CONFIG_NEWS['nbr_column']) == 0 && $i > 0) ? '</ul></td><td style="vertical-align:top;width:' . $column_width . '%"><ul style="margin:0;padding:0;list-style-type:none;">' : '';	
				$i++;
			}
				
			$tpl_news->Assign_block_vars('news', array(
				'ID' => $row['id'],
				'ICON' => ((!empty($row['icon']) && $CONFIG_NEWS['activ_icon'] == 1) ? '<a href="news' . transid('.php?cat=' . $row['idcat'], '-' . $row['idcat'] . '.php') . '"><img src="' . $row['icon'] . '" alt="" class="valign_middle" /></a>' : ''),
				'TITLE' => $row['title'],
				'CONTENTS' => second_parse($row['contents']),
				'EXTEND_CONTENTS' => (!empty($row['extend_contents']) ? '<a style="font-size:10px" href="news' . transid('.php?id=' . $row['id'], '-0-' . $row['id'] . '.php') . '">[' . $LANG['extend_contents'] . ']</a><br /><br />' : ''),
				'IMG' => (!empty($row['img']) ? '<img src="' . $row['img'] . '" alt="' . $row['alt'] . '" title="' . $row['alt'] . '" class="img_right" />' : ''),
				'PSEUDO' => $CONFIG_NEWS['display_author'] ? $row['login'] : '',				
				'DATE' => $CONFIG_NEWS['display_date'] ? $LANG['on'] . ': ' . gmdate_format('date_format_short', $row['timestamp']) : '',
				'COM' => ($CONFIG_NEWS['activ_com'] == 1) ? com_display_link($row['nbr_com'], '../news/news' . transid('.php?cat=0&amp;id=' . $row['id'] . '&amp;com=0', '-0-' . $row['id'] . '+' . url_encode_rewrite($row['title']) . '.php?com=0'), $row['id'], 'news') : '',
				'EDIT' => $admin,
				'DEL' => $del,
				'NEW_ROW' => $new_row,
				'U_MEMBER_ID' => transid('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php'),
				'U_NEWS_LINK' => transid('.php?id=' . $row['id'], '-0-' . $row['id'] . '+' . url_encode_rewrite($row['title']) . '.php'),
                'FEED_MENU' => get_feed_menu(FEED_URL)
			));
			$z++;
		}
		$Sql->Close($result);	
		
		if( $z == 0 )
		{
			$tpl_news->Assign_vars( array(
				'C_NEWS_NO_AVAILABLE' => true,
				'L_NO_NEWS_AVAILABLE' => $LANG['no_news_available']
			));
		}
	}
	else //News en liste
	{
		$column = ($CONFIG_NEWS['nbr_column'] > 1) ? true : false;
		if( $column )
		{
			$i = 0;
			$CONFIG_NEWS['nbr_column'] = ceil($CONFIG_NEWS['pagination_news']/$CONFIG_NEWS['nbr_column']);
			$CONFIG_NEWS['nbr_column'] = !empty($CONFIG_NEWS['nbr_column']) ? $CONFIG_NEWS['nbr_column'] : 1;
			$column_width = floor(100/$CONFIG_NEWS['nbr_column']);	
			
			$tpl_news->Assign_vars(array(
				'C_NEWS_LINK' => true,
				'START_TABLE_NEWS' => '<table style="margin:auto;width:98%"><tr><td style="vertical-align:top;width:' . $column_width . '%"><ul style="margin:0;padding:0;list-style-type:none;">',
				'END_TABLE_NEWS' => '</ul></td></tr></table>'
			));	
		}
		else
		{	
			$tpl_news->Assign_vars(array(
				'C_NEWS_LINK' => true,
				'START_TABLE_NEWS' => '<ul style="margin:0;padding:0;list-style-type:none;">',
				'END_TABLE_NEWS' => '</ul>'
			));
			$new_row = '';
		}
		
		$result = $Sql->Query_while("SELECT n.id, n.title, n.timestamp, nc.id AS idcat, nc.icon
		FROM ".PREFIX."news n
		LEFT JOIN ".PREFIX."news_cat nc ON nc.id = n.idcat
		WHERE n.visible = 1
		ORDER BY n.timestamp DESC 
		" . $Sql->Sql_limit($first_msg, $CONFIG_NEWS['pagination_news']), __LINE__, __FILE__);
		while ($row = $Sql->Sql_fetch_assoc($result))
		{ 
			//Séparation des news en colonnes si activé.
			if( $column )
			{	
				$new_row = (($i%$CONFIG_NEWS['nbr_column']) == 0 && $i > 0) ? '</ul></td><td style="vertical-align:top;width:' . $column_width . '%"><ul style="margin:0;padding:0;list-style-type:none;">' : '';	
				$i++;
			}
			
			$tpl_news->Assign_block_vars('list', array(
				'ICON' => ((!empty($row['icon']) && $CONFIG_NEWS['activ_icon'] == 1) ? '<a href="news' . transid('.php?cat=' . $row['idcat'], '-' . $row['idcat'] . '.php') . '"><img class="valign_middle" src="' . $row['icon'] . '" alt="" /></a>' : ''),
				'DATE' => gmdate_format('date_format_tiny', $row['timestamp']),
				'TITLE' => $row['title'],
				'NEW_ROW' => $new_row, 
				'U_NEWS' => 'news' . transid('.php?id=' . $row['id'], '-0-' . $row['id'] . '+' . url_encode_rewrite($row['title']) . '.php')
			));
		}
		$Sql->Close($result);
	}
}
elseif( !empty($idnews) ) //On affiche la news correspondant à l'id envoyé.
{
	if( empty($news['id']) )
		$Errorh->Error_handler('e_unexist_news', E_USER_REDIRECT);

	$tpl_news = new Template('news/news.tpl');
	
	//Initialisation
	list($admin, $del) = array('', '');
	if( $is_admin )
	{
		$admin = '&nbsp;&nbsp;<a href="../news/admin_news.php?id=' . $news['id'] . '" title="' . $LANG['edit'] . '"><img class="valign_middle" src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/edit.png" /></a>';
		$del = '&nbsp;&nbsp;<a href="../news/admin_news.php?delete=1&amp;id=' . $news['id'] . '" title="' . $LANG['delete'] . '" onClick="javascript:return Confirm();"><img class="valign_middle" src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/delete.png" /></a>';
	}

	$next_news = $Sql->Query_array("news", "title", "id", "WHERE visible = 1 AND id > '" . $idnews . "' " . $Sql->sql_limit(0, 1), __LINE__, __FILE__);
	$previous_news = $Sql->Query_array("news", "title", "id", "WHERE visible = 1 AND id < '" . $idnews . "' ORDER BY id DESC " . $Sql->sql_limit(0, 1), __LINE__, __FILE__);

	$tpl_news->Assign_vars(array(
	    'L_SYNDICATION' => $LANG['syndication'],
		'C_NEWS_BLOCK' => true,
		'C_NEWS_NAVIGATION_LINKS' => true,
		'L_ALERT_DELETE_NEWS' => $LANG['alert_delete_news'],
		'L_ON' => $LANG['on'],
		'U_PREVIOUS_NEWS' => !empty($previous_news['id']) ? '<img src="../templates/' . $CONFIG['theme'] . '/images/left.png" alt="" class="valign_middle" /> <a href="news' . transid('.php?id=' . $previous_news['id'], '-0-' . $previous_news['id'] . '+' . url_encode_rewrite($previous_news['title']) . '.php') . '">' . $previous_news['title'] . '</a>' : '',
		'U_NEXT_NEWS' => !empty($next_news['id']) ? '<a href="news' . transid('.php?id=' . $next_news['id'], '-0-' . $next_news['id'] . '+' . url_encode_rewrite($next_news['title']) . '.php') . '">' . $next_news['title'] . '</a> <img src="../templates/' . $CONFIG['theme'] . '/images/right.png" alt="" class="valign_middle" />' : '',
        'PATH_TO_ROOT' => PATH_TO_ROOT,
        'THEME' => $CONFIG['theme']
	));
	
	$tpl_news->Assign_block_vars('news', array(
		'ID' => $news['id'],
		'ICON' => ((!empty($news['icon']) && $CONFIG_NEWS['activ_icon'] == 1) ? '<a href="news.php?cat=' . $news['idcat'] . '"><img class="valign_middle" src="' . $news['icon'] . '" alt="" /></a>' : ''),
		'TITLE' => $news['title'],
		'CONTENTS' => second_parse($news['contents']),
		'EXTEND_CONTENTS' => second_parse($news['extend_contents']) . '<br /><br />',
		'IMG' => (!empty($news['img']) ? '<img src="' . $news['img'] . '" alt="' . $news['alt'] . '" title="' . $news['alt'] . '" class="img_right" />' : ''),
		'PSEUDO' => $news['login'],
		'DATE' => gmdate_format('date_format_short', $news['timestamp']),
		'COM' => ($CONFIG_NEWS['activ_com'] == 1) ? com_display_link($news['nbr_com'], '../news/news' . transid('.php?cat=0&amp;id=' . $idnews . '&amp;com=0', '-0-' . $idnews . '+' . url_encode_rewrite($news['title']) . '.php?com=0'), $idnews, 'news') : '',
		'EDIT' => $admin,
		'DEL' => $del,
		'U_MEMBER_ID' => transid('.php?id=' . $news['user_id'], '-' . $news['user_id'] . '.php'),
	    'FEED_MENU' => get_feed_menu(FEED_URL)
	));	
}
elseif( !empty($idcat) )
{
	$tpl_news = new Template('news/news_cat.tpl');
	
	$cat = $Sql->Query_array('news_cat', 'id', 'name', 'icon', "WHERE id = '" . $idcat . "'", __LINE__, __FILE__);
	if( empty($cat['id']) )
		$Errorh->Error_handler('error_unexist_cat', E_USER_REDIRECT);
	
	$tpl_news->Assign_vars(array(
		'C_NEWS_LINK' => true,
		'CAT_NAME' => $cat['name'],
		'EDIT' => ($is_admin) ? '&nbsp;&nbsp;<a href="admin_news_cat.php?id=' . $cat['id'] . '" title="' . $LANG['edit'] . '"><img class="valign_middle" src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/edit.png" /></a>' : '',
		'L_CATEGORY' => $LANG['category']
	));
		
	$result = $Sql->Query_while("SELECT n.id, n.title, n.nbr_com, nc.id AS idcat, nc.icon
	FROM ".PREFIX."news n
	LEFT JOIN ".PREFIX."news_cat nc ON nc.id = n.idcat
	WHERE n.visible = 1 AND n.idcat = '" . $idcat . "'
	ORDER BY n.timestamp DESC", __LINE__, __FILE__);
	while ($row = $Sql->Sql_fetch_assoc($result))
	{ 
		$tpl_news->Assign_block_vars('list', array(
			'ICON' => ((!empty($row['icon']) && $CONFIG_NEWS['activ_icon'] == 1) ? '<a href="news' . transid('.php?cat=' . $row['idcat'], '-' . $row['idcat'] . '.php') . '"><img class="valign_middle" src="' . $row['icon'] . '" alt="" /></a>' : ''),
			'TITLE' => $row['title'],
			'COM' => $row['nbr_com'],
			'U_NEWS' => 'news' . transid('.php?id=' . $row['id'], '-0-' . $row['id'] . '+'  . url_encode_rewrite($row['title']) . '.php')
		));
	}
}
	
//Affichage commentaires.
if( isset($_GET['com']) && $idnews > 0 )
{
	$tpl_news->Assign_vars(array(
		'COMMENTS' => display_comments('news', $idnews, transid('news.php?id=' . $idnews . '&amp;com=%s', 'news-0-' . $idnews . '.php?com=%s'))
	));
}

$tpl_news->parse();

require_once('../kernel/footer.php');

?>