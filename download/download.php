<?php
/*##################################################
 *                               download.php
 *                            -------------------
 *   begin                : July 27, 2005
 *   copyright            : (C) 2005 Viarre Régis, Sautel Benoit
 *   email                : crowkait@phpboost.com, ben.popeye@phpboost.com
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
require_once('../download/download_begin.php');
require_once('../kernel/header.php');

if ($file_id > 0) //Contenu
{
	$notation->set_id_in_module($file_id);

	$Template->set_filenames(array('download'=> 'download/download.tpl'));
	
	if ($download_info['size'] > 1)
		$size_tpl = $download_info['size'] . ' ' . $LANG['unit_megabytes'];
	elseif ($download_info['size'] > 0)
		$size_tpl = ($download_info['size'] * 1024) . ' ' . $LANG['unit_kilobytes'];
	else
		$size_tpl = $DOWNLOAD_LANG['unknown_size'];
	
 	$creation_date = new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $download_info['timestamp']);
 	$release_date = new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $download_info['release_timestamp']);
	
	$Template->put_all(array(
		'C_DISPLAY_DOWNLOAD' => true,
		'C_IMG' => !empty($download_info['image']),
		'C_EDIT_AUTH' => $auth_write,
		'ID_FILE' => $file_id,
		'NAME' => $download_info['title'],
		'CONTENTS' => FormatingHelper::second_parse($download_info['contents']),
		'CREATION_DATE' => $creation_date->format(DATE_FORMAT_SHORT),
		'RELEASE_DATE' => $release_date->get_timestamp() > 0 ? $release_date->format(DATE_FORMAT_SHORT) : $DOWNLOAD_LANG['unknown_date'],
		'SIZE' => $size_tpl,
		'COUNT' => $download_info['count'],
		'THEME' => get_utheme(),
		'KERNEL_NOTATION' => NotationService::display_active_image($notation),
		'HITS' => sprintf($DOWNLOAD_LANG['n_times'], (int)$download_info['count']),
		'NUM_NOTES' => sprintf($DOWNLOAD_LANG['num_notes'], (int)NotationService::get_former_number_notes($notation)),
		'U_IMG' => $download_info['image'],
		'IMAGE_ALT' => str_replace('"', '\"', $download_info['title']),
		'LANG' => get_ulang(),
		'U_COM' => '<a href="'. PATH_TO_ROOT .'/download/download' . url('.php?id=' . $file_id . '&amp;com=0', '-' . $file_id . '+' . Url::encode_rewrite($download_info['title']) . '.php?com=0') .'">'. CommentsService::get_number_and_lang_comments('download', $file_id) . '</a>',
		'L_DATE' => $LANG['date'],
		'L_SIZE' => $LANG['size'],
		'L_DOWNLOAD' => $DOWNLOAD_LANG['download'],
		'L_DOWNLOAD_FILE' => $DOWNLOAD_LANG['download_file'],
		'L_FILE_INFOS' => $DOWNLOAD_LANG['file_infos'],
		'L_INSERTION_DATE' => $DOWNLOAD_LANG['insertion_date'],
		'L_RELEASE_DATE' => $DOWNLOAD_LANG['last_update_date'],
		'L_DOWNLOADED' => $DOWNLOAD_LANG['downloaded'],
		'L_EDIT_FILE' => str_replace('"', '\"', $DOWNLOAD_LANG['edit_file']),
		'L_CONFIRM_DELETE_FILE' => str_replace('\'', '\\\'', $DOWNLOAD_LANG['confirm_delete_file']),
		'L_DELETE_FILE' => str_replace('"', '\"', $DOWNLOAD_LANG['delete_file']),
		'L_DEADLINK' => $DOWNLOAD_LANG['deadlink'],
		'U_EDIT_FILE' => url('management.php?edit=' . $file_id),
		'U_DELETE_FILE' => url('management.php?del=' . $file_id . '&amp;token=' . $Session->get_token()),
		'U_DOWNLOAD_FILE' => url('count.php?id=' . $file_id, 'file-' . $file_id . '+' . Url::encode_rewrite($download_info['title']) . '.php'),
		'U_DEADLINK' => url('download.php?id=' . $file_id . '&deadlink=1')
	));
	
	//Affichage commentaires.
	if (isset($_GET['com']))
	{
		$comments_topic = new DownloadCommentsTopic();
		$comments_topic->set_id_in_module($file_id);
		$comments_topic->set_url(new Url('/download/download.php?id='. $file_id .'&com=0'));
		$Template->put_all(array(
			'COMMENTS' => CommentsService::display($comments_topic)->render()
		));
	}
	
	// Gestion des liens morts
	if ($deadlink > 0)
	{
		$nbr_alert = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "events WHERE id_in_module = '" . $file_id . "' AND module='download' AND current_status = 0", __LINE__, __FILE__);
		if (empty($nbr_alert)) 
		{
			$contribution = new Contribution();
			$contribution->set_id_in_module($file_id);
			$contribution->set_entitled(sprintf($DOWNLOAD_LANG['contribution_deadlink'], stripslashes($download_info['title'])));
			$contribution->set_fixing_url('/download/management.php?edit=' . $file_id . '');
			$contribution->set_description(stripslashes($DOWNLOAD_LANG['contribution_deadlink_explain']));
			$contribution->set_poster_id($User->get_attribute('user_id'));
			$contribution->set_module('download');
			$contribution->set_type('alert');
			$contribution->set_auth(
				Authorizations::capture_and_shift_bit_auth(
					$DOWNLOAD_CATS[$category_id]['auth'],
					DOWNLOAD_WRITE_CAT_AUTH_BIT, 
					DOWNLOAD_CONTRIBUTION_CAT_AUTH_BIT
				)
			);

			if (!$User->check_level(User::MEMBER_LEVEL))
			{
				DispatchManager::redirect(PHPBoostErrors::user_not_authorized());
			}

			ContributionService::save_contribution($contribution);
			AppContext::get_response()->redirect(UserUrlBuilder::contribution_success()->absolute());
		}
		else
		{
			AppContext::get_response()->redirect(UserUrlBuilder::contribution_success()->absolute());
		}
	}
	
	$Template->pparse('download');
}
else
{
	$modulesLoader = AppContext::get_extension_provider_service();
	$module = $modulesLoader->get_provider('download');
	if ($module->has_extension_point(HomePageExtensionPoint::EXTENSION_POINT))
	{
		echo $module->get_extension_point(HomePageExtensionPoint::EXTENSION_POINT)->get_home_page()->get_view()->display();
	}
}
	
require_once('../kernel/footer.php'); 

?>
