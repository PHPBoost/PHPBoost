<?php
/*##################################################
 *                              download_begin.php
 *                            -------------------
 *   begin                : October 18, 2007
 *   copyright            : (C) 2007 Viarre régis
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
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

if (defined('PHPBOOST') !== true)	
	exit;

load_module_lang('download'); //Chargement de la langue du module.
$Cache->load('download');

require_once('download_auth.php');

$page = retrieve(GET, 'p', 1);
$category_id = retrieve(GET, 'cat', 0);
$file_id = retrieve(GET, 'id', 0);
$id_cat_for_download = 0;

if (!empty($file_id))
{
	$download_info = $Sql->query_array(PREFIX . 'download', '*', "WHERE visible = 1 AND approved = 1 AND id = '" . $file_id . "'", __LINE__, __FILE__);
	
	if (empty($download_info['id']))
		$Errorh->handler('e_unexist_file_download', E_USER_REDIRECT);
	$Bread_crumb->add($download_info['title'], url('download.php?id=' . $file_id, 'download-' . $file_id . '+' . Url::encode_rewrite($download_info['title']) . '.php'));
	$id_cat_for_download = $download_info['idcat'];
	define('TITLE', $DOWNLOAD_LANG['title_download'] . ' - ' . $download_info['title']);
}
elseif (!empty($category_id))
{
	if (!array_key_exists($category_id, $DOWNLOAD_CATS))
		$Errorh->handler('e_unexist_category_download', E_USER_REDIRECT);
	
	$Bread_crumb->add($DOWNLOAD_LANG['title_download'] . ' - ' . $DOWNLOAD_CATS[$category_id]['name']);
	$id_cat_for_download = $category_id;
	define('TITLE', $DOWNLOAD_LANG['title_download'] . ' - ' . $DOWNLOAD_CATS[$category_id]['name']);
}
else
	define('TITLE', $DOWNLOAD_LANG['title_download']);

$l_com_note = !empty($idurl) ? (!empty($get_note) ? $LANG['note'] : (!empty($_GET['i']) ? $LANG['com'] : '') ) : '';

$auth_read = $User->check_auth($CONFIG_DOWNLOAD['global_auth'], DOWNLOAD_READ_CAT_AUTH_BIT);
$auth_write = $User->check_auth($CONFIG_DOWNLOAD['global_auth'], DOWNLOAD_WRITE_CAT_AUTH_BIT);
$auth_contribution = $User->check_auth($CONFIG_DOWNLOAD['global_auth'], DOWNLOAD_CONTRIBUTION_CAT_AUTH_BIT);

//Bread_crumb : we read categories list recursively
while ($id_cat_for_download > 0)
{
	$Bread_crumb->add($DOWNLOAD_CATS[$id_cat_for_download]['name'], url('download.php?cat=' . $id_cat_for_download, 'category-' . $id_cat_for_download . '+' . Url::encode_rewrite($DOWNLOAD_CATS[$id_cat_for_download]['name']) . '.php'));
	$auth_read = $auth_read && $DOWNLOAD_CATS[$id_cat_for_download]['visible'];
	if (!empty($DOWNLOAD_CATS[$id_cat_for_download]['auth']))
	{
		//If we can't read a category, we can't read sub elements.
		$auth_read = $auth_read && $User->check_auth($DOWNLOAD_CATS[$id_cat_for_download]['auth'], DOWNLOAD_READ_CAT_AUTH_BIT);
		$auth_write = $User->check_auth($DOWNLOAD_CATS[$id_cat_for_download]['auth'], DOWNLOAD_WRITE_CAT_AUTH_BIT);
		$auth_contribution = $User->check_auth($DOWNLOAD_CATS[$id_cat_for_download]['auth'], DOWNLOAD_CONTRIBUTION_CAT_AUTH_BIT);
	}
	$id_cat_for_download = (int)$DOWNLOAD_CATS[$id_cat_for_download]['id_parent'];
}

$Bread_crumb->add($DOWNLOAD_LANG['download'], url('download.php'));

$Bread_crumb->reverse();

if (!$auth_read)
{
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}

?>
