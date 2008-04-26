<?php
/*##################################################
 *                               admin_forum_config.php
 *                            -------------------
 *   begin                : October 30, 2005
 *   copyright          : (C) 2005 Viarre Régis
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

require_once('../kernel/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../kernel/admin_header.php');

//Ajout du rang.
if( !empty($_POST['add']) )
{
	$name = !empty($_POST['name']) ? securit($_POST['name']) : '';
	$msg = !empty($_POST['msg']) ? numeric($_POST['msg']) : 0;    
	$icon = !empty($_POST['icon']) ? securit($_POST['icon']) : ''; 
	
	if( !empty($name) && $msg >= 0 )
	{	
		//On insere le nouveau lien, tout en précisant qu'il s'agit d'un lien ajouté et donc supprimable
		$Sql->Query_inject("INSERT INTO ".PREFIX."ranks (name,msg,icon,special) 
		VALUES('" . $name . "', '" . $msg . "', '" . $icon . "', '0')", __LINE__, __FILE__);	
				
		###### Régénération du cache des rangs #######
		$Cache->Generate_file('ranks');
		
		redirect(HOST . DIR . '/admin/admin_ranks.php');	
	}
	else
		redirect(HOST . DIR . '/admin/admin_ranks_add.php?error=incomplete#errorh');
}
elseif( !empty($_FILES['upload_ranks']['name']) ) //Upload et décompression de l'archive Zip/Tar
{
	//Si le dossier n'est pas en écriture on tente un CHMOD 777
	@clearstatcache();
	$dir = '/images/ranks/';
	if( !is_writable($dir) )
		$is_writable = (@chmod($dir, 0777)) ? true : false;
	
	@clearstatcache();
	$error = '';
	if( is_writable($dir) ) //Dossier en écriture, upload possible
	{
		include_once('../kernel/framework/files/upload.class.php');
		$Upload = new Upload($dir);
		if( !$Upload->Upload_file('upload_ranks', '`([a-z0-9_-])+\.(jpg|gif|png|bmp)+`i') )
			$error = $Upload->error;
	}
	else
		$error = 'e_upload_failed_unwritable';
	
	$error = !empty($error) ? '?error=' . $error : '';
	redirect(HOST . SCRIPT . $error);	
}
else //Sinon on rempli le formulaire	 
{	
	$Template->Set_filenames(array(
		'admin_ranks_add'=> 'admin/admin_ranks_add.tpl'
	));

	//Gestion erreur.
	$get_error = !empty($_GET['error']) ? trim($_GET['error']) : '';
	$array_error = array('e_upload_invalid_format', 'e_upload_max_weight', 'e_upload_error', 'e_upload_failed_unwritable');
	if( in_array($get_error, $array_error) )
		$Errorh->Error_handler($LANG[$get_error], E_USER_WARNING);
	if( $get_error == 'incomplete' )
		$Errorh->Error_handler($LANG['e_incomplete'], E_USER_NOTICE);
	
	//On recupère les images des groupes
	$rank_options = '<option value="">--</option>';
	
	$rep = '../templates/' . $CONFIG['theme']  . '/images/ranks';
	$j = 0;
	$array_files = array();
	if( is_dir($rep) ) //Si le dossier existe
	{
		$array_file = array();
		$dh = @opendir($rep);
		while( !is_bool($file = readdir($dh)) )
		{	
			if( $j > 1 && $file != 'index.php' && $file != 'Thumbs.db' )
				$rank_options .= '<option value="' . $file . '">' . $file . '</option>';
			$j++;
		}	
		closedir($dh); //On ferme le dossier
	}	
	
	$Template->Assign_vars(array(
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

	$Template->Pparse('admin_ranks_add');
}

require_once('../kernel/admin_footer.php');

?>