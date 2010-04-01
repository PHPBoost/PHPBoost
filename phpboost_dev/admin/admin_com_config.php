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
	$config_com = array();
	$config_com['com_auth'] = retrieve(POST, 'com_auth', -1);
	$config_com['com_max'] = retrieve(POST, 'com_max', 10);
	$config_com['com_verif_code'] = (isset($_POST['verif_code']) && @extension_loaded('gd')) ? NumberHelper::numeric($_POST['verif_code']) : 0; //désactivé par defaut. 
	$config_com['com_verif_code_difficulty'] = retrieve(POST, 'verif_code_difficulty', 2);
	$config_com['forbidden_tags'] = isset($_POST['forbidden_tags']) ? $_POST['forbidden_tags'] : array();
	$config_com['max_link'] = retrieve(POST, 'max_link', -1);
	
	$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($config_com)) . "' WHERE name = 'com'", __LINE__, __FILE__);
	
	###### Régénération du cache des news #######
	$Cache->Generate_file('com');
		
	$CONFIG['com_popup'] = retrieve(POST, 'com_popup', 0);
	$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($CONFIG)) . "' WHERE name = 'config'", __LINE__, __FILE__);
	
	###### Régénération du cache dela configuration #######
	$Cache->Generate_file('config');
	
	AppContext::get_response()->redirect(HOST . SCRIPT);	
}
//Sinon on rempli le formulaire
else	
{		
	$Template->set_filenames(array(
		'admin_com_config'=> 'admin/admin_com_config.tpl'
	));
	
	$Cache->load('com');
	
	$CONFIG['com_popup'] = isset($CONFIG['com_popup']) ? $CONFIG['com_popup'] : 0; //Affichage des commentaires
	
	//Rang d'autorisation.
	$CONFIG_COM['com_auth'] = isset($CONFIG_COM['com_auth']) ? $CONFIG_COM['com_auth'] : '-1';	
	$array_ranks = array(-1 => $LANG['guest'], 0 => $LANG['member'], 1 => $LANG['modo'], 2 => $LANG['admin']);
	$options = '';
	for ($i = -1 ; $i <= 2 ; $i++)
	{
		$selected = ($CONFIG_COM['com_auth'] == $i) ? 'selected="selected"' : '' ;
		$options .= '<option value="' . $i . '" ' . $selected . '>' . $array_ranks[$i] . '</option>';
	}
	
	for ($i = 0; $i < 5; $i++)
	{
		$Template->assign_block_vars('difficulty', array(
			'VALUE' => $i,
			'SELECTED' => ($CONFIG_COM['com_verif_code_difficulty'] == $i) ? 'selected="selected"' : ''
		));
	}
	
	$j = 0;
	foreach (FormattingTagsList::get_available_tags() as $identifier => $name)
	{	
		$Template->assign_block_vars('tag', array(
			'IDENTIFIER' => $j++,
			'CODE' => $identifier,
			'TAG_NAME' => $name,
			'C_ENABLED' => in_array($identifier, $CONFIG_COM['forbidden_tags'])
		));
	}
	
	$Template->assign_vars(array(
		'NBR_TAGS' => $j,
		'OPTIONS_RANK' =>  $options,
		'COM_MAX' => !empty($CONFIG_COM['com_max']) ? $CONFIG_COM['com_max'] : '10',
		'MAX_LINK' => isset($CONFIG_COM['max_link']) ? $CONFIG_COM['max_link'] : '-1',
		'COM_ENABLED' => ($CONFIG['com_popup'] == 0) ? 'checked="checked"' : '',
		'COM_DISABLED' => ($CONFIG['com_popup'] == 1) ? 'checked="checked"' : '',
		'GD_DISABLED' => (!@extension_loaded('gd')) ? 'disabled="disabled"' : '',
		'VERIF_CODE_ENABLED' => ($CONFIG_COM['com_verif_code'] == 1 && @extension_loaded('gd')) ? 'checked="checked"' : '',
		'VERIF_CODE_DISABLED' => ($CONFIG_COM['com_verif_code'] == 0) ? 'checked="checked"' : '',
		'L_REQUIRE' => $LANG['require'],	
		'L_COM' => $LANG['com'],
		'L_COM_MANAGEMENT' => $LANG['com_management'],
		'L_COM_CONFIG' => $LANG['com_config'],
		'L_COM_MAX' => $LANG['com_max'],	
		'L_CURRENT_PAGE' => $LANG['current_page'],
		'L_NEW_PAGE' => $LANG['new_page'],
		'L_RANK' => $LANG['rank_com_post'],
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