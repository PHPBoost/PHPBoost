<?php
/*##################################################
 *                               show_pics.php
 *                            -------------------
 *   begin                : August 12, 2005
 *   copyright          : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
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
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

require_once('../includes/begin.php'); 
require_once('../gallery/gallery_begin.php');
require_once('../includes/header_no_display.php');

$g_idpics = !empty($_GET['id']) ? numeric($_GET['id']) : 0;
$g_idcat = !empty($_GET['cat']) ? numeric($_GET['cat']) : 0;
	
if( !empty($g_idpics) )
{
	if( !empty($g_idcat) )
	{
		if( !isset($CAT_GALLERY[$g_idcat]) || $CAT_GALLERY[$g_idcat]['aprob'] == 0 ) 
			redirect(HOST . DIR . '/gallery/gallery.php?error=unexist_cat');
	}
	else //Racine.
	{
		$CAT_GALLERY[0]['auth'] = $CONFIG_GALLERY['auth_root'];
		$CAT_GALLERY[0]['aprob'] = 1;
	}
	//Niveau d'autorisation de la catégorie
	if( !$groups->check_auth($CAT_GALLERY[$g_idcat]['auth'], READ_CAT_GALLERY) )
		$errorh->error_handler('e_auth', E_USER_REDIRECT); 
	
	//Mise à jour du nombre de vues.
	$sql->query_inject("UPDATE LOW_PRIORITY ".PREFIX."gallery SET views = views + 1 WHERE idcat = '" . $g_idcat . "' AND id = '" . $g_idpics . "'", __LINE__, __FILE__);
	
	$clause_admin = $session->data['level'] == 2 ? '' : ' AND aprob = 1';
	$path = $sql->query("SELECT path FROM ".PREFIX."gallery WHERE idcat = '" . $g_idcat . "' AND id = '" . $g_idpics . "'" . $clause_admin, __LINE__, __FILE__);
	if( empty($path) )
		$errorh->error_handler('e_auth', E_USER_REDIRECT); 

	include_once('../gallery/gallery.class.php');
	$gallery = new Gallery;
		
	list($width_s, $height_s, $weight_s, $ext) = $gallery->arg_pics('pics/' . $path);
	$gallery->send_header($ext); //Header image.
	if( !empty($gallery->error) )
		die($gallery->error);
	$gallery->incrust_pics('pics/' . $path); // => logo.
}
else
{
	die($LANG['no_random_img']); //Echec paramètres images incorrects.
}

require_once('../includes/footer_no_display.php');

?>