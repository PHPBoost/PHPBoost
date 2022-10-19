<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 30
 * @since       PHPBoost 1.5 - 2007 05 07
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../kernel/begin.php');
include_once('../wiki/wiki_functions.php');

$config = WikiConfig::load();

require('../wiki/wiki_auth.php');

$id_auth = (int)retrieve(POST, 'id_auth', 0);
$id_status = (int)retrieve(POST, 'id_status', 0);
$type_status = retrieve(POST, 'status', '');
$id_change_status = (int)retrieve(POST, 'id_change_status', 0);
$contents = wiki_parse(retrieve(POST, 'contents', '', TSTRING_AS_RECEIVED));
$move = (int)retrieve(POST, 'id_to_move', 0);
$new_cat = (int)retrieve(POST, 'new_cat', 0);
$id_to_rename = (int)retrieve(POST, 'id_to_rename', 0);
$new_title = retrieve(POST, 'new_title', '');
$create_redirection_while_renaming = (bool)retrieve(POST, 'create_redirection_while_renaming', false);
$create_redirection = (int)retrieve(POST, 'create_redirection', 0);
$redirection_title = retrieve(POST, 'redirection_title', '');
$del_redirection = (int)retrieve(GET, 'del_redirection', 0);
$restore = (int)retrieve(GET, 'restore', 0);
$del_archive = (int)retrieve(GET, 'del_contents', 0);
$del_article = (int)retrieve(GET, 'del_article', 0);
$del_to_remove = (int)retrieve(POST, 'id_to_remove', 0);
$report_cat = (int)retrieve(POST, 'report_cat', 0);
$remove_action = retrieve(POST, 'action', ''); //Action à faire lors de la suppression

$db_querier = PersistenceContext::get_querier();

$categories = WikiCategoriesCache::load()->get_categories();
$request = AppContext::get_request();

$default = $request->get_postvalue('default', false);

