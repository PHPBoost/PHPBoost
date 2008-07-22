<?php
/*##################################################
 *                               admin_wiki.php
 *                            -------------------
 *   begin                : November 11, 2006
 *   copyright          : (C) 2006 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
 *
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

require_once('../admin/admin_begin.php');
load_module_lang('wiki');
define('TITLE', $LANG['administration'] . ' : ' . $LANG['wiki']);
require_once('../admin/admin_header.php');
include_once('../wiki/wiki_functions.php');

$Cache->Load_file('wiki');

$wiki_name = retrieve(POST, 'wiki_name', $LANG['wiki'], TSTRING_UNSECURE);
$index_text = stripslashes(wiki_parse(retrieve(POST, 'contents', '', TSTRING_UNSECURE)));
$last_articles = retrieve(POST, 'last_articles', 0);
$display_cats = !empty($_POST['display_cats']) ? 1 : 0;
$count_hits = !empty($_POST['count_hits']) ? 1 : 0;

if( !empty($_POST['update']) )  //Mise à jour
{
	$_WIKI_CONFIG['wiki_name'] = $wiki_name;
	$_WIKI_CONFIG['last_articles'] = $last_articles;
	$_WIKI_CONFIG['display_cats'] = $display_cats;
	$_WIKI_CONFIG['index_text'] = $index_text;
	$_WIKI_CONFIG['count_hits'] = $count_hits;
	$_WIKI_CONFIG['auth'] = serialize($_WIKI_CONFIG['auth']);

	$Sql->Query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($_WIKI_CONFIG)) . "' WHERE name = 'wiki'", __LINE__, __FILE__);
	//Régénération du cache
	$Cache->Generate_module_file('wiki');	
}

$Cache->Load_file('wiki');

$Template->Set_filenames(array(
	'wiki_config'=> 'wiki/admin_wiki.tpl'
));

$Template->Assign_vars(array(
	'KERNEL_EDITOR' => display_editor(),
	'HITS_SELECTED' => ($_WIKI_CONFIG['count_hits'] > 0) ? 'checked="checked"' : '',
	'WIKI_NAME' => $_WIKI_CONFIG['wiki_name'],
	'NOT_DISPLAY_CATS' => ( $_WIKI_CONFIG['display_cats'] == 0 ) ? 'checked="checked"' : '',
	'DISPLAY_CATS' => ( $_WIKI_CONFIG['display_cats'] != 0 ) ? 'checked="checked"' : '',
	'LAST_ARTICLES' => $_WIKI_CONFIG['last_articles'],
	'DESCRIPTION' => wiki_unparse($_WIKI_CONFIG['index_text']),
	'L_UPDATE' => $LANG['update'],
	'L_RESET' => $LANG['reset'],
	'L_WIKI_MANAGEMENT' => $LANG['wiki_management'],
	'L_WIKI_GROUPS' => $LANG['wiki_groups_config'],
	'L_CONFIG_WIKI' => $LANG['wiki_config'],
	'L_WHOLE_WIKI' => 'Configuration générale',
	'L_INDEX_WIKI' => 'Accueil du wiki',
	'L_COUNT_HITS' => 'Compter le nombre de fois que sont vus les articles', 
	'L_WIKI_NAME' => 'Nom du wiki',
	'L_DISPLAY_CATS' => 'Afficher la liste des catégories principales sur l\'accueil',
	'L_NOT_DISPLAY' => 'Ne pas afficher',
	'L_DISPLAY' => 'Afficher',
	'L_LAST_ARTICLES' => 'Nombre des derniers articles à afficher sur l\'accueil (0 pour désactiver)',
	'L_DESCRIPTION' => 'Texte de l\'accueil'
));
	
$Template->Pparse('wiki_config');

require_once('../admin/admin_footer.php');

?>