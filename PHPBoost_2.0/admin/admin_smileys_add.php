<?php
/*##################################################
 *                               admin_smileys_add.php
 *                            -------------------
 *   begin                : June 29, 2005
 *   copyright          : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *   Admin_theme_ajout, v 2.0.1 
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
###################################################*/

include_once('../includes/admin_begin.php');
define('TITLE', $LANG['administration']);
include_once('../includes/admin_header.php');

$error = !empty($_GET['error']) ? trim($_GET['error']) : '';

//Si c'est confirmé on execute
if( !empty($_POST['add']) )
{
	$code_smiley = !empty($_POST['code_smiley']) ? securit($_POST['code_smiley']) : '';
	$url_smiley = !empty($_POST['url_smiley']) ? securit($_POST['url_smiley']) : '';
	
	if( !empty($code_smiley) && !empty($url_smiley) )
	{
		$check_smiley = $sql->query("SELECT COUNT(*) as compt FROM ".PREFIX."smileys WHERE code_smiley = '" . $code_smiley . "'", __LINE__, __FILE__);
		if( empty($check_smiley) )
		{
			$sql->query_inject("INSERT INTO ".PREFIX."smileys (code_smiley,url_smiley) VALUES('" . $code_smiley . "','" . $url_smiley . "')", __LINE__, __FILE__);
		
			###### Régénération du cache des smileys #######	
			$cache->generate_file('smileys');	
		
			header('location:' . HOST . DIR . '/admin/admin_smileys.php');
			exit;
		}
		else
		{
			header('location:' . HOST . DIR . '/admin/admin_smileys_add.php?error=e_smiley_already_exist#errorh');
			exit;
		}
	}
	else
	{
		header('location:' . HOST . DIR . '/admin/admin_smileys_add.php?error=incomplete#errorh');
		exit;
	}
}
elseif( !empty($_FILES['upload_smiley']['name']) ) //Upload et décompression de l'archive Zip/Tar
{
	//Si le dossier n'est pas en écriture on tente un CHMOD 777
	@clearstatcache();
	$dir = '../images/smileys/';
	if( !is_writable($dir) )
		$is_writable = (@chmod($dir, 0777)) ? true : false;
	
	@clearstatcache();
	$error = '';
	if( is_writable($dir) ) //Dossier en écriture, upload possible
	{
		include_once('../includes/upload.class.php');
		$upload = new Upload($dir);
		if( !$upload->upload_file('upload_smiley', '`([a-z0-9_-])+\.(jpg|gif|png|bmp)+`i') )
			$error = $upload->error;
	}
	else
		$error = 'e_upload_failed_unwritable';
	
	$error = !empty($error) ? '?error=' . $error : '';
	header('location:' . HOST . SCRIPT . $error);	
	exit;
}
else
{
	$template->set_filenames(array(
		'admin_smileys_add' => '../templates/' . $CONFIG['theme'] . '/admin/admin_smileys_add.tpl'
	));
	
	$template->assign_vars(array(
		'THEME' => $CONFIG['theme'],
		'L_REQUIRE_CODE' => $LANG['require_code'],
		'L_REQUIRE_URL' => $LANG['require_url'],
		'L_ADD_SMILEY' => $LANG['add_smiley'],
		'L_REQUIRE' => $LANG['require'],
		'L_SMILEY_MANAGEMENT' => $LANG['smiley_management'],
		'L_ADD_SMILEY' => $LANG['add_smiley'],
		'L_UPLOAD_SMILEY' => $LANG['upload_smiley'],
		'L_EXPLAIN_UPLOAD_IMG' => $LANG['explain_upload_img'],
		'L_UPLOAD' => $LANG['upload'],
		'L_SMILEY_CODE' => $LANG['smiley_code'],
		'L_SMILEY_AVAILABLE' => $LANG['smiley_available'],
		'L_ADD' => $LANG['add'],
		'L_RESET' => $LANG['reset'],
	));

	//Gestion erreur.
	$get_error = !empty($_GET['error']) ? trim($_GET['error']) : '';
	$array_error = array('e_upload_invalid_format', 'e_upload_max_weight', 'e_upload_error', 'e_upload_failed_unwritable', 'e_smiley_already_exist');
	if( in_array($get_error, $array_error) )
		$errorh->error_handler($LANG[$get_error], E_USER_WARNING);
	if( $get_error == 'incomplete' )
		$errorh->error_handler($LANG['e_incomplete'], E_USER_NOTICE);
		
	//On recupère les dossier des thèmes contenu dans le dossier images/smiley.
	$rep = '../images/smileys';
	$j = 1;
	$y = 0;
	if ( is_dir($rep)) //Si le dossier existe
	{
		$dh = @opendir( $rep);
		while ( ! is_bool( $fichier = readdir( $dh ) ) )
		{	
			if( $j > 1 && $fichier != 'index.php' && $fichier != 'Thumbs.db' )
			{
				$fichier_array[] = $fichier; //On crée un array, avec les different fichiers.
			}
			$j++;
		}	
		closedir($dh); //On ferme le dossier

		if( is_array($fichier_array) )
		{			
			$result = $sql->query_while("SELECT url_smiley
			FROM ".PREFIX."smileys", __LINE__, __FILE__);
			while( $row = $sql->sql_fetch_assoc($result) )
			{
				//On recherche les clées correspondante à celles trouvée dans la bdd.
				$key = array_search($row['url_smiley'], $fichier_array);
				if( $key !== false)
					unset($fichier_array[$key]); //On supprime ces clées du tableau.
			}
			$sql->close($result);
			
			foreach($fichier_array as $smiley)
			{
				if( $y == 0)
				{
					$option = '<option value="" selected="selected">--</option>';
					$y++;
				}
				else
					$option = '<option value="' . $smiley . '">' . $smiley . '</option>';
				
				$template->assign_block_vars('select', array(
					'URL_SMILEY' => $option
				));
			}
		}
	}	
	
	$template->pparse('admin_smileys_add'); 
}

include_once('../includes/admin_footer.php');

?>