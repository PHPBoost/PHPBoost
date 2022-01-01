<?php
/**
 * Random page
 * Display authorizations, status, move, rename, redirect, delete, comments pages
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 1.6 - 2007 05 07
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../kernel/begin.php');
include_once('../wiki/wiki_functions.php');

$lang = LangLoader::get_all_langs('wiki');

$config = WikiConfig::load();

require_once('../wiki/wiki_auth.php');

$random = (bool)retrieve(GET, 'random', false);
$id_auth = (int)retrieve(GET, 'auth', 0);
$wiki_status = (int)retrieve(GET, 'status', 0);
$move = (int)retrieve(GET, 'move', 0);
$rename = (int)retrieve(GET, 'rename', 0);
$redirect = (int)retrieve(GET, 'redirect', 0);
$create_redirection = (int)retrieve(GET, 'create_redirection', 0);
$idcom = (int)retrieve(GET, 'idcom', 0);
$del_article = (int)retrieve(GET, 'del', 0);

$categories = WikiCategoriesCache::load()->get_categories();

if ($id_auth > 0) //Autorisations de l'article
{
	define('TITLE', $lang['wiki.authorizations.management']);

	try {
		$article_infos = PersistenceContext::get_querier()->select_single_row(PREFIX . 'wiki_articles', array('id', 'title', 'encoded_title', 'auth', 'is_cat', 'id_cat'), 'WHERE id = :id', array('id' => $id_auth));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	if (!AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_RESTRICTION))
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}
}
elseif ($wiki_status > 0)//On s'intéresse au statut de l'article
{
	define('TITLE', $lang['wiki.status.management']);

	try {
		$article_infos = PersistenceContext::get_querier()->select_single_row(PREFIX . 'wiki_articles', array('*'), 'WHERE id = :id', array('id' => $wiki_status));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	$general_auth = empty($article_infos['auth']);
	$article_auth = !empty($article_infos['auth']) ? TextHelper::unserialize($article_infos['auth']) : array();

	if (!((!$general_auth || AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_STATUS)) && ($general_auth || AppContext::get_current_user()->check_auth($article_auth , WIKI_STATUS))))
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}
}
elseif ($move > 0) // Déplacement d'article
{
	define('TITLE', $lang['wiki.moving.management']);

	try {
		$article_infos = PersistenceContext::get_querier()->select_single_row(PREFIX . 'wiki_articles', array('*'), 'WHERE id = :id', array('id' => $move));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	$general_auth = empty($article_infos['auth']);
	$article_auth = !empty($article_infos['auth']) ? TextHelper::unserialize($article_infos['auth']) : array();

	if (!((!$general_auth || AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_MOVE)) && ($general_auth || AppContext::get_current_user()->check_auth($article_auth , WIKI_MOVE))))
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}
}
elseif ($rename > 0) // Renommer l'article
{
	define('TITLE', $lang['wiki.renaming.management']);

	try {
		$article_infos = PersistenceContext::get_querier()->select_single_row(PREFIX . 'wiki_articles', array('*'), 'WHERE id = :id', array('id' => $rename));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	$general_auth = empty($article_infos['auth']);
	$article_auth = !empty($article_infos['auth']) ? TextHelper::unserialize($article_infos['auth']) : array();

	if (!((!$general_auth || AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_RENAME)) && ($general_auth || AppContext::get_current_user()->check_auth($article_auth , WIKI_RENAME))))
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}
}
elseif ($redirect > 0 || $create_redirection > 0) // Redirection
{
	if ($redirect > 0)
	{
		try {
			$article_infos = PersistenceContext::get_querier()->select_single_row(PREFIX . 'wiki_articles', array('*'), 'WHERE id = :id', array('id' => $redirect));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
	}
	else
	{
		try {
			$article_infos = PersistenceContext::get_querier()->select_single_row(PREFIX . 'wiki_articles', array('*'), 'WHERE id = :id', array('id' => $create_redirection));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
	}

	define('TITLE', $lang['wiki.redirections.management']);

	$general_auth = empty($article_infos['auth']);
	$article_auth = !empty($article_infos['auth']) ? TextHelper::unserialize($article_infos['auth']) : array();

	if (!((!$general_auth || AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_REDIRECT)) && ($general_auth || AppContext::get_current_user()->check_auth($article_auth , WIKI_REDIRECT))))
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}
}
elseif (AppContext::get_request()->has_getparameter('com') && $idcom > 0) // Comments
{
	try {
		$article_infos = PersistenceContext::get_querier()->select_single_row(PREFIX . 'wiki_articles', array('*'), 'WHERE id = :id', array('id' => $idcom));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	define('TITLE', sprintf($lang['wiki.comments'], stripslashes($article_infos['title'])));
	define('DESCRIPTION', sprintf($lang['wiki.comments.seo'], stripslashes($article_infos['title'])));
	$general_auth = empty($article_infos['auth']);
	$article_auth = !empty($article_infos['auth']) ? TextHelper::unserialize($article_infos['auth']) : array();

	if (!((!$general_auth || AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_COM)) && ($general_auth || AppContext::get_current_user()->check_auth($article_auth , WIKI_COM))))
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}
}
elseif ($del_article > 0) //Suppression d'un article ou d'une catégorie
{
	try {
		$article_infos = PersistenceContext::get_querier()->select_single_row(PREFIX . 'wiki_articles', array('*'), 'WHERE id = :id', array('id' => $del_article));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	define('TITLE', $lang['wiki.remove.category']);

	$general_auth = empty($article_infos['auth']);
	$article_auth = !empty($article_infos['auth']) ? TextHelper::unserialize($article_infos['auth']) : array();
	if (!((!$general_auth || AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_DELETE)) && ($general_auth || AppContext::get_current_user()->check_auth($article_auth , WIKI_DELETE))))
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}
}
else
	define('TITLE', '');

$bread_crumb_key = 'wiki_property';
require_once('../wiki/wiki_bread_crumb.php');
require_once('../kernel/header.php');

$view = new FileTemplate('wiki/property.tpl');
$view->add_lang($lang);

if ($random) // Recherche d'une page aléatoire
{
	$page = '';
	try {
		$page = PersistenceContext::get_querier()->get_column_value(PREFIX . "wiki_articles", 'encoded_title', 'WHERE redirect = 0 ORDER BY rand() LIMIT 1 OFFSET 0');
	} catch (Exception $e) {}

	if (!empty($page)) //On redirige
		AppContext::get_response()->redirect('/wiki/' . url('wiki.php?title=' . $page, $page));
	else
		AppContext::get_response()->redirect('/wiki/' . url('wiki.php'));
}
elseif ($id_auth > 0) //gestion du niveau d'autorisation
{
	$array_auth = !empty($article_infos['auth']) ? TextHelper::unserialize($article_infos['auth']) : $config->get_authorizations(); //Récupération des tableaux des autorisations et des groupes.

	$view->assign_block_vars('auth', array(
		'ID' => $id_auth,
		'TITLE' => stripslashes($article_infos['title']),

		'L_PAGE_TITLE' => sprintf($lang['wiki.authorizations.management']),
	));

	//On assigne les variables pour le POST en précisant l'idurl.
	$view->put_all(array(
		'SELECT_RESTORE_ARCHIVE' => Authorizations::generate_select(WIKI_RESTORE_ARCHIVE, $array_auth),
		'SELECT_DELETE_ARCHIVE'  => Authorizations::generate_select(WIKI_DELETE_ARCHIVE, $array_auth),
		'SELECT_EDIT'            => Authorizations::generate_select(WIKI_EDIT, $array_auth),
		'SELECT_DELETE'          => Authorizations::generate_select(WIKI_DELETE, $array_auth),
		'SELECT_RENAME'          => Authorizations::generate_select(WIKI_RENAME, $array_auth),
		'SELECT_REDIRECT'        => Authorizations::generate_select(WIKI_REDIRECT, $array_auth),
		'SELECT_MOVE'            => Authorizations::generate_select(WIKI_MOVE, $array_auth),
		'SELECT_STATUS'          => Authorizations::generate_select(WIKI_STATUS, $array_auth),
		'SELECT_COM'             => Authorizations::generate_select(WIKI_COM, $array_auth),
	));
}
elseif ($wiki_status > 0) // item status
{
	$view->assign_block_vars('status', array(
		'TITLE'             => stripslashes($article_infos['title']),
		'UNDEFINED_STATUS'  => ($article_infos['defined_status'] < 0 ) ? wiki_unparse($article_infos['undefined_status']) : '',
		'ITEM_ID'        => $wiki_status,
		'SELECTED_TEXTAREA' => ($article_infos['defined_status'] >= 0 ? 'disabled="disabled"' : ''),
		'SELECTED_SELECT'   => ($article_infos['defined_status'] < 0 ? 'disabled="disabled"' : ''),
		'UNDEFINED'         => ($article_infos['defined_status'] < 0 ? 'checked="checked"' : ''),
		'DEFINED'           => ($article_infos['defined_status'] >= 0 ? 'checked="checked"' : ''),

		'L_PAGE_TITLE'     => $lang['wiki.status.management'],
		'L_CURRENT_STATUS' => ($article_infos['defined_status'] == -1 ? $lang['wiki.undefined.status'] : (($article_infos['defined_status'] > 0 ) ? $lang['wiki.status.list'][$article_infos['defined_status'] - 1][1] : $lang['wiki.no.status'])),
	));

	//On fait une liste des statuts définis
	$view->assign_block_vars('status.list', array(
		'ID_STATUS' => 0,
		'SELECTED'  => ($article_infos['defined_status'] == 0) ? 'selected="selected"' : '',

		'L_STATUS'  => $lang['wiki.no.status'],
	));
	foreach ($lang['wiki.status.list'] as $key => $value)
	{
		$view->assign_block_vars('status.list', array(
			'ID_STATUS' => $key + 1,
			'SELECTED'  => ($article_infos['defined_status'] == $key + 1) ? 'selected="selected"' : '',

			'L_STATUS'  => $value[0],
		));
		$view->assign_block_vars('status.status_array', array(
			'ID'     => $key + 1,

			'L_TEXT' => str_replace('"', '\"', $value[1]),
		));
	}
}
elseif ($move > 0) //On déplace l'article
{
	$cats = array();
	$cat_list = display_wiki_cat_explorer($article_infos['id_cat'], $cats, 1);
	$cats = array_reverse($cats);
	if (array_key_exists(0, $cats))
		unset($cats[0]);
	$current_cat = '';
	$nbr_cats = count($cats);
	$i = 1;
	foreach ($cats as $key => $value)
	{
		$current_cat .= stripslashes($categories[$value]['title']) . (($i < $nbr_cats) ? ' / ' : '');
		$i++;
	}
	if ($article_infos['id_cat'] > 0)
		$current_cat .= ($nbr_cats > 0 ? ' / ' : '') . stripslashes($categories[$article_infos['id_cat']]['title']);
		else
			$current_cat = $lang['wiki.no.selected.category'];

	$view->assign_block_vars('move', array(
		'TITLE'            => stripslashes($article_infos['title']),
		'ITEM_ID'       => $move,
		'CATEGORIES_LIST'  => $cat_list,
		'CURRENT_CATEGORY' => $current_cat,
		'SELECTED_CAT'     => $article_infos['id_cat'],
		'ROOT_CATEGORY'            => ($article_infos['id_cat'] == 0 ? 'wiki_selected_cat' : ''),
		'ID_CATEGORY'      => $article_infos['id_cat'],

		'L_PAGE_TITLE' => $lang['wiki.moving.management'],
	));

	//Gestion des erreurs
	$error = retrieve(GET, 'error', '');
	if ($error == 'e_cat_contains_cat')
		$errstr = $lang['wiki.category.contains.category'];
	else
		$errstr = '';
	if (!empty($errstr))
		$view->put('MESSAGE_HELPER', MessageHelper::display($errstr, MessageHelper::WARNING));
}
elseif ($rename > 0)//On renomme un article
{
	$view->assign_block_vars('rename', array(
		'ITEM_ID'  => $rename,
		'FORMER_NAME' => stripslashes($article_infos['title']),
		'TITLE'       => stripslashes($article_infos['title']),

		'L_PAGE_TITLE' => $lang['wiki.renaming.management'],
	));

	//Gestion des erreurs
	$error = retrieve(GET, 'error', '');
	if ($error == 'title_already_exists')
		$errstr = $lang['wiki.title.already.exists'];
	else
		$errstr = '';
	if (!empty($errstr))
		$view->put('MESSAGE_HELPER', MessageHelper::display($errstr, MessageHelper::WARNING));
}
elseif ($redirect > 0) //Redirections de l'article
{
	$view->assign_block_vars('redirect', array(
		'TITLE' => stripslashes($article_infos['title']),

		'L_PAGE_TITLE' => $lang['wiki.redirections.management'],
	));
	//Liste des redirections
	$result = PersistenceContext::get_querier()->select("SELECT title, id
		FROM " . PREFIX . "wiki_articles
		WHERE redirect = :redirect
		ORDER BY title", array(
			'redirect' => $redirect
		));
	while ($row = $result->fetch())
	{
		$view->assign_block_vars('redirect.list', array(
			'U_REDIRECTION_DELETE' => url('action.php?del_redirection=' . $row['id'] . '&amp;token=' . AppContext::get_session()->get_token()),
			'REDIRECTION_NAME' => stripslashes($row['title']),
		));
	}

	$view->put_all(array(
		'C_REDIRECTIONS' => $result->get_rows_count() > 0,

		'U_CREATE_REDIRECTION' => url('property.php?create_redirection=' . $redirect),
	));
	$result->dispose();
}
elseif ($create_redirection > 0) //Création d'une redirection
{
	$view->assign_block_vars('create', array(
		'ITEM_ID' => $create_redirection,
		'TITLE' => stripslashes($article_infos['title']),
		'L_PAGE_TITLE' => $lang['wiki.create.redirection.management'],
	));

	//Gestion des erreurs
	$error = retrieve(GET, 'error', '');
	if ($error == 'title_already_exists')
		$errstr = $lang['wiki.title.already.exists'];
	else
		$errstr = '';
	if (!empty($errstr))
		$view->put('MESSAGE_HELPER', MessageHelper::display($errstr, MessageHelper::WARNING));
}
elseif (AppContext::get_request()->has_getparameter('com') && $idcom > 0) //Affichage des commentaires
{
	$comments_topic = new WikiCommentsTopic();
	$comments_topic->set_id_in_module($idcom);
	$comments_topic->set_url(new Url('/wiki/property.php?idcom=' . $idcom . '&com=0'));

	$view->put_all(array(
		'C_COMMENTS' => true,

		'TITLE'    => stripslashes($article_infos['title']),
		'COMMENTS' => CommentsService::display($comments_topic)->render()
	));
}
elseif ($del_article > 0) //Suppression d'un article ou d'une catégorie
{
	if (empty($article_infos['title']))//Si l'article n'existe pas
		AppContext::get_response()->redirect('/wiki/' . url('wiki.php'));

	if ($article_infos['is_cat'] == 0)//C'est un article on ne s'en occupe pas ici, on redirige vers l'article en question
		AppContext::get_response()->redirect('/wiki/' . url('wiki.php?title=' . $article_infos['encoded_title'], $article_infos['encoded_title']));
	else //Catégorie
	{
		$cats = array();
		$cat_list = display_wiki_cat_explorer($article_infos['id_cat'], $cats);
		$cats = array_reverse($cats);
		if (array_key_exists(0, $cats))
			unset($cats[0]);
		$current_cat = '';
		$nbr_cats = count($cats);
		$i = 1;
		foreach ($cats as $key => $value)
		{
			$current_cat .= stripslashes($categories[$value]['title']) . (($i < $nbr_cats) ? ' / ' : '');
			$i++;
		}
		if ($article_infos['id_cat'] > 0)
			$current_cat .= ($nbr_cats > 0 ? ' / ' : '') . stripslashes($categories[$article_infos['id_cat']]['title']);
		else
			$current_cat = $lang['wiki.no.selected.category'];

		$view->assign_block_vars('remove', array(
			'TITLE'           => stripslashes($article_infos['title']),
			'ITEM_ID'         => $del_article,
			'CATEGORIES_LIST' => $cat_list,
			'CURRENT_CAT'     => $current_cat,
			'SELECTED_CAT'    => $article_infos['id_cat'],
			'ROOT_CATEGORY'   => ($article_infos['id_cat'] == 0 ? 'wiki_selected_cat' : ''),
			'ID_CATEGORY'     => $article_infos['id_cat'],

			'L_PAGE_TITLE' => $lang['wiki.remove.category'],
		));

		//Gestion des erreurs
		$error = retrieve(GET, 'error', '');
		if ($error == 'e_cat_contains_cat')
			$errstr = $lang['wiki.category.contains.category'];
		elseif ($error == 'e_not_a_cat')
			$errstr = $lang['wiki.no.valid.category'];
		else
			$errstr = '';
		if (!empty($errstr))
			$view->put('MESSAGE_HELPER', MessageHelper::display($errstr, MessageHelper::WARNING));
	}
}
else
	AppContext::get_response()->redirect('/wiki/' . url('wiki.php'));

//On travaille uniquement en BBCode, on force le langage de l'éditeur
$content_editor = AppContext::get_content_formatting_service()->get_default_factory();
$editor = $content_editor->get_editor();
$editor->set_identifier('contents');

$view->put_all(array(
	'KERNEL_EDITOR' => $editor->display(),

	'L_ALERT_REMOVING_CAT' => str_replace('\'', '\\\'', $lang['wiki.confirm.remove.category']),
	));

$view->display();

require_once('../kernel/footer.php');

?>
