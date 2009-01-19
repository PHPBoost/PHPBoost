<?php
/*##################################################
 *              	 media_action.php
 *              	-------------------
 *  begin        	: October 20, 2008
 *  copyright    	: (C) 2007 Geoffrey ROGUELON
 *  email        	: liaght@gmail.com
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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 *
###################################################*/

require_once('../kernel/begin.php');

if (!$User->check_level(MODO_LEVEL))
{
	$Errorh->handler('e_auth', E_USER_REDIRECT);
	exit;
}

require_once('media_begin.php');

$Template->set_filenames(array('media_moderation' => 'media/moderation_media.tpl'));

$Bread_crumb->add($MEDIA_CATS[0]['name'], url('media.php'));
$Bread_crumb->add($LANG['modo_panel'], url('moderation_media.php'));

define('TITLE', $LANG['modo_panel']);
require_once('../kernel/header.php');

if (!empty($_POST['submit']))
{
	$action = retrieve(POST, 'action', array(), TARRAY);
	$show = $hide = $delete = array();

	if (!empty($action))
	{
		foreach ($action as $key => $value)
		{
			if ($value == 'visible')
			{
				$show[] = $key;
			}
			elseif ($value == 'unvisible')
			{
				$hide[] = $key;
			}
			elseif ($value == 'delete')
			{
				$delete[] = $key;
			}
		}

		if (!empty($show))
		{
			foreach ($show as $key)
			{
				$Sql->query_inject("UPDATE " . PREFIX . "media SET infos = '" . MEDIA_STATUS_APROBED . "' WHERE id = '" . $key . "'", __LINE__, __FILE__);
			}
		}
		
		if (!empty($hide))
		{
			foreach ($hide as $key)
			{
				$Sql->query_inject("UPDATE " . PREFIX . "media SET infos = '" . MEDIA_STATUS_UNVISIBLE . "' WHERE id = '" . $key . "'", __LINE__, __FILE__);
			}
		}

		if (!empty($delete))
		{
			foreach ($delete as $key)
			{
				$Sql->query_inject("DELETE FROM " . PREFIX . "media WHERE id = '" . $key . "'", __LINE__, __FILE__);
				$Sql->query_inject("DELETE FROM " . PREFIX . "com WHERE idprov = '" . $delete . "' AND script = 'media'", __LINE__, __FILE__);
			}
		}

		// Feeds Regeneration
		import('content/syndication/feed');
		Feed::clear_cache('media');
		require_once('media_cats.class.php');
		$media_categories = new MediaCats();
		$media_categories->recount_media_per_cat();

		redirect_confirm(url('moderation_media.php'), $MEDIA_LANG['moderation_success'], TIME_REDIRECT);
	}
	else
	{
		redirect(url('moderation_media.php'));
	}
}
elseif (!empty($_GET['recount']))
{
	// Feeds Regeneration
	import('content/syndication/feed');
	Feed::clear_cache('media');
	require_once('media_cats.class.php');
	$media_categories = new MediaCats();
	$media_categories->recount_media_per_cat();
	
	redirect_confirm(url('moderation_media.php'), $MEDIA_LANG['recount_success'], TIME_REDIRECT);	
}
else
{
	//On crée une pagination si le nombre de fichier est trop important.
	import('util/pagination');
	$Pagination = new Pagination();

	$nbr_media = $Sql->count_table('media', __LINE__, __FILE__);
	$result = $Sql->query_while("SELECT * FROM " . PREFIX . "media ORDER BY infos ASC, timestamp DESC" . $Sql->limit($Pagination->get_first_msg(NUM_MODO_MEDIA, 'p'), NUM_MODO_MEDIA), __LINE__, __FILE__);

	while ($row = $Sql->fetch_assoc($result))
	{		
		$Template->assign_block_vars('files', array(
			'ID' => $row['id'],
			'NAME' => $row['name'],
			'U_EDIT' => url('media_action.php?edit=' . $row['id']),
			'CAT' => !empty($MEDIA_CATS[$row['idcat']]) ? $MEDIA_CATS[$row['idcat']]['name'] : $LANG['unknow'],
			'U_CAT' => url('media.php?cat=' . $row['idcat']),
			'COLOR' => ($row['infos'] & MEDIA_STATUS_UNVISIBLE) !== 0 ? '#FFEE99' : (($row['infos'] & MEDIA_STATUS_APROBED) !== 0 ? '#CCFFCC' : '#FFCCCC')
		));
	}

	$Sql->query_close($result);

	$Template->assign_vars(array(
		'C_DISPLAY' => 1,
		'L_MODO_PANEL' => $LANG['modo_panel'],
		'L_NAME' => $LANG['name'],
		'L_CATEGORY' => $LANG['category'],
		'L_VISIBLE' => $MEDIA_LANG['show_media_short'],
		'L_UNVISIBLE' => $MEDIA_LANG['hide_media_short'],
		'L_DELETE' => $LANG['delete'],
		'L_WAIT' => $MEDIA_LANG['wait'],
		'C_NO_MODERATION' => $nbr_media > 0 ? 0 : 1,
		'L_NO_MODERATION' => $MEDIA_LANG['no_media_moderate'],
		'L_CONFIRM_DELETE' => str_replace('\'', '\\\'', $MEDIA_LANG['confirm_delete_media']),
		'L_LEGEND' => $MEDIA_LANG['legend'],
		'L_FILE_UNAPROBED' => $MEDIA_LANG['file_unaprobed'],
		'L_FILE_UNVISIBLE' => $MEDIA_LANG['file_unvisible'],
		'L_FILE_VISIBLE' => $MEDIA_LANG['file_visible'],
		'PAGINATION' => $Pagination->display('moderation_media.php?p=%d', $nbr_media, 'p', NUM_MODO_MEDIA, 3),
		'L_SUBMIT' => $LANG['submit'],
		'L_RESET' => $LANG['reset'],
		'C_ADMIN' => $User->check_level(ADMIN_LEVEL),
		'L_RECOUNT_MEDIA' => $MEDIA_LANG['recount_per_cat']
	));
}

$Template->pparse('media_moderation');

require_once('../kernel/footer.php');

?>