<?php
/*##################################################
*                                 print.php
*                            -------------------
*   begin                : September 02, 2008
*   copyright            : (C) 2008 Sautel Benoit
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

//Id de l'article à afficher en version imprimable
$article_id = retrieve(GET, 'id', 0);

//Requêtes préliminaires utiles par la suite
if ($article_id > 0) //Si on connait son titre
{
	$result = PersistenceContext::get_querier()->select("SELECT a.id, a.is_cat, a.hits, a.redirect, a.id_cat, a.title, a.encoded_title, a.is_cat, a.defined_status, com_topic.number_comments, f.id AS id_favorite, a.undefined_status, a.auth, c.menu, c.content
	FROM " . PREFIX . "wiki_articles a
	LEFT JOIN " . PREFIX . "wiki_contents c ON c.id_contents = a.id_contents
	LEFT JOIN " . PREFIX . "wiki_favorites f ON f.id_article = a.id
	LEFT JOIN " . DB_TABLE_COMMENTS_TOPIC . " com_topic ON a.id = com_topic.id_in_module AND com_topic.module_id = 'wiki'
	WHERE a.id = :id
	GROUP BY a.id", array(
		'id' => $article_id
	));
	$article_infos = $result->fetch();
	$result->dispose();

	if (!empty($article_infos['redirect']))//Si on est redirigé
	{
		$id_redirection = $article_infos['id'];
		
		$result = PersistenceContext::get_querier()->select("SELECT a.id, a.is_cat, a.hits, a.redirect, a.id_cat, a.title, a.encoded_title, a.is_cat, com_topic.number_comments, a.defined_status, f.id AS id_favorite, a.undefined_status, a.auth, c.menu, c.content
		FROM " . PREFIX . "wiki_articles a
		LEFT JOIN " . PREFIX . "wiki_contents c ON c.id_contents = a.id_contents
		LEFT JOIN " . PREFIX . "wiki_favorites f ON f.id_article = a.id
		LEFT JOIN " . DB_TABLE_COMMENTS_TOPIC . " com_topic ON a.id = com_topic.id_in_module AND com_topic.module_id = 'wiki'
		WHERE a.id = :id
		GROUP BY a.id", array(
			'id' => $article_infos['redirect']
		));
		$article_infos = $result->fetch();
		$result->dispose();
	}
}

if (empty($article_infos['id']))
	exit;

require_once(PATH_TO_ROOT . '/kernel/header_no_display.php');

$template = new FileTemplate('framework/content/print.tpl');

$template->put_all(array(
	'PAGE_TITLE' => stripslashes($article_infos['title']) . ($config->get_wiki_name() ? $config->get_wiki_name() : $LANG['wiki']),
	'TITLE' => stripslashes($article_infos['title']),
	'L_XML_LANGUAGE' => $LANG['xml_lang'],
	'CONTENT' => FormatingHelper::second_parse($article_infos['content'])
));

$template->display();

require_once(PATH_TO_ROOT . '/kernel/footer_no_display.php');
?>