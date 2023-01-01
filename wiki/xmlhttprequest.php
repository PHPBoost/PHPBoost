<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 12 09
 * @since       PHPBoost 1.6 - 2006 10 09
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../kernel/begin.php');
AppContext::get_session()->no_session_location(); //Permet de ne pas mettre jour la page dans la session.
require_once('../kernel/header_no_display.php');

$request = AppContext::get_request();

$id_cat = $request->get_postint('id_cat', 0);
$select_cat = $request->get_getint('select_cat', 0);
$selected_cat = $request->get_postint('selected_cat', 0);
$display_select_link = $request->get_getint('display_select_link', 0);
$open_cat = $request->get_postint('open_cat', 0);
$root = $request->get_getint('root', 0);

$categories = WikiCategoriesCache::load()->get_categories();

//Listage des répertoires dont le répertoire parent est connu
if ($id_cat != 0)
{
	echo '<div class="no-list"><ul>';
	//On sélectionne les répetoires dont l'id parent est connu
	$result = PersistenceContext::get_querier()->select("SELECT c.id, a.title, a.encoded_title
	FROM " . PREFIX . "wiki_cats c
	LEFT JOIN " . PREFIX . "wiki_articles a ON a.id = c.article_id
	WHERE c.id_parent = :id
	ORDER BY title ASC", array(
		'id' => $id_cat
	));
	while ($row = $result->fetch())
	{
		//On compte le nombre de catégories présentes pour savoir si on donne la possibilité de faire un sous dossier
		$sub_cats_number = PersistenceContext::get_querier()->count(PREFIX . "wiki_cats", 'WHERE id_parent = :id', array('id' => $row['id']));
		//Si cette catégorie contient des sous catégories, on propose de voir son contenu
		if ($sub_cats_number > 0)
			echo '<li class="sub-cat-tree"><a class="parent" href="javascript:show_wiki_cat_contents(' . $row['id'] . ', ' . ($display_select_link != 0 ? 1 : 0) . ');"><i class="far fa-plus-square" id="img-subfolder-' . $row['id'] . '"></i><i class="fa fa-folder" id ="img-folder-' . $row['id'] . '"></i></a> <a id="class-' . $row['id'] . '" href="javascript:' . ($display_select_link != 0 ? 'select_cat' : 'open_cat') . '(' . $row['id'] . ');">' . stripslashes($row['title']) . '</a><span id="cat-' . $row['id'] . '"></span></li>';
		else //Sinon on n'affiche pas le "+"
			echo '<li class="sub-cat-tree"><a id="class-' . $row['id'] . '" href="javascript:' . ($display_select_link != 0 ? 'select_cat' : 'open_cat') . '(' . $row['id'] . ');"><i class="fa fa-folder"></i></span> ' . stripslashes($row['title']) . '</a></li>';
	}
	$result->dispose();
	echo '</ul></div>';
}
//Retour de la localisation du dossier
elseif ($select_cat && empty($open_cat) && $root == 0)
{
	if ($selected_cat > 0)
	{
		$localisation = array();
		$id = $selected_cat; //Permier id
		do
		{
			$localisation[] = stripslashes($categories[$id]['title']);
			$id = (int)$categories[$id]['id_parent'];
		}
		while ($id > 0);
		$localisation = array_reverse($localisation);
		echo implode(' / ', $localisation);
	}
	else
	{
		echo LangLoader::get_message('wiki.no.selected.category', 'common', 'wiki');
	}
}
elseif (!empty($open_cat) || $root == 1)
{
	$open_cat = $root == 1 ? 0 : $open_cat;
	$return = '<ul class="no-list">';
	//Liste des catégories dans cette catégorie
	foreach ($categories as $key => $cat)
	{
		if ($cat['id_parent'] == $open_cat)
			$return .= '<li><a href="javascript:open_cat(' . $key . '); show_wiki_cat_contents(' . $cat['id_parent'] . ', 0);"><i class="fa fa-folder"></i>' . stripslashes($cat['title']) . '</a></li>';
	}
	$result = PersistenceContext::get_querier()->select("SELECT title, id, encoded_title
	FROM " . PREFIX . "wiki_articles a
	WHERE id_cat = :id
	AND a.redirect = 0
	ORDER BY is_cat DESC, title ASC", array(
		'id' => $open_cat
	));
	while ($row = $result->fetch())
	{
		$return .= '<li><a href="' . url('wiki.php?title=' . $row['encoded_title'], $row['encoded_title']) . '"><i class="fa fa-file"></i>' . stripslashes($row['title']) . '</a></li>';
	}
	$result->dispose();
	$return .= '</ul>';
	echo $return;
}
else
{
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}

require_once('../kernel/footer_no_display.php');
?>
