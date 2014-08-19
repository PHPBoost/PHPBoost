<?php
/*##################################################
 *                               admin_web_add.php
 *                            -------------------
 *   begin                : July 11, 2005
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
load_module_lang('web'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$editor = AppContext::get_content_formatting_service()->get_default_editor();
$editor->set_identifier('contents');

if (!empty($_POST['valid']))
{
	$title = retrieve(POST, 'name', '');
	$contents = retrieve(POST, 'contents', '', TSTRING_PARSE);
	$url = retrieve(POST, 'url', '');
	$idcat = retrieve(POST, 'idcat', 0);
	$compt = retrieve(POST, 'compt', 0);
	$aprob = retrieve(POST, 'aprob', 0);

	if (!empty($title) && !empty($url) && !empty($idcat) && isset($aprob))
	{	
		$Sql->query_inject("INSERT INTO " . PREFIX . "web (idcat,title,contents,url,compt,aprob,timestamp) VALUES('" . $idcat . "', '" . $title . "', '" . $contents . "', '" . $url . "', '" . $compt . "', '" . $aprob . "', '" . time() . "')");
		
		AppContext::get_response()->redirect('/web/admin_web.php');
	}
	else
		AppContext::get_response()->redirect('/web/admin_web_add.php?error=incomplete#message_helper');
}
elseif (!empty($_POST['previs']))
{
	$Template->set_filenames(array(
		'admin_web_add'=> 'web/admin_web_add.tpl'
	));

	$title = stripslashes(retrieve(POST, 'name', ''));
	$contents = stripslashes(retrieve(POST, 'contents', TSTRING_UNCHANGE));
	$previewed_contents = retrieve(POST, 'contents', '', TSTRING_PARSE);
	$url = retrieve(POST, 'url', '', TSTRING_UNCHANGE);
	$idcat = retrieve(POST, 'idcat', 0);
	$compt = retrieve(POST, 'compt', 0);
	$aprob = retrieve(POST, 'aprob', 0);
	
	$aprob_enable = ($aprob == 1) ? 'checked="checked"' : '';
	$aprob_disable = ($aprob == 0) ? 'checked="checked"' : '';

	$cat = $Sql->query("SELECT name FROM " . PREFIX . "web_cat WHERE id = '" . $idcat . "'");
	
	$Template->put_all(array(
		'C_PREVIEW' => true,
		'NAME' => $title,
		'KERNEL_EDITOR' => $editor->display(),
		'CONTENTS' => $contents,
		'PREVIEWED_CONTENTS' => FormatingHelper::second_parse(stripslashes($previewed_contents)),
		'URL' => $url,
		'IDCAT' => $idcat,
		'CAT' => $cat,
		'DATE' => gmdate_format('date_format_short'),
		'COMPT' => $compt,
		'CHECK_ENABLED' => $aprob_enable,
		'CHECK_DISABLED' => $aprob_disable,
		'L_DATE' => LangLoader::get_message('date', 'date-common'),
		'L_NOTE' => $LANG['note'],
		'L_REQUIRE_NAME' => $LANG['require_title'],
		'L_REQUIRE_URL' => $LANG['require_url'],
		'L_REQUIRE_CAT' => $LANG['require_cat'],
		'L_WEB_ADD' => $LANG['web_add'],
		'L_WEB_MANAGEMENT' => $LANG['web_management'],
		'L_WEB_CAT' => $LANG['cat_management'],
		'L_WEB_CONFIG' => $LANG['web_config'],
		'L_ADD_CAT' => $LANG['cat_add'],
		'L_REQUIRE' => $LANG['require'],
		'L_CATEGORY' => $LANG['categorie'],
		'L_NAME' => $LANG['name'],
		'L_URL_LINK' => $LANG['url'],
		'L_VIEWS' => $LANG['views'],
		'L_DESC' => $LANG['description'],
		'L_TIMES' => $LANG['n_time'],
		'L_VISIT' =>$LANG['visit_link'],
		'L_APROB' => $LANG['aprob'],
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_SUBMIT' => $LANG['submit'],
		'L_PREVIEW' => $LANG['preview'],
		'L_RESET' => $LANG['reset']
	));
	
	//Catégories.
	$i = 0;
	$result = $Sql->query_while("SELECT id, name 
	FROM " . PREFIX . "web_cat
	ORDER BY class");
	while ($row = $Sql->fetch_assoc($result))
	{
		$selected = ($row['id'] == $idcat) ? ' selected="selected"' : '';
		$Template->assign_block_vars('select', array(
			'CAT' => '<option value="' . $row['id'] . '"' . $selected . '>' . $row['name'] . '</option>'
		));
		$i++;
	}
	$result->dispose();
	
	if ($i == 0) //Aucune catégorie => alerte.	 
		$Template->put('message_helper', MessageHelper::display($LANG['require_cat_create'], MessageHelper::WARNING));
	
	$Template->pparse('admin_web_add'); 
}
else
{
	$Template->set_filenames(array(
		'admin_web_add'=> 'web/admin_web_add.tpl'
	));
	
	$Template->put_all(array(
		'COMPT' => '0',
		'CHECK_ENABLED' => 'checked="ckecked"',
		'CHECK_DISABLED' => '',
		'KERNEL_EDITOR' => $editor->display(),
		'L_REQUIRE_NAME' => $LANG['require_title'],
		'L_REQUIRE_URL' => $LANG['require_url'],
		'L_REQUIRE_CAT' => $LANG['require_cat'],
		'L_WEB_ADD' => $LANG['web_add'],
		'L_WEB_MANAGEMENT' => $LANG['web_management'],
		'L_WEB_CAT' => $LANG['cat_management'],
		'L_WEB_CONFIG' => $LANG['web_config'],
		'L_ADD_CAT' => $LANG['cat_add'],
		'L_REQUIRE' => $LANG['require'],
		'L_CATEGORY' => $LANG['categorie'],
		'L_NAME' => $LANG['name'],
		'L_URL_LINK' => $LANG['url'],
		'L_VIEWS' => $LANG['views'],
		'L_DESC' => $LANG['description'],
		'L_APROB' => $LANG['aprob'],
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_SUBMIT' => $LANG['submit'],
		'L_PREVIEW' => $LANG['preview'],
		'L_RESET' => $LANG['reset']
	));
	
	//Catégories.	
	$i = 0;
	$result = $Sql->query_while("SELECT id, name 
	FROM " . PREFIX . "web_cat
	ORDER BY class");
	while ($row = $Sql->fetch_assoc($result))
	{
		$Template->assign_block_vars('select', array(
			'CAT' => '<option value="' . $row['id'] . '">' . $row['name'] . '</option>'
		));
		$i++;
	}
	$result->dispose();
	
	//Gestion erreur.
	$get_error = retrieve(GET, 'error', '');
	if ($get_error == 'incomplete')
		$Template->put('message_helper', MessageHelper::display($LANG['e_incomplete'], MessageHelper::NOTICE));
	elseif ($i == 0) //Aucune catégorie => alerte.	 
		$Template->put('message_helper', MessageHelper::display($LANG['require_cat_create'], MessageHelper::WARNING));
	
	$Template->pparse('admin_web_add'); 
}

require_once('../admin/admin_footer.php');

?>