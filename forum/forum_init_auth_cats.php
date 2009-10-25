<?php
/*##################################################
 *                          forum_init_auth_cats.php
 *                            -------------------
 *   begin                : June 22, 2008
 *   copyright            : (C) 2008 LoÃ¯c Rouchon
 *   email                : loic.rouchon@phpboost.com
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

load_module_lang('forum'); //Chargement de la langue du module.
require_once(PATH_TO_ROOT . '/forum/forum_defines.php');

$Cache->load('forum');

//Vérification des autorisations sur toutes les catégories.
$AUTH_READ_FORUM = array();
if (is_array($CAT_FORUM))
{
    foreach ($CAT_FORUM as $idcat => $key)
    {
        if ($User->check_auth($CAT_FORUM[$idcat]['auth'], READ_CAT_FORUM) && $CAT_FORUM[$idcat]['aprob'])
            $AUTH_READ_FORUM[$idcat] = true;
        else
            $AUTH_READ_FORUM[$idcat] = false;
    }
}

?>
