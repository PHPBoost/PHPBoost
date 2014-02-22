<?php
/*##################################################
 *                              admin_bugtracker_authorizations.php
 *                            -------------------
 *   begin                : October 05, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

require_once('../admin/admin_begin.php');
load_module_lang('bugtracker'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$bugtracker_config = BugtrackerConfig::load();

if (!empty($_POST['valid']))
{
	$bugtracker_config->set_authorizations(Authorizations::build_auth_array_from_form(BugtrackerConfig::BUG_READ_AUTH_BIT, BugtrackerConfig::BUG_CREATE_AUTH_BIT, BugtrackerConfig::BUG_CREATE_ADVANCED_AUTH_BIT, BugtrackerConfig::BUG_MODERATE_AUTH_BIT));

	BugtrackerConfig::save();
	
	AppContext::get_response()->redirect(HOST . SCRIPT . '?error=success#message_helper');
}
else
{	
	$Template = new FileTemplate('bugtracker/admin_bugtracker_authorizations.tpl');
	
	$authorizations = $bugtracker_config->get_authorizations();
	
	$Template->put_all(array(
		'L_BUGS_MANAGEMENT'				=> $LANG['bugs.titles.admin.management'],
		'L_BUGS_CONFIG' 				=> $LANG['bugs.titles.admin.config'],
		'L_BUGS_AUTHORIZATIONS' 		=> $LANG['bugs.titles.admin.authorizations'],
		'L_AUTH'		 				=> $LANG['bugs.config.auth'],
		'L_READ_AUTH' 					=> $LANG['bugs.config.auth.read'],
		'L_CREATE_AUTH' 				=> $LANG['bugs.config.auth.create'],
		'L_CREATE_ADVANCED_AUTH'		=> $LANG['bugs.config.auth.create_advanced'],
		'L_CREATE_ADVANCED_AUTH_EXPLAIN'=> $LANG['bugs.config.auth.create_advanced_explain'],
		'L_MODERATE_AUTH'				=> $LANG['bugs.config.auth.moderate'],
		'L_UPDATE' 						=> $LANG['update'],
		'L_RESET' 						=> $LANG['reset'],
		'BUG_READ_AUTH'					=> Authorizations::generate_select(BugtrackerConfig::BUG_READ_AUTH_BIT, $authorizations),
		'BUG_CREATE_AUTH'				=> Authorizations::generate_select(BugtrackerConfig::BUG_CREATE_AUTH_BIT, $authorizations),
		'BUG_CREATE_ADVANCED_AUTH'		=> Authorizations::generate_select(BugtrackerConfig::BUG_CREATE_ADVANCED_AUTH_BIT, $authorizations),
		'BUG_MODERATE_AUTH'				=> Authorizations::generate_select(BugtrackerConfig::BUG_MODERATE_AUTH_BIT, $authorizations),
		'U_CONFIGURATION'				=> PATH_TO_ROOT . '/bugtracker/admin_bugtracker.php',
		'U_AUTHORIZATIONS'				=> PATH_TO_ROOT . '/bugtracker/admin_bugtracker_authorizations.php',
		'U_FORM'						=> PATH_TO_ROOT . '/bugtracker/admin_bugtracker_authorizations' . url('.php?token=' . $Session->get_token())
	));
	
	//Gestion erreur.
	$get_error = retrieve(GET, 'error', '');
	switch ($get_error)
	{
		case 'success':
			$errstr = $LANG['bugs.error.e_config_success'];
			$errtyp = E_USER_SUCCESS;
			break;
		default:
			$errstr = '';
			$errtyp = E_USER_NOTICE;
	}
	if (!empty($errstr))
		$Template->put('message_helper', MessageHelper::display($errstr, $errtyp));
	
	$Template->display(); // traitement du modele
}

require_once('../admin/admin_footer.php');
?>