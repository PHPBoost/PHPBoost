<?php
/*##################################################
 *                               admin_forum_config.php
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
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

//Ajout du rang.
if (!empty($_POST['add']))
{
	$name = AppContext::get_request()->get_poststring('name', '');
	$msg = AppContext::get_request()->get_postint('msg', 0);    
	$icon = AppContext::get_request()->get_poststring('icon', ''); 
	$icon = AppContext::get_request()->get_poststring('icon', ''); 
	
	if (!empty($name) && $msg >= 0)
	{	
		//On insere le nouveau lien, tout en précisant qu'il s'agit d'un lien ajouté et donc supprimable
		$Sql->query_inject("INSERT INTO " . DB_TABLE_RANKS . " (name,msg,icon,special) 
		VALUES('" . $name . "', '" . $msg . "', '" . $icon . "', '0')", __LINE__, __FILE__);	
				
		###### Régénération du cache des rangs #######
		RanksCache::invalidate();
		
		AppContext::get_response()->redirect('/admin/admin_ranks.php');	
	}
	else
		AppContext::get_response()->redirect('/admin/admin_ranks_add.php?error=incomplete#message_helper');
}
elseif (!empty($_FILES['upload_ranks']['name'])) //Upload
{
	//Si le dossier n'est pas en écriture on tente un CHMOD 777
	@clearstatcache();
	$dir = PATH_TO_ROOT . '/templates/' . get_utheme()  . '/images/ranks/';
	if (!is_writable($dir))
		$is_writable = (@chmod($dir, 0777)) ? true : false;
	
	@clearstatcache();
	$error = '';
	if (is_writable($dir)) //Dossier en écriture, upload possible
	{
		
		$Upload = new Upload($dir);
		$Upload->disableContentCheck();
		if (!$Upload->file('upload_ranks', '`([a-z0-9_ -])+\.(jpg|gif|png|bmp)+$`i'))
			$error = $Upload->get_error();
	}
	else
		$error = 'e_upload_failed_unwritable';
	
	$error = !empty($error) ? '?error=' . $error : '';
	AppContext::get_response()->redirect(HOST . SCRIPT . $error);	
}
else //Sinon on rempli le formulaire	 
{	
	$template = new FileTemplate('admin/admin_ranks_add.tpl');

	//Gestion erreur.
	$get_error = AppContext::get_request()->get_getstring('error', '');
	$array_error = array('e_upload_invalid_format', 'e_upload_max_weight', 'e_upload_error', 'e_upload_php_code', 'e_upload_failed_unwritable');
	if (in_array($get_error, $array_error))
		$template->put('message_helper', MessageHelper::display($LANG[$get_error], E_USER_WARNING));
	if ($get_error == 'incomplete')
		$template->put('message_helper', MessageHelper::display($LANG['e_incomplete'], E_USER_NOTICE));
	
	//On recupère les images des groupes
	$rank_options = '<option value="">--</option>';
	
	
	$image_folder_path = new Folder(PATH_TO_ROOT . '/templates/' . get_utheme()  . '/images/ranks');
	foreach ($image_folder_path->get_files('`\.(png|jpg|bmp|gif)$`i') as $image)
	{
		$file = $image->get_name();
		$rank_options .= '<option value="' . PATH_TO_ROOT . '/templates/' . get_utheme()  . '/images/ranks/' . $file . '">' . $file . '</option>';
	}
	
	$template->put_all(array(
		'RANK_OPTIONS' => $rank_options,
		'L_REQUIRE_RANK_NAME' => $LANG['require_rank_name'],
		'L_REQUIRE_NBR_MSG_RANK' => $LANG['require_nbr_msg_rank'],
		'L_CONFIRM_DEL_RANK' => $LANG['confirm_del_rank'],
		'L_RANKS_MANAGEMENT' => $LANG['rank_management'],
		'L_ADD_RANKS' => $LANG['rank_add'],
		'L_UPLOAD_RANKS' => $LANG['upload_rank'],
		'L_UPLOAD_FORMAT' => $LANG['upload_rank_format'],
		'L_UPLOAD' => $LANG['upload'],
		'L_RANK_NAME' => $LANG['rank_name'],
		'L_NBR_MSG' => $LANG['nbr_msg'],
		'L_IMG_ASSOC' => $LANG['img_assoc'],
		'L_DELETE' => $LANG['delete'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset'],
		'L_ADD' => $LANG['add']
	));

	$template->display();
}

require_once('../admin/admin_footer.php');

?>