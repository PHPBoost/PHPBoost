<?php
/*##################################################
 *                             admin_shoutbox.php
 *                            -------------------
 *   begin                : March 12, 2007
 *   copyright            : (C) 2007 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *  
 *
###################################################
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
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
load_module_lang('shoutbox'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

if (!empty($_POST['valid']) )
{
	$config_shoutbox = array();
	$config_shoutbox['shoutbox_max_msg'] = retrieve(POST, 'shoutbox_max_msg', 10);
	$config_shoutbox['shoutbox_auth'] = retrieve(POST, 'shoutbox_auth', -1);
	$config_shoutbox['shoutbox_forbidden_tags'] = isset($_POST['shoutbox_forbidden_tags']) ? serialize($_POST['shoutbox_forbidden_tags']) : serialize(array());
	$config_shoutbox['shoutbox_max_link'] = retrieve(POST, 'shoutbox_max_link', -1);
	$config_shoutbox['shoutbox_refresh_delay'] = numeric(retrieve(POST, 'shoutbox_refresh_delay', 0)* 60000, 'float');
	
	$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($config_shoutbox)) . "' WHERE name = 'shoutbox'", __LINE__, __FILE__);
	
	###### Régénération du cache des news #######
	$Cache->Generate_module_file('shoutbox');
	
	redirect(HOST . SCRIPT);	
}
//Sinon on rempli le formulaire
else	
{		
	$Template->set_filenames(array(
		'admin_shoutbox_config'=> 'shoutbox/admin_shoutbox_config.tpl'
	));
	
	$Cache->load('shoutbox');
	
	//Balises interdites => valeur 1.
	$array_tags = array('b' => 0, 'i' => 0, 'u' => 0, 's' => 0,	'title' => 1, 'stitle' => 1, 'style' => 1, 'url' => 0, 
	'img' => 1, 'quote' => 1, 'hide' => 1, 'list' => 1, 'color' => 0, 'bgcolor' => 0, 'font' => 0, 'size' => 0, 'align' => 1, 'float' => 1, 'sup' => 0, 
	'sub' => 0, 'indent' => 1, 'pre' => 0, 'table' => 1, 'swf' => 1, 'movie' => 1, 'sound' => 1, 'code' => 1, 'math' => 1, 'anchor' => 0, 'acronym' => 0);
	
	//Rang d'autorisation.
	$CONFIG_SHOUTBOX['shoutbox_auth'] = isset($CONFIG_SHOUTBOX['shoutbox_auth']) ? $CONFIG_SHOUTBOX['shoutbox_auth'] : '-1';	
	$array_auth_ranks = array(-1 => $LANG['guest'], 0 => $LANG['member'], 1 => $LANG['modo'], 2 => $LANG['admin']);
	$ranks = '';
	foreach ($array_auth_ranks as $rank => $name)
	{
		$selected = ($CONFIG_SHOUTBOX['shoutbox_auth'] == $rank) ? ' selected="selected"' : '' ;
		$ranks .= '<option value="' . $rank . '"' . $selected . '>' . $name . '</option>';
	}
	
	$Template->assign_vars(array(
		'NBR_TAGS' => count($array_tags),
		'SHOUTBOX_MAX_MSG' => !empty($CONFIG_SHOUTBOX['shoutbox_max_msg']) ? $CONFIG_SHOUTBOX['shoutbox_max_msg'] : '100',
		'SHOUTBOX_AUTH' => $ranks,
		'MAX_LINK' => isset($CONFIG_SHOUTBOX['shoutbox_max_link']) ? $CONFIG_SHOUTBOX['shoutbox_max_link'] : '-1',
		'SHOUTBOX_REFRESH_DELAY' => isset($CONFIG_SHOUTBOX['shoutbox_refresh_delay']) ? ($CONFIG_SHOUTBOX['shoutbox_refresh_delay']/60000) : 1,
		'L_REQUIRE' => $LANG['require'],	
		'L_SHOUTBOX' => $LANG['title_shoutbox'],
		'L_SHOUTBOX_CONFIG' => $LANG['shoutbox_config'],
		'L_SHOUTBOX_MAX_MSG' => $LANG['shoutbox_max_msg'],
		'L_SHOUTBOX_MAX_MSG_EXPLAIN' => $LANG['shoutbox_max_msg_explain'],
		'L_RANK' => $LANG['rank_post'],
		'L_SHOUTBOX_REFRESH_DELAY' => $LANG['shoutbox_refresh_delay'],
		'L_SHOUTBOX_REFRESH_DELAY_EXPLAIN' => $LANG['shoutbox_refresh_delay_explain'],
		'L_MINUTES' => $LANG['minutes'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset'],
		'L_FORBIDDEN_TAGS' => $LANG['forbidden_tags'],
		'L_EXPLAIN_SELECT_MULTIPLE' => $LANG['explain_select_multiple'],
		'L_SELECT_ALL' => $LANG['select_all'],
		'L_SELECT_NONE' => $LANG['select_none'],
		'L_MAX_LINK' => $LANG['max_link'],
		'L_MAX_LINK_EXPLAIN' => $LANG['max_link_explain']
	));
			
	//Forbidden tags
	$i = 0;
	foreach (ContentFormattingFactory::get_available_tags() as $name => $value)
	{
		$selected = '';
		if (in_array($name, $CONFIG_SHOUTBOX['shoutbox_forbidden_tags']))
			$selected = 'selected="selected"';
		
		$Template->assign_block_vars('forbidden_tags', array(
			'TAGS' => '<option id="tag' . $i++ . '" value="' . $name . '" ' . $selected . '>' . $value . '</option>'
		));
	}
	
	$Template->pparse('admin_shoutbox_config'); // traitement du modele	
}

require_once('../admin/admin_footer.php');

?>