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

//On charge le template associé
$Template->set_filenames(array('wiki_tools'=> 'wiki/wiki_tools.tpl'));

$Template->put_all(array(
	'L_CONTRIBUTION_TOOLS' => $LANG['wiki_contribution_tools'],
	'L_OTHER_TOOLS' => $LANG['wiki_other_tools'],
));

//Définition des images associés
$action_pictures = array(
	'edit' => 'edit.png',
	'delete' => 'delete_article.png',
	'history' => 'history.png',
	'create_article' => 'create_article.png',
	'create_cat' => 'add_cat.png',
	'add_article' => 'add_article.png',
	'edit_index' => 'edit_index.png',
	'rename' => 'rename.png',
	'move' => 'move.png',
	'random_page' => 'random_page.png',
	'restriction_level' => 'restriction_level.png',
	'article_status' => 'article_status.png',
	'redirect' => 'redirect.png',
	'search' => 'search.png',
	'follow-article' => 'follow-article.png',
	'followed-articles' => 'followed-articles.png',
	'rss' => 'rss.png',
	'explorer' => 'explorer.png',
	'print' => 'print_mini.png'
);

$confirm = array();
$confirm_others = array();

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

//Si on regarde un article
if ($page_type == 'article' || $page_type == 'cat')
{
	$tools = array();
	//Consultation de l'historique
	$tools[$LANG['wiki_history']] = array(url('history.php?id=' . $id_article), 'history');
	//Edition
	if ((!$general_auth || $User->check_auth($_WIKI_CONFIG['auth'], WIKI_EDIT)) && ($general_auth || $User->check_auth($article_auth , WIKI_EDIT)))
	{
		$tools[$LANG['update']] = array(url('post.php?id=' . $id_article), 'edit');
	}
	//Suppression
	if ((!$general_auth || $User->check_auth($_WIKI_CONFIG['auth'], WIKI_DELETE)) && ($general_auth || $User->check_auth($article_auth , WIKI_DELETE)))
	{
		if ($page_type == 'article')
		{
			$tools[$LANG['delete']] = array(url('action.php?del_article=' . $id_article . '&amp;token=' . $Session->get_token()), 'delete');
			//Message de confirmation de suppression (directe sinon)
			$confirm[$LANG['delete']] = 'return confirm(\'' . str_replace('\'', '\\\'', $LANG['wiki_confirm_remove_article']) . '\');';
		}
		else
		{
			$tools[$LANG['delete']] = array(url('property.php?del=' . $id_article), 'delete');
		}
	}
	//Renommer
	if ((!$general_auth || $User->check_auth($_WIKI_CONFIG['auth'], WIKI_RENAME)) && ($general_auth || $User->check_auth($article_auth , WIKI_RENAME)))
	{
		$tools[$LANG['wiki_rename']] = array(url('property.php?rename=' . $article_infos['id']), 'rename');
	}
	//Redirections
	if ((!$general_auth || $User->check_auth($_WIKI_CONFIG['auth'], WIKI_REDIRECT)) && ($general_auth  || $User->check_auth($article_auth , WIKI_REDIRECT)))
	{
		$tools[$LANG['wiki_redirections']] = array(url('property.php?redirect=' . $article_infos['id']), 'redirect');
	}
	//Déplacement
	if ((!$general_auth || $User->check_auth($_WIKI_CONFIG['auth'], WIKI_MOVE)) && ($general_auth || $User->check_auth($article_auth , WIKI_MOVE)))
	{
		$tools[$LANG['wiki_move']] = array(url('property.php?move=' . $article_infos['id']), 'move');
	}
	if ($page_type == 'cat')
	{
		if ((!$general_auth || $User->check_auth($_WIKI_CONFIG['auth'], WIKI_CREATE_ARTICLE)) && ($general_auth || $User->check_auth($article_auth , WIKI_CREATE_ARTICLE)))//Création d'un article
		{
			$tools[$LANG['wiki_add_article']] = array(url('post.php' . ($id_cat > 0 ? '?id_parent=' . $id_cat : '')), 'add_article');
		}
		if ((!$general_auth || $User->check_auth($_WIKI_CONFIG['auth'], WIKI_CREATE_CAT)) && ($general_auth || $User->check_auth($article_auth , WIKI_CREATE_CAT)))//Création d'une catégorie
		{
			$tools[$page_type == 'cat' ? $LANG['wiki_add_cat'] : $LANG['wiki_create_cat']] = array(url('post.php?type=cat&amp;id_parent=' . $id_cat), 'create_cat');
		}
	}
	//Statut de l'article
	if ((!$general_auth || $User->check_auth($_WIKI_CONFIG['auth'], WIKI_STATUS)) && ($general_auth || $User->check_auth($article_auth , WIKI_STATUS)))
	{
		$tools[$LANG['wiki_article_status']] = array(url('property.php?status=' . $article_infos['id']), 'article_status');
	}
	//Niveau de restricton
	if ($User->check_auth($_WIKI_CONFIG['auth'], WIKI_RESTRICTION))
	{
		$tools[$LANG['wiki_restriction_level']] = array(url('property.php?auth=' . $article_infos['id']), 'restriction_level');
	}

	$tools[$LANG['printable_version']] = array(url('print.php?id=' . $article_infos['id']), 'print');
}
//Accueil du wiki
elseif ($page_type == 'index')
{
	$tools = array();
	$tools[$LANG['wiki_history']] = array(url('history.php'), 'history');
	if ($User->check_level(ADMIN_LEVEL))
	{
		$tools[$LANG['wiki_update_index']] = array(url('admin_wiki.php#index'), 'edit_index');
	}
}

