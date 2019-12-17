<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 11 02
 * @since       PHPBoost 1.6 - 2007 08 13
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

require_once('../kernel/begin.php');
require_once('../pages/pages_begin.php');
define('TITLE', $LANG['pages_explorer']);
define('DESCRIPTION', $LANG['pages_explorer_seo']);
$request = AppContext::get_request();

$cat = $request->get_getint('cat', 0);

$Bread_crumb->add($LANG['pages'], url('pages.php'));
$Bread_crumb->add($LANG['pages_explorer'], url('explorer.php'));
require_once('../kernel/header.php');

//Configuration des authorisations
$config_authorizations = $pages_config->get_authorizations();

$template = new FileTemplate('pages/explorer.tpl');

$module_data_path = $template->get_pictures_data_path();

//Liste des dossiers de la racine
foreach (PagesCategoriesCache::load()->get_categories() as $key => $cat)
{
	if ($cat['id_parent'] == 0)
	{
		//Autorisation particulière ?
		$special_auth = !empty($cat['auth']);
		//Vérification de l'autorisation d'éditer la page
		if (($special_auth && AppContext::get_current_user()->check_auth($cat['auth'], READ_PAGE)) || (!$special_auth && AppContext::get_current_user()->check_auth($config_authorizations, READ_PAGE)))
		{
			$template->assign_block_vars('list_cats', array(
				'KEY' =>  $key,
				'ID_PARENT' => $cat['id_parent'],
				'TITLE' => stripslashes($cat['title'])
			));
		}
	}
}
//Liste des fichiers de la racine
$result = PersistenceContext::get_querier()->select("SELECT title, id, encoded_title, auth
	FROM " . PREFIX . "pages
	WHERE id_cat = 0 AND is_cat = 0
	ORDER BY is_cat DESC, title ASC");
while ($row = $result->fetch())
{
	//Autorisation particulière ?
	$special_auth = !empty($row['auth']);
	$array_auth = TextHelper::unserialize($row['auth']);
	//Vérification de l'autorisation d'éditer la page
	if (($special_auth && AppContext::get_current_user()->check_auth($array_auth, READ_PAGE)) || (!$special_auth && AppContext::get_current_user()->check_auth($config_authorizations, READ_PAGE)))
	{
		$template->assign_block_vars('list_files', array(
			'TITLE' => stripslashes($row['title']),
			'URL_FILE' => url('pages.php?title=' . $row['encoded_title'], $row['encoded_title'])
		));
	}
}
$result->dispose();

$template->put_all(array(
	'PAGES_PATH' => $module_data_path,
	'TITLE' => $LANG['pages_explorer'],
	'L_ROOT' => $LANG['pages_root'],
	'SELECTED_CAT' => $cat > 0 ? $cat : 0,
	'L_CATS' => $LANG['pages_cats_tree']
));

$contents = '';
$result = PersistenceContext::get_querier()->select("SELECT c.id, p.title, p.encoded_title
FROM " . PREFIX . "pages_cats c
LEFT JOIN " . PREFIX . "pages p ON p.id = c.id_page
WHERE c.id_parent = 0
ORDER BY p.title ASC");
while ($row = $result->fetch())
{
	$sub_cats_number = PersistenceContext::get_querier()->count(PREFIX . "pages_cats", 'WHERE id_parent=:id_parent', array('id_parent' => $row['id']));

	$template->assign_block_vars('list', array(
		'ID' => $row['id'],
		'TITLE' => stripslashes($row['title']),
		'U_FOLDER' => $sub_cats_number > 0
	));
}
$result->dispose();
$template->put_all(array(
	'SELECTED_CAT' => 0,
	'CAT_0' => 'selected',
	'CAT_LIST' => ''
));

$template->display();


require_once('../kernel/footer.php');

?>
