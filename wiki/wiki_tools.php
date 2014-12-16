<?php
/*##################################################
 *                              wiki_tools.php
 *                            -------------------
 *   begin                : October 29, 2006
 *   copyright            : (C) 2006 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
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

if (defined('PHPBOOST') !== true)	exit;

$config = WikiConfig::load();

//On charge le template associé
$tools_tpl = new FileTemplate('wiki/wiki_tools.tpl');

//Définition du tableau comprenant les autorisation de chaque groupe
if (!empty($article_infos['auth']))
{
	$article_auth = unserialize($article_infos['auth']);
	$general_auth = false;
}
else
{
	$general_auth = true;
	$article_auth = array();
}
	
$tools_tpl->put_all(array(
	'C_INDEX_PAGE' => $page_type == 'index',
	
	'L_OTHER_TOOLS' => $LANG['wiki_other_tools'],

	'L_EDIT_INDEX' => $LANG['wiki_update_index'],
	'U_EDIT_INDEX' => PATH_TO_ROOT . '/wiki/' . url('admin_wiki.php#index'),

	'L_HISTORY' => $LANG['wiki_history'],
	'U_HISTORY' => !empty($id_article) ? PATH_TO_ROOT . '/wiki/' . url('history.php?id=' . $id_article) : PATH_TO_ROOT . '/wiki/' . url('history.php'),

	'C_EDIT' => (!$general_auth || AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_EDIT)) && ($general_auth || AppContext::get_current_user()->check_auth($article_auth , WIKI_EDIT)),
	'L_EDIT' => $LANG['update'],
	'U_EDIT' => PATH_TO_ROOT . '/wiki/' . url('post.php?id=' . $id_article),

	'C_DELETE' => (!$general_auth || AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_DELETE)) && ($general_auth || AppContext::get_current_user()->check_auth($article_auth , WIKI_DELETE)),
	'L_DELETE' => LangLoader::get_message('delete', 'common'),
	'U_DELETE' => $page_type == 'article' ? PATH_TO_ROOT . '/wiki/' . url('action.php?del_article=' . $id_article . '&amp;token=' . AppContext::get_session()->get_token()) : PATH_TO_ROOT . '/wiki/' . url('property.php?del=' . $id_article),

	'C_RENAME' => (!$general_auth || AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_RENAME)) && ($general_auth || AppContext::get_current_user()->check_auth($article_auth , WIKI_RENAME)),
	'L_RENAME' => $LANG['wiki_rename'],
	'U_RENAME' => PATH_TO_ROOT . '/wiki/' . url('property.php?rename=' . $article_infos['id']),

	'C_REDIRECT' => (!$general_auth || AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_REDIRECT)) && ($general_auth  || AppContext::get_current_user()->check_auth($article_auth , WIKI_REDIRECT)),
	'L_REDIRECT' => $LANG['wiki_redirections'],
	'U_REDIRECT' => PATH_TO_ROOT . '/wiki/' . url('property.php?redirect=' . $article_infos['id']),

	'C_MOVE' => (!$general_auth || AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_MOVE)) && ($general_auth || AppContext::get_current_user()->check_auth($article_auth , WIKI_MOVE)),
	'L_MOVE' => $LANG['wiki_move'],
	'U_MOVE' => PATH_TO_ROOT . '/wiki/' . url('property.php?move=' . $article_infos['id']),

	'C_STATUS' => (!$general_auth || AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_STATUS)) && ($general_auth || AppContext::get_current_user()->check_auth($article_auth , WIKI_STATUS)),
	'L_STATUS' => $LANG['wiki_article_status'],
	'U_STATUS' => PATH_TO_ROOT . '/wiki/' . url('property.php?status=' . $article_infos['id']),

	'C_RESTRICTION' => AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_RESTRICTION),
	'L_RESTRICTION' => $LANG['wiki_restriction_level'],
	'U_RESTRICTION' => PATH_TO_ROOT . '/wiki/' . url('property.php?auth=' . $article_infos['id']),
	
	'L_RANDOM' => $LANG['wiki_random_page'],
	'U_RANDOM' => PATH_TO_ROOT . '/wiki/' . url('property.php?random=1'),

	'L_PRINT' => $LANG['printable_version'],
	'U_PRINT' => PATH_TO_ROOT . '/wiki/' . url('print.php?id=' . $article_infos['id']),

	'L_WATCH' => $article_infos['id_favorite'] > 0 ? $LANG['wiki_unwatch_this_topic'] : $LANG['wiki_watch'],
	'U_WATCH' => $article_infos['id_favorite'] > 0 ? PATH_TO_ROOT . '/wiki/' . url('favorites.php?del=' . $id_article . '&amp;token=' . AppContext::get_session()->get_token()) : PATH_TO_ROOT . '/wiki/' . url('favorites.php?add=' . $id_article),
));

//Discussion
if (($page_type == 'article' || $page_type == 'cat') && (!$general_auth || AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_COM)) && ($general_auth || AppContext::get_current_user()->check_auth($article_auth , WIKI_COM)))
{
	$tools_tpl->put_all(array(
		'C_ACTIV_COM' => true,
		'U_COM' => url('property.php?idcom=' . $id_article . '&amp;com=0'),
		'L_COM' => $LANG['wiki_article_com_article'] . ($article_infos['number_comments'] > 0 ? ' (' . $article_infos['number_comments'] . ')' : '')
	));
}
?>