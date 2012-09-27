<?php
/*##################################################
 *                              bugtracker_begin.php
 *                            -------------------
 *   begin                : February 01, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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
 
if (defined('PHPBOOST') !== true)
    exit;

load_module_lang('bugtracker'); //Chargement de la langue du module.

$Bread_crumb->add($LANG['bugs.module_title'], url('bugtracker.php'));
$Bread_crumb->reverse();
if (!defined('TITLE'))
{
	define('TITLE', $LANG['bugs.module_title']);
}

$id = retrieve(GET, 'id', 0, TINTEGER);
if (isset($_GET['add']))
{
	$Bread_crumb->add($LANG['bugs.titles.add_bug'], url('bugtracker.php?add=true'));
}
else if (isset($_GET['edit']) && is_numeric($id))
{
	$Bread_crumb->add($LANG['bugs.titles.edit_bug'] . ' #' . $id, url('bugtracker.php?edit=true&amp;id=' . $id));
}
elseif (isset($_GET['history']) && is_numeric($id))
{
	$Bread_crumb->add($LANG['bugs.titles.history_bug'] . ' #' . $id, url('bugtracker.php?history=true&amp;id=' . $id));
}
elseif (isset($_GET['view']) && is_numeric($id))
{
	$Bread_crumb->add($LANG['bugs.titles.view_bug'] . ' #' . $id, url('bugtracker.php?view=true&amp;id=' . $id));
}
elseif (isset($_GET['solved']))
{
	$Bread_crumb->add($LANG['bugs.titles.solved_bugs'], url('bugtracker.php?solved'));
}
elseif (isset($_GET['stats']))
{
	$Bread_crumb->add($LANG['bugs.titles.bugs_stats'], url('bugtracker.php?stats'));
}
else
{
	$Bread_crumb->add($LANG['bugs.titles.unsolved_bugs'], url('bugtracker.php'));
}

//Chargement du cache
$Cache->load('bugtracker');
?>