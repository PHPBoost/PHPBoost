<?php
/*##################################################
 *                               admin_content_config.php
 *                            -------------------
 *   begin                : July 4 2008
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

require_once('../admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

if (!empty($_POST['submit']) )
{
	$editor = retrieve(POST, 'formatting_language', '');
	
	$CONFIG['anti_flood'] 	= retrieve(POST, 'anti_flood', 0, TINTEGER);
	$CONFIG['delay_flood'] 	= retrieve(POST, 'delay_flood', 0, TINTEGER);
	$CONFIG['pm_max'] 		= retrieve(POST, 'pm_max', 25, TINTEGER);
	$CONFIG['editor'] 		= $editor == 'tinymce' ? $editor : 'bbcode';
	$CONFIG['html_auth'] 	= Authorizations::build_auth_array_from_form(1);
	$CONFIG['forbidden_tags'] = isset($_POST['forbidden_tags']) ? $_POST['forbidden_tags'] : array();
	
	$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($CONFIG)) . "' WHERE name = 'config'", __LINE__, __FILE__);
	$Cache->Generate_file('config');
		
	redirect(HOST . SCRIPT);	
}
//Sinon on rempli le formulaire
else	
{		
	$template = new Template('admin/admin_content_config.tpl');
	
	$j = 0;
	
	foreach (ContentFormattingFactory::get_available_tags() as $code => $name)
	{	
		$template->assign_block_vars('tag', array(
			'IDENTIFIER' => $j++,
			'CODE' => $code,
			'TAG_NAME' => $name,
			'C_ENABLED' => in_array($code, $CONFIG['forbidden_tags'])
		));
	}
	
	$template->assign_vars(array(
		'BBCODE_SELECTED' => $CONFIG['editor'] == 'bbcode' ? 'selected="selected"' : '',
		'TINYMCE_SELECTED' => $CONFIG['editor'] == 'tinymce' ? 'selected="selected"' : '',
		'SELECT_AUTH_USE_HTML' => Authorizations::generate_select(1, $CONFIG['html_auth']),
		'NBR_TAGS' => $j,
		
		'PM_MAX' => isset($CONFIG['pm_max']) ? $CONFIG['pm_max'] : '50',
		'DELAY_FLOOD' => !empty($CONFIG['delay_flood']) ? $CONFIG['delay_flood'] : '7',
		'FLOOD_ENABLED' => ($CONFIG['anti_flood'] == 1) ? 'checked="checked"' : '',
		'FLOOD_DISABLED' => ($CONFIG['anti_flood'] == 0) ? 'checked="checked"' : '',

		'L_CONTENT_CONFIG' => $LANG['content_config_extend'],
		'L_DEFAULT_LANGUAGE' => $LANG['default_formatting_language'],
		'L_LANGUAGE_CONFIG' => $LANG['content_language_config'],
		'L_HTML_LANGUAGE' => $LANG['content_html_language'],
		'L_AUTH_USE_HTML' => $LANG['content_auth_use_html'],
		'L_FORBIDDEN_TAGS' => $LANG['forbidden_tags'],
		'L_EXPLAIN_SELECT_MULTIPLE' => $LANG['explain_select_multiple'],
		'L_SELECT_ALL' => $LANG['select_all'],
		'L_SELECT_NONE' => $LANG['select_none'],
		
		'L_POST_MANAGEMENT' => $LANG['post_management'],
		'L_PM_MAX' => $LANG['pm_max'],
		'L_SECONDS' => $LANG['unit_seconds'],
		'L_ANTI_FLOOD' => $LANG['anti_flood'],
		'L_INT_FLOOD' => $LANG['int_flood'],
		'L_PM_MAX_EXPLAIN' => $LANG['pm_max_explain'],
		'L_ANTI_FLOOD_EXPLAIN' => $LANG['anti_flood_explain'],
		'L_INT_FLOOD_EXPLAIN' => $LANG['int_flood_explain'],
		
		'L_SUBMIT' => $LANG['submit'],
		'L_RESET' => $LANG['reset']
	));
	
	$template->parse(); // traitement du modele	
}

require_once('../admin/admin_footer.php');

?>
