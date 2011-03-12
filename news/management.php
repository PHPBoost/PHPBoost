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

load_module_lang('news'); //Chargement de la langue du module.
$Cache->load('news');

import('util/date');
import('util/mini_calendar');

$edit_news_id = retrieve(GET, 'edit', 0);
$add_news = retrieve(GET, 'new', false);
$preview = retrieve(POST, 'preview', false);
$submit = retrieve(POST, 'submit', false);
$selected_cat = retrieve(GET, 'idcat', 0);
$delete_news = retrieve(GET, 'del', 0);

//Form variables
$news_title = retrieve(POST, 'title', '');
$news_image = retrieve(POST, 'image', '');
$news_contents = retrieve(POST, 'contents', '', TSTRING_UNCHANGE);
$news_short_contents = retrieve(POST, 'short_contents', '', TSTRING_UNCHANGE);
$news_timestamp = retrieve(POST, 'timestamp', 0);
$news_cat_id = retrieve(POST, 'idcat', 0);
$news_visibility = retrieve(POST, 'visibility', 0);
$ignore_release_date = retrieve(POST, 'ignore_release_date', false);

//Instanciations of objects required
$news_creation_date = new Date(DATE_FROM_STRING, TIMEZONE_AUTO, retrieve(POST, 'creation', '', TSTRING_UNCHANGE), $LANG['date_format_short']);

if (!$ignore_release_date)
	$news_release_date = new Date(DATE_FROM_STRING, TIMEZONE_AUTO, retrieve(POST, 'release_date', ''), $LANG['date_format_short'], TSTRING_UNCHANGE);
else
	$news_release_date = new Date(DATE_NOW, TIMEZONE_AUTO);


$begining_date = new Date(DATE_FROM_STRING, TIMEZONE_AUTO, retrieve(POST, 'begining_date', '', TSTRING_UNCHANGE), $LANG['date_format_short']);
$end_date = new Date(DATE_FROM_STRING, TIMEZONE_AUTO, retrieve(POST, 'end_date', '', TSTRING_UNCHANGE), $LANG['date_format_short']);

//Deleting a news
if ($delete_news > 0)
{
	$news_infos = $Sql->query_array(PREFIX . 'news', '*', "WHERE id = '" . $delete_news . "'", __LINE__, __FILE__);
	if (empty($news_infos['title']))
		redirect(HOST. DIR . url('/news/news.php'));
	
	if ($news_categories->check_auth($news_infos['idcat']))
	{
		$Sql->query_inject("DELETE FROM " . PREFIX . "news WHERE id = '" . $delete_news . "'", __LINE__, __FILE__);
		//Deleting comments if the news has
		if ($news_infos['nbr_com'] > 0)
		{
			import('content/comments');
			$Comments = new Comments('news', $delete_news, url('news.php?id=' . $delete_news . '&amp;com=%s', 'news-' . $delete_news . '.php?com=%s'));
			//$Comments->set_arg($news_id);
			$Comments->delete_all($delete_news);
		}
		redirect(HOST. DIR . '/news/' . ($news_infos['idcat'] > 0 ? url('news.php?cat=' . $news_infos['idcat'], 'category-' . $news_infos['idcat'] . '+' . url_encode_rewrite($NEWS_CATS[$news_infos['idcat']]['name']) . '.php') : url('news.php')));
        
        // Feeds Regeneration
        import('content/syndication/feed');
        Feed::clear_cache('news');
	}
	else
		$Errorh->handler('e_auth', E_USER_REDIRECT);
}
elseif ($edit_news_id > 0)
{
	$news_infos = $Sql->query_array(PREFIX . 'news', '*', "WHERE id = '" . $edit_news_id . "'", __LINE__, __FILE__);
	if (empty($news_infos['title']))
		redirect(HOST. DIR . url('/news/news.php'));
	define('TITLE', $NEWS_LANG['news_management']);
	
	//Barre d'arborescence
	$auth_write = $User->check_auth($CONFIG_NEWS['global_auth'], WRITE_CAT_NEWS);
	
	$Bread_crumb->add($NEWS_LANG['news_management'], url('management.php?edit=' . $edit_news_id));
	
	$Bread_crumb->add($news_infos['title'], url('news.php?id=' . $edit_news_id, 'news-' . $edit_news_id . '+' . url_encode_rewrite($news_infos['title']) . '.php'));
	
	$id_cat = $news_infos['idcat'];

	//Bread_crumb : we read categories list recursively
	while ($id_cat > 0)
	{
		$Bread_crumb->add($NEWS_CATS[$id_cat]['name'], url('news.php?id=' . $id_cat, 'category-' . $id_cat . '+' . url_encode_rewrite($NEWS_CATS[$id_cat]['name']) . '.php'));
		
		if (!empty($NEWS_CATS[$id_cat]['auth']))
			$auth_write = $User->check_auth($NEWS_CATS[$id_cat]['auth'], WRITE_CAT_NEWS);
		
		$id_cat = (int)$NEWS_CATS[$id_cat]['id_parent'];
	}
	
	if (!$auth_write)
		$Errorh->handler('e_auth', E_USER_REDIRECT);
}
else
{
	$Bread_crumb->add($NEWS_LANG['news_addition'], url('management.php?new=1'));
	define('TITLE', $NEWS_LANG['news_addition']);
}


