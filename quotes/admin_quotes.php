<?php
/*##################################################
 *                               admin_quotes.php
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

require_once('../admin/admin_begin.php');
load_module_lang('quotes'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

if (!empty($_POST['valid']) )
{
	$config_quotes = array();
	$config_quotes['quotes_auth'] = retrieve(POST, 'quotes_auth', -1);
	$config_quotes['quotes_forbidden_tags'] = isset($_POST['quotes_forbidden_tags']) ? serialize($_POST['quotes_forbidden_tags']) : serialize(array());
	$config_quotes['quotes_max_link'] = retrieve(POST, 'quotes_max_link', -1);
		
	$Sql->query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($config_quotes)) . "' WHERE name = 'quotes'", __LINE__, __FILE__);
	
	###### Régénération du cache des news #######
	$Cache->Generate_module_file('quotes');
	
	redirect(HOST . SCRIPT);	
}
//Sinon on rempli le formulaire
else	
{		
	$Template->set_filenames(array(
		'admin_quotes_config'=> 'quotes/admin_quotes_config.tpl'
	));
	
	$Cache->load('quotes');

	$Template->assign_vars(array(
		'L_REQUIRE' => $LANG['require'],	
		'L_QUOTES' => $LANG['title_quotes'],
		'L_QUOTES_CONFIG' => $LANG['quotes_config'],
		'L_RANK' => $LANG['rank_post'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset'],
	));
		
	$config_quotes['quotes_auth'] = isset($CONFIG_QUOTES['quotes_auth']) ? $CONFIG_QUOTES['quotes_auth'] : '-1';	
	//Rang d'autorisation.
	for ($i = -1; $i <= 2; $i++)
	{
		switch ($i) 
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

		$selected = ($config_quotes['quotes_auth'] == $i) ? 'selected="selected"' : '' ;
		$Template->assign_block_vars('select_auth', array(
			'RANK' => '<option value="' . $i . '" ' . $selected . '>' . $rank . '</option>'
		));
	}
	
	$Template->pparse('admin_quotes_config'); // traitement du modele	
}

require_once('../admin/admin_footer.php');

?>