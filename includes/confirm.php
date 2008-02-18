<?php
/*##################################################
 *                               confirm.php
 *                            -------------------
 *   begin                : January 20 2006
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

if( defined('PHP_BOOST') !== true )
	exit;
else
{
	$Template->Set_filenames(array(
		'confirm' => '../templates/' . $CONFIG['theme'] . '/confirm.tpl'
	));
		
	//$URL_ERROR,  $L_ERROR $$DELAY_REDIRECT et en variable globale.
	$URL_ERROR = isset($URL_ERROR) ? $URL_ERROR : '';
	$DELAY_REDIRECT = isset($DELAY_REDIRECT) ? $DELAY_REDIRECT : '3';
	$L_ERROR = isset($L_ERROR) ? $L_ERROR : '';
		
	$Template->Assign_vars(array(
		'URL_ERROR' => $URL_ERROR,
		'DELAY_REDIRECT' => $DELAY_REDIRECT,
		'L_ERROR' => $L_ERROR,
		'L_REDIRECT' => $LANG['redirect']
	));
	
	$Template->Pparse('confirm'); 
}	

?>