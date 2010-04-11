<?php
/*##################################################
 *                              articles_mini.php
 *                            -------------------
 *   begin                : October 09, 2009
 *   copyright            : (C) 2009 Maurel Nicolas
 *   email                : crunchfamily@free.fr
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

if (defined('PHPBOOST') !== true) exit;

require_once PATH_TO_ROOT . '/articles/articles_constants.php';

function articles_mini($position, $block)
{
    global $Cache, $LANG, $CONFIG_ARTICLES,$ARTICLES_LANG;
   
	$Cache->load('articles'); 
	load_module_lang('articles');
	
	$tpl = new FileTemplate('articles/articles_mini.tpl');
	
	MenuService::assign_positions_conditions($tpl, $block);

	$com = false;
	$note = false;
	$date = false;
	$view = false;
	$mini_conf = unserialize($CONFIG_ARTICLES['mini']);
	switch ($mini_conf['type'])
	{
		case 'note' :	
			$sort = 'note';	
			$l_type = $ARTICLES_LANG['articles_best_note'];
			$note= true;
			break;
		case 'com' :
			$sort = 'nbr_com';
			$l_type = $ARTICLES_LANG['articles_more_com'];
			$com= true;
			break;
		case 'date' :
			$sort = 'timestamp';
			$l_type = $ARTICLES_LANG['articles_by_date'];
			$date= true;
			break;
		case 'view' :
			$sort = 'views';
			$l_type = $ARTICLES_LANG['articles_most_popular'];
			$view= true;
			break;
		default :
			$sort = 'date';
			$l_type = $ARTICLES_LANG['articles_by_date'];
			$date= true;
			break;
	}
	
	
	$result = PersistenceContext::get_sql()->query_while("SELECT a.id, a.title, a.idcat,a.description, a.icon, a.timestamp, a.views, a.note, a.nbrnote, a.nbr_com, a.user_id
	FROM " . DB_TABLE_ARTICLES . " a	
	WHERE a.visible = 1 
	ORDER BY " . $sort . " DESC ".
	PersistenceContext::get_sql()->limit(0, $mini_conf['nbr_articles']), __LINE__, __FILE__);
	
	while ($row = PersistenceContext::get_sql()->fetch_assoc($result))
	{		
		$fichier = (strlen($row['title']) > 45 ) ? substr(html_entity_decode($row['title']), 0, 45) . '...' : $row['title'];
		
		$tpl->assign_block_vars('articles', array(
			'ID' => $row['id'],
			'TITLE' => $row['title'],
			'NOTE' => $note ? (($row['nbrnote'] > 0) ? Note::display_img($row['note'], $CONFIG_ARTICLES['note_max'], 5) : '<em>' . $LANG['no_note'] . '</em>') : '',
			'DATE' => $date ? ($LANG['date']. " : ". gmdate_format('date_format_short', $row['timestamp'])) : '',
			'VIEW'=> $view ? ($LANG['views']." : ".$row['views']) : '',
			'COM'=> $com ? ($LANG['com']. " : ".$row['nbr_com']) : '',
			'DESCRIPTION'=>$row['description'],
			'U_ARTICLES_LINK' => url('.php?id=' . $row['id'] . '&amp;cat=' . $row['idcat'], '-' . $row['idcat'] . '-' . $row['id'] . '+' . Url::encode_rewrite($fichier) . '.php'),
		));
	}
	
	$tpl->assign_vars(array(
		'L_TYPE_MINI' => $l_type,
		'L_MORE_ARTICLE' => $ARTICLES_LANG['more_article'],
		'READ_ARTICLE'=>$ARTICLES_LANG['read_article'],
	));

	return $tpl->to_string();
}

?>