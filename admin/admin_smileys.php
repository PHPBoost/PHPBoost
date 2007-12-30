<?php
/*##################################################
 *                               admin_smileys.php
 *                            -------------------
 *   begin                : August 05, 2005
 *   copyright          : (C) 2005 Viarre Régis
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
###################################################*/

require_once('../includes/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../includes/admin_header.php');

$id_post = !empty($_POST['idsmiley']) ? numeric($_POST['idsmiley']) : '' ;
$id =!empty($_GET['id']) ? numeric($_GET['id']) : '' ;
$edit = !empty($_GET['edit']) ? true : false;
$del = !empty($_GET['del']) ? true : false;

if( !empty($_POST['valid']) && !empty($id_post) ) //Mise à jour.
{
	$url_smiley = !empty($_POST['url_smiley']) ? securit($_POST['url_smiley']) : '';
	$code_smiley = !empty($_POST['code_smiley']) ? securit($_POST['code_smiley']) : '';

	//On met à jour 
	if( !empty($url_smiley) && !empty($code_smiley) )
	{
		$sql->query_inject("UPDATE ".PREFIX."smileys SET url_smiley = '" . $url_smiley . "', code_smiley = '" . $code_smiley . "' WHERE idsmiley = '" . $id_post . "'", __LINE__, __FILE__);
					
		###### Régénération du cache des smileys #######
		$cache->generate_file('smileys');
		
		header('location:' . HOST . SCRIPT);	
		exit;		
	}
	else
	{
		header('location:' . HOST . DIR . '/admin/admin_smileys.php?id=' . $id_post . '&edit=1&error=incomplete#errorh');
		exit;
	}
}	
elseif( !empty($id) && $del ) //Suppression.
{
	//On supprime le smiley de la bdd.
	$sql->query_inject("DELETE FROM ".PREFIX."smileys WHERE idsmiley = '" . $id . "'", __LINE__, __FILE__);
	
	###### Régénération du cache des smileys #######	
	$cache->generate_file('smileys');
	
	header('location:' . HOST . SCRIPT); 
	exit;
}	
elseif( !empty($id) && $edit ) //Edition.
{
	$template->set_filenames(array(
		'admin_smileys_management2' => '../templates/' . $CONFIG['theme'] . '/admin/admin_smileys_management2.tpl'
	 ));

	$row = $sql->query_array('smileys', 'idsmiley', 'code_smiley', 'url_smiley', "WHERE idsmiley = '" . $id . "'", __LINE__, __FILE__);
	$url_smiley = $row['url_smiley'];
	
	//Gestion erreur.
	$get_error = !empty($_GET['error']) ? securit($_GET['error']) : '';
	if( $get_error == 'incomplete' )
		$errorh->error_handler($LANG['e_incomplete'], E_USER_NOTICE);
		
	$template->assign_vars(array(
		'IDSMILEY' => $row['idsmiley'],
		'URL_SMILEY' => $url_smiley,
		'CODE_SMILEY' => $row['code_smiley'],
		'IMG_SMILEY' => !empty($row['url_smiley']) ? '<img src="../images/smileys/' . $row['url_smiley'] . '" alt="" />' : '',
		'THEME' => $CONFIG['theme'],
		'L_REQUIRE_CODE' => $LANG['require_code'],
		'L_REQUIRE_URL' => $LANG['require_url'],
		'L_SMILEY_MANAGEMENT' => $LANG['smiley_management'],
		'L_ADD_SMILEY' => $LANG['add_smiley'],
		'L_EDIT_SMILEY' => $LANG['edit_smiley'],
		'L_REQUIRE' => $LANG['require'],
		'L_SMILEY_CODE' => $LANG['smiley_code'],
		'L_SMILEY_AVAILABLE' => $LANG['smiley_available'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset'],
	));	
	
	$result = $sql->query_while("SELECT url_smiley 
	FROM ".PREFIX."smileys", __LINE__, __FILE__);
	while( $row = $sql->sql_fetch_assoc($result) )
	{
		if( $row['url_smiley'] == $url_smiley )
			$selected = 'selected="selected"';
		else
			$selected = '';
		
		$template->assign_block_vars('select', array(
			'URL_SMILEY' => '<option value="' . $row['url_smiley'] . '" ' . $selected . '>' . $row['url_smiley'] . '</option>'
		));
	}
	$sql->close($result);
	
	$template->pparse('admin_smileys_management2');
}		
else
{			
	$template->set_filenames(array(
		'admin_smileys_management' => '../templates/' . $CONFIG['theme'] . '/admin/admin_smileys_management.tpl'
	));

	$template->assign_vars(array(
		'THEME' => $CONFIG['theme'],
		'LANG' => $CONFIG['lang'],
		'L_CONFIRM_DEL_SMILEY' => $LANG['confirm_del_smiley'],
		'L_SMILEY_MANAGEMENT' => $LANG['smiley_management'],
		'L_ADD_SMILEY' => $LANG['add_smiley'],
		'L_SMILEY' => $LANG['smiley'],
		'L_CODE' => $LANG['code'],
		'L_UPDATE' => $LANG['update'],
		'L_DELETE' => $LANG['delete'],
	));

	$result = $sql->query_while("SELECT * 
	FROM ".PREFIX."smileys", __LINE__, __FILE__);
	while( $row = $sql->sql_fetch_assoc($result) )
	{
		$template->assign_block_vars('list', array(
			'IDSMILEY' => $row['idsmiley'],
			'URL_SMILEY' => $row['url_smiley'],
			'CODE_SMILEY' => $row['code_smiley']
		));
	}
	$sql->close($result);
	
	$template->pparse('admin_smileys_management'); 
}

require_once('../includes/admin_footer.php');

?>