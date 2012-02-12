<?php
/*##################################################
 *                                gallery.php
 *                            -------------------
 *   begin                : November 29 2007
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

if (defined('PHPBOOST') !== true) exit;

//Mise à jour du cache.
@clearstatcache();
	
$chmod_dir = array('../gallery/pics', '../gallery/pics/thumbnails');
//Vérifications et le cas échéants changements des autorisations en écriture.
foreach ($chmod_dir as $dir)
{
	if (file_exists($dir) && is_dir($dir))
	{
		if (!is_writable($dir))
			@chmod($dir, 0777);			
	}
	else
		@mkdir($dir, 0777);
}

if ( !@extension_loaded('gd') )
	MenuService::delete_mini_module('gallery');

?>