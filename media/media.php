<?php
/*##################################################
 *                              media.php
 *                            -------------------
 *   begin               	: October 20, 2008
 *   copyright        	: (C) 2007 Geoffrey ROGUELON
 *   email               	: liaght@gmail.com
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

require_once('../kernel/begin.php');
require_once('media_begin.php');

$Template->set_filenames(array('media' => 'media/media.tpl'));

$id_media = retrieve(GET, 'id', 0);
$id_cat = retrieve(GET, 'cat', 0);
$level = array('', ' class="modo"', ' class="admin"');

// Display caterories and media files.
if (empty($id_media) && $id_cat >= 0)
{
	//if the category doesn't exist or is not visible
	if (empty($MEDIA_CATS[$id_cat]) || $MEDIA_CATS[$id_cat]['visible'] === false || !$User->check_auth($MEDIA_CATS[$id_cat]['auth'], MEDIA_AUTH_READ))
	{
		$Errorh->handler('e_unexist_cat', E_USER_REDIRECT);
		exit;
	}

	bread_crumb($id_cat);

	define('TITLE', $MEDIA_CATS[$id_cat]['name']);

	require_once('../kernel/header.php');

	$i = 1;
	//List of children categories
	foreach ($MEDIA_CATS as $id => $array)
	{
		if ($id != 0 && $array['visible'] && $array['id_parent'] == $id_cat && $User->check_auth($array['auth'], MEDIA_AUTH_READ))
		{
			if ($i % $MEDIA_CONFIG['nbr_column'] == 1)
			{
				$Template->assign_block_vars('row', array());
			}

			$Template->assign_block_vars('row.list_cats', array(
				'ID' => $id,
				'NAME' => $array['name'],
				'WIDTH' => floor(100 / (float)$MEDIA_CONFIG['nbr_column']),
				'SRC' => !empty($array['image']) ? $array['image'] : 'media_mini.png',
				'IMG_NAME' => addslashes($array['name']),
				'NUM_MEDIA' => ($array['active'] & MEDIA_NBR) !== 0 ? sprintf(($array['num_media'] > 1 ? $MEDIA_LANG['num_medias'] : $MEDIA_LANG['num_media']), $array['num_media']) : '',
				'U_CAT' => url('media.php?cat=' . $id, 'media-0-' . $id . '+' . url_encode_rewrite($array['name']) . '.php'),
				'U_ADMIN_CAT' => url('admin_media_cats.php?edit=' . $id)
			));

			$i++;
		}
	}

	$Template->assign_vars(array(
		'C_CATEGORIES' => true,
		'TITLE' => $MEDIA_CATS[$id_cat]['name'],
		'C_ADMIN' => $User->check_level(ADMIN_LEVEL),
		'C_MODO' => $User->check_level(MODO_LEVEL),
		'U_ADMIN_CAT' => $id_cat == 0 ? 'admin_media_config.php' : 'admin_media_cats.php?edit=' . $id_cat,
		'C_ADD_FILE' => $User->check_auth($MEDIA_CATS[$id_cat]['auth'], MEDIA_AUTH_WRITE) || $User->check_auth($MEDIA_CATS[$id_cat]['auth'], MEDIA_AUTH_CONTRIBUTION),
		'U_ADD_FILE' => 'media_action.php?add=' . $id_cat,
		'L_ADD_FILE' => $MEDIA_LANG['add_media'],
		'C_DESCRIPTION' => !empty($MEDIA_CATS[$id_cat]['desc']),
		'DESCRIPTION' => second_parse($MEDIA_CATS[$id_cat]['desc']),
		'C_SUB_CATS' => $i > 1
	));

	//Contenu de la catégorie
	if ($MEDIA_CATS[$id_cat]['num_media'] > 0)
	{
		$get_sort = retrieve(GET, 'sort', '');
		$get_mode = retrieve(GET, 'mode', '');
		$mode = ($get_mode == 'asc') ? 'ASC' : 'DESC';
		$unget = (!empty($get_sort) && !empty($mode)) ? '?sort=' . $get_sort . '&amp;mode=' . $get_mode : '';
		$selected_fields = array('alpha' => '', 'date' => '', 'nbr' => '', 'note' => '', 'com' => '', 'asc' => '', 'desc' => '');

		switch ($get_sort)
		{
			case 'alpha':
				$sort = 'name';
				$selected_fields['alpha'] = ' selected="selected"';
				break;
			default:
			case 'date':
				$sort = 'timestamp';
				$selected_fields['date'] = ' selected="selected"';
				break;
			case 'nbr':
				$sort = 'counter';
				$selected_fields['nbr'] = ' selected="selected"';
				break;
			case 'note':
				$sort = 'nbrnote';
				$selected_fields['note'] = ' selected="selected"';
				break;
			case 'com':
				$sort = 'nbr_com';
				$selected_fields['com'] = ' selected="selected"';
				break;
		}

		if ($mode == 'ASC')
		{
			$selected_fields['asc'] = ' selected="selected"';
		}
		else
		{
			$selected_fields['desc'] = ' selected="selected"';
		}

		$Template->assign_vars(array(
			'L_ALPHA' => $MEDIA_LANG['sort_title'],
			'L_DATE' => $LANG['date'],
			'L_NBR' => $MEDIA_LANG['sort_popularity'],
			'L_NOTE' => $LANG['note'],
			'L_COM' => $LANG['com'],
			'L_DESC' => $LANG['desc'],
			'L_ASC' => $LANG['asc'],
			'L_ORDER_BY' => $LANG['orderby'],
			'L_CONFIRM_DELETE_FILE' => str_replace('\'', '\\\'', $MEDIA_LANG['confirm_delete_media']),
			'SELECTED_ALPHA' => $selected_fields['alpha'],
			'SELECTED_DATE' => $selected_fields['date'],
			'SELECTED_NBR' => $selected_fields['nbr'],
			'SELECTED_NOTE' => $selected_fields['note'],
			'SELECTED_COM' => $selected_fields['com'],
			'SELECTED_ASC' => $selected_fields['asc'],
			'SELECTED_DESC' => $selected_fields['desc'],
			'A_COM' => ($MEDIA_CATS[$id_cat]['active'] & MEDIA_DL_COM) !== 0,
			'A_NOTE' => ($MEDIA_CATS[$id_cat]['active'] & MEDIA_DL_NOTE) !== 0,
			'A_USER' => ($MEDIA_CATS[$id_cat]['active'] & MEDIA_DL_USER) !== 0,
			'A_COUNTER' => ($MEDIA_CATS[$id_cat]['active'] & MEDIA_DL_COUNT) !== 0,
			'A_DATE' => ($MEDIA_CATS[$id_cat]['active'] & MEDIA_DL_DATE) !== 0,
			'A_DESC' => ($MEDIA_CATS[$id_cat]['active'] & MEDIA_DL_DESC) !== 0,
			'A_BLOCK' => ($MEDIA_CATS[$id_cat]['active'] & (MEDIA_DL_DATE + MEDIA_DL_COUNT + MEDIA_DL_COM + MEDIA_DL_NOTE + MEDIA_DL_USER)) !== 0
		));

		//On crée une pagination si le nombre de fichiers est trop important.
		import('util/Pagination');
		$Pagination = new Pagination();

		//Notes
		import('content/note');
		import('content/comments');

		$Template->assign_vars(array(
			'PAGINATION' => $Pagination->display(url('media.php' . (!empty($unget) ? $unget . '&amp;' : '?') . 'cat=' . $id_cat . '&amp;p=%d', 'media-0-' . $id_cat . '-%d' . '+' . url_encode_rewrite($MEDIA_CATS[$id_cat]['name']) . '.php' . $unget), $MEDIA_CATS[$id_cat]['num_media'], 'p', $MEDIA_CONFIG['pagin'], 3),
			'C_FILES' => true,
			'TARGET_ON_CHANGE_ORDER' => $CONFIG['rewrite'] ? 'media-0-' . $id_cat . '.php?' : 'media.php?cat=' . $id_cat . '&'
		));

		$result = $Sql->query_while("SELECT v.id, v.iduser, v.name, v.timestamp, v.counter, v.note, v.nbrnote, v.nbr_com, v.infos, v.contents, mb.login, mb.level
			FROM " . PREFIX . "media AS v
			LEFT JOIN " . DB_TABLE_MEMBER . " AS mb ON v.iduser = mb.user_id
			WHERE idcat = '" . $id_cat . "' AND infos = '" . MEDIA_STATUS_APROBED . "'
			ORDER BY " . $sort . " " . $mode .
			$Sql->limit($Pagination->get_first_msg($MEDIA_CONFIG['pagin'], 'p'), $MEDIA_CONFIG['pagin']), __LINE__, __FILE__);

		while ($row = $Sql->fetch_assoc($result))
		{
			$Template->assign_block_vars('file', array(
				'NAME' => $row['name'],
				'IMG_NAME' => str_replace('"', '\"', $row['name']),
				'C_DESCRIPTION' => !empty($row['contents']),
				'DESCRIPTION' => second_parse($row['contents']),
				'POSTER' => !empty($row['login']) ? sprintf($MEDIA_LANG['media_added_by'], $row['login'], '../member/member' . url('.php?id=' . $row['iduser'], '-' . $row['iduser'] . '.php'), $level[$row['level']]) : $LANG['guest'],
				'DATE' => sprintf($MEDIA_LANG['add_on_date'], gmdate_format('date_format_short', $row['timestamp'])),
				'COUNT' => sprintf($MEDIA_LANG['view_n_times'], $row['counter']),
				'NOTE' => $row['nbrnote'] ? Note::display_img($row['note'], $MEDIA_CONFIG['note_max'], $MEDIA_CONFIG['note_max']) : '<em>' . $LANG['no_note'] . '</em>',
				'U_MEDIA_LINK' => url('media.php?id=' . $row['id'], 'media-' . $row['id'] . '-' . $id_cat . '+' . url_encode_rewrite($row['name']) . '.php'),
				'U_ADMIN_UNVISIBLE_MEDIA' => url('media_action.php?unvisible=' . $row['id'] . '&amp;token=' . $Session->get_token()),
				'U_ADMIN_EDIT_MEDIA' => url('media_action.php?edit=' . $row['id']),
				'U_ADMIN_DELETE_MEDIA' => url('media_action.php?del=' . $row['id'] . '&amp;token=' . $Session->get_token()),
				'U_COM_LINK' => Comments::com_display_link($row['nbr_com'], '../media/media' . url('.php?id=' . $row['id'] . '&amp;com=0', '-' . $row['id'] . '-' . $id_cat . '+' . url_encode_rewrite($row['name']) . '.php?com=0'), $row['id'], 'media')
			));
		}

		$Sql->query_close($result);
	}
	else
	{
		$Template->assign_vars(array(
			'L_NO_FILE_THIS_CATEGORY' => $MEDIA_LANG['none_media'],
			'C_NO_FILE' => true
		));
	}
}
// Display the media file.
elseif ($id_media > 0)
{
	$result = $Sql->query_while("SELECT v.*, mb.login, mb.level	FROM " . PREFIX . "media AS v LEFT JOIN " . DB_TABLE_MEMBER . " AS mb ON v.iduser = mb.user_id	WHERE id = '" . $id_media . "'", __LINE__, __FILE__);
	$media = $Sql->fetch_assoc($result);
	$Sql->query_close($result);
	
	if (empty($media) || ($media['infos'] & MEDIA_STATUS_UNVISIBLE) !== 0)
	{
		$Errorh->handler('e_unexist_media', E_USER_REDIRECT);
		exit;
	}
	elseif (!$User->check_auth($MEDIA_CATS[$media['idcat']]['auth'], MEDIA_AUTH_READ))
	{
		$Errorh->handler('e_auth', E_USER_REDIRECT);
		exit;
	}

	bread_crumb($media['idcat']);
	$Bread_crumb->add($media['name'], url('media.php?id=' . $id_media, 'media-' . $id_media . '-' . $media['idcat'] . '+' . url_encode_rewrite($media['name']) . '.php'));

	define('TITLE', $media['name']);
	require_once('../kernel/header.php');

	//MAJ du compteur.
	$Sql->query_inject("UPDATE " . LOW_PRIORITY . " " . PREFIX . "media SET counter = counter + 1 WHERE id = " . $id_media, __LINE__, __FILE__);

	//Affichage notation.
	import('content/note');
	$Note = new Note('media', $id_media, url('media.php?id=' . $id_media, 'media-' . $id_media . '-' . $media['idcat'] . '+' . url_encode_rewrite($media['name']) . '.php'), $MEDIA_CONFIG['note_max'], '', NOTE_NODISPLAY_NBRNOTES);
	
	import('content/comments');
	
	$Template->assign_vars(array(
		'C_DISPLAY_MEDIA' => true,
		'C_MODO' => $User->check_level(MODO_LEVEL),
		'ID_MEDIA' => $id_media,
		'NAME' => $media['name'],
		'CONTENTS' => second_parse($media['contents']),
		'COUNT' => $media['counter'],
		'THEME' => $CONFIG['theme'],
		'KERNEL_NOTATION' => $Note->display_form(),
		'HITS' => ((int)$media['counter']+1) > 1 ? sprintf($MEDIA_LANG['n_times'], ((int)$media['counter']+1)) : sprintf($MEDIA_LANG['n_time'], ((int)$media['counter']+1)),
		'NUM_NOTES' => (int)$media['nbrnote'] > 1 ? sprintf($MEDIA_LANG['num_notes'], (int)$media['nbrnote']) : sprintf($MEDIA_LANG['num_note'], (int)$media['nbrnote']),
		'LANG' => $CONFIG['lang'],
		'U_COM' => Comments::com_display_link($media['nbr_com'], '../media/media' . url('.php?id=' . $id_media . '&amp;com=0', '-' . $id_media . '-' . $media['idcat'] . '+' . url_encode_rewrite($media['name']) . '.php?com=0'), $id_media, 'media'),
		'L_DATE' => $LANG['date'],
		'L_SIZE' => $LANG['size'],
		'L_MEDIA_INFOS' => $MEDIA_LANG['media_infos'],
		'DATE' => gmdate_format('date_format', $media['timestamp']),
		'URL' => $media['url'],
		'MIME' => $media['mime_type'],
		'WIDTH' => $media['width'],
		'HEIGHT' => $media['height'],
		'HEIGHT_P' => $media['height'] + 50,
		'L_VIEWED' => $LANG['view'],
		'L_BY' => $LANG['by'],
		'BY' => !empty($media['login']) ? sprintf($MEDIA_LANG['media_added'], $media['login'], '../member/member' . url('.php?id=' . $media['iduser'], '-' . $media['iduser'] . '.php'), $level[$media['level']]) : $LANG['guest'],
		'L_CONFIRM_DELETE_MEDIA' => str_replace('\'', '\\\'', $MEDIA_LANG['confirm_delete_media']),
		'U_UNVISIBLE_MEDIA' => url('media_action.php?unvisible=' . $id_media . '&amp;token=' . $Session->get_token()),
		'U_EDIT_MEDIA' => url('media_action.php?edit=' . $id_media),
		'U_DELETE_MEDIA' => url('media_action.php?del=' . $id_media . '&amp;token=' . $Session->get_token()),
		'U_POPUP_MEDIA' => url('media_popup.php?id=' . $id_media),
		'C_DISPLAY' => (($MEDIA_CATS[$media['idcat']]['active'] & MEDIA_DV_DATE) !== 0 || ($MEDIA_CATS[$media['idcat']]['active'] & MEDIA_DV_USER) !== 0 || ($MEDIA_CATS[$media['idcat']]['active'] & MEDIA_DV_COUNT) !== 0 || ($MEDIA_CATS[$media['idcat']]['active'] & MEDIA_DV_NOTE) !== 0),
		'A_COM' => ($MEDIA_CATS[$media['idcat']]['active'] & MEDIA_DV_COM) !== 0,
		'A_NOTE' => ($MEDIA_CATS[$media['idcat']]['active'] & MEDIA_DV_NOTE) !== 0,
		'A_USER' => ($MEDIA_CATS[$media['idcat']]['active'] & MEDIA_DV_USER) !== 0,
		'A_COUNTER' => ($MEDIA_CATS[$media['idcat']]['active'] & MEDIA_DV_COUNT) !== 0,
		'A_DATE' => ($MEDIA_CATS[$media['idcat']]['active'] & MEDIA_DV_DATE) !== 0,
		'A_DESC' => ($MEDIA_CATS[$media['idcat']]['active'] & MEDIA_DV_DESC) !== 0,
		'L_CONFIRM_DELETE_FILE' => str_replace('\'', '\\\'', $MEDIA_LANG['confirm_delete_media'])
	));

	$Template->set_filenames(array('media_format' => (empty($mime_type_tpl[$media['mime_type']]) ? 'media/format/media_other.tpl' : 'media/' . $mime_type_tpl[$media['mime_type']])));

	//Affichage commentaires.
	if (isset($_GET['com']) && ($MEDIA_CATS[$media['idcat']]['active'] & (MEDIA_DV_COM + MEDIA_DL_COM)) !== 0)
	{
		$Template->assign_vars(array('COMMENTS' => display_comments('media', $id_media, url('media.php?id=' . $id_media . '&amp;com=%s', 'media-' . $id_media . '-' . $media['idcat'] . '+' . url_encode_rewrite($media['name']) . '.php?com=%s'))));
	}
}

$Template->pparse('media');

require_once('../kernel/footer.php');

?>