$Bread_crumb->add($NEWS_LANG['news'], url('news.php'));

$Bread_crumb->reverse();
	

require_once('../kernel/header.php');

$Template->set_filenames(array(
	'news_management'=> 'news/news_management.tpl'
));

if ($edit_news_id > 0)
{
	if ($submit)
	{
		//The form is ok
		if (!empty($news_title) && $news_categories->check_auth($news_cat_id) && !empty($news_url) && !empty($news_contents))
		{
			$visible = 1;
			
			$date_now = new Date(DATE_NOW);
			
			switch ($news_visibility)
			{
				case 2:
					if ($begining_date->get_timestamp() < $date_now->get_timestamp() &&  $end_date->get_timestamp() > $date_now->get_timestamp())
					{
						$start_timestamp = $begining_date->get_timestamp();
						$end_timestamp = $end_date->get_timestamp();
					}
					else
						$visible = 0;

					break;
				case 1:
					list($start_timestamp, $end_timestamp) = array(0, 0);
					break;
				default:
					list($visible, $start_timestamp, $end_timestamp) = array(0, 0, 0);
			}
			
			$Sql->query_inject("UPDATE " . PREFIX . "news SET title = '" . $news_title . "', idcat = '" . $news_cat_id . "', url = '" . $news_url . "', size = '" . $news_size . "', count = '" . $news_hits . "', contents = '" . strparse($news_contents) . "', short_contents = '" . strparse($news_short_contents) . "', image = '" . $news_image . "', timestamp = '" . $news_creation_date->get_timestamp() . "', release_timestamp = '" . ($ignore_release_date ? 0 : $news_release_date->get_timestamp()) . "', start = '" . $start_timestamp . "', end = '" . $end_timestamp . "', visible = '" . $visible . "' WHERE id = '" . $edit_news_id . "'", __LINE__, __FILE__);
			
			//Updating the number of subnewss in each category
			if ($news_cat_id != $news_infos['idcat'])
			{
				$news_categories->Recount_sub_newss();
			}
            
            // Feeds Regeneration
            import('content/syndication/feed');
            Feed::clear_cache('news');
            
			redirect(HOST . DIR . '/news/' . url('news.php?id=' . $edit_news_id, 'news-' . $edit_news_id . '+' . url_encode_rewrite($news_title) . '.php'));
		}
		//Error (which souldn't happen because of the javascript checking)
		else
		{
			redirect(HOST . DIR . '/news/' . url('news.php'));
		}
	}
	//Previewing a news
	elseif ($preview)
	{
		$begining_calendar = new MiniCalendar('begining_date');
		$begining_calendar->set_date($begining_date);
		$end_calendar = new MiniCalendar('end_date');
		$end_calendar->set_date($end_date);
		$end_calendar->set_style('margin-left:150px;');

		$Template->set_filenames(array('news' => 'news/news.tpl'));
		
		if ($news_size > 1)
			$size_tpl = $news_size . ' ' . $LANG['unit_megabytes'];
		elseif ($news_size > 0)
			$size_tpl = ($news_size * 1024) . ' ' . $LANG['unit_kilobytes'];
		else
			$size_tpl = $NEWS_LANG['unknown_size'];
		
		//Crï¿½ation des calendriers
		$creation_calendar = new MiniCalendar('creation');
		$creation_calendar->set_date($news_creation_date);
		$release_calendar = new MiniCalendar('release_date');
		$release_calendar->set_date($news_release_date);
		
		if ($news_visibility < 0 || $news_visibility > 2)
			$news_visibility = 0;

		$Template->assign_vars(array(
			'C_DISPLAY_NEWS' => true,
			'C_IMG' => !empty($news_image),
			'C_EDIT_AUTH' => false,
			'MODULE_DATA_PATH' => $Template->get_module_data_path('news'),
			'NAME' => stripslashes($news_title),
			'CONTENTS' => second_parse(stripslashes(strparse($news_contents))),
			'CREATION_DATE' => $news_creation_date->format(DATE_FORMAT_SHORT) ,
			'RELEASE_DATE' => $news_release_date->get_timestamp() > 0 ? $news_release_date->format(DATE_FORMAT_SHORT) : $NEWS_LANG['unknown_date'],
			'SIZE' => $size_tpl,
			'COUNT' => $news_hits,
			'THEME' => get_utheme(),
			'HITS' => sprintf($NEWS_LANG['n_times'], (int)$news_hits),
			'NUM_NOTES' => sprintf($NEWS_LANG['num_notes'], 0),
			'U_IMG' => $news_image,
			'IMAGE_ALT' => str_replace('"', '\"', $news_title),
			'LANG' => get_ulang(),
			// Those langs are required by the template inclusion
			'L_DATE' => $LANG['date'],
			'L_SIZE' => $LANG['size'],
			'L_NEWS' => $NEWS_LANG['news'],
			'L_NEWS_FILE' => $NEWS_LANG['news_news'],
			'L_FILE_INFOS' => $NEWS_LANG['news_infos'],
			'L_INSERTION_DATE' => $NEWS_LANG['insertion_date'],
			'L_RELEASE_DATE' => $NEWS_LANG['release_date'],
			'L_NEWSED' => $NEWS_LANG['newsed'],
			'L_NOTE' => $LANG['note'],
			'U_NEWS_FILE' => url('count.php?id=' . $edit_news_id, 'news-' . $edit_news_id . '+' . url_encode_rewrite($news_title) . '.php')
		));

		$Template->assign_vars(array(
			'TITLE' => $news_title,
			'COUNT' => $news_hits,
			'DESCRIPTION' => $news_contents,
			'SHORT_DESCRIPTION' => $news_short_contents,
			'FILE_IMAGE' => $news_image,
			'URL' => $news_url,
			'SIZE_FORM' => $news_size,
			'DATE' => $news_creation_date->format(DATE_FORMAT_SHORT, TIMEZONE_AUTO),
			'CATEGORIES_TREE' => $news_categories->build_select_form($news_cat_id, 'idcat', 'idcat', 0, WRITE_CAT_NEWS, $CONFIG_NEWS['global_auth'], IGNORE_AND_CONTINUE_BROWSING_IF_A_CATEGORY_DOES_NOT_MATCH),
			'SHORT_DESCRIPTION_PREVIEW' => second_parse(stripslashes(strparse($news_short_contents))),
			'VISIBLE_WAITING' => $news_visibility == 2 ? ' checked="checked"' : '',
			'VISIBLE_ENABLED' => $news_visibility == 1 ? ' checked="checked"' : '',
			'VISIBLE_UNAPROVED' => $news_visibility == 0 ? ' checked="checked"' : '',
			'DATE_CALENDAR_CREATION' => $creation_calendar->display(),
			'DATE_CALENDAR_RELEASE' => $release_calendar->display(),
			'BOOL_IGNORE_RELEASE_DATE' => $ignore_release_date ? 'true' : 'false',
			'STYLE_FIELD_RELEASE_DATE' => $ignore_release_date ? 'none' : 'block',
			'IGNORE_RELEASE_DATE_CHECKED' => $ignore_release_date ? ' checked="checked"' : '',
			'BEGINING_CALENDAR' => $begining_calendar->display(),
			'END_CALENDAR' => $end_calendar->display(),
		));
	}
	//Default formulary, with news infos from the database
	else
	{
		$news_creation_date = new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $news_infos['timestamp']);
		$news_release_date = new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $news_infos['release_timestamp']);
		
		$creation_calendar = new MiniCalendar('creation');
		$creation_calendar->set_date($news_creation_date);
		
		$release_calendar = new MiniCalendar('release_date');
		$ignore_release_date = ($news_release_date->get_timestamp() == 0);
		if (!$ignore_release_date)
			$release_calendar->set_date($news_release_date);
		
		
		$begining_calendar = new MiniCalendar('begining_date');
		$end_calendar = new MiniCalendar('end_date');
		$end_calendar->set_style('margin-left:150px;');
		
		if (!empty($news_infos['start']) && !empty($news_infos['end']))
		{
			$news_visibility = 2;
			$begining_calendar->set_date(new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $news_infos['start']));
			$end_calendar->set_date(new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $news_infos['end']));
		}
		elseif (!empty($news_infos['visible']))
			$news_visibility = 1;
		else
			$news_visibility = 0;
		
		$Template->assign_vars(array(
			'TITLE' => $news_infos['title'],
			'COUNT' => !empty($news_infos['count']) ? $news_infos['count'] : 0,
			'DESCRIPTION' => unparse($news_infos['contents']),
			'SHORT_DESCRIPTION' => unparse($news_infos['short_contents']),
			'FILE_IMAGE' => $news_infos['image'],
			'URL' => $news_infos['url'],
			'SIZE_FORM' => $news_infos['size'],
			'DATE' => $news_creation_date->format(DATE_FORMAT_SHORT, TIMEZONE_AUTO),
			'CATEGORIES_TREE' => $news_categories->build_select_form($news_infos['idcat'], 'idcat', 'idcat', 0, WRITE_CAT_NEWS, $CONFIG_NEWS['global_auth'], IGNORE_AND_CONTINUE_BROWSING_IF_A_CATEGORY_DOES_NOT_MATCH),
			'DATE_CALENDAR_CREATION' => $creation_calendar->display(),
			'DATE_CALENDAR_RELEASE' => $release_calendar->display(),
			'BOOL_IGNORE_RELEASE_DATE' => $ignore_release_date ? 'true' : 'false',
			'STYLE_FIELD_RELEASE_DATE' => $ignore_release_date ? 'none' : 'block',
			'IGNORE_RELEASE_DATE_CHECKED' => $ignore_release_date ? ' checked="checked"' : '',
			'BEGINING_CALENDAR' => $begining_calendar->display(),
			'END_CALENDAR' => $end_calendar->display(),
			'VISIBLE_WAITING' => $news_visibility == 2 ? ' checked="checked"' : '',
			'VISIBLE_ENABLED' => $news_visibility == 1 ? ' checked="checked"' : '',
			'VISIBLE_UNAPROVED' => $news_visibility == 0 ? ' checked="checked"' : '',
			'U_TARGET' => url('management.php?edit=' . $edit_news_id . '&amp;token=' . $Session->get_token())
		));
	}
}
//Adding a news
elseif ($add_news)
{
	if ($submit)
	{
		//The form is ok
		if (!empty($news_title) && $news_categories->check_auth($news_cat_id) && !empty($news_url) && !empty($news_contents))
		{
			$visible = 1;
			
			$date_now = new Date(DATE_NOW);
			
			switch ($news_visibility)
			{
				case 2:
					if ($begining_date->get_timestamp() < $date_now->get_timestamp() &&  $end_date->get_timestamp() > $date_now->get_timestamp())
					{
						$start_timestamp = $begining_date->get_timestamp();
						$end_timestamp = $end_date->get_timestamp();
					}
					else
						$visible = 0;

					break;
				case 1:
					list($start_timestamp, $end_timestamp) = array(0, 0);
					break;
				default:
					list($visible, $start_timestamp, $end_timestamp) = array(0, 0, 0);
			}
			
			$Sql->query_inject("INSERT INTO " . PREFIX . "news (title, idcat, url, size, count, contents, short_contents, image, timestamp, release_timestamp, start, end, visible) VALUES ('" . $news_title . "', '" . $news_cat_id . "', '" . $news_url . "', '" . $news_size . "', '" . $news_hits . "', '" . strparse($news_contents) . "', '" . strparse($news_short_contents) . "', '" . $news_image . "', '" . $news_creation_date->get_timestamp() . "', '" . ($ignore_release_date ? 0 : $news_release_date->get_timestamp()) . "', '" . $start_timestamp . "', '" . $end_timestamp . "', '" . $visible . "')", __LINE__, __FILE__);
			
			$new_id_news = $Sql->insert_id("SELECT MAX(id) FROM " . PREFIX . "news");
			
			//Updating the number of subnewss in each category
			if ($news_cat_id != $news_infos['idcat'])
			{
				$news_categories->Recount_sub_newss();
			}
            
            // Feeds Regeneration
            import('content/syndication/feed');
            Feed::clear_cache('news');
            
			redirect(HOST . DIR . '/news/' . url('news.php?id=' . $new_id_news, 'news-' . $new_id_news . '+' . url_encode_rewrite($news_title) . '.php'));
		}
		//Error (which souldn't happen because of the javascript checking)
		else
		{
			redirect(HOST . DIR . '/news/' . url('news.php'));
		}
	}
	//Previewing a news
	elseif ($preview)
	{
		$begining_calendar = new MiniCalendar('begining_date');
		$begining_calendar->set_date($begining_date);
		$end_calendar = new MiniCalendar('end_date');
		$end_calendar->set_date($end_date);
		$end_calendar->set_style('margin-left:150px;');
		
		$Template->set_filenames(array('news' => 'news/news.tpl'));
		
		if ($news_size > 1)
			$size_tpl = $news_size . ' ' . $LANG['unit_megabytes'];
		elseif ($news_size > 0)
			$size_tpl = ($news_size * 1024) . ' ' . $LANG['unit_kilobytes'];
		else
			$size_tpl = $NEWS_LANG['unknown_size'];
		
		//Crï¿½ation des calendriers
		$creation_calendar = new MiniCalendar('creation');
		$creation_calendar->set_date($news_creation_date);
		$release_calendar = new MiniCalendar('release_date');
		$release_calendar->set_date($news_release_date);
		
		if ($news_visibility < 0 || $news_visibility > 2)
			$news_visibility = 0;

		$Template->assign_vars(array(
			'C_DISPLAY_NEWS' => true,
			'C_IMG' => !empty($news_image),
			'C_EDIT_AUTH' => false,
			'MODULE_DATA_PATH' => $Template->get_module_data_path('news'),
			'NAME' => stripslashes($news_title),
			'CONTENTS' => second_parse(stripslashes(strparse($news_contents))),
			'CREATION_DATE' => $news_creation_date->format(DATE_FORMAT_SHORT) ,
			'RELEASE_DATE' => $news_release_date->get_timestamp() > 0 ? $news_release_date->format(DATE_FORMAT_SHORT) : $NEWS_LANG['unknown_date'],
			'SIZE' => $size_tpl,
			'COUNT' => $news_hits,
			'THEME' => get_utheme(),
			'HITS' => sprintf($NEWS_LANG['n_times'], (int)$news_hits),
			'NUM_NOTES' => sprintf($NEWS_LANG['num_notes'], 0),
			'U_IMG' => $news_image,
			'IMAGE_ALT' => str_replace('"', '\"', $news_title),
			'LANG' => get_ulang(),
			// Those langs are required by the template inclusion
			'L_DATE' => $LANG['date'],
			'L_SIZE' => $LANG['size'],
			'L_NEWS' => $NEWS_LANG['news'],
			'L_NEWS_FILE' => $NEWS_LANG['news_news'],
			'L_FILE_INFOS' => $NEWS_LANG['news_infos'],
			'L_INSERTION_DATE' => $NEWS_LANG['insertion_date'],
			'L_RELEASE_DATE' => $NEWS_LANG['release_date'],
			'L_NEWSED' => $NEWS_LANG['newsed'],
			'L_NOTE' => $LANG['note'],
			'U_NEWS_FILE' => url('count.php?id=' . $edit_news_id, 'news-' . $edit_news_id . '+' . url_encode_rewrite($news_title) . '.php')
		));

		$Template->assign_vars(array(
			'TITLE' => stripslashes($news_title),
			'COUNT' => $news_hits,
			'DESCRIPTION' => $news_contents,
			'SHORT_DESCRIPTION' => $news_short_contents,
			'FILE_IMAGE' => $news_image,
			'URL' => $news_url,
			'SIZE_FORM' => $news_size,
			'DATE' => $news_creation_date->format(DATE_FORMAT_SHORT, TIMEZONE_AUTO),
			'CATEGORIES_TREE' => $news_categories->build_select_form($news_cat_id, 'idcat', 'idcat', 0, WRITE_CAT_NEWS, $CONFIG_NEWS['global_auth'], IGNORE_AND_CONTINUE_BROWSING_IF_A_CATEGORY_DOES_NOT_MATCH),
			'SHORT_DESCRIPTION_PREVIEW' => second_parse(stripslashes(strparse($news_short_contents))),
			'VISIBLE_WAITING' => $news_visibility == 2 ? ' checked="checked"' : '',
			'VISIBLE_ENABLED' => $news_visibility == 1 ? ' checked="checked"' : '',
			'VISIBLE_UNAPROVED' => $news_visibility == 0 ? ' checked="checked"' : '',
			'DATE_CALENDAR_CREATION' => $creation_calendar->display(),
			'DATE_CALENDAR_RELEASE' => $release_calendar->display(),
			'BOOL_IGNORE_RELEASE_DATE' => $ignore_release_date ? 'true' : 'false',
			'STYLE_FIELD_RELEASE_DATE' => $ignore_release_date ? 'none' : 'block',
			'IGNORE_RELEASE_DATE_CHECKED' => $ignore_release_date ? ' checked="checked"' : '',
			'BEGINING_CALENDAR' => $begining_calendar->display(),
			'END_CALENDAR' => $end_calendar->display(),
		));
	}
	else
	{
		$news_creation_date = new Date(DATE_NOW, TIMEZONE_AUTO);
		$news_release_date = new Date(DATE_NOW, TIMEZONE_AUTO);
		
		$creation_calendar = new MiniCalendar('creation');
		$creation_calendar->set_date($news_creation_date);
		
		$release_calendar = new MiniCalendar('release_date');
		$ignore_release_date = false;
		if (!$ignore_release_date)
			$release_calendar->set_date($news_release_date);
		
		
		$begining_calendar = new MiniCalendar('begining_date');
		$end_calendar = new MiniCalendar('end_date');
		$end_calendar->set_style('margin-left:150px;');
		
		$begining_calendar->set_date(new Date(DATE_NOW, TIMEZONE_AUTO));
		$end_calendar->set_date(new Date(DATE_NOW, TIMEZONE_AUTO));
		$news_visibility = 0;
		
		$Template->assign_vars(array(
			'TITLE' => '',
			'COUNT' => 0,
			'DESCRIPTION' => '',
			'SHORT_DESCRIPTION' => '',
			'FILE_IMAGE' => '',
			'URL' => '',
			'SIZE_FORM' => '',
			'DATE' => $news_creation_date->format(DATE_FORMAT_SHORT, TIMEZONE_AUTO),
			'CATEGORIES_TREE' => $news_categories->build_select_form($selected_cat, 'idcat', 'idcat', 0, WRITE_CAT_NEWS, $CONFIG_NEWS['global_auth'], IGNORE_AND_CONTINUE_BROWSING_IF_A_CATEGORY_DOES_NOT_MATCH),
			'DATE_CALENDAR_CREATION' => $creation_calendar->display(),
			'DATE_CALENDAR_RELEASE' => $release_calendar->display(),
			'BOOL_IGNORE_RELEASE_DATE' => $ignore_release_date ? 'true' : 'false',
			'STYLE_FIELD_RELEASE_DATE' => $ignore_release_date ? 'none' : 'block',
			'IGNORE_RELEASE_DATE_CHECKED' => $ignore_release_date ? ' checked="checked"' : '',
			'BEGINING_CALENDAR' => $begining_calendar->display(),
			'END_CALENDAR' => $end_calendar->display(),
			'VISIBLE_WAITING' => '',
			'VISIBLE_ENABLED' => ' checked="checked"',
			'VISIBLE_UNAPROVED' => '',
			'U_TARGET' => url('management.php?new=1&amp;token=' . $Session->get_token())
		));
	}
}

