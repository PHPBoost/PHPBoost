<?php
/*##################################################
 *                      admin_articles_management.php
 *                            -------------------
 *   begin                : July 10, 2005
 *   copyright            : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
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

require_once('../admin/admin_begin.php');
load_module_lang('articles'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');
require_once('articles_constants.php');
//On recupère les variables.
	$tpl = new Template('articles/admin_articles_management.tpl');
	require_once('admin_articles_menu.php');
	$tpl->assign_vars(array('ADMIN_MENU' => $admin_menu));

	
	$nbr_articles = $Sql->count_table('articles', __LINE__, __FILE__);
	
	//On crée une pagination si le nombre d'articles est trop important.
	import('util/pagination');
	$Pagination = new Pagination();
	
	$tpl->assign_vars(array(
		'THEME' => get_utheme(),
		'LANG' => get_ulang(),
		'PAGINATION' => $Pagination->display('admin_articles.php?p=%d', $nbr_articles, 'p', 25, 3),
		'CHEMIN' => SCRIPT,
		'L_CONFIRM_DEL_ARTICLE' => $LANG['confirm_del_article'],
		'L_ARTICLES_MANAGEMENT' => $ARTICLES_LANG['articles_management'],
		'L_NAME' => $LANG['name'],
		'L_TITLE' => $LANG['title'],
		'L_CATEGORY' => $LANG['category'],
		'L_PSEUDO' => $LANG['pseudo'],
		'L_DATE' => $LANG['date'],
		'L_APROB' => $LANG['aprob'],
		'L_UPDATE' => $LANG['update'],
		'L_DELETE' => $LANG['delete'],
		'L_SHOW' => $LANG['show']
	));

	$tpl->assign_block_vars('list', array(
	));
	
	$result = $Sql->query_while("SELECT a.id, a.idcat, a.title, a.timestamp, a.visible, a.start, a.end, ac.name, m.login 
	FROM " . DB_TABLE_ARTICLES . " a
	LEFT JOIN " . DB_TABLE_ARTICLES_CAT . " ac ON ac.id = a.idcat
	LEFT JOIN " . DB_TABLE_MEMBER . " m ON a.user_id = m.user_id
	ORDER BY a.timestamp DESC " .
	$Sql->limit($Pagination->get_first_msg(25, 'p'), 25), __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		if ($row['visible'] == 2)
			$aprob = $LANG['waiting'];
		elseif ($row['visible'] == 1)
			$aprob = $LANG['yes'];
		else
			$aprob = $LANG['no'];

		//On reccourci le lien si il est trop long pour éviter de déformer l'administration.
		$title = strlen($row['title']) > 45 ? substr_html($row['title'], 0, 45) . '...' : $row['title'];

		$visible = '';
		if ($row['start'] > 0)
			$visible .= gmdate_format('date_format_short', $row['start']);
		if ($row['end'] > 0 && $row['start'] > 0)
			$visible .= ' ' . strtolower($LANG['until']) . ' ' . gmdate_format('date_format_short', $row['end']);
		elseif ($row['end'] > 0)
			$visible .= $LANG['until'] . ' ' . gmdate_format('date_format_short', $row['end']);
		
		$tpl->assign_block_vars('list.articles', array(
			'TITLE' => $title,
			'IDCAT' => $row['idcat'],
			'ID' => $row['id'],
			'PSEUDO' => !empty($row['login']) ? $row['login'] : $LANG['guest'],
			'DATE' => gmdate_format('date_format_short', $row['timestamp']),
			'APROBATION' => $aprob,
			'VISIBLE' => ((!empty($visible)) ? '(' . $visible . ')' : ''),
			'U_CAT' => '<a href="../articles/articles' . url('.php?cat=' . $row['idcat'], '-' . $row['idcat'] . '.php') . '">' . (!empty($row['idcat']) ? $row['name'] : '<em>' . $LANG['root'] . '</em>') . '</a>'
		));
	}
	$Sql->query_close($result);
	
	$tpl->parse();


require_once('../admin/admin_footer.php');

?>