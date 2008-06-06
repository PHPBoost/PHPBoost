<?php
/*##################################################
 *                              download_changeday.php
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

if( defined('PHPBOOST') !== true) exit;

//Publication des téléchargements en attente pour la date donnée.
$result = $Sql->Query_while("SELECT id, start, end
FROM ".PREFIX."download
WHERE start > 0 AND end > 0", __LINE__, __FILE__);
$time = time();
while($row = $Sql->Sql_fetch_assoc($result) )
{
	//If the file wasn't visible and it becomes visible
	if( $row['start'] <= $time && $row['end'] >= $time && $row['visible'] = 0 )
		$Sql->Query_inject("UPDATE ".PREFIX."download SET visible = 1 WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
	
	//If it's not visible anymore
	if( $row['start'] >= $time || $row['end'] <= $time && $row['visible'] = 1 )
		$Sql->Query_inject("UPDATE ".PREFIX."download SET visible = 0 WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
}

?>