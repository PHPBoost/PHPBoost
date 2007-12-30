<?php
/*##################################################
 *                               articles_changeday.php
 *                            -------------------
 *   begin                : April 02, 2007
 *   copyright          : (C) 2007 Viarre Régis
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

if( defined('PHP_BOOST') !== true) exit;

//Publication des articles en attente pour la date donnée.
$result = $sql->query_while("SELECT id, start, end
FROM ".PREFIX."articles	
WHERE visible != 0", __LINE__, __FILE__);
while($row = $sql->sql_fetch_assoc($result) )
{ 
	if( $row['start'] <= time() && $row['start'] != 0 )
		$sql->query_inject("UPDATE ".PREFIX."articles SET visible = 1, start = 0 WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
	if( $row['end'] <= time() && $row['end'] != 0 )
		$sql->query_inject("UPDATE ".PREFIX."articles SET visible = 0, start = 0, end = 0 WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
}

?>