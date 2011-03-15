<?php
/*##################################################
 *                        download_contribution.php
 *                            -------------------
 *   begin                : November 12 2008
 *   copyright            : (C) 2008 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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

require_once('../kernel/begin.php');

// Définition des constantes des autorisations
require_once 'download_auth.php';
//Chargement du cache du module
$Cache->load('download');
//Chargement de la langue du module
load_module_lang('download');

if (!$User->check_auth($CONFIG_DOWNLOAD['global_auth'], DOWNLOAD_CONTRIBUTION_CAT_AUTH_BIT))
{
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}

define('TITLE', $DOWNLOAD_LANG['contribution_confirmation']);

$Bread_crumb->add($DOWNLOAD_LANG['download'], url('download.php'));
$Bread_crumb->add($DOWNLOAD_LANG['contribution_confirmation'], url('contribution.php'));

require_once('../kernel/header.php');

//Template
$download_template = new FileTemplate('download/contribution.tpl');

$download_template->put_all(array(
	'L_CONTRIBUTION_CONFIRMATION' => $DOWNLOAD_LANG['contribution_confirmation'],
	'L_CONTRIBUTION_SUCCESS' => $DOWNLOAD_LANG['contribution_success'],
	'L_CONTRIBUTION_CONFIRMATION_EXPLAIN' => $DOWNLOAD_LANG['contribution_confirmation_explain']
));

$download_template->display();

require_once('../kernel/footer.php'); 

?>