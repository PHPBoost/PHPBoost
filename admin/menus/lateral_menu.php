<?php
/*##################################################
 *                           lateral_menu.php
 *                          -------------------
 *   begin                : November 23, 2008
 *   copyright            : (C) 2008 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

function lateral_menu()
{
    global $LANG;
    $tpl = new FileTemplate('admin/menus/panel.tpl');
    $tpl->put_all(array(
        'L_MENUS_MANAGEMENT' => $LANG['menus_management'],
        'L_ADD_CONTENT_MENUS' => $LANG['menus_content_add'],
        'L_ADD_LINKS_MENUS' => $LANG['menus_links_add'],
        'L_ADD_FEED_MENUS' => $LANG['menus_feed_add']
    ));
    $tpl->display();
}
?>
