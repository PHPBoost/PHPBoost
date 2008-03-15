<?php
/*##################################################
 *                               admin_forum.php
 *                            -------------------
 *   begin                : October 30, 2005
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

require_once('../includes/admin_begin.php');
load_module_lang('forum'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../includes/admin_header.php');

$id = !empty($_GET['id']) ? numeric($_GET['id']) : 0;
$del = !empty($_GET['del']) ? numeric($_GET['del']) : 0;
$move = !empty($_GET['move']) ? trim($_GET['move']) : 0;

//Si c'est confirmé on execute
if( !empty($_POST['valid']) && !empty($id) )
{
	$Cache->Load_file('forum');
	
	$to = !empty($_POST['category']) ? numeric($_POST['category']) : 0;
	$name = !empty($_POST['name']) ? securit($_POST['name']) : '';
	$subname = !empty($_POST['desc']) ? securit($_POST['desc']) : '';
	$status = isset($_POST['status']) ? numeric($_POST['status']) : 1;
	$aprob = isset($_POST['aprob']) ? numeric($_POST['aprob']) : 0;  
	$auth_read = isset($_POST['groups_authr']) ? $_POST['groups_authr'] : ''; 
	$auth_write = isset($_POST['groups_authw']) ? $_POST['groups_authw'] : ''; 
	$auth_edit = isset($_POST['groups_authx']) ? $_POST['groups_authx'] : ''; 

	//Génération du tableau des droits.
	$array_auth_all = $Group->Return_array_auth($auth_read, $auth_write, $auth_edit);
		
	if( !empty($name) )
	{
		$Sql->Query_inject("UPDATE ".PREFIX."forum_cats SET name = '" . $name . "', subname = '" . $subname . "', status = '" . $status . "', aprob = '" . $aprob . "', auth = '" . securit(serialize($array_auth_all), HTML_NO_PROTECT) . "' WHERE id = '" . $id . "'", __LINE__, __FILE__);

		//Empêche le déplacement dans une catégorie fille.
		$to = $Sql->Query("SELECT id FROM ".PREFIX."forum_cats WHERE id = '" . $to . "' AND id_left NOT BETWEEN '" . $CAT_FORUM[$id]['id_left'] . "' AND '" . $CAT_FORUM[$id]['id_right'] . "'", __LINE__, __FILE__);
		 
		//Catégorie parente changée?
		$change_cat = !empty($to) ? !($CAT_FORUM[$to]['id_left'] < $CAT_FORUM[$id]['id_left'] && $CAT_FORUM[$to]['id_right'] > $CAT_FORUM[$id]['id_right'] && ($CAT_FORUM[$id]['level'] - 1) == $CAT_FORUM[$to]['level']) : $CAT_FORUM[$id]['level'] > 0;		
		if( $change_cat )
		{	
			require_once('../forum/admin_forum.class.php');
			$admin_forum = new Admin_forum();
			$admin_forum->move_cat($id, $to);
		}
	}
	else
		redirect(HOST . DIR . '/forum/admin_forum.php?id=' . $id . '&error=incomplete');

	redirect(HOST . DIR . '/forum/admin_forum.php');
}
elseif( !empty($del) ) //Suppression de la catégorie/sous-catégorie.
{
	$Cache->Load_file('forum');
	$confirm_delete = false;	
	$idcat = $Sql->Query("SELECT id FROM ".PREFIX."forum_cats WHERE id = '" . $del . "'", __LINE__, __FILE__);
	if( !empty($idcat) && isset($CAT_FORUM[$idcat]) )
	{
		//On vérifie si la catégorie contient des sous forums.
		$nbr_sub_cat = (($CAT_FORUM[$idcat]['id_right'] - $CAT_FORUM[$idcat]['id_left'] - 1) / 2);
		//On vérifie si la catégorie ne contient pas de topic.
		$check_topic = $Sql->Query("SELECT COUNT(*) FROM ".PREFIX."forum_topics WHERE idcat = '" . $idcat . "'", __LINE__, __FILE__);
		
		if( $check_topic == 0 && $nbr_sub_cat == 0 ) //Si vide on supprime simplement, la catégorie.
		{
			$confirm_delete = true;
			require_once('../forum/admin_forum.class.php');
			$admin_forum = new Admin_forum();
			$admin_forum->del_cat($idcat, $confirm_delete);
			
			redirect(HOST . SCRIPT);
		}
		else //Sinon on propose de déplacer les topics existants dans une autre catégorie.
		{
			if( empty($_POST['del_cat']) )
			{
				$Template->Set_filenames(array(
					'admin_forum_cat_del' => '../templates/' . $CONFIG['theme'] . '/forum/admin_forum_cat_del.tpl'
				));

				if( $check_topic > 0 ) //Conserve les topics.
				{
					//Listing des catégories disponibles, sauf celle qui va être supprimée.		
					$forums = '';
					$result = $Sql->Query_while("SELECT id, name, level
					FROM ".PREFIX."forum_cats 
					WHERE id_left NOT BETWEEN '" . $CAT_FORUM[$idcat]['id_left'] . "' AND '" . $CAT_FORUM[$idcat]['id_right'] . "'
					ORDER BY id_left", __LINE__, __FILE__);
					while( $row = $Sql->Sql_fetch_assoc($result) )
					{	
						$margin = ($row['level'] > 0) ? str_repeat('--------', $row['level']) : '--';
						$disabled = ($row['level'] > 0) ? '' : ' disabled="disabled"';
						$forums .= '<option value="' . $row['id'] . '"' . $disabled . '>' . $margin . ' ' . $row['name'] . '</option>';
					}
					$Sql->Close($result);
					
					$Template->Assign_block_vars('topics', array(
						'FORUMS' => $forums,
						'L_KEEP' => $LANG['keep_topic'],
						'L_MOVE_TOPICS' => $LANG['move_topics_to'],
						'L_EXPLAIN_CAT' => sprintf($LANG['error_warning'], sprintf((($check_topic > 1) ? $LANG['explain_topics'] : $LANG['explain_topic']), $check_topic), '', '')
					));
				}		
				if( $nbr_sub_cat > 0 ) //Concerne uniquement les sous-forums.
				{			
					//Listing des catégories disponibles, sauf celle qui va être supprimée.		
					$forums = '<option value="0">' . $LANG['root'] . '</option>';
					$result = $Sql->Query_while("SELECT id, name, level
					FROM ".PREFIX."forum_cats 
					WHERE id_left NOT BETWEEN '" . $CAT_FORUM[$idcat]['id_left'] . "' AND '" . $CAT_FORUM[$idcat]['id_right'] . "'
					ORDER BY id_left", __LINE__, __FILE__);
					while( $row = $Sql->Sql_fetch_assoc($result) )
					{	
						$margin = ($row['level'] > 0) ? str_repeat('--------', $row['level']) : '--';
						$forums .= '<option value="' . $row['id'] . '">' . $margin . ' ' . $row['name'] . '</option>';
					}
					$Sql->Close($result);
					
					$Template->Assign_block_vars('subforums', array(
						'FORUMS' => $forums,
						'L_KEEP' => $LANG['keep_subforum'],
						'L_MOVE_FORUMS' => $LANG['move_sub_forums_to'],
						'L_EXPLAIN_CAT' => sprintf($LANG['error_warning'], sprintf((($nbr_sub_cat > 1) ? $LANG['explain_subcats'] : $LANG['explain_subcat']), $nbr_sub_cat), '', '')
					));
				}
		
				$forum_name = $Sql->Query("SELECT name FROM ".PREFIX."forum_cats WHERE id = '" . $idcat . "'", __LINE__, __FILE__);
				$Template->Assign_vars(array(
					'IDCAT' => $idcat,
					'FORUM_NAME' => $forum_name,
					'L_REQUIRE_SUBCAT' => $LANG['require_subcat'],
					'L_FORUM_MANAGEMENT' => $LANG['forum_management'],
					'L_CAT_MANAGEMENT' => $LANG['cat_management'],
					'L_ADD_CAT' => $LANG['cat_add'],
					'L_FORUM_CONFIG' => $LANG['forum_config'],
					'L_FORUM_GROUPS' => $LANG['forum_groups_config'],
					'L_CAT_TARGET' => $LANG['cat_target'],
					'L_DEL_ALL' => $LANG['del_all'],
					'L_DEL_FORUM_CONTENTS' => sprintf($LANG['del_forum_contents'], $forum_name),
					'L_SUBMIT' => $LANG['submit'],
				));
				
				$Template->Pparse('admin_forum_cat_del'); //Traitement du modele	
			}
			else //Traitements.
			{			
				if( !empty($_POST['del_conf']) ) //Suppression complète.
					$confirm_delete = true;
				
				require_once('../forum/admin_forum.class.php');
				$admin_forum = new Admin_forum();
				$admin_forum->del_cat($idcat, $confirm_delete);	
				
				redirect(HOST . SCRIPT);
			}
		}
	}
	else
		redirect(HOST . SCRIPT);
}
elseif( !empty($id) && !empty($move) ) //Monter/descendre.
{
	$Cache->Load_file('forum');
	
	//Catégorie existe?
	if( !isset($CAT_FORUM[$id]) )
		redirect(HOST . DIR . '/forum/admin_forum.php');
	
	require_once('../forum/admin_forum.class.php');
	$admin_forum = new Admin_forum();
	
	if( $move == 'up' || $move == 'down' )
		$admin_forum->move_updown_cat($id, $move);		
		
	redirect(HOST . SCRIPT);
}
elseif( !empty($id) )
{
	$Cache->Load_file('forum');
	
	$Template->Set_filenames(array(
		'admin_forum_cat_edit' => '../templates/' . $CONFIG['theme'] . '/forum/admin_forum_cat_edit.tpl'
	));
			
	$forum_info = $Sql->Query_array("forum_cats", "id_left", "id_right", "level", "name", "subname", "status", "aprob", "auth", "WHERE id = '" . $id . "'", __LINE__, __FILE__);
	
	//Listing des catégories disponibles, sauf celle qui va être supprimée.			
	$forums = '<option value="0" checked="checked">' . $LANG['root'] . '</option>';
	$result = $Sql->Query_while("SELECT id, id_left, id_right, name, level
	FROM ".PREFIX."forum_cats 
	WHERE id_left NOT BETWEEN '" . $CAT_FORUM[$id]['id_left'] . "' AND '" . $CAT_FORUM[$id]['id_right'] . "'
	ORDER BY id_left", __LINE__, __FILE__);
	while( $row = $Sql->Sql_fetch_assoc($result) )
	{	
		$margin = ($row['level'] > 0) ? str_repeat('--------', $row['level']) : '--';
		$selected = ($row['id_left'] < $forum_info['id_left'] && $row['id_right'] > $forum_info['id_right'] && ($forum_info['level'] - 1) == $row['level'] ) ? ' selected="selected"' : '';
		$forums .= '<option value="' . $row['id'] . '"' . $selected . '>' . $margin . ' ' . $row['name'] . '</option>';
	}
	$Sql->Close($result);
	
	//Gestion erreur.
	$get_error = !empty($_GET['error']) ? trim($_GET['error']) : '';
	if( $get_error == 'incomplete' )
		$Errorh->Error_handler($LANG['e_incomplete'], E_USER_NOTICE);	
	
	$is_root = ($forum_info['level'] > 0);

	$array_groups = $Group->Create_groups_array(); //Création du tableau des groupes.
	$array_auth = !empty($forum_info['auth']) ? unserialize($forum_info['auth']) : array(); //Récupération des tableaux des autorisations et des groupes.
		
	$Template->Assign_vars(array(
		'THEME' => $CONFIG['theme'],
		'MODULE_DATA_PATH' => $Template->Module_data_path('forum'),
		'NBR_GROUP' => count($array_groups),
		'ID' => $id,
		'CATEGORIES' => $forums,
		'NAME' => $forum_info['name'],
		'DESC' => $forum_info['subname'],
		'CHECKED_APROB' => ($forum_info['aprob'] == 1) ? 'checked="checked"' : '',
		'UNCHECKED_APROB' => ($forum_info['aprob'] == 0) ? 'checked="checked"' : '',
		'CHECKED_STATUS' => ($forum_info['status'] == 1) ? 'checked="checked"' : '',
		'UNCHECKED_STATUS' => ($forum_info['status'] == 0) ? 'checked="checked"' : '',
		'AUTH_READ' => $Group->Generate_select_auth('r', $array_auth, 0x01),
		'AUTH_WRITE' => $is_root ? $Group->Generate_select_auth('w', $array_auth, 0x02) : $Group->Generate_select_auth('w', $array_auth, 0x02, array(), GROUP_DISABLE_SELECT),
		'AUTH_EDIT' => $is_root ? $Group->Generate_select_auth('x', $array_auth, 0x04) : $Group->Generate_select_auth('x', $array_auth, 0x04, array(), GROUP_DISABLE_SELECT),
		'DISABLED' => $is_root ? '0' : '1',
		'L_REQUIRE_TITLE' => $LANG['require_title'],
		'L_FORUM_MANAGEMENT' => $LANG['forum_management'],
		'L_CAT_MANAGEMENT' => $LANG['cat_management'],
		'L_ADD_CAT' => $LANG['cat_add'],
		'L_FORUM_CONFIG' => $LANG['forum_config'],
		'L_FORUM_GROUPS' => $LANG['forum_groups_config'],
		'L_EDIT_CAT' => $LANG['cat_edit'],
		'L_REQUIRE' => $LANG['require'],
		'L_APROB' => $LANG['aprob'],
		'L_STATUS' => $LANG['status'],
		'L_RANK' => $LANG['rank'],
		'L_DELETE' => $LANG['delete'],
		'L_PARENT_CATEGORY' => $LANG['parent_category'],
		'L_NAME' => $LANG['name'],
		'L_DESC' => $LANG['description'],
		'L_RESET' => $LANG['reset'],		
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_LOCK' => $LANG['lock'],
		'L_UNLOCK' => $LANG['unlock'],
		'L_GUEST' => $LANG['guest'],
		'L_MEMBER' => $LANG['member'],
		'L_MODO' => $LANG['modo'],
		'L_ADMIN' => $LANG['admin'],
		'L_UPDATE' => $LANG['update'],
		'L_AUTH_READ' => $LANG['auth_read'],
		'L_AUTH_WRITE' => $LANG['auth_write'],
		'L_AUTH_EDIT' => $LANG['auth_edit']
	));
	
	$Template->Pparse('admin_forum_cat_edit'); // traitement du modele
}
else	
{		
	$Template->Set_filenames(array(
	'admin_forum_cat' => '../templates/' . $CONFIG['theme'] . '/forum/admin_forum_cat.tpl'
	));
		
	$array_groups = $Group->Create_groups_array(); //Création du tableau des groupes.
		
	$Template->Assign_vars(array(
		'THEME' => $CONFIG['theme'],
		'MODULE_DATA_PATH' => $Template->Module_data_path('forum'),
		'NBR_GROUP' => count($array_groups),
		'L_CONFIRM_DEL' => $LANG['del_entry'],
		'L_REQUIRE_TITLE' => $LANG['require_title'],
		'L_FORUM_MANAGEMENT' => $LANG['forum_management'],
		'L_CAT_MANAGEMENT' => $LANG['cat_management'],
		'L_ADD_CAT' => $LANG['cat_add'],
		'L_FORUM_CONFIG' => $LANG['forum_config'],
		'L_FORUM_GROUPS' => $LANG['forum_groups_config'],
		'L_DELETE' => $LANG['delete'],
		'L_NAME' => $LANG['name'],
		'L_DESC' => $LANG['description'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset'],		
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_LOCK' => $LANG['lock'],
		'L_UNLOCK' => $LANG['unlock'],
		'L_GUEST' => $LANG['guest'],
		'L_MEMBER' => $LANG['member'],
		'L_MODO' => $LANG['modo'],
		'L_ADMIN' => $LANG['admin'],
		'L_ADD' => $LANG['add'],
		'L_AUTH_READ' => $LANG['auth_read'],
		'L_AUTH_WRITE' => $LANG['auth_write'],
		'L_AUTH_EDIT' => $LANG['auth_edit'],
		'L_EXPLAIN_SELECT_MULTIPLE' => $LANG['explain_select_multiple'],
		'L_SELECT_ALL' => $LANG['select_all'],
		'L_SELECT_NONE' => $LANG['select_none']
	));

	$max_cat = $Sql->Query("SELECT MAX(id_left) FROM ".PREFIX."forum_cats", __LINE__, __FILE__);
	$list_cats_js = '';
	$array_js = '';	
	$i = 0;
	$result = $Sql->Query_while("SELECT id, id_left, id_right, level, name, subname, status
	FROM ".PREFIX."forum_cats 
	ORDER BY id_left", __LINE__, __FILE__);
	while( $row = $Sql->Sql_fetch_assoc($result) )
	{
		//On assigne les variables pour le POST en précisant l'idurl.
		$Template->Assign_block_vars('list', array(
			'I' => $i,
			'ID' => $row['id'],
			'NAME' => $row['name'],
			'DESC' => $row['subname'],
			'INDENT' => $row['level'] * 75, //Indentation des sous catégories.
			'LOCK' => ($row['status'] == 0) ? '<img class="valign_middle" src="../templates/' . $CONFIG['theme'] . '/images/readonly.png" alt="" title="' . $LANG['lock'] . '" />' : '',
			'U_FORUM_VARS' => ($row['level'] > 0) ? 'forum' . transid('.php?id=' . $row['id'], '-' . $row['id'] . '+' . url_encode_rewrite($row['name']) . '.php') : transid('index.php?id=' . $row['id'], 'cat-' . $row['id'] . '+' . url_encode_rewrite($row['name']) . '.php')
		));
		
		$list_cats_js .= $row['id'] . ', ';
		
		$array_js .= 'array_cats[' . $row['id'] . '] = new Array();' . "\n"; 
		$array_js .= 'array_cats[' . $row['id'] . '][\'id\'] = ' . $row['id'] . ";\n";
		$array_js .= 'array_cats[' . $row['id'] . '][\'id_left\'] = ' . $row['id_left'] . ";\n";
		$array_js .= 'array_cats[' . $row['id'] . '][\'id_right\'] = ' . $row['id_right'] . ";\n";
		$array_js .= 'array_cats[' . $row['id'] . '][\'i\'] = ' . $i . ";\n";
		$i++;
	}
	$Sql->Close($result);
	
	$Template->Assign_vars(array(
		'LIST_CATS' => trim($list_cats_js, ', '),
		'ARRAY_JS' => $array_js,
		'ID_END' => ($i - 1)
	));

	$Template->Pparse('admin_forum_cat'); // traitement du modele	
}

require_once('../includes/admin_footer.php');

?>