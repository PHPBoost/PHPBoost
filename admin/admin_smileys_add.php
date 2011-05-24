<?php
/*##################################################
 *                               admin_smileys_add.php
 *                            -------------------
 *   begin                : June 29, 2005
 *   copyright            : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *   Admin_theme_ajout, v 2.0.1
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


//Si c'est confirmé on execute
if (!empty($_POST['add']))
{
	$code_smiley = AppContext::get_request()->get_poststring('code_smiley', '');
	$url_smiley = AppContext::get_request()->get_poststring('url_smiley', '');

	if (!empty($code_smiley) && !empty($url_smiley))
	{
		$check_smiley = $Sql->query("SELECT COUNT(*) as compt FROM " . DB_TABLE_SMILEYS . " WHERE code_smiley = '" . $code_smiley . "'", __LINE__, __FILE__);
		if (empty($check_smiley))
		{
			$Sql->query_inject("INSERT INTO " . DB_TABLE_SMILEYS . " (code_smiley,url_smiley) VALUES('" . $code_smiley . "','" . $url_smiley . "')", __LINE__, __FILE__);

			###### Régénération du cache des smileys #######
			SmileysCache::invalidate();

			AppContext::get_response()->redirect('/admin/admin_smileys.php');
		}
		else
		AppContext::get_response()->redirect('/admin/admin_smileys_add.php?error=e_smiley_already_exist#message_helper');
	}
	else
	AppContext::get_response()->redirect('/admin/admin_smileys_add.php?error=incomplete#message_helper');
}
elseif (!empty($_FILES['upload_smiley']['name'])) //Upload et décompression de l'archive Zip/Tar
{
	//Si le dossier n'est pas en écriture on tente un CHMOD 777
	@clearstatcache();
	$dir = PATH_TO_ROOT .'/images/smileys/';
	if (!is_writable($dir))
	$is_writable = (@chmod($dir, 0777)) ? true : false;

	@clearstatcache();
	$error = '';
	if (is_writable($dir)) //Dossier en écriture, upload possible
	{
		$Upload = new Upload($dir);
		$Upload->disableContentCheck();
		if (!$Upload->file('upload_smiley', '`[a-z0-9_ -]+\.(jpg|gif|png|bmp)+$`i'))
		$error = $Upload->get_error();
	}
	else
	$error = 'e_upload_failed_unwritable';

	$error = !empty($error) ? '?error=' . $error : '';
	AppContext::get_response()->redirect(HOST . SCRIPT . $error);
}
else
{
	$template = new FileTemplate('admin/admin_smileys_add.tpl');

	//Gestion erreur.
	$get_error = TextHelper::strprotect(AppContext::get_request()->get_getstring('error', ''));
	$array_error = array('e_upload_invalid_format', 'e_upload_max_weight', 'e_upload_error', 'e_upload_php_code', 'e_upload_failed_unwritable', 'e_smiley_already_exist');
	if (in_array($get_error, $array_error))
	$template->put('message_helper', MessageHelper::display($LANG[$get_error], E_USER_WARNING));
	if ($get_error == 'incomplete')
	$template->put('message_helper', MessageHelper::display($LANG['e_incomplete'], E_USER_NOTICE));

	//On recupère les dossier des thèmes contenu dans le dossier images/smiley.

	$smileys_array = array();
	$smileys_folder_path = new Folder(PATH_TO_ROOT .'/images/smileys');
	foreach ($smileys_folder_path->get_files('`\.(png|jpg|bmp|gif)$`i') as $smileys)
	$smileys_array[] = $smileys->get_name();

	$result = $Sql->query_while("SELECT url_smiley
	FROM " . PREFIX . "smileys", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		//On recherche les clées correspondante à celles trouvée dans la bdd.
		$key = array_search($row['url_smiley'], $smileys_array);
		if ($key !== false)
		unset($smileys_array[$key]); //On supprime ces clées du tableau.
	}
	$Sql->query_close($result);

	$y = 0;
	$smiley_options = '<option value="" selected="selected">--</option>';
	foreach ($smileys_array as $smiley)
	$smiley_options .= '<option value="' . $smiley . '">' . $smiley . '</option>';

	$template->put_all(array(
		'SMILEY_OPTIONS' => $smiley_options,
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

	$template->display();
}

require_once('../admin/admin_footer.php');

?>