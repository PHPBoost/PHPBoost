<?php
/*##################################################
 *                               admin_guestbook.php
 *                            -------------------
 *   begin                : March 12, 2007
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

include_once('../includes/admin_begin.php');
include_once('../guestbook/lang/' . $CONFIG['lang'] . '/guestbook_' . $CONFIG['lang'] . '.php'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
include_once('../includes/admin_header.php');

if( !empty($_POST['valid'])  )
{
	$config_guestbook = array();
	$config_guestbook['guestbook_auth'] = isset($_POST['guestbook_auth']) ? numeric($_POST['guestbook_auth']) : -1;
	$config_guestbook['guestbook_forbidden_tags'] = isset($_POST['guestbook_forbidden_tags']) ? serialize($_POST['guestbook_forbidden_tags']) : serialize(array());
	$config_guestbook['guestbook_max_link'] = isset($_POST['guestbook_max_link']) ? numeric($_POST['guestbook_max_link']) : -1;
		
	$sql->query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($config_guestbook)) . "' WHERE name = 'guestbook'", __LINE__, __FILE__);
	
	###### Régénération du cache des news #######
	$cache->generate_module_file('guestbook');
	
	header('location:' . HOST . SCRIPT);	
	exit;
}
//Sinon on rempli le formulaire
else	
{		
	$template->set_filenames(array(
		'admin_guestbook_config' => '../templates/' . $CONFIG['theme'] . '/guestbook/admin_guestbook_config.tpl'
	));
	
	$cache->load_file('guestbook');
	
	//Balises interdites => valeur 1.
	$array_tags = array('b' => 0, 'i' => 0, 'u' => 0, 's' => 0,	'title' => 0, 'stitle' => 0, 'style' => 0, 'url' => 0, 
	'img' => 0, 'quote' => 0, 'hide' => 0, 'list' => 0, 'color' => 0, 'size' => 0, 'align' => 0, 'float' => 0, 'sup' => 0, 
	'sub' => 0, 'indent' => 0, 'table' => 0, 'swf' => 1, 'movie' => 1, 'sound' => 1, 'code' => 0, 'math' => 0, 'anchor' => 0);
	
	$template->assign_vars(array(
		'NBR_TAGS' => count($array_tags),
		'MAX_LINK' => isset($CONFIG_GUESTBOOK['guestbook_max_link']) ? $CONFIG_GUESTBOOK['guestbook_max_link'] : '-1',
		'L_REQUIRE' => $LANG['require'],	
		'L_GUESTBOOK' => $LANG['title_guestbook'],
		'L_GUESTBOOK_CONFIG' => $LANG['guestbook_config'],
		'L_RANK' => $LANG['rank_post'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset'],
		'L_FORBIDDEN_TAGS' => $LANG['forbidden_tags'],
		'L_EXPLAIN_SELECT_MULTIPLE' => $LANG['explain_select_multiple'],
		'L_SELECT_ALL' => $LANG['select_all'],
		'L_SELECT_NONE' => $LANG['select_none'],
		'L_MAX_LINK' => $LANG['max_link'],
		'L_MAX_LINK_EXPLAIN' => $LANG['max_link_explain']
	));
		
	$CONFIG_GUESTBOOK['guestbook_auth'] = isset($CONFIG_GUESTBOOK['guestbook_auth']) ? $CONFIG_GUESTBOOK['guestbook_auth'] : '-1';	
	//Rang d'autorisation.
	for($i = -1; $i <= 2; $i++)
	{
		switch($i) 
		{	
			case -1:
				$rank = $LANG['guest'];
			break;				
			case 0:
				$rank = $LANG['member'];
			break;				
			case 1: 
				$rank = $LANG['modo'];
			break;		
			case 2:
				$rank = $LANG['admin'];
			break;					
			default: -1;
		} 

		$selected = ($CONFIG_GUESTBOOK['guestbook_auth'] == $i) ? 'selected="selected"' : '' ;
		$template->assign_block_vars('select_auth', array(
			'RANK' => '<option value="' . $i . '" ' . $selected . '>' . $rank . '</option>'
		));
	}
	
	//Balises interdites
	$i = 0;
	foreach($array_tags as $name => $is_selected)
	{
		if( isset($CONFIG_GUESTBOOK['guestbook_forbidden_tags']) )
		{	
			if( in_array($name, $CONFIG_GUESTBOOK['guestbook_forbidden_tags']) )
				$selected = 'selected="selected"';
		}
		else
			$selected = ($is_selected) ? 'selected="selected"' : '';		
		
		$template->assign_block_vars('forbidden_tags', array(
			'TAGS' => '<option id="tag' . $i . '" value="' . $name . '" ' . $selected . '>[' . $name . ']</option>'
		));
		$i++;
	}
	
	$template->pparse('admin_guestbook_config'); // traitement du modele	
}

include_once('../includes/admin_footer.php');

?>