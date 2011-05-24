<?php
/*##################################################
 *                               download_auth.php
 *                            -------------------
 *   begin                : April 12, 2008
 *   copyright            : (C) 2008 Sautel Benoit
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

//Authorization bit to read in a category
define('DOWNLOAD_READ_CAT_AUTH_BIT', 0x01);
//Authorization bit to write in a category
define('DOWNLOAD_WRITE_CAT_AUTH_BIT', 0x02);
//Authorization bit to contribution in a category
define('DOWNLOAD_CONTRIBUTION_CAT_AUTH_BIT', 0x04);

//Flag used in the database to know if we must force the file download or redirect the user up tu the file by a HTTP redirection
define('DOWNLOAD_FORCE_DL', 1);
define('DOWNLOAD_REDIRECT', 0);

?>