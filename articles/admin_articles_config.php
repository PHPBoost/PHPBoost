<?php
/*##################################################
 *                               admin_articles_config.php
 *                            -------------------
 *   begin                : March 12, 2007
 *   copyright            : (C) 2007 Viarre Régis
 *   email                : crowkait@phpboost.com
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
require_once('articles_constants.php');

load_module_lang('articles'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);

require_once('../admin/admin_header.php');
require_once('admin_articles_menu.php');

if (retrieve(POST,'valid',false))
{
	$Cache->load('articles');
	
	switch (retrieve(POST, 'mini_type', 'date',TSTRING))
	{
		case 'note' :
			$mini['type'] = 'note';		
			break;
		case 'com' :
			$mini['type'] = 'com';
			break;
		case 'date' :
			$mini['type'] = 'date';
		case 'view' :
			$mini['type']= 'view';
			break;
		default :
			$mini['type'] = 'date';
			break;
	}
	
	$config_articles = array(
		'nbr_articles_max' => retrieve(POST, 'nbr_articles_max', 10),
		'nbr_cat_max' => retrieve(POST, 'nbr_cat_max', 10),
		'nbr_column' => retrieve(POST, 'nbr_column', 2),
		'note_max' => max(1, retrieve(POST, 'note_max', 5)),
		'global_auth' => Authorizations::build_auth_array_from_form(AUTH_ARTICLES_READ, AUTH_ARTICLES_CONTRIBUTE, AUTH_ARTICLES_WRITE, AUTH_ARTICLES_MODERATE)
	);
	
	$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($config_articles)) . "' WHERE name = 'articles'", __LINE__, __FILE__);

	if ($CONFIG_ARTICLES['note_max'] != $config_articles['note_max'])
		NotationService::update_notation_scale('articles', $CONFIG_ARTICLES['note_max'], $config_articles['note_max']);
			
	###### Régénération du cache des articles #######
	$Cache->Generate_module_file('articles');
	AppContext::get_response()->redirect(HOST . REWRITED_SCRIPT);
}
//Sinon on rempli le formulaire
else
{
	$tpl = new FileTemplate('articles/admin_articles_config.tpl');
	$tpl->put_all(array('ADMIN_MENU' => $admin_menu));

	$Cache->load('articles');
		
	$array_ranks =
		array(
			'-1' => $LANG['guest'],
			'0' => $LANG['member'],
			'1' => $LANG['modo'],
			'2' => $LANG['admin']
		);

	$tpl->put_all(array(
		'NBR_ARTICLES_MAX' => !empty($CONFIG_ARTICLES['nbr_articles_max']) ? $CONFIG_ARTICLES['nbr_articles_max'] : '10',
		'NBR_CAT_MAX' => !empty($CONFIG_ARTICLES['nbr_cat_max']) ? $CONFIG_ARTICLES['nbr_cat_max'] : '10',
		'NBR_COLUMN' => !empty($CONFIG_ARTICLES['nbr_column']) ? $CONFIG_ARTICLES['nbr_column'] : '2',
		'NOTE_MAX' => !empty($CONFIG_ARTICLES['note_max']) ? $CONFIG_ARTICLES['note_max'] : '10',
		'AUTH_READ' => Authorizations::generate_select(AUTH_ARTICLES_READ, $CONFIG_ARTICLES['global_auth']),
		'L_REQUIRE' => $LANG['require'],	
		'L_NBR_CAT_MAX' => $LANG['nbr_cat_max'],
		'L_NBR_COLUMN_MAX' => $LANG['nbr_column_max'],
		'L_NOTE_MAX' => $LANG['note_max'],		
		'L_NBR_ARTICLES_MAX' => $ARTICLES_LANG['nbr_articles_max'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset'],
		'L_EXPLAIN_ARTICLES_COUNT' => $ARTICLES_LANG['explain_articles_count'],
		'L_RECOUNT' => $ARTICLES_LANG['recount'],
		'L_GLOBAL_AUTH' => $ARTICLES_LANG['global_auth'],
		'L_GLOBAL_AUTH_EXPLAIN' => $ARTICLES_LANG['global_auth_explain'],
		'L_AUTH_READ' => $ARTICLES_LANG['auth_read'],
		'L_AUTH_WRITE' => $ARTICLES_LANG['auth_write'],
		'L_AUTH_MODERATION' => $ARTICLES_LANG['auth_moderate'],
		'L_AUTH_CONTRIBUTION' => $ARTICLES_LANG['auth_contribute'],
		'L_ARTICLES_CONFIG'=>$ARTICLES_LANG['configuration_articles'],
		'L_ARTICLES_BEST_NOTE'=>$ARTICLES_LANG['articles_best_note'],
		'L_ARTICLES_MORE_COM' => $ARTICLES_LANG['articles_more_com'],
		'L_ARTICLES_BY_DATE' => $ARTICLES_LANG['articles_by_date'],
		'L_ARTICLES_MOST_POPULAR' =>$ARTICLES_LANG['articles_most_popular'],
	));
	
	$array_ranks =
	array(
		'0' => $LANG['member'],
		'1' => $LANG['modo'],
		'2' => $LANG['admin']
	);
			
	$tpl->put_all(array(
			'AUTH_WRITE' => Authorizations::generate_select(AUTH_ARTICLES_WRITE,$CONFIG_ARTICLES['global_auth']),
			'AUTH_CONTRIBUTION' => Authorizations::generate_select(AUTH_ARTICLES_CONTRIBUTE,$CONFIG_ARTICLES['global_auth']),
			'AUTH_MODERATION' => Authorizations::generate_select(AUTH_ARTICLES_MODERATE,$CONFIG_ARTICLES['global_auth']),
		));
		
	$tpl->display();
}

require_once('../admin/admin_footer.php');

?>