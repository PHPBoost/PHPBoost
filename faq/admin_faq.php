<?php
/*##################################################
 *                               admin_faq.php
 *                            -------------------
 *   begin                : February 27, 2008
 *   copyright          : (C) 2008 Sautel Benoit
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

include_once('../includes/admin_begin.php');
include_once('faq_begin.php'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
include_once('../includes/admin_header.php');

$Template->Set_filenames(array(
	'admin_faq' => '../templates/' . $CONFIG['theme'] . '/faq/admin_faq.tpl'
));

if( !empty($_POST['submit']) )
{
	$FAQ_CONFIG['faq_name'] = !empty($_POST['faq_name']) ? securit($_POST['faq_name']) : $FAQ_LANG['faq'];
	$FAQ_CONFIG['num_cols'] = !empty($_POST['num_cols']) ? numeric($_POST['num_cols']) : 3;
	$FAQ_CONFIG['display_block'] = !empty($_POST['display_mode']) && $_POST['display_mode'] == 'inline' ? false : true;
	$auth_read = isset($_POST['groups_auth1']) ? $_POST['groups_auth1'] : '';
	$auth_write = isset($_POST['groups_auth2']) ? $_POST['groups_auth2'] : '';
	$FAQ_CONFIG['global_auth'] = $Group->Return_array_auth($auth_read, $auth_write);
	$FAQ_CONFIG['root'] = $FAQ_CATS[0];
	
	$Sql->Query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($FAQ_CONFIG)) . "' WHERE name = 'faq'", __LINE__, __FILE__);
	//Régénération du cache
	$Cache->Generate_module_file('faq');
	
	redirect(transid('admin_faq.php', '', '&'));
}

//Création du tableau des groupes.
$array_groups = $Group->Create_groups_array();

$Template->Assign_vars(array(
	'L_FAQ_MANAGEMENT' => $FAQ_LANG['faq_management'],
	'L_CATS_MANAGEMENT' => $FAQ_LANG['cats_management'],
	'L_CONFIG_MANAGEMENT' => $FAQ_LANG['faq_configuration'],
	'L_ADD_CAT' => $FAQ_LANG['add_cat'],
	'L_FAQ_NAME' => $FAQ_LANG['faq_name'],
	'L_FAQ_NAME_EXPLAIN' => $FAQ_LANG['faq_name_explain'],
	'L_NBR_COLS' => $FAQ_LANG['nbr_cols'],
	'L_NBR_COLS_EXPLAIN' => $FAQ_LANG['nbr_cols_explain'],
	'L_DISPLAY_MODE' => $FAQ_LANG['display_mode'],
	'L_DISPLAY_MODE_EXPLAIN' => $FAQ_LANG['display_mode_admin_explain'],
	'L_BLOCKS' => $FAQ_LANG['display_block'],
	'L_INLINE' => $FAQ_LANG['display_inline'],
	'L_AUTH' => $FAQ_LANG['general_auth'],
	'L_AUTH_EXPLAIN' => $FAQ_LANG['general_auth_explain'],
	'L_AUTH_READ' => $FAQ_LANG['read_auth'],
	'L_AUTH_WRITE' => $FAQ_LANG['write_auth'],
	'L_SUBMIT' => $LANG['submit'],
	'AUTH_READ' => $Group->Generate_select_groups(1, $FAQ_CONFIG['global_auth'], AUTH_READ),
	'AUTH_WRITE' => $Group->Generate_select_groups(2, $FAQ_CONFIG['global_auth'], AUTH_WRITE),
	'FAQ_NAME' => $FAQ_CONFIG['faq_name'],
	'NUM_COLS' => $FAQ_CONFIG['num_cols'],
	'SELECTED_BLOCK' => $FAQ_CONFIG['display_block'] ? ' selected="selected"' : '',
	'SELECTED_INLINE' => !$FAQ_CONFIG['display_block'] ? ' selected="selected"' : ''
	));



$Template->Pparse('admin_faq');

include_once('../includes/admin_footer.php');

?>