if ($id_auth > 0)
{
	if (!AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_RESTRICTION))
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}

	$encoded_title = $db_querier->get_column_value(PREFIX . "wiki_articles", 'encoded_title', 'WHERE id = :id', array('id' => $id_auth));
	if (empty($encoded_title))
	{
		AppContext::get_response()->redirect('/wiki/' . url('wiki.php', '', '&'));
	}

	if ($default) //Configuration par défaut
	{
		$db_querier->update(PREFIX . "wiki_articles", array('auth' => ''), 'WHERE id = :id', array('id' => $id_auth));
	}
	else
	{
		//Génération du tableau des droits.
		$array_auth_all = Authorizations::build_auth_array_from_form(WIKI_RESTORE_ARCHIVE, WIKI_DELETE_ARCHIVE, WIKI_EDIT, WIKI_DELETE, WIKI_RENAME, WIKI_REDIRECT, WIKI_MOVE, WIKI_STATUS, WIKI_COM);
		$db_querier->update(PREFIX . "wiki_articles", array('auth' => TextHelper::serialize($array_auth_all)), 'WHERE id = :id', array('id' => $id_auth));
	}

	//Redirection vers l'article
	AppContext::get_response()->redirect('/wiki/' . url('wiki.php?title=' . $encoded_title, $encoded_title, '&'));
}
if ($id_change_status > 0)
{
	$type_status = ($type_status == 'radio_undefined') ? 'radio_undefined' : 'radio_defined';
	$status_list = LangLoader::get_message('wiki.status.list', 'common', 'wiki');
	
	//Si il s'agit d'un statut personnalisé
	if ($type_status == 'radio_undefined' && $contents != '')
	{
		$id_status = -1;
	}
	elseif ($type_status == 'radio_defined' && $id_status > 0 && is_array($status_list[$id_status - 1]))
	{
		$contents = '';
	}
	else
		$id_status = 0;

	try {
		$article_infos = $db_querier->select_single_row(PREFIX . "wiki_articles", array('id', 'title', 'encoded_title', 'auth'), 'WHERE id = :id', array('id' => $id_change_status));
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

	if (!empty($article_infos['encoded_title']))//Si l'article existe
	{
		//On met à jour dans la base de données
		$db_querier->update(PREFIX . "wiki_articles", array('defined_status' => $id_status, 'undefined_status' => $contents), 'WHERE id = :id', array('id' => $id_change_status));
		
		$description = ($id_status > 0) && isset($status_list[$id_status - 1]) && isset($status_list[$id_status - 1][0]) ? $status_list[$id_status - 1][0] : '';
		
		HooksService::execute_hook_action('wiki_change_status', 'wiki', array_merge($article_infos, array('url' => Url::to_rel('/wiki/' . url('wiki.php?title=' . $article_infos['encoded_title'], $article_infos['encoded_title'])))), $description);
		
		//Redirection vers l'article
		AppContext::get_response()->redirect('/wiki/' . url('wiki.php?title=' . $article_infos['encoded_title'], $article_infos['encoded_title'], '&'));
	}
}
elseif ($move > 0) //Déplacement d'un article
{
	try {
		$article_infos = $db_querier->select_single_row(PREFIX . "wiki_articles", array('is_cat', 'encoded_title', 'id_cat', 'auth'), 'WHERE id = :id', array('id' => $move));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	if ( empty($article_infos['encoded_title']))//Ce n'est pas un article ou une catégorie
		AppContext::get_response()->redirect('/wiki/' . url('wiki.php', '', '&'));

	$general_auth = empty($article_infos['auth']);
	$article_auth = !empty($article_infos['auth']) ? TextHelper::unserialize($article_infos['auth']) : array();

	if (!((!$general_auth || AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_MOVE)) && ($general_auth || AppContext::get_current_user()->check_auth($article_auth , WIKI_MOVE))))
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}

	if ($article_infos['is_cat'] == 0)//Article: il ne peut pas y avoir de problème
	{
		if (array_key_exists($new_cat, $categories) || $new_cat == 0)//Si la nouvelle catégorie existe
		{
			$db_querier->update(PREFIX . "wiki_articles", array('id_cat' => $new_cat), 'WHERE id = :id', array('id' => $move));
			WikiCategoriesCache::invalidate();
		}
		AppContext::get_response()->redirect('/wiki/' . url('wiki.php?title=' . $article_infos['encoded_title'], $article_infos['encoded_title'], '&'));
	}
	//Catégorie: on vérifie qu'on ne la place pas dans elle-même ou dans une de ses catégories filles
	elseif ($article_infos['is_cat'] == 1)
	{
		//On fait un tableau contenant la liste des sous catégories de cette catégorie
		$sub_cats = array();
		wiki_find_subcats($sub_cats, $article_infos['id_cat']);
		$sub_cats[] = $article_infos['id_cat'];

		if (!in_array($new_cat, $sub_cats)) //Si l'ancienne catégorie ne contient pas la nouvelle (sinon boucle infinie)
		{
			$db_querier->update(PREFIX . "wiki_cats", array('id_parent' => $new_cat), 'WHERE id = :id', array('id' => $article_infos['id_cat']));
			WikiCategoriesCache::invalidate();
			//on redirige vers l'article
			AppContext::get_response()->redirect('/wiki/' . url('wiki.php?title=' . $article_infos['encoded_title'], $article_infos['encoded_title'], '&'));
		}
		else //On redirige vers une page d'erreur
			AppContext::get_response()->redirect('/wiki/' .  url('property.php?move=' . $move  . '&error=e_cat_contains_cat', '', '&') . '#message_helper');
	}
}
elseif ($id_to_rename > 0 && !empty($new_title)) //Renommer un article
{
	try {
		$article_infos = $db_querier->select_single_row(PREFIX . "wiki_articles", array('*'), 'WHERE id = :id', array('id' => $id_to_rename));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	$general_auth = empty($article_infos['auth']);
	$article_auth = !empty($article_infos['auth']) ? TextHelper::unserialize($article_infos['auth']) : array();
	$article_auth = !empty($article_infos['auth']) ? TextHelper::unserialize($article_infos['auth']) : array();

	if (!((!$general_auth || AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_RENAME)) && ($general_auth || AppContext::get_current_user()->check_auth($article_auth , WIKI_RENAME))))
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}

	$already_exists = $db_querier->count(PREFIX . "wiki_articles", 'WHERE encoded_title = :encoded_title', array('encoded_title' => Url::encode_rewrite($new_title)));

	if (empty($article_infos['encoded_title']))//L'article n'existe pas
		AppContext::get_response()->redirect('/wiki/' . url('wiki.php', '', '&'));
	elseif (Url::encode_rewrite($new_title) == $article_infos['encoded_title'])//Si seul le titre change mais pas le titre encodé
	{
		$db_querier->update(PREFIX . "wiki_articles", array('title' => $new_title), 'WHERE id = :id', array('id' => $id_to_rename));

		WikiCategoriesCache::invalidate();
		Feed::clear_cache('wiki');

		AppContext::get_response()->redirect('/wiki/' . url('wiki.php?title=' . $article_infos['encoded_title'], $article_infos['encoded_title'], '&'));
	}
	elseif ($already_exists > 0) //Si le titre existe déjà erreur, on le signale
		AppContext::get_response()->redirect('/wiki/' . url('property.php?rename=' . $id_to_rename  . '&error=title_already_exists', '', '&') . '#message_helper');
	elseif ($already_exists == 0)
	{
		if ($create_redirection_while_renaming) //On crée un nouvel article
		{
			//On ajoute un article
			$result = $db_querier->insert(PREFIX . "wiki_articles", array('id_contents' => $article_infos['id_contents'], 'title' => $new_title, 'encoded_title' => Url::encode_rewrite($new_title), 'hits' => $article_infos['hits'], 'id_cat' => $article_infos['id_cat'], 'is_cat' => $article_infos['is_cat'], 'defined_status' => $article_infos['defined_status'], 'undefined_status' => $article_infos['undefined_status'], 'redirect' => 0, 'auth' => $article_infos['auth']));
			$new_id_article = $result->get_last_inserted_id();

			//On met à jour la table contents
			$db_querier->update(PREFIX . "wiki_contents", array('id_article' => $new_id_article), 'WHERE id_article = :id', array('id' => $id_to_rename));
			//On inscrit la redirection à l'ancien article
			$db_querier->update(PREFIX . "wiki_articles", array('redirect' => $new_id_article, 'id_contents' => 0), 'WHERE id = :id', array('id' => $id_to_rename));
			//On redirige les éventuelles redirections vers cet article sur son nouveau nom
			$db_querier->update(PREFIX . "wiki_articles", array('redirect' => $new_id_article), 'WHERE redirect = :id', array('id' => $id_to_rename));
			//Si c'est une catégorie on change l'id d'article associé
			if ($article_infos['is_cat'] == 1)
			{
				$db_querier->update(PREFIX . "wiki_cats", array('article_id' => $new_id_article), 'WHERE id = :id', array('id' => $article_infos['id_cat']));
			}
			WikiCategoriesCache::invalidate();
			 // Feeds Regeneration
			Feed::clear_cache('wiki');
			AppContext::get_response()->redirect('/wiki/' . url('wiki.php?title=' . Url::encode_rewrite($new_title), Url::encode_rewrite($new_title), '&'));
		}
		else //On met à jour l'article
		{
			$db_querier->update(PREFIX . "wiki_articles", array('title' => $new_title, 'encoded_title' => Url::encode_rewrite($new_title)), 'WHERE id = :id', array('id' => $id_to_rename));

			//Cache Regeneration
			WikiCategoriesCache::invalidate();
			Feed::clear_cache('wiki');

			AppContext::get_response()->redirect('/wiki/' . url('wiki.php?title=' . Url::encode_rewrite($new_title), Url::encode_rewrite($new_title), '&'));
		}
	}
}
elseif ($del_redirection > 0)//Supprimer une redirection
{
	//Vérification de la validité du jeton
	AppContext::get_session()->csrf_get_protect();

	$is_redirection = $db_querier->get_column_value(PREFIX . "wiki_articles", 'redirect', 'WHERE id = :id', array('id' => $del_redirection));
	if ($is_redirection > 0)
	{
		try {
			$article_infos = $db_querier->select_single_row(PREFIX . "wiki_articles", array('encoded_title', 'auth'), 'WHERE id = :id', array('id' => $is_redirection));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		$general_auth = empty($article_infos['auth']);
		$article_auth = !empty($article_infos['auth']) ? TextHelper::unserialize($article_infos['auth']) : array();

		if (!((!$general_auth || AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_REDIRECT)) && ($general_auth || AppContext::get_current_user()->check_auth($article_auth , WIKI_REDIRECT))))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}

		$db_querier->delete(PREFIX . 'wiki_articles', 'WHERE id=:id', array('id' => $del_redirection));
		AppContext::get_response()->redirect('/wiki/' . url('wiki.php?title=' . $article_infos['encoded_title'], $article_infos['encoded_title'], '&'));
	}
}
elseif ($create_redirection > 0 && !empty($redirection_title))
{
	try {
		$article_infos = $db_querier->select_single_row(PREFIX . "wiki_articles", array('*'), 'WHERE id = :id', array('id' => $create_redirection));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	$general_auth = empty($article_infos['auth']);
	$article_auth = !empty($article_infos['auth']) ? TextHelper::unserialize($article_infos['auth']) : array();

	if (!((!$general_auth || AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_REDIRECT)) && ($general_auth || AppContext::get_current_user()->check_auth($article_auth , WIKI_REDIRECT))))
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}

	$num_title = $db_querier->count(PREFIX . "wiki_articles", 'WHERE encoded_title = :encoded_title', array('encoded_title' => Url::encode_rewrite($redirection_title)));

	if (!empty($article_infos['encoded_title']))
	{
		if ($num_title == 0) //Si aucun article existe
		{
			$db_querier->insert(PREFIX . "wiki_articles", array('title' => $redirection_title, 'encoded_title' => Url::encode_rewrite($redirection_title), 'redirect' => $create_redirection, 'undefined_status' => '', 'auth' => ''));
			AppContext::get_response()->redirect('/wiki/' . url('wiki.php?title=' . Url::encode_rewrite($redirection_title), Url::encode_rewrite($redirection_title), '&'));
		}
		else
			AppContext::get_response()->redirect('/wiki/' . url('property.php?create_redirection=' . $create_redirection  . '&error=title_already_exists', '', '&') . '#message_helper');
	}
}
//Restauration d'une archive
elseif (!empty($restore)) //on restaure un ancien article
{
	//On cherche l'article correspondant
	$id_article = $db_querier->get_column_value(PREFIX . "wiki_contents", 'id_article', 'WHERE id_contents = :id', array('id' => $restore));
	if (!empty($id_article))
	{
		//On récupère l'ancien id du contenu
		try {
			$article_infos = $db_querier->select_single_row(PREFIX . "wiki_articles", array('id', 'title', 'id_contents', 'encoded_title', 'auth'), 'WHERE id = :id', array('id' => $id_article));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		$general_auth = empty($article_infos['auth']);
		$article_auth = !empty($article_infos['auth']) ? TextHelper::unserialize($article_infos['auth']) : array();

		if (!((!$general_auth || AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_RESTORE_ARCHIVE)) && ($general_auth || AppContext::get_current_user()->check_auth($article_auth , WIKI_RESTORE_ARCHIVE))))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}

		//On met à jour la table articles avec le nouvel id
		$db_querier->update(PREFIX . "wiki_articles", array('id_contents' => $restore), 'WHERE id = :id', array('id' => $id_article));
		//On met le nouvel id comme actif
		$db_querier->update(PREFIX . "wiki_contents", array('activ' => 1), 'WHERE id_contents = :id', array('id' => $restore));
		//L'ancien id devient archive
		$db_querier->update(PREFIX . "wiki_contents", array('activ' => 0), 'WHERE id_contents = :id', array('id' => $article_infos['id_contents']));
		
		HooksService::execute_hook_action('wiki_restore_archive', 'wiki', array_merge($article_infos, array('url' => Url::to_rel('/wiki/' . url('wiki.php?title=' . $article_infos['encoded_title'], $article_infos['encoded_title'])))));
	}

	AppContext::get_response()->redirect('/wiki/' . url('wiki.php?title=' . $article_infos['encoded_title'], $article_infos['encoded_title'] , '&'));
}
//Suppression d'une archive
elseif ($del_archive > 0)
{
	//Vérification de la validité du jeton
	AppContext::get_session()->csrf_get_protect();

	try {
		$contents_infos = $db_querier->select_single_row(PREFIX . "wiki_contents", array('activ', 'id_article'), 'WHERE id_contents = :id', array('id' => $del_archive));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	try {
		$article_infos = $db_querier->select_single_row(PREFIX . "wiki_articles", array('id', 'title', 'encoded_title', 'auth'), 'WHERE id = :id', array('id' => $contents_infos['id_article']));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	$general_auth = empty($article_infos['auth']);
	$article_auth = !empty($article_infos['auth']) ? TextHelper::unserialize($article_infos['auth']) : array();

	if (!((!$general_auth || AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_DELETE_ARCHIVE)) && ($general_auth || AppContext::get_current_user()->check_auth($article_auth , WIKI_DELETE_ARCHIVE))))
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}

	if ($contents_infos['activ'] == 0) //C'est une archive -> on peut supprimer
	{
		$db_querier->delete(PREFIX . 'wiki_contents', 'WHERE id_contents=:id', array('id' => $del_archive));
		HooksService::execute_hook_action('wiki_delete_archive', 'wiki', $article_infos);
	}
	if (!empty($article_infos['encoded_title'])) //on redirige vers l'article
		AppContext::get_response()->redirect('/wiki/' . url('history.php?id=' . $contents_infos['id_article'], '', '&'));
}
elseif ($del_article > 0) //Suppression d'un article
{
	//Vérification de la validité du jeton
	AppContext::get_session()->csrf_get_protect();

	try {
		$article_infos = $db_querier->select_single_row(PREFIX . "wiki_articles", array('id', 'auth', 'title', 'encoded_title', 'id_cat'), 'WHERE id = :id', array('id' => $del_article));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	$general_auth = empty($article_infos['auth']);
	$article_auth = !empty($article_infos['auth']) ? TextHelper::unserialize($article_infos['auth']) : array();

	if (!((!$general_auth || AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_DELETE)) && ($general_auth || AppContext::get_current_user()->check_auth($article_auth , WIKI_DELETE))))
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}

	//On rippe l'article
	$db_querier->delete(PREFIX . 'wiki_articles', 'WHERE id=:id', array('id' => $del_article));
	$db_querier->delete(PREFIX . 'wiki_articles', 'WHERE redirect=:id', array('id' => $del_article));
	$db_querier->delete(PREFIX . 'wiki_contents', 'WHERE id_article=:id', array('id' => $del_article));
	$db_querier->delete(PREFIX . 'wiki_favorites', 'WHERE id_article=:id', array('id' => $del_article));

	CommentsService::delete_comments_topic_module('wiki', $del_article);

	// Feeds Regeneration
	Feed::clear_cache('wiki');

	HooksService::execute_hook_action('delete', 'wiki', $article_infos);

	if (array_key_exists($article_infos['id_cat'], $categories))//Si elle  a une catégorie parente
		AppContext::get_response()->redirect('/wiki/' . url('wiki.php?title=' . Url::encode_rewrite($categories[$article_infos['id_cat']]['title']), Url::encode_rewrite($categories[$article_infos['id_cat']]['title']), '&'));
	else
		AppContext::get_response()->redirect('/wiki/' . url('wiki.php', '', '&'));
}
elseif ($del_to_remove > 0 && $report_cat >= 0) //Suppression d'une catégorie
{
	$remove_action = ($remove_action == 'move_all') ? 'move_all' : 'remove_all';

	try {
		$article_infos = $db_querier->select_single_row(PREFIX . "wiki_articles", array('id', 'title', 'encoded_title', 'id_cat', 'auth'), 'WHERE id = :id', array('id' => $del_to_remove));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	$general_auth = empty($article_infos['auth']);
	$article_auth = !empty($article_infos['auth']) ? TextHelper::unserialize($article_infos['auth']) : array();

	if (!((!$general_auth || AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_DELETE)) && ($general_auth || AppContext::get_current_user()->check_auth($article_auth , WIKI_DELETE))))
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}

	$sub_cats = array();
	//On fait un tableau contenant la liste des sous catégories de cette catégorie
	wiki_find_subcats($sub_cats, $article_infos['id_cat']);
	$sub_cats[] = $article_infos['id_cat']; //On rajoute la catégorie que l'on supprime

	if (empty($article_infos['encoded_title'])) //si l'article n'existe pas on redirige vers l'index
		AppContext::get_response()->redirect('/wiki/' . url('wiki.php', '', '&'));

	if ($remove_action == 'move_all') //Vérifications préliminaires si on va tout supprimer
	{
		//Si la nouvelle catégorie n'est pas une catégorie
		if (!array_key_exists($report_cat, $categories) && $report_cat > 0)
			AppContext::get_response()->redirect('/wiki/' . url('property.php?del=' . $del_to_remove . '&error=e_not_a_cat#message_helper', '', '&'));

		//Si on ne la déplace pas dans une de ses catégories filles
		if (($report_cat > 0 && in_array($report_cat, $sub_cats)) || $report_cat == $article_infos['id_cat'])//Si on veut reporter dans une catégorie parente
			AppContext::get_response()->redirect('/wiki/' . url('property.php?del=' . $del_to_remove . '&error=e_cat_contains_cat#message_helper', '','&'));
	}

	//Quoi qu'il arrive on supprime l'article associé
	$db_querier->delete(PREFIX . 'wiki_contents', 'WHERE id_article=:id', array('id' => $del_to_remove));
	$db_querier->delete(PREFIX . 'wiki_articles', 'WHERE id=:id', array('id' => $del_to_remove));
	$db_querier->delete(PREFIX . 'wiki_cats', 'WHERE id=:id', array('id' => $del_to_remove));
	$db_querier->delete(PREFIX . 'wiki_favorites', 'WHERE id_article=:id', array('id' => $del_to_remove));

	CommentsService::delete_comments_topic_module('wiki', $del_to_remove);

	if ($remove_action == 'remove_all' || count($sub_cats) <= 1) //On supprime le contenu de la catégorie
	{
		foreach ($sub_cats as $id) //Chaque sous-catégorie
		{
			$result = $db_querier->select("SELECT id
				FROM " . PREFIX . "wiki_articles
				WHERE id_cat = :id", array(
					'id' => $id
			));

			while ($row = $result->fetch()) //On supprime toutes les archives de chaque article avant de le supprimer lui-même
			{
				$db_querier->delete(PREFIX . 'wiki_contents', 'WHERE id_article=:id', array('id' => $row['id']));
				CommentsService::delete_comments_topic_module('wiki', $row['id']);
			}
			$result->dispose();

			$db_querier->delete(PREFIX . 'wiki_articles', 'WHERE id_cat=:id', array('id' => $id));
			$db_querier->delete(PREFIX . 'wiki_cats', 'WHERE id=:id', array('id' => $id));
		}
		WikiCategoriesCache::invalidate();

		// Feeds Regeneration
		Feed::clear_cache('wiki');
		
		HooksService::execute_hook_action('delete', 'wiki', $article_infos);

		//On redirige soit vers l'article parent soit vers la catégorie
		if (array_key_exists($article_infos['id_cat'], $categories) && $categories[$article_infos['id_cat']]['id_parent'] > 0)
		{
			$title = stripslashes($categories[$categories[$article_infos['id_cat']]['id_parent']]['title']);
			AppContext::get_response()->redirect('/wiki/' . url('wiki.php?title=' . Url::encode_rewrite($title), Url::encode_rewrite($title), '&'));
		}
		else
			AppContext::get_response()->redirect('/wiki/' . url('wiki.php', '', '&'));
	}
	elseif ($remove_action == 'move_all') //On déplace le contenu de la catégorie
	{
		$db_querier->update(PREFIX . "wiki_articles", array('id_cat' => $report_cat), 'WHERE id_cat = :id', array('id' => $article_infos['id_cat']));
		$db_querier->update(PREFIX . "wiki_cats", array('id_parent' => $report_cat), 'WHERE id_parent = :id', array('id' => $article_infos['id_cat']));
		WikiCategoriesCache::invalidate();
		
		HooksService::execute_hook_action('delete', 'wiki', $article_infos);

		if (array_key_exists($report_cat, $categories))
		{
			$title = stripslashes($categories[$report_cat]['title']);
			AppContext::get_response()->redirect('/wiki/' . url('wiki.php?title=' . Url::encode_rewrite($title), Url::encode_rewrite($title), '&'));
		}
		else
			AppContext::get_response()->redirect('/wiki/' . url('wiki.php', '', '&'));
	}
}

//On redirige vers l'index si on n'est rentré dans aucune des conditions ci-dessus
AppContext::get_response()->redirect('/wiki/' . url('wiki.php', '', '&'));

?>
