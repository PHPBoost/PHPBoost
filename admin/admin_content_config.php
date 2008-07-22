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

if( !empty($_POST['submit'])  )
{
	$editor = retrieve(POST, 'language', 'bbcode');
	$CONFIG['editor'] = $editor == 'tinymce' ? 'tinymce' : 'bbcode';
	$CONFIG['html_auth'] = $Group->Return_array_auth(1);
	$CONFIG['forbidden_tags'] = isset($_POST['forbidden_tags']) ? $_POST['forbidden_tags'] : array();
	
	$Sql->Query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($CONFIG)) . "' WHERE name = 'config'", __LINE__, __FILE__);
	$Cache->Generate_file('config');
		
	redirect(HOST . SCRIPT);	
}
//Sinon on rempli le formulaire
else	
{		
	$template = new Template('admin/admin_content_config.tpl');
	
	//Balises interdites => valeur 1.
	$array_unauth_tags = array('b', 'i', 'u', 's',	'title', 'stitle', 'style', 'url', 
	'img', 'quote', 'hide', 'list', 'color', 'bgcolor', 'font', 'size', 'align', 'float', 'sup', 
	'sub', 'indent', 'pre', 'table', 'swf', 'movie', 'sound', 'code', 'math', 'anchor', 'acronym');
	
	$forbidden_tags = '';
	$j = 0;
	
	foreach($array_unauth_tags as $name)
	{	
		$template->assign_block_vars('tag', array(
			'IDENTIFIER' => $j++,
			'TAG_NAME' => '[' . $name . ']',
			'C_ENABLED' => in_array($name, $CONFIG['forbidden_tags'])
		));
	}
	
	$template->assign_vars(array(
		'SELECT_AUTH_USE_HTML' => $Group->Generate_select_auth(1, $CONFIG['html_auth']),
		'NBR_TAGS' => $j,
		'FORBIDDEN_TAGS' => $forbidden_tags,
		'L_CONTENT_CONFIG' => $LANG['content_config_extend'],
		'L_DEFAULT_LANGUAGE' => $LANG['default_formatting_language'],
		'L_LANGUAGE_CONFIG' => $LANG['content_language_config'],
		'L_HTML_LANGUAGE' => $LANG['content_html_language'],
		'L_AUTH_USE_HTML' => $LANG['content_auth_use_html'],
		'L_FORBIDDEN_TAGS' => $LANG['forbidden_tags'],
		'L_EXPLAIN_SELECT_MULTIPLE' => $LANG['explain_select_multiple'],
		'L_SELECT_ALL' => $LANG['select_all'],
		'L_SELECT_NONE' => $LANG['select_none'],
		'L_SUBMIT' => $LANG['submit'],
		'L_RESET' => $LANG['reset']
	));
	
	$template->parse(); // traitement du modele	
}

require_once('../admin/admin_footer.php');

?>