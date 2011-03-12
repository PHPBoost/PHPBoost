<?php
/*##################################################
 *                               admin_articles_config.php
 *                            -------------------
 *   begin                : March 12, 2007
 *   copyright          : (C) 2007 Viarre Régis
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

##########################admin_news_config.tpl###########################
if (!empty($_POST['valid']) && empty($_POST['valid_edito']))
{
	$Cache->load('articles');
	
	$config_articles = array();
	$config_articles['nbr_articles_max'] = retrieve(POST, 'nbr_articles_max', 10);
	$config_articles['nbr_cat_max'] = retrieve(POST, 'nbr_cat_max', 10);
	$config_articles['nbr_column'] = retrieve(POST, 'nbr_column', 2);
	$config_articles['note_max'] = max(1, retrieve(POST, 'note_max', 5));
	$config_articles['auth_root'] = isset($CONFIG_ARTICLES['auth_root']) ? serialize($CONFIG_ARTICLES['auth_root']) : serialize(array());
		
	$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($config_articles)) . "' WHERE name = 'articles'", __LINE__, __FILE__);
	
	if ($CONFIG_ARTICLES['note_max'] != $config_articles['note_max'])
		$Sql->query_inject("UPDATE " . PREFIX . "articles SET note = note * '" . ($config_articles['note_max']/$CONFIG_ARTICLES['note_max']) . "'", __LINE__, __FILE__);
	
	###### Régénération du cache des news #######
	$Cache->Generate_module_file('articles');
	
	redirect(HOST . SCRIPT);	
}
elseif (!empty($_POST['articles_count'])) //Recompte le nombre d'articles de chaque catégories
{
	$Cache->load('articles');
	
	$info_cat = array();
	$result = $Sql->query_while ("SELECT idcat, COUNT(*) as nbr_articles_visible 
	FROM " . PREFIX . "articles 
	WHERE visible = 1 AND idcat > 0
	GROUP BY idcat", __LINE__, __FILE__);
	
	while ($row = $Sql->fetch_assoc($result))
		$info_cat[$row['idcat']]['visible'] = $row['nbr_articles_visible'];
		
	$Sql->query_close($result);
	
	$result = $Sql->query_while ("SELECT idcat, COUNT(*) as nbr_articles_unvisible 
	FROM " . PREFIX . "articles 
	WHERE visible = 0 AND idcat > 0
	GROUP BY idcat", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
		$info_cat[$row['idcat']]['unvisible'] = $row['nbr_articles_unvisible'];
		
	$Sql->query_close($result);
	
	$result = $Sql->query_while("SELECT id, id_left, id_right
	FROM " . PREFIX . "articles_cats", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{			
		$nbr_articles_visible = 0;
		$nbr_articles_unvisible = 0;
		foreach ($info_cat as $key => $value)
		{			
			if ($CAT_ARTICLES[$key]['id_left'] >= $row['id_left'] && $CAT_ARTICLES[$key]['id_right'] <= $row['id_right'])
			{	
				$nbr_articles_visible += isset($info_cat[$key]['visible']) ? $info_cat[$key]['visible'] : 0;
				$nbr_articles_unvisible += isset($info_cat[$key]['unvisible']) ? $info_cat[$key]['unvisible'] : 0; 
			}
		}
		$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET nbr_articles_visible = '" . $nbr_articles_visible . "', nbr_articles_unvisible = '" . $nbr_articles_unvisible . "' WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);	
	}
	$Sql->query_close($result);
	
	$Cache->Generate_module_file('articles');
	
	redirect(HOST . DIR . '/articles/admin_articles_config.php'); 
}
//Sinon on rempli le formulaire
else	
{		
	$Template->set_filenames(array(
		'admin_articles_config'=> 'articles/admin_articles_config.tpl'
	));
	
	$Cache->load('articles');
	
	$Template->assign_vars(array(
		'NBR_ARTICLES_MAX' => !empty($CONFIG_ARTICLES['nbr_articles_max']) ? $CONFIG_ARTICLES['nbr_articles_max'] : '10',
		'NBR_CAT_MAX' => !empty($CONFIG_ARTICLES['nbr_cat_max']) ? $CONFIG_ARTICLES['nbr_cat_max'] : '10',
		'NBR_COLUMN' => !empty($CONFIG_ARTICLES['nbr_column']) ? $CONFIG_ARTICLES['nbr_column'] : '2',
		'NOTE_MAX' => !empty($CONFIG_ARTICLES['note_max']) ? $CONFIG_ARTICLES['note_max'] : '10',
		'L_REQUIRE' => $LANG['require'],		
		'L_ARTICLES_MANAGEMENT' => $LANG['articles_management'],
		'L_ARTICLES_ADD' => $LANG['articles_add'],
		'L_ARTICLES_CAT' => $LANG['cat_management'],
		'L_ARTICLES_CONFIG' => $LANG['articles_config'],
		'L_ARTICLES_CAT_ADD' => $LANG['articles_cats_add'],
		'L_NBR_ARTICLES_MAX' => $LANG['nbr_articles_max'],
		'L_NBR_CAT_MAX' => $LANG['nbr_cat_max'],
		'L_NBR_COLUMN_MAX' => $LANG['nbr_column_max'],
		'L_NOTE_MAX' => $LANG['note_max'],
		'L_EXPLAIN_ARTICLES_COUNT' => $LANG['explain_articles_count'],
		'L_RECOUNT' => $LANG['recount'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset']
	));
		
	$Template->pparse('admin_articles_config'); // traitement du modele	
}

require_once('../admin/admin_footer.php');

?>