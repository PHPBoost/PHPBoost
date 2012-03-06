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
	$nbr_news = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_NEWS . " WHERE visible = 1", __LINE__, __FILE__);

	$news_config->set_type(retrieve(POST, 'type', 0));
	$news_config->set_activ_com(retrieve(POST, 'activ_com', 0));
	$news_config->set_activ_icon(retrieve(POST, 'activ_icon', 0));
	$news_config->set_activ_edito(retrieve(POST, 'activ_edito', 0));
	$news_config->set_activ_pagin(retrieve(POST, 'activ_pagin', 0));
	$news_config->set_display_date(retrieve(POST, 'display_date', 0));
	$news_config->set_display_author(retrieve(POST, 'display_author', 0));
	$news_config->set_pagination_news(retrieve(POST, 'pagination_news', 6));
	$news_config->set_pagination_arch(retrieve(POST, 'pagination_arch', 15));
	$news_config->set_nbr_columns(retrieve(POST, 'nbr_column', 1));
	$news_config->set_nbr_news($nbr_news);
	$news_config->set_authorization(Authorizations::build_auth_array_from_form(AUTH_NEWS_READ, AUTH_NEWS_CONTRIBUTE, AUTH_NEWS_WRITE, AUTH_NEWS_MODERATE));
	$news_config->set_edito_title(stripslashes(retrieve(POST, 'edito_title', '')));
	$news_config->set_edito(stripslashes(retrieve(POST, 'edito', '', TSTRING_PARSE)));
	
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

	$config_pagination_news = $news_config->get_pagination_news();
	$config_pagination_arch = $news_config->get_pagination_arch();
	$config_edito_title = $news_config->get_edito_title();
	$config_edito = $news_config->get_edito();
	$config_type = $news_config->get_type();
	$config_activ_pagin = $news_config->get_activ_pagin();
	$config_nbr_columns = $news_config->get_nbr_columns();
	$config_activ_edito = $news_config->get_activ_edito();
	$config_activ_com = $news_config->get_activ_com();
	$config_activ_icon = $news_config->get_activ_icon();
	$config_display_author = $news_config->get_display_author();
	$config_display_date = $news_config->get_display_date();
	$config_auth = $news_config->get_authorization();

	// Chargement du menu de l'administration.
	require_once('admin_news_menu.php');

	$editor = AppContext::get_content_formatting_service()->get_default_editor();
	$editor->set_identifier('contents');

	$tpl->put_all(array(
		'ADMIN_MENU' => $admin_menu,
		'KERNEL_EDITOR' => $editor->display(),
		'PAGINATION' => !empty($config_pagination_news) ? $config_pagination_news : 6,
		'PAGINATION_ARCH' => !empty($config_pagination_arch) ? $config_pagination_arch : 15,
		'TITLE' => !empty($config_edito_title) ? $config_edito_title : '',
		'CONTENTS' => !empty($config_edito) ? FormatingHelper::unparse($config_edito) : '',
		'BLOCK_ENABLED' => ($config_type == '1') ? 1 : 0,
		'BLOCK_DISABLED' => ($config_type == '0') ? 1 : 0,
		'PAGIN_ENABLED' => ($config_activ_pagin == '1') ? 1 : 0,
		'PAGIN_DISABLED' => ($config_activ_pagin == '0') ? 1 : 0,
		'NBR_COLUMN' => !empty($config_nbr_columns) ? $config_nbr_columns : 1,
		'EDITO_ENABLED' => ($config_activ_edito == '1') ? 1 : 0,
		'EDITO_DISABLED' => ($config_activ_edito == '0') ? 1 : 0,
		'COM_ENABLED' => ($config_activ_com == '1') ? 1 : 0,
		'COM_DISABLED' => ($config_activ_com == '0') ? 1 : 0,
		'ICON_ENABLED' => ($config_activ_icon == '1') ? 1 : 0,
		'ICON_DISABLED' => ($config_activ_icon == '0') ? 1 : 0,
		'AUTHOR_ENABLED' => ($config_display_author == '1') ? 1 : 0,
		'AUTHOR_DISABLED' => ($config_display_author == '0') ? 1 : 0,
		'DATE_ENABLED' => ($config_display_date == '1') ? 1 : 0,
		'DATE_DISABLED' => ($config_display_date == '0') ? 1 : 0,
		'AUTH_READ' => Authorizations::generate_select(AUTH_NEWS_READ, $config_auth),
		'AUTH_WRITE' => Authorizations::generate_select(AUTH_NEWS_WRITE, $config_auth),
		'AUTH_CONTRIBUTION' => Authorizations::generate_select(AUTH_NEWS_CONTRIBUTE, $config_auth),
		'AUTH_MODERATION' => Authorizations::generate_select(AUTH_NEWS_MODERATE, $config_auth),
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

	$tpl->display(); // traitement du modele
}

require_once('../admin/admin_footer.php');

?>