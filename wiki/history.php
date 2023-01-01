<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 1.6 - 2006 10 09
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../kernel/begin.php');

$lang = LangLoader::get_all_langs('wiki');

$config = WikiConfig::load();

$id_article = (int)retrieve(GET, 'id', 0);
$field = retrieve(GET, 'field', '');
$order = retrieve(GET, 'order', '');

if (!empty($id_article))
{
	try {
		$article_infos = PersistenceContext::get_querier()->select_single_row(PREFIX . 'wiki_articles', array('title', 'auth', 'encoded_title', 'id_cat'), 'WHERE id = :id', array('id' => $id_article));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	define('TITLE', sprintf($lang['wiki.item.history'], stripslashes($article_infos['title'])));
	define('DESCRIPTION', sprintf($lang['wiki.history.seo'], stripslashes($article_infos['title'])));
}
else
	define('TITLE', $lang['wiki.full.history']);

$bread_crumb_key = !empty($id_article) ? 'wiki_history_article' : 'wiki_history';
require_once('../wiki/wiki_bread_crumb.php');

require_once('../kernel/header.php');

if (!empty($id_article))
{
	$view = new FileTemplate('wiki/history.tpl');
	$view->add_lang($lang);

	$view->put_all(array(
		'C_ITEM' => true,
		'TITLE' => stripslashes($article_infos['title']),
		'U_ITEM' => url('wiki.php?title=' . $article_infos['encoded_title'], $article_infos['encoded_title'])
	));

	$general_auth = empty($article_infos['auth']);
	$article_auth = !empty($article_infos['auth']) ? TextHelper::unserialize($article_infos['auth']) : array();
	$restore_auth = (!$general_auth || AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_RESTORE_ARCHIVE)) && ($general_auth || AppContext::get_current_user()->check_auth($article_auth , WIKI_RESTORE_ARCHIVE)) ? true : false;
	$delete_auth = (!$general_auth || AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_DELETE_ARCHIVE)) && ($general_auth || AppContext::get_current_user()->check_auth($article_auth , WIKI_DELETE_ARCHIVE)) ? true : false;

	//on va chercher le contenu de la page
	$result = PersistenceContext::get_querier()->select("SELECT
		a.title, a.encoded_title,
		c.timestamp, c.id_contents, c.user_id, c.user_ip, c.change_reason, c.id_article, c.activ,
		m.display_name, m.user_groups, m.level
		FROM " . PREFIX . "wiki_contents c
		LEFT JOIN " . PREFIX . "wiki_articles a ON a.id = c.id_article
		LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = c.user_id
		WHERE c.id_article = :id
		ORDER BY c.timestamp DESC", array(
			'id' => $id_article
		));

	while ($row = $result->fetch())
	{
		//Restauration
		$actions = ($row['activ'] != 1 && $restore_auth) ? '<a class="offload" href="' . url('action.php?restore=' . $row['id_contents']. '&amp;token=' . AppContext::get_session()->get_token()) . '" aria-label="' . $lang['wiki.restore.version'] . '"><i class="fa fa-undo" aria-hidden="true"></i></a> &nbsp; ' : '';
		$restore_link =
		//Suppression
		$actions .= ($row['activ'] != 1 && $delete_auth) ? '<a href="' . url('action.php?del_contents=' . $row['id_contents']. '&amp;token=' . AppContext::get_session()->get_token()) . '" aria-label="' . $lang['common.delete'] . '" data-confirmation="' . $lang['wiki.confirm.delete.archive'] . '"><i class="far fa-trash-alt" aria-hidden="true"></i></a>' : '';

		$group_color = User::get_group_color($row['user_groups'], $row['level']);

		$view->assign_block_vars('list', array(
			'C_ACTIONS'         => $row['activ'] != 1 && $restore_auth || $row['activ'] != 1 && $delete_auth,
			'C_RESTORE'         => $row['activ'] != 1 && $restore_auth,
			'C_DELETE'          => $row['activ'] != 1 && $delete_auth,
			'C_CURRENT_VERSION' => $row['activ'] == 1,

			'AUTHOR'          => !empty($row['display_name']) ? '<a href="'. UserUrlBuilder::profile($row['user_id'])->rel() . '" class="'.UserService::get_level_class($row['level']).' offload"' . (!empty($group_color) ? ' style="color:' . $group_color . '"' : '') . '>' . $row['display_name'] . '</a>' : $row['user_ip'],
			'DATE'            => Date::to_format($row['timestamp'], Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE),

			'U_ARTICLE'       => $row['activ'] == 1 ? url('wiki.php?title=' . $row['encoded_title'], $row['encoded_title']) : url('wiki.php?id_contents=' . $row['id_contents']),
			'U_RESTORE'       => url('action.php?restore=' . $row['id_contents']. '&amp;token=' . AppContext::get_session()->get_token()),
			'U_DELETE'        => url('action.php?del_contents=' . $row['id_contents']. '&amp;token=' . AppContext::get_session()->get_token()),

			'L_CHANGE_REASON' => $row['change_reason']
		));
	}
	$result->dispose();

	$view->display();
}
else //On affiche la liste des modifications
{
	$_WIKI_NBR_ARTICLES_A_PAGE_IN_HISTORY = 25;

	//Champs sur lesquels on ordonne
	$field = ($field == 'title') ? 'title' : 'timestamp';
	$order = $order == 'asc' ? 'asc' : 'desc';

	//On compte le nombre d'articles
	$nbr_articles = PersistenceContext::get_querier()->count(PREFIX . "wiki_articles", 'WHERE redirect = 0');

	//On instancie la classe de pagination
	$page = AppContext::get_request()->get_getint('p', 1);
	$pagination = new ModulePagination($page, $nbr_articles, $_WIKI_NBR_ARTICLES_A_PAGE_IN_HISTORY);
	$pagination->set_url(new Url('/wiki/history.php?field=' . $field . '&amp;order=' . $order . '&amp;p=%d'));

	if ($pagination->current_page_is_empty() && $page > 1)
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	$view = new FileTemplate('wiki/history.tpl');
	$view->add_lang($lang);

	$view->put_all(array(
		'C_PAGINATION' => $pagination->has_several_pages(),

		'TOP_TITLE'    => ($field == 'title' && $order == 'asc') ? ''  : url('history.php?p=' . $page . '&amp;field=title&amp;order=asc'),
		'BOTTOM_TITLE' => ($field == 'title' && $order == 'desc') ? '' : url('history.php?p=' . $page . '&amp;field=title&amp;order=desc'),
		'TOP_DATE'     => ($field == 'timestamp' && $order == 'asc') ? ''  : url('history.php?p=' . $page . '&amp;field=timestamp&amp;order=asc'),
		'BOTTOM_DATE'  => ($field == 'timestamp' && $order == 'desc') ? '' : url('history.php?p=' . $page . '&amp;field=timestamp&amp;order=desc'),
		'PAGINATION'   => $pagination->display(),
	));

	$result = PersistenceContext::get_querier()->select("SELECT
		a.title, a.encoded_title,
		c.timestamp, c.id_contents AS id, c.user_id, c.user_ip, c.id_article, c.activ,  a.id_contents,
		m.display_name, m.user_groups, m.level
		FROM " . PREFIX . "wiki_articles a
		LEFT JOIN " . PREFIX . "wiki_contents c ON c.id_contents = a.id_contents
		LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = c.user_id
		WHERE a.redirect = 0
		ORDER BY " . ($field == 'title' ? 'a' : 'c') . "." . $field . " " . $order . "
		LIMIT :number_items_per_page OFFSET :display_from",
			array(
				'number_items_per_page' => $pagination->get_number_items_per_page(),
				'display_from' => $pagination->get_display_from()
			)
		);

	while ($row = $result->fetch())
	{
		$group_color = User::get_group_color($row['user_groups'], $row['level']);

		$view->assign_block_vars('list', array(
			'C_AUTHOR_EXISTS'      => !empty($row['display_name']),
			'C_AUTHOR_GROUP_COLOR' => !empty($group_color),

			'TITLE'               => stripslashes($row['title']),
			'AUTHOR_DISPLAY_NAME' => $row['display_name'],
			'AUTHOR_LEVEL_CLASS'  => UserService::get_level_class($row['level']),
			'AUTHOR_IP'           =>$row['user_ip'],
			'AUTHOR_GROUP_COLOR'  =>$group_color,
			'LAST_UPDATE'         => Date::to_format($row['timestamp'], Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE),

			'U_AUTHOR_PROFILE' => UserUrlBuilder::profile($row['user_id'])->rel(),
			'U_ITEM'           => url('wiki.php?title=' . $row['encoded_title'], $row['encoded_title'])
		));
	}
	$result->dispose();

	$view->display();
}

require_once('../kernel/footer.php');
?>
