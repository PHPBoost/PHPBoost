<?php
/*##################################################
 *                                shoutbox_changeday.php
 *                            -------------------
 *   begin                : November 25, 2006
 *   copyright          : (C) 2006 Viarre Régis
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

if( defined('PHPBOOST') !== true) exit;

$Cache->Load_file('shoutbox'); //$CONFIG_SHOUTBOX en global.

if( $CONFIG_SHOUTBOX['shoutbox_max_msg'] != -1 )
{
	//Suppression des messages en surplus dans la shoutbox.
	$Sql->Query_inject("SELECT @compt := id AS compt
	FROM ".PREFIX."shoutbox
	ORDER BY id DESC
	" . $Sql->Sql_limit(0, $CONFIG_SHOUTBOX['shoutbox_max_msg']), __LINE__, __FILE__);
	$Sql->Query_inject("DELETE FROM ".PREFIX."shoutbox WHERE id < @compt", __LINE__, __FILE__);
}

?>