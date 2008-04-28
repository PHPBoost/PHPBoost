<?php
/*##################################################
 *                               admin_config.php
 *                            -------------------
 *   begin                : June 20, 2005
 *   copyright          : (C) 2005 Viarre Régis
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

require_once('../kernel/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../kernel/admin_header.php');

//Si c'est confirmé on execute
if( !empty($_POST['cache']) )
{
	$Cache->Generate_all_files();
	redirect(HOST . DIR . '/admin/admin_cache.php?s=1');
}
else //Sinon on rempli le formulaire	 
{		
	$Template->Set_filenames(array(
		'admin_cache'=> 'admin/admin_cache.tpl'
	));

	//Gestion erreur.
	$get_error = !empty($_GET['s']) ? strprotect($_GET['s']) : '';
	if( $get_error == 1 )
		$Errorh->Error_handler($LANG['cache_success'], E_USER_SUCCESS);
	
	$Template->Assign_vars(array(
		'L_CACHE' => $LANG['cache'],
		'L_EXPLAIN_SITE_CACHE' => $LANG['explain_site_cache'],
		'L_GENERATE' => $LANG['generate']	
	));
	
	$Template->Pparse('admin_cache');
}

require_once('../kernel/admin_footer.php');

?>