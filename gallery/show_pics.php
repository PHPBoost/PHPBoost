<?php
/*##################################################
 *                               show_pics.php
 *                            -------------------
 *   begin                : August 12, 2005
 *   copyright            : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
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
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

require_once('../kernel/begin.php');
require_once('../gallery/gallery_begin.php');
require_once('../kernel/header_no_display.php');

$g_idpics = retrieve(GET, 'id', 0);

if (!empty($g_idpics))
{
	//Niveau d'autorisation de la catégorie
	if (!GalleryAuthorizationsService::check_authorizations($id_category)->read())
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}

	//Mise à jour du nombre de vues.
	PersistenceContext::get_querier()->inject("UPDATE " . GallerySetup::$gallery_table . " SET views = views + 1 WHERE idcat = :idcat AND id = :id", array('idcat' => $id_category, 'id' => $g_idpics));

	try {
		$path = PersistenceContext::get_querier()->get_column_value(GallerySetup::$gallery_table, 'path', 'WHERE idcat = :idcat AND id = :id' . (AppContext::get_current_user()->check_level(User::ADMIN_LEVEL) ? '' : ' AND aprob = 1'), array('idcat' => $id_category, 'id' => $g_idpics));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

$Gallery = new Gallery();

	list($width_s, $height_s, $weight_s, $ext) = $Gallery->Arg_pics('pics/' . $path);
	$Gallery->Send_header($ext); //Header image.
	if ($Gallery->get_error() != '')
		die($Gallery->get_error());
	$Gallery->incrust_pics('pics/' . $path); // => logo.
}
else
{
	die($LANG['no_random_img']); //Echec paramètres images incorrects.
}

require_once('../kernel/footer_no_display.php');

?>