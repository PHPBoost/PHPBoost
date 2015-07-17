<?php
/*##################################################
 *                               post_js_tools.php
 *                            -------------------
 *   begin                : May 29, 2007
 *   copyright            : (C) 2007 Sautel Benoit
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