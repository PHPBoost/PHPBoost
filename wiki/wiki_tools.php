<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 1.6 - 2006 10 29
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

if (defined('PHPBOOST') !== true)	exit;

$lang = LangLoader::get_all_langs('wiki');

$config = WikiConfig::load();

$tools_view = new FileTemplate('wiki/wiki_tools.tpl');
$tools_view->add_lang($lang);

// Get authorizations
if (!empty($article_infos['auth']))
{
	$article_auth = TextHelper::unserialize($article_infos['auth']);
	$general_auth = false;
}
else
{
	$general_auth = true;
	$article_auth = array();
}

$tools_view->put_all(array(
	'C_INDEX_PAGE' => $page_type == 'index',

	'U_EDIT_INDEX' => Url::to_rel('/wiki/' . url('admin_wiki.php')),

	'U_HISTORY' => !empty($id_article) ? Url::to_rel('/wiki/' . url('history.php?id=' . $id_article)) : Url::to_rel('/wiki/' . url('history.php')),

	'C_EDIT' => (!$general_auth || AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_EDIT)) && ($general_auth || AppContext::get_current_user()->check_auth($article_auth , WIKI_EDIT)),
	'U_EDIT' => Url::to_rel('/wiki/' . url('post.php?id=' . $id_article . ($page_type == 'cat' ? '&type=cat' : ''))),

	'C_DELETE' => (!$general_auth || AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_DELETE)) && ($general_auth || AppContext::get_current_user()->check_auth($article_auth , WIKI_DELETE)),
	'U_DELETE' => $page_type == 'article' ? Url::to_rel('/wiki/' . url('action.php?del_article=' . $id_article . '&amp;token=' . AppContext::get_session()->get_token())) : Url::to_rel('/wiki/' . url('property.php?del=' . $id_article)),

	'C_RENAME' => (!$general_auth || AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_RENAME)) && ($general_auth || AppContext::get_current_user()->check_auth($article_auth , WIKI_RENAME)),
	'U_RENAME' => isset($article_infos['id']) ? Url::to_rel('/wiki/' . url('property.php?rename=' . $article_infos['id'])) : '',

	'C_REDIRECT' => (!$general_auth || AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_REDIRECT)) && ($general_auth  || AppContext::get_current_user()->check_auth($article_auth , WIKI_REDIRECT)),
	'U_REDIRECT' => isset($article_infos['id']) ? Url::to_rel('/wiki/' . url('property.php?redirect=' . $article_infos['id'])) : '',

	'C_MOVE' => (!$general_auth || AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_MOVE)) && ($general_auth || AppContext::get_current_user()->check_auth($article_auth , WIKI_MOVE)),
	'U_MOVE' => isset($article_infos['id']) ? Url::to_rel('/wiki/' . url('property.php?move=' . $article_infos['id'])) : '',

	'C_STATUS' => (!$general_auth || AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_STATUS)) && ($general_auth || AppContext::get_current_user()->check_auth($article_auth , WIKI_STATUS)),
	'U_STATUS' => isset($article_infos['id']) ? Url::to_rel('/wiki/' . url('property.php?status=' . $article_infos['id'])) : '',

	'C_RESTRICTION' => AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_RESTRICTION),
	'U_RESTRICTION' => isset($article_infos['id']) ? Url::to_rel('/wiki/' . url('property.php?auth=' . $article_infos['id'])) : '',

	'U_RANDOM' => Url::to_rel('/wiki/' . url('property.php?random=1')),

	'U_PRINT' => isset($article_infos['id']) ? Url::to_rel('/wiki/' . url('print.php?id=' . $article_infos['id'])) : '',

	'L_TRACK' => (isset($article_infos['id_favorite']) && $article_infos['id_favorite'] > 0) ? $lang['wiki.untrack'] : $lang['wiki.track'],
	'U_TRACK' => (isset($article_infos['id_favorite']) && $article_infos['id_favorite'] > 0) ? Url::to_rel('/wiki/' . url('favorites.php?del=' . $id_article . '&amp;token=' . AppContext::get_session()->get_token())) : Url::to_rel('/wiki/' . url('favorites.php?add=' . $id_article)),
));

// Comments
if (($page_type == 'article' || $page_type == 'cat') && (!$general_auth || AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_COM)) && ($general_auth || AppContext::get_current_user()->check_auth($article_auth , WIKI_COM)))
{
	$tools_view->put_all(array(
		'C_ACTIVE_COMMENTS' => true,
		'U_COMMENTS'        => url('property.php?idcom=' . $id_article . '&amp;com=0'),
		'COMMENTS_NUMBER'   => (isset($article_infos['comments_number']) && ($article_infos['comments_number'] > 0) ? $article_infos['comments_number'] : '0')
	));
}
?>
