<?php
/*##################################################
 *                             xmlhttprequest_cats.php
 *                            -------------------
 *   begin                : February 08, 2008
 *   copyright          : (C) 2008 Benoît Sautel
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
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

require_once('../includes/begin.php');
define('TITLE', 'Ajax faq');
require_once('../includes/header_no_display.php');

if( $Member->Get_attribute('level') === 2 ) //Admin
{	
	$Cache->Load_file('faq');
	include_once('../includes/cats_management.class.php');
	// Only those two parameters must be changed if you want to use again this file in another context
	$Categories = new CategoriesManagement('faq_cats', 'faq');
	
	$id_up = !empty($_GET['id_up']) ? numeric($_GET['id_up']) : 0;
	$id_down = !empty($_GET['id_down']) ? numeric($_GET['id_down']) : 0;
	$cat_to_del = !empty($_GET['del']) ? numeric($_GET['del']) : 0;
	
	$result = false;
	
	if( $id_up > 0 )
		$result = $Categories->Move_category($id_up, 'up');
	elseif( $id_down > 0 )
		$result = $Categories->Move_category($id_down, 'down');
	
	//Operation was successfully
	if( $result )
	{	
		$cat_config = array(
			'xmlhttprequest_file' => 'xmlhttprequest_cats.php',
			'administration_file_name' => 'admin_faq_cats.php',
			'url' => array(
				'unrewrited' => '../faq/faq.php?id=%d',
				'rewrited' => '../faq/faq-%d+%s.php'),
			);
		
		$Categories->Set_displaying_configuration($cat_config);
		
		$Cache->Load_file('faq', RELOAD_CACHE);
	
		echo $Categories->Build_administration_list($FAQ_CATS, AJAX_MODE);
	}
}
include_once('../includes/footer_no_display.php');

?>
