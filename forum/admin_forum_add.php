<?php
/*##################################################
 *                               admin_forum_add.php
 *                            -------------------
 *   begin                : July  21, 2007
 *   copyright            : (C) 2007 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *
 *
###################################################
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
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

$idcat = retrieve(GET, 'idcat', 0);
$class = retrieve(GET, 'id', 0);


//Si c'est confirmé on execute
if (!empty($_POST['add'])) //Nouveau forum/catégorie.
{
	$Cache->load('forum');
	
	$parent_category = retrieve(POST, 'category', 0);
	$name = retrieve(POST, 'name', '');
	$url = retrieve(POST, 'url', '');
	$type = retrieve(POST, 'type', '');
	$aprob = retrieve(POST, 'aprob', 0);
	$status = retrieve(POST, 'status', 0);

	$subname = retrieve(POST, 'desc', '', TSTRING_UNCHANGE);
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
	else
		$status = 1;
	
	//Génération du tableau des droits.
	$array_auth_all = Authorizations::build_auth_array_from_form(READ_CAT_FORUM, WRITE_CAT_FORUM, EDIT_CAT_FORUM);

	if (!empty($name))
	{
		if (isset($CAT_FORUM[$parent_category])) //Insertion sous forum de niveau x.
		{
			//Forums parent du forum cible.
			$list_parent_cats = '';
			$result = $Sql->query_while("SELECT id
			FROM " . PREFIX . "forum_cats
			WHERE id_left <= '" . $CAT_FORUM[$parent_category]['id_left'] . "' AND id_right >= '" . $CAT_FORUM[$parent_category]['id_right'] . "'", __LINE__, __FILE__);
			while ($row = $Sql->fetch_assoc($result))
			{
				$list_parent_cats .= $row['id'] . ', ';
			}
			$Sql->query_close($result);
			$list_parent_cats = trim($list_parent_cats, ', ');
						
			if (empty($list_parent_cats))
				$clause_parent = "id = '" . $parent_category . "'";
			else
				$clause_parent = "id IN (" . $list_parent_cats . ")";
			
			$id_left = $CAT_FORUM[$parent_category]['id_right'];
			$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_right = id_right + 2 WHERE " . $clause_parent, __LINE__, __FILE__);
			$Sql->query_inject("UPDATE " . PREFIX . "forum_cats SET id_right = id_right + 2, id_left = id_left + 2 WHERE id_left > '" . $id_left . "'", __LINE__, __FILE__);
			$level = $CAT_FORUM[$parent_category]['level'] + 1;

		}
		else //Insertion forum niveau 0.
		{
			$id_left = $Sql->query("SELECT MAX(id_right) FROM " . PREFIX . "forum_cats", __LINE__, __FILE__);
			$id_left++;
			$level = 0;
		}
		
		$Sql->query_inject("INSERT INTO " . PREFIX . "forum_cats (id_left, id_right, level, name, subname, url, nbr_topic, nbr_msg, last_topic_id, status, aprob, auth) VALUES('" . $id_left . "', '" . ($id_left + 1) . "', '" . $level . "', '" . $name . "', '" . $subname . "', '" . $url . "', 0, 0, 0, '" . $status . "', '" . $aprob . "', '" . addslashes(serialize($array_auth_all)) . "')", __LINE__, __FILE__);

		###### Regénération du cache des catégories (liste déroulante dans le forum) #######
		$Cache->Generate_module_file('forum');
		
		forum_generate_feeds();
		redirect('/forum/admin_forum.php');
	}
	else
		redirect('/forum/admin_forum_add.php?error=incomplete#errorh');
}
else
{
	$Template->set_filenames(array(
		'admin_forum_add'=> 'forum/admin_forum_add.tpl'
	));
			
	//Listing des catégories disponibles, sauf celle qui va être supprimée.
	$forums = '<option value="0" checked="checked" disabled="disabled">' . $LANG['root'] . '</option>';
	$result = $Sql->query_while("SELECT id, name, level
	FROM " . PREFIX . "forum_cats
	ORDER BY id_left", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$margin = ($row['level'] > 0) ? str_repeat('--------', $row['level']) : '--';
		$forums .= '<option value="' . $row['id'] . '">' . $margin . ' ' . $row['name'] . '</option>';
	}
	$Sql->query_close($result);
	
	//Gestion erreur.
	$get_error = retrieve(GET, 'error', '');
	if ($get_error == 'incomplete')
		$Errorh->handler($LANG['e_incomplete'], E_USER_NOTICE);
	
	$Template->assign_vars(array(
		'THEME' => get_utheme(),
		'CATEGORIES' => $forums,
		'AUTH_READ' => Authorizations::generate_select(READ_CAT_FORUM, array(), array(-1 => true, 0 => true, 1 => true, 2 => true)),
		'AUTH_WRITE' => Authorizations::generate_select(WRITE_CAT_FORUM, array(), array(0 => true, 1 => true, 2 => true)),
		'AUTH_EDIT' => Authorizations::generate_select(EDIT_CAT_FORUM, array(), array(1 => true, 2 => true)),
		'L_REQUIRE_TITLE' => $LANG['require_title'],
		'L_FORUM_MANAGEMENT' => $LANG['forum_management'],
		'L_CAT_MANAGEMENT' => $LANG['cat_management'],
		'L_ADD_CAT' => $LANG['cat_add'],
		'L_FORUM_CONFIG' => $LANG['forum_config'],
		'L_FORUM_GROUPS' => $LANG['forum_groups_config'],
		'L_REQUIRE' => $LANG['require'],
		'L_APROB' => $LANG['visible'],
		'L_STATUS' => $LANG['status'],
		'L_RANK' => $LANG['rank'],
		'L_DELETE' => $LANG['delete'],
		'L_PARENT_CATEGORY' => $LANG['parent_category'],
		'L_TYPE' => $LANG['type'],
		'L_CATEGORY' => $LANG['category'],
		'L_FORUM' => $LANG['forum'],
		'L_LINK' => $LANG['link'],
		'L_NAME' => $LANG['name'],
		'L_DESC' => $LANG['description'],
		'L_URL' => $LANG['url'],
		'L_URL_EXPLAIN' => $LANG['url_explain'],
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
		'L_AUTH_EDIT' => $LANG['auth_edit']
	));
	
	$Template->pparse('admin_forum_add'); // traitement du modele
}

require_once('../admin/admin_footer.php');

?>