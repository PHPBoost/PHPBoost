<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Geoffrey ROGUELON <liaght@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2023 10 03
 * @since       PHPBoost 2.0 - 2008 10 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

if (defined('PHPBOOST') !== true)
{
	exit;
}

$request = AppContext::get_request();

$id_media = $request->get_getint('id', 0);
$id_cat = $request->get_getint('cat', 0);

require_once('media_constant.php');

function bread_crumb($id)
{
	global $Bread_crumb;
	$Bread_crumb->add(LangLoader::get_message('media.module.title', 'common', 'media'), MediaUrlBuilder::home());

	$categories = array_reverse(CategoriesService::get_categories_manager('media')->get_parents($id, true));
	foreach ($categories as $category)
	{
		if ($category->get_id() != Category::ROOT_CATEGORY)
			$Bread_crumb->add($category->get_name(), url('media.php?cat=' . $category->get_id(), 'media-0-' . $category->get_id() . '-' . $category->get_rewrited_name() . '.php'));
	}
}

?>
