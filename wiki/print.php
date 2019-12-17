<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 07 31
 * @since       PHPBoost 2.0 - 2008 09 02
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

require_once('../kernel/begin.php');
load_module_lang('wiki');
$config = WikiConfig::load();

include('../wiki/wiki_functions.php');

//Id de l'article à afficher en version imprimable
$request = AppContext::get_request();

$article_id = $request->get_getint('id', 0);

//Requêtes préliminaires utiles par la suite
if ($article_id > 0) //Si on connait son titre
{
	$result = PersistenceContext::get_querier()->select("SELECT a.id, a.is_cat, a.hits, a.redirect, a.id_cat, a.title, a.encoded_title, a.is_cat, a.defined_status, com_topic.number_comments, f.id AS id_favorite, a.undefined_status, a.auth, c.menu, c.content
	FROM " . PREFIX . "wiki_articles a
	LEFT JOIN " . PREFIX . "wiki_contents c ON c.id_contents = a.id_contents
	LEFT JOIN " . PREFIX . "wiki_favorites f ON f.id_article = a.id
	LEFT JOIN " . DB_TABLE_COMMENTS_TOPIC . " com_topic ON a.id = com_topic.id_in_module AND com_topic.module_id = 'wiki'
	WHERE a.id = :id
	GROUP BY a.id, a.is_cat, a.hits, a.redirect, a.id_cat, a.title, a.encoded_title, a.is_cat, a.defined_status, com_topic.number_comments, id_favorite, a.undefined_status, a.auth, c.menu, c.content", array(
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
		GROUP BY a.id, a.is_cat, a.hits, a.redirect, a.id_cat, a.title, a.encoded_title, a.is_cat, com_topic.number_comments, a.defined_status, id_favorite, a.undefined_status, a.auth, c.menu, c.content", array(
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
