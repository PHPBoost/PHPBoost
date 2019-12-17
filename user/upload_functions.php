<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 05 05
 * @since       PHPBoost 1.6 - 2007 09 30
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

if (defined('PHPBOOST') !== true)	exit;

//Catégories (affichage si on connait la catégorie et qu'on veut reformer l'arborescence)
function display_cat_explorer($id, &$cats, $display_select_link = 1, $user_id)
{
	if ($id > 0)
	{
		$id_cat = $id;
		//On remonte l'arborescence des catégories afin de savoir quelle catégorie développer
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

	//Maintenant qu'on connait l'arborescence on part du début
	$cats_list = '<ul class="upload-cat-list">' . show_cat_contents(0, $cats, $id, $display_select_link, $user_id) . '</ul>';

	//On liste les catégories ouvertes pour la fonction javascript
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

//Fonction récursive pour l'affichage des catégories
function show_cat_contents($id_cat, $cats, $id, $display_select_link, $user_id)
{
	$line = '';
	$result = PersistenceContext::get_querier()->select("SELECT id, name
	FROM " . PREFIX . "upload_cat
	WHERE user_id = :user_id
	AND id_parent = :id_parent
	ORDER BY name", array(
		'user_id' => $user_id,
		'id_parent' => $id_cat
	));
	while ($row = $result->fetch())
	{
		if (in_array($row['id'], $cats)) //Si cette catégorie contient notre catégorie, on l'explore
		{
			$line .= '<li><a href="javascript:show_cat_contents(' . $row['id'] . ', ' . ($display_select_link != 0 ? 1 : 0) . ');" class="far fa-minus-square" id="img2_' . $row['id'] . '"></a> <a href="javascript:show_cat_contents(' . $row['id'] . ', ' . ($display_select_link != 0 ? 1 : 0) . ');" class="fa fa-folder-open" id="img_' . $row['id'] . '"></a>&nbsp;<span id="class-' . $row['id'] . '" class="' . ($row['id'] == $id ? 'upload-selected-cat' : '') . '"><a href="javascript:' . ($display_select_link != 0 ? 'select_cat' : 'open_cat') . '(' . $row['id'] . ');">' . $row['name'] . '</a></span><span id="cat_' . $row['id'] . '">
			<ul class="upload-cat-explorer">'
			. show_cat_contents($row['id'], $cats, $id, $display_select_link, $user_id) . '</ul></span></li>';
		}
		else
		{
			//On compte le nombre de catégories présentes pour savoir si on donne la possibilité de faire un sous dossier
			$sub_cats_number = PersistenceContext::get_querier()->count(DB_TABLE_UPLOAD_CAT, 'WHERE id_parent = :id', array(
				'id' => $row['id']
			));
			//Si cette catégorie contient des sous catégories, on propose de voir son contenu
			if ($sub_cats_number > 0)
				$line .= '<li><a href="javascript:show_cat_contents(' . $row['id'] . ', ' . ($display_select_link != 0 ? 1 : 0) . ');" class="far fa-plus-square" id="img2_' . $row['id'] . '"></a> <a href="javascript:show_cat_contents(' . $row['id'] . ', ' . ($display_select_link != 0 ? 1 : 0) . ');" class="fa fa-folder" id="img_' . $row['id'] . '"></a>&nbsp;<span id="class-' . $row['id'] . '" class="' . ($row['id'] == $id ? 'upload-selected-cat' : '') . '"><a href="javascript:' . ($display_select_link != 0 ? 'select_cat' : 'open_cat') . '(' . $row['id'] . ');">' . $row['name'] . '</a></span><span id="cat_' . $row['id'] . '"></span></li>';
			else //Sinon on n'affiche pas le "+"
				$line .= '<li class="upload-no-sub-cat"><i class="fa fa-folder"></i>&nbsp;<span id="class-' . $row['id'] . '" class="' . ($row['id'] == $id ? 'upload-selected-cat' : '') . '"><a href="javascript:' . ($display_select_link != 0 ? 'select_cat' : 'open_cat') . '(' . $row['id'] . ');">' . $row['name'] . '</a></span></li>';
		}
	}
	$result->dispose();
	return "\n" . $line;
}

//Fonction qui détermine toutes les sous-catégories d'une catégorie (récursive)
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
		//On rappelle la fonction pour la catégorie fille
		upload_find_subcats($array, $row['id'], $user_id);
	}
	$result->dispose();
}

?>
