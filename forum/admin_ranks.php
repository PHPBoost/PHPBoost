<?php
/*##################################################
 *                               admin_ranks.php
 *                            -------------------
 *   begin                : October 30, 2005
 *   copyright            : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

require_once('../admin/admin_begin.php');
load_module_lang('forum'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$request = AppContext::get_request();

$get_id = $request->get_getint('id', 0);
$del = $request->get_getint('del', 0);

$valid = $request->get_postvalue('valid', false);

//Si c'est confirmé on execute
if ($valid)
{
	$result = PersistenceContext::get_querier()->select("SELECT id, special 
	FROM " . PREFIX . "forum_ranks");
	while ($row = $result->fetch())
	{
		$name = retrieve(POST, $row['id'] . 'name', '');
		$msg = retrieve(POST, $row['id'] . 'msg', 0);
		$icon = retrieve(POST, $row['id'] . 'icon', '');

		if (!empty($name) && $row['special'] != 1)
			PersistenceContext::get_querier()->update(PREFIX . "forum_ranks", array('name' => $name, 'msg' => $msg, 'icon' => $icon), ' WHERE id = :id', array('id' => $row['id']));
		else
			PersistenceContext::get_querier()->update(PREFIX . "forum_ranks", array('name' => $name, 'icon' => $icon), ' WHERE id = :id', array('id' => $row['id']));
	}
	$result->dispose();

	ForumRanksCache::invalidate();
	
	AppContext::get_response()->redirect(HOST . SCRIPT);
}
elseif (!empty($del) && !empty($get_id)) //Suppression du rang.
{
	//On supprime dans la bdd.
	PersistenceContext::get_querier()->delete(PREFIX . 'forum_ranks', 'WHERE id=:id', array('id' => $get_id));

	###### Régénération du cache des rangs #######
	ForumRanksCache::invalidate();
	
	AppContext::get_response()->redirect(HOST . SCRIPT);
}
else //Sinon on rempli le formulaire
{
	$template = new FileTemplate('forum/admin_ranks.tpl');

	$template->put_all(array(
		'L_REQUIRE_RANK_NAME' => $LANG['require_rank_name'],
		'L_REQUIRE_NBR_MSG_RANK' => $LANG['require_nbr_msg_rank'],
		'L_CONFIRM_DEL_RANK' => LangLoader::get_message('confirm.delete', 'status-messages-common'),
		'L_FORUM_MANAGEMENT' => $LANG['forum_management'],
		'L_CAT_MANAGEMENT' => $LANG['cat_management'],
		'L_ADD_CAT' => $LANG['cat_add'],
		'L_FORUM_RANKS_MANAGEMENT' => LangLoader::get_message('forum.ranks_management', 'common', 'forum'),
		'L_FORUM_ADD_RANKS' => LangLoader::get_message('forum.actions.add_rank', 'common', 'forum'),
		'L_RANK_NAME' => $LANG['rank_name'],
		'L_NBR_MSG' => $LANG['nbr_msg'],
		'L_IMG_ASSOC' => $LANG['img_assoc'],
		'L_DELETE' => LangLoader::get_message('delete', 'common'),
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset'],
		'L_ADD' => LangLoader::get_message('add', 'common')
	));

	//On recupère les images des groupes
	
	$rank_options_array = array();
	$image_folder_path = new Folder(PATH_TO_ROOT . '/forum/templates/images/ranks');
	foreach ($image_folder_path->get_files('`\.(png|jpg|bmp|gif)$`i') as $image)
	{
		$file = $image->get_name();
		$rank_options_array[] = $file;
	}
	
	$ranks_cache = ForumRanksCache::load()->get_ranks();
	
	foreach($ranks_cache as $msg => $row)
	{
		if ($row['special'] == 0)
			$del = '<a href="admin_ranks.php?del=1&amp;id=' . $row['id'] . '" class="fa fa-delete" data-confirmation="delete-element"></a>';
		else
			$del = $LANG['special_rank'];

		$rank_options = '<option value="">--</option>';
		foreach ($rank_options_array as $icon)
		{
			$selected = ($icon == $row['icon']) ? ' selected="selected"' : '';
			$rank_options .= '<option value="' . $icon . '"' . $selected . '>' . $icon . '</option>';
		}
		
		$template->assign_block_vars('rank', array(
			'ID' => $row['id'],
			'RANK' => $row['name'],
			'MSG' => ($row['special'] == 0) ? '<input type="number" min="0" name="' . $row['id'] . 'msg" value="' . $msg . '">' : $LANG['special_rank'],
			'RANK_OPTIONS' => $rank_options,
			'IMG_RANK' => $row['icon'],
			'DELETE' => $del
		));
	}
	
	$template->display();
}

require_once('../admin/admin_footer.php');
?>