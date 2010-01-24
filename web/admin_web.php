<?php
/*##################################################
 *                               admin_web.php
 *                            -------------------
 *   begin                : July 10, 2005
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

//On recupère les variables.
$id = retrieve(GET, 'id', 0);
$id_post = retrieve(POST, 'id', 0);
$del = !empty($_GET['delete']) ? true : false;

if (!empty($id) && !$del)
{
	$Template->set_filenames(array(
		'admin_web_management2'=> 'web/admin_web_management2.tpl'
	));

	$row = $Sql->query_array(PREFIX . 'web', '*', "WHERE id = '" . $id . "'", __LINE__, __FILE__);
	
	$aprob_enabled = ($row['aprob'] == 1) ? 'checked="checked"' : '';
	$aprob_disabled = ($row['aprob'] == 0) ? 'checked="checked"' : '';
	$idcat = $row['idcat'];
	
	$Template->assign_vars(array(
		'IDWEB' => $row['id'],
		'NAME' => $row['title'],
		'CONTENTS' => unparse($row['contents']),
		'URL' => $row['url'],
		'COMPT' => $row['compt'],
		'KERNEL_EDITOR' => display_editor(),
		'L_WEB_ADD' => $LANG['web_add'],
		'L_WEB_MANAGEMENT' => $LANG['web_management'],
		'L_WEB_CAT' => $LANG['cat_management'],
		'L_WEB_CONFIG' => $LANG['web_config'],
		'L_EDIT_LINK' => $LANG['edit_link'],
		'L_REQUIRE_NAME' => $LANG['require_title'],
		'L_REQUIRE_URL' => $LANG['require_url'],
		'L_REQUIRE_CAT' => $LANG['require_cat'],		
		'L_REQUIRE' => $LANG['require'],
		'L_CATEGORY' => $LANG['category'],
		'L_TITLE' => $LANG['title'],
		'L_URL_LINK' => $LANG['url'],
		'L_VIEWS' => $LANG['views'],
		'L_DESC' => $LANG['description'],
		'L_APROB' => $LANG['aprob'],
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_UPDATE' => $LANG['update'],
		'L_PREVIEW' => $LANG['preview'],
		'L_RESET' => $LANG['reset'],
		'APROB_ENABLED' => $aprob_enabled,
		'APROB_DISABLED' => $aprob_disabled
	));

	//Catégories.
	$i = 0;	
	$result = $Sql->query_while("SELECT id, name 
	FROM " . PREFIX . "web_cat", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$selected = ($row['id'] == $idcat) ? 'selected="selected"' : '';
		$Template->assign_block_vars('select', array(
			'CAT' => '<option value="' . $row['id'] . '" ' . $selected . '>' . $row['name'] . '</option>'
		));
		$i++;
	}
	$Sql->query_close($result);
	
	//Gestion erreur.
	$get_error = retrieve(GET, 'error', '');
	if ($get_error == 'incomplete')
		$Errorh->handler($LANG['e_incomplete'], E_USER_NOTICE);
	elseif ($i == 0) //Aucune catégorie => alerte.	 
		$Errorh->handler($LANG['require_cat_create'], E_USER_WARNING);	
	
	$Template->pparse('admin_web_management2'); 
}
elseif (!empty($_POST['previs']) && !empty($id_post))
{
	$Template->set_filenames(array(
		'admin_web_management'=> 'web/admin_web_management2.tpl'
	));

	$row = $Sql->query_array(PREFIX . 'web', '*', "WHERE id = '" . $id . "'", __LINE__, __FILE__);
	
	$title = retrieve(POST, 'name', '', TSTRING_UNCHANGE);
	$contents = retrieve(POST, 'contents', '', TSTRING_UNCHANGE);
	$url = retrieve(POST, 'url', '', TSTRING_UNCHANGE);
	$idcat = retrieve(POST, 'idcat', 0);
	$compt = retrieve(POST, 'compt', 0);
	$aprob = retrieve(POST, 'aprob', 0);
	
	$aprob_enable = ($aprob == 1) ? 'checked="checked"' : '';
	$aprob_disable = ($aprob == 0) ? 'checked="checked"' : '';

	$cat = $Sql->query("SELECT name FROM " . PREFIX . "web_cat WHERE id = '" . $idcat . "'", __LINE__, __FILE__);
	
	$Template->assign_block_vars('web', array(
		'NAME' => $title,
		'CONTENTS' => second_parse(stripslashes(strparse($contents))),
		'URL' => $url,
		'IDCAT' => $idcat,
		'CAT' => $cat,
		'COMPT' => $compt,
		'DATE' => gmdate_format('date_format_short'),
		'L_DESC' => $LANG['description'],
		'L_DATE' => $LANG['date'],
		'L_COM' => $LANG['com'],
		'L_VIEWS' => $LANG['views'],
		'L_NOTE' => $LANG['note'],
		'L_CATEGORY' => $LANG['categorie'],
	));

	$Template->assign_vars(array(
		'THEME' => get_utheme(),
		'LANG' => get_ulang(),
		'IDWEB' => $id_post,
		'TITLE' => $title,
		'KERNEL_EDITOR' => display_editor(),		
		'NAME' => $title,
		'CONTENTS' => $contents,
		'URL' => $url,
		'IDWEB' => $row['id'],
		'IDCAT' => $idcat,
		'COMPT' => $compt,
		'APROB_ENABLED' => $aprob_enable,
		'APROB_DISABLED' => $aprob_disable,
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
		'L_TITLE' => $LANG['title'],
		'L_URL_LINK' => $LANG['url'],
		'L_VIEWS' => $LANG['views'],
		'L_DESC' => $LANG['description'],
		'L_APROB' => $LANG['aprob'],
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_UPDATE' => $LANG['update'],
		'L_PREVIEW' => $LANG['preview'],
		'L_RESET' => $LANG['reset']
	));	
	
	//Catégories.	
	$i = 0;
	$result = $Sql->query_while("SELECT id, name 
	FROM " . PREFIX . "web_cat", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$selected = ($row['id'] == $idcat) ? ' selected="selected"' : '';
		$Template->assign_block_vars('select', array(
			'CAT' => '<option value="' . $row['id'] . '"' . $selected . '>' . $row['name'] . '</option>'
		));
		$i++;
	}
	$Sql->query_close($result);
	
	if ($i == 0) //Aucune catégorie => alerte.	 
		$Errorh->handler($LANG['require_cat_create'], E_USER_WARNING);
		
	$Template->pparse('admin_web_management'); 
}				
elseif (!empty($_POST['valid']) && !empty($id_post)) //inject
{
	$title = retrieve(POST, 'name', '');
	$contents = retrieve(POST, 'contents', '', TSTRING_PARSE);
	$url = retrieve(POST, 'url', '');
	$idcat = retrieve(POST, 'idcat', 0);
	$compt = retrieve(POST, 'compt', 0);
	$aprob = retrieve(POST, 'aprob', 0);

	if (!empty($title) && !empty($url) && !empty($idcat))
	{
		$Sql->query_inject("UPDATE " . PREFIX . "web SET title = '" . $title . "', contents = '" . $contents . "', url = '" . $url . "', idcat = '" . $idcat . "', compt = '" . $compt . "', aprob = '" . $aprob . "' WHERE id = '" . $id_post . "'", __LINE__, __FILE__);	
		
		AppContext::get_response()->redirect(HOST . SCRIPT);
	}
	else
		AppContext::get_response()->redirect('/web/admin_web.php?id= ' . $id_post . '&error=incomplete#errorh');
}
elseif ($del && !empty($id)) //Suppresion du lien web.
{
	$Session->csrf_get_protect(); //Protection csrf
	
	//On supprime dans la bdd.
	$Sql->query_inject("DELETE FROM " . PREFIX . "web WHERE id = '" . $id . "'", __LINE__, __FILE__);	

	//On supprimes les éventuels commentaires associés.
	$Sql->query_inject("DELETE FROM " . DB_TABLE_COM . " WHERE idprov = '" . $id . "' AND script = 'web'", __LINE__, __FILE__);
	
	AppContext::get_response()->redirect(HOST . SCRIPT);
}		
else
{			
	$Template->set_filenames(array(
		'admin_web_management'=> 'web/admin_web_management.tpl'
	));

	$nbr_web = $Sql->count_table(PREFIX . 'web', __LINE__, __FILE__);
	
	//On crée une pagination si le nombre de web est trop important.
	 
	$Pagination = new DeprecatedPagination();

	$Template->assign_vars(array(	
		'PAGINATION' => $Pagination->display('admin_web.php?p=%d', $nbr_web, 'p', 25, 3),	
		'THEME' => get_utheme(),
		'LANG' => get_ulang(),
		'KERNEL_EDITOR' => display_editor(),
		'L_WEB_ADD' => $LANG['web_add'],
		'L_WEB_MANAGEMENT' => $LANG['web_management'],
		'L_WEB_CAT' => $LANG['cat_management'],
		'L_WEB_CONFIG' => $LANG['web_config'],
		'L_DEL_ENTRY' => $LANG['delete_link'],
		'L_LISTE' => $LANG['list'],
		'L_NAME' => $LANG['name'],
		'L_CATEGORY' => $LANG['category'],
		'L_URL' => $LANG['url'],
		'L_VIEW' => $LANG['view'],
		'L_DATE' => $LANG['date'],
		'L_APROB' => $LANG['aprob'],
		'L_UPDATE' => $LANG['update'],
		'L_DELETE' => $LANG['delete'],
	));
		
	$result = $Sql->query_while("SELECT d.*, ad.name 
	FROM " . PREFIX . "web d 
	LEFT JOIN " . PREFIX . "web_cat ad ON ad.id = d.idcat
	ORDER BY timestamp DESC 
	" . $Sql->limit($Pagination->get_first_msg(25, 'p'), 25), __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$aprob = ($row['aprob'] == 1) ? $LANG['yes'] : $LANG['no'];
		//On reccourci le lien si il est trop long pour éviter de déformer l'administration.
		$title = $row['title'];
		$title = strlen($title) > 45 ? substr_html($title, 0, 45) . '...' : $title;

		$Template->assign_block_vars('web', array(
			'IDWEB' => $row['id'],
			'NAME' => $title,
			'IDCAT' => $row['idcat'],
			'CAT' => $row['name'],
			'DATE' => gmdate_format('date_format_short', $row['timestamp']),
			'APROBATION' => $aprob,
			'COMPT' => $row['compt']
		));	
	}
	$Sql->query_close($result);
	
	$Template->pparse('admin_web_management'); 
}

require_once('../admin/admin_footer.php');

?>