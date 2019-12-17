<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 10 24
 * @since       PHPBoost 1.6 - 2006 10 09
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

if (defined('PHPBOOST') !== true) exit;

//On charge le template associé
$jstools_tpl = new FileTemplate('wiki/wiki_js_tools.tpl');

$jstools_tpl->put_all(array(
	'C_TINYMCE_EDITOR' => (int)(AppContext::get_current_user()->get_editor() == 'TinyMCE'),
	'L_PLEASE_ENTER_A_TITLE' => $LANG['wiki_please_enter_a_link_name'],
	'L_INSERT_LINK' => $LANG['wiki_insert_a_link'],
	'L_INSERT' => $LANG['wiki_insert_link'],
	'L_TITLE_LINK' => $LANG['wiki_title_link'],
	'L_NO_JS' => $LANG['wiki_no_js_insert_link'],
	'L_EXPLAIN_PARAGRAPH_1' => sprintf($LANG['wiki_explain_paragraph'], 1),
	'L_EXPLAIN_PARAGRAPH_2' => sprintf($LANG['wiki_explain_paragraph'], 2),
	'L_EXPLAIN_PARAGRAPH_3' => sprintf($LANG['wiki_explain_paragraph'], 3),
	'L_EXPLAIN_PARAGRAPH_4' => sprintf($LANG['wiki_explain_paragraph'], 4),
	'L_EXPLAIN_PARAGRAPH_5' => sprintf($LANG['wiki_explain_paragraph'], 5),
	'L_HELP_WIKI_TAGS' => $LANG['wiki_help_tags'],
	'L_PARAGRAPH_NAME' => $LANG['wiki_paragraph_name'],
	'PARAGRAPH_NAME' => $LANG['wiki_paragraph_name_example'],
));


?>
