<?php
/*##################################################
 *                              auth.php
 *                            -------------------
 *   begin                : January 18, 2007
 *   copyright          : (C) 2006 Sautel Benoit
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

if( defined('PHPBOOST') !== true)	exit;

function wiki_auth($action, $exception = '')
{
	global $userdata['level'], $userdata['user_group'];
	
	if( !empty($exception) )//Si l'article a une autorisation spéciale
	{
		
	}
	else //Sinon on utilise les règles générales sur le wiki
	{
		if( $_WIKI_AUTH[$action]['all'] <= $userdata['level'] ) //Niveau requis
			return true;
		elseif( $userdata['user_group'] > 0 && $_WIKI_AUTH[$action]['groups'][$userdata['user_group']] != 0 ) //On regarde si l'utilisateur n'appartient pas à un groupe autorisé
			return true;
		else //Sinon accès interdit
			return false;
	}
}

$_WIKI_AUTH['delete_article'] = array('all' => '1', 'groups' => array());

?>