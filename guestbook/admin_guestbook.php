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
	$CONFIG_GUESTBOOK = array(
		'guestbook_auth' => Authorizations::build_auth_array_from_form(AUTH_GUESTBOOK_READ, AUTH_GUESTBOOK_WRITE, AUTH_GUESTBOOK_MODO),
		'guestbook_forbidden_tags' => isset($_POST['guestbook_forbidden_tags']) ? serialize($_POST['guestbook_forbidden_tags']) : serialize(array()),
		'guestbook_max_link' => retrieve(POST, 'guestbook_max_link', -1),
		'guestbook_verifcode' => retrieve(POST, 'guestbook_verifcode', 1),
		'guestbook_difficulty_verifcode' => retrieve(POST, 'guestbook_difficulty_verifcode', 2),
	);

	$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($CONFIG_GUESTBOOK)) . "' WHERE name = 'guestbook'", __LINE__, __FILE__);
	
	###### Régénération du cache des news #######
	$Cache->Generate_module_file('guestbook');
	
	AppContext::get_response()->redirect(HOST . SCRIPT);	
}
//Sinon on rempli le formulaire
else	
{		
	$Template = new FileTemplate('guestbook/admin_guestbook_config.tpl');
	
	$Cache->load('guestbook');
	
	//Balises interdites
	$i = 0;
	$tags = '';
	$CONFIG_GUESTBOOK['guestbook_forbidden_tags'] = isset($CONFIG_GUESTBOOK['guestbook_forbidden_tags']) ? $CONFIG_GUESTBOOK['guestbook_forbidden_tags'] : $array_tags;
	foreach (AppContext::get_content_formatting_service()->get_available_tags() as $name => $value)
	{
		$selected = '';
		if (in_array($name, $CONFIG_GUESTBOOK['guestbook_forbidden_tags']))
			$selected = 'selected="selected"';
		$tags .= '<option id="tag' . $i++ . '" value="' . $name . '" ' . $selected . '>' . $value . '</option>';
	}
	
	$CONFIG_GUESTBOOK['guestbook_verifcode'] = isset($CONFIG_GUESTBOOK['guestbook_verifcode']) ? $CONFIG_GUESTBOOK['guestbook_verifcode'] : 0;
	$CONFIG_GUESTBOOK['guestbook_difficulty_verifcode'] = isset($CONFIG_GUESTBOOK['guestbook_difficulty_verifcode']) ? $CONFIG_GUESTBOOK['guestbook_difficulty_verifcode'] : 2;
	
	$Template->put_all(array(
		'TAGS' => $tags,
		'NBR_TAGS' => $i,
		'MAX_LINK' => isset($CONFIG_GUESTBOOK['guestbook_max_link']) ? $CONFIG_GUESTBOOK['guestbook_max_link'] : '-1',
		'GUESTBOOK_VERIFCODE_ENABLED' => ($CONFIG_GUESTBOOK['guestbook_verifcode'] == '1') ? 'checked="checked"' : '',
		'GUESTBOOK_VERIFCODE_DISABLED' => ($CONFIG_GUESTBOOK['guestbook_verifcode'] == '0') ? 'checked="checked"' : '',
		'AUTH_READ' => Authorizations::generate_select(AUTH_GUESTBOOK_READ,$CONFIG_GUESTBOOK['guestbook_auth']),
		'AUTH_WRITE' => Authorizations::generate_select(AUTH_GUESTBOOK_WRITE,$CONFIG_GUESTBOOK['guestbook_auth']),
		'AUTH_MODO' => Authorizations::generate_select(AUTH_GUESTBOOK_MODO,$CONFIG_GUESTBOOK['guestbook_auth']),
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
			'SELECTED' => ($CONFIG_GUESTBOOK['guestbook_difficulty_verifcode'] == $i) ? 'selected="selected"' : ''
		));
	}

	$Template->display(); // traitement du modele	
}

require_once('../admin/admin_footer.php');

?>