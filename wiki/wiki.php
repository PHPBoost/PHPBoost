<?php
/*##################################################
*                               wiki.php
*                            -------------------
*   begin                : October 09, 2006
*   copyright          : (C) 2006 Sautel Benoit
*   email                : ben.popeye@phpboost.com
*
*
###################################################
*
*   This program is free software; you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation; either version 2 of the License, or
*   (at your option) any later version.
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

include_once('../includes/begin.php'); 
include_once('../wiki/lang/' . $CONFIG['lang'] . '/wiki_' . $CONFIG['lang'] . '.php');

define('ALTERNATIVE_CSS', 'wiki');

include('../wiki/wiki_functions.php');

//Titre de l'article
$encoded_title = !empty($_GET['title']) ? securit($_GET['title']) : '';
//numéro de l'article (utile pour les archives)
$id_contents = !empty($_GET['id_contents']) ? numeric($_GET['id_contents']) : 0;

$num_rows = 0;
$parse_redirection = false;

//Requêtes préliminaires utiles par la suite
if( !empty($encoded_title) ) //Si on connait son titre
{
	$result = $sql->query_while("SELECT a.id, a.is_cat, a.hits, a.redirect, a.id_cat, a.title, a.encoded_title, a.is_cat, a.defined_status, a.nbr_com, f.id AS id_favorite, a.undefined_status, a.auth, c.menu, c.content
		FROM ".PREFIX."wiki_articles AS a
		LEFT JOIN ".PREFIX."wiki_contents AS c ON c.id_contents = a.id_contents
		LEFT JOIN ".PREFIX."wiki_favorites AS f ON f.id_article = a.id
		WHERE a.encoded_title = '" . $encoded_title . "'
		GROUP BY a.id"
	, __LINE__, __FILE__);	
	$num_rows = $sql->sql_num_rows($result, "SELECT COUNT(*) FROM ".PREFIX."wiki_articles WHERE encoded_title = '" . $encoded_title . "'", __LINE__, __FILE__);
	$article_infos = $sql->sql_fetch_assoc($result);
	$sql->close($result);
	$id_article = $article_infos['id'];

	if( !empty($article_infos['redirect']) )//Si on est redirigé
	{
		$ex_title = $article_infos['title'];
		$id_redirection = $article_infos['id'];
		
		$result = $sql->query_while("SELECT a.id, a.is_cat, a.hits, a.redirect, a.id_cat, a.title, a.encoded_title, a.is_cat, a.nbr_com, a.defined_status, f.id AS id_favorite, a.undefined_status, a.auth, c.menu, c.content
		FROM ".PREFIX."wiki_articles AS a
		LEFT JOIN ".PREFIX."wiki_contents AS c ON c.id_contents = a.id_contents
		LEFT JOIN ".PREFIX."wiki_favorites AS f ON f.id_article = a.id
		WHERE a.id = '" . $article_infos['redirect'] . "'
		GROUP BY a.id"
		, __LINE__, __FILE__);	
		$article_infos = $sql->sql_fetch_assoc($result);
		$sql->close($result);
		$id_article = $article_infos['id'];
		$parse_redirection = true;
	}
}
//Sinon on cherche dans les archives
elseif( !empty($id_contents) )
{
	$result = $sql->query_while("SELECT a.title, a.encoded_title, a.id, c.id_contents, a.id_cat, a.is_cat, a.defined_status, a.undefined_status, a.nbr_com, f.id AS id_favorite, c.menu, c.content
	FROM ".PREFIX."wiki_contents AS c
	LEFT JOIN ".PREFIX."wiki_articles AS a ON a.id = c.id_article
	LEFT JOIN ".PREFIX."wiki_favorites AS f ON f.id_article = a.id
	WHERE c.id_contents = '" . $id_contents . "'"
	, __LINE__, __FILE__);
	$article_infos = $sql->sql_fetch_assoc($result);
	$sql->close($result);
	$id_article = $article_infos['id'];
	$num_rows = 1;
}

//Barre d'arborescence
$speed_bar_key = 'wiki';
require_once('../wiki/wiki_speed_bar.php');

$page_title = (!empty($article_infos['title']) ? $article_infos['title'] . ' - ' : '') . (!empty($_WIKI_CONFIG['wiki_name']) ? $_WIKI_CONFIG['wiki_name'] : $LANG['wiki']);
define('TITLE', $page_title);

include_once('../includes/header.php'); 

$template->set_filenames(array(
'wiki' => '../templates/' . $CONFIG['theme'] . '/wiki/wiki.tpl',
'index' => '../templates/' . $CONFIG['theme'] . '/wiki/index.tpl'
));
$template->assign_vars(array(
'WIKI_PATH' => $template->module_data_path('wiki')
));

//Si il s'agit d'un article
if( (!empty($encoded_title) || !empty($id_contents)) && $num_rows > 0 )
{ 
	if( $_WIKI_CONFIG['count_hits'] != 0 )//Si on prend en compte le nombre de vus
	{
		$sql->query_inject("UPDATE " . LOW_PRIORITY . " ".PREFIX."wiki_articles SET hits = hits + 1 WHERE id = '" . $article_infos['id'] . "'", __LINE__, __FILE__);
	}

	//Si c'est une archive
	if( $id_contents > 0 )
	{
		$template->assign_block_vars('warning', array(
			'UPDATED_ARTICLE' => $LANG['wiki_warning_updated_article']
		));
		$id_article = $article_infos['id'];
	}
	else //Sinon on affiche statut, avertissements en tout genre et redirection
	{
		//Si on doit parser le bloc redirection
		if( $parse_redirection )
		{
			$template->assign_block_vars('redirect', array(
				'REDIRECTED' => sprintf($LANG['wiki_redirecting_from'], '<a href="' . transid('wiki.php?title=' . $encoded_title, $encoded_title) . '">' . $ex_title . '</a>')
			));
			$general_auth = empty($article_infos['auth']) ? true : false;
			echo $general_auth;
			if( ((!$general_auth || $groups->check_auth($_WIKI_CONFIG['auth'], WIKI_REDIRECT)) && ($general_auth || $groups->check_auth($article_auth , WIKI_REDIRECT))) )
			{
				$template->assign_block_vars('redirect.remove_redirection', array(
					'L_REMOVE_REDIRECTION' => $LANG['wiki_remove_redirection'],
					'U_REMOVE_REDIRECTION' => transid('action.php?del_redirection=' . $id_redirection),
					'L_ALERT_REMOVE_REDIRECTION' => str_replace('\'', '\\\'', $LANG['wiki_alert_delete_redirection'])
				));
			}
		}
		
		//Cet article comporte un type
		if( $article_infos['defined_status'] != 0 )
		{
			if( $article_infos['defined_status'] < 0 && !empty($article_infos['undefined_status']) )
			$template->assign_block_vars('status', array(
				'ARTICLE_STATUS' => second_parse(wiki_no_rewrite($article_infos['undefined_status']))
			));
			elseif( $article_infos['defined_status'] > 0 && is_array($LANG['wiki_status_list'][$article_infos['defined_status'] - 1]) )
			$template->assign_block_vars('status', array(
				'ARTICLE_STATUS' => $LANG['wiki_status_list'][$article_infos['defined_status'] - 1][1]
			));
		}
	}
	
	if( !empty($article_infos['menu']) )
	$template->assign_block_vars('menu', array(
		'MENU' => $article_infos['menu']
	));
	
	$template->assign_vars(array(
		'TITLE' => $article_infos['title'],
		'CONTENTS' => second_parse(wiki_no_rewrite(stripslashes($article_infos['content']))),
		'HITS' => ($_WIKI_CONFIG['count_hits'] != 0 && $id_contents == 0) ? sprintf($LANG['wiki_article_hits'], (int)$article_infos['hits']) : '',
		'L_SUB_CATS' => $LANG['wiki_subcats'],
		'L_SUB_ARTICLES' => $LANG['wiki_subarticles'],
		'L_TABLE_OF_CONTENTS' => $LANG['wiki_table_of_contents'],
	));
	if( $article_infos['is_cat'] == 1 && $id_contents == 0 ) //Catégorie non archivée
	{
		//On liste les articles de la catégorie et ses sous catégories
		$result = $sql->query_while("SELECT a.title, a.encoded_title, a.id
		FROM ".PREFIX."wiki_articles AS a
		LEFT JOIN ".PREFIX."wiki_contents AS c ON c.id_contents = a.id_contents
		WHERE a.id_cat = '" . $article_infos['id_cat'] . "' AND a.id != '" . $id_article . "' AND a.redirect = 0
		ORDER BY a.title",
		__LINE__, __FILE__);

		$num_articles = $sql->sql_num_rows($result, "SELECT COUNT(*) FROM ".PREFIX."wiki_articles WHERE a.id_cat = '" . $article_infos['id_cat'] . "' AND a.id != '" . $id_article . "' AND a.redirect = 0", __LINE__, __FILE__);
		
		$template->assign_block_vars('cat', array(
			'RSS' => $num_articles > 0 ? '<a href="rss.php?cat=' . $article_infos['id_cat'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/rss.gif" alt="RSS" /></a>' : ''
		));

		while( $row = $sql->sql_fetch_assoc($result) )
		{
			$template->assign_block_vars('cat.list_art', array(
				'TITLE' => $row['title'],
				'U_ARTICLE' => transid('wiki.php?title=' . $row['encoded_title'], $row['encoded_title'])
			));
		}
		if( $num_articles == 0 )
		$template->assign_block_vars('cat.no_sub_article', array(
			'NO_SUB_ARTICLE' => $LANG['wiki_no_sub_article']
		));
		
		$i = 0;
		foreach( $_WIKI_CATS as $key => $value )
		{
			if( $value['id_parent'] == $id_cat )
			{
				$template->assign_block_vars('cat.list_cats', array(
					'NAME' => $value['name'],
					'U_CAT' => transid('wiki.php?title=' . url_encode_rewrite($value['name']), url_encode_rewrite($value['name']))
				));
				$i++;
			}
		}
		if( $i == 0 )
		$template->assign_block_vars('cat.no_sub_cat', array(
			'NO_SUB_CAT' => $LANG['wiki_no_sub_cat']
		));
	}
	
	$page_type = $article_infos['is_cat']  == 1 ? 'cat' : 'article';
	include('../wiki/wiki_tools.php');
	$template->assign_var_from_handle('TOOLS', 'wiki_tools');
	
	$template->pparse('wiki');	
}
//Si l'article n'existe pas
elseif( !empty($encoded_title) && $num_rows == 0 )
{
	$template->assign_block_vars('message', array(
		'ARTICLE_DOES_NOT_EXIST' => $LANG['wiki_article_does_not_exist']
	));
	$template->pparse('wiki');	
}
//Sinon c'est l'accueil
else
{
	if( $_WIKI_CONFIG['last_articles'] > 1 )
	{
		$result = $sql->query_while("SELECT a.title, a.encoded_title, a.id
		FROM ".PREFIX."wiki_articles AS a
		LEFT JOIN ".PREFIX."wiki_contents AS c ON c.id_contents = a.id_contents
		WHERE a.redirect = 0
		ORDER BY c.timestamp DESC
		LIMIT 0, " . $_WIKI_CONFIG['last_articles'], __LINE__, __FILE__);		
		$articles_number = $sql->sql_num_rows($result, "SELECT COUNT(*) FROM ".PREFIX."wiki_articles WHERE encoded_title = '" . $encoded_title . "'", __LINE__, __FILE__);
		
		$template->assign_block_vars('last_articles', array(
			'L_ARTICLES' => $LANG['wiki_last_articles_list'],
			'RSS' => $articles_number > 0 ? '<a href="rss.php"><img src="../templates/' . $CONFIG['theme'] . '/images/rss.gif" alt="RSS" /></a>' : ''
		));
		
		$i = 0;
		while( $row = $sql->sql_fetch_assoc($result) )
		{
			$template->assign_block_vars('last_articles.list', array(
				'ARTICLE' => $row['title'],
				'TR' => ($i > 0 && ($i%2 == 0)) ? '</tr><tr>' : '',
				'U_ARTICLE' => transid('wiki.php?title=' . $row['encoded_title'], $row['encoded_title'])
			));
			$i++;
		}
		
		if( $articles_number == 0 )
		{
			$template->assign_vars(array(
				'L_NO_ARTICLE' => '<td style="text-align:center;" class="row2">' . $LANG['wiki_no_article'] . '</td>',
			));
		}
	}
	//Affichage de toutes les catégories si c'est activé
	if( $_WIKI_CONFIG['display_cats'] != 0 )
	{
		$template->assign_block_vars('cat_list', array(
			'L_CATS' => $LANG['wiki_cats_list']
		));
		$i = 0;
		foreach( $_WIKI_CATS as $id => $infos )
		{
			//Si c'est une catégorie mère
			if( $infos['id_parent'] == 0 )
			{
				$template->assign_block_vars('cat_list.list', array(
					'CAT' => $infos['name'],
					'U_CAT' => transid('wiki.php?title=' . url_encode_rewrite($infos['name']), url_encode_rewrite($infos['name']))
				));
				$i++;
			}
		}
		if( $i == 0 )
		$template->assign_vars(array(
			'L_NO_CAT' => $LANG['wiki_no_cat'],
		));
	}
	
	$template->assign_vars(array(
		'TITLE' => !empty($_WIKI_CONFIG['wiki_name']) ? $_WIKI_CONFIG['wiki_name'] : $LANG['wiki'],
		'INDEX_TEXT' => !empty($_WIKI_CONFIG['index_text']) ? second_parse(wiki_no_rewrite($_WIKI_CONFIG['index_text'])) : $LANG['wiki_empty_index'],
		'L_EXPLORER' => $LANG['wiki_explorer'],
		'U_EXPLORER' => transid('explorer.php'),
		'WIKI_PATH' => $template->module_data_path('wiki')
	));

	$page_type = 'index';
	include('../wiki/wiki_tools.php');
	$template->assign_var_from_handle('TOOLS', 'wiki_tools');

	$template->pparse('index');
}

include_once('../includes/footer.php'); 

?>