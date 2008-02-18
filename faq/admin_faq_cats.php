<?php
/*##################################################
 *                               admin_faq_cats.php
 *                            -------------------
 *   begin                : December 26, 2007
 *   copyright          : (C) 2007 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
 *
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

include_once('../includes/admin_begin.php');
include_once('faq_begin.php'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
include_once('../includes/admin_header.php');

$Cache->Load_file('faq');

include_once('faq_cats.class.php');
$Categories = new FaqCats();

$id_up = !empty($_GET['id_up']) ? numeric($_GET['id_up']) : 0;
$id_down = !empty($_GET['id_down']) ? numeric($_GET['id_down']) : 0;
$cat_to_del = !empty($_GET['del']) ? numeric($_GET['del']) : 0;
$id_edit = !empty($_GET['edit']) ? numeric($_GET['edit']) : 0;
$new_cat = !empty($_GET['new']) ? true : false;

$Template->Set_filenames(array(
	'admin_faq_cat' => '../templates/' . $CONFIG['theme'] . '/faq/admin_faq_cats.tpl'
));

$Template->Assign_vars(array(
	'L_FAQ_MANAGEMENT' => $FAQ_LANG['faq_management'],
	'L_CATS_MANAGEMENT' => $FAQ_LANG['cats_management'],
	'L_CONFIG_MANAGEMENT' => $FAQ_LANG['faq_configuration'],
	'L_ADD_CAT' => $FAQ_LANG['add_cat']
));

if( $id_up > 0 )
{
	$Categories->Move_category($id_up, 'up');
	redirect(transid('admin_faq_cats.php'));
}
elseif( $id_down > 0 )
{
	$Categories->Move_category($id_down, 'down');
	redirect(transid('admin_faq_cats.php'));
}
elseif( $cat_to_del > 0 )
{
	
}
elseif( $new_cat XOR $id_edit > 0 )
{
	$Template->Assign_block_vars('edit_category', array(
		'' => ''
	));
}
else
{	
	$cat_config = array(
		'xmlhttprequest_file' => 'xmlhttprequest_cats.php',
		'administration_file_name' => 'admin_faq_cats.php',
		'url' => array(
			'unrewrited' => '../faq/faq.php?id=%d',
			'rewrited' => '../faq/faq-%d+%s.php'),
		);
		
	$Categories->Set_displaying_configuration($cat_config);
	
	$Template->Assign_block_vars('categories_list', array(
		'CATEGORIES' => $Categories->Build_administration_list($FAQ_CATS)
	));
}

$Template->Pparse('admin_faq_cat');

include_once('../includes/admin_footer.php');

?>