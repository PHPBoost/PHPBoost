<?php
/*##################################################
 *                                speed_bar.php
 *                            -------------------
 *   begin                : August 01, 2006
 *   copyright          : (C) 2006 Sautel Beno�t
 *   email                : ben.popeye@phpboost.com
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

if( defined('PHP_BOOST') !== true)	
	exit;

$template->set_filenames(array(
	'speed_bar' => '../templates/' . $CONFIG['theme'] . '/speed_bar.tpl'
));
	
$template->assign_vars(array(
	'START_PAGE' => get_start_page(),
	'L_INDEX' => $LANG['index']	
));
	
if( !isset($SPEED_BAR) || !is_array($SPEED_BAR) )
{
	$template->assign_block_vars('link_speed_bar', array(
		'URL' => HOST . SCRIPT . SID,
		'TITLE' => stripslashes(TITLE)
	));
}	
else
{		
	foreach($SPEED_BAR as $key => $array)
	{
		$template->assign_block_vars('link_speed_bar', array(
			'URL' => $array[1],
			'TITLE' => $array[0]
		));	
	}
}

$template->pparse('speed_bar');

?>
