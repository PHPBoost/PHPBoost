<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 28
 * @since       PHPBoost 1.6 - 2006 10 09
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../kernel/begin.php');

$lang = LangLoader::get_all_langs('wiki');

$config = WikiConfig::load();

include('../wiki/wiki_functions.php');
require_once('../wiki/wiki_auth.php');

if (!AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_READ))
{
	$error_controller = PHPBoostErrors::user_not_authorized();
	DispatchManager::redirect($error_controller);
}

//Titre de l'article
$encoded_title = retrieve(GET, 'title', '');
//numéro de l'article (utile pour les archives)
$id_contents = (int)retrieve(GET, 'id_contents', 0);

$num_rows = 0;
$parse_redirection = false;

//Requêtes préliminaires utiles par la suite
if (!empty($encoded_title)) //Si on connait son titre
{
	try {
		$article_infos = PersistenceContext::get_querier()->select_single_row_query("SELECT a.id, a.is_cat, a.hits, a.redirect, a.id_cat, a.title, a.encoded_title, a.is_cat, a.defined_status, com_topic.comments_number, f.id AS id_favorite, a.undefined_status, a.auth, c.menu, c.content, c.timestamp
		FROM " . PREFIX . "wiki_articles a
		LEFT JOIN " . PREFIX . "wiki_contents c ON c.id_contents = a.id_contents
		LEFT JOIN " . PREFIX . "wiki_favorites f ON f.id_article = a.id
		LEFT JOIN " . DB_TABLE_COMMENTS_TOPIC . " com_topic ON a.id = com_topic.id_in_module AND com_topic.module_id = 'wiki'
		WHERE a.encoded_title = :encoded_title
		GROUP BY a.id, a.is_cat, a.hits, a.redirect, a.id_cat, a.title, a.encoded_title, a.is_cat, a.defined_status, com_topic.comments_number, id_favorite, a.undefined_status, a.auth, c.menu, c.content, c.timestamp", array(
			'encoded_title' => $encoded_title
		));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	$num_rows = 1;
	$id_article = $article_infos['id'];

	if (!empty($article_infos['redirect']))//Si on est redirigé
	{
		try {
			$encoded_title = PersistenceContext::get_querier()->get_column_value(PREFIX . "wiki_articles", 'encoded_title', 'WHERE id=:id', array('id' => $article_infos['redirect']));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		AppContext::get_response()->set_status_code(301);
		AppContext::get_response()->redirect('/wiki/' . url('wiki.php?title=' . $encoded_title, $encoded_title));
	}
}
//Sinon on cherche dans les archives
elseif (!empty($id_contents))
{
	try {
		$article_infos = PersistenceContext::get_querier()->select_single_row_query("SELECT a.title, a.encoded_title, a.id, c.id_contents, a.id_cat, a.is_cat, a.defined_status, a.undefined_status, com_topic.comments_number, f.id AS id_favorite, c.menu, c.content, c.timestamp
		FROM " . PREFIX . "wiki_contents c
		LEFT JOIN " . PREFIX . "wiki_articles a ON a.id = c.id_article
		LEFT JOIN " . PREFIX . "wiki_favorites f ON f.id_article = a.id
		LEFT JOIN " . DB_TABLE_COMMENTS_TOPIC . " com_topic ON a.id = com_topic.id_in_module AND com_topic.module_id = 'wiki'
		WHERE c.id_contents = :id", array(
			'id' => $id_contents
		));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	$id_article = $article_infos['id'];
	$num_rows = 1;
}

//Barre d'arborescence
$bread_crumb_key = 'wiki';
require_once('../wiki/wiki_bread_crumb.php');

$page_title = (!empty($article_infos['title']) ? stripslashes($article_infos['title']) . ' - ' : '') . ($config->get_wiki_name() ? $config->get_wiki_name() : $lang['wiki.module.title']);
define('TITLE', $page_title);

if (isset($article_infos['content']))
	define('DESCRIPTION', TextHelper::cut_string(@strip_tags(FormatingHelper::second_parse(wiki_no_rewrite($article_infos['content'])), '<br><br/>'), 150));

require_once('../kernel/header.php');

//Si il s'agit d'un article
if ((!empty($encoded_title) || !empty($id_contents)) && $num_rows > 0)
{
	$view = new FileTemplate('wiki/wiki.tpl');
	$view->add_lang($lang);

	if ($config->is_hits_counter_enabled())//Si on prend en compte le nombre de vus
		PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "wiki_articles SET hits = hits + 1 WHERE id = " . $article_infos['id']);

	//Si c'est une archive
	if ($id_contents > 0)
	{
		$view->put('C_WARNING_UPDATE', true);
		$id_article = $article_infos['id'];
	}
	else //Sinon on affiche statut, avertissements en tout genre et redirection
	{
		//Si on doit parser le bloc redirection
		/*
		if ($parse_redirection)
		{
			$view->assign_block_vars('redirect', array(
				'REDIRECTED' => sprintf($lang['wiki.redirecting.from'], '<a href="' . url('wiki.php?title=' . $encoded_title, $encoded_title) . '">' . $ex_title . '</a>')
			));
			$general_auth = empty($article_infos['auth']) ? true : false;

			if (((!$general_auth || AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_REDIRECT)) && ($general_auth || AppContext::get_current_user()->check_auth($article_auth , WIKI_REDIRECT))))
			{
				$view->assign_block_vars('redirect.remove_redirection', array(
					'L_REMOVE_REDIRECTION' => $lang['wiki.remove.redirection'],
					'U_REMOVE_REDIRECTION' => url('action.php?del_redirection=' . $id_redirection . '&amp;token=' . AppContext::get_session()->get_token()),
					'L_ALERT_REMOVE_REDIRECTION' => str_replace('\'', '\\\'', $lang['wiki.alert.delete.redirection'])
				));
			}
		}
		*/

		//Cet article comporte un type
		if ($article_infos['defined_status'] != 0)
		{
			if ($article_infos['defined_status'] < 0 && !empty($article_infos['undefined_status']))
			$view->assign_block_vars('status', array(
				'ARTICLE_STATUS' => FormatingHelper::second_parse(wiki_no_rewrite($article_infos['undefined_status']))
			));
			elseif ($article_infos['defined_status'] > 0 && is_array($lang['wiki.status.list'][$article_infos['defined_status'] - 1]))
			$view->assign_block_vars('status', array(
				'ARTICLE_STATUS' => $lang['wiki.status.list'][$article_infos['defined_status'] - 1][1]
			));
		}
	}

	if (!empty($article_infos['menu']))
	$view->assign_block_vars('menu', array(
		'MENU' => $article_infos['menu']
	));

	$date = new Date($article_infos['timestamp'], Timezone::SERVER_TIMEZONE);
	$categories = WikiCategoriesCache::load()->get_categories();

	$content = FormatingHelper::second_parse(wiki_no_rewrite($article_infos['content']));
	$rich_content = HooksService::execute_hook_display_action('wiki', $content, $article_infos);

	$view->put_all(array_merge(
		Date::get_array_tpl_vars($date,'date'),
		array(
			'C_STICKY_MENU' => $config->is_sticky_menu_enabled(),
			'C_NEW_CONTENT' => ContentManagementConfig::load()->module_new_content_is_enabled_and_check_date('wiki', $article_infos['timestamp']),

			'ID'             => $article_infos['id'],
			'ID_CATEGORY'    => $article_infos['id_cat'],
			'CATEGORY_TITLE' => $article_infos['id_cat'] == 0 ? ($config->get_wiki_name() ? $config->get_wiki_name() : $lang['wiki.module.title']) : stripslashes($categories[$article_infos['id_cat']]['title']),
			'TITLE'          => stripslashes($article_infos['title']),
			'CONTENT'        => $rich_content,

			'L_VIEWS_NUMBER' => ($config->is_hits_counter_enabled() && $id_contents == 0) ? sprintf($lang['wiki.page.views.number'], (int)$article_infos['hits']) : '',
		)
	));

	if ($article_infos['is_cat'] == 1 && $id_contents == 0) //Catégorie non archivée
	{
		//On liste les articles de la catégorie et ses sous catégories
		$result = PersistenceContext::get_querier()->select("SELECT a.title, a.encoded_title, a.id
		FROM " . PREFIX . "wiki_articles a
		LEFT JOIN " . PREFIX . "wiki_contents c ON c.id_contents = a.id_contents
		WHERE a.id_cat = :id_cat AND a.id != :id AND a.redirect = 0
		ORDER BY a.title", array (
			'id_cat' => $article_infos['id_cat'],
			'id' => $id_article
		));

		$num_articles = $result->get_rows_count();

		$view->assign_block_vars('cat', array(
		));

		while ($row = $result->fetch())
		{
			$view->assign_block_vars('cat.list_art', array(
				'TITLE' => stripslashes($row['title']),

				'U_ITEM' => url('wiki.php?title=' . $row['encoded_title'], $row['encoded_title'])
			));
		}
		$result->dispose();

		if ($num_articles == 0)
		$view->assign_block_vars('cat.no_sub_article', array(
			'NO_SUB_ARTICLE' => $lang['wiki.no.sub.item']
		));

		$i = 0;
		foreach (WikiCategoriesCache::load()->get_categories() as $key => $cat)
		{
			if ($cat['id_parent'] == $id_cat)
			{
				$view->assign_block_vars('cat.list_cats', array(
					'NAME' => stripslashes($cat['title']),

					'U_CATEGORY' => url('wiki.php?title=' . $cat['encoded_title'], $cat['encoded_title'])
				));
				$i++;
			}
		}
	}

	$page_type = $article_infos['is_cat']  == 1 ? 'cat' : 'article';
	include('../wiki/wiki_tools.php');
	$view->put('WIKI_TOOLS', $tools_view);

	$view->display();
}
//Si l'article n'existe pas
elseif (!empty($encoded_title) && $num_rows == 0)
{
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}
//Sinon c'est l'accueil
else
{
	$modulesLoader = AppContext::get_extension_provider_service();
	$module_name = 'wiki';
	$module = $modulesLoader->get_provider($module_name);
	if ($module->has_extension_point(HomePageExtensionPoint::EXTENSION_POINT))
	{
		echo $module->get_extension_point(HomePageExtensionPoint::EXTENSION_POINT)->get_home_page()->get_view()->display();
	}
	elseif (!$no_alert_on_error)
	{
		$controller = new UserErrorController(LangLoader::get_message('warning.error', 'warning-lang'),
            'The module <strong>' . $module_name . '</strong> doesn\'t have the get_home_page function!', UserErrorController::FATAL);
        DispatchManager::redirect($controller);
	}
}

require_once('../kernel/footer.php');

?>
