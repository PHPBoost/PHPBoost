<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 11 02
 * @since       PHPBoost 1.6 - 2007 05 31
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

require_once('../kernel/begin.php');
include_once('../wiki/wiki_functions.php');
load_module_lang('wiki');

define('TITLE', $LANG['wiki_explorer']);
define('DESCRIPTION', $LANG['wiki_explorer_seo']);

$bread_crumb_key = 'wiki_explorer';
require_once('../wiki/wiki_bread_crumb.php');

$request = AppContext::get_request();

$cat = $request->get_getint('cat', 0);

require_once('../kernel/header.php');

$template = new FileTemplate('wiki/explorer.tpl');

$module_data_path = $template->get_pictures_data_path();

//Contenu de la racine:
foreach (WikiCategoriesCache::load()->get_categories() as $key => $cat)
{
	if ($cat['id_parent'] == 0)
	{
		$template->assign_block_vars('list_cats', array(
			'KEY'       =>  $key,
			'ID_PARENT' => $cat['id_parent'],
			'TITLE'     => stripslashes($cat['title'])
		));
	}
}
$result = PersistenceContext::get_querier()->select("SELECT title, id, encoded_title
	FROM " . PREFIX . "wiki_articles a
	WHERE id_cat = 0
	AND a.redirect = 0
	ORDER BY is_cat DESC, title ASC");
while ($row = $result->fetch())
{
	$template->assign_block_vars('list_files', array(
		'TITLE' => stripslashes($row['title']),
		'URL_FILE' => url('wiki.php?title=' . $row['encoded_title'], $row['encoded_title'])
	));
}
$result->dispose();

$template->put_all(array(
	'TITLE'        => $LANG['wiki_explorer'],
	'L_ROOT'       => $LANG['wiki_root'],
	'SELECTED_CAT' => $cat > 0 ? $cat : 0,
	'L_CATS'       => $LANG['wiki_cats_tree'],
	'L_CONTENTS'   => $LANG['wiki_contents'],
));

$result = PersistenceContext::get_querier()->select("SELECT c.id, a.title, a.encoded_title
FROM " . PREFIX . "wiki_cats c
LEFT JOIN " . PREFIX . "wiki_articles a ON a.id = c.article_id
WHERE c.id_parent = 0
ORDER BY title ASC");
while ($row = $result->fetch())
{
	$sub_cats_number = PersistenceContext::get_querier()->count(PREFIX . "wiki_cats", 'WHERE id_parent = :id', array('id' => $row['id']));

	$template->assign_block_vars('list', array(
		'ID' => $row['id'],
		'TITLE' => stripslashes($row['title']),
		'U_FOLDER' => $sub_cats_number > 0
	));
}
$result->dispose();
$template->put_all(array(
	'SELECTED_CAT' => 0,
	'CAT_0'        => 'selected',
	'CAT_LIST'     => '',
	'CURRENT_CAT'  => $LANG['wiki_no_selected_cat']
));

echo $template->render();


require_once('../kernel/footer.php');

?>
