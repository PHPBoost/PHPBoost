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
load_module_lang('shoutbox'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

require_once('shoutbox_constants.php');

if (!empty($_POST['valid']) )
{
	$config_shoutbox = ShoutboxConfig::load();
	
	$config_shoutbox->set_max_messages(retrieve(POST, 'shoutbox_max_msg', 10));
	$config_shoutbox->set_authorization(Authorizations::build_auth_array_from_form(AUTH_SHOUTBOX_READ, AUTH_SHOUTBOX_WRITE));
	$config_shoutbox->set_forbidden_tags($_POST['shoutbox_forbidden_tags']);
	$config_shoutbox->set_max_links(retrieve(POST, 'shoutbox_max_link', -1));
	$config_shoutbox->set_refresh_delay(NumberHelper::numeric(retrieve(POST, 'shoutbox_refresh_delay', 0)* 60000, 'float'));

	ShoutboxConfig::save();
	
	AppContext::get_response()->redirect(HOST . SCRIPT);	
}
//Sinon on rempli le formulaire
else	
{		
	$Template->set_filenames(array(
		'admin_shoutbox_config'=> 'shoutbox/admin_shoutbox_config.tpl'
	));
	
	$config_shoutbox = ShoutboxConfig::load();

	//Balises interdites => valeur 1.
	$array_tags = array('b' => 0, 'i' => 0, 'u' => 0, 's' => 0,	'title' => 1, 'stitle' => 1, 'style' => 1, 'url' => 0, 
	'img' => 1, 'quote' => 1, 'hide' => 1, 'list' => 1, 'color' => 0, 'bgcolor' => 0, 'font' => 0, 'size' => 0, 'align' => 1, 'float' => 1, 'sup' => 0, 
	'sub' => 0, 'indent' => 1, 'pre' => 0, 'table' => 1, 'swf' => 1, 'movie' => 1, 'sound' => 1, 'code' => 1, 'math' => 1, 'anchor' => 0, 'acronym' => 0);
	
	$Template->assign_vars(array(
		'NBR_TAGS' => count($array_tags),
		'SHOUTBOX_MAX_MSG' =>  $config_shoutbox->get_max_messages(),
		'AUTH_READ' => Authorizations::generate_select(AUTH_SHOUTBOX_READ, $config_shoutbox->get_authorization()),
		'AUTH_WRITE' => Authorizations::generate_select(AUTH_SHOUTBOX_WRITE, $config_shoutbox->get_authorization()),
		'MAX_LINK' => $config_shoutbox->get_max_links(),
		'SHOUTBOX_REFRESH_DELAY' => $config_shoutbox->get_refresh_delay()/60000,
		'L_REQUIRE' => $LANG['require'],	
		'L_SHOUTBOX' => $LANG['title_shoutbox'],
		'L_SHOUTBOX_CONFIG' => $LANG['shoutbox_config'],
		'L_SHOUTBOX_MAX_MSG' => $LANG['shoutbox_max_msg'],
		'L_SHOUTBOX_MAX_MSG_EXPLAIN' => $LANG['shoutbox_max_msg_explain'],
		'L_AUTH_WRITE' => $LANG['auth_write'],
		'L_AUTH_READ' => $LANG['auth_read'],
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
	foreach (AppContext::get_content_formatting_service()->get_available_tags() as $name => $value)
	{
		$selected = '';
		if (in_array($name, $config_shoutbox->get_forbidden_tags()))
			$selected = 'selected="selected"';
		
		$Template->assign_block_vars('forbidden_tags', array(
			'TAGS' => '<option id="tag' . $i++ . '" value="' . $name . '" ' . $selected . '>' . $value . '</option>'
		));
	}
	
	$Template->pparse('admin_shoutbox_config'); // traitement du modele	
}

require_once('../admin/admin_footer.php');

?>