<?php
/*##################################################
 *                              bugtracker_constants.php
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

$severity = array('minor', 'major', 'critical');
$priority = array('none', 'low', 'normal', 'high', 'urgent', 'immediate');
$status_list = array('new', 'assigned', 'fixed', 'closed', 'reopen', 'rejected');

//Authorization bit to display de bugs list
define('BUG_READ_AUTH_BIT',		0x01);
//Authorization bit to create a bug
define('BUG_CREATE_AUTH_BIT',	0x02);
//Advanced authorization bit to create a bug (more options are activated)
define('BUG_CREATE_ADVANCED_AUTH_BIT',	0x04);
//Authorization bit to moderate the bugtracker
define('BUG_MODERATE_AUTH_BIT',	0x08);
?>