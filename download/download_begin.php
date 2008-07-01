<?php
/*##################################################
 *                              download_begin.php
 *                            -------------------
 *   begin                : October 18, 2007
 *   copyright          : (C) 2007 Viarre régis
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

if( defined('PHPBOOST') !== true)	
	exit;
	
load_module_lang('download'); //Chargement de la langue du module.
$Cache->Load_file('download');

require_once('download_auth.php');

$page = retrieve(GET, 'p', 1);
$category_id = retrieve(GET, 'cat', 0);
$file_id = retrieve(GET, 'id', 0);
$id_cat_for_download = 0;

if( !empty($file_id) )
{
	$download_info = $Sql->Query_array('download', '*', "WHERE visible = 1 AND id = '" . $file_id . "'", __LINE__, __FILE__);
	if( empty($download_info['id']) )
		$Errorh->Error_handler('e_unexist_file_download', E_USER_REDIRECT);
	$Bread_crumb->Add_link($download_info['title'], transid('download.php?cat=' . $file_id, 'download-' . $file_id . '+' . url_encode_rewrite($download_info['title']) . '.php'));
	$id_cat_for_download = $download_info['idcat'];
	define('TITLE', $DOWNLOAD_LANG['title_download'] . ' - ' . addslashes($download_info['title']));
}
elseif( !empty($category_id) )
{
	if( !array_key_exists($category_id, $DOWNLOAD_CATS) )
		$Errorh->Error_handler('e_unexist_category_download', E_USER_REDIRECT);
	
	$Bread_crumb->Add_link($DOWNLOAD_LANG['title_download'] . ' - ' . addslashes($DOWNLOAD_CATS[$category_id]['name']));
	$id_cat_for_download = $category_id;
}
else
	$Bread_crumb->Add_link($DOWNLOAD_LANG['title_download']);

$l_com_note = !empty($idurl) ? (!empty($get_note) ? $LANG['note'] : (!empty($_GET['i']) ? $LANG['com'] : '') ) : '';

$auth_read = $Member->Check_auth($CONFIG_DOWNLOAD['global_auth'], READ_CAT_DOWNLOAD);
$auth_write = $Member->Check_auth($CONFIG_DOWNLOAD['global_auth'], WRITE_CAT_DOWNLOAD);

//Bread_crumb : we read categories list recursively
while( $id_cat_for_download > 0 )
{
	$Bread_crumb->Add_link($DOWNLOAD_CATS[$id_cat_for_download]['name'], transid('download.php?cat=' . $id_cat_for_download, 'category-' . $id_cat_for_download . '+' . url_encode_rewrite($DOWNLOAD_CATS[$id_cat_for_download]['name']) . '.php'));
	if( !empty($DOWNLOAD_CATS[$id_cat_for_download]['auth']) )
	{
		//If we can't read a category, we can't read sub elements.
		$auth_read = $auth_read && $Member->Check_auth($DOWNLOAD_CATS[$id_cat_for_download]['auth'], READ_CAT_DOWNLOAD);
		$auth_write = $Member->Check_auth($DOWNLOAD_CATS[$id_cat_for_download]['auth'], WRITE_CAT_DOWNLOAD);
	}
	$id_cat_for_download = (int)$DOWNLOAD_CATS[$id_cat_for_download]['id_parent'];
}

$Bread_crumb->Add_link($DOWNLOAD_LANG['download'], transid('download.php'));

$Bread_crumb->Reverse_links();

if( !$auth_read )
	$Errorh->Error_handler('e_auth', E_USER_REDIRECT);

?>