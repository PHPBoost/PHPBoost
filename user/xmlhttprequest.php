<?php
/*##################################################
 *                                xmlhttprequest.php
 *                            -------------------
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
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

require_once('../kernel/begin.php');
AppContext::get_session()->no_session_location(); //Ne réactualise pas l'emplacement du visiteur/membre
require_once('../kernel/header_no_display.php');

$request = AppContext::get_request();

$id_cat = retrieve(POST, 'id_cat', 0);
$select_cat = $request->get_getvalue('select_cat', false);
$selected_cat = retrieve(POST, 'selected_cat', 0);
$display_select_link = $request->get_getint('display_select_link', 0);
$open_cat = retrieve(POST, 'open_cat', 0);
$root = $request->get_getvalue('root', false);


//Listage des répertoires dont le répertoire parent est connu
if ($id_cat != 0)
{
	echo '<ul class="upload-cat-explorer">';
	//On sélectionne les répertoires dont l'id parent est connu
	$result = PersistenceContext::get_querier()->select("SELECT id, id_parent, name
		FROM " . PREFIX . "upload_cat
		WHERE id_parent = :id
		ORDER BY name ASC", array(
			'id' => $id_cat
	));
	
	while ($row = $result->fetch())
	{
		//On compte le nombre de catégories présentes pour savoir si on donne la possibilité de faire un sous dossier
		$sub_cats_number = PersistenceContext::get_querier()->count(DB_TABLE_UPLOAD_CAT, 'WHERE id_parent = :id_parent', array('id_parent' => $row['id']));
		//Si cette catégorie contient des sous catégories, on propose de voir son contenu
		if ($sub_cats_number > 0)
			echo '<li><a href="javascript:show_cat_contents(' . $row['id'] . ', ' . ($display_select_link != 0 ? 1 : 0) . ');" class="fa fa-plus-square-o" id="img2_' . $row['id'] . '"></a> <a href="javascript:show_cat_contents(' . $row['id'] . ', ' . ($display_select_link != 0 ? 1 : 0) . ');" class="fa fa-folder" id="img_' . $row['id'] . '"></a>&nbsp;<span id="class_' . $row['id'] . '" class=""><a href="javascript:' . ($display_select_link != 0 ? 'select_cat' : 'open_cat') . '(' . $row['id'] . ');">' . $row['name'] . '</a></span><span id="cat_' . $row['id'] . '"></span></li>';
		else //Sinon on n'affiche pas le "+"
			echo '<li class="upload-no-sub-cat"><i class="fa fa-folder"></i>&nbsp;<span id="class_' . $row['id'] . '" class=""><a href="javascript:' . ($display_select_link != 0 ? 'select_cat' : 'open_cat') . '(' . $row['id'] . ');">' . $row['name'] . '</a></span></li>';
	}
	$result->dispose();
	echo '</ul>';
}

require_once('../kernel/footer_no_display.php');
?>