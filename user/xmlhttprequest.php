<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      ??
 * @version     PHPBoost 6.0 - last update: 2020 09 04
 * @since       PHPBoost 3.0
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

require_once('../kernel/begin.php');
AppContext::get_session()->no_session_location(); // Does not update the location of the visitor / member
require_once('../kernel/header_no_display.php');

$request = AppContext::get_request();

$id_cat = $request->get_postint('id_cat', 0);
$select_cat = $request->get_getvalue('select_cat', false);
$selected_cat = $request->get_postint('selected_cat', 0);
$display_select_link = $request->get_getint('display_select_link', 0);
$open_cat = $request->get_postint('open_cat', 0);
$root = $request->get_getvalue('root', false);


// Listing of directories whose parent is known
if ($id_cat != 0)
{
	echo '<ul class="upload-cat-explorer">';
	// Select directories whose parent id is known
	$result = PersistenceContext::get_querier()->select("SELECT id, id_parent, name
		FROM " . PREFIX . "upload_cat
		WHERE id_parent = :id
		ORDER BY name ASC", array(
			'id' => $id_cat
	));

	while ($row = $result->fetch())
	{
		// Count the number of existing categories to know if making a sub-folder is possible
		$sub_cats_number = PersistenceContext::get_querier()->count(DB_TABLE_UPLOAD_CAT, 'WHERE id_parent = :id_parent', array('id_parent' => $row['id']));
		// If this category has subcategories, its content is visible
		if ($sub_cats_number > 0)
			echo '<li><a href="javascript:show_cat_contents(' . $row['id'] . ', ' . ($display_select_link != 0 ? 1 : 0) . ');" class="far fa-plus-square" id="img2_' . $row['id'] . '"></a> <a href="javascript:show_cat_contents(' . $row['id'] . ', ' . ($display_select_link != 0 ? 1 : 0) . ');" class="fa fa-folder" id="img_' . $row['id'] . '"></a>&nbsp;<span id="class-' . $row['id'] . '" class=""><a href="javascript:' . ($display_select_link != 0 ? 'select_cat' : 'open_cat') . '(' . $row['id'] . ');">' . $row['name'] . '</a></span><span id="cat_' . $row['id'] . '"></span></li>';
		else // if not display the "+"
			echo '<li class="upload-no-sub-cat"><i class="fa fa-folder"></i>&nbsp;<span id="class-' . $row['id'] . '" class=""><a href="javascript:' . ($display_select_link != 0 ? 'select_cat' : 'open_cat') . '(' . $row['id'] . ');">' . $row['name'] . '</a></span></li>';
	}
	$result->dispose();
	echo '</ul>';
}
else
{
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}

require_once('../kernel/footer_no_display.php');
?>
