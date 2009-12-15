<?php
/*##################################################
 *                               admin_smileys.php
 *                            -------------------
 *   begin                : August 05, 2005
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
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 * 
###################################################*/

require_once('../admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$id_post = retrieve(POST, 'idsmiley', 0);
$id = retrieve(GET, 'id', 0);
$edit = !empty($_GET['edit']) ? true : false;
$del = !empty($_GET['del']) ? true : false;

if (!empty($_POST['valid']) && !empty($id_post)) //Mise à jour.
{
	$url_smiley = retrieve(POST, 'url_smiley', '');
	$code_smiley = retrieve(POST, 'code_smiley', '');

	//On met à jour
	if (!empty($url_smiley) && !empty($code_smiley))
	{
		$Sql->query_inject("UPDATE " . DB_TABLE_SMILEYS . " SET url_smiley = '" . $url_smiley . "', code_smiley = '" . $code_smiley . "' WHERE idsmiley = '" . $id_post . "'", __LINE__, __FILE__);
					
		###### Régénération du cache des smileys #######
		$Cache->Generate_file('smileys');
		
		redirect(HOST . SCRIPT);
	}
	else
		redirect('/admin/admin_smileys.php?id=' . $id_post . '&edit=1&error=incomplete#errorh');
}
elseif (!empty($id) && $del) //Suppression.
{
	$Session->csrf_get_protect(); //Protection csrf
	
	//On supprime le smiley de la bdd.
	$Sql->query_inject("DELETE FROM " . DB_TABLE_SMILEYS . " WHERE idsmiley = '" . $id . "'", __LINE__, __FILE__);
	
	###### Régénération du cache des smileys #######
	$Cache->Generate_file('smileys');
	
	redirect(HOST . SCRIPT);
}
elseif (!empty($id) && $edit) //Edition.
{
	$Template->set_filenames(array(
		'admin_smileys_management2'=> 'admin/admin_smileys_management2.tpl'
	));

	$info_smiley = $Sql->query_array(DB_TABLE_SMILEYS, 'idsmiley', 'code_smiley', 'url_smiley', "WHERE idsmiley = '" . $id . "'", __LINE__, __FILE__);
	$url_smiley = $info_smiley['url_smiley'];
	
	//Gestion erreur.
	$get_error = retrieve(GET, 'error', '');
	if ($get_error == 'incomplete')
		$Errorh->handler($LANG['e_incomplete'], E_USER_NOTICE);
		
	$smiley_options = '';
	$result = $Sql->query_while("SELECT url_smiley
	FROM " . PREFIX . "smileys", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		if ($row['url_smiley'] == $url_smiley)
			$selected = 'selected="selected"';
		else
			$selected = '';
		$smiley_options .= '<option value="' . $row['url_smiley'] . '" ' . $selected . '>' . $row['url_smiley'] . '</option>';
	}
	$Sql->query_close($result);

	$Template->assign_vars(array(
		'IDSMILEY' => $info_smiley['idsmiley'],
		'URL_SMILEY' => $url_smiley,
		'CODE_SMILEY' => $info_smiley['code_smiley'],
		'IMG_SMILEY' => !empty($info_smiley['url_smiley']) ? '<img src="../images/smileys/' . $info_smiley['url_smiley'] . '" alt="" />' : '',
		'SMILEY_OPTIONS' => $smiley_options,
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
	
	$Template->pparse('admin_smileys_management2');
}
else
{
	$Template->set_filenames(array(
		'admin_smileys_management'=> 'admin/admin_smileys_management.tpl'
	));

	$Template->assign_vars(array(
		'THEME' => get_utheme(),
		'LANG' => get_ulang(),
		'L_CONFIRM_DEL_SMILEY' => $LANG['confirm_del_smiley'],
		'L_SMILEY_MANAGEMENT' => $LANG['smiley_management'],
		'L_ADD_SMILEY' => $LANG['add_smiley'],
		'L_SMILEY' => $LANG['smiley'],
		'L_CODE' => $LANG['code'],
		'L_UPDATE' => $LANG['update'],
		'L_DELETE' => $LANG['delete'],
	));

	$result = $Sql->query_while("SELECT *
	FROM " . PREFIX . "smileys", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$Template->assign_block_vars('list', array(
			'IDSMILEY' => $row['idsmiley'],
			'URL_SMILEY' => $row['url_smiley'],
			'CODE_SMILEY' => $row['code_smiley']
		));
	}
	$Sql->query_close($result);
	
	$Template->pparse('admin_smileys_management');
}

require_once('../admin/admin_footer.php');

?>