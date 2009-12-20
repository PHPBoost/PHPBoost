<?php
/*##################################################
 *                               count.php
 *                            -------------------
 *   begin                : July 28, 2005
 *   copyright            : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
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

require_once('../kernel/begin.php');

$idweb = retrieve(GET, 'id', 0);
if (!empty($idweb))
	$Sql->query_inject("UPDATE " . PREFIX . "web SET compt = compt + 1 WHERE id = '" . $idweb . "'", __LINE__, __LINE__); //MAJ du compteur.

//Redirection vers le site demandé!
$url_web = $Sql->query("SELECT url FROM " . PREFIX . "web WHERE id = '" . $idweb . "'", __LINE__, __FILE__);
if (!empty($url_web))
	redirect($url_web);

?>
