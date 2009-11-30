<?php
/*##################################################
 *                               admin_forum.php
 *                            -------------------
 *   begin                : October 30, 2005
 *   copyright            : (C) 2005 Viarre Régis
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

require_once('../admin/admin_begin.php');
load_module_lang('forum'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

require_once('../forum/forum_begin.php');

$id = retrieve(GET, 'id', 0);
$del = retrieve(GET, 'del', 0);
$move = retrieve(GET, 'move', '', TSTRING_UNCHANGE);

//Si c'est confirmé on execute
if (!empty($_POST['valid']) && !empty($id))
{
	$Cache->load('forum');

	$to = retrieve(POST, 'category', 0);
	$name = retrieve(POST, 'name', '');
	$url = retrieve(POST, 'url', '');
	$type = retrieve(POST, 'type', '');
	$subname = retrieve(POST, 'desc', '', TSTRING_UNCHANGE);
	$status = retrieve(POST, 'status', 1);
	$aprob = retrieve(POST, 'aprob', 0);

	$subname = strparse($subname, array(
	    4 => 'title',
	    5 => 'style',
	    8 => 'quote',
	    9 => 'hide',
	    10 => 'list',
	    15 => 'align',
	    16 => 'float',
	    19 => 'indent',
	    20 => 'pre',
	    21 => 'table',
	    22 => 'swf',
	    23 => 'movie',
	    24 => 'sound',
	    25 => 'code',
	    26 => 'math',
	    27 => 'anchor',
	    28 => 'acronym',
	    29 => 'block',
	    30 => 'fieldset',
	    31 => 'mail',
	    32 => 'line',
	    33 => 'wikipedia',
	    34 => 'html'
    ));

	if ($type == 1)
	{
		$url = '';
		$parent_category = 0;
	}
	elseif ($type == 2)
		$url = '';
	elseif ($type == 3)
	{
		$status = 1;
		if (empty($url)) //Ne doit pas être vide dans tout les cas.
			$url = $Sql->query("SELECT url FROM " . PREFIX . "forum_cats WHERE id = '" . $id . "'", __LINE__, __FILE__);
	}

	//Génération du tableau des droits.
	$array_auth_all = Authorizations::build_auth_array_from_form(READ_CAT_FORUM, WRITE_CAT_FORUM, EDIT_CAT_FORUM);
	if (!empty($name))
	{
		$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET name = '" . $name . "', subname = '" . $subname . "', url = '" . $url . "', status = '" . $status . "', aprob = '" . $aprob . "', auth = '" . addslashes(serialize($array_auth_all)) . "' WHERE id = '" . $id . "'", __LINE__, __FILE__);

		if ($type != 3 || !empty($to))
		{
			//Empêche le déplacement dans une catégorie fille.
			$to = $Sql->query("SELECT id FROM " . PREFIX . "forum_cats WHERE id = '" . $to . "' AND id_left NOT BETWEEN '" . $CAT_FORUM[$id]['id_left'] . "' AND '" . $CAT_FORUM[$id]['id_right'] . "'", __LINE__, __FILE__);

			//Catégorie parente changée?
			$change_cat = !empty($to) ? !($CAT_FORUM[$to]['id_left'] < $CAT_FORUM[$id]['id_left'] && $CAT_FORUM[$to]['id_right'] > $CAT_FORUM[$id]['id_right'] && ($CAT_FORUM[$id]['level'] - 1) == $CAT_FORUM[$to]['level']) : $CAT_FORUM[$id]['level'] > 0;
			if ($change_cat)
			{
				$admin_forum = new Admin_forum();
				$admin_forum->move_cat($id, $to);
			}
			else
				$Cache->Generate_module_file('forum'); //Régénération du cache.
		}
		else
			$Cache->Generate_module_file('forum'); //Régénération du cache.
	}
	else
		redirect('/forum/admin_forum.php?id=' . $id . '&error=incomplete');

    forum_generate_feeds();
	redirect('/forum/admin_forum.php');
}
elseif (!empty($del)) //Suppression de la catégorie/sous-catégorie.
{
	$Session->csrf_get_protect(); //Protection csrf

	$Cache->load('forum');
	$confirm_delete = false;
	$idcat = $Sql->query("SELECT id FROM " . PREFIX . "forum_cats WHERE id = '" . $del . "'", __LINE__, __FILE__);
	if (!empty($idcat) && isset($CAT_FORUM[$idcat]))
	{
		//On vérifie si la catégorie contient des sous forums.
		$nbr_sub_cat = (($CAT_FORUM[$idcat]['id_right'] - $CAT_FORUM[$idcat]['id_left'] - 1) / 2);
		//On vérifie si la catégorie ne contient pas de topic.
		$check_topic = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "forum_topics WHERE idcat = '" . $idcat . "'", __LINE__, __FILE__);

		if ($check_topic == 0 && $nbr_sub_cat == 0) //Si vide on supprime simplement, la catégorie.
		{
			$confirm_delete = true;
			$admin_forum = new Admin_forum();
			$admin_forum->del_cat($idcat, $confirm_delete);

			forum_generate_feeds();
			redirect(HOST . SCRIPT);
		}
		else //Sinon on propose de déplacer les topics existants dans une autre catégorie.
		{
			if (empty($_POST['del_cat']))
			{
				$Template->set_filenames(array(
					'admin_forum_cat_del'=> 'forum/admin_forum_cat_del.tpl'
				));

				if ($check_topic > 0) //Conserve les topics.
				{
					//Listing des catégories disponibles, sauf celle qui va être supprimée.
					$forums = '';
					$result = $Sql->query_while("SELECT id, name, level
					FROM " . PREFIX . "forum_cats
					WHERE id_left NOT BETWEEN '" . $CAT_FORUM[$idcat]['id_left'] . "' AND '" . $CAT_FORUM[$idcat]['id_right'] . "' AND url = ''
					ORDER BY id_left", __LINE__, __FILE__);
					while ($row = $Sql->fetch_assoc($result))
					{
						$margin = ($row['level'] > 0) ? str_repeat('--------', $row['level']) : '--';
						$disabled = ($row['level'] > 0) ? '' : ' disabled="disabled"';
						$forums .= '<option value="' . $row['id'] . '"' . $disabled . '>' . $margin . ' ' . $row['name'] . '</option>';
					}
					$Sql->query_close($result);

					$Template->assign_block_vars('topics', array(
						'FORUMS' => $forums,
						'L_KEEP' => $LANG['keep_topic'],
						'L_MOVE_TOPICS' => $LANG['move_topics_to'],
						'L_EXPLAIN_CAT' => sprintf((($check_topic > 1) ? $LANG['explain_topics'] : $LANG['explain_topic']), $check_topic)
					));
				}
				if ($nbr_sub_cat > 0) //Concerne uniquement les sous-forums.
				{
					//Listing des catégories disponibles, sauf celle qui va être supprimée.
					$forums = '<option value="0">' . $LANG['root'] . '</option>';
					$result = $Sql->query_while("SELECT id, name, level
					FROM " . PREFIX . "forum_cats
					WHERE id_left NOT BETWEEN '" . $CAT_FORUM[$idcat]['id_left'] . "' AND '" . $CAT_FORUM[$idcat]['id_right'] . "' AND url = ''
					ORDER BY id_left", __LINE__, __FILE__);
					while ($row = $Sql->fetch_assoc($result))
					{
						$margin = ($row['level'] > 0) ? str_repeat('--------', $row['level']) : '--';
						$forums .= '<option value="' . $row['id'] . '">' . $margin . ' ' . $row['name'] . '</option>';
					}
					$Sql->query_close($result);

					$Template->assign_block_vars('subforums', array(
						'FORUMS' => $forums,
						'L_KEEP' => $LANG['keep_subforum'],
						'L_MOVE_FORUMS' => $LANG['move_sub_forums_to'],
						'L_EXPLAIN_CAT' => sprintf((($nbr_sub_cat > 1) ? $LANG['explain_subcats'] : $LANG['explain_subcat']), $nbr_sub_cat)
					));
				}

				$forum_name = $Sql->query("SELECT name FROM " . PREFIX . "forum_cats WHERE id = '" . $idcat . "'", __LINE__, __FILE__);
				$Template->assign_vars(array(
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

				$Template->pparse('admin_forum_cat_del'); //Traitement du modele
			}
			else //Traitements.
			{
				if (!empty($_POST['del_conf'])) //Suppression complète.
					$confirm_delete = true;

				$admin_forum = new Admin_forum();
				$admin_forum->del_cat($idcat, $confirm_delete);

				forum_generate_feeds();
				redirect(HOST . SCRIPT);
			}
		}
	}
	else
		redirect(HOST . SCRIPT);
}
elseif (!empty($id) && !empty($move)) //Monter/descendre.
{
	$Session->csrf_get_protect(); //Protection csrf

	$Cache->load('forum');

	//Catégorie existe?
	if (!isset($CAT_FORUM[$id]))
		redirect('/forum/admin_forum.php');

	$admin_forum = new Admin_forum();

	if ($move == 'up' || $move == 'down')
		$admin_forum->move_updown_cat($id, $move);

	forum_generate_feeds();
	redirect(HOST . SCRIPT);
}
elseif (!empty($id))
{
	$Cache->load('forum');

	$Template->set_filenames(array(
		'admin_forum_cat_edit'=> 'forum/admin_forum_cat_edit.tpl'
	));

	$forum_info = $Sql->query_array(PREFIX . "forum_cats", "id_left", "id_right", "level", "name", "subname", "url", "status", "aprob", "auth", "WHERE id = '" . $id . "'", __LINE__, __FILE__);

	//Listing des catégories disponibles, sauf celle qui va être supprimée.
	$forums = '<option value="0" checked="checked">' . $LANG['root'] . '</option>';
	$result = $Sql->query_while("SELECT id, id_left, id_right, name, level
	FROM " . PREFIX . "forum_cats
	WHERE id_left NOT BETWEEN '" . $CAT_FORUM[$id]['id_left'] . "' AND '" . $CAT_FORUM[$id]['id_right'] . "'
	ORDER BY id_left", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$margin = ($row['level'] > 0) ? str_repeat('--------', $row['level']) : '--';
		$selected = ($row['id_left'] < $forum_info['id_left'] && $row['id_right'] > $forum_info['id_right'] && ($forum_info['level'] - 1) == $row['level'] ) ? ' selected="selected"' : '';
		$forums .= '<option value="' . $row['id'] . '"' . $selected . '>' . $margin . ' ' . $row['name'] . '</option>';
	}
	$Sql->query_close($result);

	//Gestion erreur.
	$get_error = retrieve(GET, 'error', '');
	if ($get_error == 'incomplete')
		$Errorh->handler($LANG['e_incomplete'], E_USER_NOTICE);

	$is_root = ($forum_info['level'] > 0);

	$array_auth = !empty($forum_info['auth']) ? unserialize($forum_info['auth']) : array(); //Récupération des tableaux des autorisations et des groupes.

	//Type de forum
	$type = 2;
	if (!empty($forum_info['url']))
		$type = 3;
	elseif ($forum_info['level'] == 0)
		$type = 1;

	$Template->assign_vars(array(
		'THEME' => get_utheme(),
		'ID' => $id,
		'TYPE' => $type,
		'CATEGORIES' => $forums,
		'NAME' => $forum_info['name'],
		'URL' => $forum_info['url'],
		'DESC' => unparse($forum_info['subname']),
		'CHECKED_APROB' => ($forum_info['aprob'] == 1) ? 'checked="checked"' : '',
		'UNCHECKED_APROB' => ($forum_info['aprob'] == 0) ? 'checked="checked"' : '',
		'CHECKED_STATUS' => ($forum_info['status'] == 1) ? 'checked="checked"' : '',
		'UNCHECKED_STATUS' => ($forum_info['status'] == 0) ? 'checked="checked"' : '',
		'AUTH_READ' => Authorizations::generate_select(READ_CAT_FORUM, $array_auth),
		'AUTH_WRITE' => $is_root ? Authorizations::generate_select(WRITE_CAT_FORUM, $array_auth) : Authorizations::generate_select(WRITE_CAT_FORUM, $array_auth, array(), GROUP_DEFAULT_IDSELECT, GROUP_DISABLE_SELECT),
		'AUTH_EDIT' => $is_root ? Authorizations::generate_select(EDIT_CAT_FORUM, $array_auth) : Authorizations::generate_select(EDIT_CAT_FORUM, $array_auth, array(), GROUP_DEFAULT_IDSELECT, GROUP_DISABLE_SELECT),
		'DISABLED' => $is_root ? '0' : '1',
		'L_REQUIRE_TITLE' => $LANG['require_title'],
		'L_FORUM_MANAGEMENT' => $LANG['forum_management'],
		'L_CAT_MANAGEMENT' => $LANG['cat_management'],
		'L_ADD_CAT' => $LANG['cat_add'],
		'L_FORUM_CONFIG' => $LANG['forum_config'],
		'L_FORUM_GROUPS' => $LANG['forum_groups_config'],
		'L_EDIT_CAT' => $LANG['cat_edit'],
		'L_REQUIRE' => $LANG['require'],
		'L_APROB' => $LANG['visible'],
		'L_STATUS' => $LANG['status'],
		'L_RANK' => $LANG['rank'],
		'L_DELETE' => $LANG['delete'],
		'L_PARENT_CATEGORY' => $LANG['parent_category'],
		'L_NAME' => $LANG['name'],
		'L_URL' => $LANG['url'],
		'L_URL_EXPLAIN' => $LANG['url_explain'],
		'L_DESC' => $LANG['description'],
		'L_RESET' => $LANG['reset'],
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_LOCK' => $LANG['lock'],
		'L_UNLOCK' => $LANG['unlock'],
		'L_GUEST' => $LANG['guest'],
		'L_USER' => $LANG['member'],
		'L_MODO' => $LANG['modo'],
		'L_ADMIN' => $LANG['admin'],
		'L_UPDATE' => $LANG['update'],
		'L_AUTH_READ' => $LANG['auth_read'],
		'L_AUTH_WRITE' => $LANG['auth_write'],
		'L_AUTH_EDIT' => $LANG['auth_edit']
	));

	$Template->pparse('admin_forum_cat_edit'); // traitement du modele
}
else
{
	$Template->set_filenames(array(
	'admin_forum_cat'=> 'forum/admin_forum_cat.tpl'
	));

	$Template->assign_vars(array(
		'THEME' => get_utheme(),
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
		'L_USER' => $LANG['member'],
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

	$max_cat = $Sql->query("SELECT MAX(id_left) FROM " . PREFIX . "forum_cats", __LINE__, __FILE__);
	$list_cats_js = '';
	$array_js = '';
	$i = 0;
	$result = $Sql->query_while("SELECT id, id_left, id_right, level, name, subname, url, status
	FROM " . PREFIX . "forum_cats
	ORDER BY id_left", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		//On assigne les variables pour le POST en précisant l'idurl.
		$Template->assign_block_vars('list', array(
			'I' => $i,
			'ID' => $row['id'],
			'NAME' => (strlen($row['name']) > 60) ? (substr($row['name'], 0, 60) . '...') : $row['name'],
			'INDENT' => $row['level'] * 75, //Indentation des sous catégories.
			'LOCK' => ($row['status'] == 0) ? '<img class="valign_middle" src="../templates/' . get_utheme() . '/images/readonly.png" alt="" title="' . $LANG['lock'] . '" />' : '',
			'URL' => !empty($row['url']) ? '<a href="' . $row['url'] . '"><img src="./forum_mini.png" alt="" class="valign_middle" /></a> ' : '',
			'U_FORUM_VARS' => !empty($row['url']) ? $row['url'] : (($row['level'] > 0) ? 'forum' . url('.php?id=' . $row['id'], '-' . $row['id'] . '+' . url_encode_rewrite($row['name']) . '.php') : url('index.php?id=' . $row['id'], 'cat-' . $row['id'] . '+' . url_encode_rewrite($row['name']) . '.php'))
		));

		$list_cats_js .= $row['id'] . ', ';

		$array_js .= 'array_cats[' . $row['id'] . '] = new Array();' . "\n";
		$array_js .= 'array_cats[' . $row['id'] . '][\'id\'] = ' . $row['id'] . ";\n";
		$array_js .= 'array_cats[' . $row['id'] . '][\'id_left\'] = ' . $row['id_left'] . ";\n";
		$array_js .= 'array_cats[' . $row['id'] . '][\'id_right\'] = ' . $row['id_right'] . ";\n";
		$array_js .= 'array_cats[' . $row['id'] . '][\'i\'] = ' . $i . ";\n";
		$i++;
	}
	$Sql->query_close($result);

	$Template->assign_vars(array(
		'LIST_CATS' => trim($list_cats_js, ', '),
		'ARRAY_JS' => $array_js,
		'ID_END' => ($i - 1)
	));

	$Template->pparse('admin_forum_cat'); // traitement du modele
}

require_once('../admin/admin_footer.php');

?>