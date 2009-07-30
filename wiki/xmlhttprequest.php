<?php
/*##################################################
 *                                xmlhttprequest.php
 *                            -------------------
 *
 *  
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 * 
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

define('NO_SESSION_LOCATION', true); //Permet de ne pas mettre jour la page dans la session.
require_once('../kernel/begin.php');
require_once('../kernel/header_no_display.php');

$Template->set_filenames(array('wiki_explorer'=> 'wiki/explorer.tpl'));

$id_cat = retrieve(POST, 'id_cat', 0);
$select_cat = !empty($_GET['select_cat']) ? true : false;
$selected_cat = retrieve(POST, 'selected_cat', 0);
$display_select_link = !empty($_GET['display_select_link']) ? 1 : 0;
$open_cat = retrieve(POST, 'open_cat', 0);
$root = !empty($_GET['root']) ? 1 : 0;


//Listage des répertoires dont le répertoire parent est connu
if ($id_cat != 0)
{
	echo '<ul style="margin:0;padding:0;list-style-type:none;padding-left:30px;">';
	//On sélectionne les répetoires dont l'id parent est connu
	$result = $Sql->query_while("SELECT c.id, a.title, a.encoded_title
	FROM " . PREFIX . "wiki_cats c
	LEFT JOIN " . PREFIX . "wiki_articles a ON a.id = c.article_id
	WHERE c.id_parent = " . $id_cat . "
	ORDER BY title ASC", __LINE__, __FILE__);
	$nbr_subcats = $Sql->num_rows($result, "SELECT COUNT(*) FROM " . PREFIX . "wiki_cats WHERE id_parent = '" . $id_cat. "'", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		//On compte le nombre de catégories présentes pour savoir si on donne la possibilité de faire un sous dossier
		$sub_cats_number = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "wiki_cats WHERE id_parent = '" . $row['id'] . "'", __LINE__, __FILE__);
		//Si cette catégorie contient des sous catégories, on propose de voir son contenu
		if ($sub_cats_number > 0)
			echo '<li><a href="javascript:show_cat_contents(' . $row['id'] . ', ' . ($display_select_link != 0 ? 1 : 0) . ');"><img src="' . $Template->get_module_data_path('wiki') . '/images/plus.png" alt="" id="img2_' . $row['id'] . '" style="vertical-align:middle" /></a> <a href="javascript:show_cat_contents(' . $row['id'] . ', ' . ($display_select_link != 0 ? 1 : 0) . ');"><img src="' . $Template->get_module_data_path('wiki') . '/images/closed_cat.png" alt="" id="img_' . $row['id'] . '" style="vertical-align:middle" /></a>&nbsp;<span id="class_' . $row['id'] . '" class=""><a href="javascript:' . ($display_select_link != 0 ? 'select_cat' : 'open_cat') . '(' . $row['id'] . ');">' . $row['title'] . '</a></span><span id="cat_' . $row['id'] . '"></span></li>';
		else //Sinon on n'affiche pas le "+"
			echo '<li style="padding-left:17px;"><img src="' . $Template->get_module_data_path('wiki') . '/images/closed_cat.png" alt=""  style="vertical-align:middle" />&nbsp;<span id="class_' . $row['id'] . '" class=""><a href="javascript:' . ($display_select_link != 0 ? 'select_cat' : 'open_cat') . '(' . $row['id'] . ');">' . $row['title'] . '</a></span></li>';
	}
	$Sql->query_close($result);
	echo '</ul>';
}
//Retour de la localisation du dossier
elseif ($select_cat && empty($open_cat) && $root == 0)
{
	if ($selected_cat > 0)
	{
		$localisation = array();
		$Cache->load('wiki');
		$id = $selected_cat; //Permier id
		do
		{
			$localisation[] = $_WIKI_CATS[$id]['name'];
			$id = (int)$_WIKI_CATS[$id]['id_parent'];
		}	
		while ($id > 0);
		$localisation = array_reverse($localisation);
		echo implode(' / ', $localisation);
	}
	else
	{
		load_module_lang('wiki');
		echo $LANG['wiki_no_selected_cat'];
	}
}
elseif (!empty($open_cat) || $root == 1)
{
	$open_cat = $root == 1 ? 0 : $open_cat;
	$return = '<table style="width:100%;">';
	//Liste des catégories dans cette catégorie
	$Cache->load('wiki');
	foreach ($_WIKI_CATS as $key => $value)
	{
		if ($value['id_parent'] == $open_cat)
			$return .= '<tr><td class="row2"><img src="' . $Template->get_module_data_path('wiki') . '/images/closed_cat.png" alt=""  style="vertical-align:middle" />&nbsp;<a href="javascript:open_cat(' . $key . '); show_cat_contents(' . $value['id_parent'] . ', 0);">' . $value['name'] . '</a></td></tr>';
	}
	$result = $Sql->query_while("SELECT title, id, encoded_title
	FROM " . PREFIX . "wiki_articles a
	WHERE id_cat = '" . $open_cat . "'
	AND a.redirect = 0
	ORDER BY is_cat DESC, title ASC", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$return .= '<tr><td class="row2"><img src="' . $Template->get_module_data_path('wiki') . '/images/article.png" alt=""  style="vertical-align:middle" />&nbsp;<a href="' . url('wiki.php?title=' . $row['encoded_title'], $row['encoded_title']) . '">' . $row['title'] . '</a></td></tr>';
	}
	$Sql->query_close($result);
	$return .= '</table>';
	echo $return;
}


require_once('../kernel/footer_no_display.php');
?>