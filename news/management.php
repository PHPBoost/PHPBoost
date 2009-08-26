<?php
/*##################################################
 *                               management.php
 *                            -------------------
 *   begin                :  August 13, 2008
 *   copyright          : (C) 2008 Viarre Régis
 *   email                : regis.viarre@phpboost.com
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

require_once('../kernel/begin.php');
require_once('news_begin.php');
require_once('news_cats.class.php');
$news_categories = new NewsCats();

import('util/date');
$now = new Date(DATE_NOW, TIMEZONE_AUTO);
import('util/mini_calendar');

$new = retrieve(GET, 'new', 0);
$edit = retrieve(GET, 'edit', 0);
$delete = retrieve(GET, 'del', 0);

if ($delete > 0)
{
	$Session->csrf_get_protect();
	$news = $Sql->query_array(DB_TABLE_NEWS, '*', "WHERE id = '" . $delete . "'", __LINE__, __FILE__);
	
	if (empty($news['id']))
	{
		$Errorh->handler('e_unexist_news', E_USER_REDIRECT);
	}
	elseif (!$User->check_auth($NEWS_CAT[$news['idcat']]['auth'], AUTH_NEWS_MODERATE))
	{
		$Errorh->handler('e_auth', E_USER_REDIRECT);
	}
	
	$Sql->query_inject("DELETE FROM " . DB_TABLE_NEWS . " WHERE id = '" . $delete . "'", __LINE__, __FILE__);
	
	if ($news['nbr_com'] > 0)
	{
		import('content/comments');
		$Comments = new Comments('news', $delete, url('news.php?id=' . $delete . '&amp;com=%s', 'news-' . $news['idcat'] . '-' . $delete . '.php?com=%s'));
		$Comments->delete_all($delete_news);
	}
	
	// Feeds Regeneration
    import('content/syndication/feed');
    Feed::clear_cache('news');
    
	redirect('news' . url('.php?cat=' . $news['idcat'], '-' . $news['idcat'] . '+' . url_encode_rewrite($NEWS_CAT[$news['idcat']]['name']) . '.php'));
}
elseif (!empty($_POST['submit']))
{
	$start = MiniCalendar::retrieve_date('start');
	$end = MiniCalendar::retrieve_date('end');
	$release = MiniCalendar::retrieve_date('release');

	$news = array(
		'id' => retrieve(POST, 'id', 0, TINTEGER),
		'idcat' => retrieve(POST, 'idcat', 0, TINTEGER),
		'title' => retrieve(POST, 'title', '', TSTRING),
		'desc' => retrieve(POST, 'contents', '', TSTRING_PARSE),
		'extend_desc' => retrieve(POST, 'extend_contents', '', TSTRING_PARSE),
		'counterpart' => retrieve(POST, 'counterpart', '', TSTRING_PARSE),
		'visible' => retrieve(POST, 'visible', 0, TINTEGER),
		'start' => $start->get_timestamp(),
		'start_hour' => retrieve(POST, 'start_hour', 0, TINTEGER),
		'start_min' => retrieve(POST, 'start_min', 0, TINTEGER),
		'end' => $end->get_timestamp(),
		'end_hour' => retrieve(POST, 'end_hour', 0, TINTEGER),
		'end_min' => retrieve(POST, 'end_min', 0, TINTEGER),
		'release' => $release->get_timestamp(),
		'release_hour' => retrieve(POST, 'release_hour', 0, TINTEGER),
		'release_min' => retrieve(POST, 'release_min', 0, TINTEGER),
		'img' => retrieve(POST, 'img', '', TSTRING),
		'alt' => retrieve(POST, 'alt', '', TSTRING)
	);

	if ($news['id'] == 0 && ($User->check_auth($NEWS_CAT[$news['idcat']]['auth'], AUTH_NEWS_WRITE) || $User->check_auth($NEWS_CAT[$news['idcat']]['auth'], AUTH_NEWS_CONTRIBUTE)) || $news['id'] > 0 && $User->check_auth($NEWS_CAT[$news['idcat']]['auth'], AUTH_NEWS_MODERATE))
	{
		// Errors.
		if (empty($news['title']))
		{
			$Errorh->handler('e_require_title', E_USER_REDIRECT);
		}
		elseif (empty($news['idcat']) && $news['idcat'] == 0)
		{
			$Errorh->handler('e_require_cat', E_USER_REDIRECT);
		}
		elseif (empty($news['desc']))
		{
			$Errorh->handler('e_require_desc', E_USER_REDIRECT);
		}
		else
		{
			// $start & $end.
			if ($news['visible'] == 2)
			{
				// Start.
				$news['start'] += ($news['start_hour'] * 60 + $news['start_min']) * 60;
				if ($news['start'] <= $now->get_timestamp())
				{
					$news['start'] = 0;
				}

				// End.
				$news['end'] += ($news['end_hour'] * 60 + $news['end_min']) * 60;
				if ($news['end'] <= $now->get_timestamp())
				{
					$news['end'] = 0;
				}

				$news['visible'] = 1;
			}
			else
			{
				$news['start'] = $news['end'] = 0;
			}

			// Release.
			$news['release'] += ($news['release_hour'] * 60 + $news['release_min']) * 60;
			if ($news['release'] == 0)
			{
				$news['release'] = $now->get_timestamp();
			}

			// Image.
			$img = new Url($news['img']);
			
			if ($news['id'] > 0)
			{
				$Sql->query_inject("UPDATE " . DB_TABLE_NEWS . " SET idcat = '" . $news['idcat'] . "', title = '" . $news['title'] . "', contents = '" . $news['desc'] . "', extend_contents = '" . $news['extend_desc'] . "', img = '" . $img->relative() . "', alt = '" . $news['alt'] . "', visible = '" . $news['visible'] . "', start = '" .  $news['start'] . "', end = '" . $news['end'] . "', timestamp = '" . $news['release'] . "'
				WHERE id = '" . $news['id'] . "'", __LINE__, __FILE__);
			}
			else
			{
				$auth_contrib = !$User->check_auth($NEWS_CAT[$news['idcat']]['auth'], AUTH_NEWS_WRITE) && $User->check_auth($NEWS_CAT[$news['idcat']]['auth'], AUTH_NEWS_CONTRIBUTE);

				$Sql->query_inject("INSERT INTO " . DB_TABLE_NEWS . " (idcat, title, contents, extend_contents, timestamp, visible, start, end, user_id, img, alt, nbr_com)
				VALUES('" . $news['idcat'] . "', '" . $news['title'] . "', '" . $news['desc'] . "', '" . $news['extend_desc'] . "', '" . $news['release'] . "', '" . $news['visible'] . "', '" . $news['start'] . "', '" . $news['end'] . "', '" . $User->get_attribute('user_id') . "', '" . $img->relative() . "', '" . $news['alt'] . "', '0')", __LINE__, __FILE__);

				$news['id'] = $Sql->insert_id("SELECT MAX(id) FROM " . DB_TABLE_NEWS);

				//If the poster couldn't write, it's a contribution and we put it in the contribution panel, it must be approved
				if ($auth_contrib)
				{
					//Importing the contribution classes
					import('events/contribution');
					import('events/contribution_service');
					$news_contribution = new Contribution();

					//The id of the file in the module. It's useful when the module wants to search a contribution (we will need it in the file edition)
					$news_contribution->set_id_in_module($news['id']);
					//The description of the contribution (the counterpart) to explain why did the contributor contributed
					$news_contribution->set_description(stripslashes($news['counterpart']));
					//The entitled of the contribution
					$news_contribution->set_entitled(sprintf($NEWS_LANG['contribution_entitled'], $news['title']));
					//The URL where a validator can treat the contribution (in the file edition panel)
					$news_contribution->set_fixing_url('/news/management.php?edit=' . $news['id']);
					//Who is the contributor?
					$news_contribution->set_poster_id($User->get_attribute('user_id'));
					//The module
					$news_contribution->set_module('news');
					//Assignation des autorisations d'écriture / Writing authorization assignation
					$news_contribution->set_auth(
						//On déplace le bit sur l'autorisation obtenue pour le mettre sur celui sur lequel travaille les contributions, à savoir CONTRIBUTION_AUTH_BIT
						//We shift the authorization bit to the one with which the contribution class works, CONTRIBUTION_AUTH_BIT
						Authorizations::capture_and_shift_bit_auth(
							//On fusionne toutes les autorisations pour obtenir l'autorisation d'écriture dans la catégorie sélectionnée :
							//C'est la fusion entre l'autorisation de la racine et de l'ensemble de la branche des catégories
							//We merge the whole authorizations of the branch constituted by the selected category
							Authorizations::merge_auth(
								$NEWS_CONFIG['global_auth'],
								//Autorisation de l'ensemble de la branche des catégories jusqu'à la catégorie demandée
								$news_categories->compute_heritated_auth($news['idcat'], AUTH_NEWS_MODERATE, AUTH_CHILD_PRIORITY),
								AUTH_NEWS_MODERATE, AUTH_CHILD_PRIORITY
							),
							AUTH_NEWS_MODERATE, CONTRIBUTION_AUTH_BIT
						)
					);

					//Sending the contribution to the kernel. It will place it in the contribution panel to be approved
					ContributionService::save_contribution($news_contribution);

					//Redirection to the contribution confirmation page
					redirect(HOST . DIR . '/news/contribution.php');
				}
			}

			// Feeds Regeneration
			import('content/syndication/feed');
			Feed::clear_cache('news');

			if ($news['visible'] && $news['start'] == 0)
			{
				redirect('news' . url('.php?id=' . $news['id'], '-' . $news['idcat'] . '-' . $news['id'] . '+' . url_encode_rewrite($news['title']) . '.php'));
			}
			else
			{
				redirect(url('news.php'));
			}
		}
	}
	else
	{
		$Errorh->handler('e_auth', E_USER_REDIRECT);
	}
}
else
{
	$tpl = new Template('news/management.tpl');
	
	if ($edit > 0)
	{
		$news = $Sql->query_array(DB_TABLE_NEWS, '*', "WHERE id = '" . $edit . "'", __LINE__, __FILE__);
		
		if (empty($news['id']))
		{
			$Errorh->handler('e_unexist_news', E_USER_REDIRECT);
		}
		elseif (!$User->check_auth($NEWS_CAT[$news['idcat']]['auth'], AUTH_NEWS_MODERATE))
		{
			$Errorh->handler('e_auth', E_USER_REDIRECT);
		}
		else
		{
			define('TITLE', $NEWS_LANG['edit_news'] . ' : ' . addslashes($news['title']));
			$news_categories->bread_crumb($news['idcat']);
			$Bread_crumb->add($news['title'], 'news' . url('.php?id=' . $news['id'], '-' . $news['idcat'] . '-' . $news['id'] . '+' . url_encode_rewrite($news['title']) . '.php'));
			$Bread_crumb->add($NEWS_LANG['edit_news'], url('management.php?edit=' . $news['id']));
			
			// Calendrier.
			$start_calendar = new MiniCalendar('start');
			$start = new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, ($news['start'] > 0 ? $news['start'] : $now->get_timestamp()));
			$start_calendar->set_date($start);
			$end_calendar = new MiniCalendar('end');
			$end = new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, ($news['end'] > 0 ? $news['end'] : $now->get_timestamp()));
			$end_calendar->set_date($end);
			$end_calendar->set_style('margin-left:150px;');
			$release_calendar = new MiniCalendar('release');
			$release = new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, ($news['timestamp'] > 0 ? $news['timestamp'] : $now->get_timestamp()));
			$release_calendar->set_date($release);

			$tpl->assign_vars(array(
				'C_CONTRIBUTION' => false,
				'JS_CONTRIBUTION' => 'false',
				'JS_INSTANCE_RELEASE' => $release_calendar->num_instance,
				'TITLE' => $news['title'],
				'CONTENTS' => unparse($news['contents']),
				'EXTEND_CONTENTS' => unparse($news['extend_contents']),
				'VISIBLE_WAITING' => $news['visible'] && (!empty($news['start']) || !empty($news['end'])),
				'VISIBLE_ENABLED' => $news['visible'],
				'VISIBLE_UNAPROB' => !$news['visible'],
				'START_CALENDAR' => $start_calendar->display(),
				'START_HOUR' => !empty($news['start']) ? $start->get_hours() : '',
				'START_MIN' => !empty($news['start']) ? $start->get_minutes() : '',
				'END_CALENDAR' => $end_calendar->display(),
				'END_HOUR' => !empty($news['end']) ? $end->get_hours() : '',
				'END_MIN' => !empty($news['end']) ? $end->get_minutes() : '',
				'RELEASE_CALENDAR' => $release_calendar->display(),
				'RELEASE_HOUR' => !empty($news['timestamp']) ? $release->get_hours() : '',
				'RELEASE_MIN' => !empty($news['timestamp']) ? $release->get_minutes() : '',
				'IMG_PREVIEW' => second_parse_url($news['img']),
				'IMG' => $news['img'],
				'ALT' => $news['alt'],
				'IDNEWS' => $news['id'],
				'USER_ID' => $news['user_id']
			));

			$news_categories->build_select_form($news['idcat'], 'idcat', 'idcat', 0, AUTH_NEWS_READ, $NEWS_CONFIG['global_auth'], IGNORE_AND_CONTINUE_BROWSING_IF_A_CATEGORY_DOES_NOT_MATCH, $tpl);
		}
	}
	else
	{
		if (!$User->check_auth($NEWS_CONFIG['global_auth'], AUTH_NEWS_CONTRIBUTE) && !$User->check_auth($NEWS_CONFIG['global_auth'], AUTH_NEWS_WRITE))
		{
			$Errorh->handler('e_auth', E_USER_REDIRECT);
		}
		else
		{
			$auth_contrib = !$User->check_auth($NEWS_CONFIG['global_auth'], AUTH_NEWS_WRITE) && $User->check_auth($NEWS_CONFIG['global_auth'], AUTH_NEWS_CONTRIBUTE);

			define('TITLE', $NEWS_LANG['add_news']);

			// Calendrier.
			$now = new Date(DATE_NOW, TIMEZONE_AUTO);
			$start_calendar = new MiniCalendar('start');
			$start_calendar->set_date(new Date(DATE_NOW, TIMEZONE_AUTO));
			$end_calendar = new MiniCalendar('end');
			$end_calendar->set_date(new Date(DATE_NOW, TIMEZONE_AUTO));
			$end_calendar->set_style('margin-left:150px;');
			$release_calendar = new MiniCalendar('release');
			$release_calendar->set_date(new Date(DATE_NOW, TIMEZONE_AUTO));

			$tpl->assign_vars(array(
				'C_CONTRIBUTION' => $auth_contrib,
				'JS_CONTRIBUTION' => $auth_contrib ? 'true' : 'false',
				'JS_INSTANCE_RELEASE' => $release_calendar->num_instance,
				'TITLE' => '',
				'CONTENTS' => '',
				'EXTEND_CONTENTS' => '',
				'VISIBLE_WAITING' => 0,
				'VISIBLE_ENABLED' => 1,
				'VISIBLE_UNAPROB' => 0,
				'START_CALENDAR' => $start_calendar->display(),
				'START_HOUR' => '',
				'START_MIN' => '',
				'END_CALENDAR' => $end_calendar->display(),
				'END_HOUR' => '',
				'END_MIN' => '',
				'RELEASE_CALENDAR' => $release_calendar->display(),
				'RELEASE_HOUR' => $now->get_hours(),
				'RELEASE_MIN' => $now->get_minutes(),
				'IMG' => '',
				'ALT' => '',
				'IDNEWS' => '0',
				'USER_ID' => $User->get_attribute('user_id')
			));
			
			$news_categories->build_select_form(0, 'idcat', 'idcat', 0, AUTH_NEWS_READ, $NEWS_CONFIG['global_auth'], IGNORE_AND_CONTINUE_BROWSING_IF_A_CATEGORY_DOES_NOT_MATCH, $tpl);
		}
	}
	require_once('../kernel/header.php');

	$tpl->assign_vars(array(
		'L_ADD_NEWS' => $NEWS_LANG['add_news'],
		'L_REQUIRE' => $LANG['require'],
		'L_TITLE_NEWS' => $NEWS_LANG['title_news'],
		'L_CATEGORY' => $NEWS_LANG['cat_news'],
		'L_DESC' => $NEWS_LANG['desc_news'],
		'L_DESC_EXTEND' => $NEWS_LANG['desc_extend_news'],
		'KERNEL_EDITOR' => display_editor(),
		'KERNEL_EDITOR_EXTEND' => display_editor('extend_contents'),
		'CONTRIBUTION_COUNTERPART_EDITOR' => display_editor('counterpart'),
		'L_TO_DATE' => $LANG['to_date'],
		'L_FROM_DATE' => $LANG['from_date'],
		'L_RELEASE_DATE' => $NEWS_LANG['release_date'],
		'L_NEWS_DATE' => $NEWS_LANG['news_date'],
		'L_UNIT_HOUR' => strtolower($LANG['unit_hour']),
		'L_IMMEDIATE' => $LANG['now'],
		'L_UNAPROB' => $LANG['unaprob'],
		'L_IMG_MANAGEMENT' => $NEWS_LANG['img_management'],
		'L_PREVIEW_IMG' => $NEWS_LANG['preview_image'],
		'L_PREVIEW_IMG_EXPLAIN' => $NEWS_LANG['preview_image_explain'],
		'L_IMG_LINK' => $NEWS_LANG['img_link'],
		'L_IMG_DESC' => $NEWS_LANG['img_desc'],
		'L_BB_UPLOAD' => $LANG['bb_upload'],
		'L_CONTRIBUTION_LEGEND' => $LANG['contribution'],
		'L_NOTICE_CONTRIBUTION' => $NEWS_LANG['notice_contribution'],
		'L_CONTRIBUTION_COUNTERPART' => $NEWS_LANG['contribution_counterpart'],
		'L_CONTRIBUTION_COUNTERPART_EXPLAIN' => $NEWS_LANG['contribution_counterpart_explain'],
		'L_REQUIRE_TITLE' => $LANG['require_title'],
		'L_REQUIRE_CAT' => $NEWS_LANG['require_cat'],
		'L_REQUIRE_TEXT' => $LANG['require_text'],
		'L_SUBMIT' => $LANG['submit'],
		'L_PREVIEW' => $LANG['preview'],
		'L_RESET' => $LANG['reset']
	));

	$tpl->parse();
}

require_once('../kernel/footer.php');

?>
