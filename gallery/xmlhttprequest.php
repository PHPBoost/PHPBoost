<?php
/*##################################################
 *                               xmlhttprequest.php
 *                            -------------------
 *   begin                : August 30, 2007
 *   copyright            : (C) 2007 Viarre Régis
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
AppContext::get_session()->no_session_location(); //Permet de ne pas mettre jour la page dans la session.
include_once('../gallery/gallery_begin.php');
require_once('../kernel/header_no_display.php');

if (AppContext::get_current_user()->is_readonly())
	exit;

$config = GalleryConfig::load();
$request = AppContext::get_request();

$increment_view = $request->get_getint('increment_view', 0);
$rename_pics = $request->get_getint('rename_pics', 0);
$aprob_pics = $request->get_getint('aprob_pics', 0);
$id_file = $request->get_postint('id_file', 0);

//Notation.
if (!empty($increment_view))
{
	$categories = GalleryService::get_categories_manager()->get_categories_cache()->get_categories();
	$g_idpics = $request->get_getint('id', 0);
	$g_idcat = $request->get_getint('cat', 0);
	if (empty($g_idpics) || (!empty($g_idcat) && !isset($categories[$g_idcat])))
		exit;
	
	//Niveau d'autorisation de la catégorie
	if (!GalleryAuthorizationsService::check_authorizations($g_idcat)->read())
		exit;

	//Mise à jour du nombre de vues.
	PersistenceContext::get_querier()->inject("UPDATE " . GallerySetup::$gallery_table . " SET views = views + 1 WHERE idcat = :idcat AND id = :id", array('idcat' => $g_idcat, 'id' => $g_idpics));
}
else
{
	AppContext::get_session()->csrf_get_protect(); //Protection csrf
	
	if (!empty($rename_pics)) //Renomme une image.
	{
		try {
			$id_cat = PersistenceContext::get_querier()->get_column_value(GallerySetup::$gallery_table, 'idcat', 'WHERE id = :id', array('id' => $id_file));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		
		if (GalleryAuthorizationsService::check_authorizations($id_cat)->moderation()) //Modo
		{
			//Initialisation  de la class de gestion des fichiers.
			include_once(PATH_TO_ROOT .'/gallery/Gallery.class.php');
			$Gallery = new Gallery;
			
			$name = TextHelper::strprotect(utf8_decode($request->get_postvalue('name', '')));
			$previous_name = TextHelper::strprotect(utf8_decode($request->get_postvalue('previous_name', '')));
			
			if (!empty($id_file))
				echo $Gallery->Rename_pics($id_file, $name, $previous_name);
			else 
				echo -1;
		}
	}
	elseif (!empty($aprob_pics))
	{
		try {
			$id_cat = PersistenceContext::get_querier()->get_column_value(GallerySetup::$gallery_table, 'idcat', 'WHERE id = :id', array('id' => $id_file));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		
		if (GalleryAuthorizationsService::check_authorizations($id_cat)->moderation()) //Modo
		{
			$Gallery = new Gallery();
			
			if (!empty($id_file))
			{
				echo $Gallery->Aprob_pics($id_file);
				//Régénération du cache des photos aléatoires.
				GalleryMiniMenuCache::invalidate();
				GalleryCategoriesCache::invalidate();
			}
			else 
				echo 0;
		}
	}
}

?>