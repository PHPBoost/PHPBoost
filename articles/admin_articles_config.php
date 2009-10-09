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
require_once('articles_constants.php');

load_module_lang('articles'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);

require_once('../admin/admin_header.php');
require_once('admin_articles_menu.php');

if (retrieve(POST,'valid',false))
{
	$Cache->load('articles');

	$config_articles = array(
		'nbr_articles_max' => retrieve(POST, 'nbr_articles_max', 10),
		'nbr_cat_max' => retrieve(POST, 'nbr_cat_max', 10),
		'nbr_column' => retrieve(POST, 'nbr_column', 2),
		'note_max' => max(1, retrieve(POST, 'note_max', 5)),
		'tab'=>retrieve(POST, 'tab', 0),
		'global_auth' => Authorizations::build_auth_array_from_form(AUTH_ARTICLES_READ, AUTH_ARTICLES_CONTRIBUTE, AUTH_ARTICLES_WRITE, AUTH_ARTICLES_MODERATE),
	);

	$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($config_articles)) . "' WHERE name = 'articles'", __LINE__, __FILE__);

	if ($CONFIG_ARTICLES['note_max'] != $config_articles['note_max'])
	$Sql->query_inject("UPDATE " .DB_TABLE_ARTICLES . " SET note = note * '" . ($config_articles['note_max']/$CONFIG_ARTICLES['note_max']) . "'", __LINE__, __FILE__);

	###### Régénération du cache des articles #######
	$Cache->Generate_module_file('articles');

	redirect(HOST . SCRIPT);
}
elseif (retrieve(POST,'articles_count',false)) //Recompte le nombre d'articles de chaque catégories
{
	$Cache->load('articles');

	$result = $Sql->query_while ("SELECT idcat, COUNT(*) as nbr_articles_visible
	FROM " . DB_TABLE_ARTICLES . "
	WHERE visible = 1 AND idcat > 0
	GROUP BY idcat", __LINE__, __FILE__);

	$info_cat = array();
	while ($row = $Sql->fetch_assoc($result))
	$info_cat[$row['idcat']]['visible'] = $row['nbr_articles_visible'];

	$Sql->query_close($result);

	$result = $Sql->query_while ("SELECT idcat, COUNT(*) as nbr_articles_unvisible
	FROM " . DB_TABLE_ARTICLES . " 
	WHERE visible = 0 AND idcat > 0
	GROUP BY idcat", __LINE__, __FILE__);

	while ($row = $Sql->fetch_assoc($result))
	$info_cat[$row['idcat']]['unvisible'] = $row['nbr_articles_unvisible'];

	$Sql->query_close($result);

	$result = $Sql->query_while("SELECT id, id_parent
	FROM " . DB_TABLE_ARTICLES_CAT, __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$nbr_articles_visible = 0;
		$nbr_articles_unvisible = 0;
		foreach ($info_cat as $key => $value)
		{
			if ($key == $row['id'])
			{
				$nbr_articles_visible += isset($info_cat[$key]['visible']) ? $info_cat[$key]['visible'] : 0;
				$nbr_articles_unvisible += isset($info_cat[$key]['unvisible']) ? $info_cat[$key]['unvisible'] : 0;
			}
		}
		$Sql->query_inject("UPDATE " . DB_TABLE_ARTICLES_CAT. " SET nbr_articles_visible = '" . $nbr_articles_visible . "', nbr_articles_unvisible = '" . $nbr_articles_unvisible . "' WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
	}
	$Sql->query_close($result);

	$Cache->Generate_module_file('articles');

	redirect(HOST . DIR . '/articles/admin_articles_config.php');
}
//Sinon on rempli le formulaire
else
{
	$tpl = new Template('articles/admin_articles_config.tpl');
	$tpl->assign_vars(array('ADMIN_MENU' => $admin_menu));

	$Cache->load('articles');

	$tpl->assign_vars(array(
		'NBR_ARTICLES_MAX' => !empty($CONFIG_ARTICLES['nbr_articles_max']) ? $CONFIG_ARTICLES['nbr_articles_max'] : '10',
		'NBR_CAT_MAX' => !empty($CONFIG_ARTICLES['nbr_cat_max']) ? $CONFIG_ARTICLES['nbr_cat_max'] : '10',
		'NBR_COLUMN' => !empty($CONFIG_ARTICLES['nbr_column']) ? $CONFIG_ARTICLES['nbr_column'] : '2',
		'NOTE_MAX' => !empty($CONFIG_ARTICLES['note_max']) ? $CONFIG_ARTICLES['note_max'] : '10',
		'AUTH_READ' => Authorizations::generate_select(AUTH_ARTICLES_READ, $CONFIG_ARTICLES['global_auth']),
		'AUTH_WRITE' => Authorizations::generate_select(AUTH_ARTICLES_WRITE, $CONFIG_ARTICLES['global_auth']),
		'AUTH_CONTRIBUTION' => Authorizations::generate_select(AUTH_ARTICLES_CONTRIBUTE, $CONFIG_ARTICLES['global_auth']),
		'AUTH_MODERATION' => Authorizations::generate_select(AUTH_ARTICLES_MODERATE, $CONFIG_ARTICLES['global_auth']),
		'TAB'=> $CONFIG_ARTICLES['tab'] == 1 ? 'checked="checked"' : '',
		'NO_TAB'=>  $CONFIG_ARTICLES['tab'] != 1 ? 'checked="checked"' : '',
		'L_REQUIRE' => $LANG['require'],		
		'L_NBR_ARTICLES_MAX' => $ARTICLES_LANG['nbr_articles_max'],
		'L_NBR_CAT_MAX' => $LANG['nbr_cat_max'],
		'L_NBR_COLUMN_MAX' => $LANG['nbr_column_max'],
		'L_NOTE_MAX' => $LANG['note_max'],
		'L_EXPLAIN_ARTICLES_COUNT' => $ARTICLES_LANG['explain_articles_count'],
		'L_RECOUNT' => $ARTICLES_LANG['recount'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset'],
		'L_GLOBAL_AUTH' => $ARTICLES_LANG['global_auth'],
		'L_GLOBAL_AUTH_EXPLAIN' => $ARTICLES_LANG['global_auth_explain'],
		'L_AUTH_READ' => $ARTICLES_LANG['auth_read'],
		'L_AUTH_WRITE' => $ARTICLES_LANG['auth_write'],
		'L_AUTH_MODERATION' => $ARTICLES_LANG['auth_moderate'],
		'L_AUTH_CONTRIBUTION' => $ARTICLES_LANG['auth_contribute'],
		'L_ARTICLES_CONFIG'=>$ARTICLES_LANG['configuration_articles'],
		'L_ENABLED'=>$LANG['enabled'],
		'L_DISABLED'=>$LANG['disabled'],
		'L_USE_TAB'=>$ARTICLES_LANG['use_tab']
	));
	$tpl->parse();
}

require_once('../admin/admin_footer.php');

?>