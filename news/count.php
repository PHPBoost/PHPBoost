<?php
/*##################################################
 *                               count.php
 *                            -------------------
 *   begin                : May 29, 2010 
 *   copyright            : (C) 2010 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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
require_once('news_constant.php');

$id = AppContext::get_request()->get_getint('id', 0);
if (!empty($id))
	$Sql->query_inject("UPDATE " . DB_TABLE_NEWS . " SET compt = compt + 1 WHERE id = '" . $id . "'", __LINE__, __LINE__); //MAJ du compteur.

?>