$other_tools = array();
//Création d'un article
if ($User->check_auth($_WIKI_CONFIG['auth'], WIKI_CREATE_ARTICLE))
{
	$other_tools[ $LANG['wiki_create_article']] = array(url('post.php'), 'create_article');
}
//Création d'une catégorie
if ($User->check_auth($_WIKI_CONFIG['auth'], WIKI_CREATE_CAT))
{
	$other_tools[$LANG['wiki_create_cat']] = array(url('post.php?type=cat'), 'create_cat');
}
//Page au hasard
{
	$other_tools[$LANG['wiki_random_page']] = array(url('property.php?random=1'), 'random_page');
}
//Recherche
$other_tools[$LANG['wiki_search']] = array(url('search.php'), 'search');
//Sujets suivis (membres seulement)
if ($User->check_level(MEMBER_LEVEL))
{
	$other_tools[$LANG['wiki_followed_articles']] = array(url('favorites.php'), 'followed-articles');
	//Suivre ce sujet (articles)
	if ($page_type == 'article' || $page_type == 'cat')
	{
		if ($article_infos['id_favorite'] > 0)
		{
			$other_tools[$LANG['wiki_unwatch_this_topic']] = array(url('favorites.php?del=' . $id_article . '&amp;token=' . $Session->get_token()), 'follow-article');
			$confirm_others[$LANG['wiki_unwatch_this_topic']] = 'return confirm(\'' . str_replace('\'', '\\\'', $LANG['wiki_confirm_unwatch_this_topic']) . '\');';
		}
		else
		{
			$other_tools[$LANG['wiki_watch']] = array(url('favorites.php?add=' . $id_article), 'follow-article');
		}
	}
}
//Discussion
if (($page_type == 'article' || $page_type == 'cat') && (!$general_auth || $User->check_auth($_WIKI_CONFIG['auth'], WIKI_COM)) && ($general_auth || $User->check_auth($article_auth , WIKI_COM)))
{
	$Template->put_all(array(
		'C_ACTIV_COM' => true,
		'U_COM' => url('property.php?idcom=' . $id_article . '&amp;com=0'),
		'L_COM' => $LANG['wiki_article_com_article'] . ($article_infos['nbr_com'] > 0 ? ' (' . $article_infos['nbr_com'] . ')' : '')
	));
}

//Explorateur du wiki
$other_tools[$LANG['wiki_explorer_short']] = array(url('explorer.php'), 'explorer');

//Flux RSS du wiki
if ($page_type == 'index')
{
	$other_tools[$LANG['wiki_rss']] = array(url(PATH_TO_ROOT . '/syndication.php?m=wiki'), 'rss');
}
if ($page_type == 'cat')
{
	$other_tools[$LANG['wiki_rss']] = array(url(PATH_TO_ROOT . '/syndication.php?m=wiki&amp;cat=' . $article_infos['id_cat']), 'rss');
}
//On parse
if ($page_type == 'index' || $page_type == 'article' || $page_type = 'cat')
{
	$i = 1;
	foreach ($tools as $key => $value)
	{
		$Template->assign_block_vars('tool', array(
			'U_TOOL' => '../wiki/'.$value[0],
			'L_TOOL' => $key
		));
		$Template->assign_block_vars('contribution_tools', array(
			'DM_A_CLASS' => $action_pictures[$value[1]],
			'U_ACTION' => '../wiki/'.$value[0],
			'L_ACTION' => $key,
			'ONCLICK' => (array_key_exists($key, $confirm)) ? $confirm[$key] : ''
			));
			$i++;
	}
}
$nbr_values = count($other_tools);
$i = 1;
foreach ($other_tools as $key => $value)
{
	$Template->assign_block_vars('tool', array(
		'U_TOOL' => '../wiki/'.$value[0],
		'L_TOOL' => $key
	));
	if ($i < $nbr_values && !empty($key))
	{
		$Template->assign_block_vars('tool.separation', array());
	}

	$Template->assign_block_vars('other_tools', array(
		'DM_A_CLASS' => $action_pictures[$value[1]],
		'U_ACTION' => '../wiki/'.$value[0],
		'L_ACTION' => $key,
		'ONCLICK' => (array_key_exists($key, $confirm_others)) ? $confirm_others[$key] : ''
		));
		$i++;
}

?>