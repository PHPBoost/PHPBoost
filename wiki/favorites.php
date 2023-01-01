<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 01 08
 * @since       PHPBoost 1.6 - 2007 05 24
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../kernel/begin.php');

$lang = LangLoader::get_all_langs('wiki');

define('TITLE' , $lang['wiki.tracked.items']);
define('DESCRIPTION', $lang['wiki.tracked.items.seo']);

$bread_crumb_key = 'wiki_favorites';
require_once('../wiki/wiki_bread_crumb.php');

require_once('../kernel/header.php');

if (!AppContext::get_current_user()->check_level(User::MEMBER_LEVEL))
{
	$error_controller = PHPBoostErrors::user_not_authorized();
	DispatchManager::redirect($error_controller);
}

$request = AppContext::get_request();

$add_favorite = $request->get_getint('add', 0);
$remove_favorite = $request->get_getint('del', 0);

if ($add_favorite > 0)//Ajout d'un favori
{
	//on vérifie que l'article existe
	try {
		$article_infos = PersistenceContext::get_querier()->select_single_row(PREFIX . "wiki_articles", array('encoded_title'), 'WHERE id = :id', array('id' => $add_favorite));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	if (empty($article_infos['encoded_title'])) //L'article n'existe pas
		AppContext::get_response()->redirect('/wiki/' . url('wiki.php', '', '&'));
	//On regarde que le sujet n'est pas en favoris
	$is_favorite = PersistenceContext::get_querier()->count(PREFIX . "wiki_favorites", 'WHERE user_id = :user_id AND id_article = :id_article', array('user_id' => AppContext::get_current_user()->get_id(), 'id_article' => $add_favorite));
	if ($is_favorite == 0)
	{
		PersistenceContext::get_querier()->insert(PREFIX . "wiki_favorites", array('id_article' => $add_favorite, 'user_id' => AppContext::get_current_user()->get_id()));
		AppContext::get_response()->redirect('/wiki/' . url('wiki.php?title=' . $article_infos['encoded_title'], $article_infos['encoded_title'], '&'));
	}
	else //Erreur: l'article est déjà en favoris
		AppContext::get_response()->redirect('/wiki/' . url('favorites.php?error=e_already_favorite', '', '&') . '#message_helper');
}
elseif ($remove_favorite > 0)
{
    //Vérification de la validité du jeton
    AppContext::get_session()->csrf_get_protect();

	//on vérifie que l'article existe
	try {
		$article_infos = PersistenceContext::get_querier()->select_single_row(PREFIX . "wiki_articles", array('encoded_title'), 'WHERE id = :id', array('id' => $remove_favorite));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	if (empty($article_infos['encoded_title'])) //L'article n'existe pas
		AppContext::get_response()->redirect('/wiki/' . url('wiki.php', '', '&'));

	//On regarde que le sujet n'est pas en favoris
	$is_favorite = PersistenceContext::get_querier()->count(PREFIX . "wiki_favorites", 'WHERE user_id = :user_id AND id_article = :id_article', array('user_id' => AppContext::get_current_user()->get_id(), 'id_article' => $remove_favorite));
	//L'article est effectivement en favoris
	if ($is_favorite > 0)
	{
		PersistenceContext::get_querier()->delete(PREFIX . 'wiki_favorites', 'WHERE id_article=:id AND user_id=:user_id', array('id' => $remove_favorite, 'user_id' => AppContext::get_current_user()->get_id()));
		AppContext::get_response()->redirect('/wiki/' . url('wiki.php?title=' . $article_infos['encoded_title'], $article_infos['encoded_title'], '&'));
	}
	else //Erreur: l'article est déjà en favoris
		AppContext::get_response()->redirect('/wiki/' . url('favorites.php?error=e_no_favorite', '', '&') . '#message_helper');
}
else
{
	$view = new FileTemplate('wiki/favorites.tpl');
	$view->add_lang($lang);

	//Gestion des erreurs
	$error = AppContext::get_request()->get_getvalue('error', '');
	$error = !empty($error) ? TextHelper::strprotect($error) : '';
	if ($error == 'e_no_favorite')
		$errstr = $lang['wiki.article.is.not.a.favorite'];
	elseif ($error == 'e_already_favorite')
		$errstr = $lang['wiki.already.favorite'];
	else
		$errstr = '';
	if (!empty($errstr))
		$view->put('MESSAGE_HELPER', MessageHelper::display($errstr, MessageHelper::WARNING));

	//on liste les favoris
	$result = PersistenceContext::get_querier()->select("SELECT f.id, a.id, a.title, a.encoded_title
	FROM " . PREFIX . "wiki_favorites f
	LEFT JOIN " . PREFIX . "wiki_articles a ON a.id = f.id_article
	WHERE user_id = :id", array(
		'id' => AppContext::get_current_user()->get_id()
	));

	$view->put_all(array(
		'C_TRACKED_ITEMS' => $result->get_rows_count() > 0
	));

	while ($row = $result->fetch())
	{
		$view->assign_block_vars('list', array(
			'TITLE' => stripslashes($row['title']),
			'ID' => $row['id'],

			'U_ITEM'    => url('wiki.php?title=' . $row['encoded_title'], $row['encoded_title']),
			'U_UNTRACK' => url('favorites.php?del=' . $row['id'] . '&amp;token=' . AppContext::get_session()->get_token()),
		));
	}
	$result->dispose();

	$view->display();
}

require_once('../kernel/footer.php');

?>
