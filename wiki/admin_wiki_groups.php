<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 11 23
 * @since       PHPBoost 1.5 - 2007 05 25
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

require_once('../admin/admin_begin.php');
load_module_lang('wiki'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

include_once('../wiki/wiki_auth.php');

$config = WikiConfig::load();
$request = AppContext::get_request();

$valid = $request->get_postvalue('valid', false);

$tpl = new FileTemplate('wiki/admin_wiki_groups.tpl');

//Si c'est confirmé on execute
if ($valid)
{
	//Génération du tableau des droits.
	$config->set_authorizations(Authorizations::build_auth_array_from_form(WIKI_CREATE_ARTICLE, WIKI_CREATE_CAT, WIKI_RESTORE_ARCHIVE, WIKI_DELETE_ARCHIVE, WIKI_EDIT, WIKI_DELETE, WIKI_RENAME, WIKI_REDIRECT, WIKI_MOVE, WIKI_STATUS, WIKI_COM, WIKI_RESTRICTION, WIKI_READ));

	WikiConfig::save();

	###### Regénération du cache des catégories #######
	WikiCategoriesCache::invalidate();

	$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 4));
}

$tpl->put_all(array(
	'SELECT_CREATE_ARTICLE' => Authorizations::generate_select(WIKI_CREATE_ARTICLE, $config->get_authorizations()),
	'SELECT_CREATE_CAT' => Authorizations::generate_select(WIKI_CREATE_CAT, $config->get_authorizations()),
	'SELECT_RESTORE_ARCHIVE' => Authorizations::generate_select(WIKI_RESTORE_ARCHIVE, $config->get_authorizations()),
	'SELECT_DELETE_ARCHIVE' => Authorizations::generate_select(WIKI_DELETE_ARCHIVE, $config->get_authorizations()),
	'SELECT_EDIT' => Authorizations::generate_select(WIKI_EDIT, $config->get_authorizations()),
	'SELECT_DELETE' => Authorizations::generate_select(WIKI_DELETE, $config->get_authorizations()),
	'SELECT_RENAME' => Authorizations::generate_select(WIKI_RENAME, $config->get_authorizations()),
	'SELECT_REDIRECT' => Authorizations::generate_select(WIKI_REDIRECT, $config->get_authorizations()),
	'SELECT_MOVE' => Authorizations::generate_select(WIKI_MOVE, $config->get_authorizations()),
	'SELECT_STATUS' => Authorizations::generate_select(WIKI_STATUS, $config->get_authorizations()),
	'SELECT_COM' => Authorizations::generate_select(WIKI_COM, $config->get_authorizations()),
	'SELECT_RESTRICTION' => Authorizations::generate_select(WIKI_RESTRICTION, $config->get_authorizations()),
	'SELECT_READ' => Authorizations::generate_select(WIKI_READ, $config->get_authorizations()),
	'L_WIKI_MANAGEMENT' => $LANG['wiki_management'],
	'L_WIKI_GROUPS' => $LANG['wiki_groups_config'],
	'L_CONFIG_WIKI' => $LANG['wiki_config'],
	'EXPLAIN_WIKI_GROUPS' => $LANG['explain_wiki_groups'],
	'L_UPDATE' => $LANG['update'],
	'L_RESET' => $LANG['reset'],
	'L_CREATE_ARTICLE' => $LANG['wiki_auth_create_article'],
	'L_CREATE_CAT' => $LANG['wiki_auth_create_cat'],
	'L_RESTORE_ARCHIVE' => $LANG['wiki_auth_restore_archive'],
	'L_DELETE_ARCHIVE' => $LANG['wiki_auth_delete_archive'],
	'L_EDIT' =>  $LANG['wiki_auth_edit'],
	'L_DELETE' =>  $LANG['wiki_auth_delete'],
	'L_RENAME' => $LANG['wiki_auth_rename'],
	'L_REDIRECT' => $LANG['wiki_auth_redirect'],
	'L_MOVE' => $LANG['wiki_auth_move'],
	'L_STATUS' => $LANG['wiki_auth_status'],
	'L_COM' => $LANG['wiki_auth_com'],
	'L_RESTRICTION' => $LANG['wiki_auth_restriction'],
	'L_READ' => $LANG['wiki_auth_read']
));

$tpl->display();

require_once('../admin/admin_footer.php');

?>
