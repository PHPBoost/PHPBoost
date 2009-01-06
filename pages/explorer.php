<?php
/*##################################################
 *                               explorer.php
 *                            -------------------
 *   begin                : August 13, 2007
 *   copyright          : (C) 2007 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
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
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

require_once('../kernel/begin.php'); 
require_once('../pages/pages_begin.php'); 
define('TITLE', $LANG['pages'] . ' : ' . $LANG['pages_explorer']);
$cat = retrieve(GET, 'cat', 0);
$Bread_crumb->add($LANG['pages'], url('pages.php'));
$Bread_crumb->add($LANG['pages_explorer'], url('explorer.php'));
require_once('../kernel/header.php');

$Template->set_filenames(array('pages_explorer'=> 'pages/explorer.tpl'));

//Liste des dossiers de la racine
$root = '';
foreach ($_PAGES_CATS as $key => $value)
{
	if ($value['id_parent'] == 0)
	{
		//Autorisation particulière ?
		$special_auth = !empty($value['auth']);
		//Vérification de l'autorisation d'éditer la page
		if (($special_auth && $User->check_auth($value['auth'], READ_PAGE)) || (!$special_auth && $User->check_auth($_PAGES_CONFIG['auth'], READ_PAGE)))
		{
			$root .= '<tr><td class="row2"><img src="' . $Template->get_module_data_path('pages') . '/images/closed_cat.png" alt="" style="vertical-align:middle" />&nbsp;<a href="javascript:open_cat(' . $key . '); show_cat_contents(' . $value['id_parent'] . ', 0);">' . $value['name'] . '</a></td></tr>';
		}
	}
}
//Liste des fichiers de la racine
$result = $Sql->query_while("SELECT title, id, encoded_title, auth
	FROM " . PREFIX . "pages
	WHERE id_cat = 0 AND is_cat = 0
	ORDER BY is_cat DESC, title ASC", __LINE__, __FILE__);
while ($row = $Sql->fetch_assoc($result))
{
	//Autorisation particulière ?
	$special_auth = !empty($row['auth']);
	$array_auth = unserialize($row['auth']);
	//Vérification de l'autorisation d'éditer la page
	if (($special_auth && $User->check_auth($array_auth, READ_PAGE)) || (!$special_auth && $User->check_auth($_PAGES_CONFIG['auth'], READ_PAGE)))
	{
		$root .= '<tr><td class="row2"><img src="' . $Template->get_module_data_path('pages') . '/images/page.png" alt=""  style="vertical-align:middle" />&nbsp;<a href="' . url('pages.php?title=' . $row['encoded_title'], $row['encoded_title']) . '">' . $row['title'] . '</a></td></tr>';
	}
}
$Sql->query_close($result);

$Template->assign_vars(array(
	'PAGES_PATH' => $Template->get_module_data_path('pages'),
	'TITLE' => $LANG['pages_explorer'],
	'L_ROOT' => $LANG['pages_root'],
	'SELECTED_CAT' => $cat > 0 ? $cat : 0,
	'ROOT_CONTENTS' => $root,
	'L_CATS' => $LANG['pages_cats_tree']
));

$contents = '';
$result = $Sql->query_while("SELECT c.id, p.title, p.encoded_title
FROM " . PREFIX . "pages_cats c
LEFT JOIN " . PREFIX . "pages p ON p.id = c.id_page
WHERE c.id_parent = 0
ORDER BY p.title ASC", __LINE__, __FILE__);
while ($row = $Sql->fetch_assoc($result))
{
	$sub_cats_number = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "pages_cats WHERE id_parent = '" . $row['id'] . "'", __LINE__, __FILE__);
	if ($sub_cats_number > 0)
	{	
		$Template->assign_block_vars('list', array(
			'DIRECTORY' => '<li><a href="javascript:show_cat_contents(' . $row['id'] . ', 0);"><img src="' . $Template->get_module_data_path('pages') . '/images/plus.png" alt="" id="img2_' . $row['id'] . '"  style="vertical-align:middle" /></a> 
			<a href="javascript:show_cat_contents(' . $row['id'] . ', 0);"><img src="' . $Template->get_module_data_path('pages') . '/images/closed_cat.png" id ="img_' . $row['id'] . '" alt="" style="vertical-align:middle" /></a>&nbsp;<span id="class_' . $row['id'] . '" class=""><a href="javascript:open_cat(' . $row['id'] . ');">' . $row['title'] . '</a></span><span id="cat_' . $row['id'] . '"></span></li>'
		));
	}
	else
	{
		$Template->assign_block_vars('list', array(
			'DIRECTORY' => '<li style="padding-left:17px;"><img src="' . $Template->get_module_data_path('pages') . '/images/closed_cat.png" alt=""  style="vertical-align:middle" />&nbsp;<span id="class_' . $row['id'] . '" class=""><a href="javascript:open_cat(' . $row['id'] . ');">' . $row['title'] . '</a></span><span id="cat_' . $row['id'] . '"></span></li>'
		));
	}
}
$Sql->query_close($result);
$Template->assign_vars(array(
	'SELECTED_CAT' => 0,
	'CAT_0' => 'pages_selected_cat',
	'CAT_LIST' => ''
));

$Template->pparse('pages_explorer');


require_once('../kernel/footer.php');

?>