<?php
/*##################################################
 *                               count.php
 *                            -------------------
 *   begin                : July 27, 2005
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

include_once('../includes/begin.php');

$idurl = !empty($_GET['id']) ? numeric($_GET['id']) : 0;
if( !empty($idurl) )
	$sql->query_inject("UPDATE ".PREFIX."download SET compt = compt + 1 WHERE id = '" . $idurl . "'", __LINE__, __FILE__); //MAJ du compteur.
$file_path = $sql->query("SELECT url FROM ".PREFIX."download WHERE id = '" . $idurl . "'", __LINE__, __FILE__);

//Redirection vers le fichier demandé!
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
$filesize = @filesize($file_path);
if( $filesize !== false )
	header("Content-Length: " . $filesize);
header("Content-Type: application/octet-stream"); 
header('Content-Disposition: attachment; filename="' . substr(strrchr($file_path, '/'), 1) . '"');
if( @readfile($file_path) === false )
{
	header('location:' . $file_path, true);
	exit;
}
?>
