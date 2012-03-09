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
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
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
require_once('news_constants.php');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');
load_module_lang('news'); //Chargement de la langue du module.
$news_config = NewsConfig::load();

if (!empty($_POST['submit']))
{
	$nbr_visible_news = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_NEWS . " WHERE visible = 1", __LINE__, __FILE__);

	$news_config->set_news_block_activated(retrieve(POST, 'news_block_activated', false));
	$news_config->set_comments_activated(retrieve(POST, 'comments_activated', false));
	$news_config->set_icon_activated(retrieve(POST, 'icon_activated', false));
	$news_config->set_edito_activated(retrieve(POST, 'edito_activated', false));
	$news_config->set_pagination_activated(retrieve(POST, 'pagination_activated', false));
	$news_config->set_display_date(retrieve(POST, 'display_date', false));
	$news_config->set_display_author(retrieve(POST, 'display_author', false));
	$news_config->set_news_pagination(retrieve(POST, 'news_pagination', 6));
	$news_config->set_archives_pagination(retrieve(POST, 'archives_pagination', 15));
	$news_config->set_nbr_columns(retrieve(POST, 'nbr_columns', 1));
	$news_config->set_nbr_visible_news($nbr_visible_news);
	$news_config->set_authorizations(Authorizations::build_auth_array_from_form(AUTH_NEWS_READ, AUTH_NEWS_CONTRIBUTE, AUTH_NEWS_WRITE, AUTH_NEWS_MODERATE));
	$news_config->set_edito_title(stripslashes(retrieve(POST, 'edito_title', '')));
	$news_config->set_edito_content(stripslashes(retrieve(POST, 'edito_content', '', TSTRING_PARSE)));
	
	NewsConfig::save();
    
	###### Régénération du cache des news #######
	$Cache->Generate_module_file('news');

	AppContext::get_response()->redirect(HOST . REWRITED_SCRIPT);
}
//Sinon on rempli le formulaire
else
{
	$tpl = new FileTemplate('news/admin_news_config.tpl');

	$Cache->load('news');

	$config_news_block_activated = $news_config->get_news_block_activated();
	$config_pagination_activated = $news_config->get_pagination_activated();
	$config_edito_activated = $news_config->get_edito_activated();
	$config_comments_activated = $news_config->get_comments_activated();
	$config_icon_activated = $news_config->get_icon_activated();
	$config_display_author = $news_config->get_display_author();
	$config_display_date = $news_config->get_display_date();
	$config_authorizations = $news_config->get_authorizations();

	// Chargement du menu de l'administration.
	require_once('admin_news_menu.php');

	$editor = AppContext::get_content_formatting_service()->get_default_editor();
	$editor->set_identifier('contents');

	$tpl->put_all(array(
		'ADMIN_MENU' => $admin_menu,
		'KERNEL_EDITOR' => $editor->display(),
		'NEWS_PAGINATION' => $news_config->get_news_pagination(),
		'ARCHIVES_PAGINATION' => $news_config->get_archives_pagination(),
		'TITLE' => $news_config->get_edito_title(),
		'CONTENTS' => FormatingHelper::unparse($news_config->get_edito_content()),
		'BLOCK_ENABLED' => ($config_news_block_activated == true) ? true : false,
		'BLOCK_DISABLED' => ($config_news_block_activated == false) ? true : false,
		'PAGIN_ENABLED' => ($config_pagination_activated == true) ? true : false,
		'PAGIN_DISABLED' => ($config_pagination_activated == false) ? true : false,
		'NBR_COLUMNS' => $news_config->get_nbr_columns(),
		'EDITO_ENABLED' => ($config_edito_activated == true) ? true : false,
		'EDITO_DISABLED' => ($config_edito_activated == false) ? true : false,
		'COM_ENABLED' => ($config_comments_activated == true) ? true : false,
		'COM_DISABLED' => ($config_comments_activated == false) ? true : false,
		'ICON_ENABLED' => ($config_icon_activated == true) ? true : false,
		'ICON_DISABLED' => ($config_icon_activated == false) ? true : false,
		'AUTHOR_ENABLED' => ($config_display_author == true) ? true : false,
		'AUTHOR_DISABLED' => ($config_display_author == false) ? true : false,
		'DATE_ENABLED' => ($config_display_date == true) ? true : false,
		'DATE_DISABLED' => ($config_display_date == false) ? true : false,
		'AUTH_READ' => Authorizations::generate_select(AUTH_NEWS_READ, $config_authorizations),
		'AUTH_WRITE' => Authorizations::generate_select(AUTH_NEWS_WRITE, $config_authorizations),
		'AUTH_CONTRIBUTION' => Authorizations::generate_select(AUTH_NEWS_CONTRIBUTE, $config_authorizations),
		'AUTH_MODERATION' => Authorizations::generate_select(AUTH_NEWS_MODERATE, $config_authorizations),
		'L_REQUIRE' => $LANG['require'],
		'L_REQUIRE_NEWS_PAGINATION' => sprintf($LANG['required_field'], $NEWS_LANG['nbr_news_p']),
		'L_REQUIRE_ARCHIVES_PAGINATION' => sprintf($LANG['required_field'], $NEWS_LANG['nbr_arch_p']),
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
		'L_ACTIV_PAGINATION' => $NEWS_LANG['pagination_activated'],
		'L_ACTIV_EDITO' => $NEWS_LANG['edito_activated'],
		'L_ACTIV_NEWS_BLOCK' => $NEWS_LANG['news_block_activated'],
		'L_ACTIV_COM_NEWS' => $NEWS_LANG['comments_activated_n'],
		'L_ACTIV_ICON_NEWS' => $NEWS_LANG['icon_activated_n'],
		'L_DISPLAY_NEWS_AUTHOR' => $NEWS_LANG['display_news_author'],
		'L_DISPLAY_NEWS_DATE' => $NEWS_LANG['display_news_date'],
		'L_GLOBAL_AUTH' => $NEWS_LANG['global_auth'],
		'L_GLOBAL_AUTH_EXPLAIN' => $NEWS_LANG['global_auth_explain'],
		'L_AUTH_READ' => $NEWS_LANG['auth_read'],
		'L_AUTH_WRITE' => $NEWS_LANG['auth_write'],
		'L_AUTH_MODERATION' => $NEWS_LANG['auth_moderate'],
		'L_AUTH_CONTRIBUTION' => $NEWS_LANG['auth_contribute']
	));

	$tpl->display(); // traitement du modele
}

require_once('../admin/admin_footer.php');

?>