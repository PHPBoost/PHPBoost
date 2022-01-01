<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 01
 * @since       PHPBoost 1.6 - 2007 08 30
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

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
	$categories = CategoriesService::get_categories_manager('gallery')->get_categories_cache()->get_categories();
	$g_idpics = $request->get_getint('id', 0);
	$g_id_category = $request->get_getint('cat', 0);
	if (empty($g_idpics) || (!empty($g_id_category) && !isset($categories[$g_id_category])))
		exit;

	//Niveau d'autorisation de la catégorie
	if (!CategoriesAuthorizationsService::check_authorizations($g_id_category)->read())
		exit;

	//Mise à jour du nombre de vues.
	PersistenceContext::get_querier()->inject("UPDATE " . GallerySetup::$gallery_table . " SET views = views + 1 WHERE id_category = :id_category AND id = :id", array('id_category' => $g_id_category, 'id' => $g_idpics));
}
elseif (!empty($rename_pics)) //Renomme une image.
{
	AppContext::get_session()->csrf_get_protect(); //Protection csrf

	try {
		$id_cat = PersistenceContext::get_querier()->get_column_value(GallerySetup::$gallery_table, 'id_category', 'WHERE id = :id', array('id' => $id_file));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	if (CategoriesAuthorizationsService::check_authorizations($id_cat)->moderation()) //Modo
	{
		//Initialisation  de la class de gestion des fichiers.
		include_once(PATH_TO_ROOT .'/gallery/Gallery.class.php');
		$Gallery = new Gallery;

		$name = $request->get_postvalue('name', '');
		$previous_name = TextHelper::strprotect(utf8_decode($request->get_postvalue('previous_name', '')));

		if (!empty($id_file))
			echo $Gallery->Rename_pics($id_file, $name, $previous_name);
		else
			echo -1;
	}
}
elseif (!empty($aprob_pics))
{
	AppContext::get_session()->csrf_get_protect(); //Protection csrf

	try {
		$id_cat = PersistenceContext::get_querier()->get_column_value(GallerySetup::$gallery_table, 'id_category', 'WHERE id = :id', array('id' => $id_file));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	if (CategoriesAuthorizationsService::check_authorizations($id_cat)->moderation()) //Modo
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
else
{
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}

?>
