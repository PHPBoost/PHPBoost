<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 29
 * @since       PHPBoost 1.2 - 2005 08 12
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

require_once('../kernel/begin.php');
require_once('../gallery/gallery_begin.php');
require_once('../kernel/header_no_display.php');
$request = AppContext::get_request();

$g_idpics = $request->get_getint('id', 0);

if (!empty($g_idpics))
{
	//Niveau d'autorisation de la catégorie
	if (!CategoriesAuthorizationsService::check_authorizations($id_category)->read())
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}

	//Mise à jour du nombre de vues.
	PersistenceContext::get_querier()->inject("UPDATE " . GallerySetup::$gallery_table . " SET views = views + 1 WHERE id_category = :id_category AND id = :id", array('id_category' => $id_category, 'id' => $g_idpics));

	try {
		$path = PersistenceContext::get_querier()->get_column_value(GallerySetup::$gallery_table, 'path', 'WHERE id_category = :id_category AND id = :id' . (AppContext::get_current_user()->check_level(User::ADMIN_LEVEL) ? '' : ' AND aprob = 1'), array('id_category' => $id_category, 'id' => $g_idpics));
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
