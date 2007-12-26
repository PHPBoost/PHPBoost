<?php
/*##################################################
 *                                header_no_display.php
 *                            -------------------
 *   begin                : August 14, 2005
 *   copyright          : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
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
 
if( defined('PHP_BOOST') !== true) exit;

//On ne vérifie pas lors de la première connexion.
if( !isset($_POST['connect']) && !isset($_POST['disconnect']) ) 
{	
	$session->session_check(SCRIPT, QUERY_STRING, TITLE); //Vérification de la session.

	//Gestion de la maintenance du site.
	if( $CONFIG['maintain'] != 0 && !$session->check_auth($session->data, 2) )
	{	
		if( SCRIPT !== (DIR . '/includes/maintain.php') )
		{ 
			header('location: ' . HOST . DIR . '/includes/maintain.php');
			exit;	
		}
	}
}
?>