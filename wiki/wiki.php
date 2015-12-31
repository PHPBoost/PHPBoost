<?php
/*##################################################
*                                 wiki.php
*                            -------------------
*   begin                : October 09, 2006
*   copyright            : (C) 2006 Sautel Benoit
*   email                : ben.popeye@phpboost.com
*
*
 ###################################################
*
*  This program is free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*
 ###################################################*/

require_once('../kernel/begin.php');
load_module_lang('wiki');
$config = WikiConfig::load();

include('../wiki/wiki_functions.php');

//Titre de l'article
$encoded_title = retrieve(GET, 'title', '');
//numéro de l'article (utile pour les archives)
$id_contents = retrieve(GET, 'id_contents', 0);

$num_rows = 0;
$parse_redirection = false;

//Requêtes préliminaires utiles par la suite
if (!empty($encoded_title)) //Si on connait son titre
{
	try {
		$article_infos = PersistenceContext::get_querier()->select_single_row_query("SELECT a.id, a.is_cat, a.hits, a.redirect, a.id_cat, a.title, a.encoded_title, a.is_cat, a.defined_status, com_topic.number_comments, f.id AS id_favorite, a.undefined_status, a.auth, c.menu, c.content
		FROM " . PREFIX . "wiki_articles a
		LEFT JOIN " . PREFIX . "wiki_contents c ON c.id_contents = a.id_contents
		LEFT JOIN " . PREFIX . "wiki_favorites f ON f.id_article = a.id
		LEFT JOIN " . DB_TABLE_COMMENTS_TOPIC . " com_topic ON a.id = com_topic.id_in_module AND com_topic.module_id = 'wiki'
		WHERE a.encoded_title = :encoded_title
		GROUP BY a.id", array(
			'encoded_title' => $encoded_title
		));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}
	
	$num_rows = 1;
	$id_article = $article_infos['id'];
	
	if (!empty($article_infos['redirect']))//Si on est redirig¦
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
		$article_infos = PersistenceContext::get_querier()->select_single_row_query("SELECT a.title, a.encoded_title, a.id, c.id_contents, a.id_cat, a.is_cat, a.defined_status, a.undefined_status, com_topic.number_comments, f.id AS id_favorite, c.menu, c.content
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

$page_title = (!empty($article_infos['title']) ? stripslashes($article_infos['title']) . ' - ' : '') . ($config->get_wiki_name() ? $config->get_wiki_name() : $LANG['wiki']);
define('TITLE', $page_title);

require_once('../kernel/header.php');

//Si il s'agit d'un article
if ((!empty($encoded_title) || !empty($id_contents)) && $num_rows > 0)
{
	$tpl = new FileTemplate('wiki/wiki.tpl');
	
	if ($config->is_hits_counter_enabled())//Si on prend en compte le nombre de vus
		PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "wiki_articles SET hits = hits + 1 WHERE id = " . $article_infos['id']);

	//Si c'est une archive
	if ($id_contents > 0)
	{
		$tpl->assign_block_vars('warning', array(
			'UPDATED_ARTICLE' => $LANG['wiki_warning_updated_article']
		));
		$id_article = $article_infos['id'];
	}
	else //Sinon on affiche statut, avertissements en tout genre et redirection
	{
		//Si on doit parser le bloc redirection
		/*
		if ($parse_redirection)
		{
			$tpl->assign_block_vars('redirect', array(
				'REDIRECTED' => sprintf($LANG['wiki_redirecting_from'], '<a href="' . url('wiki.php?title=' . $encoded_title, $encoded_title) . '">' . $ex_title . '</a>')
			));
			$general_auth = empty($article_infos['auth']) ? true : false;
			
			if (((!$general_auth || AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_REDIRECT)) && ($general_auth || AppContext::get_current_user()->check_auth($article_auth , WIKI_REDIRECT))))
			{
				$tpl->assign_block_vars('redirect.remove_redirection', array(
					'L_REMOVE_REDIRECTION' => $LANG['wiki_remove_redirection'],
					'U_REMOVE_REDIRECTION' => url('action.php?del_redirection=' . $id_redirection . '&amp;token=' . AppContext::get_session()->get_token()),
					'L_ALERT_REMOVE_REDIRECTION' => str_replace('\'', '\\\'', $LANG['wiki_alert_delete_redirection'])
				));
			}
		}
		*/
		
		//Cet article comporte un type
		if ($article_infos['defined_status'] != 0)
		{
			if ($article_infos['defined_status'] < 0 && !empty($article_infos['undefined_status']))
			$tpl->assign_block_vars('status', array(
				'ARTICLE_STATUS' => FormatingHelper::second_parse(wiki_no_rewrite($article_infos['undefined_status']))
			));
			elseif ($article_infos['defined_status'] > 0 && is_array($LANG['wiki_status_list'][$article_infos['defined_status'] - 1]))
			$tpl->assign_block_vars('status', array(
				'ARTICLE_STATUS' => $LANG['wiki_status_list'][$article_infos['defined_status'] - 1][1]
			));
		}
	}
	
	if (!empty($article_infos['menu']))
	$tpl->assign_block_vars('menu', array(
		'MENU' => $article_infos['menu']
	));
	
	$tpl->put_all(array(
		'ID' => $article_infos['id'],
		'ID_CAT' => $article_infos['id_cat'],
		'TITLE' => stripslashes($article_infos['title']),
		'CONTENTS' => FormatingHelper::second_parse(wiki_no_rewrite($article_infos['content'])),
		'HITS' => ($config->is_hits_counter_enabled() && $id_contents == 0) ? sprintf($LANG['wiki_article_hits'], (int)$article_infos['hits']) : '',
		'L_SUB_CATS' => $LANG['wiki_subcats'],
		'L_SUB_ARTICLES' => $LANG['wiki_subarticles'],
		'L_TABLE_OF_CONTENTS' => $LANG['wiki_table_of_contents'],
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
		
		$tpl->assign_block_vars('cat', array(
		));

		while ($row = $result->fetch())
		{
			$tpl->assign_block_vars('cat.list_art', array(
				'TITLE' => stripslashes($row['title']),
				'U_ARTICLE' => url('wiki.php?title=' . $row['encoded_title'], $row['encoded_title'])
			));
		}
		$result->dispose();
		
		if ($num_articles == 0)
		$tpl->assign_block_vars('cat.no_sub_article', array(
			'NO_SUB_ARTICLE' => $LANG['wiki_no_sub_article']
		));
		
		$i = 0;
		foreach (WikiCategoriesCache::load()->get_categories() as $key => $cat)
		{
			if ($cat['id_parent'] == $id_cat)
			{
				$tpl->assign_block_vars('cat.list_cats', array(
					'NAME' => stripslashes($cat['title']),
					'U_CAT' => url('wiki.php?title=' . $cat['encoded_title'], $cat['encoded_title'])
				));
				$i++;
			}
		}
		if ($i == 0)
		$tpl->assign_block_vars('cat.no_sub_cat', array(
			'NO_SUB_CAT' => $LANG['wiki_no_sub_cat']
		));
	}
	
	$page_type = $article_infos['is_cat']  == 1 ? 'cat' : 'article';
	include('../wiki/wiki_tools.php');
	$tpl->put('wiki_tools', $tools_tpl);
	
	$tpl->display();
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
		$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), 
            'Le module <strong>' . $module_name . '</strong> n\'a pas de fonction get_home_page!', UserErrorController::FATAL);
        DispatchManager::redirect($controller);
	}
}

require_once('../kernel/footer.php');

?>
