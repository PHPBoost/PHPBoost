<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 10 18
 * @since       PHPBoost 1.6 - 2007 09 30
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor janus57 <janus57@janus57.fr>
*/

if (defined('PHPBOOST') !== true)	exit;

// Categories (Display if category is known and the tree has to be reformed)
function display_cat_explorer($id, &$cats, $user_id, $display_select_link = 1)
{
	if ($id > 0)
	{
		$id_cat = $id;
		// Reform the categories tree to know witch category has to be open
		do
		{
			$id_cat = -1;
			try {
				$id_cat = PersistenceContext::get_querier()->get_column_value(DB_TABLE_UPLOAD_CAT, 'id_parent', 'WHERE id = :id_cat AND user_id = :user_id', array('id_cat' => $id_cat, 'user_id' => $user_id));
			} catch (RowNotFoundException $ex) {}

			if ($id_cat >= 0)
				$cats[] = $id_cat;
		}
		while ($id_cat > 0);
	}

	// Start from first
	$cats_list = '<ul class="upload-cat-list">' . show_cat_contents(0, $cats, $id, $display_select_link, $user_id) . '</ul>';

	// List of opened categories to send to the javascript function
	$opened_cats_list = '';
	foreach ($cats as $key => $row)
	{
		if ($key != 0)
			$opened_cats_list .= 'cat_status[' . $key . '] = 1;' . "\n";
	}
	return '<script>
	<!--
' . $opened_cats_list . '
	-->
	</script>
	' . $cats_list;

}

// Recursive function for the display of categories
function show_cat_contents($id_cat, $cats, $id, $display_select_link, $user_id)
{
	$line = '';
	$result = PersistenceContext::get_querier()->select("SELECT id, name
	FROM " . PREFIX . "upload_cat
	WHERE user_id = :user_id
	AND id_parent = :id_parent
	ORDER BY name", array(
		'user_id'   => $user_id,
		'id_parent' => $id_cat
	));
	while ($row = $result->fetch())
	{
		if (in_array($row['id'], $cats)) // If the category belongs to another, explore the parent
		{
			$line .= '<li><a href="javascript:show_cat_contents(' . $row['id'] . ', ' . ($display_select_link != 0 ? 1 : 0) . ');" class="far fa-minus-square" id="img2_' . $row['id'] . '"></a> <a href="javascript:show_cat_contents(' . $row['id'] . ', ' . ($display_select_link != 0 ? 1 : 0) . ');" class="fa fa-folder-open" id="img_' . $row['id'] . '"></a>&nbsp;<span id="class-' . $row['id'] . '" class="' . ($row['id'] == $id ? 'upload-selected-cat' : '') . '"><a href="javascript:' . ($display_select_link != 0 ? 'select_cat' : 'open_cat') . '(' . $row['id'] . ');">' . $row['name'] . '</a></span><span id="cat_' . $row['id'] . '">
			<ul class="upload-cat-explorer">'
			. show_cat_contents($row['id'], $cats, $id, $display_select_link, $user_id) . '</ul></span></li>';
		}
		else
		{
			// Count existing categries to make possible subfolder creation
			$sub_cats_number = PersistenceContext::get_querier()->count(DB_TABLE_UPLOAD_CAT, 'WHERE id_parent = :id', array(
				'id' => $row['id']
			));
			// If this category has subcategories, its content is visible
			if ($sub_cats_number > 0)
				$line .= '<li><a href="javascript:show_cat_contents(' . $row['id'] . ', ' . ($display_select_link != 0 ? 1 : 0) . ');" class="far fa-plus-square" id="img2_' . $row['id'] . '"></a> <a href="javascript:show_cat_contents(' . $row['id'] . ', ' . ($display_select_link != 0 ? 1 : 0) . ');" class="fa fa-folder" id="img_' . $row['id'] . '"></a>&nbsp;<span id="class-' . $row['id'] . '" class="' . ($row['id'] == $id ? 'upload-selected-cat' : '') . '"><a href="javascript:' . ($display_select_link != 0 ? 'select_cat' : 'open_cat') . '(' . $row['id'] . ');">' . $row['name'] . '</a></span><span id="cat_' . $row['id'] . '"></span></li>';
			else // If not, hide the "+"
				$line .= '<li class="upload-no-sub-cat"><i class="fa fa-folder"></i>&nbsp;<span id="class-' . $row['id'] . '" class="' . ($row['id'] == $id ? 'upload-selected-cat' : '') . '"><a href="javascript:' . ($display_select_link != 0 ? 'select_cat' : 'open_cat') . '(' . $row['id'] . ');">' . $row['name'] . '</a></span></li>';
		}
	}
	$result->dispose();
	return "\n" . $line;
}

// Function to define all sub-categories (recursive)
function upload_find_subcats(&$array, $id_cat, $user_id)
{
	$result = PersistenceContext::get_querier()->select("SELECT id
		FROM " . DB_TABLE_UPLOAD_CAT . "
		WHERE id_parent = :id_parent AND user_id = :user_id", array(
			'id_parent' => $id_cat,
			'user_id' => $user_id
		));
	while ($row = $result->fetch())
	{
		$array[] = $row['id'];
		// Callback the function for the child category
		upload_find_subcats($array, $row['id'], $user_id);
	}
	$result->dispose();
}

?>
