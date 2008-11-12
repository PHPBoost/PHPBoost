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
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 * 
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

require_once('../kernel/begin.php');
require_once('../download/download_begin.php');
require_once('../kernel/header.php');

$Cache->load('download');

if( !$User->check_auth($CONFIG_DOWNLOAD['global_auth'], DOWNLOAD_CONTRIBUTION_CAT_AUTH_BIT) )
	$Errorh->handler('e_auth', E_USER_REDIRECT);

//Template
$download_template = new Template();



$download_template->parse();



require_once('../kernel/footer.php'); 

?>