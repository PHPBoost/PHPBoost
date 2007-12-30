<?php
/*##################################################
 *                               admin_news_config.php
 *                            -------------------
 *   begin                : June 20, 2005
 *   copyright          : (C) 2005 Viarre Régis
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

require_once('../includes/admin_begin.php');
load_module_lang('news', $CONFIG['lang']); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../includes/admin_header.php');

if( !empty($_POST['valid']) )
{
	$config_news = array();
	$config_news['type'] = isset($_POST['type']) ? numeric($_POST['type']) : 0;
	$config_news['activ_pagin'] = !empty($_POST['activ_pagin']) ? numeric($_POST['activ_pagin']) : 0;
	$config_news['activ_edito'] = !empty($_POST['activ_edito']) ? numeric($_POST['activ_edito']) : 0;
	$config_news['pagination_news'] = isset($_POST['pagination_news']) ? numeric($_POST['pagination_news']) : '6';
	$config_news['pagination_arch'] = isset($_POST['pagination_arch']) ? numeric($_POST['pagination_arch']) : '15';
	$config_news['activ_com'] = isset($_POST['activ_com']) ? numeric($_POST['activ_com']) : 0;  
	$config_news['activ_icon'] = isset($_POST['activ_icon']) ? numeric($_POST['activ_icon']) : 0;  
	$config_news['nbr_news'] = $sql->query("SELECT COUNT(*) FROM ".PREFIX."news WHERE visible = 1", __LINE__, __FILE__);
	$config_news['nbr_column'] = !empty($_POST['nbr_column']) ? numeric($_POST['nbr_column']) : 1;
	$config_news['edito'] = !empty($_POST['edito']) ? stripslashes(parse($_POST['edito'])) : '';
	$config_news['edito_title'] = !empty($_POST['edito_title']) ? stripslashes(securit($_POST['edito_title'])) : '';
		
	$sql->query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($config_news)) . "' WHERE name = 'news'", __LINE__, __FILE__);
	
	###### Régénération du cache des news #######
	$cache->generate_module_file('news');
	
	header('location:' . HOST . SCRIPT);	
	exit;
}
//Sinon on rempli le formulaire
else	
{		
	$template->set_filenames(array(
		'admin_news_config' => '../templates/' . $CONFIG['theme'] . '/news/admin_news_config.tpl'
	));
	
	$cache->load_file('news');
	
	$template->assign_vars(array(
		'PAGINATION' => !empty($CONFIG_NEWS['pagination_news']) ? $CONFIG_NEWS['pagination_news'] : '6',
		'PAGINATION_ARCH' => !empty($CONFIG_NEWS['pagination_arch']) ? numeric($CONFIG_NEWS['pagination_arch']) : '15',
		'TITLE' => !empty($CONFIG_NEWS['edito_title']) ? $CONFIG_NEWS['edito_title'] : '',
		'CONTENTS' => !empty($CONFIG_NEWS['edito']) ? unparse($CONFIG_NEWS['edito']) : '',
		'BLOCK_ENABLED' => ($CONFIG_NEWS['type'] == '1') ? 'checked="checked"' : '',
		'BLOCK_DISABLED' => ($CONFIG_NEWS['type'] == '0') ? 'checked="checked"' : '',
		'PAGIN_ENABLED' => ($CONFIG_NEWS['activ_pagin'] == '1') ? 'checked="checked"' : '',
		'PAGIN_DISABLED' => ($CONFIG_NEWS['activ_pagin'] == '0') ? 'checked="checked"' : '',
		'NBR_COLUMN' => !empty($CONFIG_NEWS['nbr_column']) ? $CONFIG_NEWS['nbr_column'] : '1',
		'EDITO_ENABLED' => ($CONFIG_NEWS['activ_edito'] == '1') ? 'checked="checked"' : '',
		'EDITO_DISABLED' => ($CONFIG_NEWS['activ_edito'] == '0') ? 'checked="checked"' : '',
		'COM_ENABLED' => ($CONFIG_NEWS['activ_com'] == '1') ? 'checked="checked"' : '',
		'COM_DISABLED' => ($CONFIG_NEWS['activ_com'] == '0') ? 'checked="checked"' : '',
		'ICON_ENABLED' => ($CONFIG_NEWS['activ_icon'] == '1') ? 'checked="checked"' : '',
		'ICON_DISABLED' => ($CONFIG_NEWS['activ_icon'] == '0') ? 'checked="checked"' : '',
		'L_REQUIRE_TITLE' => $LANG['require_title'],
		'L_REQUIRE_TEXT' => $LANG['require_text'],
		'L_REQUIRE' => $LANG['require'],
		'L_NEWS_MANAGEMENT' => $LANG['news_management'],
		'L_ADD_NEWS' => $LANG['add_news'],
		'L_CONFIG_NEWS' => $LANG['configuration_news'],
		'L_CAT_NEWS' => $LANG['category_news'],
		'L_TITLE' => $LANG['title'],
		'L_TEXT' => $LANG['contents'],
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_APROB' => $LANG['aprob'],
		'L_UNAPROB' => $LANG['unaprob'],
		'L_SUBMIT' => $LANG['submit'],
		'L_UPDATE' => $LANG['update'],
		'L_PREVIEW' => $LANG['preview'],
		'L_RESET' => $LANG['reset'],
		'L_EDITO_WHERE' => $LANG['edito_where'],
		'L_CONFIG_NEWS' => $LANG['config_news'],
		'L_NBR_NEWS_P' => $LANG['nbr_news_p'],
		'L_NBR_NEWS_P_EXPLAIN' => $LANG['nbr_news_p_explain'],
		'L_NBR_COLUMN_MAX' => $LANG['nbr_news_column'],
		'L_NBR_ARCH_P' => $LANG['nbr_arch_p'],
		'L_NBR_ARCH_P_EXPLAIN' => $LANG['nbr_arch_p_explain'],
		'L_MODULE_MANAGEMENT' => $LANG['module_management'],
		'L_ACTIV_PAGINATION' => $LANG['activ_pagination'],
		'L_ACTIV_PAGINATION_EXPLAIN' => $LANG['activ_pagination_explain'],
		'L_ACTIV_EDITO' => $LANG['activ_edito'],
		'L_ACTIV_EDITO_EXPLAIN' => $LANG['activ_edito_explain'],
		'L_ACTIV_NEWS_BLOCK' => $LANG['activ_news_block'],
		'L_ACTIV_COM_NEWS' => $LANG['activ_com_n'],
		'L_ACTIV_ICON_NEWS' => $LANG['activ_icon_n']
	));

	include_once('../includes/bbcode.php');
	
	$template->pparse('admin_news_config'); // traitement du modele	
}

require_once('../includes/admin_footer.php');

?>