<?php
/*##################################################
 *                               admin_news_config.php
 *                            -------------------
 *   begin                : June 20, 2005
 *   copyright            : (C) 2005 Viarre Régis, Roguelon Geoffrey
 *   email                : crowkait@phpboost.com, liaght@gmail.com
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
require_once('news_constant.php');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');
load_module_lang('news'); //Chargement de la langue du module.

if (!empty($_POST['submit']))
{
	$config_news = array(
		'type' => retrieve(POST, 'type', 0),
		'activ_com' => retrieve(POST, 'activ_com', 0),
		'activ_icon' => retrieve(POST, 'activ_icon', 0),
		'activ_edito' => retrieve(POST, 'activ_edito', 0),
		'activ_pagin' => retrieve(POST, 'activ_pagin', 0),
		'display_date' => retrieve(POST, 'display_date', 0),
		'display_author' => retrieve(POST, 'display_author', 0),
		'pagination_news' => retrieve(POST, 'pagination_news', 6),
		'pagination_arch' => retrieve(POST, 'pagination_arch', 15),
		'nbr_column' => retrieve(POST, 'nbr_column', 1),
		'nbr_news' => $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_NEWS . " WHERE visible = 1", __LINE__, __FILE__),
		'global_auth' => Authorizations::build_auth_array_from_form(AUTH_NEWS_READ, AUTH_NEWS_CONTRIBUTE, AUTH_NEWS_WRITE, AUTH_NEWS_MODERATE),
		'edito_title' => stripslashes(retrieve(POST, 'edito_title', '')),
		'edito' => stripslashes(retrieve(POST, 'edito', '', TSTRING_PARSE))
	);

	$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($config_news)) . "' WHERE name = 'news'", __LINE__, __FILE__);

	###### Régénération du cache des news #######
	$Cache->Generate_module_file('news');

	redirect(HOST . SCRIPT);
}
//Sinon on rempli le formulaire
else
{
	$tpl = new Template('news/admin_news_config.tpl');

	$Cache->load('news');

	// Chargement du menu de l'administration.
	require_once('admin_news_menu.php');

	$tpl->assign_vars(array(
		'ADMIN_MENU' => $admin_menu,
		'KERNEL_EDITOR' => display_editor(),
		'PAGINATION' => !empty($NEWS_CONFIG['pagination_news']) ? $NEWS_CONFIG['pagination_news'] : 6,
		'PAGINATION_ARCH' => !empty($NEWS_CONFIG['pagination_arch']) ? $NEWS_CONFIG['pagination_arch'] : 15,
		'TITLE' => !empty($NEWS_CONFIG['edito_title']) ? $NEWS_CONFIG['edito_title'] : '',
		'CONTENTS' => !empty($NEWS_CONFIG['edito']) ? unparse($NEWS_CONFIG['edito']) : '',
		'BLOCK_ENABLED' => ($NEWS_CONFIG['type'] == '1') ? 1 : 0,
		'BLOCK_DISABLED' => ($NEWS_CONFIG['type'] == '0') ? 1 : 0,
		'PAGIN_ENABLED' => ($NEWS_CONFIG['activ_pagin'] == '1') ? 1 : 0,
		'PAGIN_DISABLED' => ($NEWS_CONFIG['activ_pagin'] == '0') ? 1 : 0,
		'NBR_COLUMN' => !empty($NEWS_CONFIG['nbr_column']) ? $NEWS_CONFIG['nbr_column'] : 1,
		'EDITO_ENABLED' => ($NEWS_CONFIG['activ_edito'] == '1') ? 1 : 0,
		'EDITO_DISABLED' => ($NEWS_CONFIG['activ_edito'] == '0') ? 1 : 0,
		'COM_ENABLED' => ($NEWS_CONFIG['activ_com'] == '1') ? 1 : 0,
		'COM_DISABLED' => ($NEWS_CONFIG['activ_com'] == '0') ? 1 : 0,
		'ICON_ENABLED' => ($NEWS_CONFIG['activ_icon'] == '1') ? 1 : 0,
		'ICON_DISABLED' => ($NEWS_CONFIG['activ_icon'] == '0') ? 1 : 0,
		'AUTHOR_ENABLED' => ($NEWS_CONFIG['display_author'] == '1') ? 1 : 0,
		'AUTHOR_DISABLED' => ($NEWS_CONFIG['display_author'] == '0') ? 1 : 0,
		'DATE_ENABLED' => ($NEWS_CONFIG['display_date'] == '1') ? 1 : 0,
		'DATE_DISABLED' => ($NEWS_CONFIG['display_date'] == '0') ? 1 : 0,
		'AUTH_READ' => Authorizations::generate_select(AUTH_NEWS_READ, $NEWS_CONFIG['global_auth']),
		'AUTH_WRITE' => Authorizations::generate_select(AUTH_NEWS_WRITE, $NEWS_CONFIG['global_auth']),
		'AUTH_CONTRIBUTION' => Authorizations::generate_select(AUTH_NEWS_CONTRIBUTE, $NEWS_CONFIG['global_auth']),
		'AUTH_MODERATION' => Authorizations::generate_select(AUTH_NEWS_MODERATE, $NEWS_CONFIG['global_auth']),
		'L_REQUIRE' => $LANG['require'],
		'L_REQUIRE_PAGIN_NEWS' => sprintf($LANG['required_field'], $NEWS_LANG['nbr_news_p']),
		'L_REQUIRE_PAGIN_ARCH' => sprintf($LANG['required_field'], $NEWS_LANG['nbr_arch_p']),
		'L_REQUIRE_NBR_COL' => sprintf($LANG['required_field'], $NEWS_LANG['nbr_news_column']),
		'L_TITLE' => $LANG['title'],
		'L_TEXT' => $LANG['content'],
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_APROB' => $LANG['aprob'],
		'L_UNAPROB' => $LANG['unaprob'],
		'L_SUBMIT' => $LANG['submit'],
		'L_UPDATE' => $LANG['update'],
		'L_PREVIEW' => $LANG['preview'],
		'L_RESET' => $LANG['reset'],
		'L_EDITO_WHERE' => $NEWS_LANG['edito_where'],
		'L_CONFIG_NEWS' => $NEWS_LANG['configuration_news'],
		'L_NBR_NEWS_P' => $NEWS_LANG['nbr_news_p'],
		'L_NBR_ARCH_P' => $NEWS_LANG['nbr_arch_p'],
		'L_NBR_COLUMN_MAX' => $NEWS_LANG['nbr_news_column'],
		'L_ACTIV_PAGINATION' => $NEWS_LANG['activ_pagination'],
		'L_ACTIV_EDITO' => $NEWS_LANG['activ_edito'],
		'L_ACTIV_NEWS_BLOCK' => $NEWS_LANG['activ_news_block'],
		'L_ACTIV_COM_NEWS' => $NEWS_LANG['activ_com_n'],
		'L_ACTIV_ICON_NEWS' => $NEWS_LANG['activ_icon_n'],
		'L_DISPLAY_NEWS_AUTHOR' => $NEWS_LANG['display_news_author'],
		'L_DISPLAY_NEWS_DATE' => $NEWS_LANG['display_news_date'],
		'L_GLOBAL_AUTH' => $NEWS_LANG['global_auth'],
		'L_GLOBAL_AUTH_EXPLAIN' => $NEWS_LANG['global_auth_explain'],
		'L_AUTH_READ' => $NEWS_LANG['auth_read'],
		'L_AUTH_WRITE' => $NEWS_LANG['auth_write'],
		'L_AUTH_MODERATION' => $NEWS_LANG['auth_moderate'],
		'L_AUTH_CONTRIBUTION' => $NEWS_LANG['auth_contribute']
	));

	$tpl->parse(); // traitement du modele
}

require_once('../admin/admin_footer.php');

?>