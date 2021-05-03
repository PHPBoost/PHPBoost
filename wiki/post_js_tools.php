<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 05 19
 * @since       PHPBoost 1.6 - 2006 10 09
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

if (defined('PHPBOOST') !== true) exit;

//On charge le template associé
$lang = LangLoader::get('common', 'wiki');
$jstools_tpl = new FileTemplate('wiki/wiki_js_tools.tpl');
$jstools_tpl->add_lang(array_merge($lang, LangLoader::get('common-lang'), LangLoader::get('form-lang')));

$jstools_tpl->put_all(array(
	'L_PARAGRAPH_1' => sprintf($lang['wiki.paragraph'], 1),
	'L_PARAGRAPH_2' => sprintf($lang['wiki.paragraph'], 2),
	'L_PARAGRAPH_3' => sprintf($lang['wiki.paragraph'], 3),
	'L_PARAGRAPH_4' => sprintf($lang['wiki.paragraph'], 4),
	'L_PARAGRAPH_5' => sprintf($lang['wiki.paragraph'], 5),
	//
	'L_HELP_WIKI_TAGS' => $lang['wiki.help.tags'],
	'L_PARAGRAPH_NAME' => $lang['wiki.warning.paragraph.name'],
	'PARAGRAPH_NAME' => $lang['wiki.paragraph.name'],
	'L_PLEASE_ENTER_A_TITLE' => $lang['wiki.link.name.add'],
	'L_INSERT_LINK' => $lang['wiki.insert.link'],
	'L_TITLE_LINK' => $lang['wiki.link.title'],
	'L_NO_JS' => $lang['wiki.no.js.insert.link'],
));


?>
