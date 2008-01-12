<?php
/*##################################################
 *                               admin_com.php
 *                            -------------------
 *   begin                : January 11, 2008
 *   copyright          : (C) 2007 Viarre Régis
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

require_once('../includes/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../includes/admin_header.php');

$del = !empty($_GET['del']) ? true : false;
$edit = !empty($_GET['edit']) ? true : false;
$idcom = !empty($_GET['id']) ? numeric($_GET['id']) : 0;

if( $del && !empty($idcom)  ) //Suppression d'un com.
{

}
elseif( $edit && !empty($idcom)  ) //Edition d'un com.
{

}
else	
{		
	$template->set_filenames(array(
		'admin_com_management' => '../templates/' . $CONFIG['theme'] . '/admin/admin_com_management.tpl'
	));
	
	$template->assign_vars(array(
		'L_COM' => $LANG['com'],
		'L_COM_MANAGEMENT' => $LANG['com_management'],
		'L_COM_CONFIG' => $LANG['com_config'],
	));

$result = $sql->query_while("SELECT * 
FROM ".PREFIX."com c
LEFT JOIN ".PREFIX."member m ON m.user_id = c.user_id
GROUP BY c.idcom
ORDER BY c.timestamp DESC", __LINE__, __FILE__);
while($row = $sql->sql_fetch_assoc($result) )
{
	echo $row['contents'];
}
$sql->close($result);
	
	$template->pparse('admin_com_management'); // traitement du modele	
}

require_once('../includes/admin_footer.php');

?>