<?php
/*##################################################
 *                              bugtracker_functions.php
 *                            -------------------
 *   begin                : February 01, 2012
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
 
if (defined('PHPBOOST') !== true)
    exit;

//Coloration du membre suivant son level d'autorisation
function level_to_status($level) {
	$status = '';
	switch ($level) 
	{
		case 0:
		$status = 'member';
		break;
		
		case 1: 
		$status = 'modo';
		break;
		
		case 2: 
		$status = 'admin';
		break;
		
		default:
		$status = 'member';
	}
	return $status;
}

//Nouveau statut possible pour un bug suivant son statut
function new_status_possible($status) {
	
	switch ($status) 
	{
		case 'new':
		$possible = array('new', 'assigned', 'rejected', 'closed', 'pending');
		break;
		
		case 'assigned': 
		$possible = array('assigned', 'fixed', 'pending');
		break;		
		
		case 'fixed': 
		$possible = array('fixed', 'verified', 'assigned', 'rejected');
		break;
		
		case 'verified': 
		$possible = array('verified', 'closed');
		break;
		
		case 'closed': 
		$possible = array('closed', 'reopen');
		break;
		
		case 'reopen': 
		$possible = array('reopen', 'pending', 'assigned');
		break;
		
		case 'rejected': 
		$possible = array('rejected', 'reopen');
		break;
		
		case 'pending': 
		$possible = array('pending', 'closed', 'assigned', 'rejected');
		break;
		
		default:
		$possible = array('');
	}
	return $possible;
}

//Changement d'état d'un bug
function change_bug_status_possible($status, $new_status) {
	return (in_array($new_status, new_status_possible($status))) ? true : false;
}
?>