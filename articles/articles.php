<?php
/*##################################################
 *                                artciles.php
 *                            -------------------
 *   begin                : July 17, 2005
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
require_once('../articles/articles_begin.php');
require_once('../includes/header.php'); 

$page = !empty($_GET['p']) ? numeric($_GET['p']) : 1;
if( !empty($idart) && isset($_GET['cat']) )
{
	//Niveau d'autorisation de la catégorie
	if( !isset($CAT_ARTICLES[$idartcat]) || !$Member->Check_auth($CAT_ARTICLES[$idartcat]['auth'], READ_CAT_ARTICLES) || $CAT_ARTICLES[$idartcat]['aprob'] == 0 ) 
		$Errorh->Error_handler('e_auth', E_USER_REDIRECT); 
	if( empty($articles['id']) )
		$Errorh->Error_handler('e_unexist_articles', E_USER_REDIRECT); 
	
	$Template->Set_filenames(array('articles' => '../templates/' . $CONFIG['theme'] . '/articles/articles.tpl'));		
	
	//MAJ du compteur.
	$Sql->Query_inject("UPDATE " . LOW_PRIORITY . " ".PREFIX."articles SET views = views + 1 WHERE id = " . $idart, __LINE__, __FILE__); 
	
	if( $Member->Check_level(ADMIN_LEVEL) )
	{
		$java = '<script type="text/javascript">
		<!--
		function Confirm() {
		return confirm("' . $LANG['alert_delete_article'] . '");
		}
		-->
		</script>';
		
		$edit = '&nbsp;&nbsp;<a href="../articles/admin_articles' . transid('.php?id=' . $articles['id']) . '" title="'  . $LANG['edit'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/edit.png" class="valign_middle" alt="'  . $LANG['edit'] . '" /></a>';
		$del = '&nbsp;&nbsp;<a href="../articles/admin_articles.php?delete=1&amp;id=' . $articles['id'] . '" title="' . $LANG['delete'] . '" onClick="javascript:return Confirm();"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/delete.png" class="valign_middle" alt="' . $LANG['delete'] . '" /></a>';
		
		$Template->Assign_vars(array(
			'JAVA' => $java,
			'EDIT' => $edit,
			'DEL' => $del
		));
	}
	
	//Notation
	if( $Member->Get_attribute('user_id') !== -1 ) //Utilisateur connecté
		$link_note = '<a class="com" style="font-size:10px;" href="articles' . transid('.php?note=' . $idart . '&amp;id=' . $idart . '&amp;cat=' . $idartcat, '-' . $idartcat . '-' . $idart . '+' . url_encode_rewrite($articles['title']) . '.php?note=' . $idart) . '#note" title="' . $LANG['note'] . '">' . $LANG['note'] . '</a>';
	else
		$link_note = $LANG['note'];

	$note = ($articles['nbrnote'] > 0 ) ? $articles['note'] : '<em>' . $LANG['no_note'] . '</em>';
	
	//Commentaires
	$link_pop = "<a class=\"com\" href=\"#\" onclick=\"popup('" . HOST . DIR . transid("/includes/com.php?i=" . $idart . "articles") . "', 'articles');\">";
	$link_current = '<a class="com" href="' . HOST . DIR . '/articles/articles' . transid('.php?cat=' . $idartcat . '&amp;id=' . $idart . '&amp;i=0', '-' . $idartcat . '-' . $idart . '+' . url_encode_rewrite($articles['title']) . '.php?i=0') . '#articles">';	
	$link = ($CONFIG['com_popup'] == '0') ? $link_current : $link_pop;
	
	$l_com = ($articles['nbr_com'] > 1) ? $LANG['com_s'] : $LANG['com'];
	
	$com_true = $l_com . ' (' . $articles['nbr_com'] . ')</a>';
	$com_false = $LANG['post_com'] . '</a>';
	$com = (!empty($articles['nbr_com'])) ? $com_true : $com_false;

	//pagination des articles.
	$array_contents = explode('[page]', $articles['contents']);
	$nbr_page = count($array_contents);
	$nbr_page = !empty($nbr_page) ? $nbr_page : 1;
	
	//On crée une pagination si il y plus d'une page.
	include_once('../includes/pagination.class.php'); 
	$Pagination = new Pagination();
	
	$Template->Assign_vars(array(
		'C_DISPLAY_ARTICLE' => true,
		'IDART' => $articles['id'],
		'IDCAT' => $idartcat,
		'NAME' => $LANG['title_articles'] . ' - ' . $articles['title'],
		'PSEUDO' => $Sql->Query("SELECT login FROM ".PREFIX."member WHERE user_id = '" . $articles['user_id'] . "'", __LINE__, __FILE__),		
		'CONTENTS' => second_parse($array_contents[$page - 1]),
		'CAT' => $CAT_ARTICLES[$idartcat]['name'],
		'DATE' => gmdate_format('date_format_short', $articles['timestamp']),
		'PAGINATION_ARTICLES' => $Pagination->Display_pagination('articles' . transid('.php?cat=' . $idartcat . '&amp;id='. $idart . '&amp;p=%d', '-' . $idartcat . '-'. $idart . '-%d+' . url_encode_rewrite($articles['title']) . '.php'), $nbr_page, 'p', 1, 3),
		'COM' => $link . $com,
		'NOTE' => $note,
		'U_MEMBER_ID' => transid('.php?id=' . $articles['user_id'], '-' . $articles['user_id'] . '.php'),
		'L_NOTE' => $link_note,
		'L_WRITTEN' =>  $LANG['written_by'],
		'L_ON' => $LANG['on']	
	));

	//Affichage et gestion de la notation
	if( !empty($get_note) && !empty($CAT_ARTICLES[$idartcat]['name']) )
	{
		$Template->Assign_vars(array(
			'L_ACTUAL_NOTE' => $LANG['actual_note'],
			'L_VOTE' => $LANG['vote'],
			'L_NOTE' => $LANG['note']
		));
				
		if( $Member->Check_level(MEMBER_LEVEL) ) //Utilisateur connecté.
		{
			if( !empty($_POST['valid_note']) )
			{
				$note = !empty($_POST['note']) ? numeric($_POST['note']) : 0;
				
				//Echelle de notation.
				$check_note = (($note >= 0) && ($note <= $CONFIG_ARTICLES['note_max'])) ? true : false;				
				$users_note = $Sql->Query("SELECT users_note FROM ".PREFIX."articles WHERE idcat = " . $idartcat . " AND id = " . $get_note, __LINE__, __FILE__);
				
				$array_users_note = explode('/', $users_note);
				if( !in_array($Member->Get_attribute('user_id'), $array_users_note) && $Member->Get_attribute('user_id') != '' && $check_note === true )
				{
					$row_note = $Sql->Query_array('articles', 'users_note', 'nbrnote', 'note', "WHERE id = " . $get_note, __LINE__, __FILE__);
					$note = ( ($row_note['note'] * $row_note['nbrnote']) + $note ) / ($row_note['nbrnote'] + 1);
					
					$row_note['nbrnote']++;
					
					$users_note = !empty($row_note['users_note']) ? $row_note['users_note'] . '/' . $Member->Get_attribute('user_id') : $Member->Get_attribute('user_id'); //On ajoute l'id de l'utilisateur.
					
					$Sql->Query_inject("UPDATE ".PREFIX."articles SET note = " . $note . ", nbrnote = " . $row_note['nbrnote'] . ", 
					users_note = '" . $users_note . "' WHERE id = " . $get_note . " AND idcat = " . $idartcat, __LINE__, __FILE__);
					
					//Success.
					redirect(HOST . DIR . '/articles/articles' . transid('.php?cat=' . $idartcat . '&id=' . $get_note, '-' . $idartcat . '-' . $get_note . '.php', '&'));
				}
				else
					redirect(HOST . DIR . '/articles/articles' . transid('.php?cat=' . $idartcat . '&id=' . $get_note, '-' . $idartcat . '-' . $get_note . '.php', '&'));
			}
			else
			{
				$row = $Sql->Query_array('articles', 'users_note', 'nbrnote', 'note', "WHERE idcat = " . $idartcat . " AND id = " . $get_note, __LINE__, __FILE__);
				
				$array_users_note = explode('/', $row['users_note']);
				$select = '';
				if( in_array($Member->Get_attribute('user_id'), $array_users_note) ) //Déjà voté
					$select .= '<option value="-1">' . $LANG['already_vote'] . '</option>';
				else 
				{
					//Génération de l'échelle de notation.
					for( $i = -1; $i <= $CONFIG_ARTICLES['note_max']; $i++)
					{
						if( $i == -1 )
							$select = '<option value="-1">' . $LANG['note'] . '</option>';
						else
							$select .= '<option value="' . $i . '">' . $i . '</option>';
					}
				}
				$Template->Assign_vars(array(
					'C_DISPLAY_ARTICLE_NOTE' => true,
					'NOTE' => ($row['nbrnote'] > 0) ? $row['note'] : '<em>' . $LANG['no_note'] . '</em>',
					'SELECT' => $select,
					'U_ARTICLE_ACTION_NOTE' => transid('.php?note=' . $get_note . '&amp;id=' . $get_note . '&amp;cat=' . $idartcat, '-' . $idartcat . '-' . $get_note . '.php?note=' . $get_note)
				));
			}
		}
		else 
			$Errorh->Error_handler('e_auth', E_USER_REDIRECT); 
	}	
	
	//Affichage commentaires.
	if( isset($_GET['i']) )
	{
		$_com_vars = 'articles.php?cat=' . $idartcat . '&amp;id=' . $idart . '&amp;i=%d';
		$_com_vars_e = 'articles.php?cat=' . $idartcat . '&id=' . $idart . '&i=1';
		$_com_vars_r = 'articles-' . $idartcat . '-' . $idart . '.php?i=%d%s';
		$_com_idprov = $idart;
		$_com_script = 'articles';
		include_once('../includes/com.php');
	}	

	$Template->Pparse('articles');	
}
else
{
	$Template->Set_filenames(array(
		'articles_cat' => '../templates/' . $CONFIG['theme'] . '/articles/articles_cat.tpl'
	));	

	if( $idartcat > 0 )
	{
		if( !isset($CAT_ARTICLES[$idartcat]) || $CAT_ARTICLES[$idartcat]['aprob'] == 0 ) 
			$Errorh->Error_handler('e_auth', E_USER_REDIRECT); 

		$cat_links = '';
		foreach($CAT_ARTICLES as $id => $array_info_cat)
		{
			if( $CAT_ARTICLES[$idartcat]['id_left'] >= $array_info_cat['id_left'] && $CAT_ARTICLES[$idartcat]['id_right'] <= $array_info_cat['id_right'] && $array_info_cat['level'] <= $CAT_ARTICLES[$idartcat]['level'] )
				$cat_links .= ' <a href="articles' . transid('.php?cat=' . $id, '-' . $id . '.php') . '">' . $array_info_cat['name'] . '</a> &raquo;';
		}
		$clause_cat = " WHERE ac.id_left > '" . $CAT_ARTICLES[$idartcat]['id_left'] . "' AND ac.id_right < '" . $CAT_ARTICLES[$idartcat]['id_right'] . "' AND ac.level = '" . ($CAT_ARTICLES[$idartcat]['level'] + 1) . "' AND ac.aprob = 1";
	}
	else //Racine.
	{
		$cat_links = '';
		$clause_cat = " WHERE ac.level = '0' AND ac.aprob = 1";
	}

	//Niveau d'autorisation de la catégorie
	if( !$Member->Check_auth($CAT_ARTICLES[$idartcat]['auth'], READ_CAT_ARTICLES) ) 
		$Errorh->Error_handler('e_auth', E_USER_REDIRECT); 
	
	$nbr_articles = $Sql->Query("SELECT COUNT(*) FROM ".PREFIX."articles WHERE visible = 1 AND idcat = '" . $idartcat . "'", __LINE__, __FILE__);	
	$total_cat = $Sql->Query("SELECT COUNT(*) FROM ".PREFIX."articles_cats ac " . $clause_cat, __LINE__, __FILE__);	
		
	$rewrite_title = url_encode_rewrite($CAT_ARTICLES[$idartcat]['name']);
	
	//Colonnes des catégories.
	$nbr_column_cats = ($total_cat > $CONFIG_ARTICLES['nbr_column']) ? $CONFIG_ARTICLES['nbr_column'] : $total_cat;
	$nbr_column_cats = !empty($nbr_column_cats) ? $nbr_column_cats : 1;
	$column_width_cats = floor(100/$nbr_column_cats);
	
	$is_admin = $Member->Check_level(ADMIN_LEVEL) ? true : false;	
	$Template->Assign_vars(array(
		'COLUMN_WIDTH_CAT' => $column_width_cats,
		'ADD_ARTICLES' => $is_admin ? '<a href="admin_articles_add.php"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/add.png" alt="" class="valign_middle" /></a>' : '',
		'L_ARTICLES' => $LANG['articles'],
		'L_DATE' => $LANG['date'],
		'L_VIEW' => $LANG['views'],
		'L_NOTE' => $LANG['note'],
		'L_COM' => $LANG['com'],
		'L_TOTAL_ARTICLE' => ($nbr_articles > 0) ? sprintf($LANG['nbr_articles_info'], $nbr_articles) : '', 
		'L_NO_ARTICLES' => ($nbr_articles == 0) ? $LANG['none_article'] : '',
		'L_ARTICLES_INDEX' => $LANG['title_articles'],
		'L_CATEGORIES' => ($CAT_ARTICLES[$idartcat]['level'] >= 0) ? $LANG['sub_categories'] : $LANG['categories'],	
		'U_ARTICLES_CAT_LINKS' => $cat_links,
		'U_ARTICLES_ALPHA_TOP' => transid('.php?sort=alpha&amp;mode=desc&amp;cat=' . $idartcat, '-' . $idartcat . '+' . $rewrite_title . '.php?sort=alpha&amp;mode=desc'),
		'U_ARTICLES_ALPHA_BOTTOM' => transid('.php?sort=alpha&amp;mode=asc&amp;cat=' . $idartcat, '-' . $idartcat . '+' . $rewrite_title . '.php?sort=alpha&amp;mode=asc'),
		'U_ARTICLES_DATE_TOP' => transid('.php?sort=date&amp;mode=desc&amp;cat=' . $idartcat, '-' . $idartcat . '+' . $rewrite_title . '.php?sort=date&amp;mode=desc'),
		'U_ARTICLES_DATE_BOTTOM' => transid('.php?sort=date&amp;mode=asc&amp;cat=' . $idartcat, '-' . $idartcat . '+' . $rewrite_title . '.php?sort=date&amp;mode=asc'),
		'U_ARTICLES_VIEW_TOP' => transid('.php?sort=view&amp;mode=desc&amp;cat=' . $idartcat, '-' . $idartcat . '+' . $rewrite_title . '.php?sort=view&amp;mode=desc'),
		'U_ARTICLES_VIEW_BOTTOM' => transid('.php?sort=view&amp;mode=asc&amp;cat=' . $idartcat, '-' . $idartcat . '+' . $rewrite_title . '.php?sort=view&amp;mode=asc'),
		'U_ARTICLES_NOTE_TOP' => transid('.php?sort=note&amp;mode=desc&amp;cat=' . $idartcat, '-' . $idartcat . '+' . $rewrite_title . '.php?sort=note&amp;mode=desc'),
		'U_ARTICLES_NOTE_BOTTOM' => transid('.php?sort=note&amp;mode=asc&amp;cat=' . $idartcat, '-' . $idartcat . '+' . $rewrite_title . '.php?sort=note&amp;mode=asc'),
		'U_ARTICLES_COM_TOP' => transid('.php?sort=com&amp;mode=desc&amp;cat=' . $idartcat, '-' . $idartcat . '+' . $rewrite_title . '.php?sort=com&amp;mode=desc'),
		'U_ARTICLES_COM_BOTTOM' => transid('.php?sort=com&amp;mode=asc&amp;cat=' . $idartcat, '-' . $idartcat . '+' . $rewrite_title . '.php?sort=com&amp;mode=asc')
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
		$sort = 'views';
		break;		
		case 'note' :
		$sort = 'note/' . $CONFIG_ARTICLES['note_max'];
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

	//Catégories non autorisées.
	$unauth_cats_sql = array();
	foreach($CAT_ARTICLES as $id => $key)
	{
		if( !$Member->Check_auth($CAT_ARTICLES[$id]['auth'], READ_CAT_ARTICLES) )
			$unauth_cats_sql[] = $id;
	}
	$nbr_unauth_cats = count($unauth_cats_sql);
	$clause_unauth_cats = ($nbr_unauth_cats > 0) ? " AND ac.id NOT IN (" . implode(', ', $unauth_cats_sql) . ")" : '';

	##### Catégorie disponibles #####	
	if( $total_cat > 0 && $nbr_unauth_cats < $total_cat )
	{
		$Template->Assign_vars(array(			
			'C_ARTICLES_CAT' => true,
			'PAGINATION_CAT' => $Pagination->Display_pagination('articles' . transid('.php' . (!empty($unget) ? $unget . '&amp;' : '?') . 'cat=' . $idartcat . '&amp;pcat=%d', '-' . $idartcat . '-0+' . $rewrite_title . '.php?pcat=%d' . $unget), $total_cat , 'pcat', $CONFIG_ARTICLES['nbr_cat_max'], 3),
			'EDIT' => $is_admin ? '<a href="admin_articles_cat.php"><img class="valign_middle" src="../templates/' . $CONFIG['theme'] .  '/images/' . $CONFIG['lang'] . '/edit.png" alt="" /></a>' : ''
		));	
			
		$i = 0;	
		$result = $Sql->Query_while("SELECT ac.id, ac.name, ac.contents, ac.icon, (ac.nbr_articles_visible + ac.nbr_articles_unvisible) AS nbr_articles, ac.nbr_articles_unvisible
		FROM ".PREFIX."articles_cats ac
		" . $clause_cat . $clause_unauth_cats . "
		ORDER BY ac.id_left
		" . $Sql->Sql_limit($Pagination->First_msg($CONFIG_ARTICLES['nbr_cat_max'], 'pcat'), $CONFIG_ARTICLES['nbr_cat_max']), __LINE__, __FILE__);
		while( $row = $Sql->Sql_fetch_assoc($result) )
		{
			$Template->Assign_block_vars('cat_list', array(
				'IDCAT' => $row['id'],
				'CAT' => $row['name'],
				'DESC' => $row['contents'],
				'ICON_CAT' => !empty($row['icon']) ? '<a href="articles' . transid('.php?cat=' . $row['id'], '-' . $row['id'] . '+' . url_encode_rewrite($row['name']) . '.php') . '"><img src="' . $row['icon'] . '" alt="" class="valign_middle" /></a><br />' : '',
				'EDIT' => $is_admin ? '<a href="admin_articles_cat.php?id=' . $row['id'] . '"><img class="valign_middle" src="../templates/' . $CONFIG['theme'] .  '/images/' . $CONFIG['lang'] . '/edit.png" alt="" /></a>' : '',
				'L_NBR_ARTICLES' => sprintf($LANG['nbr_articles_info'], $row['nbr_articles']),
				'U_CAT' => transid('.php?cat=' . $row['id'], '-' . $row['id'] . '+' . url_encode_rewrite($row['name']) . '.php')
			));
		}
		$Sql->Close($result);	
	}
	
	##### Affichage des articles #####	
	if( $nbr_articles > 0 )
	{
		$Template->Assign_vars(array(		
			'C_ARTICLES_LINK' => true,
			'PAGINATION' => $Pagination->Display_pagination('articles' . transid('.php' . (!empty($unget) ? $unget . '&amp;' : '?') . 'cat=' . $idartcat . '&amp;p=%d', '-' . $idartcat . '-0-%d+' . $rewrite_title . '.php' . $unget), $nbr_articles , 'p', $CONFIG_ARTICLES['nbr_articles_max'], 3),
			'CAT' => $CAT_ARTICLES[$idartcat]['name'],
			'EDIT' => $is_admin ? '<a href="admin_articles_cat.php?id=' . $idartcat . '"><img class="valign_middle" src="../templates/' . $CONFIG['theme'] .  '/images/' . $CONFIG['lang'] . '/edit.png" alt="" /></a>' : ''
		));

		$result = $Sql->Query_while("SELECT id, title, icon, timestamp, views, note, nbrnote, nbr_com
		FROM ".PREFIX."articles
		WHERE visible = 1 AND idcat = '" . $idartcat .	"' 
		ORDER BY " . $sort . " " . $mode . 
		$Sql->Sql_limit($Pagination->First_msg($CONFIG_ARTICLES['nbr_articles_max'], 'p'), $CONFIG_ARTICLES['nbr_articles_max']), __LINE__, __FILE__);
		while( $row = $Sql->Sql_fetch_assoc($result) )
		{
			//On reccourci le lien si il est trop long.
			$fichier = (strlen($row['title']) > 45 ) ? substr(html_entity_decode($row['title']), 0, 45) . '...' : $row['title'];

			//Commentaires
			$link_pop = "<a href=\"#\" onclick=\"popup('" . HOST . DIR . transid("/includes/com.php?i=" . $row['id'] . "articles") . "', 'articles');\">";
			$link_current = '<a href="' . HOST . DIR . '/articles/articles' . transid('.php?cat=' . $idartcat . '&amp;id=' . $row['id'] . '&amp;i=0', '-' . $idartcat . '-' . $row['id'] . '.php?i=0') . '#articles">';	
			$link = ($CONFIG['com_popup'] == '0') ? $link_current : $link_pop;
			
			$Template->Assign_block_vars('articles', array(			
				'NAME' => $fichier,
				'ICON' => !empty($row['icon']) ? '<a href="articles' . transid('.php?id=' . $row['id'] . '&amp;cat=' . $idartcat, '-' . $idartcat . '-' . $row['id'] . '+' . url_encode_rewrite($fichier) . '.php') . '"><img src="' . $row['icon'] . '" alt="" class="valign_middle" /></a>' : '',
				'CAT' => $CAT_ARTICLES[$idartcat]['name'],
				'DATE' => gmdate_format('date_format_short', $row['timestamp']),
				'COMPT' => $row['views'],
				'NOTE' => ($row['nbrnote'] > 0) ? $row['note'] . '/' . $CONFIG_ARTICLES['note_max'] : '<em>' . $LANG['no_note'] . '</em>',
				'COM' => $link . $row['nbr_com'] . '</a>',
				'U_ARTICLES_LINK' => transid('.php?id=' . $row['id'] . '&amp;cat=' . $idartcat, '-' . $idartcat . '-' . $row['id'] . '+' . url_encode_rewrite($fichier) . '.php')
			));

		}
		$Sql->Close($result);
	}
	 
	$Template->Pparse('articles_cat');
}
			
require_once('../includes/footer.php'); 

?>