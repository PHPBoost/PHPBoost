<?php
/*##################################################
 *                               admin_wiki.php
 *                            -------------------
 *   begin                : November 11, 2006
 *   copyright            : (C) 2006 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
 *
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

require_once('../admin/admin_begin.php');
load_module_lang('wiki');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');
include_once('../wiki/wiki_functions.php');

$config = WikiConfig::load();
$request = AppContext::get_request();

$update = $request->get_postvalue('update', false);
$display_categories_on_index = $request->get_postvalue('display_categories_on_index', false);
$hits_counter = $request->get_postvalue('hits_counter', false);

$index_text = stripslashes(wiki_parse(retrieve(POST, 'contents', '', TSTRING_AS_RECEIVED)));
if ($update) //Mise à jour
{
	$config->set_wiki_name(TextHelper::strprotect(retrieve(POST, 'wiki_name', $LANG['wiki'], TSTRING_AS_RECEIVED), TextHelper::HTML_PROTECT, TextHelper::ADDSLASHES_NONE));
	$config->set_number_articles_on_index(retrieve(POST, 'number_articles_on_index', 0));
	if ($display_categories_on_index)
		$config->display_categories_on_index();
	else
		$config->hide_categories_on_index();
	if ($hits_counter)
		$config->enable_hits_counter();
	else
		$config->disable_hits_counter();
	$config->set_index_text(stripslashes(wiki_parse(retrieve(POST, 'contents', '', TSTRING_AS_RECEIVED))));
	
	WikiConfig::save();
	
	//Régénération du cache
	WikiCategoriesCache::invalidate();
}

$tpl = new FileTemplate('wiki/admin_wiki.tpl');

//On travaille uniquement en BBCode, on force le langage de l'éditeur
$content_editor = AppContext::get_content_formatting_service()->get_default_factory();
$editor = $content_editor->get_editor();
$editor->set_identifier('contents');

$tpl->put_all(array(
	'KERNEL_EDITOR' => $editor->display(),
	'HITS_SELECTED' => $config->is_hits_counter_enabled() ? 'checked="checked"' : '',
	'WIKI_NAME' => $config->get_wiki_name(),
	'HIDE_CATEGORIES_ON_INDEX' => !$config->are_categories_displayed_on_index() ? 'checked="checked"' : '',
	'DISPLAY_CATEGORIES_ON_INDEX' => $config->are_categories_displayed_on_index() ? 'checked="checked"' : '',
	'NUMBER_ARTICLES_ON_INDEX' => $config->get_number_articles_on_index(),
	'DESCRIPTION' => FormatingHelper::unparse($config->get_index_text()),
	'L_UPDATE' => $LANG['update'],
	'L_RESET' => $LANG['reset'],
	'L_PREVIEW' => $LANG['preview'],
	'L_WIKI_MANAGEMENT' => $LANG['wiki_management'],
	'L_WIKI_GROUPS' => $LANG['wiki_groups_config'],
	'L_CONFIG_WIKI' => $LANG['wiki_config'],
	'L_WHOLE_WIKI' => $LANG['wiki_config_whole'],
	'L_INDEX_WIKI' => $LANG['wiki_index'],
	'L_HITS_COUNTER' => $LANG['wiki_count_hits'], 
	'L_WIKI_NAME' => $LANG['wiki_name'],
	'L_DISPLAY_CATEGORIES_ON_INDEX' => $LANG['wiki_display_cats'],
	'L_NOT_DISPLAY' => $LANG['wiki_no_display'],
	'L_DISPLAY' => $LANG['wiki_display'],
	'L_NUMBER_ARTICLES_ON_INDEX' => $LANG['wiki_last_articles'],
	'L_NUMBER_ARTICLES_ON_INDEX_EXPLAIN' => $LANG['wiki_last_articles_explain'],
	'L_DESCRIPTION' => $LANG['wiki_desc']
));

$tpl->display();

require_once('../admin/admin_footer.php');

?>