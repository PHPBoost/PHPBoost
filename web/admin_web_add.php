<?php
/*##################################################
 *                               admin_web_add.php
 *                            -------------------
 *   begin                : July 11, 2005
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
###################################################*/

require_once('../includes/admin_begin.php');
load_module_lang('web'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../includes/admin_header.php');

if( !empty($_POST['valid']) )
{
	$title = !empty($_POST['name']) ? securit($_POST['name']) : '';
	$contents = !empty($_POST['contents']) ? parse($_POST['contents']) : '';
	$url = !empty($_POST['url']) ? securit($_POST['url']) : '';
	$idcat = !empty($_POST['idcat']) ? numeric($_POST['idcat']) : 0;
	$compt = isset($_POST['compt']) ? numeric($_POST['compt']) : 0;
	$aprob = isset($_POST['aprob']) ? numeric($_POST['aprob']) : 0;

	if( !empty($title) && !empty($url) && !empty($idcat) && isset($aprob) )
	{	
		$Sql->Query_inject("INSERT INTO ".PREFIX."web (idcat,title,contents,url,compt,aprob,timestamp,users_note,nbrnote,note,nbr_com) VALUES('" . $idcat . "', '" . $title . "', '" . $contents . "', '" . $url . "', '" . $compt . "', '" . $aprob . "', '" . time() . "', '0', '0', '0', '0')", __LINE__, __FILE__);
		
		redirect(HOST . DIR . '/web/admin_web.php');
	}
	else
		redirect(HOST . DIR . '/web/admin_web_add.php?error=incomplete#errorh');
}
elseif( !empty($_POST['previs']) )
{
	$Template->Set_filenames(array(
		'admin_web_add'=> 'web/admin_web_add.tpl'
	));

	$title = !empty($_POST['name']) ? trim($_POST['name']) : '';
	$contents = !empty($_POST['contents']) ? trim($_POST['contents']) : '';
	$url = !empty($_POST['url']) ? trim($_POST['url']) : '';
	$idcat = !empty($_POST['idcat']) ? numeric($_POST['idcat']) : 0;
	$compt = isset($_POST['compt']) ? numeric($_POST['compt']) : 0;
	$aprob = isset($_POST['aprob']) ? numeric($_POST['aprob']) : 0;
	
	$aprob_enable = ($aprob == 1) ? 'checked="checked"' : '';
	$aprob_disable = ($aprob == 0) ? 'checked="checked"' : '';

	$cat = $Sql->Query("SELECT name FROM ".PREFIX."web_cat WHERE id = '" . $idcat . "'", __LINE__, __FILE__);
	
	$Template->Assign_block_vars('web', array(
		'NAME' => stripslashes($title),
		'CONTENTS' => second_parse(stripslashes(parse($contents))),
		'URL' => stripslashes($url),
		'IDCAT' => $idcat,
		'CAT' => $cat,
		'COMPT' => $compt,
		'DATE' => gmdate_format('date_format_short'),
		'L_DESC' => $LANG['description'],
		'L_DATE' => $LANG['date'],
		'L_COM' => $LANG['com'],
		'L_VIEWS' => $LANG['views'],
		'L_NOTE' => $LANG['note'],
		'L_CAT' => $LANG['categorie'],
	));

	$Template->Assign_vars(array(
		'THEME' => $CONFIG['theme'],
		'LANG' => $CONFIG['lang'],
		'NAME' => stripslashes($title),
		'CONTENTS' => stripslashes($contents),
		'URL' => stripslashes($url),
		'IDCAT' => $idcat,
		'COMPT' => $compt,
		'CHECK_ENABLED' => $aprob_enable,
		'CHECK_DISABLED' => $aprob_disable,
		'L_NOTE' => $LANG['note'],
		'L_REQUIRE_NAME' => $LANG['require_title'],
		'L_REQUIRE_URL' => $LANG['require_url'],
		'L_REQUIRE_CAT' => $LANG['require_cat'],
		'L_WEB_ADD' => $LANG['web_add'],
		'L_WEB_MANAGEMENT' => $LANG['web_management'],
		'L_WEB_CAT' => $LANG['cat_management'],
		'L_WEB_CONFIG' => $LANG['web_config'],
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
	$result = $Sql->Query_while("SELECT id, name 
	FROM ".PREFIX."web_cat
	ORDER BY class", __LINE__, __FILE__);
	while( $row = $Sql->Sql_fetch_assoc($result) )
	{
		$selected = ($row['id'] == $idcat) ? ' selected="selected"' : '';
		$Template->Assign_block_vars('select', array(
			'CAT' => '<option value="' . $row['id'] . '"' . $selected . '>' . $row['name'] . '</option>'
		));
		$i++;
	}
	$Sql->Close($result);
	
	if( $i == 0 ) //Aucune catégorie => alerte.	 
		$Errorh->Error_handler($LANG['require_cat_create'], E_USER_WARNING);
	
	include_once('../includes/framework/content/bbcode.php');
	
	$Template->Pparse('admin_web_add'); 
}
else
{
	$Template->Set_filenames(array(
		'admin_web_add'=> 'web/admin_web_add.tpl'
	));
	
	$Template->Assign_vars(array(
		'COMPT' => '0',
		'CHECK_ENABLED' => 'checked="ckecked"',
		'CHECK_DISABLED' => '',
		'L_REQUIRE_NAME' => $LANG['require_title'],
		'L_REQUIRE_URL' => $LANG['require_url'],
		'L_REQUIRE_CAT' => $LANG['require_cat'],
		'L_WEB_ADD' => $LANG['web_add'],
		'L_WEB_MANAGEMENT' => $LANG['web_management'],
		'L_WEB_CAT' => $LANG['cat_management'],
		'L_WEB_CONFIG' => $LANG['web_config'],
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
	$result = $Sql->Query_while("SELECT id, name 
	FROM ".PREFIX."web_cat
	ORDER BY class", __LINE__, __FILE__);
	while( $row = $Sql->Sql_fetch_assoc($result) )
	{
		$Template->Assign_block_vars('select', array(
			'CAT' => '<option value="' . $row['id'] . '">' . $row['name'] . '</option>'
		));
		$i++;
	}
	$Sql->Close($result);
	
	//Gestion erreur.
	$get_error = !empty($_GET['error']) ? securit($_GET['error']) : '';
	if( $get_error == 'incomplete' )
		$Errorh->Error_handler($LANG['e_incomplete'], E_USER_NOTICE);
	elseif( $i == 0 ) //Aucune catégorie => alerte.	 
		$Errorh->Error_handler($LANG['require_cat_create'], E_USER_WARNING);
	
	include_once('../includes/framework/content/bbcode.php');
	
	$Template->Pparse('admin_web_add'); 
}

require_once('../includes/admin_footer.php');

?>