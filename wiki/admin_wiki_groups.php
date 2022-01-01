<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 1.5 - 2007 05 25
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../admin/admin_begin.php');

$lang = LangLoader::get_all_langs('wiki');

define('TITLE', $lang['form.authorizations']);
require_once('../admin/admin_header.php');

include_once('../wiki/wiki_auth.php');

$config = WikiConfig::load();
$request = AppContext::get_request();

$valid = $request->get_postvalue('valid', false);

$view = new FileTemplate('wiki/admin_wiki_groups.tpl');
$view->add_lang($lang);

// Execute on validation
if ($valid)
{
	// Save all authorizations
	$config->set_authorizations(Authorizations::build_auth_array_from_form(WIKI_CREATE_ARTICLE, WIKI_CREATE_CAT, WIKI_RESTORE_ARCHIVE, WIKI_DELETE_ARCHIVE, WIKI_EDIT, WIKI_DELETE, WIKI_RENAME, WIKI_REDIRECT, WIKI_MOVE, WIKI_STATUS, WIKI_COM, WIKI_RESTRICTION, WIKI_READ));

	WikiConfig::save();

	// Regeneration of the  categories cache
	WikiCategoriesCache::invalidate();

	HooksService::execute_hook_action('edit_config', 'wiki', array('title' => $lang['form.authorizations'], 'url' => Url::to_rel('/wiki/admin_wiki_groups.php')));

	$view->put('MESSAGE_HELPER', MessageHelper::display($lang['warning.success.config'], MessageHelper::SUCCESS, 4));
}

$view->put_all(array(
	'SELECT_CREATE_ARTICLE'  => Authorizations::generate_select(WIKI_CREATE_ARTICLE, $config->get_authorizations()),
	'SELECT_CREATE_CAT'      => Authorizations::generate_select(WIKI_CREATE_CAT, $config->get_authorizations()),
	'SELECT_RESTORE_ARCHIVE' => Authorizations::generate_select(WIKI_RESTORE_ARCHIVE, $config->get_authorizations()),
	'SELECT_DELETE_ARCHIVE'  => Authorizations::generate_select(WIKI_DELETE_ARCHIVE, $config->get_authorizations()),
	'SELECT_EDIT'            => Authorizations::generate_select(WIKI_EDIT, $config->get_authorizations()),
	'SELECT_DELETE'          => Authorizations::generate_select(WIKI_DELETE, $config->get_authorizations()),
	'SELECT_RENAME'          => Authorizations::generate_select(WIKI_RENAME, $config->get_authorizations()),
	'SELECT_REDIRECT'        => Authorizations::generate_select(WIKI_REDIRECT, $config->get_authorizations()),
	'SELECT_MOVE'            => Authorizations::generate_select(WIKI_MOVE, $config->get_authorizations()),
	'SELECT_STATUS'          => Authorizations::generate_select(WIKI_STATUS, $config->get_authorizations()),
	'SELECT_COM'             => Authorizations::generate_select(WIKI_COM, $config->get_authorizations()),
	'SELECT_RESTRICTION'     => Authorizations::generate_select(WIKI_RESTRICTION, $config->get_authorizations()),
	'SELECT_READ'            => Authorizations::generate_select(WIKI_READ, $config->get_authorizations()),
));

$view->display();

require_once('../admin/admin_footer.php');

?>
