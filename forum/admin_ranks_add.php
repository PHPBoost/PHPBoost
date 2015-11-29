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
load_module_lang('forum'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$request = AppContext::get_request();

$add = $request->get_postvalue('add', false);

//Ajout du rang.
if ($add)
{
	$name = retrieve(POST, 'name', '');
	$msg = retrieve(POST, 'msg', 0);    
	$icon = retrieve(POST, 'icon', ''); 
	
	if (!empty($name) && $msg >= 0)
	{
		//On insere le nouveau lien, tout en précisant qu'il s'agit d'un lien ajouté et donc supprimable
		PersistenceContext::get_querier()->insert(PREFIX . "forum_ranks", array('name' => $name, 'msg' => $msg, 'icon' => $icon, 'special' => 0));
		
		###### Régénération du cache des rangs #######
		ForumRanksCache::invalidate();
		
		AppContext::get_response()->redirect('/forum/admin_ranks.php');	
	}
	else
		AppContext::get_response()->redirect('/forum/admin_ranks_add.php?error=incomplete#message_helper');
}
elseif (!empty($_FILES['upload_ranks']['name'])) //Upload
{
	//Si le dossier n'est pas en écriture on tente un CHMOD 777
	@clearstatcache();
	$dir = PATH_TO_ROOT . '/forum/templates/images/ranks/';
	if (!is_writable($dir))
		$is_writable = @chmod($dir, 0777);
	
	$error = '';
	if (is_writable($dir)) //Dossier en écriture, upload possible
	{
		$authorized_pictures_extensions = FileUploadConfig::load()->get_authorized_picture_extensions();
		
		if (!empty($authorized_pictures_extensions))
		{
			$Upload = new Upload($dir);
			$Upload->disableContentCheck();
			if (!$Upload->file('upload_ranks', '`([a-z0-9_ -])+\.(' . implode('|', array_map('preg_quote', $authorized_pictures_extensions)) . ')+$`i'))
				$error = $Upload->get_error();
		}
		else
			$error = 'e_upload_invalid_format';
	}
	else
		$error = 'e_upload_failed_unwritable';
	
	$error = !empty($error) ? '?error=' . $error : '';
	AppContext::get_response()->redirect(HOST . SCRIPT . $error);
}
else //Sinon on rempli le formulaire
{
	$template = new FileTemplate('forum/admin_ranks_add.tpl');

	//Gestion erreur.
	$get_error = retrieve(GET, 'error', '');
	$array_error = array('e_upload_invalid_format', 'e_upload_max_weight', 'e_upload_error', 'e_upload_php_code', 'e_upload_failed_unwritable');
	if (in_array($get_error, $array_error))
		$template->put('message_helper', MessageHelper::display($LANG[$get_error], MessageHelper::WARNING));
	if ($get_error == 'incomplete')
		$template->put('message_helper', MessageHelper::display($LANG['e_incomplete'], MessageHelper::NOTICE));
	
	//On recupère les images des groupes
	$rank_options = '<option value="">--</option>';
	
	
	$image_folder_path = new Folder(PATH_TO_ROOT . '/forum/templates/images/ranks/');
	foreach ($image_folder_path->get_files('`\.(png|jpg|bmp|gif)$`i') as $image)
	{
		$file = $image->get_name();
		$rank_options .= '<option value="' . $file . '">' . $file . '</option>';
	}
	
	$template->put_all(array(
		'RANK_OPTIONS' => $rank_options,
		'L_REQUIRE' => LangLoader::get_message('form.explain_required_fields', 'status-messages-common'),
		'L_REQUIRE_RANK_NAME' => $LANG['require_rank_name'],
		'L_REQUIRE_NBR_MSG_RANK' => $LANG['require_nbr_msg_rank'],
		'L_FORUM_MANAGEMENT' => $LANG['forum_management'],
		'L_CAT_MANAGEMENT' => $LANG['cat_management'],
		'L_ADD_CAT' => $LANG['cat_add'],
		'L_FORUM_RANKS_MANAGEMENT' => LangLoader::get_message('forum.ranks_management', 'common', 'forum'),
		'L_FORUM_ADD_RANKS' => LangLoader::get_message('forum.actions.add_rank', 'common', 'forum'),
		'L_UPLOAD_RANKS' => $LANG['upload_rank'],
		'L_UPLOAD_FORMAT' => $LANG['explain_upload_img'],
		'L_UPLOAD' => $LANG['upload'],
		'L_RANK_NAME' => $LANG['rank_name'],
		'L_NBR_MSG' => $LANG['nbr_msg'],
		'L_IMG_ASSOC' => $LANG['img_assoc'],
		'L_DELETE' => LangLoader::get_message('delete', 'common'),
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset'],
		'L_ADD' => LangLoader::get_message('add', 'common')
	));

	$template->display();
}

require_once('../admin/admin_footer.php');
?>