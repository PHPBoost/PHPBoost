<?php
/*##################################################
 *                              articles_mini.php
 *                            -------------------
 *   begin                : October 09, 2009
 *   copyright          : (C) 2009 Maurel Nicolas
 *   email                : crunchfamily@free.fr
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
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

if (defined('PHPBOOST') !== true) exit;

function articles_mini($position, $block)
{
    global $Cache, $LANG, $CONFIG_ARTICLES;
   
   $Cache->load('articles'); 

	load_module_lang('articles');
	
	$tpl = new Template('articles/articles_mini.tpl');
	import('core/menu_service');
	MenuService::assign_positions_conditions($tpl, $block);

	$mini_conf=unserialize($CONFIG_ARTICLES['mini']);
	
	echo $mini_conf;
	switch ($mini_conf['type'])
	{
		case 'note' :	
			$sort = 'note';	
			$l_type=$ARTICLES_LANG['articles_best_note'];
			break;
		case 'com' :
			$sort = 'nbr_com';
			$l_type=$ARTICLES_LANG['articles_more_com'];
			break;
		case 'date' :
			$sort = 'timestamp';
			$l_type=$ARTICLES_LANG['articles_by_date'];
			break;
		case 'view' :
			$sort = 'views';
			$l_type=$ARTICLES_LANG['articles_most_popular'];
			break;
		default :
			$sort = 'date';
			$l_type=$ARTICLES_LANG['articles_by_date'];
			break;
	}
	
	//echo "sort : ".$sort."   - nbr : ".$mini_conf['nbr_articles'];
	import('content/note');
	$result = $this->sql_querier->query_while("SELECT a.id, a.title,a.description, a.icon, a.timestamp, a.views, a.note, a.nbrnote, a.nbr_com,a.user_id,m.user_id,m.login,m.level
	FROM " . DB_TABLE_ARTICLES . " a
	WHERE a.visible = 1 
	ORDER BY " . $sort . " DESC ".
	$this->sql_querier->limit(0,$mini_conf['nbr_articles']), __LINE__, __FILE__);
	
	while ($row = $this->sql_querier->fetch_assoc($result))
	{		
		$tpl->assign_block_vars('articles', array(
			'ID' => $row['id'],
			'TITLE' => $row['title'],
			'NOTE' => ($row['nbrnote'] > 0) ? Note::display_img($row['note'], $CONFIG_ARTICLES['note_max'], 5) : '<em>' . $LANG['no_note'] . '</em>',
		));

	}
	
	$tpl->assign_vars(array(
		'L_TYPE_MINI'=>$l_type,
		'L_VOTE' => $LANG['poll_vote'],
		'L_POLL_RESULT' => $LANG['poll_result'],
		'U_POLL_RESULT' => url('.php?id=' . $poll_mini['id'] . '&amp;r=1', '-' . $poll_mini['id'] . '-1.php')
	));

	return $tpl->parse(Template::TEMPLATE_PARSER_STRING);

}
?>