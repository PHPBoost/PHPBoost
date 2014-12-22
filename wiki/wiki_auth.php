<?php
/*##################################################
 *                              wik_begin.php
 *                            -------------------
 *   begin                : May 20, 2007
 *   copyright            : (C) 2007 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
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

if (defined('PHPBOOST') !== true)	exit;

define('WIKI_CREATE_ARTICLE', 0x01);
define('WIKI_CREATE_CAT', 0x02);
define('WIKI_RESTORE_ARCHIVE', 0x04);
define('WIKI_DELETE_ARCHIVE', 0x08);
define('WIKI_EDIT', 0x10);
define('WIKI_DELETE', 0x20);
define('WIKI_RENAME', 0x40);
define('WIKI_REDIRECT', 0x80);
define('WIKI_MOVE', 0x100);
define('WIKI_STATUS', 0x200);
define('WIKI_COM', 0x400);
define('WIKI_RESTRICTION', 0x800);

?>