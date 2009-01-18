<?php
/*##################################################
 *              	 media_action.php
 *              	-------------------
 *  begin        	: October 20, 2008
 *  copyright    	: (C) 2007 Geoffrey ROGUELON
 *  email        	: liaght@gmail.com
 *
 *
 *
###################################################
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 *
###################################################*/

require_once('../kernel/begin.php');
require_once('media_begin.php');
require_once('media_cats.class.php');
$media_categories = new MediaCats();

$Template->set_filenames(array('media_action' => 'media/media_action.tpl'));

$aprob = retrieve(GET, 'aprob', 0, TINTEGER);
$visible = retrieve(GET, 'visible', 0, TINTEGER);
$unvisible = retrieve(GET, 'unvisible', 0, TINTEGER);
$add = retrieve(GET, 'add', 0, TINTEGER);
$edit = retrieve(GET, 'edit', 0, TINTEGER);
$delete = retrieve(GET, 'del', 0, TINTEGER);

// Modification du statut du fichier.
if ($visible > 0 XOR $unvisible > 0 XOR $aprob > 0)
{
	$id = ($visible > 0 ? $visible : ($unvisible > 0 ? $unvisible : $aprob));
	$media = $Sql->query_array(PREFIX . 'media', '*', "WHERE id = '" . $id . "'", __LINE__, __FILE__);

	// Gestion des erreurs.
	if (empty($media))
	{
		$Errorh->handler('e_unexist_media', E_USER_REDIRECT);
		exit;
	}
	elseif (!$User->check_level(MODO_LEVEL))
	{
		$Errorh->handler('e_auth', E_USER_REDIRECT);
		exit;
	}

	$id_parent = $MEDIA_CATS[$media['idcat']]['id_parent'];
	$Bread_crumb->add($MEDIA_CATS[$media['idcat']]['name'], url('media.php?cat=' . $media['idcat'], 'media-' . $media['idcat'] . '+' . url_encode_rewrite($MEDIA_CATS[$media['idcat']]['name']) . '.php'));

	while ($id_parent >= 0)
	{
		$Bread_crumb->add($MEDIA_CATS[$id_parent]['name'], url('media.php?cat=' . $id_parent, 'media-' . $id_parent . '+' . url_encode_rewrite($MEDIA_CATS[$id_parent]['name']) . '.php'));
		$id_parent = $MEDIA_CATS[$id_parent]['id_parent'];
	}

	$Bread_crumb->reverse();
	$Bread_crumb->add($media['name'], url('media.php?id=' . $media['id'], 'media-' . $media['id'] . '+' . url_encode_rewrite($media['name']) . '.php'));

	// Détermination du bit, du nom de la page et de la Breadcrum.
	if ($visible > 0)
	{
		$bit = ($media['infos'] & ~MEDIA_STATUS_UNVISIBLE);
		define('TITLE', $MEDIA_LANG['media_moderation']);
		$Bread_crumb->add($MEDIA_LANG['show_media'], url('media_action.php?visible=' . $media['id']));
	}
	elseif ($unvisible > 0)
	{
		$bit = ($media['infos'] | MEDIA_STATUS_UNVISIBLE);
		define('TITLE', $MEDIA_LANG['media_moderation']);
		$Bread_crumb->add($MEDIA_LANG['hide_media'], url('media_action.php?unvisible=' . $media['id']));
	}
	else
	{
		$bit = ($media['infos'] | MEDIA_STATUS_APROBED);
		define('TITLE', $MEDIA_LANG['media_aprob']);
		$Bread_crumb->add($MEDIA_LANG['aprob_media'], url('media_action.php?aprob=' . $media['id']));
	}

	$Sql->query_inject("UPDATE ".PREFIX."media SET infos = '" . $bit . "' WHERE id = '" . $id . "'", __LINE__, __FILE__);

	require_once('../kernel/header.php');

	$media_categories->recount_media_per_cat($media['idcat']);

	redirect_confirm('media' . url('.php?cat=' . $media['idcat'], '-' . $media['idcat'] . '.php'), $MEDIA_LANG['action_success'], TIME_REDIRECT);
}
// Suppression d'un fichier.
elseif ($delete > 0)
{
	$media = $Sql->query_array(PREFIX . 'media', '*', "WHERE id = '" . $delete. "'", __LINE__, __FILE__);

	if (empty($media))
	{
		$Errorh->handler('e_unexist_media', E_USER_REDIRECT);
		exit;
	}
	elseif (!$User->check_level(MODO_LEVEL))
	{
		$Errorh->handler('e_auth', E_USER_REDIRECT);
		exit;
	}

	$Sql->query_inject("DELETE FROM ".PREFIX."media WHERE id = '" . $delete . "'", __LINE__, __FILE__);

	//Deleting comments if the file has
	if ($media['nbr_com'] > 0)
	{
		import('content/comments');
		$Comments = new Comments('media', $delete, url('media.php?id=' . $delete . '&amp;com=%s', 'media-' . $delete . '.php?com=%s'));
		$Comments->delete_all($delete);
	}

	// Feeds Regeneration
	import('content/syndication/feed');
	Feed::clear_cache('media');

	$media_categories->recount_media_per_cat($media['idcat']);

	$id_parent = $MEDIA_CATS[$media['idcat']]['id_parent'];
	$Bread_crumb->add($MEDIA_CATS[$media['idcat']]['name'], url('media.php?cat=' . $media['idcat'], 'media-' . $media['idcat'] . '+' . url_encode_rewrite($MEDIA_CATS[$media['idcat']]['name']) . '.php'));

	while ($id_parent >= 0)
	{
		$Bread_crumb->add($MEDIA_CATS[$id_parent]['name'], url('media.php?cat=' . $id_parent, 'media-' . $id_parent . '+' . url_encode_rewrite($MEDIA_CATS[$id_parent]['name']) . '.php'));
		$id_parent = $MEDIA_CATS[$id_parent]['id_parent'];
	}

	$Bread_crumb->reverse();
	$Bread_crumb->add($MEDIA_LANG['delete_media'], url('media.php?cat=' . $media['idcat']));

	define('TITLE', $MEDIA_LANG['delete_media']);
	require_once('../kernel/header.php');

	redirect_confirm('media' . url('.php?cat=' . $media['idcat'], '-' . $media['idcat'] . '.php'), $MEDIA_LANG['deleted_success'], TIME_REDIRECT);
}
// Formulaire d'ajout ou d'édition.
elseif ($add >= 0 && empty($_POST['submit']) || $edit > 0)
{
	$Template->assign_vars(array(
		'C_ADD_MEDIA' => true,
		'U_TARGET' => url('media_action.php'),
		'L_TITLE' => $MEDIA_LANG['media_name'],
		'L_CATEGORY' => $MEDIA_LANG['media_category'],
		'L_WIDTH' => $MEDIA_LANG['media_width'],
		'L_HEIGHT' => $MEDIA_LANG['media_height'],
		'L_U_MEDIA' => $MEDIA_LANG['media_url'],
		'L_CONTENTS' => $MEDIA_LANG['media_description'],
		'KERNEL_EDITOR' => display_editor(),
		'L_APPROVED' => $MEDIA_LANG['media_approved'],
		'L_CONTRIBUTION_LEGEND' => $LANG['contribution'],
		'L_NOTICE_CONTRIBUTION' => $MEDIA_LANG['notice_contribution'],
		'L_CONTRIBUTION_COUNTERPART' => $MEDIA_LANG['contribution_counterpart'],
		'L_CONTRIBUTION_COUNTERPART_EXPLAIN' => $MEDIA_LANG['contribution_counterpart_explain'],
		'L_RESET' => $LANG['reset'],
		'L_PREVIEW' => $LANG['preview'],
		'L_SUBMIT' => $edit > 0 ? $LANG['update'] : $LANG['submit']
	));

	// Édition.
	if ($edit > 0 && ($media = $Sql->query_array(PREFIX . 'media', '*', "WHERE id = '" . $edit. "'", __LINE__, __FILE__)) && !empty($media) && $User->check_level(MODO_LEVEL))
	{
		$Template->assign_vars(array(
			'L_PAGE_TITLE' => $MEDIA_LANG['edit_media'],
			'C_CONTRIBUTION' => 0,
			'IDEDIT' => $media['id'],
			'NAME' => $media['name'],
			'CATEGORIES_TREE' => $media_categories->build_select_form($media['idcat'], 'idcat', 'idcat', 0, MEDIA_AUTH_WRITE, $MEDIA_CATS[0]['auth'], IGNORE_AND_CONTINUE_BROWSING_IF_A_CATEGORY_DOES_NOT_MATCH),
			'WIDTH' => $media['width'],
			'HEIGHT' => $media['height'],
			'U_MEDIA' => $media['url'],
			'DESCRIPTION' => unparse($media['contents']),
			'APPROVED' => ($media['infos'] & MEDIA_STATUS_APROBED) !== 0 ? 'checked="checked"' : '',
			'C_APROB' => ($media['infos'] & MEDIA_STATUS_APROBED) === 0
		));
	}
	// Ajout.
	elseif (($write = $User->check_auth($MEDIA_CATS[$add]['auth'], MEDIA_AUTH_WRITE)) || $User->check_auth($MEDIA_CATS[$add]['auth'], MEDIA_AUTH_CONTRIBUTION))
	{
		$Template->assign_vars(array(
			'L_PAGE_TITLE' => $write ? $MEDIA_LANG['add_media'] : $MEDIA_LANG['contribute_media'],
			'C_CONTRIBUTION' => !$write,
			'CONTRIBUTION_COUNTERPART_EDITOR' => display_editor('counterpart'),
			'IDEDIT' => 0,
			'NAME' => '',
			'CATEGORIES_TREE' => $media_categories->build_select_form(0, 'idcat', 'idcat', 0, ($write ? MEDIA_AUTH_WRITE : MEDIA_AUTH_CONTRIBUTION), $MEDIA_CATS[0]['auth'], IGNORE_AND_CONTINUE_BROWSING_IF_A_CATEGORY_DOES_NOT_MATCH),
			'WIDTH' => '',
			'HEIGHT' => '',
			'U_MEDIA' => 'http://',
			'DESCRIPTION' => '',
			'APPROVED' => 'checked="checked"',
			'C_APROB' => false
		));
	}
	else
	{
		$Errorh->handler('e_auth', E_USER_REDIRECT);
		exit;
	}

	$idcat = !empty($media) ? $media['idcat'] : $add;
	$id_parent = $MEDIA_CATS[$idcat]['id_parent'];
	$Bread_crumb->add($MEDIA_CATS[$idcat]['name'], url('media.php?cat=' . $idcat, 'media-' . $idcat . '+' . url_encode_rewrite($MEDIA_CATS[$idcat]['name']) . '.php'));

	while ($id_parent >= 0)
	{
		$Bread_crumb->add($MEDIA_CATS[$id_parent]['name'], url('media.php?cat=' . $id_parent, 'media-' . $id_parent . '+' . url_encode_rewrite($MEDIA_CATS[$id_parent]['name']) . '.php'));
		$id_parent = $MEDIA_CATS[$id_parent]['id_parent'];
	}

	$Bread_crumb->reverse();

	if (!empty($media))
	{
		$Bread_crumb->add($media['name'], url('media.php?id=' . $media['id'], 'media-' . $media['id'] . '+' . url_encode_rewrite($media['name']) . '.php'));
		$Bread_crumb->add($MEDIA_LANG['edit_media'], url('media_action.php?edit=' . $media['id']));
		define('TITLE', $MEDIA_LANG['edit_media']);
	}
	else
	{
		$Bread_crumb->add($MEDIA_LANG['add_media'], url('media_action.php'));
		define('TITLE', $MEDIA_LANG['add_media']);
	}

	require_once('../kernel/header.php');
}
// Traitement du formulaire.
elseif (!empty($_POST['submit']))
{
	$media = array(
		'idedit' => retrieve(POST, 'idedit', 0, TINTEGER),
		'name' => retrieve(POST, 'name', '', TSTRING),
		'idcat' => retrieve(POST, 'idcat', 0, TINTEGER),
		'width' => min(retrieve(POST, 'width', $MEDIA_CONFIG['width'], TINTEGER), $MEDIA_CONFIG['width']),
		'height' => min(retrieve(POST, 'height', $MEDIA_CONFIG['height'], TINTEGER), $MEDIA_CONFIG['height']),
		'url' => retrieve(POST, 'u_media', '', TSTRING),
		'contents' => retrieve(POST, 'contents', '', TSTRING_UNCHANGE),
		'approved' => retrieve(POST, 'approved', 0, TBOOL),
		'contrib' => retrieve(POST, 'contrib', 0, TBOOL),
		'counterpart' => retrieve(POST, 'counterpart', '', TSTRING_PARSE)
	);

	$auth_cat = !empty($MEDIA_CATS[$media['idcat']]['auth']) ? $MEDIA_CATS[$media['idcat']]['auth'] : $MEDIA_CATS[0]['auth'];
	$media['idcat'] = !empty($MEDIA_CATS[$media['idcat']]) ? $media['idcat'] : 0;

	$id_parent = $MEDIA_CATS[$media['idcat']]['id_parent'];
	$Bread_crumb->add($MEDIA_CATS[$media['idcat']]['name'], url('media.php?cat=' . $media['idcat'], 'media-' . $media['idcat'] . '+' . url_encode_rewrite($MEDIA_CATS[$media['idcat']]['name']) . '.php'));

	while ($id_parent >= 0)
	{
		$Bread_crumb->add($MEDIA_CATS[$id_parent]['name'], url('media.php?cat=' . $id_parent, 'media-' . $id_parent . '+' . url_encode_rewrite($MEDIA_CATS[$id_parent]['name']) . '.php'));
		$id_parent = $MEDIA_CATS[$id_parent]['id_parent'];
	}

	$Bread_crumb->reverse();

	if ($media['idedit'])
	{
		$Bread_crumb->add($media['name'], url('media.php?id=' . $media['idedit'], 'media-' . $media['idedit'] . '+' . url_encode_rewrite($media['name']) . '.php'));
		$Bread_crumb->add($MEDIA_LANG['edit_media'], url('media_action.php?edit=' . $media['idedit']));
		define('TITLE', $MEDIA_LANG['edit_media']);
	}
	else
	{
		$Bread_crumb->add($MEDIA_LANG['add_media'], url('media_action.php?add=' . $media['idcat']));
		define('TITLE', $MEDIA_LANG['add_media']);
	}

	require_once('../kernel/header.php');
	
	if (!empty($media['url']))
	{			
		if ($MEDIA_CATS[$media['idcat']]['mime_type'] == MEDIA_TYPE_MUSIC)
		{
			$mime_type = $mime_type['audio'];
		}
		elseif ($MEDIA_CATS[$media['idcat']]['mime_type'] == MEDIA_TYPE_VIDEO)
		{
			$mime_type = $mime_type['video'];
		}
		else
		{
			$mime_type = array_merge($mime_type['audio'], $mime_type['video']);
		}

		if (($pathinfo = pathinfo($media['url'])) && !empty($pathinfo['extension']))
		{
			if (array_key_exists($pathinfo['extension'], $mime_type))
			{
				$media['mime_type'] = $mime_type[$pathinfo['extension']];
			}
			else
			{
				$Errorh->handler('e_mime_disable_media', E_USER_REDIRECT);
				exit;
			}
		}
		elseif (function_exists('get_headers') && ($headers = get_headers($media['url'], 1)) && !empty($headers['Content-Type']))
		{
			if (!is_array($headers['Content-Type']) && in_array($headers['Content-Type'], $mime_type))
			{
				$media['mime_type'] = $headers['Content-Type'];
			}
			elseif (is_array($headers['Content-Type']))
			{
				foreach ($headers['Content-Type'] as $type)
				{
					if (in_array($type, $mime_type))
					{
						$media['mime_type'] = $type;
					}
				}
				
				if (empty($media['mime_type']))
				{
					$Errorh->handler('e_mime_disable_media', E_USER_REDIRECT);
					exit;
				}
			}
			else
			{
				$Errorh->handler('e_mime_disable_media', E_USER_REDIRECT);
				exit;
			}
		}
		else
		{
			$Errorh->handler('media_unknow_mime', E_USER_REDIRECT);
			exit;
		}
	}
	else
	{
		$Errorh->handler('media_empty_link', E_USER_REDIRECT);
		exit;
	}

	// Édition
	if ($media['idedit'] && $User->check_level(MODO_LEVEL))
	{
		$Sql->query_inject("UPDATE ".PREFIX."media SET idcat = '" . $media['idcat'] . "', name = '" . $media['name'] . "', url='" . $media['url'] . "', contents = '" . strparse($media['contents']) . "', infos = '" . ($User->check_auth($auth_cat, MEDIA_AUTH_WRITE) ? MEDIA_STATUS_APROBED : 0) . "', width = '" . $media['width'] . "', height = '" . $media['height'] . "' WHERE id = '" . $media['idedit'] . "'", __LINE__, __FILE__);

		$media_categories->recount_media_per_cat($media['idcat']);

		if ($media['approved'])
		{
			import('events/contribution');
			import('events/contribution_service');

			$corresponding_contributions = ContributionService::find_by_criteria('media', $media['idedit']);

			if (count($corresponding_contributions) > 0)
			{
				$media_contribution = $corresponding_contributions[0];
				$media_contribution->set_status(CONTRIBUTION_STATUS_PROCESSED);

				ContributionService::save_contribution($media_contribution);
			}
		}

		// Feeds Regeneration
		import('content/syndication/feed');
		Feed::clear_cache('media');

		redirect_confirm('media' . url('.php?id=' . $media['idedit']), $MEDIA_LANG['edit_success'], TIME_REDIRECT);
	}
	// Ajout
	elseif (!$media['idedit'] && (($auth_write = $User->check_auth($auth_cat, MEDIA_AUTH_WRITE)) || $User->check_auth($auth_cat, MEDIA_AUTH_CONTRIBUTION)))
	{
		$Sql->query_inject("INSERT INTO ".PREFIX."media (idcat, iduser, timestamp, name, contents, url, mime_type, infos, width, height) VALUES ('" . $media['idcat'] . "', '" . $User->Get_attribute('user_id') . "', '" . time() . "', '" . $media['name'] . "', '" . strparse($media['contents']) . "', '" . $media['url'] . "', '" . $media['mime_type'] . "', " . "'" . ($User->check_auth($auth_cat, MEDIA_AUTH_WRITE) ? MEDIA_STATUS_APROBED : 0) . "', '" . $media['width'] . "', '" . $media['height'] . "')", __LINE__, __FILE__);

		$new_id_media = $Sql->insert_id("SELECT MAX(id) FROM ".PREFIX."media");
		$media_categories->recount_media_per_cat($media['idcat']);
		// Feeds Regeneration
		import('content/syndication/feed');
		Feed::clear_cache('media');

		if (!$auth_write)
		{
			import('events/contribution');
			import('events/contribution_service');

			$media_contribution = new Contribution();
			$media_contribution->set_id_in_module($new_id_media);
			$media_contribution->set_description(stripslashes($media['counterpart']));
			$media_contribution->set_entitled(sprintf($MEDIA_LANG['contribution_entitled'], $media['name']));
			$media_contribution->set_fixing_url('/media/media_action.php?edit=' . $new_id_media);
			$media_contribution->set_poster_id($User->get_attribute('user_id'));
			$media_contribution->set_module('media');
			$media_contribution->set_auth(Authorizations::capture_and_shift_bit_auth(Authorizations::merge_auth($MEDIA_CATS[0]['auth'], $media_categories->compute_heritated_auth($media['idcat'], MEDIA_AUTH_WRITE, AUTH_CHILD_PRIORITY), MEDIA_AUTH_WRITE, AUTH_CHILD_PRIORITY), MEDIA_AUTH_WRITE, CONTRIBUTION_AUTH_BIT));

			ContributionService::save_contribution($media_contribution);

			redirect(HOST . DIR . '/media/contribution.php?cat=' . $media['idcat']);
		}
		else
		{
			redirect_confirm('media' . url('.php?id=' . $new_id_media), $MEDIA_LANG['add_success'], TIME_REDIRECT);
		}
	}
	else
	{
		$Errorh->handler('e_auth', E_USER_REDIRECT);
		exit;
	}
}
else
{
	$Errorh->handler('e_auth', E_USER_REDIRECT);
	exit;
}

$Template->pparse('media_action');

require_once('../kernel/footer.php');

?>