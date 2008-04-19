<?php
/*##################################################
 *                               admin_com_config.php
 *                            -------------------
 *   begin                : March 13, 2007
 *   copyright          : (C) 2007 Viarre Régis
 *   email                : crowkait@phpboost.com
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

require_once('../includes/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../includes/admin_header.php');

if( !empty($_POST['valid'])  )
{
	$config_com = array();
	$config_com['com_auth'] = isset($_POST['com_auth']) ? numeric($_POST['com_auth']) : -1;
	$config_com['com_max'] = !empty($_POST['com_max']) ? numeric($_POST['com_max']) : 10;
	$config_com['forbidden_tags'] = isset($_POST['forbidden_tags']) ? serialize($_POST['forbidden_tags']) : serialize(array());
	$config_com['max_link'] = isset($_POST['max_link']) ? numeric($_POST['max_link']) : -1;
	
	$Sql->Query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($config_com)) . "' WHERE name = 'com'", __LINE__, __FILE__);
	
	###### Régénération du cache des news #######
	$Cache->Generate_file('com');
		
	$CONFIG['com_popup'] = isset($_POST['com_popup']) ? numeric($_POST['com_popup']) : 0;
	$Sql->Query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($CONFIG)) . "' WHERE name = 'config'", __LINE__, __FILE__);
	
	###### Régénération du cache dela configuration #######
	$Cache->Generate_file('config');
	
	redirect(HOST . SCRIPT);	
}
//Sinon on rempli le formulaire
else	
{		
	$Template->Set_filenames(array(
		'admin_com_config'=> 'admin/admin_com_config.tpl'
	));
	
	$Cache->Load_file('com');
	
	$CONFIG['com_popup'] = isset($CONFIG['com_popup']) ? $CONFIG['com_popup'] : 0; //Affichage des commentaires
	
	//Rang d'autorisation.
	$CONFIG_COM['com_auth'] = isset($CONFIG_COM['com_auth']) ? $CONFIG_COM['com_auth'] : '-1';	
	$array_ranks = array(-1 => $LANG['guest'], 0 => $LANG['member'], 1 => $LANG['modo'], 2 => $LANG['admin']);
	$options = '';
	for($i = -1 ; $i <= 2 ; $i++)
	{
		$selected = ($CONFIG_COM['com_auth'] == $i) ? 'selected="selected"' : '' ;
		$options .= '<option value="' . $i . '" ' . $selected . '>' . $array_ranks[$i] . '</option>';
	}
	
	//Balises interdites => valeur 1.
	$array_unauth_tags = array('b' => 0, 'i' => 0, 'u' => 0, 's' => 0,	'title' => 0, 'stitle' => 0, 'style' => 0, 'url' => 0, 
	'img' => 0, 'quote' => 0, 'hide' => 0, 'list' => 0, 'color' => 0, 'bgcolor' => 0, 'font' => 0, 'size' => 0, 'align' => 0, 'float' => 0, 'sup' => 0, 
	'sub' => 0, 'indent' => 0, 'pre' => 0, 'table' => 0, 'swf' => 0, 'movie' => 0, 'sound' => 0, 'code' => 0, 'math' => 0, 'anchor' => 0, 'acronym' => 0);
	$j = 0;
	$forbidden_tags = '';
	foreach($array_unauth_tags as $name => $is_selected)
	{
		if( isset($CONFIG_COM['forbidden_tags']) )
		{	
			if( in_array($name, $CONFIG_COM['forbidden_tags']) )
				$selected = 'selected="selected"';
		}
		else
			$selected = ($is_selected) ? 'selected="selected"' : '';
			
		$forbidden_tags .= '<option id="tag' . $j . '" value="' . $name . '" ' . $selected . '>[' . $name . ']</option>';
		$j++;
	}	
	
	$Template->Assign_vars(array(
		'NBR_TAGS' => $j,
		'OPTIONS_RANK' =>  $options,
		'COM_MAX' => !empty($CONFIG_COM['com_max']) ? $CONFIG_COM['com_max'] : '10',
		'MAX_LINK' => isset($CONFIG_COM['max_link']) ? $CONFIG_COM['max_link'] : '-1',
		'COM_ENABLED' => ($CONFIG['com_popup'] == 0) ? 'checked="checked"' : '',
		'COM_DISABLED' => ($CONFIG['com_popup'] == 1) ? 'checked="checked"' : '',
		'FORBIDDEN_TAGS' => $forbidden_tags,
		'L_REQUIRE' => $LANG['require'],	
		'L_COM' => $LANG['com'],
		'L_COM_MANAGEMENT' => $LANG['com_management'],
		'L_COM_CONFIG' => $LANG['com_config'],
		'L_COM_MAX' => $LANG['com_max'],	
		'L_CURRENT_PAGE' => $LANG['current_page'],
		'L_NEW_PAGE' => $LANG['new_page'],
		'L_RANK' => $LANG['rank_com_post'],
		'L_VIEW_COM' => $LANG['view_com'],	
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset'],
		'L_FORBIDDEN_TAGS' => $LANG['forbidden_tags'],
		'L_EXPLAIN_SELECT_MULTIPLE' => $LANG['explain_select_multiple'],
		'L_SELECT_ALL' => $LANG['select_all'],
		'L_SELECT_NONE' => $LANG['select_none'],
		'L_MAX_LINK' => $LANG['max_link'],
		'L_MAX_LINK_EXPLAIN' => $LANG['max_link_explain']
	));
	
	$Template->Pparse('admin_com_config'); // traitement du modele	
}

require_once('../includes/admin_footer.php');

?>