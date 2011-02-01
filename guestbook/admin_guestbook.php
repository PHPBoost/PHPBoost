<?php
/*##################################################
 *                               admin_guestbook.php
 *                            -------------------
 *   begin                : March 12, 2007
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
load_module_lang('guestbook'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');
require_once('guestbook_constants.php');
if (!empty($_POST['valid']) )
{
	$guestbook_config = GuestbookConfig::load();
	
	$guestbook_config->set_authorizations(Authorizations::build_auth_array_from_form(GuestbookConfig::AUTH_READ, GuestbookConfig::AUTH_WRITE, GuestbookConfig::AUTH_MODO));
	if (isset($_POST['guestbook_forbidden_tags'])) {
		$guestbook_config->set_forbidden_tags($_POST['guestbook_forbidden_tags']);
	}
	$guestbook_config->set_maximum_links_message(retrieve(POST, 'guestbook_max_link', -1));
	$guestbook_config->set_captcha_difficulty(retrieve(POST, 'guestbook_difficulty_verifcode', 2));
	$guestbook_config->set_display_captcha(retrieve(POST, 'guestbook_verifcode', 1));

	GuestbookConfig::save();
	
	AppContext::get_response()->redirect(HOST . REWRITED_SCRIPT);
}
//Sinon on rempli le formulaire
else	
{		
	$Template = new FileTemplate('guestbook/admin_guestbook_config.tpl');
	
	$guestbook_config = GuestbookConfig::load();

	//Balises interdites
	$i = 0;
	$tags = '';
	foreach (AppContext::get_content_formatting_service()->get_available_tags() as $name => $value)
	{
		$selected = in_array($name, $guestbook_config->get_forbidden_tags()) ? 'selected="selected"' : '';
		$tags .= '<option id="tag' . $i++ . '" value="' . $name . '" ' . $selected . '>' . $value . '</option>';
	}
	
	$authorizations = $guestbook_config->get_authorizations();
	
	$Template->put_all(array(
		'TAGS' => $tags,
		'NBR_TAGS' => $i,
		'MAX_LINK' => $guestbook_config->get_maximum_links_message(),
		'GUESTBOOK_VERIFCODE_ENABLED' => ($guestbook_config->get_display_captcha() == '1') ? 'checked="checked"' : '',
		'GUESTBOOK_VERIFCODE_DISABLED' => ($guestbook_config->get_display_captcha() == '0') ? 'checked="checked"' : '',
		'AUTH_READ' => Authorizations::generate_select(GuestbookConfig::AUTH_READ, $authorizations),
		'AUTH_WRITE' => Authorizations::generate_select(GuestbookConfig::AUTH_WRITE, $authorizations),
		'AUTH_MODO' => Authorizations::generate_select(GuestbookConfig::AUTH_MODO, $authorizations),
		'L_AUTH_WRITE' => $LANG['rank_post'],
		'L_AUTH_READ' => $LANG['rank_read'],
		'L_AUTH_MODO' => $LANG['rank_modo'],
		'L_REQUIRE' => $LANG['require'],	
		'L_GUESTBOOK' => $LANG['title_guestbook'],
		'L_GUESTBOOK_CONFIG' => $LANG['guestbook_config'],
		'L_GUESTBOOK_VERIFCODE' => $LANG['verif_code'],
		'L_GUESTBOOK_VERIFCODE_EXPLAIN' => $LANG['verif_code_explain'],
		'L_CAPTCHA_DIFFICULTY' => $LANG['captcha_difficulty'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset'],
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_FORBIDDEN_TAGS' => $LANG['forbidden_tags'],
		'L_EXPLAIN_SELECT_MULTIPLE' => $LANG['explain_select_multiple'],
		'L_SELECT_ALL' => $LANG['select_all'],
		'L_SELECT_NONE' => $LANG['select_none'],
		'L_MAX_LINK' => $LANG['max_link'],
		'L_MAX_LINK_EXPLAIN' => $LANG['max_link_explain']
	));
	
	for ($i = 0; $i < 5; $i++)
	{
		$Template->assign_block_vars('difficulty', array(
			'VALUE' => $i,
			'SELECTED' => ($guestbook_config->get_captcha_difficulty() == $i) ? 'selected="selected"' : ''
		));
	}

	$Template->display(); // traitement du modele	
}

require_once('../admin/admin_footer.php');

?>