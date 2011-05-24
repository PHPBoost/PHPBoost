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

$id_post = AppContext::get_request()->get_postint('idsmiley', 0);
$id = AppContext::get_request()->get_getint('id', 0);
$edit = !empty($_GET['edit']) ? true : false;
$del = !empty($_GET['del']) ? true : false;

if (!empty($_POST['valid']) && !empty($id_post)) //Mise à jour.
{
	$url_smiley = TextHelper::strprotect(AppContext::get_request()->get_poststring('url_smiley', ''));
	$code_smiley = TextHelper::strprotect(AppContext::get_request()->get_poststring('code_smiley', ''));

	//On met à jour
	if (!empty($url_smiley) && !empty($code_smiley))
	{
		$Sql->query_inject("UPDATE " . DB_TABLE_SMILEYS . " SET url_smiley = '" . $url_smiley . "', code_smiley = '" . $code_smiley . "' WHERE idsmiley = '" . $id_post . "'", __LINE__, __FILE__);
			
		###### Régénération du cache des smileys #######
		SmileysCache::invalidate();

		AppContext::get_response()->redirect(HOST . REWRITED_SCRIPT);
	}
	else
	AppContext::get_response()->redirect('/admin/admin_smileys.php?id=' . $id_post . '&edit=1&error=incomplete#message_helper');
}
elseif (!empty($id) && $del) //Suppression.
{
	$Session->csrf_get_protect(); //Protection csrf

	//On supprime le smiley de la bdd.
	$Sql->query_inject("DELETE FROM " . DB_TABLE_SMILEYS . " WHERE idsmiley = '" . $id . "'", __LINE__, __FILE__);

	###### Régénération du cache des smileys #######
	SmileysCache::invalidate();

	AppContext::get_response()->redirect(HOST . REWRITED_SCRIPT);
}
elseif (!empty($id) && $edit) //Edition.
{
	$template = new FileTemplate('admin/admin_smileys_management2.tpl');

	$info_smiley = $Sql->query_array(DB_TABLE_SMILEYS, 'idsmiley', 'code_smiley', 'url_smiley', "WHERE idsmiley = '" . $id . "'", __LINE__, __FILE__);
	$url_smiley = $info_smiley['url_smiley'];

	//Gestion erreur.
	$get_error = TextHelper::strprotect(AppContext::get_request()->get_getstring('error', ''));
	if ($get_error == 'incomplete')
	$template->put('message_helper', MessageHelper::display($LANG['e_incomplete'], E_USER_NOTICE));

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

	$template->put_all(array(
		'IDSMILEY' => $info_smiley['idsmiley'],
		'URL_SMILEY' => $url_smiley,
		'CODE_SMILEY' => $info_smiley['code_smiley'],
		'IMG_SMILEY' => !empty($info_smiley['url_smiley']) ? '<img src="'. PATH_TO_ROOT .'/images/smileys/' . $info_smiley['url_smiley'] . '" alt="" />' : '',
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

	$template->display();
}
else
{
	$template = new FileTemplate('admin/admin_smileys_management.tpl');

	$template->put_all(array(
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

	$smileys_cache = SmileysCache::load()->get_smileys();
	foreach($smileys_cache as $code => $row)
	{
		$template->assign_block_vars('list', array(
			'IDSMILEY' => $row['idsmiley'],
			'URL_SMILEY' => $row['url_smiley'],
			'CODE_SMILEY' => $code
		));
	}

	$template->display();
}

require_once('../admin/admin_footer.php');

?>