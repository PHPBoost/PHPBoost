<?php
/*##################################################
 *                              articles_begin.php
 *                            -------------------
 *   begin                : October 18, 2007
 *   copyright            : (C) 2007 Viarre régis
 *   email                : crowkait@phpboost.com
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

if (defined('PHPBOOST') !== true)
exit;

require_once('articles_constants.php');
load_module_lang('articles');

$Cache->load('articles');

$idartcat = retrieve(GET, 'cat', 0);
$idart = retrieve(GET, 'id', 0);
$invisible = retrieve(GET, 'invisible', false, TBOOL);

if (isset($ARTICLES_CAT[$idartcat]))
{
	$articles_categories = new ArticlesCats();
	$articles_categories->bread_crumb($idartcat);

	if (!empty($idart))
	{
		$articles = $Sql->query_array(DB_TABLE_ARTICLES, '*', "WHERE id = '" . $idart . "'", __LINE__, __FILE__);
		$idartcat = $articles['idcat'];

		define('TITLE', $ARTICLES_LANG['title_articles'] . ' - ' . $articles['title']);

		$Bread_crumb->add($articles['title'], 'articles' . url('.php?cat=' . $idartcat . '&amp;id=' . $idart, '-' . $idartcat . '-' . $idart . '+' . Url::encode_rewrite($articles['title']) . '.php'));

		if (isset($_GET['com']))
			$Bread_crumb->add($LANG['com_s'], '');
	}
	else
	{
		if (isset($_GET['invisible']))
			$Bread_crumb->add($ARTICLES_LANG['waiting_articles'], REWRITED_SCRIPT);
			
		if (isset($_GET['invisible']))
			define('TITLE', $ARTICLES_LANG['title_articles'] . ' - ' . $ARTICLES_LANG['waiting_articles']);
		else
			define('TITLE', $ARTICLES_LANG['title_articles'] . ' - ' . $ARTICLES_CAT[$idartcat]['name']);
	}
}
else
{
	$Bread_crumb->add($ARTICLES_LANG['title_articles'], 'articles.php');
	if (!defined('TITLE'))
		define('TITLE', $ARTICLES_LANG['title_articles']);
}
?>