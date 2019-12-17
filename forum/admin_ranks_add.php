<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 10
 * @since       PHPBoost 1.2 - 2005 10 30
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../admin/admin_begin.php');
load_module_lang('forum'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$request = AppContext::get_request();

$add = $request->get_postbool('add', false);

$template = new FileTemplate('forum/admin_ranks_add.tpl');

//Ajout du rang.
if ($add)
{
	$name = $request->get_poststring('name', '');
	$msg_number = $request->get_postint('msg', 0);
	$icon = $request->get_poststring('icon', '');

	if (!empty($name) && $msg_number >= 0)
	{
		//On insere le nouveau lien, tout en précisant qu'il s'agit d'un lien ajouté et donc supprimable
		PersistenceContext::get_querier()->insert(PREFIX . "forum_ranks", array('name' => $name, 'msg' => $msg_number, 'icon' => $icon, 'special' => 0));

		###### Régénération du cache des rangs #######
		ForumRanksCache::invalidate();

		$template->put('message_helper', MessageHelper::display(LangLoader::get_message('process.success', 'status-messages-common'), MessageHelper::SUCCESS, 4));
	}
	else
		$template->put('message_helper', MessageHelper::display($LANG['e_incomplete'], MessageHelper::NOTICE));
}
elseif (!empty($_FILES['upload_ranks']['name'])) //Upload
{
	//Si le dossier n'est pas en écriture on tente un CHMOD 777
	@clearstatcache();
	$dir = PATH_TO_ROOT . '/forum/templates/images/ranks/';
	if (!is_writable($dir))
		$is_writable = @chmod($dir, 0777);

	$error = '';
	if (is_writable($dir)) //Dossier en écriture, upload possible
	{
		$authorized_pictures_extensions = FileUploadConfig::load()->get_authorized_picture_extensions();

		if (!empty($authorized_pictures_extensions))
		{
			$Upload = new Upload($dir);
			$Upload->disableContentCheck();
			if (!$Upload->file('upload_ranks', '`\.(' . implode('|', array_map('preg_quote', $authorized_pictures_extensions)) . ')+$`iu'))
				$error = $Upload->get_error();
		}
		else
			$error = 'e_upload_invalid_format';
	}
	else
		$error = 'e_upload_failed_unwritable';

	if (!empty($error))
		$template->put('message_helper', MessageHelper::display($LANG[$error], MessageHelper::WARNING));
	else
		$template->put('message_helper', MessageHelper::display(LangLoader::get_message('process.success', 'status-messages-common'), MessageHelper::SUCCESS, 4));
}

//On recupère les images des groupes
$rank_options = '<option value="">--</option>';


$image_folder_path = new Folder(PATH_TO_ROOT . '/forum/templates/images/ranks/');
foreach ($image_folder_path->get_files('`\.(png|jpg|bmp|gif)$`iu') as $image)
{
	$file = $image->get_name();
	$rank_options .= '<option value="' . $file . '">' . $file . '</option>';
}

$template->put_all(array(
	'RANK_OPTIONS'             => $rank_options,
	'MAX_FILE_SIZE' 		   => ServerConfiguration::get_upload_max_filesize(),
	'MAX_FILE_SIZE_TEXT'       => File::get_formated_size(ServerConfiguration::get_upload_max_filesize()),
	'ALLOWED_EXTENSIONS'       => implode('", "',FileUploadConfig::load()->get_authorized_picture_extensions()),
	'L_REQUIRE'                => LangLoader::get_message('form.explain_required_fields', 'status-messages-common'),
	'L_REQUIRE_RANK_NAME'      => $LANG['require_rank_name'],
	'L_REQUIRE_NBR_MSG_RANK'   => $LANG['require_nbr_msg_rank'],
	'L_FORUM_MANAGEMENT'       => $LANG['config.ranks.manager'],
	'L_FORUM_RANKS_MANAGEMENT' => LangLoader::get_message('forum.ranks.manager', 'common', 'forum'),
	'L_FORUM_ADD_RANKS'        => LangLoader::get_message('forum.rank.add', 'common', 'forum'),
	'L_UPLOAD_RANKS'           => $LANG['upload_rank'],
	'L_UPLOAD_FORMAT'          => $LANG['explain_upload_img'],
	'L_UPLOAD'                 => $LANG['upload'],
	'L_RANK_NAME'              => $LANG['rank_name'],
	'L_NBR_MSG'                => $LANG['nbr_msg'],
	'L_IMG_ASSOC'              => $LANG['img_assoc'],
	'L_DELETE'                 => LangLoader::get_message('delete', 'common'),
	'L_UPDATE'                 => $LANG['update'],
	'L_RESET'                  => $LANG['reset'],
	'L_ADD'                    => LangLoader::get_message('add', 'common')
));

$template->display();

require_once('../admin/admin_footer.php');
?>
