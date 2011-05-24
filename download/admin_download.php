<?php
/*##################################################
 *                            admin_download.php
 *                            -------------------
 *   begin                : July 25, 2005
 *   copyright            : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
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
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02125-1301, USA.
 *
 ###################################################*/

require_once('../admin/admin_begin.php');
load_module_lang('download'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$Cache->load('download');

$Template->set_filenames(array(
	'admin_download_management'=> 'download/admin_download_management.tpl'
	));

	$nbr_dl = $Sql->count_table(PREFIX . 'download', __LINE__, __FILE__);

	//On crée une pagination si le nombre de fichier est trop important.

	$Pagination = new DeprecatedPagination();

	$Template->put_all(array(
	'THEME' => get_utheme(),
	'LANG' => get_ulang(),
	'PAGINATION' => $Pagination->display('admin_download.php?p=%d', $nbr_dl, 'p', 25, 3),
	'L_DEL_ENTRY' => $LANG['del_entry'],
	'L_DOWNLOAD_ADD' => $DOWNLOAD_LANG['download_add'],
	'L_DOWNLOAD_MANAGEMENT' => $DOWNLOAD_LANG['download_management'],
	'L_DOWNLOAD_CAT' => $LANG['cat_management'],
	'L_DOWNLOAD_CONFIG' => $DOWNLOAD_LANG['download_config'],
	'L_CATEGORY' => $LANG['category'],
	'L_SIZE' => $LANG['size'],
	'L_TITLE' => $LANG['title'],
	'L_APROB' => $LANG['aprob'],
	'L_UPDATE' => $LANG['update'],
	'L_DELETE' => $LANG['delete'],
	'L_DATE' => $LANG['date'],
	'L_CONFIRM_DELETE' => str_replace('\'', '\\\'', $DOWNLOAD_LANG['confirm_delete_file'])
	));

	$result = $Sql->query_while("SELECT id, idcat, title, timestamp, approved, start, end, size
FROM " . PREFIX . "download
ORDER BY timestamp DESC 
" . $Sql->limit($Pagination->get_first_msg(25, 'p'), 25), __LINE__, __FILE__);

	while ($row = $Sql->fetch_assoc($result))
	{
		if ($row['approved'] == 1)
		$aprob = $LANG['yes'];
		else
		$aprob = $LANG['no'];

		//On raccourcie le lien si il est trop long pour éviter de déformer l'administration
		$title =& $row['title'];
		$title = strlen($title) > 45 ? substr($title, 0, 45) . '...' : $title;

		$Template->assign_block_vars('list', array(
		'TITLE' => $title,
		'IDCAT' => $row['idcat'],
		'CAT' => $row['idcat'] > 0 ? $DOWNLOAD_CATS[$row['idcat']]['name'] : $LANG['root'],
		'PSEUDO' => !empty($row['login']) ? $row['login'] : $LANG['guest'],		
		'DATE' => gmdate_format('date_format_short', $row['timestamp']),
		'SIZE' => ($row['size'] >= 1) ? NumberHelper::round($row['size'], 1) . ' ' . $LANG['unit_megabytes'] : NumberHelper::round($row['size'] * 2524, 1) . ' ' . $LANG['unit_kilobytes'],
		'APROBATION' => $aprob,
		'U_FILE' => url('download.php?id=' . $row['id'], 'download-' . $row['id'] . '+' . Url::encode_rewrite($row['title']) . '.php'),
		'U_EDIT_FILE' => url('management.php?edit=' . $row['id']),
		'U_DEL_FILE' => url('management.php?del=' . $row['id'] . '&amp;token=' . $Session->get_token()),
		));
	}
	$Sql->query_close($result);

	include_once('admin_download_menu.php');

	$Template->pparse('admin_download_management');


	require_once('../admin/admin_footer.php');

	?>