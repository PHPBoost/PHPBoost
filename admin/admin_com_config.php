<?php
/*##################################################
 *                               admin_com_config.php
 *                            -------------------
 *   begin                : March 13, 2007
 *   copyright            : (C) 2007 Viarre Régis
 *   email                : crowkait@phpboost.com
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
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

if (!empty($_POST['valid']) )
{
	$comments_config = CommentsConfig::load();
	$server_configuration = new ServerConfiguration();
	
	$comments_config->set_auth_post_comments(Authorizations::build_auth_array_from_form(Comments::POST_COMMENT_AUTH));
	$comments_config->set_display_comments_in_popup(retrieve(POST, 'com_popup', false));
	$comments_config->set_display_captcha($server_configuration->has_gd_libray() ? $_POST['verif_code'] : false);
	$comments_config->set_captcha_difficulty(retrieve(POST, 'verif_code_difficulty', 2));
	$comments_config->set_number_comments_per_page(retrieve(POST, 'com_max', 10));
	if (!empty($_POST['forbidden_tags'])) {
	   $comments_config->set_forbidden_tags($_POST['forbidden_tags']);
	}
	$comments_config->set_max_links_comment(retrieve(POST, 'max_link', -1));
	CommentsConfig::save();
	
	AppContext::get_response()->redirect(HOST . SCRIPT);	
}
//Sinon on rempli le formulaire
else	
{		
	$Template->set_filenames(array(
		'admin_com_config'=> 'admin/admin_com_config.tpl'
	));
	
	$comments_config = CommentsConfig::load();

	for ($i = 0; $i < 5; $i++)
	{
		$Template->assign_block_vars('difficulty', array(
			'VALUE' => $i,
			'SELECTED' => ($comments_config->get_captcha_difficulty() == $i) ? 'selected="selected"' : ''
		));
	}
	
	$j = 0;
	foreach (AppContext::get_content_formatting_service()->get_available_tags() as $identifier => $name)
	{	
		$Template->assign_block_vars('tag', array(
			'IDENTIFIER' => $j++,
			'CODE' => $identifier,
			'TAG_NAME' => $name,
			'C_ENABLED' => in_array($identifier, $comments_config->get_forbidden_tags())
		));
	}

	$Template->put_all(array(
		'NBR_TAGS' => $j,
		'AUTH_POST_COMMENTS' => Authorizations::generate_select(Comments::POST_COMMENT_AUTH, $comments_config->get_auth_post_comments()),
		'COM_MAX' => $comments_config->get_number_comments_per_page(),
		'MAX_LINK' => $comments_config->get_max_links_comment(),
		'COM_ENABLED' => !$comments_config->get_display_comments_in_popup() ? 'checked="checked"' : '',
		'COM_DISABLED' => $comments_config->get_display_comments_in_popup() ? 'checked="checked"' : '',
		'GD_DISABLED' => (!@extension_loaded('gd')) ? 'disabled="disabled"' : '',
		'VERIF_CODE_ENABLED' => $comments_config->get_display_captcha() ? 'checked="checked"' : '',
		'VERIF_CODE_DISABLED' => !$comments_config->get_display_captcha() ? 'checked="checked"' : '',
		'L_REQUIRE' => $LANG['require'],	
		'L_COM' => $LANG['com'],
		'L_COM_MANAGEMENT' => $LANG['com_management'],
		'L_COM_CONFIG' => $LANG['com_config'],
		'L_COM_MAX' => $LANG['com_max'],	
		'L_CURRENT_PAGE' => $LANG['current_page'],
		'L_NEW_PAGE' => $LANG['new_page'],
		'L_AUTH_POST_COMMENTS' => $LANG['rank_com_post'],
		'L_VIEW_COM' => $LANG['view_com'],
		'L_VERIF_CODE' => $LANG['verif_code'],
		'L_VERIF_CODE_EXPLAIN' => $LANG['verif_code_explain'],
		'L_CAPTCHA_DIFFICULTY' => $LANG['captcha_difficulty'],	
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset'],
		'L_FORBIDDEN_TAGS' => $LANG['forbidden_tags'],
		'L_EXPLAIN_SELECT_MULTIPLE' => $LANG['explain_select_multiple'],
		'L_SELECT_ALL' => $LANG['select_all'],
		'L_SELECT_NONE' => $LANG['select_none'],
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_MAX_LINK' => $LANG['max_link'],
		'L_MAX_LINK_EXPLAIN' => $LANG['max_link_explain']
	));
	
	$Template->pparse('admin_com_config'); // traitement du modele	
}

require_once('../admin/admin_footer.php');

?>