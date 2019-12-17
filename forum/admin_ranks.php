<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 10
 * @since       PHPBoost 1.2 - 2005 10 30
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../admin/admin_begin.php');
load_module_lang('forum'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$request = AppContext::get_request();

$get_id = $request->get_getint('id', 0);
$del = $request->get_getint('del', 0);

$valid = $request->get_postbool('valid', false);

$template = new FileTemplate('forum/admin_ranks.tpl');

//Si c'est confirmé on execute
if ($valid)
{
	$result = PersistenceContext::get_querier()->select("SELECT id, special
	FROM " . PREFIX . "forum_ranks");
	while ($row = $result->fetch())
	{
		$name = $request->get_poststring($row['id'] . 'name', '');
		$msg_number = $request->get_postint($row['id'] . 'msg', 0);
		$icon = $request->get_poststring($row['id'] . 'icon', '');

		if (!empty($name) && $row['special'] != 1)
			PersistenceContext::get_querier()->update(PREFIX . "forum_ranks", array('name' => $name, 'msg' => $msg_number, 'icon' => $icon), ' WHERE id = :id', array('id' => $row['id']));
		else
			PersistenceContext::get_querier()->update(PREFIX . "forum_ranks", array('name' => $name, 'icon' => $icon), ' WHERE id = :id', array('id' => $row['id']));
	}
	$result->dispose();

	ForumRanksCache::invalidate();

	$template->put('message_helper', MessageHelper::display(LangLoader::get_message('process.success', 'status-messages-common'), MessageHelper::SUCCESS, 4));
}
elseif (!empty($del) && !empty($get_id)) //Suppression du rang.
{
	//On supprime dans la bdd.
	PersistenceContext::get_querier()->delete(PREFIX . 'forum_ranks', 'WHERE id=:id', array('id' => $get_id));

	###### Régénération du cache des rangs #######
	ForumRanksCache::invalidate();

	$template->put('message_helper', MessageHelper::display(LangLoader::get_message('process.success', 'status-messages-common'), MessageHelper::SUCCESS, 4));
}

$template->put_all(array(
	'L_REQUIRE_RANK_NAME'      => $LANG['require_rank_name'],
	'L_REQUIRE_NBR_MSG_RANK'   => $LANG['require_nbr_msg_rank'],
	'L_CONFIRM_DEL_RANK'       => LangLoader::get_message('confirm.delete', 'status-messages-common'),
	'L_FORUM_MANAGEMENT'       => $LANG['config.ranks.manager'],
	'L_FORUM_RANKS_MANAGEMENT' => LangLoader::get_message('forum.ranks.manager', 'common', 'forum'),
	'L_FORUM_ADD_RANKS'        => LangLoader::get_message('forum.rank.add', 'common', 'forum'),
	'L_RANK_NAME'              => $LANG['rank_name'],
	'L_NBR_MSG'                => $LANG['nbr_msg'],
	'L_IMG_ASSOC'              => $LANG['img_assoc'],
	'L_DELETE'                 => LangLoader::get_message('delete', 'common'),
	'L_UPDATE'                 => $LANG['update'],
	'L_RESET'                  => $LANG['reset'],
	'L_ADD'                    => LangLoader::get_message('add', 'common')
));

//On recupère les images des groupes
$rank_options_array = array();

//On regarde s'il existe un repertoire d'image de rank dans le "thème par défaut" templates/{THEME}/modules/forum/images/ranks
$rank_folder = PATH_TO_ROOT . '/templates/' . ThemesManager::get_default_theme() . '/modules/forum/images/ranks';

if ( !is_dir($rank_folder) )
	$rank_folder = (PATH_TO_ROOT . '/forum/templates/images/ranks');

$image_folder_path = new Folder($rank_folder);

foreach ($image_folder_path->get_files('`\.(png|jpg|bmp|gif)$`i') as $image)
{
	$file = $image->get_name();
	$rank_options_array[] = $file;
}

$ranks_cache = ForumRanksCache::load()->get_ranks();

foreach($ranks_cache as $msg => $row)
{
	$rank_options = '<option value="">--</option>';
	foreach ($rank_options_array as $icon)
	{
		$selected = ($icon == $row['icon']) ? ' selected="selected"' : '';
		$rank_options .= '<option value="' . $icon . '"' . $selected . '>' . $icon . '</option>';
	}

	$template->assign_block_vars('rank', array(
		'ID'             => $row['id'],
		'RANK'           => $row['name'],
		'MSG'            => $msg,
		'RANK_OPTIONS'   => $rank_options,
		'IMG_RANK'       => $row['icon'],
		'U_IMG_RANK'     => $rank_folder . '/' . $row['icon'],
		'JS_PATH_RANKS'  => $rank_folder . '/',
		'U_DELETE'       => 'admin_ranks.php?del=1&amp;id=' . $row['id'],
		'C_SPECIAL_RANK' => $row['special'] == 0,
		'L_SPECIAL_RANK' => $LANG['special_rank']
	));
}

$template->display();

require_once('../admin/admin_footer.php');
?>
