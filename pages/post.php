<?php
/*##################################################
*                               post.php
*                            -------------------
*   begin                : August 12, 2007
*   copyright          : (C) 2007 Sautel Benoit
*   email                : ben.popeye@phpboost.com
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
require_once('../pages/pages_begin.php'); 
include_once('pages_functions.php');

$id_edit = !empty($_GET['id']) ? numeric($_GET['id']) : 0;
$id_edit_post = !empty($_POST['id_edit']) ? numeric($_POST['id_edit']) : 0;
$id_edit = $id_edit > 0 ? $id_edit : $id_edit_post;
$title = !empty($_POST['title']) ? securit($_POST['title']) : '';
$contents = !empty($_POST['contents']) ? trim($_POST['contents']) : '';
$count_hits = !empty($_POST['count_hits']) ? 1 : 0;
$activ_com = !empty($_POST['activ_com']) ? 1 : 0;
$own_auth = !empty($_POST['own_auth']);
$is_cat = !empty($_POST['is_cat']) ? 1 : 0;
$id_cat = !empty($_POST['id_cat']) ? numeric($_POST['id_cat']) : 0;
$preview = !empty($_POST['preview']) ? true : false;
$del_article = !empty($_GET['del']) ? numeric($_GET['del']) : 0;

//Variable d'erreur
$error = '';
if( $id_edit > 0 )
	define('TITLE', $LANG['pages_edition']);
else
	define('TITLE', $LANG['pages_creation']);
	
if( $id_edit > 0 )
{
	$page_infos = $Sql->Query_array('pages', 'id', 'title', 'encoded_title', 'contents', 'auth', 'count_hits', 'activ_com', 'id_cat', 'is_cat', "WHERE id = '" . $id_edit . "'", __LINE__, __FILE__);
	$Speed_bar->Add_link(TITLE, transid('post.php?id=' . $id_edit));
	$Speed_bar->Add_link($page_infos['title'], transid('pages.php?title=' . $page_infos['encoded_title'], $page_infos['encoded_title']));
	$id = $page_infos['id_cat'];
	while( $id > 0 )
	{
		$Speed_bar->Add_link($_PAGES_CATS[$id]['name'], transid('pages.php?title=' . url_encode_rewrite($_PAGES_CATS[$id]['name']), url_encode_rewrite($_PAGES_CATS[$id]['name'])));
		$id = (int)$_PAGES_CATS[$id]['id_parent'];
	}
	if( $Member->Check_auth($_PAGES_CONFIG['auth'], EDIT_PAGE) )
		$Speed_bar->Add_link($LANG['pages'], transid('pages.php'));
	$Speed_bar->Reverse_links();
}
else
	$Speed_bar->Add_link($LANG['pages'], transid('pages.php'));

require_once('../includes/header.php');

//On cr�e ou on �dite une page
if( !empty($contents) )
{
	if( $own_auth )
	{
		//Autorisations de la page -> reconstitution du tableau
		$auth_create = isset($_POST['groups_auth1']) ? $_POST['groups_auth1'] : '';
		$auth_edit = isset($_POST['groups_auth2']) ? $_POST['groups_auth2'] : '';
		$auth_com = isset($_POST['groups_auth3']) ? $_POST['groups_auth3'] : '';
		
		//G�n�ration du tableau des droits.
		$array_auth_all = $Group->Return_array_auth($auth_create, $auth_edit, $auth_com);
		$page_auth = addslashes(serialize($array_auth_all));
	}
	else
		$page_auth = '';
	
	//on ne pr�visualise pas, donc on poste le message ou on l'�dite
	if( !$preview )
	{
		//Edition d'une page
		if( $id_edit > 0 )
		{
			$page_infos = $Sql->Query_array('pages', 'id', 'title', 'contents', 'auth', 'encoded_title', 'is_cat', 'id_cat', "WHERE id = '" . $id_edit . "'", __LINE__, __FILE__);
			
			//Autorisation particuli�re ?
			$special_auth = !empty($page_infos['auth']);
			$array_auth = unserialize($page_infos['auth']);
			//V�rification de l'autorisation d'�diter la page
			if( ($special_auth && !$Member->Check_auth($array_auth, EDIT_PAGE)) || (!$special_auth && !$Member->Check_auth($_PAGES_CONFIG['auth'], EDIT_PAGE)) )
				redirect(HOST . DIR . transid('/pages/pages.php?error=e_auth', '', '&'));
			
			//on v�rifie que la cat�gorie ne s'ins�re pas dans un de ses filles
			if( $page_infos['is_cat'] == 1 )
			{
				$sub_cats = array();
				pages_find_subcats($sub_cats, $page_infos['id_cat']);
				$sub_cats[] = $page_infos['id_cat'];
				if( in_array($id_cat, $sub_cats) ) //Si l'ancienne cat�gorie ne contient pas la nouvelle (sinon boucle infinie)
					$error = 'cat_contains_cat';
			}
			
			//Articles (on �dite l'entr�e de l'article pour la cat�gorie donc aucun probl�me)
			if( $page_infos['is_cat'] == 0 )
			{		
				//On met � jour la table
				$Sql->Query_inject("UPDATE ".PREFIX."pages SET contents = '" . pages_parse($contents) . "', count_hits = '" . $count_hits . "', activ_com = '" . $activ_com . "', auth = '" . $page_auth . "', id_cat = '" . $id_cat . "' WHERE id = '" . $id_edit . "'", __LINE__, __FILE__);
				//On redirige vers la page mise � jour
				redirect(HOST . DIR . '/pages/' . transid('pages.php?title=' . $page_infos['encoded_title'], $page_infos['encoded_title'], '&'));
			}
			//cat�gories : risque de boucle infinie
			elseif( $page_infos['is_cat'] == 1 && empty($error) )
			{
				//Changement de cat�gorie m�re ? => on met � jour la table cat�gories
				if( $id_cat != $page_infos['id_cat'] )
				{
					$Sql->Query_inject("UPDATE ".PREFIX."pages_cats SET id_parent = '" . $id_cat . "' WHERE id = '" . $page_infos['id_cat'] . "'", __LINE__, __FILE__);
				}
				//On met � jour la table
				$Sql->Query_inject("UPDATE ".PREFIX."pages SET contents = '" . pages_parse($contents) . "', count_hits = '" . $count_hits . "', activ_com = '" . $activ_com . "', auth = '" . $page_auth . "' WHERE id = '" . $id_edit . "'", __LINE__, __FILE__);
				//R�g�n�ration du cache
				$Cache->Generate_module_file('pages');
				//On redirige vers la page mise � jour
				redirect(HOST . DIR . '/pages/' . transid('pages.php?title=' . $page_infos['encoded_title'], $page_infos['encoded_title'], '&'));
			}
		}
		//Cr�ation d'une page
		elseif( !empty($title) )
		{
			if( !$Member->Check_auth($_PAGES_CONFIG['auth'], EDIT_PAGE) )
				redirect(HOST . DIR . transid('/pages/pages.php?error=e_auth', '', '&'));
			
			$encoded_title = url_encode_rewrite($title);
			$is_already_page = $Sql->Query("SELECT COUNT(*) FROM ".PREFIX."pages WHERE encoded_title = '" . $encoded_title . "'", __LINE__, __FILE__);
			
			//Si l'article n'existe pas d�j�, on enregistre
			if( $is_already_page == 0 )
			{
				$Sql->Query_inject("INSERT INTO ".PREFIX."pages (title, encoded_title, contents, user_id, count_hits, activ_com, timestamp, auth, is_cat, id_cat) VALUES ('" . $title . "', '" . $encoded_title . "', '" .  pages_parse($contents) . "', '" . $Member->Get_attribute('user_id') . "', '" . $count_hits . "', '" . $activ_com . "', '" . time() . "', '" . $page_auth . "', '" . $is_cat . "', '" . $id_cat . "')", __LINE__, __FILE__);
				//Si c'est une cat�gorie
				if( $is_cat > 0 )
				{
					$last_id_page = $Sql->Sql_insert_id("SELECT MAX(id) FROM ".PREFIX."pages");  
					$Sql->Query_inject("INSERT INTO ".PREFIX."pages_cats (id_parent, id_page) VALUES ('" . $id_cat . "', '" . $last_id_page . "')", __LINE__, __FILE__);
					$last_id_pages_cat = $Sql->Sql_insert_id("SELECT MAX(id) FROM ".PREFIX."pages_cats");
					$Sql->Query_inject("UPDATE ".PREFIX."pages SET id_cat = '" . $last_id_pages_cat . "' WHERE id = '" . $last_id_page . "'", __LINE__, __FILE__);
					//R�g�n�ration du cache
					$Cache->Generate_module_file('pages');
				}
				//On redirige vers la page mise � jour
				redirect(HOST . DIR . '/pages/' . transid('pages.php?title=' . $encoded_title, $encoded_title, '&'));
			}
			//Sinon, message d'erreur
			else
			{
				$error = 'page_already_exists';
			}
		}
	}
	else
		$error = 'preview';
}
//Suppression d'une page
elseif( $del_article > 0 )
{
	$page_infos = $Sql->Query_array('pages', 'id', 'title', 'encoded_title', 'contents', 'auth', 'count_hits', 'activ_com', 'id_cat', 'is_cat', "WHERE id = '" . $del_article . "'", __LINE__, __FILE__);
	
	//Autorisation particuli�re ?
	$special_auth = !empty($page_infos['auth']);
	$array_auth = unserialize($page_infos['auth']);
	if( ($special_auth && !$Member->Check_auth($array_auth, EDIT_PAGE)) || (!$special_auth && !$Member->Check_auth($_PAGES_CONFIG['auth'], EDIT_PAGE)) )
		redirect(HOST . DIR . transid('/pages/pages.php?error=e_auth', '', '&'));
		
	//la page existe bien, on supprime
	if( !empty($page_infos['title']) )
	{
		$Sql->Query_inject("DELETE FROM ".PREFIX."pages WHERE id = '" . $del_article . "'", __LINE__, __FILE__);
		$Sql->Query_inject("DELETE FROM ".PREFIX."pages WHERE redirect = '" . $del_article . "'", __LINE__, __FILE__);
		$Sql->Query_inject("DELETE FROM ".PREFIX."com WHERE script = 'pages' AND idprov = '" . $del_article . "'", __LINE__, __FILE__);
		redirect(HOST . DIR . transid('/pages/pages.php?error=delete_success', '', '&'));
	}
	else
		redirect(HOST . DIR . transid('/pages/pages.php?error=delete_failure', '', '&'));
}

$Template->Set_filenames(array('post' => '../templates/' . $CONFIG['theme'] . '/pages/post.tpl'));

if( $id_edit > 0 )
{
	//Autorisation particuli�re ?
	$special_auth = !empty($page_infos['auth']);
	$array_auth = unserialize($page_infos['auth']);
	//V�rification de l'autorisation d'�diter la page
	if( ($special_auth && !$Member->Check_auth($array_auth, EDIT_PAGE)) || (!$special_auth && !$Member->Check_auth($_PAGES_CONFIG['auth'], EDIT_PAGE)) )
		redirect(HOST . DIR . transid('/pages/pages.php?error=e_auth', '', '&'));
	
	//Erreur d'enregistrement ?
	if( $error == 'cat_contains_cat' )
		$Errorh->Error_handler($LANG['pages_cat_contains_cat'], E_USER_WARNING);
	elseif( $error == 'preview' )
	{
		$Errorh->Error_handler($LANG['pages_notice_previewing'], E_USER_NOTICE);
		$Template->Assign_block_vars('previewing', array(
			'PREVIEWING' => pages_second_parse(stripslashes(pages_parse($contents))),
			'TITLE' => stripslashes($title)
		));
	}

	//G�n�ration de l'arborescence des cat�gories
	$cats = array();
	//num�ro de la cat�gorie de la page ou de la cat�gorie
	$id_cat_display = $page_infos['is_cat'] == 1 ? $_PAGES_CATS[$page_infos['id_cat']]['id_parent'] : $page_infos['id_cat'];
	$cat_list = display_cat_explorer($id_cat_display, $cats, 1);
	
	$Template->Assign_vars(array(
		'CONTENTS' => !empty($error) ? stripslashes($contents) : pages_unparse($page_infos['contents']),
		'COUNT_HITS_CHECKED' => !empty($error) ? ($count_hits == 1 ? 'checked="checked"' : '') : ($page_infos['count_hits'] == 1 ? 'checked="checked"' : ''),
		'ACTIV_COM_CHECKED' => !empty($error) ? ($activ_com == 1 ? 'checked="checked"' : '') : ($page_infos['activ_com'] == 1 ? 'checked="checked"' : ''),
		'OWN_AUTH_CHECKED' => !empty($page_infos['auth']) ? 'checked="checked"' : '',
		'CAT_0' => $id_cat_display == 0 ? 'pages_selected_cat' : '',
		'ID_CAT' => $id_cat_display,
		'SELECTED_CAT' => $id_cat_display,
		'CHECK_IS_CAT' => 'disabled="disabled"' . ($page_infos['is_cat'] == 1 ? ' checked="checked"' : '')
	));
}
else
{
	//Autorisations
	if( !$Member->Check_auth($_PAGES_CONFIG['auth'], EDIT_PAGE) )
		redirect(HOST . DIR . '/pages/pages.php?error=e_auth');
		
	//La page existe d�j� !
	if( $error == 'page_already_exists' )
		$Errorh->Error_handler($LANG['pages_already_exists'], E_USER_WARNING);
	elseif( $error == 'preview' )
	{
		$Errorh->Error_handler($LANG['pages_notice_previewing'], E_USER_NOTICE);
		$Template->Assign_block_vars('previewing', array(
			'PREVIEWING' => pages_second_parse(stripslashes(pages_parse($contents))),
			'TITLE' => stripslashes($title)
		));
	}
	if( !empty($error) )
		$Template->Assign_vars(array(
			'CONTENTS' => stripslashes($contents),
			'PAGE_TITLE' => stripslashes($title)
		));
	
	$Template->Assign_block_vars('create', array());
	
	//G�n�ration de l'arborescence des cat�gories
	$cats = array();
	$cat_list = display_cat_explorer(0, $cats, 1);
	$current_cat = $LANG['pages_root'];
	
	$Template->Assign_vars(array(
		'COUNT_HITS_CHECKED' => !empty($error) ? ($count_hits == 1 ? 'checked="checked"' : '') : ($_PAGES_CONFIG['count_hits'] == 1 ? 'checked="checked"' : ''),
		'ACTIV_COM_CHECKED' => !empty($error) ? ($activ_com == 1 ? 'checked="checked"' : '') :($_PAGES_CONFIG['activ_com'] == 1 ? 'checked="checked"' : ''),
		'OWN_AUTH_CHECKED' => '',
		'CAT_0' => 'pages_selected_cat',
		'ID_CAT' => '0',
		'SELECTED_CAT' => '0'
	));
}

$array_groups = $Group->Create_groups_array(); //Cr�ation du tableau des groupes.
$array_auth = !empty($page_infos['auth']) ? unserialize($page_infos['auth']) : (isset($_PAGES_CONFIG['auth'])) ? $_PAGES_CONFIG['auth'] : array();

$Template->Assign_vars(array(
	'ID_EDIT' => $id_edit,
	'NBR_GROUP' => count($array_groups),
	'SELECT_READ_PAGE' => $Group->Generate_select_auth(1, $array_auth, READ_PAGE),
	'SELECT_EDIT_PAGE' => $Group->Generate_select_auth(2, $array_auth, EDIT_PAGE),
	'SELECT_READ_COM' => $Group->Generate_select_auth(3, $array_auth, READ_COM),
	'OWN_AUTH_DISABLED' => !empty($page_infos['auth']) ? 'false' : 'true',
	'DISPLAY' => empty($page_infos['auth']) ? 'display:none;' : '',
	'PAGES_PATH' => $Template->Module_data_path('pages'),
	'CAT_LIST' => $cat_list,
	'L_AUTH' => $LANG['pages_auth'],
	'L_ACTIV_COM' => $LANG['pages_activ_com'],
	'L_COUNT_HITS' => $LANG['pages_count_hits'],
	'L_ALERT_CONTENTS' => $LANG['page_alert_contents'],
	'L_ALERT_TITLE' => $LANG['page_alert_title'],
	'L_READ_PAGE' => $LANG['pages_auth_read'],
	'L_EDIT_PAGE' => $LANG['pages_auth_edit'],
	'L_READ_COM' => $LANG['pages_auth_read_com'],
	'L_OWN_AUTH' => $LANG['pages_own_auth'],
	'L_IS_CAT' => $LANG['pages_is_cat'],
	'L_CAT' => $LANG['pages_parent_cat'],
	'L_AUTH' => $LANG['pages_auth'],
	'L_PATH' => $LANG['pages_page_path'],
	'L_PROPERTIES' => $LANG['pages_properties'],
	'L_TITLE_POST' => $id_edit > 0 ? sprintf($LANG['pages_edit_page'], $page_infos['title']) : $LANG['pages_creation'],
	'L_TITLE_FIELD' => $LANG['page_title'],
	'L_CONTENTS' => $LANG['page_contents'],
	'L_RESET' => $LANG['reset'],
	'L_PREVIEW' => $LANG['preview'],
	'L_SUMBIT' => $LANG['submit'],
	'L_ROOT' => $LANG['pages_root'],
	'L_PREVIEWING' => $LANG['pages_previewing'],
	'L_CONTENTS_PART' => $LANG['pages_contents_part'],
	'L_SUBMIT' => $LANG['submit'],
	'TARGET' => transid('post.php')
));

include_once('../includes/bbcode.php');

$Template->Pparse('post');

require_once('../includes/footer.php'); 

?>