$Template->assign_vars(array(
	'KERNEL_EDITOR' => display_editor(),
	'KERNEL_EDITOR_SHORT' => display_editor('short_contents'),
	'C_PREVIEW' => $preview,
	'L_PAGE_TITLE' => TITLE,
	'L_EDIT_FILE' => $NEWS_LANG['edit_news'],
	'L_YES' => $LANG['yes'],
	'L_NO' => $LANG['no'],
	'L_NEWS_DATE' => $NEWS_LANG['news_date'],
	'L_IGNORE_RELEASE_DATE' => $NEWS_LANG['ignore_release_date'],
	'L_RELEASE_DATE' => $NEWS_LANG['release_date'],
	'L_FILE_VISIBILITY' => $NEWS_LANG['news_visibility'],
	'L_NOW' => $LANG['now'],
	'L_UNAPPROVED' => $LANG['unapproved'],
	'L_TO_DATE' => $LANG['to_date'],
	'L_FROM_DATE' => $LANG['from_date'],
	'L_DESC' => $LANG['description'],
	'L_NEWS' => $NEWS_LANG['news'],
	'L_SIZE' => $LANG['size'],
	'L_URL' => $LANG['url'],
	'L_FILE_IMAGE' => $NEWS_LANG['news_image'],
	'L_TITLE' => $LANG['title'],
	'L_CATEGORY' => $LANG['category'],
	'L_REQUIRE' => $LANG['require'],
	'L_NEWS_ADD' => $NEWS_LANG['news_add'],
	'L_NEWS_MANAGEMENT' => $NEWS_LANG['news_management'],
	'L_NEWS_CONFIG' => $NEWS_LANG['news_config'],
	'L_UPDATE' => $LANG['update'],
	'L_RESET' => $LANG['reset'],
	'L_PREVIEW' => $LANG['preview'],
	'L_UNIT_SIZE' => $LANG['unit_megabytes'],
	'L_CONTENTS' => $NEWS_LANG['complete_contents'],
	'L_SHORT_CONTENTS' => $NEWS_LANG['short_contents'],
	'L_SUBMIT' => $edit_news_id > 0 ? $NEWS_LANG['update_news'] : $NEWS_LANG['add_news'],
	'L_WARNING_PREVIEWING' => $NEWS_LANG['warning_previewing'],
	'L_REQUIRE_DESCRIPTION' => $NEWS_LANG['require_description'],
	'L_REQUIRE_URL' => $NEWS_LANG['require_url'],
	'L_REQUIRE_CREATION_DATE' => $NEWS_LANG['require_creation_date'],
	'L_REQUIRE_RELEASE_DATE' => $NEWS_LANG['require_release_date'],
	'L_REQUIRE_TITLE' => $LANG['require_title']
));
	
$Template->pparse('news_management');
require_once('../kernel/footer.php');

?>
